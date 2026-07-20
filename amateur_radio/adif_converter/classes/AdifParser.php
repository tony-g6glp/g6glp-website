<?php

class AdifParser
{

    public function parse($filename)
    {

        $contents = file_get_contents($filename);

        if ($contents === false) {

            throw new Exception('Unable to read ADIF file');

        }


        $records = [];

        preg_match_all(
            '/<([^>]+)>([^<]*)/i',
            $contents,
            $matches,
            PREG_SET_ORDER
        );


        $qso = [];


        foreach ($matches as $field) {

            $tag = strtoupper(trim($field[1]));
            $value = trim($field[2]);


            /*
                Ignore tags that contain lengths
                but extract the field name
            */

            if (strpos($tag, ':') !== false) {

                $parts = explode(':', $tag);
                $tag = strtoupper($parts[0]);

            }


            if ($tag === 'EOR') {

                if (!empty($qso)) {

                    $records[] = $qso;
                    $qso = [];

                }

                continue;

            }


            /*
                Ignore ADIF header information
                until we reach actual QSOs
            */

            if ($tag !== 'EOH') {

                $qso[$tag] = $value;

            }

        }


        return $records;

    }

}