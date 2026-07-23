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
// $contest_id = $_POST['contest'] ?? '';
$contest_id = trim($_POST['contest'] ?? '');
if (!$token || !$contest_id) {

    die('Missing information');

}

require_once __DIR__ . '/classes/Contest/ContestFactory.php';

$contestObject = ContestFactory::create(
    $pdo,
    $contest_id
);

$stationFields = $contestObject->getStationFields();


require_once __DIR__ . '/classes/Job.php';

try {

    $job = Job::load($pdo, $token);

} catch (Exception $e) {

    die($e->getMessage());

}

$job->saveContest($pdo, $contest_id);


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
<?= htmlspecialchars($contest_id) ?>
</strong>
</p>


<form method="post" action="create.php">


<input type="hidden" 
       name="token"
       value="<?= htmlspecialchars($token) ?>">
<input type="hidden"
       name="contest"
       value="<?= htmlspecialchars($contest) ?>">
<?= csrf_field() ?>
<br><br>

<?php foreach ($stationFields as $field => $definition): ?>

<label>
<?= htmlspecialchars(strtoupper(str_replace('_', ' ', $field))) ?>
</label>

<br>

<?php if ($definition['type'] === 'select'): ?>

<select name="<?= htmlspecialchars($field) ?>">

<?php foreach ($definition['options'] as $option): ?>

<option value="<?= htmlspecialchars($option) ?>">
<?= htmlspecialchars($option) ?>
</option>

<?php endforeach; ?>

</select>

<?php else: ?>

<input type="text"
       name="<?= htmlspecialchars($field) ?>">

<?php endif; ?>

<br><br>

<?php endforeach; ?>


<br><br>


<button type="submit">
Generate Cabrillo
</button>


</form>


</div>


<?php include __DIR__ . '/../../include/footer.php'; ?>


</body>
</html>