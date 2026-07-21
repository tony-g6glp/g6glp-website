<?php

require_once __DIR__ . '/../../include/bootstrap.php';
verify_csrf();
require_once __DIR__ . '/classes/AdifParser.php';

require_once __DIR__ . '/classes/QsoNormalizer.php';

require_once __DIR__ . '/classes/Job.php';

require_once __DIR__ . '/classes/Contest/ContestFactory.php';

require_once __DIR__ . '/classes/Contest/ContestFactory.php';

/*
if (!can('use_adif_converter')) {

    http_response_code(403);
    die('Access denied');

}
*/

$token = $_POST['token'] ?? '';

$callsign = strtoupper(trim($_POST['callsign'] ?? ''));

$operator = trim($_POST['operator'] ?? '');

$power = strtoupper(trim($_POST['power'] ?? 'LOW'));


if (!$token || !$callsign) {

    die('Missing information');

}


/*
    Load job
*/



try {

    $job = Job::load($pdo, $token);

} catch (Exception $e) {

    die($e->getMessage());

}

$contest = ContestFactory::create(
    $job->get('contest')
);

$station = [
    'callsign' => $callsign
];

foreach ($contest->getStationFields() as $field => $definition) {

    $station[$field] = $_POST[$field] ?? '';

}

$job->saveStation(
    $pdo,
    $station
);
$file = __DIR__ . '/uploads/' . $job->get('stored_filename');

$parser = new AdifParser();

$qsos = $parser->parse($file);

$normalizer = new QsoNormalizer();

foreach ($qsos as &$qso) {

	$qso = $normalizer->normalise($qso);
    
}

unset($qso);






$errors = $contest->validate($qsos);

if (!empty($errors)) {

   echo '<div class="container">';

	echo '<h1>Cabrillo Generation Failed</h1>';
	
	echo '<p>';
	echo 'The following problems were found in your ADIF file:';
	echo '</p>';
	
	echo '<ul>';
	
	foreach ($errors as $error) {
	
		echo '<li>';
		echo htmlspecialchars($error);
		echo '</li>';
	
	}
	
	echo '</ul>';
	
	echo '<p>';
	echo 'Please correct the ADIF file and upload it again.';
	echo '</p>';
	
	echo '</div>';

exit;

}


$cabrillo = $contest->buildHeader($station);

foreach ($qsos as $qso) {

    $cabrillo .= $contest->buildQso($qso, $station);

}
/*
    Send download
*/

header('Content-Type: text/plain');

header(
    'Content-Disposition: attachment; filename="' .
    $callsign . '.log"'
);


echo $cabrillo;