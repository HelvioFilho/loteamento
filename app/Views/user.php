<?= $this->extend('template/layout') ?>

<?= $this->section('style') ?>
<style>
  #imageLabel {
    width: 249px;
    height: 249px;
    cursor: pointer;
    position: relative;
  }

  #preview {
    height: 250px;
    width: 250px;
    object-fit: cover;
    display: none;
    position: absolute;
    top: -1px;
    left: -1px;
  }

  .card-img {
    object-fit: cover;
    height: 150px;
    width: 150px;
  }

  #removeImageBtn {
    width: 200px;
    display: none;
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Begin Page Content -->
<div class="container-fluid">
  <!-- Content Row -->

  <div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Usuários</h1>
    <button class="btn btn-sm btn-primary shadow-sm" data-bs-toggle="collapse" data-bs-target="#addUserForm" id="toggleButton">
      <i class="fas fa-minus-circle fa-sm text-white-50 d-none" id="iconMinus"></i>
      <i class="fas fa-plus-circle fa-sm text-white-50" id="iconPlusButton"></i> <span class="d-none d-md-inline">Adicionar Usuário</span>
    </button>
  </div>

  <!-- Form Collapse -->
  <div class="collapse" id="addUserForm">
    <form id="userForm" method="POST" action="<?= base_url('createUser') ?>" enctype="multipart/form-data">
      <div class="row">
        <!-- Image field -->
        <div class="col-xl-4 col-lg-5">
          <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">Foto</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body d-flex flex-column justify-content-center align-items-center">
              <label for="inputImage" class="d-flex flex-column justify-content-center align-items-center border border-primary" id="imageLabel">
                <i class="fas fa-plus-circle fa-3x text-primary" id="iconPlus"></i>
                <span class="text-primary" id="addPhotoText">Adicionar Foto</span>
                <img id="preview" />
              </label>
              <input type="file" id="inputImage" name="image" class="d-none" accept="image/*" />
              <div class="invalid-feedback mt-2 text-center">A foto é obrigatória.</div>
              <button id="removeImageBtn" class="btn btn-danger mt-1">Remover Imagem</button>
            </div>
          </div>
        </div>
        <!-- Area Chart -->
        <div class="col-xl-8 col-lg-7">
          <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">Adicionar Usuário</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
              <!-- <form id="userForm"> -->
              <div class="row mb-3">
                <div class="col-md-6">
                  <div class="form-floating mb-3 mb-md-0">
                    <input class="form-control" id="inputFirstName" name="name" type="text" placeholder="Coloque o seu nome" />
                    <label for="inputFirstName">Nome</label>
                    <div class="invalid-feedback">O campo Nome é obrigatório.</div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <input class="form-control" id="inputBirthDate" name="birth_date" type="text" placeholder="Data de Nascimento" />
                    <label for="inputBirthDate">Nascimento</label>
                    <div class="invalid-feedback">Data de Nascimento inválida.</div>
                  </div>
                </div>
              </div>
              <div class="form-floating mb-3">
                <input class="form-control" id="inputEmail" type="email" name="email" placeholder="nome@exemplo.com" />
                <label for="inputEmail">Email</label>
                <div class="invalid-feedback">Email inválido.</div>
              </div>
              <div class="row mb-3">
                <div class="col-md-6">
                  <div class="form-floating mb-3 mb-md-0">
                    <input class="form-control" id="inputPhone" type="text" name="phone" placeholder="(99) 99999-9999" />
                    <label for="inputPhone">Telefone</label>
                    <div class="invalid-feedback">Número de telefone inválido.</div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating mb-3 mb-md-0">
                    <input class="form-control" id="inputCPF" type="text" name="cpf" placeholder="CPF" />
                    <label for="inputCPF">CPF</label>
                    <div class="invalid-feedback">CPF inválido.</div>
                  </div>
                </div>
              </div>
              <div class="form-floating mb-3">
                <input class="form-control" id="inputAddress" type="text" name="address" placeholder="Av. Julio de Castilhos, 1000" />
                <label for="inputAddress">Endereço</label>
                <div class="invalid-feedback">O campo Endereço é obrigatório.</div>
              </div>
              <!-- </form> -->
            </div>
          </div>
        </div>
      </div>
    </form>
    <!-- Button Below Everything -->
    <div class="row mb-4">
      <div class="col">
        <div class="d-flex justify-content-center justify-content-md-end align-items-center">
          <button class="btn btn-primary btn-block w-50 w-md-25" id="createUserBtn">Criar Usuário</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Content Row -->
  <div class="row">

    <!-- Content Column -->
    <div class="col-lg-12 mb-4">

      <!-- Project Card Example -->
      <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Todos os Usuários</h6>
          <span class="d-flex flex-row align-items-center">
            <form id="searchForm" method="GET" action="<?= base_url('user') ?>" class="d-flex">
              <input class="form-control form-control-sm me-2" id="searchInput" type="text" name="search" placeholder="Pesquisar Usuário" value="<?= isset($search) ? esc($search) : '' ?>" />
              <button type="submit" class="btn btn-primary btn-sm" id="searchButton"><i class="fas fa-search"></i></button>
            </form>
          </span>
        </div>
        <?php if (count($users) > 0) : ?>
          <div class="card-body">
            <?php foreach ($users as $user) : ?>
              <div class="row">
                <div class="col">
                  <div class="card shadow mb-4 user-card" data-user-id="<?= $user->id ?>">
                    <div class="card-header py-3">
                      <div class="d-flex align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary name-view"><?= $user->name ?></h6>
                        <button class="btn btn-sm btn-link text-primary ms-2 edit-name-btn" data-id="<?= $user->id ?>">
                          <i class="fas fa-edit"></i>
                        </button>
                      </div>
                      <div class="name-edit d-none">
                        <input type="text" class="form-control name-edit-input" value="<?= $user->name ?>" />
                        <div class="invalid-feedback">O campo Nome é obrigatório.</div>
                        <button class="btn btn-primary btn-sm mt-2 save-name-btn">Salvar</button>
                        <button class="btn btn-secondary btn-sm mt-2 cancel-name-btn">Cancelar</button>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-12 col-md-8 d-flex flex-column gap-1 mt-3">
                          <img class="card-img user-image align-self-center align-self-md-start" src="<?= base_url(['images', 'users', $user->image]) ?>" alt="Imagem do Usuário <?= $user->name ?>" data-id="<?= $user->id ?>" data-original-src="<?= base_url(['images', 'users', $user->image]) ?>">


                          <div class="mt-2 image-action-buttons d-none" data-id="<?= $user->id ?>">
                            <button class="btn btn-success btn-sm save-image-btn"><i class="fas fa-check"></i> Salvar</button>
                            <button class="btn btn-danger btn-sm cancel-image-btn"><i class="fas fa-times"></i> Cancelar</button>
                          </div>

                          <button class="btn btn-sm btn-link text-primary mt-2 edit-image-btn align-self-md-start" data-id="<?= $user->id ?>">
                            <i class="fas fa-edit"></i> Alterar Imagem
                          </button>

                          <input type="file" class="form-control-file d-none image-upload-input" data-id="<?= $user->id ?>" accept="image/*">
                          <div class="invalid-feedback mt-2">Por favor, selecione uma imagem válida.</div>
                        </div>
                        <!-- Informações do Usuário -->
                        <div class="col-12 col-md-8 d-flex flex-column gap-1 mt-3 user-info">
                          <div class="my-2 warning-field">

                          </div>
                          <div class="">
                            <span><strong>Email:</strong> <span class="view-value email-view"><?= $user->email ?></span>
                              <input type="email" class="edit-value form-control email-edit d-none" value="<?= $user->email ?>">
                            </span><br>
                            <span><strong>Telefone:</strong> <span class="view-value phone-view"><?= $user->phone ?></span>
                              <input type="text" class="edit-value form-control phone-edit d-none" value="<?= $user->phone ?>">
                            </span><br>
                            <span><strong>CPF:</strong> <span class="view-value cpf-view"><?= $user->cpf ?></span>
                              <input type="text" class="edit-value form-control cpf-edit d-none" value="<?= $user->cpf ?>">
                            </span><br>
                            <span><strong>Nascimento:</strong> <span class="view-value birth-date-view"><?= $user->birth_date ?></span>
                              <input type="text" class="edit-value form-control birth-date-edit d-none" value="<?= $user->birth_date ?>">
                            </span><br>
                            <span><strong>Endereço:</strong> <span class="view-value address-view"><?= $user->address ?></span>
                              <input type="text" class="edit-value form-control address-edit d-none" value="<?= $user->address ?>">
                            </span><br>
                            <span><strong>Code:</strong> <span class="view-value id-code-view"><?= $user->id_code ?></span></span><br>
                          </div>
                        </div>

                        <!-- QRCode do Usuário -->
                        <div class="col-12 col-md-4 d-flex justify-content-center mt-4 mb-3 mt-md-0">
                          <img class="card-img" src="<?= base_url(['images', 'qrcode', 'qrcode' . $user->id_code . '.png']) ?>" alt="QrCode do usuário">
                        </div>
                      </div>
                      <div class="d-flex flex-row gap-3 mt-3 justify-content-center justify-content-md-start">
                        <button class="btn btn-info btn-edit" data-id="<?= $user->id ?>"><i class="fas fa-edit"></i> Editar</button>
                        <button class="btn btn-danger btn-delete" data-id="<?= $user->id ?>" data-name="<?= $user->name ?>"><i class="fas fa-trash"></i> Excluir</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          <?php if ($pager->getPageCount() > 1): ?>
            <?= $pager->links('default', 'pagination', ['query' => ['search' => $search]]) ?>
          <?php endif; ?>
        <?php else : ?>
          <div class="card-body">
            <h1 class="h4 text-center">Nenhum usuário encontrado!</h1>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<!-- /.container-fluid -->

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
<?php
$messageError = '';
if (session()->has('error')) {
  $messageError = session()->get('error');
}
if (session()->has('success')) {
  $messageError = session()->get('success');
}

?>

<script>
  let warningMessageError = "<?= $messageError ?>";

  $(document).ready(function() {
    var warningModal = new bootstrap.Modal(document.getElementById('warningModal'), {});

    if (warningMessageError !== '') {
      $('#modalErrorMessage').addClass('text-danger').text(warningMessageError);
      warningModal.show();
    }

    $('.btn-close').click(function() {
      warningModal.hide();
    });

    $('#closeModal').click(function() {
      warningModal.hide();
    });

    $('#toggleButton').click(function() {
      $('#iconPlusButton').toggleClass('d-none');
      $('#iconMinus').toggleClass('d-none');
    });

    $('#inputImage').change(function(event) {
      previewImage(event);
    });

    $('#page-top').on('dragover', function(event) {
      event.preventDefault();
    });

    $('#page-top').on('drop', function(event) {
      event.preventDefault();
      const file = event.originalEvent.dataTransfer.files[0];
      if (file && file.type.startsWith('image/')) {
        $('#inputImage')[0].files = event.originalEvent.dataTransfer.files;
        previewImage({
          target: $('#inputImage')[0]
        });
      }
    });

    $('#removeImageBtn').click(function() {
      removeImage();
    });

    function previewImage(event) {
      const input = event.target;
      const preview = $('#preview');
      const iconPlus = $('#iconPlus');
      const addPhotoText = $('#addPhotoText');
      const removeImageBtn = $('#removeImageBtn');

      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.attr('src', e.target.result);
          preview.show();
          iconPlus.hide();
          addPhotoText.hide();
          removeImageBtn.show();
          $('#inputImage').removeClass('is-invalid').addClass('is-valid');
        }
        reader.readAsDataURL(input.files[0]);
      }
    }

    function removeImage() {
      const input = $('#inputImage');
      const preview = $('#preview');
      const iconPlus = $('#iconPlus');
      const addPhotoText = $('#addPhotoText');
      const removeImageBtn = $('#removeImageBtn');

      input.val("");
      preview.attr('src', "");
      preview.hide();
      iconPlus.show();
      addPhotoText.show();
      removeImageBtn.hide();
      $('#inputImage').removeClass('is-valid').addClass('is-invalid');
    }

    // Apply input masks
    $('#inputPhone').inputmask('(99) 99999-9999');
    $('#inputCPF').inputmask('999.999.999-99');
    $('#inputBirthDate').inputmask('99/99/9999');

    $('.phone-edit').inputmask('(99) 99999-9999');
    $('.cpf-edit').inputmask('999.999.999-99');
    $('.birth-date-edit').inputmask('99/99/9999');

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


    // CPF Validation Function
    function validateCPF(cpf) {
      cpf = cpf.replace(/[\.\-]/g, "");
      if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
        return false;
      }
      let sum = 0;
      let remainder;
      for (let i = 1; i <= 9; i++) {
        sum += parseInt(cpf.substring(i - 1, i)) * (11 - i);
      }
      remainder = (sum * 10) % 11;
      if (remainder === 10 || remainder === 11) remainder = 0;
      if (remainder !== parseInt(cpf.substring(9, 10))) return false;
      sum = 0;
      for (let i = 1; i <= 10; i++) {
        sum += parseInt(cpf.substring(i - 1, i)) * (12 - i);
      }
      remainder = (sum * 10) % 11;
      if (remainder === 10 || remainder === 11) remainder = 0;
      return remainder === parseInt(cpf.substring(10, 11));
    }

    // Blur event validation for CPF, Phone, and Address fields
    $('#inputFirstName').blur(function() {
      if ($(this).val().trim() === '') {
        $(this).addClass('is-invalid');
      } else {
        $(this).removeClass('is-invalid').addClass('is-valid');
      }
    });

    $('#inputBirthDate').blur(function() {
      if (!isValidDate($(this).val().trim())) {
        $(this).addClass('is-invalid');
      } else {
        $(this).removeClass('is-invalid').addClass('is-valid');
      }
    });

    $('#inputEmail').blur(function() {
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test($(this).val().trim())) {
        $(this).addClass('is-invalid');
      } else {
        $(this).removeClass('is-invalid').addClass('is-valid');
      }
    });

    $('#inputCPF').blur(function() {
      const cpfValue = $(this).val().trim();
      if (!validateCPF(cpfValue)) {
        $(this).addClass('is-invalid');
      } else {
        $(this).removeClass('is-invalid').addClass('is-valid');
      }
    });

    $('#inputPhone').blur(function() {
      if (!$('#inputPhone').inputmask("isComplete")) {
        $(this).addClass('is-invalid');
      } else {
        $(this).removeClass('is-invalid').addClass('is-valid');
      }
    });

    $('#inputAddress').blur(function() {
      if ($(this).val().trim() === '') {
        $(this).addClass('is-invalid');
      } else {
        $(this).removeClass('is-invalid').addClass('is-valid');
      }
    });

    // Capturar o evento de submit do formulário
    $('#userForm').on('submit', function(event) {
      event.preventDefault();
      $('#createUserBtn').prop('disabled', true).html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`);

      let isValid = true;

      if ($('#inputFirstName').val().trim() === '') {
        $('#inputFirstName').addClass('is-invalid');
        isValid = false;
      } else {
        $('#inputFirstName').removeClass('is-invalid').addClass('is-valid');
      }

      const birthDateValue = $('#inputBirthDate').val().trim();
      if (!isValidDate(birthDateValue)) {
        $('#inputBirthDate').addClass('is-invalid');
        isValid = false;
      } else {
        $('#inputBirthDate').removeClass('is-invalid').addClass('is-valid');
      }

      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test($('#inputEmail').val().trim())) {
        $('#inputEmail').addClass('is-invalid');
        isValid = false;
      } else {
        $('#inputEmail').removeClass('is-invalid').addClass('is-valid');
      }

      if (!$('#inputPhone').inputmask("isComplete")) {
        $('#inputPhone').addClass('is-invalid');
        isValid = false;
      } else {
        $('#inputPhone').removeClass('is-invalid').addClass('is-valid');
      }

      const cpfValue = $('#inputCPF').val().trim();
      if (!validateCPF(cpfValue)) {
        $('#inputCPF').addClass('is-invalid');
        isValid = false;
      } else {
        $('#inputCPF').removeClass('is-invalid').addClass('is-valid');
      }

      if ($('#inputAddress').val().trim() === '') {
        $('#inputAddress').addClass('is-invalid');
        isValid = false;
      } else {
        $('#inputAddress').removeClass('is-invalid').addClass('is-valid');
      }

      if ($('#inputImage').val() === '') {
        $('#inputImage').addClass('is-invalid');
        isValid = false;
      } else {
        $('#inputImage').removeClass('is-invalid').addClass('is-valid');
      }

      if (isValid) {
        this.submit();
      } else {
        $('#createUserBtn').prop('disabled', false).html('Criar Usuário');
      }
    });

    // Clique no botão de criar usuário também deve submeter o formulário
    $('#createUserBtn').click(function(event) {
      event.preventDefault();
      $('#userForm').submit(); // Dispara o evento de submit que já está configurado
    });


    $('.btn-delete').click(function() {
      const userId = $(this).data('id');
      const name = $(this).data('name');
      $('#modalErrorMessage').addClass('text-danger').html(`<span>Tem certeza que deseja excluir ${name} ? Essa ação não pode ser desfeita.</span>`);
      $('.modal-footer').html(`<button id="closeModal" class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
      <button id="removeUser" class="btn btn-danger" type="button"  data-id="${userId}">Excluir</button>`);
      warningModal.show();
    });

    $(document).on('click', '#removeUser', async function() {
      $('#modalErrorMessage').removeClass('text-danger').addClass('text-muted').html(`
        <span>Processando a exclusão...</span>
      `);

      $('.modal-footer').html(`<button  class="btn btn-danger" type="button" ><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></button>`);
      const userId = $(this).data('id');
      setTimeout(function() {
        window.location.href = '/deleteUser/' + userId;
      }, 800);

    });

    // Adiciona evento ao clicar em Editar
    $(document).on('click', '.btn-edit', function() {
      let $button = $(this);
      let $userCard = $button.closest('.user-card');
      let isEditing = $button.data('editing');

      if (!isEditing) {
        // Entrar no modo de edição
        $userCard.find('.view-value').addClass('d-none');
        $userCard.find('.edit-value').removeClass('d-none');

        $button.html('<i class="fas fa-check"></i> Confirmar');
        $button.data('editing', true);

        // Aplicar máscaras novamente nos inputs que foram criados dinamicamente
        $userCard.find('.phone-edit').inputmask('(99) 99999-9999');
        $userCard.find('.cpf-edit').inputmask('999.999.999-99');
        $userCard.find('.birth-date-edit').inputmask('99/99/9999');
      } else {
        // Sair do modo de edição e salvar as alterações
        let userId = $button.data('id');
        let updatedData = {
          email: $userCard.find('.email-edit').val(),
          phone: $userCard.find('.phone-edit').val(),
          cpf: $userCard.find('.cpf-edit').val(),
          birth_date: $userCard.find('.birth-date-edit').val(),
          address: $userCard.find('.address-edit').val()
        };

        // Fazer a validação dos campos antes de enviar
        let valid = validateUserData($userCard, updatedData);
        if (!valid) {
          return; // Não prosseguir se houver erros de validação
        }

        // Enviar as alterações para o backend via AJAX
        $.ajax({
          url: '<?= base_url('updateUser') ?>/' + userId,
          method: 'POST',
          data: updatedData,
          success: function(response) {
            if (response.success) {
              // Atualizar as informações na visualização
              $userCard.find('.email-view').text(updatedData.email);
              $userCard.find('.phone-view').text(updatedData.phone);
              $userCard.find('.cpf-view').text(updatedData.cpf);
              $userCard.find('.birth-date-view').text(updatedData.birth_date);
              $userCard.find('.address-view').text(updatedData.address);

              // Sair do modo de edição
              $userCard.find('.view-value').removeClass('d-none');
              $userCard.find('.edit-value').addClass('d-none');
              $button.html('<i class="fas fa-edit"></i> Editar');
              $button.data('editing', false);

              $('#modalErrorMessage').removeClass('text-danger').addClass('text-success').text('Alterações salvas com sucesso!');
              warningModal.show();
            } else {
              $('#modalErrorMessage').removeClass('text-success').addClass('text-danger').text('Erro ao salvar as alterações!');
              warningModal.show();
            }
          },
          error: function() {
            $('#modalErrorMessage').removeClass('text-success').addClass('text-danger').text('Erro ao salvar as alterações!');
            warningModal.show();
          }
        });
      }
    });

    // Função para validação
    function validateUserData($userCard, data) {
      let valid = true;

      // Limpar estados de erro anteriores
      $userCard.find('.edit-value').removeClass('is-invalid');
      $userCard.find('.invalid-feedback').remove();

      // Validação do Email
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test(data.email)) {
        valid = false;
        let $emailInput = $userCard.find('.email-edit');
        $emailInput.addClass('is-invalid');
        $emailInput.after('<div class="invalid-feedback">E-mail inválido.</div>');
      }

      // Validação do CPF
      if (!validateCPF(data.cpf)) {
        valid = false;
        let $cpfInput = $userCard.find('.cpf-edit');
        $cpfInput.addClass('is-invalid');
        $cpfInput.after('<div class="invalid-feedback">CPF inválido.</div>');
      }

      // Validação da Data de Nascimento
      if (!isValidDate(data.birth_date)) {
        valid = false;
        let $birthDateInput = $userCard.find('.birth-date-edit');
        $birthDateInput.addClass('is-invalid');
        $birthDateInput.after('<div class="invalid-feedback">Data de nascimento inválida.</div>');
      }

      // Validação do Telefone usando a máscara
      let $phoneInput = $userCard.find('.phone-edit');
      if (!$phoneInput.inputmask("isComplete")) {
        valid = false;
        $phoneInput.addClass('is-invalid');
        $phoneInput.after('<div class="invalid-feedback">Telefone inválido.</div>');
      }

      // Validação do Endereço
      if (data.address.trim() === '') {
        valid = false;
        let $addressInput = $userCard.find('.address-edit');
        $addressInput.addClass('is-invalid');
        $addressInput.after('<div class="invalid-feedback">O campo Endereço é obrigatório.</div>');
      }

      return valid;
    }

    $(document).on('click', '.edit-name-btn', function() {
      const $cardHeader = $(this).closest('.card-header');
      $cardHeader.find('.name-view').addClass('d-none');
      $cardHeader.find('.edit-name-btn').addClass('d-none');
      $cardHeader.find('.name-edit').removeClass('d-none');
    });

    // Ao clicar no botão cancelar
    $(document).on('click', '.cancel-name-btn', function() {
      const $cardHeader = $(this).closest('.card-header');
      $cardHeader.find('.name-view').removeClass('d-none');
      $cardHeader.find('.edit-name-btn').removeClass('d-none');
      $cardHeader.find('.name-edit').addClass('d-none');
      $cardHeader.find('.name-edit-input').removeClass('is-invalid');
    });

    // Ao clicar no botão salvar
    $(document).on('click', '.save-name-btn', function() {
      const $cardHeader = $(this).closest('.card-header');
      const $nameInput = $cardHeader.find('.name-edit-input');
      const newName = $nameInput.val().trim();

      // Validação: campo nome não pode ser vazio
      if (newName === '') {
        $nameInput.addClass('is-invalid');
        return;
      } else {
        $nameInput.removeClass('is-invalid');
      }

      // ID do usuário
      const userId = $cardHeader.find('.edit-name-btn').data('id');

      // Enviar para o servidor
      $.ajax({
        url: '<?= base_url('updateUserName') ?>/' + userId,
        method: 'POST',
        data: {
          name: newName
        },
        success: function(response) {
          if (response.success) {
            // Atualizar a exibição do nome
            $cardHeader.find('.name-view').text(newName);
            // Voltar ao modo de visualização
            $cardHeader.find('.name-view').removeClass('d-none');
            $cardHeader.find('.edit-name-btn').removeClass('d-none');
            $cardHeader.find('.name-edit').addClass('d-none');
            $('#modalErrorMessage').removeClass('text-danger').addClass('text-success').text('Nome alterado com sucesso!');
            warningModal.show();
          } else {
            $('#modalErrorMessage').removeClass('text-success').addClass('text-danger').text('Erro ao atualizar o nome. Tente novamente.');
            warningModal.show();
          }
        },
        error: function() {
          $('#modalErrorMessage').removeClass('text-success').addClass('text-danger').text('Erro ao atualizar o nome. Tente novamente.');
          warningModal.show();
        }
      });
    });

    // Quando clicar no botão para editar a imagem
    // Quando clicar no botão para editar a imagem
    $(document).on('click', '.edit-image-btn', function() {
      const userId = $(this).data('id');
      const $inputFile = $(this).closest('.col-12').find('.image-upload-input');
      $inputFile.click(); // Abrir o seletor de arquivos
    });

    // Quando selecionar uma imagem
    $(document).on('change', '.image-upload-input', function() {
      const file = this.files[0];
      if (file) {
        const userId = $(this).data('id');
        const reader = new FileReader();

        // Salvar a URL original da imagem, caso ainda não esteja salva
        const $userImage = $('.user-image[data-id="' + userId + '"]');
        if (!$userImage.data('original-src-saved')) {
          $userImage.attr('data-original-src', $userImage.attr('src'));
          $userImage.data('original-src-saved', true);
        }

        // Pré-visualização da imagem
        reader.onload = function(e) {
          $userImage.attr('src', e.target.result);
          $userImage.addClass('new-image-preview');

          // Mostrar botões de salvar e cancelar
          const $actionButtons = $('.image-action-buttons[data-id="' + userId + '"]');
          $actionButtons.removeClass('d-none');
        };
        reader.readAsDataURL(file);
      }
    });

    // Cancelar edição da imagem
    $(document).on('click', '.cancel-image-btn', function() {
      const userId = $(this).closest('.image-action-buttons').data('id');
      const $userImage = $('.user-image[data-id="' + userId + '"]');

      // Reverter para a imagem original
      const originalSrc = $userImage.attr('data-original-src');
      $userImage.attr('src', originalSrc);
      $userImage.removeClass('new-image-preview');

      // Esconder os botões de ação e resetar o input
      $(this).closest('.image-action-buttons').addClass('d-none');
      $('.image-upload-input[data-id="' + userId + '"]').val(''); // Resetar o input file
    });

    // Salvar a nova imagem
    $(document).on('click', '.save-image-btn', function() {
      const userId = $(this).closest('.image-action-buttons').data('id');
      const $inputFile = $('.image-upload-input[data-id="' + userId + '"]')[0];

      if ($inputFile.files.length > 0) {
        const file = $inputFile.files[0];
        const formData = new FormData();
        formData.append('image', file);
        formData.append('id', userId);

        $.ajax({
          url: '<?= base_url('updateUserImage') ?>/' + userId,
          method: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            if (response.success) {

              // Atualizar a URL original com a nova imagem salva
              const $userImage = $('.user-image[data-id="' + userId + '"]');
              $userImage.attr('data-original-src', $userImage.attr('src'));

              $('#modalErrorMessage').removeClass('text-danger').addClass('text-success').text('Imagem atualizada com sucesso!');
              warningModal.show();
            } else {
              $('#modalErrorMessage').removeClass('text-success').addClass('text-danger').text('Erro ao atualizar a imagem. Tente novamente.');
              warningModal.show();
            }

            // Esconder botões e resetar o input
            const $actionButtons = $('.image-action-buttons[data-id="' + userId + '"]');
            $actionButtons.addClass('d-none');
            $inputFile.value = '';
          },
          error: function() {
            $('#modalErrorMessage').removeClass('text-success').addClass('text-danger').text('Erro ao atualizar a imagem. Tente novamente.');
            warningModal.show();
          }
        });
      }
    });

  });
</script>
<?= $this->endSection() ?>