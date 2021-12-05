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
 * @param string $data The data to be sanitized
 * @return string
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
 * @param array $data The array of data to be sanitized
 * @return array
 */
function filterDataArray(array $dataArr) {
    $result = [];

    foreach ($dataArr as $k => $v) {
        $result[$k] = filterData($v);
    } // End-foreach

    return $result;
} // End-filterDataArray


/**
 * @brief Executes the query and returns the result
 *
 * @param string $query The query to be run
 * @param array $binds An associative array of binds used in the query and
 *                     the value to be bound
 * @param int $mode The mode to fetch the results in
 * @return array
 */
function execQuery(string $query, array $binds = [], int $mode) {
    $bugTrackerDB = connectToDB();

    $stmt = $bugTrackerDB->prepare($query);

    foreach ($binds as $k => $v) {
        $stmt->bindValue($k, $v[0], $v[1]);
    } // End-foreach

    $stmt->execute();
    //echo $stmt->queryString;
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Closes the connection
    $bugTrackerDB = null;
    $stmt = null;
    return $result;
} // End-execQuery

/**
 * @brief Returns a list of users
 *
 * @return array
 */
function getUsers($filter = []) {
    $data = filterDataArray($filter);
    $clauses = "";
    $binds = [];

    // Converts the filter criteria into a WHERE clause in SQL
    foreach ($data as $k => $v) {
        switch ($k) {
            case "id":
                $clauses = $clauses . "`id` = :uID and ";
                $binds[":uID"] = [intval($v), PDO::PARAM_INT];
                break;
            default:
                $clauses = $clauses . "";
                break;
        } // End-switch-case
    } // End-foreach

    if (strlen($clauses) > 0) {
        $clauses = " WHERE " . (rtrim($clauses, " and "));
    } // End-if

    $query = "SELECT `id`, `firstname`, `lastname` FROM users{$clauses}";
    $result = execQuery($query, $binds, PDO::FETCH_ASSOC);
    return $result;
} // End-getUsers

/**
 * @brief Returns a list of issues
 *
 * @param array $filter An associative array of filter criteria to narrow down
 *                      the search results
 * @return array
 */
function getIssues($filter = []) {
    $data = filterDataArray($filter);
    $clauses = "";
    $binds = [];

    // Converts the filter criteria into a WHERE clause in SQL
    foreach ($data as $k => $v) {
        switch ($k) {
            case "status":
                switch ($v) {
                    case "open":
                        $clauses = $clauses . "`status` = :iStatus and ";
                        $binds[":iStatus"] = ["OPEN", PDO::PARAM_STR];
                        break;
                    case "in-progress":
                        $clauses = $clauses . "`status` = :iStatus and ";
                        $binds[":iStatus"] = ["IN PROGRESS", PDO::PARAM_STR];
                        break;
                    case "closed":
                        $clauses = $clauses . "`status` = :iStatus and ";
                        $binds[":iStatus"] = ["CLOSED", PDO::PARAM_STR];
                        break;
                    default:
                        $clauses = $clauses . "";
                        break;
                } // End-switch-case
                break;
            case "assignedTo":
                $clauses = $clauses . "`assigned_to` = :iAssig and ";
                $binds[":iAssig"] = [intval($v), PDO::PARAM_INT];
                break;
            case "id":
                $clauses = $clauses . "i.`id` = :iID and ";
                $binds[":iID"] = [intval($v), PDO::PARAM_INT];
                break;
            default:
                $clauses = $clauses . "";
                break;
        } // End-switch-case
    } // End-foreach

    if (strlen($clauses) > 0) {
        $clauses = " WHERE " . (rtrim($clauses, " and "));
    } // End-if

    $query = "SELECT i.`id`, `title`, `description`, `type`, `status`,
            `firstname`, `lastname`, `created_by`, `created`, `updated`
        FROM
            (`issues` as i JOIN `users` as a
                ON i.`assigned_to`=a.`id`){$clauses};";

    $result = execQuery($query, $binds, PDO::FETCH_ASSOC);
    return $result;
} // End-getIssues

/**
 * @brief Adds a new user to the database
 *
 * @param array $uData An associative array of the data to create the issue
 * @return JSON
 */
function createUser($uData) {
    $data = filterDataArray($uData);

    $query = "SELECT `email` FROM users WHERE `email` = :uEmail";
    $binds = [":uEmail" => [$data["email"], PDO::PARAM_STR]];

    $result = execQuery($query, $binds, PDO::FETCH_ASSOC);

    if (count($result) == 0) {
        $query = "INSERT INTO users (`firstname`, `lastname`, `password`,
                `email`)
            VALUES (:uFname, :uLname, :uPasswd, :uEmail)";

        $binds = [":uFname" => [$data["f-name"], PDO::PARAM_STR],
            ":uLname" => [$data["l-name"], PDO::PARAM_STR],
            ":uPasswd" => [password_hash($data["passwd"], PASSWORD_DEFAULT), PDO::PARAM_STR],
            ":uEmail" => [$data["email"], PDO::PARAM_STR]
        ];

        $result = execQuery($query, $binds, PDO::FETCH_ASSOC);
        return json_encode(["status" => "success"]);
    } // End-if
    return json_encode(["status" => "user already exists with that email"]);
} // End-createUser

/**
 * @brief Adds a new issue to the database
 *
 * @param array $iData An associative array of the data to create the issue
 * @return JSON
 */
function createIssue($iData) {
    $data = filterDataArray($iData);
    var_dump($data);

    $query = "INSERT INTO issues (`title`, `description`, `type`, `priority`,
            `assigned_to`, `created_by`)
        VALUES (:iTitle, :iDesc, :iType, :iPri, :iAssign, :iCreator)";

    $binds = [":iTitle" => [$data["title"], PDO::PARAM_STR],
        ":iDesc" => [$data["desc"], PDO::PARAM_STR],
        ":iType" => [$data["type"], PDO::PARAM_STR],
        ":iPri" => [$data["pri"], PDO::PARAM_STR],
        ":iAssign" => [$data["assign"], PDO::PARAM_INT],
        //":iCreator" => [$data["creator"], PDO::PARAM_INT]
        ":iCreator" => [1, PDO::PARAM_INT]
    ];

    $result = execQuery($query, $binds, PDO::FETCH_ASSOC);
    return json_encode($result);
} // End-createIssue

/**
 * @brief Updates the status of the issue
 *
 * @param array $filter An associative array of ise issue id and the new
 *                      status
 * @return JSON
 */
function updateIssueStatus($iStatus) {
    $data = filterDataArray($iStatus);
    $binds = [];
    $err = [];
    $issue = getIssues(["id" => $data["id"]]);

    if (count($issue) == 1) {
        $issue = $issue[0];

        if ($issue["status"] != "CLOSED") {
            // Generates the bind
            switch ($data["status"]) {
                case "in-progress":
                    if ($issue["status"] != "IN PROGRESS") {
                        $binds[":iStatus"] = ["IN PROGRESS", PDO::PARAM_STR];
                    }else{
                        $err["status"] = "status already set";
                    } // End-if
                    break;
                case "closed":
                    if ($issue["status"] != "CLOSED") {
                        $binds[":iStatus"] = ["CLOSED", PDO::PARAM_STR];
                    }else{
                        $err["status"] = "status already set";
                    } // End-if
                    break;
                default:
                    $err["status"] = "invalid status";
                    break;
            } // End-switch-case

            if (count($err) == 0) {
                $query = "UPDATE issues SET `status` = :iStatus WHERE `id` = :iID";
                $binds[":iID"] = [$data["id"], PDO::PARAM_INT];
                $result = execQuery($query, $binds, PDO::FETCH_ASSOC);
                return json_encode(["status" => "success"]);
            } // End-if
        } // End-if
        $err["status"] = "issue already closed";
    }else{
        $err["status"] = "ERROR";
    } // End-if
    return json_encode($err);
} // End-updateIssueStatus

/**
 * @brief Returns true if the credentials provided match the ones in the
 *        database
 *
 * @param array $uData An associative array of the users credentials
 * @return JSON
 */
function verifyUser($uData) {
    $data = filterDataArray($uData);

    $bugTrackerDB = connectToDB();
    $query = "SELECT `id`, `firstname`, `lastname` FROM users
        WHERE `email` = :uEmail and `password` = :uPasswd";

    $binds = [":uEmail" => [$data["email"], PDO::PARAM_STR],
        //":uPasswd" => [password_hash($data["passwd"], PASSWORD_DEFAULT), PDO::PARAM_STR]
        ":uPasswd" => [$data["passwd"], PDO::PARAM_STR]
    ];

    $result = execQuery($query, $binds, PDO::FETCH_ASSOC);

    if ($result != []) {
        $result["auth"] = true;
        return json_encode($result);
    }else{
        return json_encode(["auth" => false]);
    } // End-if
} // End-verifyUser
?>
