<?php
if (!empty($_FILES['files']['name'][0])){

    $files = $_FILES['files'];

    $uploaded = array();
    $failed = array();

    $allowed = array('png', 'jpg', 'gif');

    foreach ($files['name'] as $position => $file_name){

        $file_tmp = $files['tmp_name'][$position];
        $file_size = $files['size'][$position];
        $file_error = $files['error'][$position];

        $file_ext = explode('.', $file_name);
        $file_ext = strtolower(end($file_ext));

        if (in_array($file_ext, $allowed)){

            if ($file_error === 0){

                if ($file_size <= 1000000){

                    $file_name_new = uniqid('', true) . '.' . $file_ext;
                    $file_destination = 'uploads/' . $file_name_new;

                    if (move_uploaded_file($file_tmp, $file_destination)){
                        $uploaded[$position] = $file_destination;
                    } else {
                        $failed[$position] = "[{$file_name}] failed to upload.";
                    }

                }else {
                    $failed[$position] = "[{$file_name}] is too large.";
                }

            } else {
                $failed[$position] = "[{$file_name}] errored with code {$file_error}.";
            }

        } else {
            $failed[$position] = "[{$file_name}] file extension '{$file_ext}' is not allowed.";
        }
    }
    if (!empty($uploaded)){
        print_r($uploaded);
    }
    if (!empty($failed)){
        print_r($failed);
    }
}

$dir = 'uploads/';

$files_scan = scandir($dir);

$files = array_diff($files_scan, array('.', '..'));

if (isset($_GET['imgToDelete'])) {

    $file = $_GET['imgToDelete'];

    $dir = 'uploads/';

    $link = $dir.$file;
    var_dump($link);

    if (file_exists($link)) {

        unlink($link);
        header('Location: upload.php');
    }
    else {
        header('Location: upload.php');
    }
}

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Titre de la page</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>
<form action="" method="post" enctype="multipart/form-data">
    <label for="imageUpload">Upload an profile image</label>
    <input type="file" name="files[]" multiple="multiple" id="imageUpload" />
    <input type="submit" value="Upload">
</form>
<figure>
    <?php
    foreach ($files as $image){
        ?>
        <div class="card" style="width: 18rem;">
            <img src="uploads/<?php echo $image; ?>" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title"><?php echo $image; ?></h5>
                <a href="upload.php?imgToDelete=<?php echo $image; ?>" class="btn btn-primary">Delete</a>
            </div>
        </div>
        <?php
    }
    ?>
</body>
</html>