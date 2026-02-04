<?php 

/* 

Lab One Instructions: 

1.) Clone or download the Lab One starter files from the course GitHub repository.
2.) Create a PHP file called car.php. In this file, create a class to represent a car. Properties should include make, model and year and include a method to return this information. Use include/require to include this code in index.php. Add comments to explain your code. (/5 marks)
3.) Instantiate a new instance of a car object and echo the car information in the browser (/2 marks)
4.) Using your local server (XAMPP, MAMP, WAMP), create a database. Create a file called connect.php and use PDO to connect to your database. Remember to include try/catch blocks to handle connection errors. Use include/require to include this code in index.php. Add comments to explain your code (/5 marks)
6.) Add a multi-line comment in index.php reflecting on which parts of the lab you found easy and which parts were challening. (/2 marks)
7.) Congrats! All done! Add me as a collaborator to your repository (username: JessicaGilfilan) and submit a github link to your completed lab on Blackboard. (/2 marks)

/16 marks 

*/ 

require "header.php";
require "connect.php";  
echo "<p> Follow the instructions outlined in instructions.txt to complete this lab. Good luck & have fun!😀 </p>";
include "car.php"; 
require "footer.php"; 