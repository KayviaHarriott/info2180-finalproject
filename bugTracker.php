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
    case "New User":
        include "newUserView.php";
        break;
    case "Sign In":
        include "signInView.php";
        break;
    case "New Issue":
        include "newIssueView.php";
        break;
    case "Issues":
        include "issueView.php";
        break;
    default: ?>
        <h1 class="server-error">Page Not Found</h1>
        <?php break;
endswitch;

//--------------------------Data Management Functions-------------------------

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
