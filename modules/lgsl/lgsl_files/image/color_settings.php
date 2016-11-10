<?php
// Custom	
switch($server['s']['game']){
	case "nucleardawn":
		switch($type){
			case "small":
				$txt_outline = true;
				$text_color0 = ImageColorAllocate($im, 255, 149, 56);
			break;
			case "normal":
				$txt_outline = true;
				$text_color0 = ImageColorAllocate($im, 255, 149, 56);
			break;
			case "sky":
				$name_type_vertical = true;
				$txt_outline = true;
				$text_color1 = ImageColorAllocate($im, 255, 149, 56);
			break;
		}
	break;
}
?>