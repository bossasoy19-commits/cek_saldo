<!--begin::Container-->
<div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
        <div class="col-12">
            <!--begin::Card-->
            <div class="card">
                <div class="card-header" style="padding: 10px;">
                    <button disabled class="btn btn-primary btn-sm  btn_action" onclick="openHtmxModal({
                                    url: '<?= base_url($controller . '/form') ?>',
                                    title: '<?= 'Tambah ' . $title ?>',
                                    static: true,
                                    size:  '<?= $size_modal ?? 'lg' ?>'
                                })" type="button">
                        <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>
                        <?= $title ?> Baru
                    </button>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse" aria-label="Collapse card">
                            <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                            <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-lte-toggle="card-remove" aria-label="Remove card">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div id="content_htmx">

                    </div>
                </div>
                <!-- /.card-body -->
                <?php if (isset($footer_card_title)) : ?>
                    <div class="card-footer">
                        <?= $footer_card_title ?>
                    </div>

                <?php endif ?>
                <!-- /.card-footer -->
            </div>
            <!--end::Card-->
        </div>
        <!-- /.col -->
    </div>
    <!--end::Row-->
</div>
<!--end::Container-->