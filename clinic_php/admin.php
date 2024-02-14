<?php
include "config.php";


$username = "";
$password = "";
$fname = "";
$lname = "";
$role = "";
$address = "";
$phone = "";
$edit = false;
$user_id = 0;

// Check user login or not
if(!isset($_SESSION['username']) || $_SESSION['role'] != 'admin'){
    header('Location: login.php');
}

// logout
if(isset($_POST['logout'])){
    session_destroy();
    unset($_SESSION["username"]);
    header('Location: login.php');    
}

$result = mysqli_query($con, "SELECT `AUTO_INCREMENT` as AI
FROM  INFORMATION_SCHEMA.TABLES
WHERE TABLE_SCHEMA = 'dentist_clinic'
AND   TABLE_NAME   = 'users';");
$row = mysqli_fetch_array($result);
$id = (int)$row['AI'];

if(isset($_POST['save'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    $con->query("INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role');")
     or die($con->error);

    $con->query("INSERT INTO employees (user_id, first_name, last_name, address, phone_no)
                     VALUES ($id, '$fname', '$lname', '$address', '$phone');") or die($con->error);
            
    $_SESSION['message'] = "Record has been saved";
    $_SESSION['msg_type'] = 'success';
}

if(isset($_GET['edit'])){
    $user_id = $_GET['edit'];
    $edit = true;
    $result = $con->query("SELECT * FROM users u JOIN employees e ON u.user_id = e.user_id
                            WHERE u.user_id=$user_id") or die($con->error);
    if($result){
        $row = $result->fetch_array();
        $username = $row['username'];
        $password = $row['password'];
        $fname = $row['first_name'];
        $lname = $row['last_name'];
        $role = $row['role'];
        $address = $row['address'];
        $phone = $row['phone_no'];
    }
}

if(isset($_POST['update'])){
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $role = $_POST['role'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    $con->query("UPDATE users u JOIN employees e SET u.username = '$username', u.password='$password', u.role='$role',
    e.first_name = '$fname', e.last_name='$lname', e.address='$address', e.phone_no='$phone'
    WHERE u.user_id = $user_id and e.user_id=$user_id;")
        or die($con->error);

    $_SESSION['message'] = "Record has been updated";
    $_SESSION['msg_type'] = 'warning';
}

if(isset($_GET['delete'])){
    $user_id = $_GET['delete'];
    $con->query("DELETE FROM users WHERE user_id=$user_id") or die ($con->error);
    $_SESSION['message'] = "Record has been deleted";
    $_SESSION['msg_type'] = 'danger';
}

$result = $con->query("SELECT * from users JOIN employees ON users.user_id = employees.user_id") or die($con->error);
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
            <h1 style="margin-top: 3%;">Admin Dashboard</h1>
         </center>
         <ul style="margin-top:5%;">
            <li><a href="admin.php" class="active">Users</a></li>
            <li><a href="clients.php">Clients</a></li>
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
                           <th >Username</th>
                           <th>First Name</th>
                           <th>Last Name</th>
                           <th>Role</th>
                           <th>Address</th>
                           <th>Phone Number</th>
                           <th colspan="2">Action</th>
                        </tr>
                     </thead>
                     <?php
                        while ($row = $result->fetch_assoc()):?>
                     <tr>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['first_name']; ?></td>
                        <td><?php echo $row['last_name']; ?></td>
                        <td><?php echo $row['role']; ?></td>
                        <td><?php echo $row['address']; ?></td>
                        <td><?php echo $row['phone_no']; ?></td>
                        <td>
                           <a href="admin.php?edit=<?php echo $row['user_id']; ?>"><i class='fas fa-edit' style='font-size:20px;color:yellow'></i></a>
                           <a href="admin.php?delete=<?php echo $row['user_id']; ?>"><i class='fas fa-trash' style='font-size:20px;color:red'></i></a>
                        </td>
                     </tr>
                     <?php endwhile; ?>    
                  </table>
               </div>
            </div>
            <div class="frm col-sm-4">
               <div class="row justify-content-center">
                  <?php 
                     if($edit == true):
                     ?>
                  <h3>Update existing user</h3>
                  <?php else: ?>
                  <h3>Create a new user</h3>
                  <?php endif ?>
                  <form action="" method="POST">
                     <table>
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                        <tr>
                           <td>
                              <div class="form-group">
                                 <label>Username</label>
                                 <input class="form-control" type="text" name="username"
                                    value="<?php echo $username; ?>"
                                    placeholder="Enter username" required>
                              </div>
                           </td>
                           <td>
                              <div class="form-group">
                                 <label>Password</label>
                                 <input class="form-control" type="password" name="password"
                                    value="<?php echo $password; ?>"
                                    placeholder="Enter password" required>
                              </div>
                           </td>
                        </tr>
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
                                 <label>Role</label>
                                 <select name="role" class="form-control" required>
                                    <option value="" disabled selected>Select role</option>
                                    <option value="admin">admin</option>
                                    <option value="admin">employee</option>
                                    <option value="nurse">nurse</option>
                                    <option value="dentist">dentist</option>
                                 </select>
                           </td>
                           <td>
                           <div class="form-group">
                           <label>Address</label>
                           <input class="form-control" type="text" name="address"
                              value="<?php echo $address; ?>"
                              placeholder="Enter address" required>
                           </div>
                           </td>
                        </tr>
                        <tr>
                        <td>
                        <div class="form-group">
                        <label>Phone No.</label>
                        <input class="form-control" type="text" name="phone"
                           value="<?php echo $phone; ?>"
                           placeholder="Enter phone number" required>
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