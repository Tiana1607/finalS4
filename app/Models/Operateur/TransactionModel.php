<?php

namespace App\Models\Operateur;

use CodeIgniter\Model;

class TransactionModel extends Model
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

    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

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
     * Gains totaux groupés par type d'opération (retrait + transfert).
     */
    public function getGainsParType(): array
    {
        return $this->db->table('transactions t')
            ->select('t.type_operation_id, to2.libelle, SUM(t.frais) AS total_frais, COUNT(*) AS nombre')
            ->join('types_operation to2', 'to2.id = t.type_operation_id', 'left')
            ->whereIn('t.type_operation_id', [2, 3])
            ->groupBy('t.type_operation_id')
            ->get()
            ->getResultArray();
    }

    /**
     * Transactions détaillées d'un type donné (retrait ou transfert), avec infos client.
     */
    public function getTransactionsByType(int $typeId): array
    {
        $builder = $this->db->table('transactions t');
        $builder->select('
            t.id,
            t.montant,
            t.frais,
            t.date_operation,
            c_client.telephone AS client_tel,
            c_dest.telephone   AS destinataire_tel,
            to2.libelle        AS type_libelle
        ');
        $builder->join('types_operation to2', 'to2.id = t.type_operation_id', 'left');
        $builder->join('clients c_client', 'c_client.id = t.client_id', 'left');
        $builder->join('clients c_dest', 'c_dest.id = t.destinataire_id', 'left');
        $builder->where('t.type_operation_id', $typeId);
        $builder->orderBy('t.date_operation', 'DESC');

        return $builder->get()->getResultArray();
    }
}
