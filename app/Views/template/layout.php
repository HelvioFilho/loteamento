<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Helvio Seabra de Vilhena Filho">
  <link rel="icon" type="imagem/ico" href="<?= base_url(['images', 'favicon.ico']) ?>" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="<?= base_url(['assets', 'css', 'main.css']) ?>" rel="stylesheet">
  <?= $this->renderSection('style') ?>

  <title><?= esc($title) ?></title>
</head>

<body>
  <main class="py-5">
    <div class="content">
      <?= $this->renderSection('content') ?>
    </div>
    <?= $this->include('template/footer') ?>
  </main>
  <script src="<?= base_url(['assets', 'js', 'jquery3.7.1.js']) ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  <?= $this->renderSection('js') ?>
</body>

</html>