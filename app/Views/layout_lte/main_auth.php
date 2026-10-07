<?= view('layout_lte/partials/1_head.php'); ?>
<!--begin::Body-->

<body class="login-page bg-body-secondary">

    <?= $this->renderSection('content'); ?>
    <!-- Modal -->
    <div class="modal fade" id="globalModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable" id="globalModalDialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="globalModalTitle">Loading...</h5>
                    <button type="button" class="btn-close btn btn-danger" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" id="globalModalBody">
                    <!-- HTMX content masuk sini -->
                </div>

            </div>
        </div>
    </div>
    <?= view('layout_lte/partials/6_script.php'); ?>
</body>
<!--end::Body-->

</html>