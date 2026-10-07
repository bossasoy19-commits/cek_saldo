<table id="table_data" width="100%" class="table table-sm table-hover datatable" data-skip="[0,7]" data-page_length="<?= $OPSI[1] ?? env('app.page_length'); ?>" data-coloum_search='1' data-options="table_cabang">

    <thead>
        <?php
        $header = <<<HTML
        <tr>
                <th>#</th>
                <th>Nama Cabang</th>
                <th>alamat</th>
                <th>Kontak</th>
                <th>Email</th>
                <th>PIC</th>
                <th>keterangan</th>
                <th>Status</th>
                <th>Action</th>
            <th  class="d-none">hide</th>
        </tr>
        HTML;

        echo $header;
        ?>
    </thead>

    <tfoot>
        <?= $header ?>
    </tfoot>
    <?php
    $no = 0;
    if ($data_table) : ?>
        <tbody>
            <?php foreach ($data_table as $row) :
                $no++;
                $aktif = $row['aktif'];
                $id = $row['id_cabang'];
                $id_encrypt = encrypt_url($id);
            ?>
                <tr class="rows">
                    <td></td>
                    <td><?= $row['nama_cabang']; ?></td>
                    <td><?= $row['alamat']; ?></td>
                    <td><?= $row['no_hp']; ?></td>
                    <td><?= $row['email']; ?></td>
                    <td><?= $row['realName']; ?></td>
                    <td><?= $row['keterangan']; ?></td>
                    <td><?= $aktif ?></td>
                    <td></td>
                    <td class="d-none"><?= $id_encrypt ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>

    <?php endif ?>
</table>
<?= view('layout_lte/partials/script_datatable_init'); ?>