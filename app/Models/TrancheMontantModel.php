<?php

namespace App\Models;

use CodeIgniter\Model;

class TrancheMontantModel extends Model
{
    protected $table            = 'tranche_montant';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['type_operation_id', 'montant_min', 'montant_max', 'frais'];

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
     * Cherche les frais applicables pour un type d'opération et un montant donné.
     * Retourne les frais ou 0 si aucune tranche ne correspond.
     */
    public function getFrais($typeOperationId, $montant)
    {
        $tranche = $this->where('type_operation_id', $typeOperationId)
            ->where('montant_min <=', $montant)
            ->where('montant_max >=', $montant)
            ->first();

        return $tranche ? $tranche['frais'] : 0;
    }
}
