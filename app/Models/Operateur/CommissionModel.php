<?php

namespace App\Models\Operateur;

use CodeIgniter\Model;

class CommissionModel extends Model
{
    protected $table            = 'commissions_externes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['operateur_id', 'pourcentage', 'date_creation'];

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
        'operateur_id' => 'required|integer',
        'pourcentage'  => 'required|decimal',
    ];
    protected $validationMessages   = [
        'operateur_id' => [
            'required' => 'L\'opérateur est obligatoire.',
            'integer'  => 'L\'opérateur doit être valide.',
        ],
        'pourcentage' => [
            'required' => 'Le pourcentage est obligatoire.',
            'decimal'  => 'Le pourcentage doit être un nombre valide.',
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

    /**
     * Liste les commissions avec le nom de l'opérateur.
     */
    public function getAllWithOperateur(): array
    {
        return $this->select('commissions_externes.*, operateurs.nom as operateur_nom')
            ->join('operateurs', 'operateurs.id = commissions_externes.operateur_id')
            ->orderBy('operateurs.nom', 'ASC')
            ->findAll();
    }

    /**
     * Récupère la commission d'un opérateur donné.
     */
    public function getByOperateur(int $operateurId): ?array
    {
        return $this->where('operateur_id', $operateurId)->first();
    }

    /**
     * Retourne le pourcentage de commission d'un opérateur (0 si non configuré).
     */
    public function getPourcentageByOperateur(int $operateurId): float
    {
        $row = $this->where('operateur_id', $operateurId)->first();
        return $row ? (float) $row['pourcentage'] : 0.0;
    }
}
