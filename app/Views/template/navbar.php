<?php


function active($uri)
{
  $currentUrl = current_url();
  $splotUrl = explode("/", $currentUrl);
  $link = $splotUrl[3];
  return $uri === $link ? 'active' : '';
}

?>

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url() ?>">
    <div class="sidebar-brand-icon">
      <i class="fas fa-map-signs"></i>
    </div>
    <div class="sidebar-brand-text mx-3">Chacreamento</div>
  </a>
  <!-- Divider -->
  <hr class="sidebar-divider my-0">

  <!-- Heading -->
  <div class="sidebar-heading mt-3">
    Principal
  </div>

  <li class="nav-item <?= active('user') ?>">
    <a class="nav-link" href="<?= base_url() ?>">
      <i class="fas fa-users"></i>
      <span>Usuários</span></a>
  </li>
  <li class="nav-item <?= active('dependentes') ?>">
    <a class="nav-link" href="<?= base_url('dependentes') ?>">
      <i class="fas fa-user-friends"></i>
      <span>Dependentes</span></a>
  </li>
  <li class="nav-item <?= active('lotes') ?>">
    <a class="nav-link" href="<?= base_url('lotes') ?>">
      <i class="fas fa-map-signs"></i>
      <span>Lotes</span></a>
  </li>
  <li class="nav-item <?= active('lista-de-pagamentos') ?>">
    <a class="nav-link" href="<?= base_url('lista-de-pagamentos') ?>">
      <i class="far fa-list-alt"></i>
      <span>Listas</span></a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider">

  <!-- Heading -->
  <div class="sidebar-heading">
    Ajustes
  </div>

  <!-- Nav Item - Charts -->
  <li class="nav-item">
    <a class="nav-link" href="<?= base_url() ?>">
      <i class="fas fa-user-cog"></i>
      <span>Minha Conta</span></a>
  </li>

  <!-- Nav Item - Tables -->
  <li class="nav-item">
    <a class="nav-link" href="<?= base_url() ?>">
      <i class="fas fa-user-plus"></i>
      <span>Criar Conta</span></a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block">

  <!-- Sidebar Toggler (Sidebar) -->
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>

</ul>
<!-- End of Sidebar -->