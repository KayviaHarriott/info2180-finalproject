<?php

function connectToDB() {
    $host = 'localhost';
    $username = 'bugmeAdmin';
    $password = 'password123';
    $dbname = 'bugme';

    return new PDO("mysql:host={$host};dbname={$dbname}",
        $username, $password);
} // End-connectToDB

/**
 * @brief Returns a sanitized string of the input
 *
 * @param String $data The data to be sanitized
 * @return String
 */
function filterData($data) {
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

/**
 * @brief Returns an array of sanitized strings
 *
 * @param Array $data The array of data to be sanitized
 * @return Array
 */
function filterDataArray(Array $dataArr) {
    $result = [];

    foreach ($dataArr as $k => $v) {
        $result[$k] = filterData($v);
    } // End-foreach

    return $result;
} // End-filterDataArray


/**
 * @brief Returns a list of users
 *
 * @return Array
 */
function getUsers() {
    $bugTrackerDB = connectToDB();
    $stmt = $bugTrackerDB->prepare(
        "SELECT `id`, `firstname`, `lastname` FROM users");
    $result = $stmt->fetchAll();

    // Closes the connection
    $bugTrackerDB = null;
    $stmt = null;
    return $result;
} // End-getUsers

/**
 * @brief Returns a list of issues
 *
 * @param Array $filter An associative array of filter criteria to narrow down
 *                      the search results
 * @return Array
 */
function getIssues($filter = []) {
    $data = filterDataArray($filter);
    $clauses = "";

    // Converts the filter criteria into a WHERE clause in SQL
    foreach ($data as $k => $v) {
        switch ($k) {
            case "status":
                switch ($v) {
                    case "open":
                        $clauses = $clauses . "status = 'OPEN' and ";
                        break;
                    case "in-progress":
                        $clauses = $clauses . "status = 'IN PROGRESS' and ";
                        break;
                    case "closed":
                        $clauses = $clauses . "status = 'CLOSED' and ";
                        break;
                    default:
                        $clauses = $clauses . "";
                        break;
                } // End-switch-case
                break;
            case "owner":
                $clauses = $clauses . "created_by = {$v} and ";
                break;
            default:
                $clauses = $clauses . "";
                break;
        } // End-switch-case
    } // End-foreach

    if (strlen($clauses) > 0) {
        $clauses = " WHERE " . (rtrim($clauses, " and "));
    } // End-if

    $bugTrackerDB = connectToDB();
    $stmt = $bugTrackerDB->prepare(
        "SELECT i.`id`, `title`, `type`, `status`, `firstname`, `lastname`,
            `created`
        FROM
            (`issues` as i JOIN `users` as a
                ON i.`assigned_to`=a.`id`){$clauses};");
    $result = $stmt->fetchAll();

    // Closes the connection
    $bugTrackerDB = null;
    $stmt = null;
    return $result;
} // End-getIssues

/**
 * @brief Adds a new user to the database
 *
 * @param Array $uData An associative array of the data to create the issue
 */
function createUser($uData) {
    $data = filterDataArray($uData);

    $bugTrackerDB = connectToDB();
    $stmt = $bugTrackerDB->prepare(
        "INSERT INTO users (`firstname`, `lastname`, `password`, `email`)
        VALUES (:uFname, :uLname, :uPasswd, :uEmail)");

    $stmt->bindParam(":uFname", $data["fname"]);
    $stmt->bindParam(":uLname", $data["lname"]);
    $stmt->bindParam(":uPasswd", $data["passwd"]);
    $stmt->bindParam(":uEmail", $data["email"]);

    $stmt->execute();
} // End-createUser

/**
 * @brief Adds a new issue to the database
 *
 * @param Array $iData An associative array of the data to create the issue
 */
function createIssue($iData) {
    $data = filterDataArray($iData);

    $bugTrackerDB = connectToDB();
    $stmt = $bugTrackerDB->prepare(
        "INSERT INTO issues (`title`, `description`, `type`, `priority`,
            `assigned_to`, `created_by`)
        VALUES (:iTitle, :iDesc, :iType, :iPri, :iAssign, :iCreator)");

    $stmt->bindParam(":iTitle", $data["title"]);
    $stmt->bindParam(":iDesc", $data["desc"]);
    $stmt->bindParam(":iType", $data["type"]);
    $stmt->bindParam(":iPri", $data["pri"]);
    $stmt->bindParam(":iAssign", $data["assign"]);
    $stmt->bindParam(":iCreator", $data["creator"]);

    $stmt->execute();
} // End-createIssue
?>
