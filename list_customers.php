<?php
/**
 * Created by PhpStorm.
 * User: MKochanski
 * Date: 7/24/2018
 * Time: 3:07 PM
 */
require_once 'config.inc.php';

// Get the filter parameter (if provided)
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

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
    <h2>Job List</h2>
    <form method="GET" action="">
        <label for="filter">Filter by Job Name:</label>
        <input type="text" id="filter" name="filter" value="<?php echo htmlspecialchars($filter); ?>">
        <button type="submit">Apply Filter</button>
    </form>
    <?php
    // Create connection
    $conn = new mysqli($servername, $username, $password, $database, $port);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL Statement
    $sql = "SELECT `jobName`, `jobID`, `type` FROM `Job`";
    
    // Add filter condition if provided
    if (!empty($filter)) {
        $filter = '%' . $conn->real_escape_string($filter) . '%';
        $sql .= " WHERE `jobName` LIKE ?";
    }

    $sql .= " ORDER BY `jobName`"; // Sort by customername column

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Failed to prepare";
    } else {
        // Bind parameters if filter is provided
        if (!empty($filter)) {
            $stmt->bind_param('s', $filter);
        }

        // Execute the Statement
        $stmt->execute();

        // Bind the result
        $stmt->bind_result($jobName, $jobID, $type);

        // Loop Through Result
        echo "<ul>";
        $recordsFound = false; // Flag to track if any records are found
        while ($stmt->fetch()) {
            $recordsFound = true;
            echo '<li><a href="show_customer.php?jobID=' . htmlspecialchars($jobID) . '">' . htmlspecialchars($jobName) . '</a></li>';
        }
        echo "</ul>";

        // Display message if no records are found
        if (!$recordsFound) {
            echo "No records found.";
        }
    }

    // Close Connection
    $stmt->close();
    $conn->close();
    ?>
</div>
</body>
</html>
