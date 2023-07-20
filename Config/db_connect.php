<?php

    $conn=mysqli_connect('localhost','marc','marc','sef_login');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }
      
?>