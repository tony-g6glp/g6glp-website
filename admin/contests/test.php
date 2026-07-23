<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once  'DatabaseContest.php';
require_once __DIR__ . '/../../include/db.php';   // adjust path if needed

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
<title>Untitled Document</title>
</head>

<body>
<?php echo "Before object<br>";

$contest = new DatabaseContest($pdo, 4);

echo "After object<br>";

//$contest->debug();$contest = new DatabaseContest($pdo, 4); 
echo "Boo<br>";
echo "<pre>";

//$contest->debug();

echo "</pre>";

$station = [
    'callsign' => 'G6GLP',
    'operator' => 'Tony'
]; 
echo "<pre>";

print_r($contest->buildHeader($station));

echo "</pre>";

$qso = [
    'Band' => '80M',
    'Mode' => 'DG',
    'Date' => '20260722',
    'Time' => '123400',
    'Call' => 'G3ABC',
    'RST_Sent' => '599',
    'STX' => '001',
    'RST_RCVD' => '599',
    'SRX' => '002'
];


echo $contest->buildQso($qso);

echo "<pre>";
print_r($qso);
echo "</pre>";
?>
</body>
</html>
