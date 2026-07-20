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
            "CATEGORY-POWER: " . $station['power'] . "\n" .
			"NAME: " . ($station['operator'] ?? '') . "\n\n";

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
        'location',
        'category_operator',
        'category_assisted',
        'category_band',
        'category_power',
        'category_mode',
        'category_transmitter',
        'category_overlay',
        'grid_locator'
    	];
	}

}