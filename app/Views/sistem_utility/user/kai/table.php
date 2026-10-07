<style>
    table.table td,
    table.table th {
        padding: 2px 6px !important;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="card-body">
                <div class="row">
                    <div class=" col-6">
                        <button id="btn_hapus_chk" class="btn btn-link">
                            Hapus ?
                        </button>

                        <button id="btn_exec_hapus" data-url="<?= base_url('user/hapusBulk') ?>" class="btn btn-sm btn-danger d-none">
                            Hapus Terpilih
                        </button>
                    </div>
                    <div class=" col-6 text text-end">
                        <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                            <!-- Button trigger modal -->
                            <button class=" btn btn-primary btn-sm" onclick="openHtmxModal({
                                    url: '<?= base_url('user/form') ?>',
                                    title: 'Tambah' . $title,
                                    static: true,
                                    size: 'lg'
                                })">
                                Tambah <?= $title ?>
                            </button>
                        </div>
                    </div>
                </div>
                <div id="info_result"></div>


                <table id="table_data" width="100%" class="table table-responsive-lg table-hover datatable" data-skip="[0,5]" data-page_length="<?= $OPSI[1] ?? 10 ?>" data-coloum_search='1'>
                    <thead>
                        <tr>

                            <th>#</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Group</th>
                            <th class="text text-center">Status</th>

                            <th class="text text-center">Action</th>
                        </tr>
                    </thead>
                    <?php if ($data_table) : ?>
                        <tfoot>
                            <tr>

                                <th>#</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Group</th>
                                <th class="text text-center">Status</th>

                                <th class="text text-center">Action</th>
                            </tr>
                        </tfoot>
                        <tbody>

                            <?php
                            $no = 0;
                            foreach ($data_table as $row) :
                                $id = $row['userID'];
                                $userStatus = $row['userStatus'];
                                $no++;
                                $row_id = 'row_' . $controller . $no;
                            ?>
                                <tr class="rows">

                                    <td class="text text-center"><?= $no ?></td>
                                    <td><?= $row['realName'] ?></td>
                                    <td><?= $row['userName'] ?></td>
                                    <td><?= $row['groupName'] ?></td>

                                    <td class="text text-center" data-search="<?= $userStatus == '1' ? 'Aktif' : 'Tidak Aktif' ?>">
                                        <?php
                                        if ($userStatus == '1') {
                                            $status = '<i class=" feather icon-check"></i> Aktif ';
                                            $badge = 'badge badge-primary';
                                        } else {
                                            $status = '<i class=" feather icon-stop-circle"></i> Tidak Aktif';
                                            $badge = 'badge badge-danger';
                                        }
                                        ?>
                                        <span class="<?= $badge ?>"><?= $status ?></span>

                                    </td>
                                    <!-- ACTION -->
                                    <td class="text text-center">

                                        <button class="btn btn-danger btn-xs mb-1">
                                            <i class="fas fa-trash-alt "></i>
                                        </button>
                                        <button class="btn btn-warning btn-xs">
                                            <i class="fas fa-pencil-alt "></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    <?php endif ?>
                </table>


            </div>
        </div>

    </div>

    <script>
        // setTimeout(() => {
        //     init_table.search('medan').draw();

        // }, 2000);
    </script>