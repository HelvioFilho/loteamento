<?php

namespace App\Controllers;

use App\Models\UserAdminModel;

class Home extends BaseController
{
    public function index()
    {
        $userModel = new UserAdminModel();

        // Verifica se o usuário já está logado pela sessão
        if (session()->get('isLoggedIn')) {
            // Usuário está logado, redireciona para a rota protegida (ex.: /user)
            return redirect()->to('/user');
        }

        if ($userModel->checkRememberMeCookie()) {
            return redirect()->to('/user');
        }

        if ($this->request->getMethod() === 'POST') {
            // Obter dados do formulário

            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $rememberMe = $this->request->getPost('remember_me');

            // Buscar o usuário pelo e-mail
            $user = $userModel->where('email', $email)->first();

            if ($user) {
                // Verifica a senha
                if (password_verify($password, $user->password)) {
                    // Cria a sessão do usuário
                    session()->set([
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'user_email' => $user->email,
                        'isLoggedIn' => true,
                        'access_type' => $user->access_type
                    ]);

                    // Verifica a opção "lembrar-me"
                    if ($rememberMe) {
                        $userModel->setRememberMeCookie($user);
                    }
                    $userModel->update($user->id, ['last_login' => date('Y-m-d H:i:s')]);
                    // Redireciona para o painel de controle ou outra página
                    return redirect()->to('/user');
                } else {
                    // Senha incorreta
                    return redirect()->back()->with('message', 'Usuário ou senha incorretos.')->withInput();
                }
            } else {
                // Usuário não encontrado
                return redirect()->back()->with('message', 'Usuário ou senha incorretos.')->withInput();
            }
        }
        return view('login');
    }



    public function logout()
    {
        helper('cookie');

        // Destruir a sessão do usuário
        session()->destroy();

        // Remover o cookie "remember_me", se existir
        delete_cookie('remember_me');


        // Redirecionar para a página de login com uma mensagem de sucesso
        return redirect()->to('/')->with('message', 'Você saiu do sistema!');
    }
}
