<?php

/**
 * @brief Retreives all the issues filtered by the data in the parameters
 *
 * @param Type <var> Description
 * @return type
 * @throws conditon
 */
function getIssues() {
    // just a placeholder until the code gets written
    $statusLst = ["OPEN", "CLOSED", "IN PROGRESS"];
    $result = [];
    for ($i = 0; $i < 3; $i++) {
        $iObj = [
            "title"       => "Issue {$i}",
            "type"        => "BUG",
            "status"      => "{$statusLst[$i]}",
            "assignedTo"  => "person",
            "dateCreated" => "date"
        ];
        $result[] = $iObj;
    } // End-for
    return $result;
} // End-getIssues

$issueLst = getIssues(); ?>

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
        <td><?= $i["title"] ?></td>
        <td><?= $i["type"] ?></td>
        <td class=<?= $type ?>><?= $i["status"] ?></td>
        <td><?= $i["assignedTo"] ?></td>
        <td><?= $i["dateCreated"] ?></td>
    </tr>
<?php endforeach; ?>
</table>
