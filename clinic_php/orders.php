<?php
include "config.php";

$client_id = 0;
$product_id = 0;
$edit = false;
$order_id = 0;


if($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'employee')
$access = true;

// Check user login or not
if(!isset($_SESSION['username']) || !$access){
    header('Location: login.php');
}

// logout
if(isset($_POST['logout'])){
    session_destroy();
    unset($_SESSION["username"]);
    header('Location: login.php');    
}

if(isset($_POST['save'])){
    $client_id = $_POST['client_id'];
    $product_id = $_POST['product_id'];
    $date = $_POST['date'];
    $real_date = date("Y-m-d", strtotime($date));
    $con->query("INSERT INTO orders (client_id, product_id, date)
                     VALUES ('$client_id', '$product_id', '$real_date');") or die($con->error);
    $_SESSION['message'] = "Record has been saved";
    $_SESSION['msg_type'] = 'success';
}

if(isset($_GET['edit'])){
    $order_id = $_GET['edit'];
    $edit = true;
    $result = $con->query("SELECT c.client_id, c.first_name as client_name,
    p.product_id, p.product_name
    FROM orders o JOIN clients c ON c.client_id = o.client_id
    JOIN products p ON p.product_id = o.product_id
    WHERE order_id=$order_id") or die($con->error);
    if($result){
        $row = $result->fetch_array();
        $client_id = $row['client_id'];
        $client_name = $row['client_name'];
        $product_id = $row['product_id'];
        $name = $row['product_name'];
    }
}

if(isset($_POST['update'])){
    $order_id = $_POST['order_id'];
    $client_id = $_POST['client_id'];
    $product_id = $_POST['product_id'];
    $date = $_POST['date'];

    $con->query("UPDATE orders SET client_id = '$client_id', product_id = '$product_id', date='$date'
    WHERE order_id = $order_id;")
        or die($con->error);

    $_SESSION['message'] = "Record has been updated";
    $_SESSION['msg_type'] = 'warning';
}

if(isset($_GET['delete'])){
    $order_id = $_GET['delete'];
    $con->query("DELETE FROM orders WHERE order_id=$order_id") or die ($con->error);
    $_SESSION['message'] = "Record has been deleted";
    $_SESSION['msg_type'] = 'danger';
}

$result = $con->query("SELECT o.order_id, c.first_name, p.product_name, DATE_FORMAT(o.date, '%b-%D-%Y') as date from orders o JOIN clients c ON c.client_id = o.client_id
JOIN products p ON p.product_id = o.product_id") or die($con->error);
?>

<!doctype html>
<html>
   <head>
      <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
      <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" rel="stylesheet"/>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
   </head>
   <body>
      <?php 
         if (isset($_SESSION['message'])): ?>
      <div class="alert alert-<?=$_SESSION['msg_type']?>" id="msg">
         <?php 
            echo $_SESSION['message'];
            unset($_SESSION['message']);
            ?>
      </div>
      <?php endif ?>
      <div class="container">
         <center>
            <h1 style="margin-top: 3%;">My Dashboard</h1>
         </center>
         <ul style="margin-top:5%;">
            <li><a href="admin.php">Users</a></li>
            <li><a href="clients.php">Clients</a></li>
            <li><a href="appointments.php">Appointments</a></li>
            <li><a href="medecines.php">Medecines</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="orders.php" class="active">Orders</a></li>
            <li style="float:right">
               <form method='post' action=""><input style="margin-top:10%;" type="submit" value="Logout" name="logout" class="btn btn-danger"></form>
            </li>
            <li style="float:right"><a style="pointer-events: none;">Welcome, <?php print ($_SESSION['role'])?></a></li>
         </ul>
         <div class="row">
            <div class="col-sm-7">
               <div class="row" justify-content-center>
                  <table class="table">
                     <thead>
                        <tr class="bg-primary" style="color: white;">
                           <th>Client Name</th>
                           <th>Product Name</th>
                           <th>Order Date</th>
                           <th colspan="2">Action</th>
                        </tr>
                     </thead>
                     <?php
                        while ($row = $result->fetch_assoc()):?>
                     <tr>
                        <td><?php echo $row['first_name']; ?></td>
                        <td><?php echo $row['product_name']; ?></td>
                        <td><?php echo $row['date']; ?></td>
                        <td>
                           <a href="orders.php?edit=<?php echo $row['order_id']; ?>"><i class='fas fa-edit' style='font-size:20px;color:yellow'></i></a>
                           <a href="orders.php?delete=<?php echo $row['order_id']; ?>"><i class='fas fa-trash' style='font-size:20px;color:red'></i></a>
                        </td>
                     </tr>
                     <?php endwhile; ?>    
                  </table>
               </div>
            </div>
            <div class="col-sm-5" style="padding-left: 75px;">
               <div class="row justify-content-center">
                  <?php 
                     if($edit == true):
                     ?>
                  <h3>Update existing order</h3>
                  <?php else: ?>
                  <h3>Make a new order</h3>
                  <?php endif ?>
                  <form action="" method="POST">
                     <table>
                        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">  
                        <tr>
                           <td>
                           <div class="form-group">
                                 <label>Client</label>
                                 <select name="client_id" class="form-control" required>
                                 <?php 
                                    if($edit == true):
                                    ?><option value="<?php echo $client_id; ?>" selected><?php echo $client_id.'-->'.$client_name; ?></option>
                                    <?php else:?>
                                    <option value="" disabled selected>Select client</option>
                                    <?php endif ?>
                                    <?php
                                    $result = $con->query("SELECT * from clients ") or die($con->error);
                                    while($row = mysqli_fetch_array($result)) {
                                        echo '<option value='.$row['client_id'].'>'.$row['client_id'].'-->'.$row['first_name'].'</option>';
                                    }
                                    ?>
                                 </select>
                              </div>
                           </td>
                           <td>
                           <div class="form-group">
                                 <label>Product</label>
                                 <select name="product_id" class="form-control" required>
                                 <?php 
                                    if($edit == true):
                                    ?><option value="<?php echo $product_id; ?>" selected><?php echo $product_id.'-->'.$name; ?></option>
                                    <?php else:?>
                                    <option value="" disabled selected>Select product</option>
                                    <?php endif ?>
                                    <?php
                                    $result = $con->query("SELECT * from products ") or die($con->error);
                                    while($row = mysqli_fetch_array($result)) {
                                        echo '<option value='.$row['product_id'].'>'.$row['product_id'].'-->'.$row['product_name'].'</option>';
                                    }
                                    ?>
                                 </select>
                              </div>
                           </td>
                        </tr>
                        <tr>
                           <td>
                              <div class="form-group">
                                 <label>Date</label>
                                 <input class="form-control" type="date" name="date"
                                    value="<?php echo $date; ?>"
                                    placeholder="Enter date" required>
                              </div>
                           </td>
                           <td>
                           </td>
                        </tr>
                     </table>
                     <div class="form-group">
                     <?php 
                        if($edit == true):
                        ?>
                     <button class="btn btn-info" type="submit" name="update">Update</button>
                     <?php else: ?>
                     <button class="btn btn-primary" type="submit" name="save">Save</button>
                     <?php endif ?>
                     </div>
                  </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <script>
         <?php require_once("script.js");?>
      </script>
      <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
   </body>
</html>