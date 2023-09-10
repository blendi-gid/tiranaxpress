<?php
session_start();

$nid = "";
$phone_number = "";
$father_name = "";
$otp = "";
$errors = array(); 

$db = mysqli_connect('localhost', 'root', '', 'tiranaxpress');

  $user_check_query = "SELECT * FROM users WHERE nid='$nid' OR phone_number='$phone_number' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { 
    if ($user['username'] === $username) {
      array_push($errors, "NID already registered!");
    }

    if ($user['email'] === $email) {
      array_push($errors, "Phone number already registered!");
    }
  }

if (isset($_POST['login_user'])) {
  $nid = mysqli_real_escape_string($db, $_POST['nid']);
  $phone_number = mysqli_real_escape_string($db, $_POST['phone_number']);
  $father_name = mysqli_real_escape_string($db, $_POST['father_name']);
  $otp = mysqli_real_escape_string($db, $_POST['otp']);

  if (empty($nid)) {
  	array_push($errors, "NID is required!");
  }
  if (empty($phone_number)) {
  	array_push($errors, "Phone number is required!");
  }
  if (empty($father_name)) {
    array_push($errors, "Father's Name is required!");
  }
  if (empty($otp)) {
    array_push($errors, "OTP is required!");
  }


  if (count($errors) == 0) {
  	$otp = md5($otp);
  	$query = "SELECT * FROM users WHERE nid='$nid' AND phone_number='$phone_number' AND father_name='$father_name' AND otp='$otp'";
  	$results = mysqli_query($db, $query);
  	if (mysqli_num_rows($results) == 1) {
  	  header('location: home.html');
  	}else {
  		array_push($errors, "Wrong username/password combination");
  	}
  }
}

?>