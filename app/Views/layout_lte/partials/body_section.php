<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <?php
        echo  view('layout_lte/partials/2_header.php');
        echo  view('layout_lte/partials/3_sidebar.php');
        ?>

        <!--begin::App Main-->
        <main class="app-main">
            <?= view('layout_lte/partials/4_content_header.php'); ?>
            <!--begin::App Content-->
            <div class="app-content">
                <?= $this->renderSection('content'); ?>
            </div>
            <!--end::App Content-->
        </main>
        <!--end::App Main-->
        <?= view('layout_lte/partials/5_footer.php'); ?>
    </div>
    <!--end::App Wrapper-->
    <?= view('layout_lte/partials/6_script.php'); ?>
</body>