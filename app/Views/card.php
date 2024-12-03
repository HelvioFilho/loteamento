<?= $this->extend('template/layout'); ?>

<?= $this->section('style') ?>
<style>
  .card-wrapper {}

  .card-header {
    text-align: center;
    background-color: #d3f859;
    border-radius: 15px;
    padding: 20px;
  }

  .card-img {
    width: 150px;
    height: 150px;
    border-radius: 1rem;
    object-fit: cover;
    margin-bottom: 20px;
  }

  .card-body {
    padding: 15px;
    color: #444;
    font-size: 16px;
  }

  .card-body label {
    font-weight: bold;
  }

  .card-body p {
    margin-bottom: 5px;
  }

  .left {
    width: 250px;
  }

  .right {
    width: 350px;
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-5">
  <div class="row">
    <div class="col-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2 col-xl-6 offset-xl-3">
      <div class="d-flex border">
        <div class="card-header left">
          <center>
            <div>
              <img src="<?= base_url(['images', 'users', $data['image']]) ?>" class="card-img" alt="Foto Titular">
            </div>
          </center>
          <h4 class="mt-3"><?= $data['name'] ?></h4>
          <p class="">Lote 1, 2 Lado Esquerdo</p>
          <p><strong>Cód Individual:</strong> 96e839</p>
        </div>
        <div class="card-body right d-flex flex-column justify-content-center align-items-center">
          <div class="info">
            <label>Telefone:</label>
            <p>91 9999-9999</p>
          </div>
          <div class="info">
            <label>CPF:</label>
            <p>003.003.003-00</p>
          </div>
          <div class="info">
            <label>Nascimento:</label>
            <p>10/10/1999</p>
          </div>
          <div class="info">
            <label>Filiação:</label>
            <p>Dependente 1</p>
            <p>Dependente 2</p>
            <p>Dependente 3</p>
            <p>Dependente 4</p>
            <p>Dependente 5</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<?= $this->endSection() ?>