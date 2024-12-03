<?php

namespace App\Controllers;

use App\Libraries\ImageTools;
use App\Models\UsersModel;
use App\Models\DependentsModel;
use App\Models\PlotsModel;
use App\Models\Dependents_PlotsModel;
use App\Models\QrCodeModel;

class User extends BaseController
{
  public function index()
  {
    $usersModel = new UsersModel();

    $search = $this->request->getGet('search') ?? '';  // Obter o termo de pesquisa do input
    $cpfSearch = preg_replace('/\D/', '', $search);

    if ($search) {

      $usersModel->groupStart()
        ->orLike('name', $search)
        ->orLike('cpf', $cpfSearch)
        ->groupEnd();
    }

    $users = $usersModel->select('id, name, email, phone, cpf, birth_date, address, id_code, image')->orderBy('id', 'DESC')->paginate(5);

    $headerData = [
      'title' => 'Home',
      'users' => $users,
      'pager' => $usersModel->pager,
      'search' => $search,
    ];

    return view('home', $headerData);
  }

  public function createUser()
  {
    $saveImage = new ImageTools();
    $usersModel = new UsersModel();
    $qrCodeModel = new QrCodeModel();

    $data = [
      'name' => trim($this->request->getPost('name')) ?? '',
      'email' => trim($this->request->getPost('email')) ?? '',
      'phone' => preg_replace('/\D/', '', $this->request->getPost('phone')) ?? '',
      'cpf' => preg_replace('/\D/', '', $this->request->getPost('cpf')) ?? '',
      'birth_date' => $usersModel->formatDate($this->request->getPost('birth_date')) ?? '',
      'address' => trim($this->request->getPost('address')) ?? '',
    ];

    // Verificar se o CPF já está cadastrado
    $existingUser = $usersModel->where('cpf', $data['cpf'])->first();

    if ($existingUser) {
      // Se o CPF já estiver cadastrado, redirecionar com uma mensagem de erro
      return redirect()->route('home')->with('error', 'O CPF informado já está cadastrado no sistema. Por favor, verifique as informações.');
    }

    $data['id_code'] = $usersModel->generateIdCode($data['cpf'], mb_url_title($data['name'], ' ', true));

    $urlDestination = base_url('chacreamento/' . $data['id_code']);

    $response = $qrCodeModel->generateCode($urlDestination, './images/qrcode/qrcode' . $data['id_code'] . '.png', '');

    if (!$response) {
      return redirect()->route('home')->with('error', 'Erro ao gerar o QrCode, tente novamente mais tarde ou contate o administrador do sistema');
    }

    $img = $this->request->getFile('image');
    $imageName = mb_url_title($data['name'], '-', true);

    if (!$img->hasMoved()) {
      $result = $saveImage->load($img, $imageName);

      if ($result['success']) {
        $data['image'] = $result['imageName'];
      } else {
        $saveImage->cleanTempFolder();
        return redirect()->route('home')->with('error', 'Não foi possivel salvar a imagem, verifique a extensão da imagem ou tente novamente!');
      }
    }

    $arrayImage = [];
    array_push($arrayImage, $data['image']);

    if (isset($arrayImage)) {
      $saveImage->moveImages($arrayImage);
    }

    try {
      $usersModel->insert($data);
      return redirect()->route('home')->with('success', 'Usuário cadastrado com sucesso!');
    } catch (\Exception $e) {
      return redirect()->route('home')->with('error', 'Não foi possivel salvar a imagem, verifique a extensão da imagem ou tente novamente!');
    }
  }

  public function updateUser($id)
  {
    $usersModel = new UsersModel();

    // Obter os dados do POST
    $data = [
      'email' => trim($this->request->getPost('email')),
      'phone' => preg_replace('/\D/', '', $this->request->getPost('phone')),
      'cpf' => preg_replace('/\D/', '', $this->request->getPost('cpf')),
      'birth_date' => $usersModel->formatDate($this->request->getPost('birth_date')),
      'address' => trim($this->request->getPost('address')),
    ];

    // Atualizar os dados no banco de dados
    try {
      $usersModel->update($id, $data);
      return $this->response->setJSON(['success' => true]);
    } catch (\Exception $e) {
      return $this->response->setJSON(['success' => false, 'message' => 'Erro ao atualizar o usuário.']);
    }
  }

  public function updateUserName($id)
  {
    $usersModel = new UsersModel();

    // Obter o nome atualizado do POST
    $newName = trim($this->request->getPost('name'));

    // Atualizar o nome no banco de dados
    try {
      $usersModel->update($id, ['name' => $newName]);
      return $this->response->setJSON(['success' => true]);
    } catch (\Exception $e) {
      return $this->response->setJSON(['success' => false, 'message' => 'Erro ao atualizar o nome.']);
    }
  }

  public function updateUserImage($id)
  {
    $usersModel = new UsersModel();
    $imageTools = new ImageTools();

    $user = $usersModel->find($id);

    $imageTools->deleteImage($user->image, $_ENV['IMAGE_PATH']);

    $img = $this->request->getFile('image');
    $imageName = mb_url_title($user->name, '-', true);

    if (!$img->hasMoved()) {
      $result = $imageTools->load($img, $imageName);

      if ($result['success']) {
        $data['image'] = $result['imageName'];

        $arrayImage = [];
        array_push($arrayImage, $data['image']);

        if (isset($arrayImage)) {
          $imageTools->moveImages($arrayImage);
        }

        $usersModel->update($id, $data);
        return $this->response->setJSON(['success' => true]);
      } else {
        $imageTools->cleanTempFolder();
        return $this->response->setJSON(['success' => false, 'message' => 'Erro ao atualizar a imagem.']);
      }
    }

    return $this->response->setJSON(['success' => false, 'message' => 'Erro ao atualizar a imagem.']);
  }

  public function deleteUser($id)
  {
    $userModel = new UsersModel();
    $dependentsModel = new DependentsModel();
    $plotsModel = new PlotsModel();
    $dependentsPlotsModel = new Dependents_PlotsModel();
    $imageTools = new ImageTools();

    $user = $userModel->find($id);

    if (!$user) {
      // Usuário não encontrado
      return redirect()->route('home')->with('error', 'Usuário não encontrado.');
    }

    // Etapa 1: Obter os lotes e dependentes do usuário
    $plots = $plotsModel->where('user_id', $id)->findAll();
    $dependents = $dependentsModel->where('user_id', $id)->findAll();

    // Etapa 2: Remover relações de dependents_plots
    foreach ($dependents as $dependent) {
      $dependentsPlotsModel->where('dependent_id', $dependent->id)->delete();
    }

    // Etapa 3: Excluir dependentes
    foreach ($dependents as $dependent) {
      $dependentsModel->delete($dependent->id);
    }

    // Etapa 4: Excluir lotes do usuário
    foreach ($plots as $plot) {
      $plotsModel->delete($plot->id);
    }

    // Etapa 5: Excluir imagem associada ao usuário
    if (!empty($user->image)) {
      $imageTools->deleteImage($user->image, $_ENV['IMAGE_PATH']);
      $imageTools->deleteImage('qrcode' . $user->id_code . '.png', $_ENV['IMAGE_PATH_QRCODE']);
    }

    // Etapa 6: Excluir o próprio usuário
    try {
      $userModel->delete($id);
      return redirect()->route('home')->with('success', 'Usuário excluído com sucesso!');
    } catch (\Exception $e) {
      return redirect()->route('home')->with('error', 'Erro ao excluir o usuário. Por favor, tente novamente.');
    }
  }

  public function card($id): string
  {
    $headerData = [
      'title' => 'Card',
    ];

    if ($id === "452-FFS") {
      $data = [
        'image' => 'image01.png',
        'name' => 'Adrianne Alves Souto',
        'lote' => '1, 2 lado esquerdo',
        'phone' => '(91) 8442-9325',
        'cpf' => '123.456.789-00',
        'birth' => '06/12/1996',
        'dependents' => [
          [
            'name' => 'Fulano01',
            'link' => 'chacreamento/481-FFS'
          ],
          [
            'name' => 'Fulano02',
            'link' => 'chacreamento/482-FFS'
          ],
          [
            'name' => 'Fulano03',
            'link' => 'chacreamento/483-FFS'
          ]
        ],
      ];
    } else {
      $data = null;
    }

    $headerData['data'] = $data;

    return view('card', $headerData);
  }
}
