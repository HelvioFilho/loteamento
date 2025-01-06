<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\DependentsModel;

class Dependents extends BaseController
{
  public function index()
  {
    $usersModel = new UsersModel();
    $users = $usersModel->orderBy('name', 'ASC')->findAll();

    $headerData = [
      'title' => 'Dependentes',
      'users' => $users,
    ];

    return view('dependents', $headerData);
  }

  public function createPageDependent($userId)
  {
    $usersModel = new UsersModel();
    $dependentsModel = new DependentsModel();

    // Verifica se o usuário existe
    $user = $usersModel->find($userId);
    if (!$user) {
      return redirect()->to('/dependentes')->with('error', 'Usuário não encontrado.');
    }

    // Busca os dependentes desse usuário específico
    $dependents = $dependentsModel->where('user_id', $userId)->findAll();

    $headerData = [
      'title' => 'Gerenciar Dependentes de ' . $user->name,
      'user' => $user,
      'dependents' => $dependents,
    ];

    return view('dependentsManage', $headerData);
  }

  public function createDependent($id)
  {

    $dependentsModel = new DependentsModel();
    $usersModel = new UsersModel();

    // Pega os dados do formulário
    $name = $this->request->getPost('name');
    $birthDate = $this->request->getPost('birth_date');

    // Valida os dados
    if (empty($name)) {
      return redirect()->to('/dependentes/gerenciar/' . $id)->with('message', 'Preencha todos os campos');
    }

    try {
      // Insere os dados no banco de dados
      $dependentsModel->insert([
        'user_id' => $id,
        'name' => $name,
        'birth_date' => $birthDate ? $usersModel->formatDate($birthDate) ?? '' : null
      ]);

      return redirect()->to('/dependentes/gerenciar/' . $id)->with('message', 'Dependente cadastrado com sucesso.');
    } catch (\Exception $e) {
      return redirect()->to('/dependentes/gerenciar/' . $id)->with('message', 'Erro ao cadastrar o dependente.');
    }
  }

  public function deleteDependent($dependentId, $return)
  {
    $dependentsModel = new DependentsModel();

    try {
      $dependentsModel->delete($dependentId);
      return redirect()->to('/dependentes/gerenciar/' . $return)->with('message', 'Dependente excluído com sucesso.');
    } catch (\Exception $e) {
      return redirect()->to('/dependentes/gerenciar/' . $return)->with('message', 'Erro ao excluir o dependente.');
    }
  }

  public function updateDependent($id)
  {
    $dependentsModel = new DependentsModel();
    $usersModel = new UsersModel();
    // Obtém os dados do formulário
    $name = $this->request->getPost('name');
    $birthDate = $this->request->getPost('birth_date');

    // Valida os dados
    if (empty($name)) {
      return $this->response->setJSON(['success' => false, 'message' => 'Preencha todos os campos.']);
    }

    try {
      // Atualiza os dados do dependente
      $dependentsModel->update($id, [
        'name' => $name,
        'birth_date' => $birthDate ? $usersModel->formatDate($birthDate) ?? '' : null
      ]);

      return $this->response->setJSON(['success' => true, 'message' => 'Dependente atualizado com sucesso.']);
    } catch (\Exception $e) {
      return $this->response->setJSON(['success' => false, 'message' => 'Erro ao atualizar o dependente.']);
    }
  }
}
