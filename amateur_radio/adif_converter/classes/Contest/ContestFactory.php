<?php

require_once __DIR__ . '/RsgbData.php';
require_once __DIR__ . '/Rsgb80mClub.php';

class ContestFactory
{

    public static function create($id)
    {

        switch ($id) {


            case 'rsgb-data':

                return new RsgbData();
				
			case 'rsgb-80m-club':
    		
				return new Rsgb80mClub();


            default:

                throw new Exception(
                    'Unknown contest: ' . $id
                );

        }

    }

}