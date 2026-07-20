<?php

require_once __DIR__ . '/AbstractContest.php';


class RsgbData extends AbstractContest
{

    public function getId()
    {
        return 'rsgb-data';
    }


    public function getName()
    {
        return 'RSGB Data Contest';
    }


    public function validate(array $qsos)
    {

        $errors = [];


        foreach ($qsos as $index => $qso) {


            if (empty($qso['CALL'])) {

                $errors[] =
                    'QSO ' . ($index + 1) .
                    ': Missing callsign';

            }


            if (empty($qso['STX'])) {

                $errors[] =
                    'QSO ' . ($index + 1) .
                    ': Missing sent exchange';

            }


            if (
                empty($qso['SRX']) &&
                empty($qso['SRX_STRING'])
            ) {

                $errors[] =
                    'QSO ' . ($index + 1) .
                    ': Missing received exchange';

            }

        }


        return $errors;

    }


    public function buildHeader(array $station)
    {

        return
            "START-OF-LOG: 3.0\n" .
            "CONTEST: RSGB-DATA\n" .
            "CALLSIGN: " . $station['callsign'] . "\n" .
            "CATEGORY-POWER: " . ($station['category_power'] ?? '') . "\n" .
			"NAME: " . ($station['operator'] ?? '') . "\n" .
			"LOCATION: " . ($station['location'] ?? '') . "\n" .
			"CATEGORY-OPERATOR: " . ($station['category_operator'] ?? '') . "\n" .
			"CATEGORY-ASSISTED: " . ($station['category_assisted'] ?? '') . "\n" .
			"CATEGORY-BAND: " . ($station['category_band'] ?? '') . "\n" .
			"CATEGORY-POWER: " . ($station['category_power'] ?? '') . "\n" .
			"CATEGORY-MODE: " . ($station['category_mode'] ?? '') . "\n" .
			"CATEGORY-TRANSMITTER: " . ($station['category_transmitter'] ?? '') . "\n" .
			"CATEGORY-OVERLAY: " . ($station['category_overlay'] ?? '') . "\n" .
			"GRID-LOCATOR: " . ($station['grid_locator'] ?? '') . "\n\n";

    }


	public function buildQso(array $qso, array $station)
		{

        return sprintf(
        "QSO: %-5s %-3s %s %s %-12s %s %-6s %-12s %s %-6s\n",
        $qso['CABRILLO_BAND'] ?? '',
        $qso['CABRILLO_MODE'] ?? '',
        $qso['QSO_DATE'],
        $qso['TIME_ON'],
        $station['callsign'],
        $qso['RST_SENT'] ?? '599',
        $qso['STX'] ?? '',
        $qso['CALL'],
        $qso['RST_RCVD'] ?? '599',
        $qso['SRX'] ?? $qso['SRX_STRING'] ?? ''
    	);

    }
	
	public function getStationFields()
		{
    	return [

			'operator' => [
				'type' => 'text'
			],
	
			'location' => [
				'type' => 'text'
			],
	
			'category_operator' => [
				'type' => 'select',
				'options' => [
					'SINGLE-OP',
					'MULTI-OP'
				]
			],
	
			'category_assisted' => [
				'type' => 'select',
				'options' => [
					'ASSISTED',
					'NON-ASSISTED'
				]
			],
	
			'category_band' => [
				'type' => 'select',
				'options' => [
					'ALL',
					'160M',
					'80M',
					'40M',
					'20M',
					'15M',
					'10M'
				]
			],
	
			'category_power' => [
				'type' => 'select',
				'options' => [
					'HIGH',
					'LOW',
					'QRP'
				]
			],
	
			'category_mode' => [
				'type' => 'select',
				'options' => [
					'SSB',
					'CW',
					'DIGITAL'
				]
			],
	
			'category_transmitter' => [
				'type' => 'select',
				'options' => [
					'ONE',
					'TWO',
					'UNLIMITED'
				]
			],
	
			'category_overlay' => [
				'type' => 'text'
			],
	
			'grid_locator' => [
				'type' => 'text'
			]
	
		];
}

}