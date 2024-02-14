<?php
include "config.php";



$fname = "";
$lname = "";
$address = "";
$phone = "";
$edit = false;
$client_id = 0;

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
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    $con->query("INSERT INTO clients (first_name, last_name, address, phone)
                     VALUES ('$fname', '$lname', '$address', '$phone');") or die($con->error);
    $_SESSION['message'] = "Record has been saved";
    $_SESSION['msg_type'] = 'success';
}

if(isset($_GET['edit'])){
    $client_id = $_GET['edit'];
    $edit = true;
    $result = $con->query("SELECT * FROM clients WHERE client_id=$client_id") or die($con->error);
    if($result){
        $row = $result->fetch_array();
        $fname = $row['first_name'];
        $lname = $row['last_name'];
        $address = $row['address'];
        $phone = $row['phone'];
    }
}

if(isset($_POST['update'])){
    $client_id = $_POST['client_id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    $con->query("UPDATE clients SET first_name = '$fname', last_name='$lname', address='$address', phone='$phone'
    WHERE client_id = $client_id;")
        or die($con->error);

    $_SESSION['message'] = "Record has been updated";
    $_SESSION['msg_type'] = 'warning';
}

if(isset($_GET['delete'])){
    $client_id = $_GET['delete'];
    $con->query("DELETE FROM clients WHERE client_id=$client_id") or die ($con->error);
    $_SESSION['message'] = "Record has been deleted";
    $_SESSION['msg_type'] = 'danger';
}

$result = $con->query("SELECT * from clients") or die($con->error);
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
            <li <?php if ($_SESSION['role'] != 'admin'):?> hidden<?php endif ?>><a href="admin.php">Users</a></li>
            <li><a href="clients.php" class="active">Clients</a></li>
            <li><a href="appointments.php">Appointments</a></li>
            <li><a href="medecines.php">Medecines</a></li>
            <li><a href="products.php">Products</a></li>
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
                           <th>First Name</th>
                           <th>Last Name</th>
                           <th>Address</th>
                           <th>Phone Number</th>
                           <th colspan="2">Action</th>
                        </tr>
                     </thead>
                     <?php
                        while ($row = $result->fetch_assoc()):?>
                     <tr>
                        <td><?php echo $row['first_name']; ?></td>
                        <td><?php echo $row['last_name']; ?></td>
                        <td><?php echo $row['address']; ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td>
                           <a href="clients.php?edit=<?php echo $row['client_id']; ?>"><i class='fas fa-edit' style='font-size:20px;color:yellow'></i></a>
                           <a href="clients.php?delete=<?php echo $row['client_id']; ?>"><i class='fas fa-trash' style='font-size:20px;color:red'></i></a>
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
                  <h3>Update existing client</h3>
                  <?php else: ?>
                  <h3>Add a new client</h3>
                  <?php endif ?>
                  <form action="" method="POST">
                     <table>
                        <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">  
                        <tr>
                           <td>
                              <div class="form-group">
                                 <label>First name</label>
                                 <input class="form-control" type="text" name="fname"
                                    value="<?php echo $fname; ?>"
                                    placeholder="Enter first name" required>
                              </div>
                           </td>
                           <td>
                              <div class="form-group">
                                 <label>Last name</label>
                                 <input class="form-control" type="text" name="lname"
                                    value="<?php echo $lname; ?>"
                                    placeholder="Enter last name" required>
                              </div>
                           </td>
                        </tr>
                        <tr>
                           <td>
                           <div class="form-group">
                           <label>Address</label>
                           <input class="form-control" type="text" name="address"
                              value="<?php echo $address; ?>"
                              placeholder="Enter address" required>
                           </div>
                           </td>
                           <td>
                            <div class="form-group">
                            <label>Phone No.</label>
                            <input class="form-control" type="text" name="phone"
                            value="<?php echo $phone; ?>"
                            placeholder="Enter phone number" required>
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