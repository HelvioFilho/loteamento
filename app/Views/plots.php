<?= $this->extend('template/layout') ?>

<?= $this->section('style') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Lotes</h1>
  </div>

  <!-- Form para Escolher Usuário -->
  <div class="mb-4" id="addListForm">
    <form id="listForm">
      <div class="row">
        <div class="col-12 col-md-3">
          <label for="user" class="form-label">Escolha um usuário</label>
          <select class="form-control" id="user" name="user" required>
            <option value="" selected disabled>Selecione um Usuário</option>
            <?php foreach ($users as $user) : ?>
              <option value="<?= $user->id ?>"><?= $user->name ?></option>
            <?php endforeach; ?>
          </select>
          <div class="invalid-feedback">A seleção do usuário é obrigatória.</div>
        </div>
      </div>
      <button type="button" class="btn btn-primary mt-3" id="createListButton">
        <span id="buttonText">Escolher Usuário</span>
        <span id="buttonSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
      </button>
    </form>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
  $(document).ready(function() {
    // Evento ao clicar no botão "Escolher Usuário"
    $('#createListButton').click(function() {
      const selectedUserId = $('#user').val();

      if (!selectedUserId) {
        // Adiciona a classe is-invalid para mostrar a mensagem de erro
        $('#user').addClass('is-invalid');
      } else {
        // Remove a classe is-invalid, caso o campo seja preenchido
        $('#user').removeClass('is-invalid');

        // Desativa o botão e mostra o spinner
        let $button = $(this);
        $button.prop('disabled', true);
        $('#buttonText').text('Carregando');
        $('#buttonSpinner').removeClass('d-none');

        setTimeout(function() {
          window.location.href = '/lotes/gerenciar/' + selectedUserId;
        }, 800);
        // Redireciona para a página de dependentes do usuário selecionado
      }
    });

    // Quando o valor do campo "user" mudar, remover o estado de erro
    $('#user').change(function() {
      $(this).removeClass('is-invalid');
    });
  });
</script>
<?= $this->endSection() ?>