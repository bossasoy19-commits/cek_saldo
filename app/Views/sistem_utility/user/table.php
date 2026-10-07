<table id="table_data" width="100%" class="table table-sm table-hover datatable" data-skip="[0,5]" data-page_length="<?= $OPSI[1] ?? env('app.page_length'); ?>" data-coloum_search='1' data-options="table_user">


    <thead>
        <?php
        $header = <<<HTML
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Username</th>
            <th>Group</th>
            <th>Status</th>
            <th>Action</th>
            <th>hide</th>
        </tr>
        HTML;

        echo $header;
        ?>
    </thead>

    <tfoot>
        <?= $header ?>
    </tfoot>
    <?php if ($data_table) : ?>
        <tbody>

            <?php
            $no = 0;
            foreach ($data_table as $row) :
                $id = $row['userID'];
                $id_encrypt = encrypt_url($id);
                $userStatus = $row['userStatus'];
                $no++;
                $row_id = 'row_' . $controller . $no;
            ?>
                <!-- <tr class="rows" data-id="<?= encrypt_url($id)  ?>"> -->
                <tr class="rows">

                    <td></td>
                    <td><?= $row['realName'] ?></td>
                    <td><?= $row['userName'] ?></td>
                    <td><?= $row['groupName'] ?></td>

                    <td><?= $userStatus ?></td>

                    <td></td>
                    <td class="d-none"><?= $id_encrypt ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>

    <?php endif ?>
</table>

<?= view('layout_lte/partials/script_datatable_init'); ?>