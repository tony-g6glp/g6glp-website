<?php

class Rsgb80mClub extends AbstractContest
{


    public function getId()
    {
    	return 'rsgb-80m-club';
    }


    public function getName()
    {
        return 'RSGB 80m Club Championship';
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
					'NON-ASSISTED',
					'ASSISTED'
					
				]
			],
	
			'category_band' => [
				'type' => 'select',
				'options' => [
					'80M'
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
					'TWO'
				]
			],
	
			
	
			'grid_locator' => [
				'type' => 'text'
			]
	
		];
	}


    public function buildHeader(array $station)
    {

        return
            "START-OF-LOG: 3.0\n" .
            "CONTEST: RSGB-80m-CC\n" .
            "CALLSIGN: " . $station['callsign'] . "\n" .
			"NAME: " . ($station['operator'] ?? '') . "\n" .
			"CATEGORY-OPERATOR: " . ($station['category_operator'] ?? '') . "\n" .
			"CATEGORY-ASSISTED: " . ($station['category_assisted'] ?? '') . "\n" .
			"CATEGORY-BAND: " . ($station['category_band'] ?? '') . "\n" .
			"CATEGORY-POWER: " . ($station['category_power'] ?? '') . "\n" .
			"CATEGORY-MODE: " . ($station['category_mode'] ?? '') . "\n" .
			"CATEGORY-TRANSMITTER: " . ($station['category_transmitter'] ?? '') . "\n" .
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

}