<?php

namespace App\Models;

use CodeIgniter\Model;

class Dependents_PlotsModel extends Model
{
  protected $table = 'dependents_plots';
  protected $allowedFields = ['dependent_id', 'plot_id'];
}
