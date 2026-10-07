 <div class="row">
     <div class="col-md-12">
         <div class="card">

             <div class="card-body">

                 <div class="table-responsive">
                     <table id="multi-filter-select" class=" table table-striped table-hover">
                         <thead>
                             <tr>
                                 <th>Nama</th>
                                 <th>Username</th>
                                 <th>Group</th>
                                 <th>Status</th>
                                 <th>Action</th>
                             </tr>
                         </thead>
                         <?php if ($data_table) : ?>
                             <tfoot>
                                 <tr>
                                     <th>Nama</th>
                                     <th>Username</th>
                                     <th>Group</th>
                                     <th>Status</th>
                                     <th>Action</th>
                                 </tr>
                             </tfoot>
                             <tbody>

                                 <?php foreach ($data_table as $row) : ?>
                                     <tr>
                                         <td></td>
                                         <td>Username</td>
                                         <td>Group</td>
                                         <td>Status</td>
                                         <td>Action</td>
                                     </tr>
                                     <tr>
                                         <td></td>
                                         <td>Username</td>
                                         <td>Group</td>
                                         <td>Status</td>
                                         <td>Action</td>
                                     </tr>
                                     <tr>
                                         <td></td>
                                         <td>Username</td>
                                         <td>Group</td>
                                         <td>Status</td>
                                         <td>Action</td>
                                     </tr>
                                     <tr>
                                         <td></td>
                                         <td>Username</td>
                                         <td>Group</td>
                                         <td>Status</td>
                                         <td>Action</td>
                                     </tr>
                                     <tr>
                                         <td></td>
                                         <td>Username</td>
                                         <td>Group</td>
                                         <td>Status</td>
                                         <td>Action</td>
                                     </tr>
                                     <tr>
                                         <td></td>
                                         <td>Username</td>
                                         <td>Group</td>
                                         <td>Status</td>
                                         <td>Action</td>
                                     </tr>
                                     <tr>
                                         <td></td>
                                         <td>Username</td>
                                         <td>Group</td>
                                         <td>Status</td>
                                         <td>Action</td>
                                     </tr>
                                     <tr>
                                         <td></td>
                                         <td>Username</td>
                                         <td>Group</td>
                                         <td>Status</td>
                                         <td>Action</td>
                                     </tr>
                                     <tr>
                                         <td></td>
                                         <td>Username</td>
                                         <td>Group</td>
                                         <td>Status</td>
                                         <td>Action</td>
                                     </tr>
                                     <tr>
                                         <td></td>
                                         <td>Username</td>
                                         <td>Group</td>
                                         <td>Status</td>
                                         <td>Action</td>
                                     </tr>
                                     <tr>
                                         <td></td>
                                         <td>Username</td>
                                         <td>Group</td>
                                         <td>Status</td>
                                         <td>Action</td>
                                     </tr>
                                     <tr>
                                         <td></td>
                                         <td>Username</td>
                                         <td>Group</td>
                                         <td>Status</td>
                                         <td>Action</td>
                                     </tr>
                                     <tr>
                                         <td></td>
                                         <td>Username</td>
                                         <td>Group</td>
                                         <td>Status</td>
                                         <td>Action</td>
                                     </tr>
                                     <tr>
                                         <td></td>
                                         <td>Username</td>
                                         <td>Group</td>
                                         <td>Status</td>
                                         <td>Action</td>
                                     </tr>
                                     <tr>
                                         <td></td>
                                         <td>Username</td>
                                         <td>Group</td>
                                         <td>Status</td>
                                         <td>Action</td>
                                     </tr>
                                     <tr>
                                         <td></td>
                                         <td>Username</td>
                                         <td>Group</td>
                                         <td>Status</td>
                                         <td>Action</td>
                                     </tr>
                                     <tr>
                                         <td></td>
                                         <td>Username</td>
                                         <td>Group</td>
                                         <td>Status</td>
                                         <td>Action</td>
                                     </tr>
                                     <tr>
                                         <td></td>
                                         <td>Username</td>
                                         <td>Group</td>
                                         <td>Status</td>
                                         <td>Action</td>
                                     </tr>
                                 <?php endforeach ?>
                             </tbody>
                         <?php endif ?>
                     </table>
                 </div>
             </div>
         </div>
     </div>

 </div>

 <script>
     $(document).ready(function() {
         //  initDataTableFilter('#multi-filter-select');
         setTimeout(() => {
             let table = new DataTable('#multi-filter-select', {
                 responsive: true
             });

             table.columns.adjust();
             table.responsive.recalc();
         }, 200);

     });
 </script>