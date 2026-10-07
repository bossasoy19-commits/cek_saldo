<table id="table_data" width="100%" class="table table-sm table-hover datatable" data-skip="[0,3]" data-page_length="<?= $OPSI[1] ?? env('app.page_length'); ?>" data-coloum_search='1' data-options="table_ugroup">

    <thead>
        <?php
        $header = <<<HTML
        <tr>
            <th>#</th>
            <th >Group</th>
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
                $groupStatus = $row['groupStatus'];
                $id = $row['groupID'];
                $id_encrypt = encrypt_url($id);
            ?>
                <tr class="rows">
                    <td></td>
                    <td><?= $row['groupName']; ?></td>
                    <td><?= $groupStatus ?></td>
                    <td></td>
                    <td class="d-none"><?= $id_encrypt ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>

    <?php endif ?>
</table>
<?= view('layout_lte/partials/script_datatable_init'); ?>