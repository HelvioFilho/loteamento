<?php

namespace App\Models;

use CodeIgniter\Model;

class UserAdminModel extends Model
{
  protected $table = 'user_admin';
  protected $primaryKey = 'id';
  protected $useAutoIncrement = true;
  protected $returnType       = 'object';
  protected $allowedFields = [
    'name',
    'email',
    'access_type',
    'password',
    'code',
    'code_expires_at',
    'updated_at',
    'created_at',
    'last_login'
  ];

  protected $useTimestamps = true;
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';

  public function setRememberMeCookie($user)
  {
    helper('cookie');
    // Define um cookie com o token para lembrar o usuário
    $cookieValue = json_encode(['email' => $user['email'], 'password' => $user['password']]);
    set_cookie('remember_me', $cookieValue, 30 * 24 * 60 * 60); // Cookie de 30 dias
  }

  public function checkRememberMeCookie()
  {
    helper('cookie');
    $rememberMeCookie = get_cookie('remember_me');

    if ($rememberMeCookie) {
      $rememberData = json_decode($rememberMeCookie, true);

      if (!empty($rememberData['email']) && !empty($rememberData['password'])) {

        $user = $this->where('email', $rememberData['email'])->first();

        if ($user && $user['password'] === $rememberData['password']) {
          // Autentica o usuário automaticamente
          session()->set([
            'user_id' => $user['id'],
            'user_name' => $user['name'],
            'user_email' => $user['email'],
            'isLoggedIn' => true,
            'access_type' => $user['access_type']
          ]);
          return true;
        }
      }
    }

    return false;
  }
}
