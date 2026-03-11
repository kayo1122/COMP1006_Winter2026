<?php
//require database connection script 
require "includes/connect.php";  

/*1*/
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request');
}

/*2* sanitize data */
$firstName = trim(filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_SPECIAL_CHARS));
$lastName  = trim(filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_SPECIAL_CHARS));
$email     = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$phone     = trim(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS));
$address   = trim(filter_input(INPUT_POST, 'address', FILTER_SANITIZE_SPECIAL_CHARS));
$comments  = trim(filter_input(INPUT_POST, 'comments', FILTER_SANITIZE_SPECIAL_CHARS));

/*3*/

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

/*4*/

//build our query using named placeholders 

$sql = "INSERT INTO orders (first_name, last_name, phone, address, email, comments) VALUES (:first_name, :last_name, :phone, :address, :email, :comments)"; 

//prepare the query 

$stmt = $pdo->prepare($sql); 

//map the named placeholder to the user data/actual data 

$stmt->bindParam(':first_name', $firstName);
$stmt->bindParam(':last_name', $lastName); 
$stmt->bindParam(':phone', $phone); 
$stmt->bindParam(':email', $email); 
$stmt->bindParam(':address', $address); 
$stmt->bindParam(':comments', $comments); 

//execute the query 
$stmt->execute(); 

//close the connection 
$pdo = null; 
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
