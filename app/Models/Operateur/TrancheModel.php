<?php

namespace App\Models\Operateur;

use CodeIgniter\Model;

class TrancheModel extends Model
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

    protected $validationRules      = [
        'type_operation_id' => 'required|integer',
        'montant_min'       => 'required|numeric',
        'montant_max'       => 'required|numeric',
        'frais'             => 'required|numeric',
    ];
    protected $validationMessages   = [
        'type_operation_id' => [
            'required' => 'Le type d\'opération est obligatoire.',
            'integer'  => 'Le type d\'opération doit être un entier.',
        ],
        'montant_min' => [
            'required' => 'Le montant minimum est obligatoire.',
            'numeric'  => 'Le montant minimum doit être un nombre.',
        ],
        'montant_max' => [
            'required' => 'Le montant maximum est obligatoire.',
            'numeric'  => 'Le montant maximum doit être un nombre.',
        ],
        'frais' => [
            'required' => 'Les frais sont obligatoires.',
            'numeric'  => 'Les frais doivent être un nombre.',
        ],
    ];
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

    public function getByTypeOperation(int $typeId): array
    {
        return $this->where('type_operation_id', $typeId)
                     ->orderBy('montant_min', 'ASC')
                     ->findAll();
    }
}
