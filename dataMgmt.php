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
 * @return Mixed
 */
function execQuery(string $query, array $binds = [], int $mode) {
    $bugTrackerDB = connectToDB();

    $stmt = $bugTrackerDB->prepare($query);

    foreach ($binds as $k => $v) {
        $stmt->bindParam($k, $v);
    } // End-foreach

    $stmt->execute();
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
function getUsers() {
    $query = "SELECT `id`, `firstname`, `lastname` FROM users";
    return execQuery($query, [], PDO::FETCH_ASSOC);
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

    $query = "SELECT i.`id`, `title`, `type`, `status`, `firstname`,
            `lastname`, `created`
        FROM
            (`issues` as i JOIN `users` as a
                ON i.`assigned_to`=a.`id`){$clauses};";

    return execQuery($query, [], PDO::FETCH_ASSOC);
} // End-getIssues

/**
 * @brief Adds a new user to the database
 *
 * @param array $uData An associative array of the data to create the issue
 * @return bool
 */
function createUser($uData) {
    $data = filterDataArray($uData);

    $query = "SELECT `email` FROM users WHERE `email` = :uEmail";
    $binds = [":uEmail" => $data["email"]];

    $result = execQuery($query, $binds, PDO::FETCH_ASSOC);

    if (count($result) == 0) {
        $query = "INSERT INTO users (`firstname`, `lastname`, `password`,
                `email`)
            VALUES (:uFname, :uLname, :uPasswd, :uEmail)";

        $binds = [":uFname" => $data["fname"],
            ":uLname" => $data["lname"],
            ":uPasswd" => password_hash($data["passwd"]),
            ":uEmail" => $data["email"]
        ];

        $result = execQuery($query, $binds, PDO::FETCH_ASSOC);
        return true;
    }else{
        return false;
    } // End-if
} // End-createUser

/**
 * @brief Adds a new issue to the database
 *
 * @param array $iData An associative array of the data to create the issue
 */
function createIssue($iData) {
    $data = filterDataArray($iData);

    $query = "INSERT INTO issues (`title`, `description`, `type`, `priority`,
            `assigned_to`, `created_by`)
        VALUES (:iTitle, :iDesc, :iType, :iPri, :iAssign, :iCreator)";

    $binds = [":iTitle" => $data["title"],
        ":iDesc" => $data["desc"],
        ":iType" => $data["type"],
        ":iPri" => $data["pri"],
        ":iAssign" => $data["assign"],
        ":iCreator" => $data["creator"]
    ];

    $result = execQuery($query, $binds, PDO::FETCH_ASSOC);
} // End-createIssue

/**
 * @brief Returns true if the credentials provided match the ones in the
 *        database
 *
 * @param array $uData An associative array of the data to create the issue
 */
function verifyUser($uEmail, $uPasswd) {
    $email = filterData($uEmail);

    $bugTrackerDB = connectToDB();
    $query = "SELECT `id`, `firstname`, `lastname` FROM users
        WHERE `email` = :uEmail and `password` = :uPasswd FROM users";

    $binds = [":uEmail" => $data["email"],
        ":uPasswd" => password_hash($data["passwd"])
    ];

    $result = execQuery($query, $binds, PDO::FETCH_ASSOC);

    if ($result != []) {
        return json_encode($result);
    }else{
        return json_encode(["auth" => false]);
    } // End-if
} // End-verifyUser

?>
