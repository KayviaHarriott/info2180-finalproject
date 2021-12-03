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
        default: ?>
            <h1 class="server-error">Page Not Found</h1>
            <?php break;
    endswitch;
} // End-if

if (array_key_exists("a", $_POST)){
    $action = filterData($_POST["a"]);

    switch ($action) {
        case "add-user":
            addUser();
            break;
        case "sign-in":
            //
            break;
        case "log-out":
            //
            break;
        case "add-issue":
            addIssue();
            break;
        default:
            // Respond with a 404 or something
            echo "Error";
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
        echo "TEST";
        // Hash the password and add the new user
    } // End-if
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
        createIssue($data);
    } // End-if
} // End-addIssue

?>
