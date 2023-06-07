<?php
/**
 * Created by PhpStorm.
 * User: MKochanski
 * Date: 7/24/2018
 * Time: 3:07 PM
 */
require_once 'config.inc.php';

// Get Customer Number
$id = $_GET['id'];
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
        $name = $_POST['jobName'];
        if ($name === null) {
            echo "<div><i>Specify a new name</i></div>";
        } else if ($name === false) {
            echo "<div><i>Specify a new name</i></div>";
        } else if (trim($name) === "") {
            echo "<div><i>Specify a new name</i></div>";
        } else {
            // Update the customer name
            $sql = "UPDATE Job SET jobName = ? WHERE jobID = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                echo "Failed to prepare";
            } else {
                // Bind user input to the statement
                $stmt->bind_param('ss', $jobName, $jobID);
                // Execute the statement
                $stmt->execute();
                // Check if the update was successful
                if ($stmt->affected_rows > 0) {
                    echo "Customer name updated successfully.";
                } else {
                    echo "Failed to update customer name.";
                }
            }
        }
    }

    // Retrieve the customer details
    $sql = "SELECT CustomerNumber, CustomerName, StreetAddress, CityName, StateCode, PostalCode FROM customer C " .
        "INNER JOIN address A ON C.defaultAddressID = A.addressID WHERE CustomerNumber = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Failed to prepare";
    } else {
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $stmt->bind_result($customerNumber, $customerName, $streetName, $cityName, $stateCode, $postalCode);
        ?>
        <form method="post">
            <input type="hidden" name="id" value="<?= $id ?>">
            <?php
            while ($stmt->fetch()) {
                echo '<a href="show_customer.php?id=' . $customerNumber . '">' . $customerName . '</a><br>' .
                    $streetName . ', ' . $cityName . ', ' . $stateCode . ' ' . $postalCode;
            }
            ?><br><br>
            New Name: <input type="text" name="customerName">
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
