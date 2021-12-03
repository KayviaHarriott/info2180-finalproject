<form id="new-issue-form" action="#">
<label id="i-title-label" for="i-title">Title</label><br>
<input type="text" class="i-title-input" id="i-title" name="issueTitle" placeholder=""/><br>

<?php    
    $input1 = $_POST["issueTitle"];

    $uppercase = preg_match('#[A-Z]#', $input1);           //check for uppercase letters
    $lowercase = preg_match('#[a-z]#', $input1);            //check for lowecase letters
    $number    = preg_match('#[0-9]#', $input1);           //check for numbers
    $specialChars = preg_match('#[^\w]#', $input1);     //check for special characters
    
    // Validate first name -> should only contain letters regardless of the case

    if(!$uppercase || !$lowercase) {
        if ($number || $specialChars || strlen($input1) < 1){
        ?><p style="color:red; font-weight:bold">*Title should not have numbers or special characters. </p>
        <br>
        <?php
        }
    }
?>
<br>

<label id="i-desc-label" for="i-desc">Description</label><br>
<input type="text" class="i-desc-input" id="i-desc" name="issueDes" placeholder=""/><br>
<?php    
    $input2 = $_POST["issueDes"];

    $uppercase = preg_match('#[A-Z]#', $input2);           //check for uppercase letters
    $lowercase = preg_match('#[a-z]#', $input2);            //check for lowecase letters
    $number    = preg_match('#[0-9]#', $input2);           //check for numbers
    $specialChars = preg_match('#[^\w]#', $input2);     //check for special characters
    
    // Validate first name -> should only contain letters regardless of the case
    if(!$uppercase || !$lowercase || $number) {
        if ($specialChars || strlen($input2) < 1){
        ?><p style="color:red; font-weight:bold">*Invalid Description</p>
        <br>
        <?php
        }
    }
?>
<br>

<label id="i-assign-label" for="i-assign">Assigned To</label><br>
<select id="i-assign" class="i-assign-input" placeholder="1">
<?php foreach ([1, 2, 3, 4] as $i): ?>
    <option value=<?= $i ?>></option>
<?php endforeach;?>
</select><br><br>

<label id="i-type-label" for="i-type">Type</label><br>
<select id="i-type" class="i-type-input">
<?php foreach ([1, 2, 3, 4] as $i): ?>
    <option value=<?= $i ?>></option>
<?php endforeach;?>
</select><br><br>

<label id="i-priority-label" for="i-priority">Priority</label><br>
<select id="i-priority" class="i-priority-input">
<?php foreach ([1, 2, 3, 4] as $i): ?>
    <option value=<?= $i ?>></option>
<?php endforeach;?>
</select><br><br>

<input type="submit"  class="new-submit-issue-button" id="new-submit-issue-button" value="Submit"/>
</form>
