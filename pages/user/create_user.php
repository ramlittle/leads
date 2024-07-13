
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
    $user->is_active=1; //default active for new users
    $user->access_level=2;//default access level for standard user

    if ($user->createUser()===true) {
        echo "<div class='alert alert-success' role='alert'>Added successfully</div>";
    } elseif ($user->createUser() === 'email_exists') {
        echo "<div class='alert alert-danger' role='alert'>Username already exists</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Failed Adding</div>";
    }
}
?>


<p class="fs-1 text-center p-2 bg-warning text-white">Create User</p>
<div class="d-flex justify-content-end p-2">
<a href="/todo/" class="btn btn-danger">Back</a>
</div>
<form method="POST" action="create_user.php">
    <table class="table">
        <tr>
            <td><label>First Name</label></td>
            <td><input type="text" class="form-control" name="first_name" required /></td>
        </tr>
        <tr>
            <td><label>Last Name</label></td>
            <td><input type="text" class="form-control" name="last_name" required/></td>
        </tr>
        <tr>
            <td><label>Email</label></td>
            <td><input type="text" class="form-control" name="email" required/></td>
        </tr>
        <tr>
            <td><label>Password</label></td>
            <td><input type="text" class="form-control" name="password" required/></td>
        </tr>
       
        <tr>
            <td colspan="2 d-flex justify-content-end"></td>
        </tr>
    </table>
    <div class="d-flex justify-content-end p-2">

        <button type="submit" class="btn btn-success">Save</button>
    </div>
</form>


<?php include_once "../../partials/footer.php";?>