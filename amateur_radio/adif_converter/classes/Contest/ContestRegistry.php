<?php

require_once __DIR__ . '/RsgbData.php';
require_once __DIR__ . '/Rsgb80mClub.php';
require_once __DIR__ . '/DatabaseContest.php';


class ContestRegistry
{

    public static function all(PDO $pdo): array
    {

        $contests = [];


        // PHP contests

        $contests[] = new RsgbData();
        $contests[] = new Rsgb80mClub();



        // Database contests

        $stmt = $pdo->query("
            SELECT id
            FROM contests
            WHERE active = 1
            ORDER BY name
        ");


        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {

            $contests[] = new DatabaseContest(
                $pdo,
                $row['id']
            );

        }


        return $contests;

    }

}