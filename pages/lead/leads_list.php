<?php
require_once "../../pages/auth/check-login.php";
include_once "../../classes/Lead.php";
include_once "../../config/dbleads.php";
include_once '../../partials/header.php';

$db = new Database();
$dbase = $db->getConnection();

$lead = new Lead($dbase);
$statement = $lead->readLeads($_SESSION['user_id']);

//search feature
$searchResults = array();
if (isset($_GET['query'])) {
    $searchQuery = $_GET['query'];
    $searchResults = $activity->searchLead($searchQuery);

    // Optionally, copy the search results to another variable if needed
    $statement = $searchResults;
}
?>

<div class="col-4">
    <form method="GET" action="leads_list.php" class="form">
        <input class="p-2" type="text" name="query" placeholder="Search lead...">
        <input class="p-2" type="submit" value="Search">
    </form>
</div>
<div>
    <a href="/leads/pages/add_lead.php">Add Lead</a>
</div>
<div>
    <table border="1">
        <thead>
            <tr>
                <th>LEAD ID</th>
                <th>PHONE NUMBER</th>
                <th>CONTACT NAME</th>
                <th>EMAIL</th>
                <th>DISPOSITION</th>
                <th>ADDED BY</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($statement as $row) {
                echo "<tr>
                        <td>" . $row['lead_id'] . "</td>
                        <td>" . $row['phone_number'] . "</td>
                        <td>" . $row['contact_name'] . "</td>
                        <td>" . $row['email'] . "</td>
                        <td>" . $row['disposition'] . "</td>
                        <td>" . $row['added_by'] . "</td>
                        <td><a style='font-size:x-small;' href='pages/update_lead.php?id=" . $row['lead_id'] . "' role='button'>Update</a>
                        <a delete-lead-id='" . $row['lead_id'] . "' class='delete-lead-object'> " . $row['lead_id'] . " Delete</a>
                    </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- SCRIPT NEEDED FOR DELETE -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    $(document).on('click', '.delete-lead-object', function () {
        var id = $(this).attr('delete-lead-id');
        console.log(('this is what yu have choen to deleteasdf', id))
        // if want to design confirm box, try look at boot box or sweet alert
        var confirm = window.confirm("Are you sure you want to delete this record?")
        if (confirm == true) {
            $.post('./pages/delete_lead.php', {
                lead_id: id
            }, function (data) {
                location.reload();
            }).fail(function () {
                alert('unable to delete. please check code');
            })
        }
    })
</script>