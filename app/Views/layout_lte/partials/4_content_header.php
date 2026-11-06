 <!--begin::App Content Header-->
 <?php
    $judul = $title ?? 'Title'; ?>
 <div class="row justify-content-center">
     <div class="col-md-11 col-lg-6">
         <div class="app-content-header" style="padding-bottom: 2px;">
             <!--begin::Container-->
             <div class="container-fluid">
                 <!--begin::Row-->
                 <div class="row">
                     <div class="col-sm-6">
                         <h1 class="mb-0 fs-3"><?= $judul ?></h1>
                     </div>
                     <div class="col-sm-6">
                         <nav aria-label="breadcrumb">
                             <ol class="breadcrumb float-sm-end">
                                 <li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
                                 <li class="breadcrumb-item active" aria-current="page"><?= $judul == 'Dashboard' ? '' : $judul  ?></li>
                             </ol>
                         </nav>
                     </div>
                     <div class="col-sm-12" id="htmx_target1" data-controller="<?= $controller ?? '' ?>" data-title="<?= $title ?? '' ?>" data-size_modal="<?= $size_modal ?? 'lg' ?>">
                     </div>
                 </div>
                 <!--end::Row-->
             </div>
             <!--end::Container-->
         </div>
     </div>
 </div>
 <!--end::App Content Header-->