<?= $this->extend('template/layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dependentes de <?= esc($user->name) ?></h1>
  </div>

  <!-- Formulário para adicionar um dependente -->
  <div class="card shadow mb-4">
    <div class="card-body">
      <form id="addDependentForm" method="POST" action="<?= base_url(['createDependent', $user->id]) ?>">
        <div class="row">
          <div class="col-12 col-md-5 mb-3">
            <label for="dependentName" class="form-label">Nome do Dependente</label>
            <input type="text" class="form-control" id="dependentName" name="name" required>
            <div class="invalid-feedback">O nome do dependente é obrigatório.</div>
          </div>
          <div class="col-12 col-md-2 mb-3">
            <label for="inputBirthDate" class="form-label">Nascimento</label>
            <input type="text" class="form-control" id="inputBirthDate" name="birth_date">
            <div class="invalid-feedback">Data de nascimento inválida.</div>
          </div>
        </div>
        <button type="button" class="btn btn-primary" id="addDependentButton">
          <span id="buttonText">Adicionar Dependente</span>
          <span id="buttonSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
        </button>
      </form>
    </div>
  </div>

  <!-- Lista de dependentes existentes -->
  <div class="card shadow mb-4">
    <div class="card-body">
      <?php if (count($dependents) > 0): ?>
        <ul class="list-group">
          <?php foreach ($dependents as $dependent): ?>
            <li class="list-group-item">
              <div class="row">
                <div class="col-12 col-md-8 d-flex align-items-center justify-content-center justify-content-md-start gap-2">
                  <span class="dependent-info" data-dependent-id="<?= $dependent->id ?>">
                    <!-- Campos de visualização -->
                    <span class="dependent-name"><?= esc($dependent->name) ?></span>
                    <?php if ($dependent->birth_date): ?>
                      <span class="divider"> - </span>
                      <span class="birth-date"><?= esc($dependent->birth_date) ?></span>
                    <?php endif; ?>
                    <!-- Campos de edição (inicialmente ocultos) -->
                    <div class="edit-fields d-none gap-2 flex-column">
                      <input type="text" class="form-control form-control-sm edit-dependent-name" value="<?= esc($dependent->name) ?>" />
                      <input type="text" class="form-control form-control-sm edit-birth-date" value="<?= esc($dependent->birth_date) ?>" />
                      <button class="btn btn-sm btn-success save-dependent-btn d-none" data-dependent-id="<?= $dependent->id ?>">
                        <i class="fas fa-save"></i> Salvar
                      </button>
                    </div>
                  </span>
                </div>

                <!-- Botões para Gerenciar, Editar e Excluir -->
                <div class="col-12 col-md-4">
                  <div class="d-flex flex-row align-items-center justify-content-center justify-content-md-end gap-2 mt-3 mt-md-0">
                    <button class="btn btn-sm btn-warning edit-dependent-btn" data-dependent-id="<?= $dependent->id ?>">
                      <i class="fas fa-pencil-alt"></i> Editar
                    </button>

                    <button class="btn btn-sm btn-danger deleteDependentButton" data-dependent-id="<?= $dependent->id ?>">Excluir</button>
                  </div>
                </div>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p class="h5 text-muted text-center">Este usuário ainda não possui dependentes.</p>
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
        Dependente cadastrado com sucesso!
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
        Tem certeza de que deseja excluir este dependente? Essa ação não pode ser desfeita.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteDependentBtn">Excluir</button>
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

  $(document).ready(function() {
    var warningModal = new bootstrap.Modal(document.getElementById('warningListModal'), {});
    var confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'), {});
    var dependentIdToDelete;

    $('#inputBirthDate').inputmask('99/99/9999');
    $('.edit-birth-date').inputmask('99/99/9999');

    if (warningMessageError !== '') {
      $('.text-modal-warning').addClass('text-muted').text(warningMessageError);
      warningModal.show();
    }

    function isValidDate(dateString) {
      // Verifica se o formato básico está correto: dd/mm/yyyy
      const datePattern = /^(\d{2})\/(\d{2})\/(\d{4})$/;
      const match = dateString.match(datePattern);
      if (!match) {
        return false; // Formato inválido
      }

      const day = parseInt(match[1], 10);
      const month = parseInt(match[2], 10);
      const year = parseInt(match[3], 10);

      // Verifica se o mês está entre 1 e 12
      if (month < 1 || month > 12) {
        return false;
      }

      // Verifica os dias possíveis em cada mês
      const monthDays = [31, (isLeapYear(year) ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

      if (day < 1 || day > monthDays[month - 1]) {
        return false;
      }

      return true;
    }

    // Função para verificar se o ano é bissexto
    function isLeapYear(year) {
      return ((year % 4 === 0 && year % 100 !== 0) || (year % 400 === 0));
    }

    $('#addDependentButton').click(function() {
      // Obter valores dos inputs
      const name = $('#dependentName').val().trim();
      const birthDateValue = $('#inputBirthDate').val().trim();

      // Resetar classes de validação
      $('#dependentName, #inputBirthDate').removeClass('is-invalid');

      // Validação dos campos
      let isValid = true;

      if (name === "") {
        $('#dependentName').addClass('is-invalid');
        isValid = false;
      } else {
        $('#dependentName').removeClass('is-invalid').addClass('is-valid');
      }

      if (birthDateValue !== "") {
        if (!isValidDate(birthDateValue)) {
          $('#inputBirthDate').addClass('is-invalid');
          isValid = false;
        } else {
          $('#inputBirthDate').removeClass('is-invalid').addClass('is-valid');
        }
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
        $('#addDependentForm').submit();
      }, 800);
    });

    // Quando o valor do campo mudar, remover o estado de erro
    $('#dependentName, #inputBirthDate').change(function() {
      $(this).removeClass('is-invalid');
    });

    // Abrir o modal de confirmação ao clicar no botão de excluir
    $('.deleteDependentButton').click(function() {
      dependentIdToDelete = $(this).data('dependent-id');
      confirmDeleteModal.show();
    });

    // Quando confirmar a exclusão do dependente
    $('#confirmDeleteDependentBtn').click(function() {
      if (dependentIdToDelete) {
        let $button = $(this);
        $button.prop('disabled', true);
        $button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Aguarde...');

        setTimeout(function() {
          window.location.href = '/deleteDependent/' + dependentIdToDelete + '/' + userId;
        }, 800);
      }
    });

    // Evento ao clicar em "Editar Dependente"
    $('.edit-dependent-btn').click(function() {
      const $listItem = $(this).closest('.list-group-item');
      // Mostrar os campos de edição e ocultar os campos de visualização
      $listItem.find('.edit-fields').removeClass('d-none').addClass('d-flex');
      $listItem.find('.divider').addClass('d-none');
      $listItem.find('.dependent-name, .birth-date').addClass('d-none');
      // Alternar os botões
      $(this).addClass('d-none');
      $listItem.find('.save-dependent-btn').removeClass('d-none');
    });

    // Evento ao clicar em "Salvar Dependente"
    $('.save-dependent-btn').click(function() {
      const dependentId = $(this).data('dependent-id');
      const $listItem = $(this).closest('.list-group-item');
      const name = $listItem.find('.edit-dependent-name').val().trim();
      const birthDate = $listItem.find('.edit-birth-date').val().trim();

      // Validação dos campos
      let isValid = true;

      if (name === "") {
        $listItem.find('.edit-dependent-name').addClass('is-invalid');
        isValid = false;
      } else {
        $listItem.find('.edit-dependent-name').removeClass('is-invalid').addClass('is-valid');
      }

      if (!isValidDate(birthDate)) {
        $listItem.find('.edit-birth-date').addClass('is-invalid');
        isValid = false;
      } else {
        $listItem.find('.edit-birth-date').removeClass('is-invalid').addClass('is-valid');
      }

      if (!isValid) {
        return;
      }

      // Enviar o formulário via AJAX
      $.ajax({
        url: '<?= base_url('updateDependent') ?>/' + dependentId,
        method: 'POST',
        data: {
          name: name,
          birth_date: birthDate
        },
        success: function(response) {
          if (response.success) {
            // Atualizar a visualização com os novos dados
            $listItem.find('.dependent-name').text(name);
            $listItem.find('.birth-date').text(birthDate);
            // Mostrar os campos de visualização e ocultar os campos de edição
            $listItem.find('.edit-fields').removeClass('d-flex').addClass('d-none');
            $listItem.find('.divider').removeClass('d-none');
            $listItem.find('.dependent-name, .birth-date').removeClass('d-none');
            // Alternar os botões
            $listItem.find('.save-dependent-btn').addClass('d-none');
            $listItem.find('.edit-dependent-btn').removeClass('d-none');
            // Mostrar modal de sucesso
            $('.text-modal-warning').text('Dependente atualizado com sucesso!');
            warningModal.show();
          } else {
            // Exibir erro
            $('.text-modal-warning').text('Erro ao atualizar o dependente.');
            warningModal.show();
          }
        },
        error: function() {
          $('.text-modal-warning').text('Erro ao atualizar o dependente.');
          warningModal.show();
        }
      });
    });
  });
</script>
<?= $this->endSection() ?>