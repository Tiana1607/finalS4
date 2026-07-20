<?php

namespace App\Models\Client;

use CodeIgniter\Model;

class TransactionsModel extends Model
{
    protected $table            = 'transactions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['client_id', 'destinataire_id', 'type_operation_id', 'montant', 'frais', 'date_operation'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Récupère l'historique complet d'un client (opérations sortantes + entrantes).
     */
    public function getHistorique(int $clientId): array
    {
        $builder = $this->db->table('transactions t');
        $builder->select('t.*, to2.libelle as type_libelle, c_dest.telephone as destinataire_tel');
        $builder->join('types_operation to2', 'to2.id = t.type_operation_id', 'left');
        $builder->join('clients c_dest', 'c_dest.id = t.destinataire_id', 'left');
        $builder->where('t.client_id', $clientId);
        $builder->orderBy('t.date_operation', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Récupère l'historique avec filtres optionnels et tri.
     */
    public function getHistoriqueFiltre(int $clientId, array $filtres = [], string $triDate = 'DESC'): array
    {
        $builder = $this->db->table('transactions t');
        $builder->select('t.*, to2.libelle as type_libelle, c_dest.telephone as destinataire_tel');
        $builder->join('types_operation to2', 'to2.id = t.type_operation_id', 'left');
        $builder->join('clients c_dest', 'c_dest.id = t.destinataire_id', 'left');
        $builder->where('t.client_id', $clientId);

        // Filtre par type d'opération
        if (!empty($filtres['type_operation'])) {
            $builder->where('t.type_operation_id', (int) $filtres['type_operation']);
        }

        // Filtre par date début
        if (!empty($filtres['date_debut'])) {
            $builder->where('t.date_operation >=', $filtres['date_debut'] . ' 00:00:00');
        }

        // Filtre par date fin
        if (!empty($filtres['date_fin'])) {
            $builder->where('t.date_operation <=', $filtres['date_fin'] . ' 23:59:59');
        }

        // Filtre par montant minimum
        if (!empty($filtres['montant_min'])) {
            $builder->where('t.montant >=', (float) $filtres['montant_min']);
        }

        // Filtre par montant maximum
        if (!empty($filtres['montant_max'])) {
            $builder->where('t.montant <=', (float) $filtres['montant_max']);
        }

        $ordre = strtoupper($triDate) === 'ASC' ? 'ASC' : 'DESC';
        $builder->orderBy('t.date_operation', $ordre);

        return $builder->get()->getResultArray();
    }
}
