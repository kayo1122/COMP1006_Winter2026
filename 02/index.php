<?php 
//make PHP strict, needs to be at the start of your script 
declare(strict_types=1); 
require_once "connect.php";

//.1 Code Commenting

// inline comment 

/* 

multi-line comment 

*/

//2. Variables, Data Types, Concatenation & Conditional Statements

$firstName = "Jessica"; //string
$lastName = "Gilfillan"; //string 
$age = 40; //int 
$isInstructor = true; //boolean

echo "<p> Hello there, my name is ". $firstName . " ". $lastName ."</p>";

if($isInstructor) {
    echo "<p> I am your teacher. </p>"; 
}
else {
    echo "<p> Whoops, teach didn't show! </p>"; 
}

//3. PHP is loosely typed 
//create two variables, one called num1 and one called num2, in num1 store an integer and in num2 store a number but treat as string "10"

$num1 = 10; //integer
$num2 = "10"; //string 

//add type hints to make PHP less loosey goosey 
/*function add(int $num1, int $num2) : int {
    return $num1 + $num2; 
}

echo "<p>" . add($num1,$num2) . "</p>"; 

*/

// OOP with PHP {

class Person {
    public string $name;
    public int $age; 
    public bool $isInstructor; 
    
    public function __construct(string $name, int $age, bool $isInstructor) {
        $this->name = $name; 
        $this->age = $age; 
        $this->isInstructor = $isInstructor;
    }

    public function getBadge(): string {
        $role = $this->isInstructor ? "Instructor" : "Student"; 
        return "Name : {$this->name} | Age: {$this->age} | Role : $role";
    }
}

//create an instance of the object 

$person = new Person("Jessica", 40, true); 

echo $person->getBadge(); 









