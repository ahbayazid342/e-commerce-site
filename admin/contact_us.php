<?php
require('top_inc.php');
require('connection_inc.php');
require('function_inc.php');

if (isset($_GET['type']) && $_GET['type'] != '') {
   $type = get_safe_value($conn, $_GET['type']);

   if ($type == 'delete') {
      $id = get_safe_value($conn, $_GET['id']);
      $delete_sql = "DELETE FROM contact WHERE id = '$id'";
      mysqli_query($conn, $delete_sql);
   }
}

$sql = "SELECT * FROM contact order by added_on desc";
$res = mysqli_query($conn, $sql);


?>

<div class="content pb-0">
   <div class="orders">
      <div class="row">
         <div class="col-xl-12">
            <div class="card">
               <div class="card-body">
                  <h4 class="box-title">contact Us </h4>
               </div>
               <div class="card-body--">
                  <div class="table-stats order-table ov-h">
                     <table class="table ">
                        <thead>
                           <tr>
                              <th>ID</th>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Phone Number</th>
                              <th>Comment</th>
                              <th>Added On</th>
                              <th>Delete</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php $i = 1;
                           while ($row = mysqli_fetch_assoc($res)) { ?>

                              <tr>
                                 <td><?php echo $row['id'] ?></td>
                                 <td><?php echo $row['name'] ?></td>
                                 <td><?php echo $row['email'] ?></td>
                                 <td><?php echo $row['phone'] ?></td>
                                 <td><?php echo $row['comment'] ?></td>
                                 <td><?php echo $row['added_on'] ?></td>
                                 <td>
                                    <?php
                                    echo "<span class='badge badge-delete'><a href='?type=delete&id= " . $row['id'] . "'> Delete </a> </span> &nbsp";
                                    ?>
                                 </td>
                              </tr>

                           <?php $i++;
                           } ?>

                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>



<?php
require('footer_inc.php');
?>