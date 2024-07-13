<?php
include_once "../../config/dbleads.php";
include_once "../../classes/User.php";

$db = new Database();
$dbase = $db->getConnection();
$user = new User($dbase);


if ($_POST) {
    $user->first_name = $_POST['first_name'];
    $user->last_name = $_POST['last_name'];
    $user->email = $_POST['email'];
    $user->password = $_POST['password'];
    $user->is_active = 1; //default active for new users
    $user->access_level = 2;//default access level for standard user

    if ($user->createUser() === true) {
        echo "<div class='alert alert-success' role='alert'>Added successfully</div>";
    } elseif ($user->createUser() === 'email_exists') {
        echo "<div class='alert alert-danger' role='alert'>Email already exists</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Failed Adding</div>";
    }
}
?>

<link rel="stylesheet" type="text/css" href="/leads/css/pages/user/create_user.css" />

<section class="create-user">
    <div>
        <p>Sign Up</p>
    </div>

    <div>
        <a href="/leads/pages/auth/login.php">Back</a>
    </div>

    <div>
        <form method="POST" action="create_user.php">
            <div>
                <div>
                    <span><label>First Name</label></span>
                    <span><input type="text" class="form-control" name="first_name" required /></span>
                </div>
                <div>
                    <span><label>Last Name</label></span>
                    <span><input type="text" class="form-control" name="last_name" required /></span>
                </div>
                <div>
                    <span><label>Email</label></span>
                    <span><input type="email" class="form-control" name="email" required /></s>
                </div>
                <div>
                    <span><label>Password</label></span>
                    <span><input type="text" class="form-control" name="password" required /></span>
                </div>
                <div>
                    <span></span>
                    <span><button type="submit">Save</button></span>
                </div>
            </div>

        </form>
    </div>

</section>

<?php include_once "../../partials/footer.php"; ?>