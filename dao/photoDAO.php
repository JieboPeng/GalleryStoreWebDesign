<?php
require_once('abstractDAO.php');
require_once('./model/photo.php');

class photoDAO extends abstractDAO {
        
    function __construct() {
        try{
            parent::__construct();
        } catch(mysqli_sql_exception $e){
            throw $e;
        }
    }  
    
    public function getPhoto($photoId){
        $query = 'SELECT * FROM photos WHERE id = ?';
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param('i', $photoId);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows == 1){
            $temp = $result->fetch_assoc();
            $photo = new photo($temp['id'],$temp['number'], $temp['text'], $temp['date'], $temp['image']);
            $result->free();
            return $photo;
        }
        $result->free();
        return false;
    }


    public function getPhotos(){
        //The query method returns a mysqli_result object
        $result = $this->mysqli->query('SELECT * FROM photos');
        $photos = Array();
        
        if($result->num_rows >= 1){
            while($row = $result->fetch_assoc()){
                //Create a new photo object, and add it to the array.
                $photo = new Photo($row['id'], $row['number'], $row['text'], $row['date'], $row['image']);
                $photos[] = $photo;
            }
            $result->free();
            return $photos;
        }
        $result->free();
        return false;
    }   
    
    public function addPhoto($photo){
        
        if(!$this->mysqli->connect_errno){
            //The query uses the question mark (?) as a
            //placeholder for the parameters to be used
            //in the query.
            //The prepare method of the mysqli object returns
            //a mysqli_stmt object. It takes a parameterized 
            //query as a parameter.
			$query = 'INSERT INTO photos (number, text, date, image) VALUES (?,?,?,?)';
			$stmt = $this->mysqli->prepare($query);
            if($stmt){
                    $number = $photo->getNumber();
			        $text = $photo->getText();
			        $date = $photo->getDate();
                    $image = $photo->getImage();
                  
			        $stmt->bind_param('isss', 
				        $number,
				        $text,
				        $date,
                        $image
			        );    
                    //Execute the statement
                    $stmt->execute();         
                    
                    if($stmt->error){
                        return $stmt->error;
                    } else {
                        return $photo->getNumber() . ' added successfully!';
                    } 
			}
             else {
                $error = $this->mysqli->errno . ' ' . $this->mysqli->error;
                echo $error; 
                return $error;
            }
       
        }else {
            return 'Could not connect to Database.';
        }
    }   
    public function updatePhoto($photo){
        
        if(!$this->mysqli->connect_errno){
            //The query uses the question mark (?) as a
            //placeholder for the parameters to be used
            //in the query.
            //The prepare method of the mysqli object returns
            //a mysqli_stmt object. It takes a parameterized 
            //query as a parameter.
            $query = "UPDATE photos SET number=?, text=?, date=?, image=? WHERE id=?";
            $stmt = $this->mysqli->prepare($query);
            if($stmt){
                    $id = $photo->getId();
                    $number = $photo->getNumber();
			        $text = $photo->getText();
			        $date = $photo->getDate();
                    $image = $photo->getImage();
                  
			        $stmt->bind_param('isssi', 
				        $number,
				        $text,
				        $date,
                        $image,
                        $id
			        );    
                    //Execute the statement
                    $stmt->execute();         
                    
                    if($stmt->error){
                        return $stmt->error;
                    } else {
                        return $photo->getNumber() . ' updated successfully!';
                    } 
			}
             else {
                $error = $this->mysqli->errno . ' ' . $this->mysqli->error;
                echo $error; 
                return $error;
            }
       
        }else {
            return 'Could not connect to Database.';
        }
    }   

    public function deletePhoto($photoId){
        if(!$this->mysqli->connect_errno){
            $query = 'DELETE FROM photos WHERE id = ?';
            $stmt = $this->mysqli->prepare($query);
            $stmt->bind_param('i', $photoId);
            $stmt->execute();
            if($stmt->error){
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }
}
?>