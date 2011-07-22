<?php
require('imageFunctions.php');
require('validations.php');

session_start();
$user_id = $_SESSION['id'];

$max_dimension = "400";		// Max width allowed for the large image
$min_dimension = "75";

/*image type:
1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF, 5 = PSD, 6 = BMP, 7 = TIFF(orden de bytes intel), 8 = TIFF(orden de bytes motorola), 9 = JPC, 10 = JP2, 11 = JPX, 12 = JB2, 13 = SWC, 14 = IFF, 15 = WBMP, 16 = XBM.
*/

//acceptable image types
$gif = 1;
$jpg = 2;
$png = 3;
$bmp = 6;

//check image type
$validImageType = array($gif, $jpg, $png, $bmp);
list($width, $height, $type, $attr) = getimagesize($_FILES["file"]["tmp_name"]); 

//check extension
$validExtensions = array("png", "gif", "jpeg", "jpg", "bmp");
$ext = end(explode(".",$_FILES["file"]["name"]));
$ext2= strtolower($ext);

//check image type 2 ways, extensions & size
if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/bmp")
|| ($_FILES["file"]["type"] == "image/png")
|| ($_FILES["file"]["type"] == "image/x-png"))
&& in_array($ext2,$validExtensions)
&& in_array($type,$validImageType))
{
	//check image size
	if($_FILES["file"]["size"] > 1000000){
		$error_message = "Please select a smaller image.";
		sendToJS(0, $error_message);
	}
	
	//check for error code
	if ($_FILES["file"]["error"] > 0)
	{
		$error_message = "Return Code: " . $_FILES["file"]["error"];
		sendToJS(0, $error_message);
	}
	else
	{
		//rename image & add back on extension
		$file_name_orig = basename($_FILES['file']['name']);
		$file_ext = strtolower(substr($file_name_orig, strrpos($file_name_orig, '.') + 1));
		
		$key = strtotime(date('Y-m-d H:i:s'));
		$file_name = $user_id . "_temp_" . $key . "." . $file_ext;// name the image w/ random number; should be of form: UID_temp_#####.***
		
		$large_path = "../images/temporary";
		
		$large_image_location = $large_path."/".$file_name;
		
		//save large image
		move_uploaded_file($_FILES["file"]["tmp_name"],
		$large_image_location);      
		chmod($large_image_location, 0777);
		
		//get height, width & scale if too big
		$width = getWidth($large_image_location);
		$height = getHeight($large_image_location);			

		//find out which dimension is larger (we need both min and max)
		//this only works because the picture is a square
		if ($width > $height){
			$max_dimension_num = $width;
			$min_dimension_num = $height;
		}else
		{
			$max_dimension_num = $height;
			$min_dimension_num = $width;
		}
		
		//error if image is too small
		if ($min_dimension_num < $min_dimension){
			$error_message = "Please use a larger image.";
			sendToJS(0, $error_message);
		}
	
		/*
		//test file conversion to .jpg
		$large_image_location = convertImage($large_image_location);
		chmod($large_image_location, 0777);*/
		
		//Scale the image if it is greater than the max dimension
		if ($max_dimension_num > $max_dimension){
			$scale = $max_dimension/$max_dimension_num;
			$uploaded = resizeImage($large_image_location,$width,$height,$scale);
		}else{
			$scale = 1;
			$uploaded = resizeImage($large_image_location,$width,$height,$scale);
		} 
		
		//set data array of picture location & print to JavaSrcipt
		
		$new_file_name  = substr($file_name, 0, strrpos($file_name, '.')).".jpg";
		sendToJS(1, $new_file_name);

		//sendToJS(1, $file_name);
		
	}
}
else
{
	$error_message = "Please select a valid file type.";
	sendToJS(0, $error_message);
}

?>