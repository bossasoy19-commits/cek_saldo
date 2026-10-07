<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="container">
  <div class="page-inner">
    <?php
    $data2['breadcrumb'] = $breadcrumb;
    $data2['title'] = $title;
    echo view('layout/partials/page_header.php', $data2) ?>
    <div id="content_htmx" hx-get="<?= base_url($controller . '/table') ?>" hx-trigger="load" hx-target="#content_htmx" hx-swap="innerHTML">
      <div class="text-center">
        <div class="spinner-border" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
    </div>
  </div>
</div>



<?= $this->endSection() ?>