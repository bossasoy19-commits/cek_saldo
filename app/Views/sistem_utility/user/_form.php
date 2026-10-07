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
    echo f_option([
        'name' => 'userGroup',
        'label' => 'Group',
        'opt' => $opt_ug,
        'selected' => $user['userGroup'] ?? '',
        'awal' => ['--Pilih Group', 'disabled '],
        'class_div' => 'col-lg-4'
    ], 'required', true);
    if (!$user) {
        echo f_input(['name' => 'userPassword', 'label' => 'Password', 'type' => 'password', 'class_div' => 'col-lg-6'], ' placeholder="123456" autocomplete="new-password" spellcheck="false" autocapitalize="off" autocorrect="off"');

        echo f_input(['name' => 'userPasswordMatch', 'label' => 'Ulangi Password', 'type' => 'password', 'class_div' => 'col-lg-6'], '  placeholder="123456" autocomplete="new-password" spellcheck="false" autocapitalize="off" autocorrect="off"');
    } else {
        echo f_input(['name' => 'userPassword', 'label' => 'Password Baru', 'type' => 'password', 'class_div' => 'col-lg-6'], 'placeholder="(Kosongkan jika tetap)"');
        echo f_input(['name' => 'userPasswordMatch', 'label' => 'Ulangi Password Baru', 'type' => 'password', 'class_div' => 'col-lg-6'], '(Kosongkan jika tetap)');
    }

    echo f_textarea(['name' => 'userDescription', 'label' => 'Keterangan', 'value' => $user['userDescription'] ?? '']);

    ?>

    <?php if ($id_edit) : ?>
        <input type="hidden" name="id_encrypt" value="<?= encrypt_url($id_edit);  ?>">
    <?php endif ?>

    <!-- TRUE -->

    <div class="col-md-12">
        <label for="realName" class="form-label">Akses Ke Cabang <span class="text text-danger">*</span></label>
        <div class="input-group has-validation ">
            <?php
            $no_cab = 0;
            foreach ($cabang as $cab) :
                $no_cab++;
                $id_cabang = $cab['id_cabang'];
                $selected = is_array($id_cabang_allow) && in_array($id_cabang, $id_cabang_allow) ? 'checked' : '';
            ?>
                <div class="form-check form-switch" style="margin-right: 10px;">
                    <input <?= $selected ?> class=" form-check-input" type="checkbox" id="cab<?= $no_cab ?>" name="id_cabang[]" value="<?= encrypt_url($id_cabang) ?>" <?= $cab['aktif'] == 1 ? '' : 'disabled' ?>>
                    <label class="form-check-label" for="cab<?= $no_cab ?>"><?= $cab['nama_cabang'] . ' ' . ($cab['aktif'] == 1 ? '' : '<i>(Tidak Aktif ⛔)</i>') ?></label>
                </div>
            <?php endforeach ?>
        </div>
    </div>
    <hr>
    <div class="col-md-12">
        <button class="btn btn-primary" type="submit"><i class=" bi  <?= $id_edit ? 'bi-bookmark-check' : ' bi-floppy' ?> "></i> <?= $id_edit ? 'Perbaharui' : 'Simpan' ?> </button>
        <button class="btn btn-danger ms-5" type="button" data-bs-dismiss="modal"><i class=" bi bi-door-closed"></i> Close</button>
    </div>
    <div class="col-md-12" id="respon_htmx">

    </div>

    <?= form_close() ?>
</div>