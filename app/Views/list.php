<?= $this->extend('template/layout') ?>

<?= $this->section('style') ?>
<style>
  .checkbox-label {
    cursor: pointer;
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Criar Lista de Pagamento</h1>
    <button class="btn btn-sm btn-primary shadow-sm" data-bs-toggle="collapse" data-bs-target="#addListForm" id="toggleButton">
      <i class="fas fa-plus-circle fa-sm text-white-50"></i> Criar Lista
    </button>
  </div>

  <!-- Form para Criar Lista -->
  <div class="collapse mb-4" id="addListForm">
    <form id="listForm" method="POST" action="<?= base_url('createList') ?>">
      <div class="mb-3">
        <label for="listName" class="form-label">Nome da Lista</label>
        <input type="text" class="form-control" id="listName" name="name" required>
      </div>
      <div class="row">
        <div class="col-md-6">
          <label for="month" class="form-label">Mês</label>
          <select class="form-control" id="month" name="month" required>
            <option value="1">Janeiro</option>
            <option value="2">Fevereiro</option>
            <!-- demais meses... -->
          </select>
        </div>
        <div class="col-md-6">
          <label for="year" class="form-label">Ano</label>
          <input type="number" class="form-control" id="year" name="year" required>
        </div>
      </div>
      <button type="submit" class="btn btn-primary mt-3">Criar Lista</button>
    </form>
  </div>

  <!-- Listagem de Usuários para Marcar Pagamentos -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Usuários</h6>
    </div>
    <div class="card-body">
      <form id="paymentListForm">
        <?php foreach ($users as $user): ?>
          <div class="form-check">
            <input type="checkbox" class="form-check-input payment-checkbox" id="user_<?= $user->id ?>" data-user-id="<?= $user->id ?>">
            <label class="form-check-label checkbox-label" for="user_<?= $user->id ?>">
              <?= esc($user->name) ?>
            </label>
          </div>
        <?php endforeach; ?>
        <button type="button" id="savePaymentsBtn" class="btn btn-success mt-4">Salvar Pagamentos</button>
      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
  $(document).ready(function() {
    $('#savePaymentsBtn').click(function() {
      const paymentData = [];

      $('.payment-checkbox').each(function() {
        if ($(this).is(':checked')) {
          paymentData.push({
            user_id: $(this).data('user-id'),
            paid: true
          });
        } else {
          paymentData.push({
            user_id: $(this).data('user-id'),
            paid: false
          });
        }
      });

      $.ajax({
        url: '<?= base_url('savePayments') ?>',
        method: 'POST',
        data: {
          payment_list_id: <?= $payment_list_id ?>,
          payments: paymentData
        },
        success: function(response) {
          if (response.success) {
            alert('Pagamentos salvos com sucesso!');
          } else {
            alert('Erro ao salvar os pagamentos.');
          }
        },
        error: function() {
          alert('Erro ao salvar os pagamentos.');
        }
      });
    });
  });
</script>
<?= $this->endSection() ?>