<?php 
require "header.php"; 
require "car.php";
echo "<p> Follow the instructions outlined in instructions.txt to complete this lab. Good luck & have fun!😀 </p>";
require "footer.php"; 
$myCar = new Car("Honda", "Civic", 2005);
echo $myCar->getCarInfo();
?>
/* 
I found making the connect.php file and using PDO to connect to the database the most challenging. I definitley had to look up some resources online to understand how to set it up properly. I'm definitely going to need to practice more with PDO to get comfortable using it in future projects.
I found creating the car.php class file the easiest part of the lab. The structure of classes and methods is pretty straightforward i felt.
*/ 
 