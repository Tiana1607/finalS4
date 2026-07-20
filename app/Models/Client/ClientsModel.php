<?php

namespace App\Models\Client;

use CodeIgniter\Model;

class ClientsModel extends Model
{
    protected $table = 'clients';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['telephone', 'solde', 'date_creation'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];


    public function findByTelephone($telephone)
    {
        return $this->where('telephone', $telephone)->first();
    }

    public function findOrCreateByTelephone($telephone)
    {
        $client = $this->findByTelephone($telephone);

        if ($client !== null) {
            return $client;
        }

        $id = $this->insert([
            'telephone' => $telephone,
            'solde' => 0,
            'date_creation' => date('Y-m-d H:i:s'),
        ]);

        return $this->find($id);
    }

}
