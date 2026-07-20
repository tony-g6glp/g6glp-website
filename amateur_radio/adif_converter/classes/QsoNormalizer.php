<?php

class QsoNormalizer
{

    public function normalise(array $qso)
    {

        // Sent exchange

        if (
            empty($qso['STX']) &&
            !empty($qso['STX_STRING'])
        ) {

            $qso['STX'] = $qso['STX_STRING'];

        }
		
		if (!empty($qso['BAND'])) {

			$qso['CABRILLO_BAND'] =
				$this->normaliseBand($qso['BAND']);
		
		}
		
		if (!empty($qso['MODE'])) {

    		$qso['CABRILLO_MODE'] =
        	$this->normaliseMode($qso['MODE']);

		}

        // Received exchange

        if (
            empty($qso['SRX']) &&
            !empty($qso['SRX_STRING'])
        ) {

            $qso['SRX'] = $qso['SRX_STRING'];

        }


        // Uppercase common fields

        foreach (
            ['CALL','MODE','BAND']
            as $field
        ) {

            if (!empty($qso[$field])) {

                $qso[$field] =
                    strtoupper($qso[$field]);

            }

        }


        return $qso;

    }
	
	private function normaliseBand($band)
	{
	
		$band = strtoupper(trim($band));
	
		$band = str_replace('M','',$band);
	
		return $band . 'M';
	
	}
	
	private function normaliseMode($mode)
	{
	
		$mode = strtoupper(trim($mode));
	
	
		$map = [
	
			'RTTY'      => 'RY',
	
			'PSK'       => 'PS',
			'PSK31'     => 'DG',
			'PSK63'     => 'PS',
	
			'DIGITAL'   => 'DG',
	
			'SSB'       => 'PH',
			'USB'       => 'PH',
			'LSB'       => 'PH',
			'PHONE'     => 'PH',
	
			'CW'        => 'CW'
	
		];
	
	
		return $map[$mode] ?? $mode;
	
	}
}