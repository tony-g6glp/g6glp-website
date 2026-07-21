<?php

require_once __DIR__ . '/RsgbData.php';
require_once __DIR__ . '/Rsgb80mClub.php';


class ContestRegistry
{

    public static function all()
    {

        return [

            new RsgbData(),
			new Rsgb80mClub(),

        ];

    }

}