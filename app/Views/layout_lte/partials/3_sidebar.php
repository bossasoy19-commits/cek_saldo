 <!--begin::Sidebar-->
 <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
     <!--begin::Sidebar Brand-->
     <div class="sidebar-brand">
         <!--begin::Brand Link-->
         <a href="<?= base_url('admin_lte/') ?>index.html" class="brand-link">
             <!--begin::Brand Image-->
             <img src="<?= base_url('admin_lte/') ?>assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image opacity-75 shadow" />
             <!--end::Brand Image-->
             <!--begin::Brand Text-->
             <?php if (ENVIRONMENT == 'production') : ?>
                 <span class="brand-text fw-light">AdminLTE 4</span>
             <?php else : ?>
                 <span class="brand-text fw-light bg-danger"><?= ENVIRONMENT ?></span>
             <?php endif ?>

             <!--end::Brand Text-->
         </a>
         <!--end::Brand Link-->
     </div>
     <!--end::Sidebar Brand-->
     <!--begin::Sidebar Wrapper-->
     <div class="sidebar-wrapper">
         <nav class="mt-2" aria-label="Main navigation">
             <!--begin::Sidebar Menu-->
             <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" data-accordion="false" id="navigation">

                 <!-- <li class="nav-item menu-open">
                     <a href="#" class="nav-link active">
                         <i class="nav-icon bi bi-speedometer"></i>
                         <p>
                             Dashboard
                             <i class="nav-arrow bi bi-chevron-right"></i>
                         </p>
                     </a>
                     <ul class="nav nav-treeview">
                         <li class="nav-item">
                             <a href="<?= base_url('admin_lte/') ?>index.html" class="nav-link active">
                                 <i class="nav-icon bi bi-circle"></i>
                                 <p>Dashboard v1</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="<?= base_url('admin_lte/') ?>index2.html" class="nav-link">
                                 <i class="nav-icon bi bi-circle"></i>
                                 <p>Dashboard v2</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="<?= base_url('admin_lte/') ?>index3.html" class="nav-link">
                                 <i class="nav-icon bi bi-circle"></i>
                                 <p>Dashboard v3</p>
                             </a>
                         </li>
                     </ul>
                 </li> -->
                 <li class="nav-item">
                     <a class="nav-link" href="<?= base_url() ?>">
                         <i class="nav-icon bi bi-speedometer"></i>
                         <p>Dashboard</p>
                     </a>
                 </li>
                 <?php
                    $userGroup = session()->get('userGroup');

                    if ($userGroup) {
                        echo  generate_menu($userGroup);
                    }
                    ?>
                 <li class="nav-item">
                     <a class="nav-link" href="<?= base_url('logout') ?>">
                         <i class="nav-icon bi bi-speedometer"></i>
                         <p>Logout</p>
                     </a>
                 </li>
             </ul>
             <!--end::Sidebar Menu-->

             <!-- Docs CTA (bottom of sidebar) -->
             <div class="p-3 mt-3 border-top border-secondary border-opacity-25">
                 <a href="<?= base_url('admin_lte/') ?>docs/introduction.html" class="btn btn-sm btn-outline-light w-100 d-flex align-items-center justify-content-center gap-2">
                     <i class="bi bi-book" aria-hidden="true"></i>
                     View documentation
                 </a>
             </div>
         </nav>
     </div>
     <!--end::Sidebar Wrapper-->
 </aside>
 <!--end::Sidebar-->