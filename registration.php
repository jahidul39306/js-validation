<?php
    include "DBregistration.php";

    $fname = $lname = $gender = $dob = $religion = $specialist = $presAddress = $permAddress = $phone = $email = $website = $username = $password = "";
    $fnameErr = $lnameErr = $genderErr = $dobErr = $religionErr = $specialistErr = $phoneErr = $emailErr = $usernameErr = $passwordErr = "";
    $hasErr = false;
    $confirmPasswordErr = "";
    $successMsg = $failMsg = "";
    $namePattern = "/^[a-zA-Z-' ]*$/";
    $phonePattern = "/^0{1}[0-9]{10}|^(\+880){1}[0-9]{10}/";
    
    if($_SERVER["REQUEST_METHOD"] === "POST"){
       
        if(empty($_POST["fname"])){
           $fnameErr = "First name field is empty";
           $hasErr = true;
           $failMsg = "Registration Failed";
        }
        if(!preg_match($namePattern, $_POST["fname"]))
        {
            $fnameErr = "Only letters and white spaces";
            $hasErr = true;
            $failMsg = "Registration Failed";
        }
                   
        if(empty($_POST["lname"])){
            $lnameErr = "Last name field is empty";
            $hasErr = true;
            $failMsg = "Registration Failed";
        }
        if(!preg_match($namePattern, $_POST["lname"]))
        {
            $lnameErr = "Only letters and white spaces";
            $hasErr = true;
            $failMsg = "Registration Failed";
        }
        if(empty($_POST["gender"])){
            $genderErr = "Gender field is empty";
            $hasErr = true;
            $failMsg = "Registration Failed";
        }
        if(empty($_POST["dob"])){
            $dobErr = "Date of birth field is empty";
            $hasErr = true;
            $failMsg = "Registration Failed";
        }
        if(empty($_POST["religion"]) || ($_POST["religion"]) === "select"){
            $religionErr = "Religion field is empty";
            $hasErr = true;
            $failMsg = "Registration Failed";
        }

        if(empty($_POST["phone"])) {
            $phoneErr = "Phone field is empty";
            $hasErr = true;
            $failMsg = "Registration Failed";
        }
        
        if(!(preg_match($phonePattern, $_POST["phone"])) && !empty($_POST["phone"])) {
            $phoneErr = "Invalid phone number";
            $hasErr = true;
            $failMsg = "Registration Failed";
        }
        
        if(empty($_POST["email"])){
            $emailErr = "Email field is empty";
            $hasErr = true;
            $failMsg = "Registration Failed";
        }
        if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) && !empty($_POST["email"])){
            $emailErr = "Ivalid email format";
            $hasErr = true;
            $failMsg = "Registration Failed";
        }
        if(empty($_POST["username"])){
            $usernameErr = "User name field is empty";
            $hasErr = true;
            $failMsg = "Registration Failed";
        }

        if(strlen(test_input($_POST["username"])) < 6)
        {
            $usernameErr = "User name must be minimum 6 character";
            $hasErr = true;
            $failMsg = "Registration Failed";
        }


        if(!empty($_POST["username"]) && searchUsername($_POST["username"]))
        {
            $usernameErr = "User name already taken. Choose another username";
            $hasErr = true;
            $failMsg = "Registration Failed";
        }

        if(empty($_POST["password"])){
            $passwordErr = "Password field is empty";
            $hasErr = true;
            $failMsg = "Registration Failed";
        }

        if(!empty($_POST["password"]) && strlen(test_input($_POST["password"])) < 5)
        {
            $passwordErr = "Password need to be minimum 5 character";
            $hasErr = true;
            $failMsg = "Registration Failed";
        }

        if($_POST["confirmPassword"] !== $_POST["password"]){
            $confirmPasswordErr = "Password does not match";
            $hasErr = true;
            $failMsg = "Registration Failed";
        }
        if(!$hasErr){
            $fname = test_input($_POST["fname"]);
            $lname = test_input($_POST["lname"]);
            $gender = test_input($_POST["gender"]);
            $dob = test_input($_POST["dob"]);
            $religion = test_input($_POST["religion"]);
            $presAddress = test_input($_POST["presAddress"]);
            $permAddress = test_input($_POST["permAddress"]);
            $phone = test_input($_POST["phone"]);
            $email = test_input($_POST["email"]);
            $website = test_input($_POST["website"]);
            $username = test_input($_POST["username"]);
            $password = test_input($_POST["password"]); 

            if(register($fname, $lname, $gender, $dob, $religion, $presAddress, $permAddress, $phone, $email, $website, $username, $password))
            {
                $successMsg = "Registration Successful";
                echo "<p style = \"background-color:LightGreen\"> Information stored successfully. Redirecting to login page within 5 seconds...</p>";
                echo "<meta http-equiv='refresh' content='5; url=\"login.php\"' />";
            }
                  
        }
    }
    function test_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
</head>
<body>

    
    <form method = "POST" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" name = "registrationForm" onsubmit="return isValid()">
        <h1>Registration form</h1>
        
        <fieldset>
            <legend>Basic information:</legend>
            <br>
            <label for = "fname">First Name: </label>
            <input type = "text" id = "fname" name = "fname" value = "<?php echo $_POST["fname"] ?? '';?>">
            <span id = "fnameErr" class = "error" style = "color:red">&nbsp; *<?php echo $fnameErr?></span>
            <br><br>

            <label for = "lname">Last Name: </label>
            <input type = "text" id = "lname" name = "lname" value = "<?php echo $_POST["lname"] ?? '';?>">
            <span  id = "lnameErr" class = "error" style = "color:red">&nbsp; *<?php echo $lnameErr?></span>
            <br><br>
            
            <label for = "gender">Gender: </label>
            <input type = "radio" id = "male" name = "gender" value = "male" <?php if(isset($_POST["gender"]) && $_POST["gender"] == "male") echo "checked";?>>
            <label for = "male">Male</label>
            <input type = "radio" id = "female" name = "gender" value = "female" <?php if(isset($_POST["gender"]) && $_POST["gender"] == "female") echo "checked";?>>
            <label for = "female">Female</label>
            <input type = "radio" id = "others" name = "gender" value = "others" <?php if(isset($_POST["gender"]) && $_POST["gender"] == "others") echo "checked";?>>
            <label for = "others">Others</label>
            <span  id = "genderErr" class = "error" style = "color:red">&nbsp; *<?php echo $genderErr?></span>
            <br><br>

            <label for = "dob">Date of Birth: </label>
            <input type = "date" id = "dob" name = "dob" value = "<?php echo $_POST["dob"] ?? '';?>">
            <span  id = "dobErr" class = "error" style = "color:red">&nbsp; *<?php echo $dobErr?></span>
            <br><br>

            <label for = "religion">Religion: </label>
            <select name = "religion" id = "religion">
                <option value = "select" <?php if(isset($_POST["religion"]) && $_POST["religion"] == "select") echo "selected";?>>--Select--</option>
                <option value = "islam"  <?php if(isset($_POST["religion"]) && $_POST["religion"] == "islam") echo "selected";?>>Islam</option>
                <option value = "hindu"  <?php if(isset($_POST["religion"]) && $_POST["religion"] == "hindu") echo "selected";?>>Hindu</option>
                <option value = "christan" <?php if(isset($_POST["religion"]) && $_POST["religion"] == "christan") echo "selected";?>>Christan</option>
                <option value = "other" <?php if(isset($_POST["religion"]) && $_POST["religion"] == "other") echo "selected";?>>Other</option>
            </select>
            <span  id = "religionErr" class = "error" style = "color:red">&nbsp; *<?php echo $religionErr; ?></span>
            <br><br>

        </fieldset>

        <br>

        <fieldset>
            <legend>Contact information:</legend>
            <br>

            <label for="presAddress">Present Address:</label>
            <br>
            <textarea id="presAddress" name="presAddress" rows="5" cols="70"><?php if(isset($_POST["presAddress"])) echo $_POST["presAddress"]?></textarea>
            <br><br>

            <label for="permAdd">Permanent Address:</label>
            <br>
            <textarea id="permAddress" name="permAddress" rows="5" cols="70"><?php if(isset($_POST["permAddress"])) echo $_POST["permAddress"]?></textarea>
            <br><br>

            <label for="phone">Phone: </label>
            <input type="tel" id="phone" name="phone" value = "<?php echo $_POST["phone"] ?? '';?>">
            <span  id = "phoneErr" class = "error" style = "color:red">&nbsp; *<?php echo $phoneErr?></span>
            <br><br>

            <label for="email">Email: </label>
            <input type="email" id="email" name="email" value = "<?php echo $_POST["email"] ?? '';?>">
            <span  id = "emailErr" class = "error" style = "color:red">&nbsp; *<?php echo $emailErr; ?></span>
            <br><br>
            
            
            <label for="website">Personel Website Link: </label>
            <input type="url" id="website" name="website" value = "<?php echo $_POST["website"] ?? '';?>">
            <br><br>

        </fieldset>

        <br>

        <fieldset>
            <legend>Account Information: </legend>
            <br>

            <label for = "username">Username: </label>
            <input type = "text" id = "username" name = "username" value = "<?php echo $_POST["username"] ?? '';?>">
            <span  id = "usernameErr" class = "error" style = "color:red">&nbsp; *<?php echo $usernameErr?></span>
            <br><br>

            <label for = "password">Password: </label>
            <input type = "password" id = "password" name = "password">
            <span  id = "passwordErr" class = "error" style = "color:red">&nbsp; *<?php echo $passwordErr?></span>
            <br><br>

            <label for = "confirmPassword">Confirm password: </label>
            <input type = "password" id = "confirmPassword" name = "confirmPassword">
            <span  id = "confirmPasswordErr" class = "error" style = "color:red">&nbsp; *<?php echo $confirmPasswordErr?></span>
            <br><br>
        </fieldset>

        <br><br>
        <input type="submit" value="Submit">

        <p style = "color:green"><?php echo $successMsg; ?></p>
        <p style = "color:red"><?php echo $failMsg; ?></p>
        <p>Already a User? &nbsp;<a href = "login.php">LogIn</a>&nbsp;</p>
    </form>

    <script>
        function isValid()
        {
            vaild = true;
            var fname = document.forms["registrationForm"]["fname"].value;
            var lname = document.forms["registrationForm"]["lname"].value;
            var gender = document.forms["registrationForm"]["gender"].value;
            var dob = document.forms["registrationForm"]["dob"].value;
            var religion = document.forms["registrationForm"]["religion"].value;
            var phone = document.forms["registrationForm"]["phone"].value;
            var email = document.forms["registrationForm"]["email"].value;
            var username = document.forms["registrationForm"]["username"].value;
            var password = document.forms["registrationForm"]["password"].value;
            var confirmPassword = document.forms["registrationForm"]["confirmPassword"].value;

            if(fname === "")
            {
                valid = false;
                document.getElementById("fnameErr").innerHTML = "firstname field is empty jS";
            } 
            if(lname === "")
            {
                valid = false;
                document.getElementById("lnameErr").innerHTML = "lastname field is empty jS";
            } 
            if(gender === "")
            {
                valid = false;
                document.getElementById("genderErr").innerHTML = "gender field is empty jS";
            } 
            if(dob === "")
            {
                valid = false;
                document.getElementById("dobErr").innerHTML = "DOB field is empty jS";
            } 
            if(religion === "")
            {
                valid = false;
                document.getElementById("religionErr").innerHTML = "religion field is empty jS";
            } 
            if(phone === "")
            {
                valid = false;
                document.getElementById("phoneErr").innerHTML = "phone field is empty jS";
            } 
            if(email === "")
            {
                valid = false;
                document.getElementById("emailErr").innerHTML = "email field is empty jS";
            } 
            if(username === "")
            {
                valid = false;
                document.getElementById("usernameErr").innerHTML = "username field is empty jS";
            } 
            if(password === "")
            {
                valid = false;
                document.getElementById("passwordErr").innerHTML = "password field is empty jS";
            } 
            if(confirmPassword === "" || confirmPassword != password)
            {
                valid = false;
                document.getElementById("confirmPasswordErr").innerHTML = "password does not match";
            } 
            return valid;
        }

    </script>
      
</body>
</html>