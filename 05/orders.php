<?php
require "includes/header.php";
//connect to the database 


// select everything 


?>

<main class="container mt-4">
    <h2>Orders</h2>

    <?php if (count($orders) === 0): ?>
        <p>No orders yet.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($orders as $order): 
                //loop through and display items 
                // Calculate total items
                $total =
                    $order['chaos_croissant']
                    + $order['midnight_muffin']
                    + $order['existential_eclair']
                    + $order['procrastination_cookie']
                    + $order['finals_week_brownie']
                    + $order['victory_cinnamon_roll'];
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

                        <?php if ($order['midnight_muffin'] > 0): ?>
                            <li>Midnight Muffin: <?php echo $order['midnight_muffin']; ?></li>
                        <?php endif; ?>

                        <?php if ($order['existential_eclair'] > 0): ?>
                            <li>Existential Éclair: <?php echo $order['existential_eclair']; ?></li>
                        <?php endif; ?>

                        <?php if ($order['procrastination_cookie'] > 0): ?>
                            <li>Procrastination Cookie: <?php echo $order['procrastination_cookie']; ?></li>
                        <?php endif; ?>

                        <?php if ($order['finals_week_brownie'] > 0): ?>
                            <li>Finals Week Brownie: <?php echo $order['finals_week_brownie']; ?></li>
                        <?php endif; ?>

                        <?php if ($order['victory_cinnamon_roll'] > 0): ?>
                            <li>Victory Cinnamon Roll: <?php echo $order['victory_cinnamon_roll']; ?></li>
                        <?php endif; ?>
                    </ul>

                    <strong>Total Items:</strong> <?php echo $total; ?>
                </li>
                <hr>

            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <?php //close the db connection 
    $pdo = null
    ?>
    <p class="mt-3">
        <a href="index.php">Back to Order Form</a>
    </p>
</main>

<?php require "includes/footer.php"; ?>