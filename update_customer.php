<?php
/**
 * Created by PhpStorm.
 * User: MKochanski
 * Date: 7/24/2018
 * Time: 3:07 PM
 */
require_once 'config.inc.php';

// Get Job ID
$jobID = $_GET['jobID'];
if ($jobID === "") {
    header('location: list_customers.php');
    exit();
}
if ($jobID === false) {
    header('location: list_customers.php');
    exit();
}
if ($jobID === null) {
    header('location: list_customers.php');
    exit();
}
?>
<html>
<head>
    <title>Sample PHP Database Program</title>
    <link rel="stylesheet" href="base.css">
</head>
<body>
<?php
require_once 'header.inc.php';
?>
<div>
    <h2>Update Job</h2>
    <?php

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database, $port);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $jobName = $_POST['jobName'];
        $maxLevel = $_POST['maxLevel'];
        $type = $_POST['type'];

        // Update the job details
        $sql = "UPDATE Job SET jobName = ?, maxLevel = ?, type = ? WHERE jobID = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo "Failed to prepare";
        } else {
            // Bind user input to the statement
            $stmt->bind_param('ssss', $jobName, $maxLevel, $type, $jobID);
            // Execute the statement
            $stmt->execute();
            // Check if the update was successful
            if ($stmt->affected_rows > 0) {
                echo "Job details updated successfully.";
            } else {
                echo "Failed to update job details.";
            }
        }
    }

    // Retrieve the job details
    $sql = "SELECT j.jobName, j.jobID, j.maxLevel, j.type, i.itemName
            FROM Job j
            JOIN JobItem ji ON j.jobID = ji.jobID
            JOIN Item i ON ji.itemID = i.itemID
            WHERE j.jobID = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Failed to prepare";
    } else {
        $stmt->bind_param('s', $jobID);
        $stmt->execute();
        $stmt->bind_result($jobName, $jobID, $maxLevel, $type, $itemName);
        ?>
        <form method="post">
            <input type="hidden" name="jobID" value="<?= $jobID ?>">
            <div>
                <label for="jobName">Job Name:</label>
                <input type="text" name="jobName" value="<?= htmlspecialchars($jobName) ?>">
            </div>
            <div>
                <label for="maxLevel">Max Level:</label>
                <input type="text" name="maxLevel" value="<?= htmlspecialchars($maxLevel) ?>">
            </div>
            <div>
                <label for="type">Type:</label>
                <input type="text" name="type" value="<?= htmlspecialchars($type) ?>">
            </div>
            <div>
                <label for="itemName">Item Name:</label>
                <input type="text" name="itemName" value="<?= htmlspecialchars($itemName) ?>">
            </div>
            <button type="submit">Update</button>
        </form>
        <?php
    }

    $stmt->close();
    $conn->close();

    ?>
</div>
</body>
</html>
