<?php
$currentMonth = date('n');

$message = $css = "";

if (session()->has('error')) {
  $message = session()->get('error');
  $css = 'alert-danger';
}

if (session()->has('success')) {
  $message = session()->get('success');
  $css = 'alert-success';
}

?>
<?= $this->extend('template/layout') ?>

<?= $this->section('style') ?>
<style>
  .checkbox-label {
    cursor: pointer;
  }

  .btn-close-alert {
    font-size: .75rem;
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Lista de Pagamento</h1>
    <button class="btn btn-sm btn-primary shadow-sm" data-bs-toggle="collapse" data-bs-target="#addListForm" id="toggleButton">
      <i class="fas fa-plus-circle fa-sm text-white-50"></i> Criar Lista
    </button>
  </div>

  <!-- Form para Criar Lista -->
  <div class="collapse mb-4" id="addListForm">
    <form id="listForm" method="POST" action="<?= base_url(['payments', 'createList']) ?>">
      <div class="row">
        <div class="mb-3 col-12 col-md-6">
          <label for="listName" class="form-label">Nome da Lista</label>
          <input type="text" class="form-control" id="listName" name="name" required>
        </div>
        <div class="col-6 col-md-3">
          <label for="month" class="form-label">Mês</label>
          <select class="form-control" id="month" name="month" required>
            <option value="1" <?= $currentMonth == 1 ? 'selected' : '' ?>>Janeiro</option>
            <option value="2" <?= $currentMonth == 2 ? 'selected' : '' ?>>Fevereiro</option>
            <option value="3" <?= $currentMonth == 3 ? 'selected' : '' ?>>Março</option>
            <option value="4" <?= $currentMonth == 4 ? 'selected' : '' ?>>Abril</option>
            <option value="5" <?= $currentMonth == 5 ? 'selected' : '' ?>>Maio</option>
            <option value="6" <?= $currentMonth == 6 ? 'selected' : '' ?>>Junho</option>
            <option value="7" <?= $currentMonth == 7 ? 'selected' : '' ?>>Julho</option>
            <option value="8" <?= $currentMonth == 8 ? 'selected' : '' ?>>Agosto</option>
            <option value="9" <?= $currentMonth == 9 ? 'selected' : '' ?>>Setembro</option>
            <option value="10" <?= $currentMonth == 10 ? 'selected' : '' ?>>Outubro</option>
            <option value="11" <?= $currentMonth == 11 ? 'selected' : '' ?>>Novembro</option>
            <option value="12" <?= $currentMonth == 12 ? 'selected' : '' ?>>Dezembro</option>
          </select>
        </div>
        <div class="col-6 col-md-3">
          <label for="year" class="form-label">Ano</label>
          <input type="number" class="form-control" id="year" name="year" value="<?= date('Y') ?>" required>
        </div>
      </div>
      <button type="submit" class="btn btn-primary mt-3" id="createListButton">
        <span id="buttonText">Criar Lista</span>
        <span id="buttonSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
      </button>
    </form>
  </div>

  <!-- Listagem de Listas Criadas -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">Listas Criadas</h6>
      <span class="d-flex flex-row align-items-center">
        <form id="searchForm" method="GET" action="<?= base_url('lista-de-pagamentos') ?>" class="d-flex">
          <input class="form-control form-control-sm me-2" id="searchInput" type="text" name="search" placeholder="Pesquisar Lista" value="<?= isset($search) ? esc($search) : '' ?>" />
          <button type="submit" class="btn btn-primary btn-sm" id="searchButton"><i class="fas fa-search"></i></button>
        </form>
      </span>
    </div>
    <?php if ($message): ?>
      <div class="col-12 col-md-10 col-lg-8 col-xl-6 mt-2">
        <div class="alert <?= $css ?> alert-dismissible fade show" role="alert">
          <?= $message; ?>
          <button type="button" class="btn-close btn-close-alert" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
    <?php endif; ?>
    <div class="card-body">
      <?php if (count($lists) > 0): ?>
        <ul class="list-group mb-4">
          <?php foreach ($lists as $list): ?>
            <li class="list-group-item">
              <div class="row">
                <div class="col-12 col-md-8 d-flex align-items-center justify-content-center justify-content-md-start">
                  <span class="list-name" data-list-id="<?= $list->id ?>"><?= esc($list->name) ?> (<?= esc($list->month) ?>/<?= esc($list->year) ?>)</span>
                  <div class="edit-fields d-none gap-2 flex-column">
                    <input type="text" class="form-control form-control-sm list-edit-name" value="<?= esc($list->name) ?>" />
                    <select class="form-control form-control-sm list-edit-month">
                      <option value="1" <?= $list->month == 1 ? 'selected' : '' ?>>Janeiro</option>
                      <option value="2" <?= $list->month == 2 ? 'selected' : '' ?>>Fevereiro</option>
                      <option value="3" <?= $list->month == 3 ? 'selected' : '' ?>>Março</option>
                      <option value="4" <?= $list->month == 4 ? 'selected' : '' ?>>Abril</option>
                      <option value="5" <?= $list->month == 5 ? 'selected' : '' ?>>Maio</option>
                      <option value="6" <?= $list->month == 6 ? 'selected' : '' ?>>Junho</option>
                      <option value="7" <?= $list->month == 7 ? 'selected' : '' ?>>Julho</option>
                      <option value="8" <?= $list->month == 8 ? 'selected' : '' ?>>Agosto</option>
                      <option value="9" <?= $list->month == 9 ? 'selected' : '' ?>>Setembro</option>
                      <option value="10" <?= $list->month == 10 ? 'selected' : '' ?>>Outubro</option>
                      <option value="11" <?= $list->month == 11 ? 'selected' : '' ?>>Novembro</option>
                      <option value="12" <?= $list->month == 12 ? 'selected' : '' ?>>Dezembro</option>
                    </select>
                    <input type="number" class="form-control form-control-sm list-edit-year" value="<?= esc($list->year) ?>" />
                    <button class="btn btn-sm btn-primary d-none save-list-btn" data-list-id="<?= $list->id ?>">
                      <i class="fas fa-save"></i> Salvar
                    </button>
                  </div>
                </div>

                <!-- botão de gerenciar, editar e excluir -->
                <div class="col-12 col-md-4">
                  <div class="d-flex flex-row align-items-center justify-content-center justify-content-md-end gap-2 mt-3 mt-md-0">
                    <a href="<?= base_url(['lista-de-pagamentos', 'gerenciar', $list->id]) ?>" class="btn btn-sm btn-success">
                      <i class="fas fa-edit"></i> Gerenciar
                    </a>
                    <button class="btn btn-sm btn-warning edit-list-btn" data-list-id="<?= $list->id ?>">
                      <i class="fas fa-pencil-alt"></i> Editar
                    </button>
                    <button class="btn btn-sm btn-danger delete-list-btn" data-list-id="<?= $list->id ?>">
                      <i class="fas fa-trash-alt"></i> Excluir
                    </button>

                  </div>
                </div>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
        <?php if ($pager->getPageCount() > 1): ?>
          <?= $pager->links('default', 'pagination', ['query' => ['search' => $search]]) ?>
        <?php endif; ?>
      <?php else: ?>
        <p>Nenhuma lista criada até o momento.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmação de Exclusão</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Tem certeza de que deseja excluir esta lista de pagamento? Esta ação não pode ser desfeita.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Excluir</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="warningListModal" tabindex="-1" aria-labelledby="warningListModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="warningListModalLabel">Aviso</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-modal-warning">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ok</button>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
  $(document).ready(function() {
    let listIdToDelete;
    let warningModal = new bootstrap.Modal(document.getElementById('warningListModal'), {});


    $('#toggleButton').click(function() {
      $('#addListForm').collapse('toggle');
    });

    // Quando clicar no botão de excluir, mostrar o modal de confirmação
    $('.delete-list-btn').click(function() {
      listIdToDelete = $(this).data('list-id');
      $('#confirmDeleteModal').modal('show');
    });

    // Quando confirmar a exclusão
    $('#confirmDeleteBtn').click(function() {
      if (listIdToDelete) {
        let $button = $(this);

        // Desativar o botão e mostrar o spinner com "Aguarde..."
        $button.prop('disabled', true).html(`
          <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Aguarde...
        `);

        setTimeout(function() {
          window.location.href = '/payments/delete/' + listIdToDelete;

        }, 800);

      }
    });

    $('#listForm').on('submit', function(event) {
      // Desativar o botão de submit para evitar múltiplos cliques
      let $button = $('#createListButton');
      $button.prop('disabled', true);

      // Mostrar o spinner e alterar o texto
      $('#buttonText').text('Carregando');
      $('#buttonSpinner').removeClass('d-none');
    });

    // Edição da lista
    $('.edit-list-btn').click(function() {
      let $listItem = $(this).closest('.list-group-item');
      $listItem.find('.list-name').addClass('d-none');
      $listItem.find('.edit-fields').removeClass('d-none').addClass('d-flex');
      $(this).addClass('d-none');
      $listItem.find('.save-list-btn').removeClass('d-none');
    });

    // Salvar edição da lista
    $('.save-list-btn').click(function() {
      let listId = $(this).data('list-id');
      let $listItem = $(this).closest('.list-group-item');
      let newName = $listItem.find('.list-edit-name').val();
      let newMonth = $listItem.find('.list-edit-month').val();
      let newYear = $listItem.find('.list-edit-year').val();

      let $button = $(this);
      $button.prop('disabled', true).html(`
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Aguarde...
      `);

      $.ajax({
        url: '<?= base_url('payments/editList') ?>',
        method: 'POST',
        data: {
          list_id: listId,
          name: newName,
          month: newMonth,
          year: newYear
        },
        success: function(response) {
          if (response.success) {
            // Atualizar os dados diretamente na interface sem reload
            $listItem.find('.list-name').text(`${newName} (${newMonth}/${newYear})`).removeClass('d-none');
            $listItem.find('.edit-fields').removeClass('d-flex').addClass('d-none');
            $button.addClass('d-none');
            $listItem.find('.edit-list-btn').removeClass('d-none');

            // Mostrar mensagem de sucesso no modal
            $('.modal-body.text-modal-warning').text('Alterações salvas com sucesso.');
            warningModal.show();
          } else {
            $('.modal-body.text-modal-warning').text('Erro ao salvar alterações.');
            warningModal.show();
          }
        },
        error: function() {
          $('.modal-body.text-modal-warning').text('Erro ao salvar alterações.');
          warningModal.show();
        },
        complete: function() {
          // Restaurar o botão
          $button.prop('disabled', false).html('<i class="fas fa-save"></i> Salvar');
        }
      });
    });
  });
</script>
<?= $this->endSection() ?>