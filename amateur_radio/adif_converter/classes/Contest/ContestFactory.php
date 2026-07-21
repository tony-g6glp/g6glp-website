<?php

require_once __DIR__ . '/RsgbData.php';
require_once __DIR__ . '/Rsgb80mClub.php';
require_once __DIR__ . '/DatabaseContest.php';


class ContestFactory
{

    public static function create($pdo, $id)
    {


        /*
            First try database contests
        */

        $stmt = $pdo->prepare("
            SELECT id
            FROM contests
            WHERE contest_id = ?
            AND active = 1
            LIMIT 1
        ");

        $stmt->execute([
            $id
        ]);


        $contest = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($contest) {

            return new DatabaseContest(
                $pdo,
                $contest['id']
            );

        }



        /*
            Fallback to PHP contests
        */

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