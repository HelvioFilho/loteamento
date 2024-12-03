<?php

namespace App\Models;

use CodeIgniter\Model;

class DependentsModel extends Model
{
  protected $table = 'dependents';
  protected $primaryKey       = 'id';
  protected $useAutoIncrement = true;
  protected $returnType       = 'object';
  protected $allowedFields = ['name', 'birth_date', 'user_id'];
  protected $afterFind = ['formatDependentData'];

  protected function formatDependentData($data)
  {
    if (isset($data['data']) && is_array($data['data'])) {
      foreach ($data['data'] as &$dependent) {
        $dependent->birth_date = date('d/m/Y', strtotime($dependent->birth_date));
      }
    } elseif (isset($data['data']) && is_object($data['data'])) {
      $data['data']->birth_date = date('d/m/Y', strtotime($data['data']->birth_date));
    }

    return $data;
  }
}
