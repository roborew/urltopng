urltopng
========

Class to convert URLS to pngs using URL2PNG api: http://url2png.com

The image size switch allows you to define different folders for different size captures. 

example of use :

  		$capture_img_location = new urltopng($site_url, $unique_id, $image_size);
      echo '<img src="'.$capture_img_location.'" />';

