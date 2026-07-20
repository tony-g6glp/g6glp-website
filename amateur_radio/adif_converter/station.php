<form method="post" action="create.php">

<?= csrf_field() ?>

<input type="hidden"
       name="token"
       value="<?= htmlspecialchars($job['token']) ?>">


<label>
Callsign
</label>

<input type="text"
       name="callsign"
       required>


<label>
Operator
</label>

<input type="text"
       name="operator">


<label>
Power
</label>

<select name="power">

<option value="LOW">Low</option>
<option value="HIGH">High</option>
<option value="QRP">QRP</option>

</select>


<button type="submit">
Continue
</button>

</form>