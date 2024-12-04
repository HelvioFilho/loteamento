<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\DependentsModel;
use App\Models\PlotsModel;

class Chacreamento extends BaseController
{
  public function chacreamento($id_code): string
  {
    $usersModel = new UsersModel();
    $dependentsModel = new DependentsModel();
    $plotsModel = new PlotsModel();

    $headerData = [
      'title' => 'Chacreamento',
    ];

    $user = $usersModel->where('id_code', $id_code)->first();

    if (!$user) {

      $headerData['data'] = null;
      return view('index', $headerData);
    }

    $dependents = $dependentsModel->where('user_id', $user->id)->findAll();
    $plots = $plotsModel->where('user_id', $user->id)->findAll();

    // Agrupando e formatando os lotes por lado
    $plotsGrouped = [];
    foreach ($plots as $plot) {
      $side = $plot->side ? $plot->side : 'indefinido';
      if (!isset($plotsGrouped[$side])) {
        $plotsGrouped[$side] = [];
      }
      $plotsGrouped[$side][] = $plot->plot_number;
    }

    $plotsFormatted = [];
    foreach ($plotsGrouped as $side => $numbers) {
      $plotsFormatted[] = implode(", ", $numbers) . " lado " . $side;
    }
    $plotsString = implode(", ", $plotsFormatted);

    // Formatando os dependentes
    $dependentsFormatted = array_map(function ($dependent) {
      return [
        'name' => $dependent->name,
        'birth_date' => $dependent->birth_date,
      ];
    }, $dependents);

    // Formatando os dados do usuário para a view
    $data = [
      'image' => $user->image,
      'name' => $user->name,
      'lote' => $plotsString,
      'phone' => $user->phone,
      'birth_date' => $user->birth_date,
      'dependents' => $dependentsFormatted,
    ];

    $headerData['data'] = $data;

    return view('information', $headerData);
  }
}
