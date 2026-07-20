<?php

require_once __DIR__ . '/RsgbData.php';


class ContestRegistry
{

    public static function all()
    {

        return [

            new RsgbData(),

        ];

    }

}