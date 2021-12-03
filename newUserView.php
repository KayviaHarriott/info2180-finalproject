<form id="new-user-form" action="#">

<!-- FIRST NAME -->
<label id="f-name-label" for="f-name">Firstname</label>
<br>
<input type="text" class="f-name-input" id="f-name" name="f-name"  placeholder=""/>
<br>
<?php    
    $input1 = $_POST["f-name"];

    $uppercase = preg_match('#[A-Z]#', $input1);           //check for uppercase letters
    $lowercase = preg_match('#[a-z]#', $input1);            //check for lowecase letters
    $number    = preg_match('#[0-9]#', $input1);           //check for numbers
    $specialChars = preg_match('#[^\w]#', $input1);     //check for special characters
    
    // Validate first name -> should only contain letters regardless of the case
    if(!$uppercase || !$lowercase) {
        if ($number || $specialChars || strlen($input1) < 1){
        ?><p style="color:red; font-weight:bold">*Invalid First Name</p>
        <br>
        <?php
        }
    }
?>
<br>

<!-- LAST NAME -->
<label id="l-name-label" for="l-name">Lastname</label>
<br>
<input type="text" class="l-name-input" id="l-name" name="l-name" placeholder=""/>
<br>
<?php    
    $input4 = $_POST["l-name"];

    $uppercase = preg_match('#[A-Z]#', $input2);           //check for uppercase letters
    $lowercase = preg_match('#[a-z]#', $input2);            //check for lowecase letters
    $number    = preg_match('#[0-9]#', $input2);           //check for numbers
    $specialChars = preg_match('#[^\w]#', $input2);     //check for special characters

    // Validate last name -> should only contain letters regardless of the case
    if(!$uppercase || !$lowercase) {
        if ($number || $specialChars || strlen($input2) < 1){
        ?><p style="color:red; font-weight:bold">*Invalid Last Name</p>
        <br>
        <?php
        }
    }
?>
<br>

<!-- PASSWORD -->
<label id="p-word-label" for="p-word">Password</label>
<br>
<input type="text" class="p-word-input" id="p-word" name="p-word"  placeholder=""/>
<br>
<?php    
    $input3 = $_POST["p-word"];
    
    $uppercase = preg_match('#[A-Z]#', $input3);           //check for uppercase letters
    $lowercase = preg_match('#[a-z]#', $input3);            //check for lowecase letters
    $number    = preg_match('#[0-9]#', $input3);           //check for numbers
    $specialChars = preg_match('#[^\w]#', $input3);     //check for special characters

    // Validate password -> Must have at least 1 number, 1 capital letter and  8 characters
    if ((strlen($input4) >= 8) || !$uppercase || !$lowercase || !$number || $specialChars) {
        ?><p style="color:red; font-weight:bold">*Password MUST have at least 1 number, 1 capital letter and 8 characters but no special characters.</p>
        <br>
        <?php
    }
?>
<br> 

<!-- EMAIL -->
<label id="email-label" for="email">Email</label>
<br>
<input type="text" class="email-input" id="email" name="email" placeholder=""/>
<br>
<?php    
    $input4 = $_POST["email"];

    // Validate email
    if (!filter_var($input4, FILTER_VALIDATE_EMAIL)) {
        ?><p style="color:red; font-weight:bold">*Invalid Email</p>
        <br>
        <?php
    }
?>
<br> 
<input type="submit"  class="new-user-button" id="new-user-button" name="new-user-button" value="Submit"/>
</form>

<?php
$input1 = $_POST["f-name"];
$input2 = $_POST["l-name"];
$input4 = $_POST["p-word"];

//santise inputs
function sanitise($data) {
    
    return $str;
  }



$email = filter_var($input3, FILTER_VALIDATE_EMAIL);

?>