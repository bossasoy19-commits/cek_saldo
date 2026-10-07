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

    echo f_input(['name' => 'realName', 'label' => 'Nama Asli', 'value' => $user['realName'] ?? ''], 'required');
    echo f_input(['name' => 'userName', 'label' => 'User Name', 'value' => $user['userName'] ?? '', 'class_div' => 'col-lg-8'], 'required autocomplete="off"');


    echo f_input(['name' => 'userPassword', 'label' => 'Password Baru', 'type' => 'password', 'class_div' => 'col-lg-6'], 'placeholder="(Kosongkan jika tetap)"');
    echo f_input(['name' => 'userPasswordMatch', 'label' => 'Ulangi Password Baru', 'type' => 'password', 'class_div' => 'col-lg-6'], '(Kosongkan jika tetap)');


    echo f_textarea(['name' => 'userDescription', 'label' => 'Keterangan', 'value' => $user['userDescription'] ?? '']);

    ?>



    <!-- TRUE -->


    <hr>
    <?php
    $id_edit = false;
    ?>
    <div class="col-md-12">
        <button class="btn btn-primary" type="submit"><i class=" bi  <?= $id_edit ? 'bi-bookmark-check' : ' bi-floppy' ?> "></i> <?= $id_edit ? 'Perbaharui' : 'Simpan' ?> </button>
        <button class="btn btn-danger ms-5" type="button" data-bs-dismiss="modal"><i class=" bi bi-door-closed"></i> Close</button>
    </div>
    <div class="col-md-12" id="respon_htmx">

    </div>

    <?= form_close() ?>
</div>