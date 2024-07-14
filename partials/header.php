<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leads Management</title>
    <link rel="stylesheet" type="text/css" href="./css/partials/header.css"/>
    
</head>

<?php
function showWhoIsLoggedIn(){
    if (isset($_SESSION['email'])) {
        // htmlspecialchars is used here to avoid prevent XSS attacks
        return htmlspecialchars($_SESSION['email']);
    } else {
        return "Not logged in.";
    }
}

?>

<script>
    function confirmLogout(){
        const confirmedLogout=window.confirm("You are about to be logged out");
        if(confirmedLogout){
            return true;
        }
        return false;
    }
</script>

<body>

<header>
    <nav>
        <ul>
            <a href ="">Dashboard</a>
            <a href ="">Leads</a>
            <a href ="">Profile</a>
        </ul>
    </nav>
    <div>
        <span><?php echo showWhoIsLoggedIn(); ?></span>
        <span><a href ="/leads/pages/auth/logout.php" onclick="return confirmLogout();">Logout</a></span>
    </div>
    <hr/>
</header>