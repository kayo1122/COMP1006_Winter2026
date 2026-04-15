<?php
// ============================================
// Dad Joke API Demo - Search Version
// Instructor File for COMP1006
// ============================================
// This page shows how to:
// 1. collect user input from a form
// 2. send that input to an API as part of the URL
// 3. decode JSON results returned by the API
// 4. loop through multiple jokes and display them

?>
 <!--
        This form sends the user's search word back to this same page.
        PHP then uses that word to build the API request URL.
    -->
    <form method="post">
        <label for="search_term">Enter a word:</label>
        <input
            type="text"
            name="search_term"
            id="search_term"
            value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button type="submit" name="search_jokes">Search</button>
    </form>

    <?php if ($message != ""): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <?php if (!empty($jokes)): ?>
        <h2>Results for "<?php echo htmlspecialchars($searchTerm); ?>"</h2>

        <ul>
            <?php foreach ($jokes as $joke): ?>
                <!--
                    Each item in the results array is itself an array.
                    The actual joke text is stored in the 'joke' field.
                -->
                <li><?php echo htmlspecialchars($joke['joke']); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</body>
</html>
