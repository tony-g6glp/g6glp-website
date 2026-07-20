<?php

require_once __DIR__ . '/../../include/bootstrap.php';

require_once __DIR__ . '/classes/AdifParser.php';

require_once __DIR__ . '/classes/Contest/ContestRegistry.php';
/*
if (!can('use_adif_converter')) {

    http_response_code(403);
    die('Access denied');

} */


$token = $_GET['token'] ?? '';

if (!$token) {

    die('Missing token');

}


$stmt = $pdo->prepare("
    SELECT *
    FROM adif_jobs
    WHERE token = ?
");

$stmt->execute([$token]);

$job = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$job) {

    die('Job not found');

}


$file = __DIR__ . '/uploads/' . $job['stored_filename'];

if (!file_exists($file)) {

    die('File missing: ' . $file);

}


if (!is_readable($file)) {

    die('File exists but is not readable: ' . $file);

}
$parser = new AdifParser();

$qsos = $parser->parse($file);

require_once __DIR__ . '/classes/QsoNormalizer.php';

$normalizer = new QsoNormalizer();

$normalised_qsos = [];

foreach ($qsos as $qso) {

    $normalised_qsos[] =
        $normalizer->normalise($qso);
}


	

// Build summary information

$qso_count = count($qsos);

$bands = [];
$modes = [];
$dates = [];


foreach ($qsos as $qso) {

    if (!empty($qso['BAND'])) {
        $bands[$qso['BAND']] = true;
    }

    if (!empty($qso['MODE'])) {
        $modes[$qso['MODE']] = true;
    }

    if (!empty($qso['QSO_DATE'])) {
        $dates[] = $qso['QSO_DATE'];
    }

}


$bands = array_keys($bands);
$modes = array_keys($modes);

sort($bands);
sort($modes);

sort($dates);


$first_date = $dates[0] ?? '';
$last_date  = end($dates) ?: '';

?>
<!DOCTYPE html>
<html>
<head>
<title>ADIF Results</title>
<link rel="stylesheet" href="/g6glp/include/css.css">
</head>

<body>

<?php include __DIR__ . '/../../include/header.php'; ?>
<?php include __DIR__ . '/../../include/public-nav.php'; ?>


<div class="container">

<h1>ADIF Analysis</h1>

<p>
File:
<?= htmlspecialchars($job['original_filename']) ?>
</p>

<p>
QSOs found:
<strong><?= $qso_count ?></strong>
</p>


<p>
Date range:
<strong>
<?= htmlspecialchars($first_date) ?>
-
<?= htmlspecialchars($last_date) ?>
</strong>
</p>


<p>
Modes:
<strong>
<?= htmlspecialchars(implode(', ', $modes)) ?>
</strong>
</p>


<p>
Bands:
<strong>
<?= htmlspecialchars(implode(', ', $bands)) ?>
</strong>
</p>

<h3>First QSO</h3>

<pre>
<?= htmlspecialchars(print_r($qsos[0] ?? [], true)) ?>
</pre>
<h3>First Normalised QSO</h3>

<pre>
<?= htmlspecialchars(
    print_r($normalised_qsos[0] ?? [], true)
) ?>
</pre>



<h2>QSO Preview</h2>

<table class="table">

<tr>
    <th>Date</th>
    <th>Time</th>
    <th>Call</th>
    <th>Band</th>
    <th>Mode</th>
    <th>Sent</th>
    <th>Received</th>
</tr>

<?php foreach (array_slice($qsos, 0, 20) as $qso): ?>

<tr>

<td>
<?= htmlspecialchars($qso['QSO_DATE'] ?? '') ?>
</td>

<td>
<?= htmlspecialchars($qso['TIME_ON'] ?? '') ?>
</td>

<td>
<?= htmlspecialchars($qso['CALL'] ?? '') ?>
</td>

<td>
<?= htmlspecialchars($qso['BAND'] ?? '') ?>
</td>

<td>
<?= htmlspecialchars($qso['MODE'] ?? '') ?>
</td>

<td>
<?= htmlspecialchars(
    $qso['STX'] ?? $qso['STX_STRING'] ?? ''
) ?>
</td>

<td>
<?= htmlspecialchars(
    $qso['SRX'] ?? $qso['SRX_STRING'] ?? ''
) ?>
</td>

</tr>

<?php endforeach; ?>

</table>

<p>
Showing first <?= min(20, count($qsos)) ?> QSOs
of <?= count($qsos) ?> total.
</p>

<h3>Available Fields</h3>

<pre>
<?= htmlspecialchars(print_r(array_keys($qsos[0] ?? []), true)) ?>
</pre>
<h2>Create Cabrillo Log</h2>
<?php $contests = ContestRegistry::all(); ?>
<form method="post" action="generate.php">

    <input type="hidden" name="token"
           value="<?= htmlspecialchars($token) ?>">

	<?= csrf_field() ?>
    <label for="contest">
        Select Contest:
    </label>

    <br>

    <select name="contest" id="contest">
	
		<?php foreach ($contests as $contest): ?>
		<option value="<?= htmlspecialchars($contest->getId()) ?>">
		<?= htmlspecialchars($contest->getName()) ?>
		</option>

<?php endforeach; ?>
    </select>

    <br><br>

    <button type="submit">
        Continue
    </button>

</form>
</div>


<?php include __DIR__ . '/../../include/footer.php'; ?>

</body>
</html>