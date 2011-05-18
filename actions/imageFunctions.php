<?php

/* ------------------------------functions:
function resizeImage($image,$width,$height,$scale);
function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale);
function getHeight($image);
function getWidth($image);

------------------------------------------------------------*/


function resizeImage($image,$width,$height,$scale) {
	list($imagewidth, $imageheight, $imageType) = getimagesize($image);
	$imageType = image_type_to_mime_type($imageType);
	$newImageWidth = ceil($width * $scale);
	$newImageHeight = ceil($height * $scale);
	$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
	switch($imageType) {
		case "image/gif":
			$source=imagecreatefromgif($image); 
			break;
		case "image/pjpeg":
			$source=imagecreatefromjpeg($image); 
			break;
		case "image/jpeg":
			$source=imagecreatefromjpeg($image); 
			break;
		case "image/jpg":
			$source=imagecreatefromjpeg($image); 
			break;
		case "image/png":
			$source=imagecreatefrompng($image); 
			break;
		case "image/x-png":
			$source=imagecreatefrompng($image); 
			break;
  	}
	imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
	
	switch($imageType) {
		case "image/gif":
	  		imagegif($newImage,$image);
			break;
		case "image/pjpeg":
			imagejpeg($newImage,$image,90); 
			break;
		case "image/jpeg":
			imagejpeg($newImage,$image,90); 
			break;
		case "image/jpg":
	  		imagejpeg($newImage,$image,90); 
			break;
		case "image/png":
			imagepng($newImage,$image);  
			break;
		case "image/x-png":
			imagepng($newImage,$image);  
			break;
	}
	
	
	chmod($image, 0777);
	return $image;
}
//You do not need to alter these functions
function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale){
	list($imagewidth, $imageheight, $imageType) = getimagesize($image);
	$imageType = image_type_to_mime_type($imageType);
	
	$newImageWidth = ceil($width * $scale);
	$newImageHeight = ceil($height * $scale);
	$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
	switch($imageType) {
		case "image/gif":
			$source=imagecreatefromgif($image); 
			break;
		case "image/pjpeg":
			$source=imagecreatefromjpeg($image); 
			break;
		case "image/jpeg":
			$source=imagecreatefromjpeg($image); 
			break;
		case "image/jpg":
			$source=imagecreatefromjpeg($image); 
			break;
		case "image/png":
			$source=imagecreatefrompng($image); 
			break;
		case "image/x-png":
			$source=imagecreatefrompng($image); 
			break;
  	}
	imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);
	switch($imageType) {
		case "image/gif":
	  		imagegif($newImage,$thumb_image_name); 
			break;
		case "image/pjpeg":
			magejpeg($newImage,$thumb_image_name,90); 
			break;
		case "image/jpeg":
			magejpeg($newImage,$thumb_image_name,90); 
			break;
		case "image/jpg":
	  		imagejpeg($newImage,$thumb_image_name,90); 
			break;
		case "image/png":
			imagepng($newImage,$thumb_image_name);  
			break;
		case "image/x-png":
			imagepng($newImage,$thumb_image_name);  
			break;
    }
	chmod($thumb_image_name, 0777);
	return $thumb_image_name;
}
//You do not need to alter these functions
function getHeight($image) {
	$size = getimagesize($image);
	$height = $size[1];
	return $height;
}
//You do not need to alter these functions
function getWidth($image) {
	$size = getimagesize($image);
	$width = $size[0];
	return $width;
}


?>