<?php
/**
 * process.php
 * ------------------------------------------------------------
 * Handles the bakery order form submission:
 *  1) Checks the request method
 *  2) Sanitizes input
 *  3) Validates required fields + item quantities
 *  4) Inserts the order using a prepared statement (PDO)
 *  5) Displays either errors or a confirmation message
 */

// DB Connection 
require "includes/connect.php"; 
// --------------------------------------------------
// 1. Check form submission
// --------------------------------------------------
// Only allow this script to run when the form is submitted via POST.
// If someone visits process.php directly, we stop the script.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request');
}

// --------------------------------------------------
// 2. Sanitize input
// --------------------------------------------------
// trim() removes extra whitespace at the start/end of user input.
// filter_input() helps sanitize incoming form data.
$firstName = trim(filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_SPECIAL_CHARS));
$lastName  = trim(filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_SPECIAL_CHARS));
$email     = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$phone     = trim(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS));
$address   = trim(filter_input(INPUT_POST, 'address', FILTER_SANITIZE_SPECIAL_CHARS));
$comments  = trim(filter_input(INPUT_POST, 'comments', FILTER_SANITIZE_SPECIAL_CHARS));

//array of product items 
$items = $_POST['items'] ?? [];

// --------------------------------------------------
// 3. Server-side validation
// --------------------------------------------------
$errors = [];

// Required fields
if ($firstName === null || $firstName === '') {
    $errors[] = "First Name is required.";
}

if ($lastName === null || $lastName === '') {
    $errors[] = "Last Name is required.";
}

// Email: required + format check
if ($email === null || $email === '') {
    $errors[] = "Email is required.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Email must be a valid email address.";
}

// Phone: required + simple regex format check
if ($phone === null || $phone === '') {
    $errors[] = "Phone number is required.";
} elseif (!filter_var($phone, FILTER_VALIDATE_REGEXP, [
    'options' => ['regexp' => '/^[0-9\-\+\(\)\s]{7,25}$/']
])) {
    $errors[] = "Phone number format is invalid.";
}

// Address: required
if ($address === null || $address === '') {
    $errors[] = "Address is required.";
}

// Validate order quantities
// We only accept items with an integer quantity > 0.
$itemsOrdered = [];

foreach ($items as $item => $quantity) {
    // FILTER_VALIDATE_INT returns false if not a valid integer string
    if (filter_var($quantity, FILTER_VALIDATE_INT) !== false && $quantity > 0) {
        $itemsOrdered[$item] = $quantity;
    }
}

// Require at least one item to be ordered
if (count($itemsOrdered) === 0) {
    $errors[] = "Please order at least one item.";
}

// If there are errors, show them and stop the script before inserting to the DB
if (!empty($errors)) {
    require "includes/header.php"; 
    echo "<div class='alert alert-danger'>";
    echo "<h2>Please fix the following:</h2>";
    echo "<ul>";
    foreach ($errors as $error) {
        // htmlspecialchars() prevents any unexpected HTML from being rendered
        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    echo "</ul>";
    echo "</div>";

    require "includes/footer.php";
    exit;
}

// --------------------------------------------------
// 4. Set Up Query & Prepare
// --------------------------------------------------
// NOTE: We insert ALL item columns every time.
// If an item was not ordered, we store 0 for that column.

$sql = "INSERT INTO orders1 (first_name, last_name, phone, address, email, chaos_croissant, existential_eclair,procrastination_cookie, comments) VALUES (:first_name, :last_name, :phone, :address, :email, :chaos_croissant, :existential_eclair,:procrastination_cookie, :comments)"; 

$stmt = $pdo->prepare($sql); 

// --------------------------------------------------
// 5. Bind parameters
// --------------------------------------------------
/*
 * - bindParam() binds variables by reference (value is read at execute time)
 * - binding array elements directly with bindParam() can be unreliable
 *   because array offsets aren't always safe references.
 *
 * Solution:
 * - Assign values to variables first, then bind those variables.
 * - Use defaults (0) if an item wasn't included.
 */

// Build “clean” values for each DB column using defaults.
// We pull from $itemsOrdered so only validated quantities get used.
$chaosCroissant = $itemsOrdered['chaos_croissant'] ?? 0; 
$existentialEclair     = $itemsOrdered['existential_eclair'] ?? 0;
$procrastinationCookie = $itemsOrdered['procrastination_cookie'] ?? 0;

// bind parameters 
$stmt->bindParam(':first_name', $firstName);
$stmt->bindParam(':last_name', $lastName);
$stmt->bindParam(':phone', $phone);
$stmt->bindParam(':address', $address);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':comments', $comments);

// order items
// We bind as integers so the DB receives numeric values (0, 1, 2, ...).
$stmt->bindParam(':chaos_croissant', $chaosCroissant, PDO::PARAM_INT);
$stmt->bindParam(':existential_eclair', $existentialEclair, PDO::PARAM_INT);
$stmt->bindParam(':procrastination_cookie', $procrastinationCookie, PDO::PARAM_INT);


// --------------------------------------------------
// 6. Execute
// --------------------------------------------------
$stmt->execute();

// close the connection 

$pdo = null; 

// --------------------------------------------------
// 7. Confirmation output
// --------------------------------------------------

?>
<? require "includes/header.php"; ?>
<div class="alert alert-success">
    <h1>Thank you for your order, <?= htmlspecialchars($firstName) ?>!</h1>
    <p>
        We’ve received your order and will contact you at
        <strong><?= htmlspecialchars($email) ?></strong>.
    </p>
</div>

<?php require "includes/footer.php"; ?>