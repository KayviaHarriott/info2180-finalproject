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
        case "Issue Detail":
            include "issueDetailView.php";
            break;
        default: ?>
            <h1 class="server-error">Page Not Found</h1>
            <?php break;
    endswitch;
} // End-if

if (array_key_exists("z", $_GET)){
    $action = filterData($_GET["z"]);
    //header("Content-Type: application/json; charset=utf-8");

    switch ($action) {
        case "add-user":
            echo addUser();
            break;
        case "sign-in":
            echo signIn();
            break;
        case "log-out":
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            } // End-if

            session_unset();
            session_destroy();
            echo json_encode(["status" => "LOGOUT"]);
            break;
        case "add-issue":
            echo addIssue();
            break;
        case "update-issue-status":
            echo updateIssue();
            break;
        default:
            echo json_encode(["status" => "ERROR"]);
            break;
    } // End-switch-case
} // End-if

function signIn() {
    $attributes = ["email", "passwd"];
    $isValid = true;
    foreach ($attributes as $a) {
        $isValid = $isValid && array_key_exists($a, $_GET);
    } // End-foreach

    if ($isValid) {
        return verifyUser([
            "email" => $_GET["email"],
            "passwd" => $_GET["passwd"]
        ]);
    } // End-if
    return json_encode(["status" => "data missing"]);
} // End-signIn

function updateIssue() {
    $attributes = ["id", "status"];
    $isValid = true;
    foreach ($attributes as $a) {
        $isValid = $isValid && array_key_exists($a, $_GET);
    } // End-foreach

    if ($isValid) {
        return updateIssueStatus([
            "id" => $_GET["id"],
            "status" => $_GET["status"]
        ]);
    } // End-if
    return json_encode(["status" => "data missing"]);
} // End-updateIssue

function addUser() {
    $attributes = ["f-name", "l-name", "email", "passwd"];
    $alpha = "A-Za-z";
    $alphaNum = "${alpha}0-9";
    $tld = "\.[${alpha}][${alphaNum}]*";
    $hostname = "[${alpha}][${alphaNum}\-]*[${alphaNum}]";
    $username = "[${alpha}][${alphaNum}\-\.]*[${alphaNum}]";

    $realNameRegex = "/^[{$alpha}]+$/";
    $emailRegex = "/^${username}@${hostname}${tld}$/";

    $isValid = true;
    foreach ($attributes as $a) {
        $isValid = $isValid && array_key_exists($a, $_GET);
    } // End-foreach

    $err = [];
    if ($isValid) {
        foreach ($_GET as $k => $v) {
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
            foreach ($attributes as $a) {
                $data[$a] = $_GET[$a];
            } // End-foreach
            return createUser($data);
        } // End-if
    }else{
        $err["status"] = "data missing";
    } // End-if
    return json_encode($err);
} // End-addUser

function addIssue() {
    $attributes = ["title", "desc", "type", "pri", "assign"];
    $alpha = "A-Za-z";
    $alphaNum = "${alpha}0-9";
    $tld = "\.[${alpha}][${alphaNum}]*";
    $hostname = "[${alpha}][${alphaNum}\-]*[${alphaNum}]";
    $username = "[${alpha}][${alphaNum}\-\.]*[${alphaNum}]";

    $realNameRegex = "/^[{$alpha}]+$/";
    $emailRegex = "/^${username}@${hostname}${tld}$/";

    $isValid = true;
    foreach ($attributes as $a) {
        $isValid = $isValid && array_key_exists($a, $_GET);
    } // End-foreach

    $err = [];
    if ($isValid) {
        foreach ($_GET as $k => $v) {
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
                case "pri":
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
            foreach ($attributes as $a) {
                $data[$a] = $_GET[$a];
            } // End-foreach
            return createIssue($data);
        } // End-if
    }else{
        $err["status"] = "data missing";
    } // End-if
    return json_encode($err);
} // End-addIssue
?>
