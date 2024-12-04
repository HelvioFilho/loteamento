<?php

namespace App\Controllers;

use App\Models\PlotsModel;
use App\Models\UsersModel;

class Plots extends BaseController
{
  public function index()
  {
    $usersModel = new UsersModel();
    $users = $usersModel->orderBy('id', 'DESC')->findAll();

    $headerData = [
      'title' => 'Lotes',
      'users' => $users,
    ];

    return view('plots', $headerData);
  }

  public function createPagePlot($userId)
  {
    $usersModel = new UsersModel();
    $plotsModel = new PlotsModel();

    // Verifica se o usuário existe
    $user = $usersModel->find($userId);
    if (!$user) {
      return redirect()->to('/lotes')->with('error', 'Usuário não encontrado.');
    }

    // Busca os dependentes desse usuário específico
    $plots = $plotsModel->where('user_id', $userId)->findAll();

    $headerData = [
      'title' => 'Gerenciar Lotes de ' . $user->name,
      'user' => $user,
      'plots' => $plots,
    ];

    return view('plotsManage', $headerData);
  }

  public function createPlot($id)
  {

    $plotsModel = new PlotsModel();
    $usersModel = new UsersModel();

    // Pega os dados do formulário
    $plot = $this->request->getPost('plot');
    $side = $this->request->getPost('side');

    $existingPlot = $plotsModel->verifyExistingPlot($plot, $side);

    if ($existingPlot) {
      $user = $usersModel->find($existingPlot->user_id);
      return redirect()->to('/lotes/gerenciar/' . $id)->with('message', 'O lote ' . $plot . ' - ' . $side . '  já foi cadastrado para ' . $user->name . ' verifique as informações e tente novamente.');
    }


    try {
      // Insere os dados no banco de dados
      $plotsModel->insert([
        'user_id' => $id,
        'plot_number' => $plot,
        'side' => $side
      ]);

      return redirect()->to('/lotes/gerenciar/' . $id)->with('message', 'Lote cadastrado com sucesso.');
    } catch (\Exception $e) {
      return redirect()->to('/lotes/gerenciar/' . $id)->with('message', 'Erro ao cadastrar o lote.');
    }
  }

  public function deletePlot($plotId, $return)
  {
    $plotsModel = new PlotsModel();

    try {
      $plotsModel->delete($plotId);
      return redirect()->to('/lotes/gerenciar/' . $return)->with('message', 'Lote excluído com sucesso.');
    } catch (\Exception $e) {
      return redirect()->to('/lotes/gerenciar/' . $return)->with('message', 'Erro ao excluir o lote.');
    }
  }

  public function updatePlot($id)
  {
    $plotsModel = new PlotsModel();
    $usersModel = new UsersModel();

    // Obtém os dados do formulário
    $plot = $this->request->getPost('plot');
    $side = $this->request->getPost('side');

    $existingPlot = $plotsModel->verifyExistingPlot($plot, $side);

    if ($existingPlot) {
      $user = $usersModel->find($existingPlot->user_id);
      return $this->response->setJSON(['error' => true, 'message' => 'O lote "' . $plot . ' - ' . $side . '"   já foi cadastrado para ' . $user->name . ' verifique as informações e tente novamente.']);
    }

    try {
      // Atualiza os dados do dependente
      $plotsModel->update($id, [
        'plot_number' => $plot,
        'side' => $side
      ]);

      return $this->response->setJSON(['success' => true, 'message' => 'Lote atualizado com sucesso.']);
    } catch (\Exception $e) {
      return $this->response->setJSON(['error' => false, 'message' => 'Erro ao atualizar o lote.']);
    }
  }
}
