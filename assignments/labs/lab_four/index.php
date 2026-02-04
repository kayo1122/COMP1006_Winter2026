<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COMP1006 - Lab Four</title>
</head>

<body>

    <main class="container mt-4">
        <h1>Subscribe to Our Mailing List</h1>

        <form action="process.php" method="post" class="mt-3">
            <label class="form-label" for="first_name">First Name</label>
            <input class="form-control" type="text" id="first_name" name="first_name">

            <label class="form-label mt-3" for="last_name">Last Name</label>
            <input class="form-control" type="text" id="last_name" name="last_name">

            <label class="form-label mt-3" for="email">Email Address</label>
            <input class="form-control" type="email" id="email" name="email">

            <button class="btn btn-primary mt-4" type="submit">Subscribe</button>
        </form>

        <p class="mt-4">
            <a href="subscribers.php">View Subscribers</a>
        </p>
    </main>
</body>

</html>