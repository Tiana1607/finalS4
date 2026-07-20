<?php

namespace App\Models\Operateur;

use CodeIgniter\Model;

class PrefixeModel extends Model
{
    protected $table         = 'prefixes';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['prefixe', 'operateur_id'];
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    public function getNosPrefixes()
    {
        return $this->select('prefixes.*, operateurs.nom as operateur_nom')
            ->join('operateurs', 'operateurs.id = prefixes.operateur_id')
            ->where('operateurs.est_nous', 1)
            ->orderBy('prefixes.prefixe', 'ASC')
            ->findAll();
    }

    public function getAutresPrefixes()
    {
        return $this->select('prefixes.*, operateurs.nom as operateur_nom')
            ->join('operateurs', 'operateurs.id = prefixes.operateur_id')
            ->where('operateurs.est_nous', 0)
            ->orderBy('operateurs.nom', 'ASC')
            ->orderBy('prefixes.prefixe', 'ASC')
            ->findAll();
    }
}