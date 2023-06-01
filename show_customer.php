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
    <title>Sample PHP Database Program</title>
    <link rel="stylesheet" href="base.css">
</head>
<body>
<?php
require_once 'header.inc.php';
?>
<div>
    <h2>Show Customer</h2>
    <?php

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database, $port);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL using Parameterized Form (Safe from SQL Injections)
    $sql = "SELECT c.CustomerNumber, c.CustomerName, a.StreetAddress, a.CityName, a.StateCode, a.PostalCode, o.OrderNumber, o.OrderDate, oi.ItemNumber, ci.ItemDescription, oi.Quantity, oi.UnitPrice FROM customer c JOIN address a ON c.DefaultAddressID = a.AddressID JOIN ordermaster o ON c.CustomerNumber = o.CustomerNumber JOIN orderitem oi ON o.OrderNumber = oi.OrderNumber JOIN catalogitem ci ON oi.ItemNumber = ci.ItemNumber WHERE c.CustomerNumber = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "failed to prepare";
    } else {
        // Bind Parameters from User Input
        $stmt->bind_param('s', $id);

        // Execute the Statement
        $stmt->execute();

        // Process Results Using Cursor
        $stmt->bind_result($customerNumber, $customerName, $streetName, $cityName, $stateCode, $postalCode);
        echo "<div>";
        while ($stmt->fetch()) {
            echo '<a href="show_customer.php?id=' . htmlspecialchars($customerNumber) . '">' . htmlspecialchars($customerName) . '</a><br>' .
                htmlspecialchars($streetName) . ',' . htmlspecialchars($stateCode) . '  ' . htmlspecialchars($postalCode);
        }
        echo "</div>";
    ?>
        <div>
            <a href="update_customer.php?id=<?= htmlspecialchars($customerNumber) ?>">Update Customer</a>
        </div>
    <?php
    }

    $stmt->close();
    $conn->close();

    ?>
</div>
</body>
</html>
