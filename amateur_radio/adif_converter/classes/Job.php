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
			string $callsign,
			string $operator,
			string $power
		)
		{
			$stmt = $pdo->prepare("
				UPDATE adif_jobs
				SET callsign = ?,
					operator = ?,
					power = ?
				WHERE token = ?
			");
		
			$stmt->execute([
				$callsign,
				$operator,
				$power,
				$this->data['token']
			]);
		
			$this->data['callsign'] = $callsign;
			$this->data['operator'] = $operator;
			$this->data['power'] = $power;
	}

}