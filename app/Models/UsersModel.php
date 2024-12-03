<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
  protected $table            = 'users';
  protected $primaryKey       = 'id';
  protected $useAutoIncrement = true;
  protected $returnType       = 'object';
  protected $allowedFields    = ['name', 'image', 'id_code', 'email', 'birth_date', 'phone', 'cpf', 'address'];
  protected $afterFind = ['formatUserData'];

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

  public function generateIdCode($cpf, $name)
  {
    $name = trim($name);
    $name = strtoupper($name);
    $cpfNumbers = preg_replace('/\D/', '', $cpf);
    $cpfMiddleDigits = substr($cpfNumbers, 2, 6); // Pega da posição 2 (índice começa em 0) e pega 6 dígitos
    $firstTwoLetters = substr($name, 0, 2);
    $lastTwoLetters = substr($name, -2);
    $cpfDigitsArray = str_split($cpfMiddleDigits);
    $lettersArray = [
      $firstTwoLetters[0],
      $firstTwoLetters[1],
      $lastTwoLetters[0],
      $lastTwoLetters[1]
    ];

    $idCode = '';
    $idCode .= $cpfDigitsArray[0];
    $idCode .= $cpfDigitsArray[1];
    $idCode .= $lettersArray[0];
    $idCode .= $cpfDigitsArray[2];
    $idCode .= $lettersArray[1];
    $idCode .= $cpfDigitsArray[3];
    $idCode .= $cpfDigitsArray[4];
    $idCode .= $lettersArray[2];
    $idCode .= $cpfDigitsArray[5];
    $idCode .= $lettersArray[3];

    return $idCode;
  }

  protected function formatUserData($data)
  {

    if (isset($data['data']) && is_array($data['data'])) {
      // Caso seja uma lista de usuários
      foreach ($data['data'] as &$user) {
        $user->phone = $this->formatPhone($user->phone);
        $user->cpf = $this->formatCPF($user->cpf);
        $user->birth_date = $this->formatDateBar($user->birth_date);
      }
    } elseif (isset($data['data']) && is_object($data['data'])) {
      // Caso seja apenas um único usuário
      $data['data']->phone = $this->formatPhone($data['data']->phone);
      $data['data']->cpf = $this->formatCPF($data['data']->cpf);
      $data['data']->birth_date = $this->formatDateBar($data['data']->birth_date);
    }

    return $data;
  }

  // Função para formatar o telefone
  public function formatPhone($phone)
  {
    if (strlen($phone) == 11) {
      return sprintf(
        '(%s) %s %s-%s',
        substr($phone, 0, 2),
        substr($phone, 2, 1),
        substr($phone, 3, 4),
        substr($phone, 7)
      );
    }
    return sprintf(
      '(%s) %s-%s',
      substr($phone, 0, 2),
      substr($phone, 2, 4),
      substr($phone, 6)
    );
  }

  // Função para formatar o CPF
  public function formatCPF($cpf)
  {
    return sprintf(
      '%s.%s.%s-%s',
      substr($cpf, 0, 3),
      substr($cpf, 3, 3),
      substr($cpf, 6, 3),
      substr($cpf, 9, 2)
    );
  }
}
