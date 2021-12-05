<?php
include_once "dataMgmt.php";

$filter = [];
if (array_key_exists("iid", $_GET)) {
    $filter = ["id" => filterData($_GET["iid"])];
} // End-if

$issue = getIssues($filter);

switch (count($issue)):
    case 1:
        $user = getUsers(["id" => $issue[0]["created_by"]]);
    ?>
        <div class="issue-detailed-container">
            <div class="title" id="title">
                <h1><?= $issue[0]["title"] ?></h1>
                <div id="issue-title" class="issue-title">
                    <h4>Issue #<?= $issue[0]["id"] ?></h4>
                </div>
            </div>
            <br>
            <div id= "issue-detailed" class= "issue-detailed">
                <p><?= $issue[0]["description"] ?></p>
                <br>
                <ul id="latest-issue" class="latest=issue">
                    <li>Issue created on <?= $issue[0]["created"] ?> by
                        <?= $user[0]["firstname"] ?> <?= $user[0]["lastname"] ?></li>
                    <li>Last updated on <?= $issue[0]["updated"] ?></li>
                </ul>
            </div>
            <div id="overview-issue" class="overview-issue">
                <h4 id="assignedTo-issue">Assigned To</h4>
                <p><?= $issue[0]["firstname"] ?> <?= $issue[0]["lastname"] ?></p>
                <br>
                <h4 id="type-issue">Type:</h4>
                <p><?= $issue[0]["type"] ?></p>
                <br>
                <h4 id="priority-issue">Priority:</h4>
                <p><?= $issue[0]["priority"] ?></p>
                <br>
                <h4 id="status-issue">Status:</h4>
                <p><?= $issue[0]["status"] ?></p>
                <br>
                <br>
            </div>
            <div id="detail-issue-buttons" class="detail-issue-buttons" >
                <br><input type="submit" class="mark-closed-button" id="mark-closed-button" value="Mark as Closed">
                <br>
                <br><input type="submit" class="mark-in-progress-button" id="mark-in-progress-button" value="Mark in Progress">
            </div>
        </div>
        <?php break;
    case 0: ?>
        <h1>ISSUE NOT FOUND</h1>
        <?php break;
    default: ?>
        <h1>ERROR</h1>
        <?php break;
endswitch;
?>
