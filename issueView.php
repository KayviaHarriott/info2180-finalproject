<?php
include_once "dataMgmt.php";

switch (filterData($_GET["filter"])) {
    case "OPEN":
        $filter = ["status" => "open"];
        break;
    case "MY TICKETS":
        // REMEMBER TO CHANGE TO USE THE ID THE PAGE SENDS
        $filter = ["owner" => 1];
        break;
    default:
        $filter = [];
        break;
} // End-switch-case

$issueLst = getIssues($filter); ?>
<div id="filter" class="filter">
    <span><p>Filter by: </p></span>
    <a class="selected-filter" id="all-button" href="#">ALL</a>
    <a id="open-button" href="#">OPEN</a>
    <a id="my-tickets-button" href="#">MY TICKETS</a>
</div>
<table id="issues-table">
    <tr class="table-header">
        <th id='table-title'>Title</th>
        <th id='table-title'>Type</th>
        <th id='table-title'>Status</th>
        <th id='table-title'>Assigned To</th>
        <th id='table-title'>Created</th>
    </tr>
<?php foreach ($issueLst as $i):
    switch ($i["status"]) {
        case "OPEN":
            $type = "open-bug";
            break;
        case "CLOSED":
            $type = "closed-bug";
            break;
        case "IN PROGRESS":
            $type = "inprogress-bug";
            break;
        default:
            $type = "server-error";
            break;
    } // End-switch-case ?>
    <tr>
        <td><span>#<?= $i["id"] ?></span> <?= $i["title"] ?></td>
        <td><?= $i["type"] ?></td>
        <td class=<?= $type ?>><?= $i["status"] ?></td>
        <td><?= $i["firstname"] ?> <?= $i["lastname"] ?></td>
        <td><?= $i["created"] ?></td>
    </tr>
<?php endforeach; ?>
</table>
