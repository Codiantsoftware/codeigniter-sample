<nav class="layout-navbar container-fluid navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

        <ul class="navbar-nav flex-row align-items-center ms-auto">

            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <div class="form-control mr-sm-2 d-flex p-0">
                        <div class="avatar avatar-online">
                            <img src=<?= base_url("/assets/admin/images/avatar.jpg") ?> alt class="w-px-40 h-auto rounded-circle avatar avatar-online" />
                        </div>
                        <b>
                            <p class="image-text">Hi, <?= ucfirst($this->ion_auth->user()->row()->username) ?></p>
                        </b>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <?php if ($this->ion_auth->is_admin()) { ?>

                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src=<?= base_url("/assets/admin/images/avatar.jpg") ?> alt class="w-px-40 h-auto rounded-circle avatar avatar-online" />
                                    </div>
                                </div>

                                <div class="flex-grow-1 p-2">
                                    <b>
                                        <p class="image-text"> <?= ucfirst($this->ion_auth->user()->row()->username) ?></p>
                                    </b>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>

                        <a href="<?= base_url('admin/home/profile') ?>" class="dropdown-item">
                            <i class="fas fa-user-circle mr-2 fa-lg"></i> Profile
                        </a>

                        <a href="<?= base_url('admin/home/logout') ?>" class="dropdown-item">
                            <i class="fa fa-sign-out-alt mr-2 fa-lg"></i> Log Out
                        </a>

                    <?php } else { ?>
                        <a href="#" class="dropdown-item">Welcome <b><?= ucfirst($this->ion_auth->user()->row()->username) ?> </b>! </a>
                        <a href="" class="dropdown-item"><i class="fas fa-user mr-2"></i> Profile </a>
                        <a href="#" class="dropdown-item "><i class="fa fa-sign-out-alt mr-2"></i> Log Out </a>
                    <?php } ?>
                </div>
            </li>


            <!--/ User -->
        </ul>
    </div>
</nav>