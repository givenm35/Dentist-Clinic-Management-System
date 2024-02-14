<?php
include "config.php";

$name = "";
$stock = 0;
$edit = false;
$medecine_id = 0;



// Check user login or not
if(!isset($_SESSION['username'])){
    header('Location: login.php');
}

// logout
if(isset($_POST['logout'])){
    session_destroy();
    unset($_SESSION["username"]);
    header('Location: login.php');    
}

if(isset($_POST['save'])){
    $name = $_POST['name'];
    $stock = $_POST['stock'];

    $con->query("INSERT INTO medecines (name, stock)
                     VALUES ('$name', '$stock');") or die($con->error);
    $_SESSION['message'] = "Record has been saved";
    $_SESSION['msg_type'] = 'success';
}

if(isset($_GET['edit'])){
    $medecine_id = $_GET['edit'];
    $edit = true;
    $result = $con->query("SELECT * FROM medecines WHERE medecine_id=$medecine_id") or die($con->error);
    if($result){
        $row = $result->fetch_array();
        $name = $row['name'];
        $stock = $row['stock'];
    }
}

if(isset($_POST['update'])){
    $medecine_id = $_POST['medecine_id'];
    $name = $_POST['name'];
    $stock = $_POST['stock'];

    $con->query("UPDATE medecines SET name = '$name', stock='$stock'
    WHERE medecine_id = $medecine_id;")
        or die($con->error);

    $_SESSION['message'] = "Record has been updated";
    $_SESSION['msg_type'] = 'warning';
}

if(isset($_GET['delete'])){
    $medecine_id = $_GET['delete'];
    $con->query("DELETE FROM medecines WHERE medecine_id=$medecine_id") or die ($con->error);
    $_SESSION['message'] = "Record has been deleted";
    $_SESSION['msg_type'] = 'danger';
}

$result = $con->query("SELECT * from medecines") or die($con->error);
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
            <li <?php if ($_SESSION['role'] == 'dentist' || $_SESSION['role'] == 'nurse' ):?> hidden<?php endif ?>><a href="admin.php">Users</a></li>
            <li <?php if ($_SESSION['role'] == 'dentist' || $_SESSION['role'] == 'nurse' ):?> hidden<?php endif ?>><a href="clients.php">Clients</a></li>
            <li><a href="appointments.php">Appointments</a></li>
            <li><a href="medecines.php" class="active">Medecines</a></li>
            <li <?php if ($_SESSION['role'] == 'dentist' || $_SESSION['role'] == 'nurse' ):?> hidden<?php endif ?>><a href="products.php">Products</a></li>
            <li <?php if ($_SESSION['role'] == 'dentist' || $_SESSION['role'] == 'nurse' ):?> hidden<?php endif ?>><a href="orders.php">Orders</a></li>
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
                           <th>Stock</th>
                           <th colspan="2" <?php if ($_SESSION['role'] == 'nurse'):?> hidden<?php endif ?>>Action</th>
                        </tr>
                     </thead>
                     <?php
                        while ($row = $result->fetch_assoc()):?>
                     <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['stock']; ?></td>
                        <td <?php if ($_SESSION['role'] == 'nurse'):?> hidden<?php endif ?>> 
                           <a href="medecines.php?edit=<?php echo $row['medecine_id']; ?>"><i class='fas fa-edit' style='font-size:20px;color:yellow'></i></a>
                           <a href="medecines.php?delete=<?php echo $row['medecine_id']; ?>"><i class='fas fa-trash' style='font-size:20px;color:red'></i></a>
                        </td>
                     </tr>
                     <?php endwhile; ?>    
                  </table>
               </div>
            </div>
            <div class="col-sm-5" style="padding-left: 75px;" <?php if ($_SESSION['role'] == 'nurse'):?> hidden<?php endif ?>>
               <div class="row justify-content-center">
                  <?php 
                     if($edit == true):
                     ?>
                  <h3>Update existing medecint</h3>
                  <?php else: ?>
                  <h3>Add a new medecine</h3>
                  <?php endif ?>
                  <form action="" method="POST">
                     <table>
                        <input type="hidden" name="medecine_id" value="<?php echo $medecine_id; ?>">  
                        <tr>
                           <td>
                              <div class="form-group">
                                 <label>Name</label>
                                 <input class="form-control" type="text" name="name"
                                    value="<?php echo $name; ?>"
                                    placeholder="Enter medecine name" required>
                              </div>
                           </td>
                           <td>
                              <div class="form-group">
                                 <label>Stock</label>
                                 <input class="form-control" type="text" name="stock"
                                    value="<?php echo $stock; ?>"
                                    placeholder="Enter stock" required>
                              </div>
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