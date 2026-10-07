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

    echo f_input(['name' => 'nama_cabang', 'label' => 'Nama Cabang', 'value' => $data_lama['nama_cabang'] ?? ''], 'required');
    echo f_input(['name' => 'no_hp', 'type' => 'tel', 'label' => 'No. Hp', 'value' => $data_lama['no_hp'] ?? '', 'class_div' => 'col-md-5'], '');
    echo f_input(['name' => 'email', 'type' => 'email', 'label' => 'email', 'value' => $data_lama['email'] ?? '', 'class_div' => 'col-md-7'], '');
    echo f_textarea(['name' => 'alamat', 'label' => 'Alamat', 'value' => $data_lama['alamat'] ?? '', 'class_div' => 'col-md-6']);
    echo f_textarea(['name' => 'keterangan', 'label' => 'Keterangan', 'value' => $data_lama['keterangan'] ?? '', 'class_div' => 'col-md-6']);
    echo f_option([
        'name' => 'penanggung_jawab',
        'label' => 'Penanggung Jawab',
        'opt' => $opt_penanggung_jawab ?? [],
        'selected' => $data_lama['penanggung_jawab'] ?? '',
        'awal' => ['--Pilih Penanggung Jawab', 'disabled '],
        'class_div' => 'col-lg-8'
    ], '', true);
    echo f_option([
        'name' => 'aktif',
        'label' => 'Status',
        'opt' => $opt_status,
        'selected' => $data_lama['aktif'] ?? '',
        'awal' => ['--Pilih Status', 'disabled '],
        'class_div' => 'col-lg-4'
    ], 'required', false);

    ?>

    <?php if ($id_edit) : ?>
        <input type="hidden" name="id_encrypt" value="<?= encrypt_url($id_edit);  ?>">
    <?php endif ?>

    <!-- TRUE -->

    <hr>
    <div class="col-md-12" id="respon_htmx">
    </div>
    <div class="col-md-12">
        <button class="btn btn-primary" type="submit"><i class=" bi  <?= $id_edit ? 'bi-bookmark-check' : ' bi-floppy' ?> "></i> <?= $id_edit ? 'Perbaharui' : 'Simpan' ?> </button>
        <button class="btn btn-danger ms-2" type="button" data-bs-dismiss="modal"><i class=" bi bi-door-closed"></i> Close</button>
    </div>


    <?= form_close() ?>
</div>