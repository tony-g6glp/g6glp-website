<?php
class Job
{
    public array $data = [];

    public static function load(PDO $pdo, string $token): Job
    {
        $stmt = $pdo->prepare("
            SELECT *
            FROM adif_jobs
            WHERE token = ?
        ");

        $stmt->execute([$token]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            throw new Exception('Job not found');
        }

        $job = new self();
        $job->data = $row;

        return $job;
    }
	
	public function get(string $field)
		{
			return $this->data[$field] ?? null;
	}

	public function saveContest(PDO $pdo, string $contest)
		{
			$stmt = $pdo->prepare("
				UPDATE adif_jobs
				SET contest = ?
				WHERE token = ?
			");
		
			$stmt->execute([
				$contest,
				$this->data['token']
			]);
		
			$this->data['contest'] = $contest;
	}

	public function saveStation(
			PDO $pdo,
			array $station
		)
		{
			$stationJson = json_encode($station);
		
			$stmt = $pdo->prepare("
			UPDATE adif_jobs
			SET callsign = ?,
				operator = ?,
				power = ?,
				location = ?,
				category_operator = ?,
				category_assisted = ?,
				category_band = ?,
				category_power = ?,
				category_mode = ?,
				category_transmitter = ?,
				category_overlay = ?,
				grid_locator = ?,
				station_json = ?
				WHERE token = ?
			");
		
			$stmt->execute([
				$station['callsign'] ?? '',
				$station['operator'] ?? '',
				$station['category_power'] ?? '',
				$station['location'] ?? '',
				$station['category_operator'] ?? '',
				$station['category_assisted'] ?? '',
				$station['category_band'] ?? '',
				$station['category_power'] ?? '',
				$station['category_mode'] ?? '',
				$station['category_transmitter'] ?? '',
				$station['category_overlay'] ?? '',
				$station['grid_locator'] ?? '',
				json_encode($station),
				$this->data['token']
			]);
		
			$this->data['callsign'] = $station['callsign'] ?? '';
			$this->data['operator'] = $station['operator'] ?? '';
			$this->data['power'] = $station['category_power'] ?? '';
			$this->data['station_json'] = $stationJson;
		}

}