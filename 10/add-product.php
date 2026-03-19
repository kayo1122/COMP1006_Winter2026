<?php
// Make sure the user is logged in before they can access this page
require "includes/auth.php";

// Connect to the database
require "includes/connect.php";

// Show the admin-style header/navigation
require "includes/header_admin.php";

// Array for validation errors
$errors = [];

// Success message
$success = "";

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get and sanitize form values
    $name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS));
    $description = trim(filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS));
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);

    // This will store the image path for the database
    $imagePath = null;

    // Validate product name
    if ($name === '') {
        $errors[] = "Product name is required.";
    }

    // Validate description
    if ($description === '') {
        $errors[] = "Product description is required.";
    }

    // Validate price
    if ($price === false || $price < 0) {
        $errors[] = "Price must be a valid number.";
    }

    //Add Code Here 

    // If there are no errors, insert the product into the database
    if (empty($errors)) {
        $sql = "INSERT INTO products (name, description, price, image_path)
                VALUES (:name, :description, :price, :image_path)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':image_path', $imagePath);
        $stmt->execute();

        $success = "Product added successfully!";
    }
}
?>

<main class="container mt-4">
    <h1>Add Product</h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <h3>Please fix the following:</h3>
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success !== ""): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>
    <!--enctype="multipart/form-data" required for uploads, will not send properly if not included -->
    <form method="post" enctype="multipart/form-data" class="mt-3">
        <label for="name" class="form-label">Product Name</label>
        <input
            type="text"
            id="name"
            name="name"
            class="form-control mb-3"
            required
        >

        <label for="description" class="form-label">Description</label>
        <textarea
            id="description"
            name="description"
            class="form-control mb-3"
            rows="4"
            required
        ></textarea>

        <label for="price" class="form-label">Price</label>
        <input
            type="number"
            id="price"
            name="price"
            class="form-control mb-3"
            step="0.01"
            min="0"
            required
        >

        <label for="product_image" class="form-label">Product Image</label>
        <input
            type="file"
            id="product_image"
            name="product_image"
            class="form-control mb-4"
            accept=".jpg,.jpeg,.png,.webp"
        >

        <button type="submit" class="btn btn-primary">Add Product</button>
    </form>
</main>

<?php require "footer.php"; ?>