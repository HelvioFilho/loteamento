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

  public function formatDate($date)
  {
    if (!$date) {
      return null;
    }

    $dateTime = \DateTime::createFromFormat('d/m/Y', $date);
    if ($dateTime) {
      return $dateTime->format('Y-m-d');
    }
    return null;
  }

  public function formatDateBar($date)
  {
    if (!$date) {
      return null;
    }

    $dateTime = \DateTime::createFromFormat('Y-m-d', $date);
    if ($dateTime) {
      return $dateTime->format('d/m/Y');
    }
    return null;
  }

  protected function formatDependentData($data)
  {
    if (isset($data['data']) && is_array($data['data'])) {
      foreach ($data['data'] as &$dependent) {
        $dependent->birth_date = $dependent->birth_date ? $this->formatDateBar($dependent->birth_date) : null;
      }
    } elseif (isset($data['data']) && is_object($data['data'])) {
      $data['data']->birth_date = $data['data']->birth_date ? $this->formatDateBar($data['data']->birth_date) : null;
    }

    return $data;
  }
}
