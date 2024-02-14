<?php
session_start();
$host = "localhost"; /* Host name */
$user = "root"; /* User */
$password = ""; /* Password */
$db = "dentist_clinic"; /* Database name */


$con = mysqli_connect($host, $user, $password,$db);
// Check connection
if (!$con) {
  die("Connection failed: " . mysqli_connect_error());
}
?>