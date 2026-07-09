<?php

// =================================
// Redirect helpers
// =================================
function redirect($path) {
    header("Location: /g6glp" . $path);
    exit;
}



// =================================
// Slug helpers
// =================================
function create_slug($text) {
   
	$slug = strtolower($text);
	$slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
	$slug = trim($slug, '-');
	return $slug;
}


// =================================
// Image helpers
// =================================
function e($value)
{
    if (is_array($value)) {
        return '';
    }

    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}



function upload_image($file, $folder = 'posts')
{
    if (
        !isset($file) ||
        $file['error'] === UPLOAD_ERR_NO_FILE
    ) {
        return null;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp'
    ];

    $mime = mime_content_type($file['tmp_name']);

    if (!isset($allowed[$mime])) {
        return null;
    }

	$filename = uniqid('', true) . '.jpg';

$destination = __DIR__ . '/../uploads/' . $folder . '/' . $filename;


// Get image dimensions
$info = getimagesize($file['tmp_name']);

$width = $info[0];
$height = $info[1];

$max_width = 1600;
$max_height = 1200;


// Work out new size
$ratio = min(
    $max_width / $width,
    $max_height / $height,
    1
);

$new_width = (int)($width * $ratio);
$new_height = (int)($height * $ratio);


// Create source image
switch ($mime) {

    case 'image/jpeg':
        $source = imagecreatefromjpeg($file['tmp_name']);
        break;

    case 'image/png':
        $source = imagecreatefrompng($file['tmp_name']);
        break;

    case 'image/webp':
        $source = imagecreatefromwebp($file['tmp_name']);
        break;

    default:
        return null;
}


// Create resized image
$image = imagecreatetruecolor(
    $new_width,
    $new_height
);


// Preserve transparency
imagecopyresampled(
    $image,
    $source,
    0,
    0,
    0,
    0,
    $new_width,
    $new_height,
    $width,
    $height
);


// Save as JPEG quality 85
imagejpeg(
    $image,
    $destination,
    85
);


imagedestroy($source);
imagedestroy($image);


// Create thumbnail
$thumb_name = pathinfo($filename, PATHINFO_FILENAME) . '_thumb.jpg';

$thumb_path = __DIR__ . '/../uploads/' . $folder . '/thumbs/' . $thumb_name;


create_thumbnail(
    $destination,
    $thumb_path,
    400
);


return $filename;
}



// =================================
// Thumbnail helpers
// =================================
function create_thumbnail($source, $destination, $max_width = 400)
{
    $info = getimagesize($source);

    if (!$info) {
        return false;
    }

    $mime = $info['mime'];

    switch ($mime) {

        case 'image/jpeg':
            $image = imagecreatefromjpeg($source);
            break;

        case 'image/png':
            $image = imagecreatefrompng($source);
            break;

        case 'image/webp':
            $image = imagecreatefromwebp($source);
            break;

        default:
            return false;
    }


    $width = imagesx($image);
    $height = imagesy($image);


    if ($width <= $max_width) {
        $new_width = $width;
        $new_height = $height;

    } else {

        $ratio = $max_width / $width;

        $new_width = $max_width;
        $new_height = (int)($height * $ratio);

    }


    $thumb = imagecreatetruecolor(
        $new_width,
        $new_height
    );


    imagecopyresampled(
        $thumb,
        $image,
        0,
        0,
        0,
        0,
        $new_width,
        $new_height,
        $width,
        $height
    );


    imagejpeg(
        $thumb,
        $destination,
        85
    );


    imagedestroy($image);
    imagedestroy($thumb);
	
	
	return true;
}