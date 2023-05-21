<?php
// Include photoDAO file
require_once('./dao/photoDAO.php');

 
// Define variables and initialize with empty values
$number = $text = $date = $image = "";
$number_err = $text_err = $date_err = $image_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

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
    if (isset($_FILES['image'])){
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        //$file_type = $_FILES['image']['type'];
        //$file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
        $file_parts = explode('.', $file_name);
        $file_ext = end($file_parts);
        $expensions= array("jpeg","jpg","png");

        if (empty($file_name)) {
            $image_err='Please select a image.';
        }
        elseif(in_array($file_ext,$expensions)=== false){
           $image_err="extension not allowed, please choose a JPEG or PNG file.";
        }
        elseif ($file_size > 2097152) {
           $image_err = 'File size must be smaller than 2 MB';
        }

        if (empty($image_err) == true && isset($_FILES['image'])) {
           move_uploaded_file($file_tmp, "images/" . $file_name);
           $image = $file_name;
        }
    } else {
        $image_err = 'File is not posted';
    }

    // Check input errors before inserting in database
    if(empty($number_err) && empty($text_err) && empty($date_err) && empty($image_err)){
        $photoDAO = new photoDAO();    
        $photo = new Photo(0, $number, $text, $date, $image);
        $addResult = $photoDAO->addPhoto($photo);        
        header( "refresh:2; url=index.php" ); 
		echo '<br><h6 style="text-align:center">' . $addResult . '</h6>';   
        // Close connection
        $photoDAO->getMysqli()->close();
        }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Photo</title>
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
                    <h2 class="mt-5">Add Photo</h2>
                    <p>Please fill this form and submit to add photo record to the database.</p>
					
					<!--the following form action, will send the submitted form data to the page itself ($_SERVER["PHP_SELF"]), instead of jumping to a different page.-->
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Number</label>
                            <input type="text" name="number" class="form-control <?php echo (!empty($number_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $number; ?>">
                            <span class="invalid-feedback"><?php echo $number_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Text</label>
                            <textarea name="text" class="form-control <?php echo (!empty($text_err)) ? 'is-invalid' : ''; ?>"><?php echo $text; ?></textarea>
                            <span class="invalid-feedback"><?php echo $text_err;?></span>
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
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
        <?include 'footer.php';?>
    </div>
</body>
</html>