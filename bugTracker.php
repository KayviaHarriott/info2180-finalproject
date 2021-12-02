<?php

function filterData(String $data) {
    $result = $data;

    $filters = [
        "striptags" => FILTER_SANITIZE_SPECIAL_CHARS,
        "string"    => FILTER_SANITIZE_STRING
    ];
    $filterFlags = [
        "striptags" => [FILTER_FLAG_STRIP_HIGH],
        "string"    => [FILTER_FLAG_STRIP_HIGH]
    ];
    $filterOrder = ["striptags", "string"];

    foreach ($filterOrder as $f) {
        $result = filter_var($result, $filters[$f], $filterFlags[$f]);
    } // End-foreach

    return $result;
} // End-filterData

//--------------------------Backend for AJAX Queries--------------------------

$page = filterData($_REQUEST["a"]);

switch ($page):
    case "New User": ?>
        <form id="new-user-form" action="#">
            <label id="f-name-label" for="f-name">Firstname</label>
            <br>
            <input type="text" class="f-name-input"
                id="f-name" placeholder=""/>
            <br><br>
            <label id="l-name-label" for="l-name">Lastname</label>
            <br>
            <input type="text" class="l-name-input"
                id="l-name" placeholder=""/>
            <br><br>
            <label id="p-word-label" for="p-word">Password</label>
            <br>
            <input type="text" class="p-word-input"
                id="p-word" placeholder=""/>
            <br><br>
            <label id="email-label" for="email">Email</label>
            <br>
            <input type="text" class="email-input"
                id="email" placeholder=""/>
            <br><br>
            <input type="submit" class="submit-button"
                id="submit-button" value="Submit"/>
        </form>
        <?php break;
    case "Sign In": ?>
        <form id="sign-in-form" action="#">
            <br><br>
            <label id="email-label" for="email">Email</label>
            <br>
            <input type="text" class="email-input" id="email" placeholder=""/>
            <br><br>
            <label id="p-word-label" for="p-word">Password</label>
            <br>
            <input type="password" class="p-word-input"
                id="p-word" placeholder=""/>
            <br><br>
            <input type="submit" class="sign-in-button"
                id="sign-in-button" value="Submit"/>
        </form>
        <?php break;
    case "New Issue": ?>
        <h1>NEW ISSUE</h1>
        <?php break;
    case "Issues":
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
        <?php break;
    default: ?>
        <h1 class="server-error">Page Not Found</h1>
        <?php break;
endswitch;

//--------------------------Data Management Functions-------------------------

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

/**
 * @brief Adds a new issue to the database
 *
 * @param Type <var> Description
 * @return type
 * @throws conditon
 */
function addIssue() {
    //
} // End-addIssue


// Get all the data from the POST request, filter it and then use the methods
// above to add the issue to the database or modify the issue
//$issueData = filterData($_POST["a"]);

?>
