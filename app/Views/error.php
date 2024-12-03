<?= $this->extend('template/layout'); ?>

<?= $this->section('style') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
  <div class="row">
    <div class="col-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2 col-xl-6 offset-xl-3">
      <h1 class="text-center">Erro 404</h1>
      <p class="text-center">A p&aacute;gina que voc&ecirc; procura n&atilde;o foi encontrada.</p>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<?= $this->endSection() ?>