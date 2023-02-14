<?php
echo phpinfo();
$servername = "localhost";
$username = "ngfunjob_hmc";
$password = "ZyBs&]}YhRkP";

try {
  $conn = new PDO("mysql:host=$servername;dbname=ngfunjob_funjobpc_app", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "Connected successfully!!!!";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>