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


    public function buildQso(array $qso)
    {

        return sprintf(
            "QSO: %-5s %-3s %s %s %-12s %s %s\n",
            $qso['CABRILLO_BAND'] ?? '',
            $qso['CABRILLO_MODE'] ?? '',
            $qso['QSO_DATE'],
            $qso['TIME_ON'],
            $qso['CALL'],
            $qso['STX'] ?? '',
            $qso['SRX'] ?? $qso['SRX_STRING'] ?? ''
        );

    }

}