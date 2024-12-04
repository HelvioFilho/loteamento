<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Helvio Seabra de Vilhena Filho">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <link href="<?= base_url(['assets', 'css', 'sb-admin.css']) ?>" rel="stylesheet">
  <link href="<?= base_url(['assets', 'css', 'information.css']) ?>" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">


  <title>Informações do Usuário</title>
</head>

<body id="page-top">
  <div class="container">
    <div class="row">
      <div class="col-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2 col-xl-6 offset-xl-3">
        <?php if ($data !== null) : ?>
          <div class="profile-section mt-4">
            <center>
              <img src="<?= base_url(['images', 'users', $data['image']]); ?>" alt="Imagem do usuário" class="profile-image">
            </center>
            <h2 class="label text-center"><?= $data['name'] ?></h2>
            <div class="d-flex flex-column gap-1">
              <span class="label lote">Lote</span>
              <span class="label"><?= $data['lote'] !== '' ? $data['lote'] : 'Não informado' ?></span>
            </div>
            <div class="divider"></div>
            <h2 class="label text-center">Informações de Cadastro</h2>
            <div class="divider"></div>
            <div class="d-flex flex-column gap-1">
              <div>
                <span class="label">Telefone: </span><span><?= $data['phone'] ?></span>
              </div>
              <div>
                <span class="label">Nascimento: </span><span><?= $data['birth_date'] ?></span>
              </div>
            </div>
            <div class="divider"></div>
            <h2 class="label text-center">Dependentes:</h2>
            <div class="divider"></div>
            <ul class="dependents-list">
              <?php if (empty($data['dependents'])): ?>
                <li>
                  <span class="label">Ainda não há dependentes cadastrados.</span>
                </li>
              <?php else: ?>
                <?php foreach ($data['dependents'] as $dependent) : ?>
                  <li>
                    <?= $dependent['name'] ?>, <?= $dependent['birth_date'] ?>
                  </li>
                <?php endforeach; ?>
              <?php endif; ?>
            </ul>
          </div>
        <?php else: ?>
          <div>
            <h3 class="label text-center">Nenhuma informação encontrada para o código informado!</h3>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <footer class="sticky-footer bg-white">
    <div class="container my-auto">
      <div class="copyright text-center my-auto">
        <span>&copy; <?= date('Y') ?> Chacreamento. Todos os direitos reservados.</span>
      </div>
    </div>
  </footer>
  <script src="<?= base_url(['assets', 'js', 'jquery3.7.1.js']) ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>

</html>