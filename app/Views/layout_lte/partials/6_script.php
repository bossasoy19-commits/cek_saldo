<script>
    window.APP = {
        baseUrl: "<?= base_url() ?>"
    };
</script>
<!--begin::Script-->
<!--begin::Third Party Plugin(OverlayScrollbars)-->
<script src="<?= base_url('admin_lte/js/')  ?>overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
<!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
<script src="<?= base_url('admin_lte/js/')  ?>popper.min.js" crossorigin="anonymous"></script>
<!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
<script src="<?= base_url('admin_lte/js/')  ?>bootstrap.min.js" crossorigin="anonymous"></script>
<!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
<script src="<?= base_url('admin_lte/js/adminlte.js') ?>"></script>
<!--end::Required Plugin(AdminLTE)-->
<!--begin::OverlayScrollbars Configure-->
<script>
    const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
    const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
    };
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);

        // Disable OverlayScrollbars on mobile devices to prevent touch interference
        const isMobile = window.innerWidth <= 992;

        if (
            sidebarWrapper &&
            OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined &&
            !isMobile
        ) {
            OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                scrollbars: {
                    theme: Default.scrollbarTheme,
                    autoHide: Default.scrollbarAutoHide,
                    clickScroll: Default.scrollbarClickScroll,
                },
            });
        }
    });
</script>
<!--end::OverlayScrollbars Configure-->

<!--begin::Color Mode Toggle-->
<!-- The light/dark/auto switcher ships in adminlte.js as the ColorMode
     module (since 4.1) — no page script needed. Only the no-flash snippet
     in <head> stays inline, because it must run before first paint. -->
<!--end::Color Mode Toggle-->

<script src="<?= base_url() ?>assets/js/core/jquery-3.7.1.min.js"></script>



<!-- Sweet Alert -->
<!-- <script src="<?= base_url() ?>assets/js/plugin/sweetalert/sweetalert.min.js"></script> -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="<?= base_url() ?>assets/js/custom/script_func.js"></script>
<script src="<?= base_url() ?>assets/js/custom/render.js"></script>
<script src="<?= base_url() ?>assets/js/custom/swall.js"></script>

<?= $this->renderSection('script_datatables'); ?>
<?= $this->renderSection('script_custom'); ?>
<script src="<?= base_url() ?>assets/js/custom/htmx.min.js"></script>
<script src="<?= base_url() ?>assets/js/custom/htmx_custom.js"></script>
<!-- Alpine.js Plugin (Opsional tetapi berguna untuk form) -->
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script>

<!-- Alpine.js Core -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<!-- <script>
    document.addEventListener("htmx:afterSettle", function(evt) {
        if (window.Alpine) {
            window.Alpine.discoverUninitializedComponents(function(el) {
                window.Alpine.initializeComponent(el);
            });
        }
    });
</script> -->
<script>
    document.addEventListener("htmx:afterSettle", function(evt) {
        if (window.Alpine && evt.detail.target) {
            window.Alpine.initTree(evt.detail.target);
        }
    });
</script>