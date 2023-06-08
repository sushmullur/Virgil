<?php
/**
 * Created by PhpStorm.
 * User: MKochanski
 * Date: 7/24/2018
 * Time: 3:07 PM
 */
require_once 'config.inc.php';
// Get Job Name
$jobName = isset($_GET['jobName']) ? $_GET['jobName'] : '';
if ($jobName === "") {
    header('location: list_customers.php');
    exit();
}
if ($jobName === false) {
    header('location: list_customers.php');
    exit();
}
if ($jobName === null) {
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
    $sql = "SELECT j.jobName, j.jobID, j.maxLevel, j.type, i.itemName
        FROM Job j
        JOIN JobItem ji ON j.jobID = ji.jobID
        JOIN Item i ON ji.itemID = i.itemID
        WHERE j.jobName = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Failed to prepare";
    } else {
        // Bind Parameters from User Input
        $stmt->bind_param('s', $jobName);

        // Execute the Statement
        $stmt->execute();

        // Process Results Using Cursor
        $stmt->bind_result($jobName, $jobID, $maxLevel, $type, $itemName);

        echo "<div>";
        if ($stmt->fetch()) {
            echo "Job Name: " . htmlspecialchars($jobName) . "<br>";
            echo "Job ID: " . htmlspecialchars($jobID) . "<br>";
            echo "Max Level: " . htmlspecialchars($maxLevel) . "<br>";
            echo "Type: " . htmlspecialchars($type) . "<br>";
            echo "Item Name: " . htmlspecialchars($itemName) . "<br>";
            while ($stmt->fetch()) {
                // Additional rows, if any, can be processed here
                echo "Item Name: " . htmlspecialchars($itemName) . "<br>";
            }
        }
        echo "</div>";
    ?>
        <div>
            <a href="update_job.php?jobName=<?= htmlspecialchars($jobName) ?>">Update Job</a>
        </div>
    <?php
    }

    $stmt->close();
    $conn->close();

    ?>
</div>
</body>
</html>
