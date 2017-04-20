<?php

if(isset($_POST["submit"]))
{
	if(isset($_FILES['pic']))
	{
	if ($_FILES['pic']['type']=="image/jpeg" || $_FILES['pic']['type']=="image/jpg" || $_FILES['pic']['type']=="image/png" || $_FILES['pic']['type']=="image/gif" || $_FILES['pic']['type']=="image/bmp") {       //checking for image or not

			if($_FILES['pic']['size']<50000000 && $_FILES['pic']['size']>1000){ //Checking filesize to be in between 1KB to 50MB
				$loc="uploaded/".basename($_FILES['pic']['name']);
				if(file_exists($loc))
					header("location:index.php?status=5");
				else if(!isset($_POST['msg'])){
					header("location:index.php?status=6");
				}
				else{
					if (move_uploaded_file($_FILES["pic"]["tmp_name"], $loc)) {
                        generateeditimage($loc);
                        
                        
                    }else{
                        header("location:index.php?status=0");
                    }
                }
            }else{
                header("location:index.php?status=2");
                
            }

        }
        else{
         header("location:index.php?status=3");
     }	
 }else{
  header("location:index.php?status=4");
}
	
}

function generateeditimage($loc){
	$filename=$loc;
	$lochalf="uploaded/edited".basename($_FILES['pic']['name']);//storage location for edited file
	list($width, $height) = getimagesize($filename);
	$new_width = $width/2;
	$new_height = $height/2;
	$thumb = imagecreatetruecolor($new_width, $new_height);//creates the template image 
	switch ($_FILES['pic']['type']) {
		//Generates edited image files for different image formats 
		case "image/jpeg":
			$image = imagecreatefromjpeg($filename);//retrieves the image identifier of the original image
			imagecopyresampled($thumb, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			$thumb=addtext($thumb,$new_width,$new_height);		
			imagejpeg($thumb,$lochalf );//creates a jpg file in the specified location
			break;
		case "image/jpg":
			$image = imagecreatefromjpg($filename);//retrieves the image identifier of the original image
			imagecopyresampled($thumb, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			$thumb=addtext($thumb,$new_width,$new_height);
			imagejpg($thumb,$lochalf );
			break;
		case "image/png":
			$image = imagecreatefrompng($filename);//retrieves the image identifier of the original image
			imagecopyresampled($thumb, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			$thumb=addtext($thumb,$new_width,$new_height);	
			imagepng($thumb,$lochalf);
			break;
		case "image/gif":
			$image = imagecreatefromgif($filename);//retrieves the image identifier of the original image
			imagecopyresampled($thumb, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);	
			$thumb=addtext($thumb,$new_width,$new_height);
			imagegif($thumb,$lochalf);
			break;
		case "image/bmp":
			$image = imagecreatefrombmp($filename);//retrieves the image identifier of the original image
			imagecopyresampled($thumb, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			$thumb=addtext($thumb,$new_width,$new_height);	
			imagebmp($thumb,$lochalf);
			break;

	}
	//inserting in database
	$con=mysqli_connect("localhost","root","","images") or die(mysqli_error($con));
    mysqli_query($con,"INSERT INTO locations VALUES ('".$loc."','".$lochalf."')");
    header("location:index.php?status=1");
	imagedestroy($thumb);//freeing up memory
}


function addtext($thumb,$new_width,$new_height){//this adds text at the center
	$text=test_input($_POST['msg']);
	$font='font/arial.ttf';
	$fsize=$new_height/10;//font size
	$white = imagecolorallocate($thumb, 255, 255, 255);
	$black = imagecolorallocate($thumb, 0, 0, 0);//Assigning colors for text shadow
	$new_width=($new_width/2)-((strlen($text)/2)*fsize);
	imagettftext($thumb, $fsize, 0, $new_width, $new_height/2, $black, $font, $text);
	imagettftext($thumb, $fsize, 0, $new_width+1 , $new_height/2+1 , $white, $font, $text);
	return $thumb;
}

function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
?>