<?php include_once "dataMgmt.php"; ?>

<form id="new-issue-form" action="#">
<label id="i-title-label" for="i-title">Title</label><br>
<input type="text" class="i-title-input" id="i-title" name="issueTitle" placeholder=""/><br><br>

<label id="i-desc-label" for="i-desc">Description</label><br>
<input type="text" class="i-desc-input" id="i-desc" name="issueDes" placeholder=""/><br><br>

<label id="i-assign-label" for="i-assign">Assigned To</label><br>
<select id="i-assign" class="i-assign-input" placeholder="1">
<?php foreach (getUsers() as $a): ?>
    <option value=<?= $a["id"] ?>><?= $a["firstname"] ?> <?= $a["lastname"] ?></option>
<?php endforeach;?>
</select><br><br>

<label id="i-type-label" for="i-type">Type</label><br>
<select id="i-type" class="i-type-input">
<?php foreach (["Bug", "Proposal", "Task"] as $t): ?>
    <option value=<?= $t ?>><?= $t ?></option>
<?php endforeach;?>
</select><br><br>

<label id="i-priority-label" for="i-priority">Priority</label><br>
<select id="i-priority" class="i-priority-input">
<?php foreach (["Minor", "Major", "Critical"] as $p): ?>
    <option value=<?= $p ?>><?= $p ?></option>
<?php endforeach;?>
</select><br><br>

<input type="submit" class="new-issue-button" id="new-issue-button" value="Submit"/>
</form>
