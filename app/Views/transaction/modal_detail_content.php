<div class="container-fluid p-0">

    <!-- HEADER INFORMASI UTAMA -->
    <div class="mb-3 p-3 rounded-3 bg-light border">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="badge bg-secondary-subtle text-secondary border fs-8">
                Ref: <?= esc($trx['no_referensi'] ?? '-') ?>
            </span>
            <?php if (isset($trx['status_transaksi']) && $trx['status_transaksi'] === 'PENDING') : ?>
                <span class="badge bg-warning-subtle text-warning border border-warning-subtle fs-8 rounded-pill px-2 py-0">
                    <i class="bi bi-clock me-1"></i> Pending
                </span>
            <?php endif; ?>
        </div>

        <h6 class="fw-bold text-dark mb-1"><?= esc($trx['deskripsi'] ?: 'Tanpa Keterangan') ?></h6>
        <span class="text-muted fs-8"><i class="bi bi-calendar-event me-1"></i> <?= date('d M Y, H:i', strtotime($trx['tanggal_transaksi'])) ?></span>
    </div>

    <!-- TABEL RINCIAN AKUN (DOUBLE ENTRY DETAILS) -->
    <div class="mb-3">
        <label class="form-label fw-bold small text-muted text-uppercase fs-8 mb-2">Rincian Jurnal / Akun Terlibat</label>
        <div class="table-responsive border rounded-3 overflow-hidden">
            <table class="table table-sm table-striped mb-0 fs-7 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="py-2 px-3">Akun / Kategori</th>
                        <th class="text-end py-2 px-3">Debet (Rp)</th>
                        <th class="text-end py-2 px-3">Kredit (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($trx['details'])) : ?>
                        <?php foreach ($trx['details'] as $det) : ?>
                            <tr>
                                <td class="py-2 px-3">
                                    <div class="fw-semibold text-dark"><?= esc($det['category']) ?></div>
                                    <?php if (!empty($det['detail_keterangan'])) : ?>
                                        <small class="text-muted d-block"><?= esc($det['detail_keterangan']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end py-2 px-3 text-success fw-semibold">
                                    <?= $det['debet'] > 0 ? number_format($det['debet'], 0, ',', '.') : '-' ?>
                                </td>
                                <td class="text-end py-2 px-3 text-danger fw-semibold">
                                    <?= $det['kredit'] > 0 ? number_format($det['kredit'], 0, ',', '.') : '-' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">Tidak ada rincian akun.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- LAMPIRAN / ATTACHMENT -->
    <?php if (!empty($trx['attachment_url'])) : ?>
        <?php
        // Decode JSON attachment
        $attachments = json_decode($trx['attachment_url'], true);

        // Jika datanya berupa string tunggal, ubah jadi array agar seragam
        if (is_string($attachments)) {
            $attachments = [$attachments];
        }
        ?>

        <?php if (!empty($attachments) && is_array($attachments)) : ?>
            <div class="mb-3">
                <label class="form-label fw-bold small text-muted text-uppercase fs-8 mb-2">Lampiran Berkas</label>
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <?php
                    $no = 1;
                    foreach ($attachments as $file) : ?>
                        <?php
                        // Ambil ekstensi file untuk mendeteksi apakah itu gambar
                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);

                        // Sesuaikan URL file (jika path relative, gabungkan dengan base_url)
                        $fileUrl = filter_var($file, FILTER_VALIDATE_URL) ? $file : base_url($file);
                        $fileName = basename($file);
                        ?>

                        <?php if ($isImage) : ?>
                            <!-- GAYA GALLERY (PREVIEW GAMBAR KECIL) -->
                            <a href="<?= esc($fileUrl) ?>" target="_blank" class="d-block border rounded-3 overflow-hidden shadow-xs position-relative" style="width: 70px; height: 70px;" title="Klik untuk memperbesar">
                                <img src="<?= esc($fileUrl) ?>" alt="Attachment" class="w-100 h-100 object-fit-cover">
                            </a>
                        <?php else : ?>
                            <!-- GAYA BUTTON UNTUK FILE (PDF, DOCX, DLL) -->
                            <a href="<?= esc($fileUrl) ?>" target="_blank" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1 fs-8 rounded-3 px-2 py-1">
                                <i class="bi bi-file-earmark-text text-danger fs-6"></i>
                                <span class="text-truncate" style="max-width: 150px;"><?= 'Buka File ' . $no++ ?></span>
                                <i class="bi bi-box-arrow-up-right ms-1 small text-muted"></i>
                            </a>
                        <?php endif; ?>

                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- FOOTER MODAL (TUTUP) -->
    <div class="d-flex justify-content-end pt-2 border-top">
        <button type="button" class="btn btn-light btn-sm px-4 rounded-3 fw-semibold fs-7 border" data-bs-dismiss="modal">
            Tutup
        </button>
    </div>

</div>