<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Source database
$source = new PDO(
    "mysql:host=172.16.10.198;dbname=TARS;charset=utf8",
    "tony",
    "apr100455apr"
);

// Destination database
$dest = new PDO(
    "mysql:host=172.16.10.198;dbname=G6GLP;charset=utf8",
    "tony",
    "apr100455apr"
);

// Read from source
$sql = "SELECT raised, raised_by, problem, work, resolved FROM ISSUES";
$stmt = $source->query($sql);

// Prepare insert
$insert = $dest->prepare("
    INSERT INTO blog_posts
    (
        title,
        content,
        status,
		category_id,
		created_at,
		created_by
    )
   VALUES
(
    :title,
    :content,
    :status,
    :category_id,
    :created_at,
    :created_by
)
"); 

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    // Transform data
    $title  = $row['problem'];
	$content = $row['work'];
	$status = 'published';
    $category_id  = '$category_id' ;
    $created_at = $row['raised'];
	$created_by = '1';

    // Insert
    $insert->execute([
        ':title' => $title,
		':content' => $content,
        ':status' => $status,
        ':category_id' => '8',
		':created_at' =>$created_at,
		':created_by' =>$created_by
    ]);
/*	echo "INSERT INTO blog_posts
(title, content, status, category_id, created_at, created_by)
VALUES (
'" . addslashes($row['problem']) . "',
'" . addslashes($row['work']) . "',
'published',
'8',
'" . $row['raised'] . "',
'1'
);<br>"; */
    
	 
}

echo "Import complete.";