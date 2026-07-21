<?php

class DatabaseContest extends AbstractContest
{

    private PDO $pdo;
    private int $contestId;
    private array $contest = [];


    public function __construct(PDO $pdo, int $contestId)
    {
        $this->pdo = $pdo;
        $this->contestId = $contestId;

        $this->loadContest();
    }


    private function loadContest()
    {

        $stmt = $this->pdo->prepare("
            SELECT *
            FROM contests
            WHERE id = ?
        ");

        $stmt->execute([
            $this->contestId
        ]);

        $this->contest = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$this->contest) {

            throw new Exception(
                'Contest not found'
            );

        }

    }


    public function getId()
    {
        return $this->contest['contest_id'];
    }


    public function getName()
    {
        return $this->contest['name'];
    }

	public function getStationFields(): array
{

    $fields = [];


    $stmt = $this->pdo->prepare("
        SELECT *
        FROM contest_fields
        WHERE contest_id = ?
        ORDER BY sort_order
    ");

    $stmt->execute([
        $this->contestId
    ]);


    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $field) {


        $definition = [
            'type' => $field['field_type']
        ];


        if ($field['field_type'] === 'select') {


            $opt = $this->pdo->prepare("
                SELECT option_value
                FROM contest_field_options
                WHERE field_id = ?
                ORDER BY sort_order
            ");


            $opt->execute([
                $field['id']
            ]);


            $definition['options'] =
                $opt->fetchAll(PDO::FETCH_COLUMN);

        }


        $fields[$field['field_name']] = $definition;

    }


    return $fields;

	}
	
	public function buildHeader(array $station): string
{

    $output = '';

    $output .= "START-OF-LOG: "
        . ($this->contest['cabrillo_version'] ?? '3.0')
        . "\n";

    $output .= "CONTEST: "
        . ($this->contest['cabrillo_name'] ?? '')
        . "\n";


    $stmt = $this->pdo->prepare("
        SELECT *
        FROM contest_headers
        WHERE contest_id = ?
        ORDER BY sort_order
    ");

    $stmt->execute([
        $this->contestId
    ]);


    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $header) {


        $source = $header['source_field'];


        if (isset($station[$source])) {

            $value = $station[$source];

        } else {

            $value = '';

        }


        $output .= $header['header_name']
            . ": "
            . $value
            . "\n";

    }


    return $output . "\n";

	}
	
	public function buildQso(array $qso, array $station): string
{

    $parts = [];


    $stmt = $this->pdo->prepare("
        SELECT *
        FROM contest_qso_fields
        WHERE contest_id = ?
        ORDER BY position
    ");

    $stmt->execute([
        $this->contestId
    ]);


    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $field) {


        $source = $field['source_field'];


        if (str_starts_with($source, 'station.')) {

            $key = substr($source, 8);
            $value = $station[$key] ?? '';

        } else {

            $value = $qso[$source] ?? '';

        }


        if ($value === '' && $field['default_value'] !== null) {

            $value = $field['default_value'];

        }

		if ($field['field_width']) {
		
			$width = (int)$field['field_width'];
		
			if ($field['alignment'] === 'right') {
		
				$value = str_pad(
					$value,
					$width,
					' ',
					STR_PAD_LEFT
				);
		
			} else {
		
				$value = str_pad(
					$value,
					$width,
					' ',
					STR_PAD_RIGHT
				);
		
			}
		
		}


        $parts[] = $value;

    }


    return "QSO: " . implode('', $parts) . "\n";

	}
	
	public function validate(array $qsos): array
	{
		return [];
	}
}