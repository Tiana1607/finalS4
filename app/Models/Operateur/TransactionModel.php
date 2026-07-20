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
     * Gains internes : retraits + transferts sans opérateur externe (nos frais).
     */
    public function getGainsInternes(): array
    {
        return $this->db->table('transactions t')
            ->select('t.type_operation_id, to2.libelle, SUM(t.frais) AS total_frais, COUNT(*) AS nombre')
            ->join('types_operation to2', 'to2.id = t.type_operation_id', 'left')
            ->whereIn('t.type_operation_id', [2, 3])
            ->where('t.operateur_destinataire_id', null)
            ->groupBy('t.type_operation_id')
            ->get()
            ->getResultArray();
    }

    /**
     * Gains externes : commissions dues aux opérateurs externes (sur transferts).
     */
    public function getGainsExternes(): array
    {
        return $this->db->table('transactions t')
            ->select('op.nom AS operateur_nom, SUM(t.commission_externe) AS total_commission, COUNT(*) AS nombre')
            ->join('operateurs op', 'op.id = t.operateur_destinataire_id', 'left')
            ->where('t.type_operation_id', 3)
            ->where('t.operateur_destinataire_id IS NOT NULL', null, false)
            ->groupBy('t.operateur_destinataire_id')
            ->get()
            ->getResultArray();
    }

    /**
     * Montant total à envoyer par opérateur externe (SUM du montant des transferts vers chaque opérateur).
     */
    public function getMontantsParOperateur(): array
    {
        return $this->db->table('transactions t')
            ->select('op.id AS operateur_id, op.nom AS operateur_nom, SUM(t.montant) AS montant_total, COUNT(*) AS nb_transferts')
            ->join('operateurs op', 'op.id = t.operateur_destinataire_id', 'left')
            ->where('t.type_operation_id', 3)
            ->where('t.operateur_destinataire_id IS NOT NULL', null, false)
            ->groupBy('t.operateur_destinataire_id')
            ->orderBy('montant_total', 'DESC')
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

    
    //  Les transactions d'un client donné (émetteur ou destinataire).
     
    public function getTransactionsByClient(int $clientId): array
    {
        $builder = $this->db->table('transactions t');
        $builder->select('
            t.id,
            t.montant,
            t.frais,
            t.type_operation_id,
            t.date_operation,
            to2.libelle        AS type_libelle,
            c_client.telephone AS client_tel,
            c_dest.telephone   AS destinataire_tel
        ');
        $builder->join('types_operation to2', 'to2.id = t.type_operation_id', 'left');
        $builder->join('clients c_client', 'c_client.id = t.client_id', 'left');
        $builder->join('clients c_dest', 'c_dest.id = t.destinataire_id', 'left');
        $builder->groupStart();
            $builder->where('t.client_id', $clientId);
            $builder->orWhere('t.destinataire_id', $clientId);
        $builder->groupEnd();
        $builder->orderBy('t.date_operation', 'DESC');

        return $builder->get()->getResultArray();
    }
}
