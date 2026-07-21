<?php

require_once __DIR__ . '/../../include/db.php';
require_once __DIR__ . '/classes/Contest/AbstractContest.php';
require_once __DIR__ . '/classes/Contest/DatabaseContest.php';


$contest = new DatabaseContest($pdo, 1);


echo $contest->getId();
echo "\n";

echo $contest->getName();
echo "\n";


print_r(
    $contest->getStationFields()
);

$station = [
    'callsign' => 'G6GLP',
    'operator' => 'Tony',
    'location' => 'DX',
    'category_operator' => 'SINGLE-OP',
    'category_assisted' => 'NON-ASSISTED',
    'category_band' => 'ALL',
    'category_power' => 'HIGH',
    'category_mode' => 'SSB',
    'category_transmitter' => 'ONE',
    'category_overlay' => '',
    'grid_locator' => 'IO80en'
];

echo $contest->buildHeader($station);

echo "\n\nQSO TEST\n\n";

$qso = [
    'CABRILLO_BAND' => '80M',
    'CABRILLO_MODE' => 'PH',
    'QSO_DATE' => '20240902',
    'TIME_ON' => '190143',
    'RST_SENT' => '59',
    'STX' => '001',
    'CALL' => 'G4FNL',
    'RST_RCVD' => '59',
    'SRX' => '007'
];

echo $contest->buildQso($qso, $station);