<?php
/**
 * Created by PhpStorm.
 * User: MKochanski
 * Date: 7/24/2018
 * Time: 3:07 PM
 */
require_once 'config.inc.php';
// Get Customer Number
$id = isset($_GET['id']) ? $_GET['id'] : '';
if ($id === "") {
    header('location: list_customers.php');
    exit();
}
if ($id === false) {
    header('location: list_customers.php');
    exit();
}
if ($id === null) {
    header('location: list_customers.php');
    exit();
}
?>
<html>
<head>
    <title>Group 2 Term Project</title>
    <link rel="stylesheet" href="base.css">
</head>
<body>
<?php
require_once 'header.inc.php';
?>
<div>
    <h2>Show Job</h2>
    <?php

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database, $port);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL using Parameterized Form (Safe from SQL Injections)
    $sql = "SELECT j.jobName, j.jobID, j.maxLevel, j.type
        FROM Job j 
        WHERE j.jobName = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "failed to prepare";
    } else {
        // Bind Parameters from User Input
        $stmt->bind_param('s', $id);

        // Execute the Statement
        $stmt->execute();

        // Process Results Using Cursor
        $stmt->bind_result($jobName, $jobID, $maxLevel, $type);

        echo "<div>";
        if ($stmt->fetch()) {
            echo htmlspecialchars($jobName) . "<br>";
            while ($stmt->fetch()) {
                // Additional rows, if any, can be processed here
            }
        }
        echo "</div>";
    ?>
        <div>
            <a href="update_customer.php?id=<?= htmlspecialchars($jobName) ?>">Update Job</a>
        </div>
    <?php
    }

    $stmt->close();
    $conn->close();

    ?>
</div>
</body>
</html>
