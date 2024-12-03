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

  <?= $this->renderSection('style') ?>

  <title><?= esc($title) ?></title>
</head>

<body id="page-top">

  <div id="wrapper">
    <?= $this->include('template/navbar') ?>
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <?= $this->include('template/topbar') ?>
        <?= $this->renderSection('content') ?>
        <?= $this->include('template/footer') ?>
      </div>
    </div>
  </div>
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Deseja mesmo sair?</h5>
          <button class="close close-logoutModal" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Selecione "Sair" abaixo se estiver pronto para encerrar a sua sessão atual.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary close-logoutModal" type="button" data-dismiss="modal">Cancelar</button>
          <a class="btn btn-primary" href="<?= base_url('logout') ?>">Sair</a>
        </div>
      </div>
    </div>
  </div>
  <script src="<?= base_url(['assets', 'js', 'jquery3.7.1.js']) ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

  <script src="<?= base_url(['assets', 'js', 'jquery.easing.js']) ?>"></script>
  <script src="<?= base_url(['assets', 'js', 'sb-admin.js']) ?>"></script>
  <?= $this->renderSection('js') ?>
  <script>
    $(document).ready(function() {
      var logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'), {});

      // Evento para abrir o modal ao clicar no botão de logout
      $('#logoutButton').click(function() {
        logoutModal.show();
      });

      $('.close-logoutModal').click(function() {
        logoutModal.hide();
      });

    });
  </script>
</body>

</html>