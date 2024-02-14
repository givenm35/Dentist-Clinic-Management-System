<?php
include "config.php";

if(isset($_POST['submit'])){

    $username = mysqli_real_escape_string($con,$_POST['txt_username']);
    $password = mysqli_real_escape_string($con,$_POST['txt_password']);
    $role = mysqli_real_escape_string($con,$_POST['role']);
    


    if (!empty($username) && !empty($password)){

        $sql_query = "select count(*) as user_count from users where username='".$username."' and password='".$password."'
        and role='".$role."'";
        $result = mysqli_query($con,$sql_query);
        $row = mysqli_fetch_array($result);

        $count = $row['user_count'];

        if($count > 0){
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
    
            if ($role=="admin")
            header('Location: admin.php');
            else if ($role=="employee")
            header('Location: clients.php');
            else if ($role=="dentist" || $role=="nurse")
            header('Location: appointments.php');
            
        }
        // else{
        //     array_push($messages, "Invalid username or password");
        // }

    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" rel="stylesheet"/>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body>
<div class="login-page">
      <div class="form">
        <div class="login">
          <div class="login-header">
            <h3>LOGIN</h3>
            <p>Please enter your credentials to login.</p>
          </div>
        </div>
        <form class="login-form" method="POST">
            <input type="text" class="textbox" id="txt_username" name="txt_username" placeholder="Username" required/>
            <input type="password" class="textbox" id="txt_password" name="txt_password" placeholder="Password" required/>
            <select name="role" class="form-control">
                    <option value="admin">admin</option>
                    <option value="employee">employee</option>
                    <option value="dentist">dentist</option>
                    <option value="nurse">nurse</option>
                </select>
          <button type="submit" value="Submit" name="submit" id="submit">login</button>
        </form>
      </div>
    </div>

</body>
</html>