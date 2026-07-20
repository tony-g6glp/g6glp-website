<?php

require_once __DIR__ . '/../../include/bootstrap.php';

verify_csrf();

/*
if (!can('use_adif_converter')) {

    http_response_code(403);
    die('Access denied');

}
*/

$token = $_POST['token'] ?? '';
$contest = $_POST['contest'] ?? '';


if (!$token || !$contest) {

    die('Missing information');

}

if ($contest !== 'rsgb-data') {

    die('Invalid contest');
}

require_once __DIR__ . '/classes/Job.php';

try {

    $job = Job::load($pdo, $token);

} catch (Exception $e) {

    die($e->getMessage());

}

$job->saveContest($pdo, $contest);

?>
<!DOCTYPE html>
<html>
<head>

<title>Cabrillo Details</title>

<link rel="stylesheet" href="/g6glp/include/css.css">

</head>

<body>


<?php include __DIR__ . '/../../include/header.php'; ?>
<?php include __DIR__ . '/../../include/public-nav.php'; ?>


<div class="container">

<h1>Cabrillo Details</h1>


<p>
Contest selected:
<strong>
<?= htmlspecialchars($contest) ?>
</strong>
</p>


<form method="post" action="create.php">


<input type="hidden" 
       name="token"
       value="<?= htmlspecialchars($token) ?>">

<?= csrf_field() ?>
<label>
Callsign
</label>

<br>

<input type="text"
       name="callsign"
       required>


<br><br>


<label>
Operator
</label>

<br>

<input type="text"
       name="operator">


<br><br>


<label>
Power
</label>

<br>

<select name="power">

<option value="LOW">
Low
</option>

<option value="HIGH">
High
</option>

<option value="QRP">
QRP
</option>

</select>


<br><br>


<button type="submit">
Generate Cabrillo
</button>


</form>


</div>


<?php include __DIR__ . '/../../include/footer.php'; ?>


</body>
</html>