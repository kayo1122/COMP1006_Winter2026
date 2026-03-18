<?php
require "includes/auth.php";
/**
 * delete.php
 * ------------------------------------------------------------
 * Deletes one order from the orders1 table.
 * - Gets the customer_id from the URL (delete.php?id=5)
 * - Uses PDO + bindParam for safety
 * - Redirects back to orders.php
 */

require "includes/connect.php";

// Make sure we received an ID
if (!isset($_GET['id'])) {
  die("No order ID provided.");
}

$customerId = $_GET['id'];

// Delete query (customer_id is the primary key)
$sql = "DELETE FROM orders1 WHERE customer_id = :customer_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':customer_id', $customerId);
$stmt->execute();

// Redirect back to admin list
header("Location: orders.php");
exit;
