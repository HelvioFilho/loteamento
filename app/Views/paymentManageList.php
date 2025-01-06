<?= $this->extend('template/layout') ?>

<?= $this->section('style') ?>
<style>
  .checkbox-paid {
    cursor: pointer;
  }

  .checkbox-paid {
    width: 20px;
    /* Define um tamanho fixo para garantir que esteja visível */
    height: 20px;
    accent-color: #007bff;
    /* Cor para navegadores modernos que suportam customização de checkboxes */
    border: 2px solid #007bff;
    /* Garante a visibilidade do checkbox */
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between mb-4 flex-column flex-md-row gap-3">
    <h1 class="h3 mb-0 text-gray-800"><?= esc($list->name . ' (' . $list->month . '/' . $list->year . ')') ?></h1>
    <!-- espaço para o botão do excel -->
    <a href="<?= base_url('payments/exportExcel/' . $list->id) ?>" class="btn btn-success d-flex justify-content-center align-items-center gap-2">
      <i class="fas fa-file-excel"></i> <span>Gerar Excel</span>
    </a>
    <button id="savePaymentsBtn" class="btn btn-primary d-flex justify-content-center align-items-center gap-2">
      <i class="fas fa-save"></i> <span> Salvar Pagamentos </span>
    </button>
  </div>

  <div class="row">
    <div class="col-lg-12">
      <div class="card shadow mb-4">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="usersTable">
              <thead>
                <tr>
                  <th>Nome</th>
                  <th class="d-none d-md-table-cell">Lotes</th>
                  <th>Pago</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($users as $user): ?>
                  <tr>
                    <td><?= esc($user->name) ?></td>
                    <td class="d-none d-md-table-cell"><?= esc($user->plots) ?></td>
                    <td>
                      <div class="d-flex justify-content-center align-items-center">
                        <input type="checkbox" class="checkbox-paid" data-user-id="<?= $user->id ?>" <?= $user->paid ? 'checked' : '' ?>>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="warningModal" tabindex="-1" role="dialog" aria-labelledby="warningModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="warningModalLabel">Aviso</h5>
        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <span id="modalErrorMessage"></span>
      </div>
      <div class="modal-footer">
        <button id="closeModal" class="btn btn-primary" type="button" data-bs-dismiss="modal">Ok</button>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
  $(document).ready(function() {

    var warningModal = new bootstrap.Modal(document.getElementById('warningModal'), {});

    $('.btn-close').click(function() {
      warningModal.hide();
    });

    $('#closeModal').click(function() {
      warningModal.hide();
    });

    // Evento ao clicar em "Salvar Pagamentos"
    $('#savePaymentsBtn').click(function() {
      let $button = $(this);
      let payments = [];

      // Bloqueia o botão e mostra a mensagem de "Aguarde..."
      $button.prop('disabled', true).html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <span>Carregando informações...<span>`);

      $('.checkbox-paid').each(function() {
        payments.push({
          user_id: $(this).data('user-id'),
          paid: $(this).is(':checked') ? 1 : 0 // Enviar como 1 ou 0 para representar boolean
        });
      });

      $.ajax({
        url: '<?= base_url('payments/savePayments') ?>',
        method: 'POST',
        contentType: 'application/json', // Define que os dados estão sendo enviados como JSON
        data: JSON.stringify({
          payment_list_id: <?= $list->id ?>,
          payments: payments
        }),
        success: function(response) {
          if (response.success) {
            // Mensagem de sucesso
            $('#modalErrorMessage').removeClass('text-danger').addClass('text-success').text('Pagamentos salvos com sucesso!');
          } else {
            // Mensagem de erro
            $('#modalErrorMessage').removeClass('text-success').addClass('text-danger').text('Erro ao salvar pagamentos.');
          }
          warningModal.show();
        },
        error: function() {
          // Mensagem de erro
          $('#modalErrorMessage').removeClass('text-success').addClass('text-danger').text('Erro ao salvar pagamentos.');
          warningModal.show();
        },
        complete: function() {
          // Desbloqueia o botão e restaura o texto original
          $button.prop('disabled', false).html('<i class="fas fa-save"></i> <span>Salvar Pagamentos<span>');
        }
      });
    });
  });
</script>
<?= $this->endSection() ?>