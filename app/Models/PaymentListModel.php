<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentListModel extends Model
{
  protected $table         = 'payment_lists';
  protected $primaryKey    = 'id';
  protected $returnType    = 'object';
  protected $allowedFields = ['name', 'month', 'year'];
  protected $useTimestamps = true;
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';
}
