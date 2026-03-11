<?php require "includes/header.php" ?>
<main>
  <h2 class="mb-4"> Order Online - Easy & Simple (And Totally Secure...) 🧁</h2>
  <form action="process.php" method="post">

    <!-- Customer Information -->
    <!-- Step One - Add Client Side Validation with HTML Attributes -->
    <fieldset>
      <legend>Customer Information</legend>
      <label for="first_name" class="form-label">First name</label>
      <input type="text" id="first_name" name="first_name" class="form-control">
      <label for="last_name" class="form-label">Last name</label>
      <input type="text" id="last_name" name="last_name" class="form-control">
      <label for="phone" class="form-label">Phone number</label>
      <input type="tel" id="phone" name="phone" placeholder="555-123-4567" class="form-control">
      <label for="address" class="form-label">Address</label>
      <input type="text" id="address" name="address" class="form-control">
      <label for="email" class="form-label">Email</label>
      <input type="text" id="email" name="email">
    </fieldset>

    <fieldset>
      <legend>Additional Comments</legend>

      <label for="comments">Comments (optional)</label><br>
      <textarea id="comments" name="comments" rows="4"
        placeholder="Allergies, delivery instructions, custom messages..."></textarea>
    </fieldset>

    <button type="submit" class="btn btn-primary">Place Order</button>

  </form>
</main>
</body>

</html>

<?php require "includes/footer.php" ?>