  <div class="page-header">
      <h4 class="page-title"><?= $title ?? 'No Title'  ?></h4>
      <ul class="breadcrumbs">
          <li class="nav-home">
              <a href="<?= base_url() ?>">
                  <i class="icon-home"></i>
              </a>
          </li>
          <?php foreach ($breadcrumb as $item) : ?>
              <li class="separator">
                  <i class="icon-arrow-right"></i>
              </li>
              <li class="nav-item">
                  <a href="<?= base_url($item['href']) ?>"><?= $item['title'] ?></a>
              </li>
          <?php endforeach ?>

      </ul>
  </div>