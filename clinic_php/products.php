<?php
include "config.php";


$name = "";
$price = 0;
$stock = 0;
$edit = false;
$product_id = 0;

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
    $name = $_POST['product_name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $con->query("INSERT INTO products (name, price, stock)
                     VALUES ('$name', '$price', '$stock');") or die($con->error);
    $_SESSION['message'] = "Record has been saved";
    $_SESSION['msg_type'] = 'success';
}

if(isset($_GET['edit'])){
    $product_id = $_GET['edit'];
    $edit = true;
    $result = $con->query("SELECT * FROM products WHERE product_id=$product_id") or die($con->error);
    if($result){
        $row = $result->fetch_array();
        $name = $row['product_name'];
        $price = $row['price'];
        $stock = $row['stock'];
    }
}

if(isset($_POST['update'])){
    $product_id = $_POST['product_id'];
    $name = $_POST['product_name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $con->query("UPDATE products SET name = '$name', price = '$price', stock='$stock'
    WHERE product_id = $product_id;")
        or die($con->error);

    $_SESSION['message'] = "Record has been updated";
    $_SESSION['msg_type'] = 'warning';
}

if(isset($_GET['delete'])){
    $product_id = $_GET['delete'];
    $con->query("DELETE FROM products WHERE product_id=$product_id") or die ($con->error);
    $_SESSION['message'] = "Record has been deleted";
    $_SESSION['msg_type'] = 'danger';
}

$result = $con->query("SELECT * from products") or die($con->error);
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
            <li><a href="products.php" class="active">Products</a></li>
            <li><a href="orders.php">Orders</a></li>
            <li style="float:right">
               <form method='post' action=""><input style="margin-top:10%;" type="submit" value="Logout" name="logout" class="btn btn-danger"></form>
            </li>
            <li style="float:right"><a style="pointer-events: none;">Welcome, <?php print ($_SESSION['username'])?></a></li>
         </ul>
         <div class="row">
            <div class="col-sm-7">
               <div class="row" justify-content-center>
                  <table class="table">
                     <thead>
                        <tr class="bg-primary" style="color: white;">
                           <th>Name</th>
                           <th>Price ($)</th>
                           <th>Stock</th>
                           <th colspan="2">Action</th>
                        </tr>
                     </thead>
                     <?php
                        while ($row = $result->fetch_assoc()):?>
                     <tr>
                        <td><?php echo $row['product_name']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td><?php echo $row['stock']; ?></td>
                        <td>
                           <a href="products.php?edit=<?php echo $row['product_id']; ?>"><i class='fas fa-edit' style='font-size:20px;color:yellow'></i></a>
                           <a href="products.php?delete=<?php echo $row['product_id']; ?>"><i class='fas fa-trash' style='font-size:20px;color:red'></i></a>
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
                  <h3>Update existing product</h3>
                  <?php else: ?>
                  <h3>Add a new product</h3>
                  <?php endif ?>
                  <form action="" method="POST">
                     <table>
                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">  
                        <tr>
                           <td>
                              <div class="form-group">
                                 <label>Name</label>
                                 <input class="form-control" type="text" name="name"
                                    value="<?php echo $name; ?>"
                                    placeholder="Enter product name" required>
                              </div>
                           </td>
                           <td>
                              <div class="form-group">
                                 <label>Price</label>
                                 <input class="form-control" type="text" name="price"
                                    value="<?php echo $price; ?>"
                                    placeholder="Enter price" required>
                              </div>
                           </td>
                        </tr>
                        <tr>
                           <td>
                              <div class="form-group">
                                 <label>Stock</label>
                                 <input class="form-control" type="text" name="stock"
                                    value="<?php echo $stock; ?>"
                                    placeholder="Enter stock" required>
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