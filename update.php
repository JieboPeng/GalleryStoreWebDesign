<?php
// Include photoDAO file
require_once('./dao/photoDAO.php');
 
// Define variables and initialize with empty values
$number = $text = $date = $image = $imageOld = "";
$number_err = $text_err = $date_err = $image_err = "";
$photoDAO = new photoDAO(); 

// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];

    // Validate number
    $input_number = trim($_POST["number"]);
    if(empty($input_number)){
        $number_err = "Please enter a number.";
    } elseif($input_number < 0 || $input_number > 1000) {
        $number_err = "Please enter a valid number between 0 and 1000.";
    } else{
        $number = $input_number;
    }

    // Validate text
    $input_text = trim($_POST["text"]);
    if(empty($input_text)){
        $text_err = "Please enter text.";     
    } elseif(strlen($input_text) > 20) {
        $text_err = "Please enter text less than 20 chars";
    } else{
        $text = $input_text;
    }
    
    // Validate date
    $input_date = trim($_POST["date"]);
    if(empty($input_date)){
        $date_err = "Please enter the date.";     
    } elseif(date_create($input_date) < date_create('1800-01-01')){
        $date_err = "Please enter a valid date which bigger than 1800-01-01.";
    } else{
        $date = $input_date;
    }
    
    // Validate image
    $imageOld = trim($_POST["imageOld"]);
    if (isset($_FILES['image'])){
        $file_name = $_FILES['image']['name'];

        if (empty($file_name)) {
            $image=$imageOld;
        }
        else {
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        //$file_type = $_FILES['image']['type'];
        //$file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
        $file_parts = explode('.', $file_name);
        $file_ext = end($file_parts);
        $expensions= array("jpeg","jpg","png");

        if(in_array($file_ext,$expensions)=== false){
           $image_err="extension not allowed, please choose a JPEG or PNG file.";
        }
        elseif ($file_size > 2097152) {
           $image_err = 'File size must be smaller than 2 MB';
        }

        if (empty($image_err) == true && isset($_FILES['image'])) {
           move_uploaded_file($file_tmp, "images/" . $file_name);
           $image = $file_name;
        }
        }
    } else {
        $image_err = 'File is not posted';
    }

    // Check input errors before inserting in database
    if(empty($number_err) && empty($text_err) && empty($date_err) && empty($image_err)){
        $photo = new Photo($id, $number, $text, $date, $image);
        $result = $photoDAO->updatePhoto($photo);        
        header( "refresh:2; url=index.php" ); 
		echo '<br><h6 style="text-align:center">' . $result . '</h6>';   
        // Close connection
        $photoDAO->getMysqli()->close();
    }

} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        $photo = $photoDAO->getPhoto($id);
        
        if($photo){
            // Retrieve individual field value
            $number = $photo->getNumber();
            $text = $photo->getText();
            $date = $photo->getDate();
            $imageOld = $photo->getImage();
        } else {
            // URL doesn't contain valid id. Redirect to error page
            header("location: error.php");
            exit();
        }
    } else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
    // Close connection
    $photoDAO->getMysqli()->close();
}
?>
 
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Update Photo Record</h2>
                    <p>Please edit the input values and submit to update the photo record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Number</label>
                            <input type="text" name="number" class="form-control <?php echo (!empty($number_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $number; ?>">
                            <span class="invalid-feedback"><?php echo $number_err;?></span>
                            <?php echo $number; ?>
                        </div>
                        <div class="form-group">
                            <label>Text</label>
                            <textarea name="text" class="form-control <?php echo (!empty($text_err)) ? 'is-invalid' : ''; ?>"><?php echo $text; ?></textarea>
                            <span class="invalid-feedback"><?php echo $text_err;?></span>
                            <?php echo $text; ?>
                        </div>
                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" name="date" class="form-control <?php echo (!empty($date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $date; ?>">
                            <span class="invalid-feedback"><?php echo $date_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $image; ?>">
                            <span class="invalid-feedback"><?php echo $image_err;?></span>
                        </div>
                        <input type="hidden" name="imageOld" value="<?php echo $imageOld; ?>"/>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>