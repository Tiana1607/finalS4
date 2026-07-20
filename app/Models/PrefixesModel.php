<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixesModel extends Model
{
    protected $table            = 'prefixes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['prefixe', 'operateur_id', 'date_creation'];

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
     * Vérifie si le préfixe du numéro existe dans la table prefixes.
     */
    public function prefixeExiste(string $telephone): bool
    {
        $prefixe = substr($telephone, 0, 3);

        return $this->where('prefixe', $prefixe)->countAllResults() > 0;
    }

    public function prefixeEstANous(string $telephone): bool
    {
        $prefixe = substr($telephone, 0, 3);
        $row = $this->where('prefixe', $prefixe)->first();

        if (!$row || !isset($row['operateur_id'])) {
            return false;
        }

        $operateur = $this->db->table('operateurs')
            ->where('id', $row['operateur_id'])
            ->where('est_nous', 1)
            ->get()->getRowArray();

        return $operateur !== null;
    }

    public function getOperateurIdByTelephone(string $telephone): ?int
    {
        $prefixe = substr($telephone, 0, 3);
        $row = $this->where('prefixe', $prefixe)->first();

        return isset($row['operateur_id']) ? (int) $row['operateur_id'] : null;
    }
}
