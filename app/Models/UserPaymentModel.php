<?php

namespace App\Models;

use CodeIgniter\Model;

class UserPaymentModel extends Model
{
  protected $table = 'user_payments';
  protected $primaryKey = 'id';
  protected $returnType    = 'object';
  protected $allowedFields = ['user_id', 'payment_list_id', 'paid'];
  protected $useTimestamps = false;
}
