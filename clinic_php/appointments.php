<?php
include "config.php";
$client_id = 0;
$user_id = 0;
$first_name = "";
$action = "";
$edit = false;
$appointment_id = 0;


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
    $client_id = $_POST['client_id'];
    $time = $_POST['time'];
    $user_id = $_POST['user_id'];
    $action = $_POST['action'];
    $real_time = date("Y-m-d H:i:s", strtotime($time));
    $con->query("INSERT INTO appointments (client_id, time, user_id, action)
     VALUES ($client_id, '$real_time', $user_id, '$action');") or die($con->error);
    $_SESSION['message'] = "Record has been saved";
    $_SESSION['msg_type'] = 'success';
}

if($_SESSION['role'] != 'nurse'){
if(isset($_GET['edit'])){
    $appointment_id = $_GET['edit'];
    $edit = true;
    $result = $con->query("SELECT c.client_id as client_id, e.user_id as user_id, c.first_name as client_name, e.first_name as employee_name, DATE_FORMAT(a.time, '%b %D %H:%i') as time, a.action 
    FROM `appointments` a
    JOIN employees e ON e.user_id = a.user_id
    JOIN clients c ON c.client_id = a.client_id
    WHERE a.appointment_id = $appointment_id;") or die($con->error);
    if($result){
        $row = $result->fetch_array();
        $client_id = $row['client_id'];
        $client_name = $row['client_name'];
        $user_id = $row['user_id'];
        $employee_name = $row['employee_name'];
        $action = $row['action'];
    }
}

if(isset($_POST['update'])){
    $appointment_id = $_POST['appointment_id'];
    $client_id = $_POST['client_id'];
    $time = $_POST['time'];
    $user_id = $_POST['user_id'];
    $action = $_POST['action'];
    $real_time = date("Y-m-d H:i:s", strtotime($time));

    $con->query("UPDATE appointments SET client_id = $client_id, time='$real_time', user_id=$user_id, action='$action'
    WHERE appointment_id = $appointment_id;")
        or die($con->error);

    $_SESSION['message'] = "Record has been updated";
    $_SESSION['msg_type'] = 'warning';
}

if(isset($_GET['delete'])){
    $appointment_id = $_GET['delete'];
    $con->query("DELETE FROM appointments WHERE appointment_id=$appointment_id") or die ($con->error);
    $_SESSION['message'] = "Record has been deleted";
    $_SESSION['msg_type'] = 'danger';
}
}


$result = $con->query("SELECT appointment_id, CONCAT(c.first_name, ' ', c.last_name) as 'client_name',
CONCAT(e.first_name, ' ', e.last_name) as 'employee_name', DATE_FORMAT(a.time, '%b %D %H:%i') as time, a.action 
FROM `appointments` a
JOIN employees e ON e.user_id = a.user_id
JOIN clients c ON c.client_id = a.client_id
ORDER BY time;") or die($con->error);
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
            <li><a href="appointments.php" class="active">Appointments</a></li>
            <li><a href="medecines.php">Medecines</a></li>
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
                           <th>Client Name</th>
                           <th>Employee Name</th>
                           <th><i class='far fa-calendar-alt'></i> Time</th>
                           <th>Reason</th>
                           <th colspan="2" <?php if ($_SESSION['role'] == 'nurse'):?> hidden<?php endif ?>>Action</th>
                        </tr>
                     </thead>
                     <?php
                        while ($row = $result->fetch_assoc()):?>
                     <tr>
                        <td><?php echo $row['client_name']; ?></td>
                        <td><?php echo $row['employee_name']; ?></td>
                        <td><?php echo $row['time']; ?></td>
                        <td><?php echo $row['action']; ?></td>
                        <td <?php if ($_SESSION['role'] == 'nurse'):?> hidden<?php endif ?>>
                           <a href="appointments.php?edit=<?php echo $row['appointment_id']; ?>"><i class='fas fa-edit' style='font-size:20px;color:yellow'></i></a>
                           <a href="appointments.php?delete=<?php echo $row['appointment_id']; ?>"><i class='fas fa-trash' style='font-size:20px;color:red'></i></a>
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
                  <h3>Update existing client</h3>
                  <?php else: ?>
                  <h3>Add a new Appointment</h3>
                  <?php endif ?>
                  <form action="" method="POST">
                     <table>
                        <input type="hidden" name="appointment_id" value="<?php echo $appointment_id; ?>">  
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
                                 <label>Employee</label>
                                 <select name="user_id" class="form-control" required>
                                 <?php
                                 if($edit == true):
                                    ?><option value="<?php echo $user_id; ?>" selected><?php echo $user_id.'-->'.$employee_name; ?></option>
                                    <?php else:?>
                                    <option value="" disabled selected>Select client</option>
                                    <?php endif ?>
                                    <?php
                                    $result = $con->query("SELECT * from employees ") or die($con->error);
                                    while($row = mysqli_fetch_array($result)) {
                                        echo '<option value='.$row['user_id'].'>'.$row['user_id'].'-->'.$row['first_name'].'</option>';
                                    }
                                    ?>
                                 </select>
                              </div>
                           </td>
                        </tr>
                        <tr>
                           <td>
                           <div class="form-group">
                           <label>Time</label>
                           <input class="form-control" type="datetime-local" name="time"
                              value="<?php echo $time; ?>"
                              placeholder="yyyy/mm/dd" required>
                           </div>
                           </td>
                           <td>
                            <div class="form-group">
                            <label>Reason</label>
                            <input class="form-control" type="text" name="action"
                            value="<?php echo $action; ?>"
                            placeholder="Enter reason for visit" required>
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