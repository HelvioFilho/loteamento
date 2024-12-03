<?php
$message = $cssError = $cssEyes = $email = '';

if (session()->has('message')) {
  $message = session()->get('message');
  $cssError = 'is-invalid';
  $cssEyes = 'd-none';
  $email = session()->get('email');
}

if (session()->has('logout')) {
  $message = session()->get('logout');
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Helvio Seabra de Vilhena Filho">
  <link rel="icon" type="imagem/ico" href="<?= base_url(['images', 'favicon.ico']) ?>" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <link href="<?= base_url(['assets', 'css', 'sb-admin.css']) ?>" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <title>Chacreamento - Login</title>
  <style>
    .space-eye {
      position: absolute;
      top: 50%;
      right: 10px;
      transform: translateY(-50%);
      width: 40px;
      height: 30px;
      cursor: pointer;
      color: #6c757d;
      /* Opcional, ajusta a cor do ícone */
    }

    .btn-close {
      font-size: .7rem;
    }

    .form-control-user {
      border-radius: 10px !important;
    }
  </style>
</head>

<body class="bg-gradient-primary">
  <div class="container">
    <!-- Outer Row -->
    <div class="row justify-content-center">
      <div class="col-xl-6 col-lg-6 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-12">
                <div class="p-5">
                  <div class="d-flex flex-row justify-content-center align-items-center mb-4 gap-2">
                    <i class="fas fa-map-signs fa-2x" style="color: #7a4a00;"></i>
                    <h1 class="h2 text-gray-900 mt-2">Chacreamento</h1>
                    <i class="fas fa-map-signs fa-2x" style="color: #7a4a00;"></i>
                  </div>
                  <?php if ($message): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                      <?= $message; ?>
                      <button id="button-x" type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                  <?php endif; ?>
                  <form class="user-login" action="<?= base_url('login') ?>" method="post">
                    <div class="form-floating mb-3 mb-md-0">
                      <input class="form-control" id="inputEmail" name="email" type="email" placeholder="Coloque o seu email" />
                      <label for="inputEmail">E-mail</label>
                      <div class="invalid-feedback">O e-mail não pode ser vazio.</div>
                    </div>

                    <div class="form-floating mb-3 position-relative">
                      <input class="form-control" id="inputPassword" name="password" type="password" placeholder="Coloque sua senha" />
                      <label for="inputPassword">Senha</label>
                      <div class="d-flex align-items-center justify-content-center space-eye">
                        <i class="fas fa-eye password-toggle-icon" id="togglePassword"></i>
                      </div>
                      <div class="invalid-feedback">A senha precisa ser preenchida.</div>
                    </div>

                    <div class="form-group">
                      <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="customCheck" name="remember_me">
                        <label class="custom-control-label" for="customCheck">lembrar-me</label>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block btn-login">
                      Entrar
                    </button>
                    <hr>
                    <div class="text-center">
                      <a class="text-decoration-none" href="forgot-password.html">Esqueceu a Senha?</a>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="<?= base_url(['assets', 'js', 'jquery3.7.1.js']) ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

  <script src="<?= base_url(['assets', 'js', 'jquery.easing.js']) ?>"></script>
  <script src="<?= base_url(['assets', 'js', 'sb-admin.js']) ?>"></script>

  <script>
    $(document).ready(function() {
      $('#togglePassword').click(function() {
        const $passwordInput = $('#inputPassword');
        const $icon = $(this);

        // Alternar o tipo do campo de senha
        if ($passwordInput.attr('type') === 'password') {
          $passwordInput.attr('type', 'text');
          $icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
          $passwordInput.attr('type', 'password');
          $icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
      });

      // Função para validar o formulário
      function validateForm() {
        let isValid = true;

        // E-mail Validation
        const $emailInput = $('#inputEmail');
        if ($emailInput.val().trim() === '') {
          $emailInput.addClass('is-invalid');
          isValid = false;
        } else {
          $emailInput.removeClass('is-invalid');
        }

        // Password Validation
        const $passwordInput = $('#inputPassword');
        if ($passwordInput.val().trim() === '') {
          $passwordInput.addClass('is-invalid');
          isValid = false;
        } else {
          $passwordInput.removeClass('is-invalid');
        }

        return isValid;
      }

      $('.user-login').on('submit', function(event) {
        if (!validateForm()) {
          event.preventDefault(); // Evita o envio do formulário

        } else {}
      });

      // Evento de clique no botão "Entrar"
      $('.btn-login').click(function(event) {
        event.preventDefault(); // Evita o envio do formulário
        $('.user-login').submit();
      });

      // Remover a classe de erro ao digitar nos campos
      $('#inputEmail, #inputPassword').on('input', function() {
        if ($(this).hasClass('is-invalid')) {
          $(this).removeClass('is-invalid');
        }
      });
    });
  </script>

</body>

</html>