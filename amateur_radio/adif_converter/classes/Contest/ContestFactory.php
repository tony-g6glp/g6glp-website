<?php

require_once __DIR__ . '/RsgbData.php';


class ContestFactory
{

    public static function create($id)
    {

        switch ($id) {


            case 'rsgb-data':

                return new RsgbData();


            default:

                throw new Exception(
                    'Unknown contest: ' . $id
                );

        }

    }

}