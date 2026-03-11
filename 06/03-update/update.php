<?php
/**
 * update.php
 * ------------------------------------------------------------
 * Admin update page for orders.
 * - Uses GET ?id= to know which order to edit
 * - Loads that order and echoes values into the form
 * - On POST, updates the row using PDO + bindParam
 */

require "includes/header.php";
require "includes/connect.php";

/* -------------------------------------------
   STEP 1: Make sure we received an ID in the URL
   Example: update.php?id=5
-------------------------------------------- */
if (!isset($_GET['id'])) {
  die("No order ID provided.");
}

$customerId = $_GET['id'];

/* -------------------------------------------
   STEP 2: If form is submitted, UPDATE the row
-------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Basic sanitization (trim removes extra spaces)
  $firstName = trim($_POST['first_name'] ?? '');
  $lastName  = trim($_POST['last_name'] ?? '');
  $phone     = trim($_POST['phone'] ?? '');
  $address   = trim($_POST['address'] ?? '');
  $email     = trim($_POST['email'] ?? '');

  // Product quantities (force to integer, prevent blanks)
  $chaosCroissant       = (int)($_POST['chaos_croissant'] ?? 0);
  $existentialEclair    = (int)($_POST['existential_eclair'] ?? 0);
  $procrastinationCookie = (int)($_POST['procrastination_cookie'] ?? 0);

  // Simple validation (beginner-friendly)
  if ($firstName === '' || $lastName === '' || $email === '') {
    $error = "First name, last name, and email are required.";
  } else {

    $sql = "UPDATE orders1
            SET first_name = :first_name,
                last_name = :last_name,
                phone = :phone,
                address = :address,
                email = :email,
                chaos_croissant = :chaos_croissant,
                existential_eclair = :existential_eclair,
                procrastination_cookie = :procrastination_cookie
            WHERE customer_id = :customer_id";

    $stmt = $pdo->prepare($sql);

    // Bind parameters (safe + beginner friendly)
    $stmt->bindParam(':first_name', $firstName);
    $stmt->bindParam(':last_name', $lastName);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':email', $email);

    $stmt->bindParam(':chaos_croissant', $chaosCroissant);
    $stmt->bindParam(':existential_eclair', $existentialEclair);
    $stmt->bindParam(':procrastination_cookie', $procrastinationCookie);

    $stmt->bindParam(':customer_id', $customerId);

    $stmt->execute();

    // Redirect back to the orders list (prevents resubmission on refresh)
    header("Location: orders.php");
    exit;
  }
}

/* -------------------------------------------
   STEP 3: Load existing order data (to echo in the form)
-------------------------------------------- */
$sql = "SELECT * FROM orders1 WHERE customer_id = :customer_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':customer_id', $customerId);
$stmt->execute();

$order = $stmt->fetch();

if (!$order) {
  die("Order not found.");
}
?>

<main class="container mt-4">
  <h2>Update Order #<?= htmlspecialchars($order['customer_id']); ?></h2>

  <?php if (!empty($error)): ?>
    <p class="text-danger"><?= htmlspecialchars($error); ?></p>
  <?php endif; ?>

  <!--
    This form is pre-filled using the order data pulled from the database.
    The admin can edit the values and submit to update the row.
  -->
  <form method="post">

    <h4 class="mt-3">Customer Info</h4>

    <label class="form-label">First Name</label>
    <input
      type="text"
      name="first_name"
      class="form-control mb-3"
      value="<?= htmlspecialchars($order['first_name']); ?>"
      required
    >

    <label class="form-label">Last Name</label>
    <input
      type="text"
      name="last_name"
      class="form-control mb-3"
      value="<?= htmlspecialchars($order['last_name']); ?>"
      required
    >

    <label class="form-label">Phone</label>
    <input
      type="text"
      name="phone"
      class="form-control mb-3"
      value="<?= htmlspecialchars($order['phone']); ?>"
    >

    <label class="form-label">Address</label>
    <input
      type="text"
      name="address"
      class="form-control mb-3"
      value="<?= htmlspecialchars($order['address']); ?>"
    >

    <label class="form-label">Email</label>
    <input
      type="email"
      name="email"
      class="form-control mb-4"
      value="<?= htmlspecialchars($order['email']); ?>"
      required
    >

    <h4 class="mt-3">Products</h4>

    <label class="form-label">Chaos Croissant</label>
    <input
      type="number"
      name="chaos_croissant"
      class="form-control mb-3"
      min="0"
      value="<?= (int)$order['chaos_croissant']; ?>"
    >

    <label class="form-label">Existential Éclair</label>
    <input
      type="number"
      name="existential_eclair"
      class="form-control mb-3"
      min="0"
      value="<?= (int)$order['existential_eclair']; ?>"
    >

    <label class="form-label">Procrastination Cookie</label>
    <input
      type="number"
      name="procrastination_cookie"
      class="form-control mb-4"
      min="0"
      value="<?= (int)$order['procrastination_cookie']; ?>"
    >

    <button class="btn btn-primary">Save Changes</button>
    <a href="orders.php" class="btn btn-secondary">Cancel</a>

  </form>
</main>

<?php require "includes/footer.php"; ?>
