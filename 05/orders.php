<?php
require "includes/header.php";
require "includes/connect.php";

?>

<main class="container mt-4">
  <h2>Orders</h2>

  <?php if (count($orders) === 0): ?>
    <p>No orders yet.</p>
  <?php else: ?>
    <ul>

    </ul>
    
  <?php endif; ?>

  <p class="mt-3">
    <a href="index.php">Back to Order Form</a>
  </p>
</main>

<?php require "includes/footer.php"; ?>
