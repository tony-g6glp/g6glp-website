<?php


class DatabaseContest
{

    private PDO $pdo;

    private array $contest;

    private array $headers = [];

    private array $qsoFields = [];
	
	
    public function __construct(PDO $pdo, int $contestId)
    {
		echo "Constructor started<br>";
        $this->pdo = $pdo;


        // Load contest

        $stmt = $this->pdo->prepare("
            SELECT *
            FROM contests
            WHERE id = ?
        ");

        $stmt->execute([$contestId]);

        $this->contest = $stmt->fetch(PDO::FETCH_ASSOC);


        if (!$this->contest) {
            throw new Exception("Contest not found");
        }


        // Load headers

        $stmt = $this->pdo->prepare("
            SELECT *
            FROM contest_headers
            WHERE contest_id = ?
            ORDER BY sort_order
        ");

        $stmt->execute([$contestId]);

        $this->headers = $stmt->fetchAll(PDO::FETCH_ASSOC);



        // Load QSO layout

        $stmt = $this->pdo->prepare("
            SELECT *
            FROM contest_qso_fields
            WHERE contest_id = ?
            ORDER BY position
        ");

        $stmt->execute([$contestId]);

        $this->qsoFields = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

	public function debug()
{
    echo "<pre>";

    echo "CONTEST\n";
    print_r($this->contest);

    echo "\nHEADERS\n";
    print_r($this->headers);

    echo "\nQSO FIELDS\n";
    print_r($this->qsoFields);

    echo "</pre>";
	}
	
	public function buildHeader(array $station): array
{
    $output = [];

    foreach ($this->headers as $header) {

        $value = '';

        if (isset($station[$header['source_field']])) {
            $value = $station[$header['source_field']];
        }

        $output[$header['header_name']] = $value;

    }

    return $output;
	}

	public function buildQso(array $qso): string
{
    $parts = [];

    foreach ($this->qsoFields as $field) {

        $value = '';

        if (isset($qso[$field['source_field']])) {
            $value = $qso[$field['source_field']];
        }
        elseif (!empty($field['default_value'])) {
            $value = $field['default_value'];
        }


        $parts[] = $value;

    }


    return implode(' ', $parts);
	}
}


?>