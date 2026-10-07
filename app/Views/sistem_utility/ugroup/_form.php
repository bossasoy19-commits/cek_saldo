<div class="container">
    <?php
    echo form_open(
        base_url(($controller . '/save')),
        [
            'id' => 'form_datas',
            'class' => 'row g-3 needs-validation',
            'novalidate' => 'novalidate',
            'autocomplete' => 'off',

            'data-title' => 'Simpan Data ' .  ucwords(str_replace('_', ' ', $title)),
            'data-text' => 'Cek dan pastikan data yang dimasukkan sudah benar !',
            'data-icon' => 'question',
            'data-confirm' => 'Ya, Simpan 💾',
            'data-cancel' => 'Cek kembali 🔍',
            'data-method' => $hx_post ?? 'post',
        ]
    );

    echo f_input(['name' => 'groupName', 'label' => 'Nama Group', 'value' => $data_lama['groupName'] ?? ''], 'required');
    echo f_textarea(['name' => 'groupDescription', 'label' => 'Keterangan', 'value' => $data_lama['groupDescription'] ?? '']);
    echo f_option([
        'name' => 'groupStatus',
        'label' => 'Status',
        'opt' => $opt_status,
        'selected' => $data_lama['groupStatus'] ?? '',
        'awal' => ['--Pilih Status', 'disabled '],
        'class_div' => 'col-lg-12'
    ], 'required', false);
    ?>

    <?php if ($id_edit) : ?>
        <input type="hidden" name="id_encrypt" value="<?= encrypt_url($id_edit);  ?>">
    <?php endif ?>

    <!-- TRUE -->

    <hr>
    <div class="col-md-12">
        <button class="btn btn-primary" type="submit"><i class=" bi  <?= $id_edit ? 'bi-bookmark-check' : ' bi-floppy' ?> "></i> <?= $id_edit ? 'Perbaharui' : 'Simpan' ?> </button>
        <button class="btn btn-danger ms-2" type="button" data-bs-dismiss="modal"><i class=" bi bi-door-closed"></i> Close</button>
    </div>
    <div class="col-md-12" id="respon_htmx">

    </div>

    <?= form_close() ?>
</div>