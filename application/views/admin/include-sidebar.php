<!DOCTYPE html>

<?php $current_url = current_url();

$settings = getSettings('system_settings', true); ?>


<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="<?= base_url('assets/admin') ?>" data-template="vertical-menu-template-free">

</html>
<!-- Menu -->


<!-- Sidebar -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="<?= base_url('admin/dashboard') ?>" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="<?= base_url()  . getSettings('favicon') ?>" class="brand-image">
            </span>
            <span class="brand-text  fw-bolder ms-2"><?= $settings['app_name']; ?></span>

        </a>

        <a href="<?= base_url('admin/dashboard') ?>" class="app-brand-link">

        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1 ps ps--active-y">

        <!-- Add icons to the links using the .nav-icon class
       with font-awesome or any other icon font library -->

        <!-- Dashboard -->
        <li class="menu-item <?= ($current_url == base_url('admin/dashboard')) ? 'active' : '' ?>">
            <a href="<?= base_url('/admin/dashboard') ?>" class="menu-link ">
                <i class="ion-icon-desktop-outline"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>

    </ul>
</aside>


<!-- /.sidebar -->
<!-- / Menu -->