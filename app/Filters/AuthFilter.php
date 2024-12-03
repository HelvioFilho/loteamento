<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
  public function before(RequestInterface $request, $arguments = null)
  {
    // Verifica se o usuário está logado
    if (!session()->get('isLoggedIn')) {
      // Redireciona para a página de login, caso não esteja logado
      return redirect()->to('/login')->with('message', 'Você precisa estar logado para acessar essa página.');
    }
  }

  public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
  {
    // Não faz nada após a solicitação ser processada
  }
}
