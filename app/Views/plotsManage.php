<?= $this->extend('template/layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Lotes de <?= esc($user->name) ?></h1>
  </div>

  <!-- Formulário para adicionar um dependente -->
  <div class="card shadow mb-4">
    <div class="card-body">
      <form id="addPlotForm" method="POST" action="<?= base_url(['createPlot', $user->id]) ?>">
        <div class="row">
          <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
            <label for="plotNumber" class="form-label">Número do lote</label>
            <input type="text" class="form-control" id="plotNumber" name="plot" required>
            <div class="invalid-feedback">O número do lote é obrigatório.</div>
          </div>
          <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
            <label for="plotSide" class="form-label">Escolha um lado</label>
            <select class="form-control" id="plotSide" name="side" required>
              <option value="direito" selected>Direito</option>
              <option value="esquerdo">Esquerdo</option>
            </select>
          </div>
        </div>
        <button type="button" class="btn btn-primary" id="addPlotButton">
          <span id="buttonText">Adicionar Lote</span>
          <span id="buttonSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
        </button>
      </form>
    </div>
  </div>

  <!-- Lista de dependentes existentes -->
  <div class="card shadow mb-4">
    <div class="card-body">
      <?php if (count($plots) > 0): ?>
        <ul class="list-group">
          <?php foreach ($plots as $plot): ?>
            <li class="list-group-item">
              <div class="row">
                <div class="col-12 col-md-8 d-flex align-items-center justify-content-center justify-content-md-start gap-2">
                  <!-- Campos de visualização -->
                  <span class="divider">Lote: </span>
                  <span class="plot-number"><?= esc($plot->plot_number) ?></span>
                  <span class="divider"> - </span>
                  <span class="divider">Lado: </span>
                  <span class="plot-side"><?= esc($plot->side) ?></span>

                  <!-- Campos de edição (inicialmente ocultos) -->
                  <div class="edit-fields d-none gap-2 flex-column">
                    <input type="text" class="form-control form-control-sm edit-plot-number" value="<?= esc($plot->plot_number) ?>" />
                    <select class="form-control form-control-sm edit-plot-side">
                      <option value="direito" <?= $plot->side == 'direito' ? 'selected' : '' ?>>Direito</option>
                      <option value="esquerdo" <?= $plot->side == 'esquerdo' ? 'selected' : '' ?>>Esquerdo</option>
                    </select>
                    <button class="btn btn-sm btn-success save-plot-btn d-none" data-plot-id="<?= $plot->id ?>">
                      <i class="fas fa-save"></i> Salvar
                    </button>
                  </div>
                  </span>
                </div>

                <!-- Botões para Gerenciar, Editar e Excluir -->
                <div class="col-12 col-md-4">
                  <div class="d-flex flex-row align-items-center justify-content-center justify-content-md-end gap-2 mt-3 mt-md-0">
                    <button class="btn btn-sm btn-warning edit-plot-btn" data-plot-id="<?= $plot->id ?>">
                      <i class="fas fa-pencil-alt"></i> Editar
                    </button>

                    <button class="btn btn-sm btn-danger deletePlotButton" data-plot-id="<?= $plot->id ?>">Excluir</button>
                  </div>
                </div>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p class="h5 text-muted text-center">Este usuário ainda não possui lotes cadastrados.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Modal para exibir mensagens -->
<div class="modal fade" id="warningListModal" tabindex="-1" aria-labelledby="warningListModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="warningListModalLabel">Aviso</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-modal-warning">
        Lote cadastrado com sucesso!
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ok</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para confirmação de exclusão -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmação de Exclusão</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Tem certeza de que deseja excluir este lote? Essa ação não pode ser desfeita.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="confirmDeletePlotBtn">Excluir</button>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?php
$messageError = '';

if (session()->has('message')) {
  $messageError = session()->get('message');
}
?>

<?= $this->section('js') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
<script>
  let warningMessageError = "<?= $messageError ?>";
  let userId = "<?= $user->id ?>";

  $('#plotNumber, .edit-plot-number').inputmask({
    alias: "numeric", // Permitir apenas valores numéricos
    allowMinus: false, // Não permitir números negativos
    allowPlus: false, // Não permitir sinal de mais
    digits: 0, // Número de dígitos decimais permitidos (zero, neste caso)
    rightAlign: false // Alinhar os números à esquerda
  });


  $(document).ready(function() {
    var warningModal = new bootstrap.Modal(document.getElementById('warningListModal'), {});
    var confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'), {});
    var plotIdToDelete;

    if (warningMessageError !== '') {
      $('.text-modal-warning').addClass('text-muted').text(warningMessageError);
      warningModal.show();
    }

    $('#addPlotButton').click(function() {
      // Obter valores dos inputs
      const plot = $('#plotNumber').val().trim();
      const side = $('#plotSide').val().trim();

      // Resetar classes de validação
      $('#plotNumber').removeClass('is-invalid');

      // Validação dos campos
      let isValid = true;

      if (plot === "") {
        $('#plotNumber').addClass('is-invalid');
        isValid = false;
      } else {
        $('#plotNumber').removeClass('is-invalid').addClass('is-valid');
      }

      // Se os campos não forem válidos, não enviar o formulário
      if (!isValid) {
        return;
      }

      // Desativar o botão e mostrar o spinner
      let $button = $(this);
      $button.prop('disabled', true);
      $('#buttonText').text('Carregando');
      $('#buttonSpinner').removeClass('d-none');

      setTimeout(function() {
        $('#addPlotForm').submit();
      }, 800);
    });

    // Quando o valor do campo mudar, remover o estado de erro
    $('#plotNumber').change(function() {
      $(this).removeClass('is-invalid');
    });

    // Abrir o modal de confirmação ao clicar no botão de excluir
    $('.deletePlotButton').click(function() {
      plotIdToDelete = $(this).data('plot-id');
      confirmDeleteModal.show();
    });

    // Quando confirmar a exclusão do dependente
    $('#confirmDeletePlotBtn').click(function() {
      if (plotIdToDelete) {
        let $button = $(this);
        $button.prop('disabled', true);
        $button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Aguarde...');

        setTimeout(function() {
          window.location.href = '/deletePlot/' + plotIdToDelete + '/' + userId;
        }, 800);
      }
    });

    // Evento ao clicar em "Editar Dependente"
    $('.edit-plot-btn').click(function() {
      const $listItem = $(this).closest('.list-group-item');
      // Mostrar os campos de edição e ocultar os campos de visualização
      $listItem.find('.edit-fields').removeClass('d-none').addClass('d-flex');
      $listItem.find('.divider').addClass('d-none');
      $listItem.find('.plot-number, .plot-side').addClass('d-none');
      // Alternar os botões
      $(this).addClass('d-none');
      $listItem.find('.save-plot-btn').removeClass('d-none');
    });

    // Evento ao clicar em "Salvar Dependente"
    $('.save-plot-btn').click(function() {
      const plotId = $(this).data('plot-id');
      const $listItem = $(this).closest('.list-group-item');
      const plot = $listItem.find('.edit-plot-number').val().trim();
      const side = $listItem.find('.edit-plot-side').val().trim();

      // Validação dos campos
      let isValid = true;

      if (plot === "") {
        $listItem.find('.edit-plot-number').addClass('is-invalid');
        isValid = false;
      } else {
        $listItem.find('.edit-plot-number').removeClass('is-invalid').addClass('is-valid');
      }

      if (!isValid) {
        return;
      }

      // Enviar o formulário via AJAX
      $.ajax({
        url: '<?= base_url('updatePlot') ?>/' + plotId,
        method: 'POST',
        data: {
          plot: plot,
          side: side
        },
        success: function(response) {
          if (response.success) {
            // Atualizar a visualização com os novos dados
            $listItem.find('.plot-number').text(plot);
            $listItem.find('.plot-side').text(side);

            // Mostrar modal de sucesso
            $('.text-modal-warning').text(response.message);

          } else {
            // Exibir erro
            $('.text-modal-warning').text(response.message);
          }
          // Mostrar os campos de visualização e ocultar os campos de edição
          $listItem.find('.edit-fields').removeClass('d-flex').addClass('d-none');
          $listItem.find('.divider').removeClass('d-none');
          $listItem.find('.plot-number, .plot-side').removeClass('d-none');
          // Alternar os botões
          $listItem.find('.save-plot-btn').addClass('d-none');
          $listItem.find('.edit-plot-btn').removeClass('d-none');
          warningModal.show();
        },
        error: function() {
          $('.text-modal-warning').text('Erro ao atualizar o lote.');
          warningModal.show();
        }
      });
    });
  });
</script>
<?= $this->endSection() ?>