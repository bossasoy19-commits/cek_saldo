<style>
    .mobile-min-vh-100 {
        min-height: auto;
    }

    @media (max-width: 767.98px) {
        .mobile-min-vh-100 {
            min-height: 100vh;
        }
    }
</style>
<main class="login-box">
    <div class="card card-outline card-primary">

        <div class="container-fluid mobile-min-vh-100 d-flex flex-column justify-content-center py-4 px-3 bg-body-tertiary">

            <div class="row justify-content-center">
                <div class="col-sm-12">

                    <!-- Header Branding -->
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mb-2 shadow-sm" style="width: 56px; height: 56px;">
                            <i class="bi bi-box-arrow-in-right fs-3"></i>
                        </div>
                        <h3 class="fw-bold text-dark mb-1">Selamat Datang</h3>
                        <p class="text-muted small" id="respon_htmx">Masuk ke akun Saldoku Anda</p>
                    </div>

                    <!-- Card Form Login -->
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">

                            <?php
                            echo form_open(
                                'login/proccess',
                                ['id' => 'form_login', 'hx-post' => site_url($controller . '/proccess'), 'hx-target' => '#respon_htmx', 'hx-swap' => 'innerHTML']
                            );

                            // Email / WhatsApp / Username
                            echo f_input([
                                'name' => 'identity',
                                'label' => 'No. WhatsApp/Email',
                                'value' => '',
                                'class_feedback' => '',
                                'class_div' => 'col-12'
                            ], 'required placeholder="email atau WhatsApp"');

                            // Password
                            echo f_input([
                                'name' => 'password',
                                'label' => 'Password',
                                'type' => 'password',
                                'class_feedback' => '',
                                'class_div' => 'col-12'
                            ], 'required placeholder="password" autocomplete="current-password" spellcheck="false" autocapitalize="off" autocorrect="off"');

                            ?>

                            <div class="col-12 d-flex justify-content-end">
                                <a href="<?= base_url('auth/forgot_password') ?>" class="text-primary text-decoration-none small">
                                    Lupa Password?
                                </a>
                            </div>

                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-primary btn-lg w-100 py-3 rounded-3 fw-semibold shadow-sm">
                                    Masuk
                                </button>
                            </div>

                            <?php echo form_close(); ?>
                        </div>
                    </div>

                    <!-- Footer Links -->
                    <div class="text-center mt-4 d-flex flex-column gap-2">
                        <div>
                            <span class="text-muted small">Belum punya akun?</span>
                            <a class="text-primary text-decoration-none fw-semibold small ms-1" onclick="openHtmxModal({
                                    url: '<?= base_url($controller . '/registers') ?>',
                                    title: '<?= 'Form Pendaftaran' ?>',
                                    static: true,
                                    size:  '<?= $size_modal ?? 'lg' ?>'
                                })" type="button">
                                Daftar Akun Baru
                            </a>
                        </div>
                        <div>
                            <a href="<?= base_url('auth/guest') ?>" class="text-secondary text-decoration-none small">
                                <i class="bi bi-lightning-charge"></i> Coba Sebagai Guest
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</main>