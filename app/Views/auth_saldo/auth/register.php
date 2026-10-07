<div class="row justify-content-center">
    <div class=" col-sm-12 col-md-9">

        <!-- Header Branding -->
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mb-2 shadow-sm" style="width: 56px; height: 56px;">
                <i class="bi bi-person-plus-fill fs-3"></i>
            </div>
            <h3 class="fw-bold text-dark mb-1">Daftar Akun</h3>
            <p class="text-muted small" id="reg_respon">Buat akun untuk mengamankan data keuangan Anda</p>
        </div>

        <!-- Card Form Registrasi -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
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
                        'data-target' => '#reg_respon',
                        'data-confirm' => 'Ya, Simpan 💾',
                        'data-cancel' => 'Cek kembali 🔍',
                        'data-method' => $hx_post ?? 'post',
                    ]
                );



                ?>
                <div class="col-sm-12 mt-0">
                    <?php
                    // Nama Lengkap
                    echo f_input([
                        'name' => 'nama',
                        'label' => 'Nama Lengkap',
                        'value' => $user['nama'] ?? '',
                        'class_div' => 'col-12',
                        'pesan' => 'Nama lengkap minimal 5 karakter'
                    ], 'required minlength="5" placeholder="Nama anda"');

                    ?>
                </div>
                <div class="col-lg-6  mt-0">
                    <?php
                    // Email / WhatsApp
                    echo f_input([
                        'type' => 'email',
                        'name' => 'email',
                        'label' => 'Email',
                        'value' => $user['email'] ?? '',
                        'class_div' => 'col-12',
                        'pesan' => 'Email harus format email dan aktif'
                    ], 'required autocomplete="off" minlength="10" placeholder="email@info.com"');
                    echo f_input([
                        'type' => 'text',
                        'name' => 'no_wa',
                        'label' => 'Nomor Whatsapp',
                        'value' => $user['no_wa'] ?? '',
                        'class_div' => 'col-12',
                        'pesan' => 'Nomor whatsapp Minimal 10 digit, tanpa huruf atau simbol.'
                    ], 'required autocomplete="off" pattern="[0-9]{10,}" minlength="10" placeholder="08123..."');

                    ?>
                </div>
                <div class="col-lg-6  mt-0">
                    <?php
                    // Password
                    echo f_input([

                        'name' => 'password',
                        'label' => 'Password',
                        'type' => 'password',
                        'class_div' => 'col-12'
                    ], 'required placeholder="Minimal 6 karakter" minlength="6" autocomplete="new-password" spellcheck="false" autocapitalize="off" autocorrect="off"');
                    // Ulangi Password
                    echo f_input([
                        'name' => 'password_match',
                        'label' => 'Ulangi Password',
                        'type' => 'password',
                        'class_div' => 'col-12'
                    ], 'required placeholder="Sama dengan password di atas" minlength="6" autocomplete="new-password" spellcheck="false" autocapitalize="off" autocorrect="off"');

                    ?>
                </div>


                <div class="col-sm-12 mt-4">
                    <button type="submit" class="btn btn-primary btn-lg w-100 py-3 rounded-3 fw-semibold shadow-sm">
                        Daftar Sekarang
                    </button>
                </div>

                <?php echo form_close(); ?>
            </div>



        </div>

        <!-- Footer Link Login -->
        <div class="text-center mt-4">
            <span class="text-muted small">Sudah punya akun?</span>
            <a href="<?= base_url('auth/login') ?>" class="text-primary text-decoration-none fw-semibold small ms-1">
                Masuk di sini
            </a>
        </div>

    </div>
</div>