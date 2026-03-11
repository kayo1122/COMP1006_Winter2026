<?php
require "includes/header.php";
require "includes/connect.php"; // connect to db 

//create query 
$sql = "SELECT * FROM orders1 ORDER BY created_at DESC"; 

//prepare
$stmt = $pdo->prepare($sql);  

//execute 
$stmt->execute(); 

//retrieve all rows returned by a SQL query at once
$orders = $stmt->fetchAll(); 
?>

<main class="mt-4">
  <h2>Orders</h2>

  <?php if (count($orders) === 0): ?>
    <p>No orders yet.</p>
  <?php else: ?>
    <ul>
      <?php foreach ($orders as $order): ?>

        <?php
          // Calculate total items
          $total = $order['chaos_croissant'] + $order['existential_eclair'] + $order['procrastination_cookie']
        
        ?>

        <li class="mb-3">
          <strong>Order #<?php echo htmlspecialchars($order['customer_id']); ?></strong><br>

          <?php echo htmlspecialchars($order['first_name']); ?>
          <?php echo htmlspecialchars($order['last_name']); ?>
          (<?php echo htmlspecialchars($order['email']); ?>)<br>

          <strong>Items Ordered:</strong>
          <ul>
            <?php if ($order['chaos_croissant'] > 0): ?>
              <li>Chaos Croissant: <?php echo $order['chaos_croissant']; ?></li>
            <?php endif; ?>

            <?php if ($order['existential_eclair'] > 0): ?>
              <li>Existential Éclair: <?php echo $order['existential_eclair']; ?></li>
            <?php endif; ?>

            <?php if ($order['procrastination_cookie'] > 0): ?>
              <li>Procrastination Cookie: <?php echo $order['procrastination_cookie']; ?></li>
            <?php endif; ?>
          </ul>

          <strong>Total Items:</strong> <?php echo $total; ?>
        </li>
        <hr>

      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <p class="mt-3">
    <a href="index.php">Back to Order Form</a>
  </p>
</main>

<?php require "includes/footer.php"; ?>
