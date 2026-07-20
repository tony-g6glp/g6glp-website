<?php

require_once __DIR__ . '/../../include/bootstrap.php';
verify_csrf();
require_once __DIR__ . '/classes/AdifParser.php';

require_once __DIR__ . '/classes/QsoNormalizer.php';

require_once __DIR__ . '/classes/Job.php';

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


$job->saveStation(
    $pdo,
    $callsign,
    $operator,
    $power
);

$contest = ContestFactory::create($job->get('contest'));
/*
    Read ADIF
*/

$file = __DIR__ . '/uploads/' . $job->get('stored_filename');

$parser = new AdifParser();

$qsos = $parser->parse($file);

$normalizer = new QsoNormalizer();

foreach ($qsos as &$qso) {

    $qso = $normalizer->normalise($qso);

}

unset($qso);

/*
    Create Cabrillo text
*/

$cabrillo = "";


$cabrillo .= "START-OF-LOG: 3.0\n";
$cabrillo .= "CALLSIGN: " . $callsign . "\n";
$cabrillo .= "CONTEST: " . strtoupper($job->get('contest')) . "\n";
$cabrillo .= "CATEGORY-POWER: " . $power . "\n";


if ($operator) {

    $cabrillo .= "NAME: " . $operator . "\n";

}


$cabrillo .= "\n";


foreach ($qsos as $qso) {


    $date = $qso['QSO_DATE'] ?? '';

    $time = $qso['TIME_ON'] ?? '';

    $band = $qso['CABRILLO_BAND'] ?? '';
	$mode = $qso['CABRILLO_MODE'] ?? '';

    $call = strtoupper($qso['CALL'] ?? '');

    $sent = $qso['RST_SENT'] ?? '';

    $recv = $qso['RST_RCVD'] ?? '';


    $cabrillo .= sprintf(
        "QSO: %-5s %-3s %s %s %-12s %s %s\n",
        $band,
        $mode,
        $date,
        $time,
        $call,
        $sent,
        $recv
    );

}


$cabrillo .= "END-OF-LOG:\n";


/*
    Send download
*/

header('Content-Type: text/plain');

header(
    'Content-Disposition: attachment; filename="' .
    $callsign . '.log"'
);


echo $cabrillo;