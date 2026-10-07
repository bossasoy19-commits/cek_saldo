<div class="container">
    <div id="respon_htmx" class="mt-3"></div>
    <?php
    echo form_open(
        base_url(($controller . '/save')),
        [
            'id' => 'form_datas',
            'class' => 'row g-3 needs-validation',
            'novalidate' => 'novalidate',
            'autocomplete' => 'off',
        ]
    );
    echo f_input(['name' => 'realName', 'label' => 'Nama Asli'], 'required');
    echo f_input(['name' => 'userName', 'label' => 'User Name', 'class_div' => 'col-md-8'], 'required autocomplete="off"');
    echo f_option([
        'name' => 'userGroup',
        'label' => 'Group',
        'opt' => $opt_ug,
        'selected' => '',
        'awal' => ['--Pilih Group', 'disabled '],
        'class_div' => 'col-md-4'
    ], 'required');
    echo f_input(['name' => 'userPassword', 'label' => 'Password', 'type' => 'password', 'class_div' => 'col-md-6'], 'required');
    echo f_input(['name' => 'userPasswordMatch', 'label' => 'Ulangi Password', 'type' => 'password', 'class_div' => 'col-md-6'], 'required');
    echo f_textarea(['name' => 'userDescription', 'label' => 'Keterangan'], 'required');

    ?>
    <div class="col-md-12">
        <label for="realName" class="form-label">Akses Cabang <span class="text text-danger">*</span></label>
        <div class="input-group has-validation ">
            <?php
            $no_cab = 0;
            foreach ($cabang as $cab) :
                $no_cab++;
            ?>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="cab<?= $no_cab ?>" name="id_cabang[]" val="<?= encrypt_url($cab['id_cabang']) ?>" <?= $cab['aktif'] == 1 ? '' : 'disabled' ?>>
                    <label class="form-check-label" for="cab<?= $no_cab ?>"><?= $cab['nama_cabang'] . ' ' . ($cab['aktif'] == 1 ? '' : '<i>(Tidak Aktif ⛔)</i>') ?></label>
                </div>
            <?php endforeach ?>
        </div>
    </div>
    <hr>
    <div class="col-12">
        <button class="btn btn-primary" type="submit">Submit form</button>
    </div>
    <?= form_close() ?>
</div>