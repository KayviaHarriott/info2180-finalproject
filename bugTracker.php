<?php
include_once "dataMgmt.php";

//GLOBAL $bugTrackerDB = connectToDB();

if (array_key_exists("a", $_GET)){
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
        case "Issue List":
            include "issueList.php";
            break;
        default: ?>
            <h1 class="server-error">Page Not Found</h1>
            <?php break;
    endswitch;
} // End-if

if (array_key_exists("a", $_POST)){
    $action = filterData($_POST["a"]);
    header("Content-Type: application/json; charset=utf-8");

    switch ($action) {
        case "add-user":
            echo addUser();
            break;
        case "sign-in":
            echo verifyUser([
                "email" => $_POST["email"],
                "passwd" => $_POST["passwd"]
            ]);
            break;
        case "log-out":
            echo json_encode(["status" => "LOGOUT"]);
            break;
        case "add-issue":
            echo addIssue();
            break;
        default:
            echo json_encode(["status" => "ERROR"]);
            break;
    } // End-switch-case
} // End-if

function addUser() {
    $alpha = "A-Za-z";
    $alphaNum = "${alpha}0-9";
    $tld = "\.[${alpha}][${alphaNum}]*";
    $hostname = "[${alpha}][${alphaNum}\-]*[${alphaNum}]";
    $username = "[${alpha}][${alphaNum}\-\.]*[${alphaNum}]";

    $realNameRegex = "/^[{$alpha}]+$/";
    $emailRegex = "/^${username}@${hostname}${tld}$/";

    foreach ($_POST as $k => $v) {
        $err = [];
        switch ($k) {
            case "f-name":
                if (preg_match($realNameRegex, $v) != 1) {
                    $err[$k] = false;
                } // End-if
                break;
            case "l-name":
                if (preg_match($realNameRegex, $v) != 1) {
                    $err[$k] = false;
                } // End-if
                break;
            case "email":
                if (preg_match($emailRegex, $v) != 1) {
                    $err[$k] = false;
                } // End-if
                break;
            default:
                // Do nothing
                break;
        } // End-switch-case
    } // End-foreach

    if (count($err) == 0) {
        $data = [];
        $attributes = ["f-name", "l-name", "email", "passwd"];
        foreach ($attributes as $a) {
            $data[$a] = $_POST[$a];
        } // End-foreach
        return createUser($data);
    } // End-if
    return json_encode($err);
} // End-addUser

function addIssue() {
    $alpha = "A-Za-z";
    $alphaNum = "${alpha}0-9";
    $tld = "\.[${alpha}][${alphaNum}]*";
    $hostname = "[${alpha}][${alphaNum}\-]*[${alphaNum}]";
    $username = "[${alpha}][${alphaNum}\-\.]*[${alphaNum}]";

    $realNameRegex = "/^[{$alpha}]+$/";
    $emailRegex = "/^${username}@${hostname}${tld}$/";

    foreach ($_POST as $k => $v) {
        $err = [];
        switch ($k) {
            case "title":
                if (preg_match("/^[\w]+$/", $v) != 1) {
                    $err[$k] = false;
                } // End-if
                break;
            case "desc":
                if (preg_match("/^[\w]+$/", $v) != 1) {
                    $err[$k] = false;
                } // End-if
                break;
            case "assign":
                if (preg_match("/^[\d]+$/", $v) != 1) {
                    $err[$k] = false;
                } // End-if
                break;
            case "type":
                if (preg_match("/^(Bug)|(Proposal)|(Task)$/", $v) != 1) {
                    $err[$k] = false;
                } // End-if
                break;
            case "priority":
                if (preg_match("/^(Minor)|(Major)|(Critical)$/", $v) != 1) {
                    $err[$k] = false;
                } // End-if
                break;
            default:
                // Do nothing
                break;
        } // End-switch-case
    } // End-foreach

    if (count($err) == 0) {
        $data = [];
        $attributes = ["title", "desc", "type", "pri", "assign", "creator"];
        foreach ($attributes as $a) {
            $data[$a] = $_POST[$a];
        } // End-foreach
        return createIssue($data);
    } // End-if
    return json_encode($err);
} // End-addIssue

?>
