<?php

namespace App\Models;

use CodeIgniter\Model;

class PlotsModel extends Model
{
  protected $table = 'plots';
  protected $primaryKey       = 'id';
  protected $useAutoIncrement = true;
  protected $returnType       = 'object';
  protected $allowedFields    = ['plot_number', 'side', 'user_id'];
}
