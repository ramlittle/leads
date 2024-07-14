<?php
session_start(); // is needed in files that needs user authenticatoin and protection
//on login.php, logout.php and your other proctected php files, 
//if this is included in your header.php, then always include your header in all your pages too

//var_dump($_SESSION);//checks what columsn/field were obtained after user logs in

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    // User is not logged in, redirect to the login page
    header("Location: /leads/pages/auth/login.php");
    exit(); // Ensure that no further code execution happens after redirection
}

?>