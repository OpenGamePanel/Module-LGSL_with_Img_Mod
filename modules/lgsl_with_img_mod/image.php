<?php
/*
 *
 * OGP - Open Game Panel
 * Copyright (C) 2008 - 2017 The OGP Development Team
 *
 * http://www.opengamepanel.org/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 */

//------------------------------------------------------------------------------------------------------------+
// Basic Dynamic Server Image Status addon for LGSL by MadMakz (http://madmakz.com) for Cyber Games X24 (http://cgx24.com/).
// Tested & written for LGSL v5.8 (Might work with older/newer versions too)
// Version: 1.6-dev
// Licence: Nobody cares anyway, use it for whatever you want as long you do not sell/re-sell or steal the credits it´s fine.
//------------------------------------------------------------------------------------------------------------+
//------------------------------------------------------------------------------------------------------------+

// CUSTOM FUNCTIONS

function pretty_text($im, $fontsize, $x, $y, $string, $color, $outline = false)
{
	$black  = imagecolorallocate($bgImg, 0, 0, 0);

	// Black outline
	if($outline){
		imagestring($im, $fontsize, $x - 1, $y - 1, $string, $black);
		imagestring($im, $fontsize, $x - 1, $y, $string, $black);
		imagestring($im, $fontsize, $x - 1, $y + 1, $string, $black);
		imagestring($im, $fontsize, $x, $y - 1, $string, $black);
		imagestring($im, $fontsize, $x, $y + 1, $string, $black);
		imagestring($im, $fontsize, $x + 1, $y - 1, $string, $black);
		imagestring($im, $fontsize, $x + 1, $y, $string, $black);
		imagestring($im, $fontsize, $x + 1, $y + 1, $string, $black);
	}

	// Your text
	imagestring($im, $fontsize, $x, $y, $string, $color);
	return $im;
}


function pretty_text_ttf($im, $fontsize, $angle, $x, $y, $color, $font, $string, $outline = false)
{
	$black  = imagecolorallocate($bgImg, 0, 0, 0);

	// Black outline
	if($outline){
		imagettftext($im, $fontsize, $angle, $x - 1, $y - 1, $black, $font, $string);
		imagettftext($im, $fontsize, $angle, $x - 1, $y, $black, $font, $string);
		imagettftext($im, $fontsize, $angle, $x - 1, $y + 1, $black, $font, $string);
		imagettftext($im, $fontsize, $angle, $x, $y - 1, $black, $font, $string);
		imagettftext($im, $fontsize, $angle, $x, $y + 1, $black, $font, $string);
		imagettftext($im, $fontsize, $angle, $x + 1, $y - 1, $black, $font, $string);
		imagettftext($im, $fontsize, $angle, $x + 1, $y, $black, $font, $string);
		imagettftext($im, $fontsize, $angle, $x + 1, $y + 1, $black, $font, $string);
	}

	// Your text
	imagettftext($im, $fontsize, $angle, $x, $y, $color, $font, $string);
	return $im;
}


function lgsl_lookup_ip($ip, $port)
{
	global $db;
	$ip = $db->realEscapeSingle($ip);
	$port = $db->realEscapeSingle($port);
	
	$result = $db->resultQuery("SELECT `id` FROM `OGP_DB_PREFIXlgsl` WHERE `ip`='{$ip}' AND c_port='{$port}' LIMIT 1");
	return $result[0];
}
  
  
function error_img($msg, $id, $type)
{
	if(empty($type)) { $type = "normal"; }
	$bgimg = "modules/lgsl_with_img_mod/lgsl_files/image/default_{$type}.png";
	if (!file_exists($bgimg)) {
		$bgimg = "modules/lgsl_with_img_mod/lgsl_files/image/default.png";
	}
	
	$im = imagecreatefrompng($bgimg);
	$text_color = ImageColorAllocate($im,255,0,0);
	
	switch($type){
		case "normal":
			imagestring($im,6,2,5,"ERROR! ID/IP: ".$id,$text_color);
			imagestring($im,5,2,20,$msg,$text_color);
		break;
		case "small":
			imagestring($im,6,2,0,"ERROR! ID/IP: ".$id,$text_color);
			imagestring($im,5,2,10,$msg,$text_color);
		break;
		case "sky":
			$misc['image_map'] = "modules/lgsl_with_img_mod/lgsl_files/other/map_no_response.jpg";
			$im_map_info = getimagesize($misc['image_map']);
			$im_map = imagecreatefromjpeg($misc['image_map']);
			$im_map_width  = 130;
			$im_map_height = 120;
			$im_map_posx   = 25;
			$im_map_posy   = 112;
		
			imagecopyresampled($im, $im_map, $im_map_posx, $im_map_posy, 0, 0, $im_map_width, $im_map_height, $im_map_info[0], $im_map_info[1]);
			imagestring($im,1,6,28,"ERROR! ID/IP: ".$id,$text_color);
			imagestring($im,1,6,45,$msg,$text_color);
		break;
	}	
	make_img($im);	
}


function make_img($im = false, $cache_on = false, $cache_data = false, $force_cached = false, $format = false)
{
	header("Content-type: image/png");
	
	if($cache_on && $cache_data["file"]){
		$expire = gmdate("D, d M Y H:i:s", $cache_data["cache_expire"])." GMT";
		//$last = gmdate("D, d M Y H:i:s", filemtime($cache_data["file"]))." GMT";
		
		header("Expires: ".$expire);
		
		if(!$force_cached){ imagepng($im, $cache_data["file"], 9); }
		readfile($cache_data["file"]);
	}
	else{ imagepng($im, null, 9); }
	
	imagedestroy($im);
	exit;
}


function lgsl_cache_info($id)
{
	global $db;	
	$id = $db->realEscapeSingle($id);
	$result = $db->resultQuery("SELECT `cache_time` FROM `OGP_DB_PREFIXlgsl` WHERE `id`='{$id}' LIMIT 1");
	return empty($result[0]['cache_time']) ? array(0,0,0) : explode("_", $result[0]['cache_time']);
}


function cleaninput($input)
{
	$remove = array("#\\\\+#", "#/+#", "#\\+#", "#\s+#", "#http+#", "#ftp+#", "#%00+#", "#\\0+#", "#\\x00+#", "#\(+#", "#\)+#", "#\{+#", "#\}+#");
	// Some rules might be paranoid
	
	$input = preg_replace($remove, "", $input); 
	$input = htmlspecialchars($input, ENT_QUOTES);
	
	return $input;
}

//------------------------------------------------------------------------------------------------------------+
// SETTINGS

$cache_enable = true; // true/false // Enable/Disable Caching of generated images
$name_type_vertical = false; // false/true // Global default/fallback for printing the Servername verticaly on "Sky" images.

//------------------------------------------------------------------------------------------------------------+
// GET THE LGSL CORE

require_once "modules/lgsl_with_img_mod/lgsl_files/lgsl_class.php";

function exec_ogp_module()
{
	error_reporting(0);	  
	//------------------------------------------------------------------------------------------------------------+
	// GET THE SERVER DETAILS AND PREPARE IT FOR DISPLAY
	$s = cleaninput($_GET['s']);

	if (strpos($s, ":")){
		$is_ip = true;
		$s2 = explode(":", $s, 2);
	}
	if (strpos($s, "_")){
		$is_ip = true;
		$s2 = explode("_", $s, 2);
	}

	if ($is_ip){
		$lookup = lgsl_lookup_ip($s2[0], $s2[1]);
		$lgsl_server_id = $lookup['id'];
		if(!$lookup){
			$s2[0] = gethostbyname($s2[0]);
			$lookup = lgsl_lookup_ip($s2[0], $s2[1]);
			$lgsl_server_id = $lookup['id'];
		}
	}
	else { 
		$lookup = $s; 
		$lgsl_server_id = $s;
	}

	if($lookup){
		$server = lgsl_query_cached("", "", "", "", "", "sep", $lgsl_server_id);
		$fields = lgsl_sort_fields($server, $fields_show, $fields_hide, $fields_other);
		$server = lgsl_sort_players($server);
		$server = lgsl_sort_extras($server);
		$misc   = lgsl_server_misc($server);
	}
	
	if(!$lookup || !$server){
		if(!$is_ip){ error_img("This server does not exist in Database", $s, cleaninput($_GET['img_type'])); }
		else { error_img("This server does not exist in Database", $s2[0].":".$s2[1], cleaninput($_GET['img_type'])); }
		return;
	}

	//------------------------------------------------------------------------------------------------------------+
	// PREPARE THE IMAGE INFOS
	// WHAT BACKGROUND IMAGE WE USE. THE LAYOUT IS "image/<GAMETYPE>/<GAME>_<IMGTYPE>.png"

	$type = cleaninput($_GET['img_type']);
		  if (empty($type)){
		   $type = "normal";
		}

	$bgimg = cleaninput($_GET['bg']);
		  if (empty($bgimg)){
		   $bgimg = "{$server['b']['type']}/{$server['s']['game']}";
		}

	$bgimg = "modules/lgsl_with_img_mod/lgsl_files/image/{$bgimg}_{$type}.png";

	// IMAGE CACHE
	$cache_dir_prefix = "modules/lgsl_with_img_mod/lgsl_files/image";
	$cache_file = "modules/lgsl_with_img_mod/lgsl_files/image/cache/".$server['b']['ip']."_".$server['b']['c_port']."-".$type;
	$cache_time = lgsl_cache_info($lgsl_server_id);
	$cache_time_expire = $cache_time[0];
	$cache = array("file" => $cache_file, "cache_expire" => $cache_time_expire);

	if($cache_enable && file_exists($cache["file"])){
		if((time() - filemtime($cache["file"]) < $lgsl_config['cache_time']) || $server['b']['pending']){
			make_img(false, true, $cache, true);	
		}
	}

	// CHECK IF BACKGROUND IMAGE EXISTS, IF NOT USE DEFAULT
	if (!file_exists($bgimg)) {
		$bgimg = "modules/lgsl_with_img_mod/lgsl_files/image/default_{$type}.png";
		if (!file_exists($bgimg)) {
			$bgimg = "modules/lgsl_with_img_mod/lgsl_files/image/default.png";
			$type = "INVALID";
		}
	}
	if($server['disabled'] == 1){
		$bgimg = "modules/lgsl_with_img_mod/lgsl_files/image/default_{$type}.png";
		if (!file_exists($bgimg)) {
			$bgimg = "modules/lgsl_with_img_mod/lgsl_files/image/default.png";
			$type = "INVALID";
		}
	}

	//------------------------------------------------------------------------------------------------------------+
	// GET THE REQUIRED VARIABLES FROM LGSL
	// Rename status "NO RESPONSE" into "OFFLINE"
	if ($misc['text_status']=="NO RESPONSE"){
	  $misc['text_status'] = "Offline";
	}

	// Dummies
	$string0 = "";
	$string1 = "";
	$string2 = "";
	$string3 = "";
	$string4 = "";

	// If available; Strip Bots from current player and seperate true/total ammount.
	// Currently that only works on Sourcegames or any other game wich defines the bot ammount via "bots" tag in querry.
	// There are probably more games wich provide bot ammount info via querry, taged as "bots" or under other name.
	// Output format is: Trueplayers (Totalplayers)/Maxplayers
	$botinfo = true;
	$trueplayers = $server['s']['players'] - $server['e']['bots'];
	$slotusage = "{$server['s']['players']}/{$server['s']['playersmax']}";

	if(!$server['e']||!$server['e']['bots']||$server['e']['bots']=""){ $botinfo = false; }

	if($botinfo){
		$slotusage = "{$trueplayers} ({$server['s']['players']})/{$server['s']['playersmax']}";
	}

	// And now for real, first we do a little exeption for Left4Dead/2 (and TF2) since they have long mapnames wich would blow out on "small" image type
	// as workarround we just remove the "Status" line
	if($server['disabled'] == 0){
		if ($server['s']['game']=="left4dead" || $server['s']['game']=="left4dead2" || $server['s']['game']=="tf" && $type=="small"){
		$string0 = $server['s']['name'];
		$string1 = "{$server['b']['ip']}:{$server['b']['c_port']}";
		$string2 = $server['s']['map'];
		$string4 = $slotusage;
		}
		else{
		$string0 = $server['s']['name'];
		$string1 = "{$server['b']['ip']}:{$server['b']['c_port']}";
		$string2 = $server['s']['map'];
		$string3 = $slotusage;
		$string4 = ucfirst(strtolower($misc['text_status']));
		}
	}

	//------------------------------------------------------------------------------------------------------------+
	// DEFINE CREATE IMAGE FROM IMAGE SOURCE
	
	$im = imagecreatefrompng($bgimg);

	// MAP
	if($server['disabled'] == 1){
		$misc['image_map'] = "modules/lgsl_with_img_mod/lgsl_files/other/map_no_response.jpg";
	}
	
	// Adjust image map:
	if(cURLEnabled()){
		$misc['image_map'] = curlCacheImage($cache_dir_prefix, $misc['image_map']); 
	}else{
		stream_context_set_default(
			array(
				'http' => array(
					'method' => 'GET',
					'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:51.0) Gecko/20100101 Firefox/51.0',
					'header'=>'Referer: http://gametracker.com'
				)
			)
		);
	}
	
	$im_map_info = getimagesize($misc['image_map']);
	if ($im_map_info[2] == 1) { $im_map = imagecreatefromgif($misc['image_map']);  }
	if ($im_map_info[2] == 2) { $im_map = imagecreatefromjpeg($misc['image_map']); }
	if ($im_map_info[2] == 3) { $im_map = imagecreatefrompng($misc['image_map']);  }

	if($server['disabled'] == 0){
		// GAMEICON
		$im_icon_info = getimagesize($misc['icon_game']);
		if ($im_icon_info[2] == 1) { $im_icon = imagecreatefromgif($misc['icon_game']);  }
		if ($im_icon_info[2] == 2) { $im_icon = imagecreatefromjpeg($misc['icon_game']); }
		if ($im_icon_info[2] == 3) { $im_icon = imagecreatefrompng($misc['icon_game']);  }

		// GEO IP (optional)
		if (file_exists("modules/lgsl_with_img_mod/lgsl_files/geoip.inc.php")){
			$geoip = true;
			require_once "modules/lgsl_with_img_mod/lgsl_files/geoip.inc.php";
			
			$gi = geoip_open( "modules/lgsl_with_img_mod/lgsl_files/GeoIP.dat", GEOIP_STANDARD );
			$clookup = geoip_country_code_by_addr($gi, $server['b']['ip']);
			if (empty($clookup)){ $clookup = geoip_country_code_by_name($gi, $server['b']['ip']); }
			$clookup = strtolower($clookup);
			$cimg = "images/countries/{$clookup}.png";
			if (!file_exists($cimg)) { $cimg = "images/countries/noflag.png"; }
			$cimage_info = getimagesize($cimg);
			$cimage = imagecreatefrompng($cimg);
		}
	}

	//------------------------------------------------------------------------------------------------------------+
	// TEXT COLOR & FORMATING. PLAY WITH IT!
	// WE USE 2 FONTS HERE, FIRST IS FOR THE HEADING/SERVERNAME (UTF-8), SECOND IS FOR THE CONTENT SUCH AS CURRENT MAP
	$text_font0 = "modules/lgsl_with_img_mod/lgsl_files/image/_font/Cyberbas.ttf";

	$size0 = 10; //Normal
	$size2 = 12; //Small
	$size4 = 9; //Sky

	$text_font1 = "modules/lgsl_with_img_mod/lgsl_files/image/_font/Sansation_Regular.ttf";

	$size1 = 10; //Normal
	$size3 = 10; //Small
	$size5 = 9; //Sky
		
	// TEXT SETTINGS    	
	if (file_exists("modules/lgsl_with_img_mod/lgsl_files/image/color_settings.php")){
		include_once "modules/lgsl_with_img_mod/lgsl_files/image/color_settings.php";
	}

	// Fallback/default Textcolor
	if(!$text_color0){
		$text_color0 = ImageColorAllocate($im,245,250,1);
	}
	if(!$text_color1){
		$text_color1 = ImageColorAllocate($im,1,250,1);
	}

	// Fallback/default for the Text outline
	if (!$txt_outline){
		switch($type){
			case "small":
				$txt_outline = false;
			break;
			case "normal":
				$txt_outline = false;
			break;
			case "sky":
				$txt_outline = false;
			break;
		}
	}
	 
	//------------------------------------------------------------------------------------------------------------+   

	//------------------------------------------------------------------------------------------------------------+    
	// LOCATIONS/TYPES

	switch($type){
		case "normal":
			if ($geoip){ imagecopyresampled($im, $cimage, 245, 35, 0, 0, 16, 11, $cimage_info[0], $cimage_info[1]); } // Country  
			pretty_text_ttf($im,$size4,0,2,15,$text_color1,$text_font0,substr($string0,0,60), $txt_outline); // Servername
			pretty_text_ttf($im,$size4,0,65,45,$text_color0,$text_font1,$string1, $txt_outline); // IP:PORT
			pretty_text_ttf($im,$size1,0,65,63,$text_color0,$text_font1,$string2, $txt_outline); // Map
			pretty_text_ttf($im,$size1,0,292,45,$text_color0,$text_font1,$string3, $txt_outline); // Players
			pretty_text_ttf($im,$size1,0,293,63,$text_color0,$text_font1,$string4, $txt_outline); // Status
		break;
		
		case "small":
			if ($geoip){ imagecopyresampled($im, $cimage, 315, 1, 0, 0, 16, 11, $cimage_info[0], $cimage_info[1]); } // Country
			pretty_text_ttf($im,$size0,0,2,10,$text_color1,$text_font0,substr($string0,0,45), $txt_outline); // Servername
			pretty_text_ttf($im,$size3,0,2,24,$text_color0,$text_font1,substr($string1,0,20), $txt_outline); // IP:Port
			pretty_text_ttf($im,$size3,0,145,24,$text_color0,$text_font1,$string2, $txt_outline); // Map
			pretty_text_ttf($im,$size3,0,255,24,$text_color0,$text_font1,$string3, $txt_outline); // Players
			pretty_text_ttf($im,$size3,0,295,24,$text_color0,$text_font1,$string4, $txt_outline); // Status
		break;
		
		case "sky":
		// DEFINE
			$im_map_width  = 160;
			$im_map_height = 120;
			$im_map_posx   = 10;
			$im_map_posy   = 125;

			$im_icon_width  = 16;
			$im_icon_height = 16;
			$im_icon_posx   = 16;
			$im_icon_posy   = 127;

			imagecopyresampled($im, $im_map, $im_map_posx, $im_map_posy, 0, 0, $im_map_width, $im_map_height, $im_map_info[0], $im_map_info[1]); // Mapimage
			if($server['disabled'] == 0){
				imagecopyresampled($im, $im_icon, $im_icon_posx, $im_icon_posy, 0, 0, $im_icon_width, $im_icon_height, $im_icon_info[0], $im_icon_info[1]); // Gameicon
				if ($geoip){ imagecopyresampled($im, $cimage, $im_icon_posx + 132, $im_icon_posy, 0, 0, 16, 11, $cimage_info[0], $cimage_info[1]); } // Country
			}
			if($name_type_vertical){ pretty_text_ttf($im,$size4,270,5,20,$text_color1,$text_font0,substr($string0,0,28), $txt_outline); } // Servername Vertical
			else{ pretty_text_ttf($im,$size4,0,10,15,$text_color1,$text_font0,substr($string0,0,26), $txt_outline); } // Servername
			pretty_text_ttf($im,$size5,0,5,30,$text_color0,$text_font1,"IP:Port:  ".substr($string1,0,20), $txt_outline); // IP:Port
			pretty_text_ttf($im,$size5,0,5,52,$text_color0,$text_font1,"Map    :  ".substr($string2,0,20), $txt_outline); // Map
			pretty_text_ttf($im,$size5,0,5,74,$text_color0,$text_font1,"Players:  ".$string3, $txt_outline); // Players
			pretty_text_ttf($im,$size5,0,5,96,$text_color0,$text_font1,"Status :  ".substr($string4,0,20), $txt_outline); // Status
		break;

	// WHATEVER HAPPENS, ALWAYS PRINT SOMETHING & HOPE THAT AT LEAST THE DEFAULT IMAGE EXISTS...
		default:
			$text_color0 = ImageColorAllocate($im,0,0,0);
			imagettftext($im,11,0,10,14,$text_color0,$text_font0,$string0);
			imagettftext($im,10,0,10,34,$text_color0,$text_font1,$string1);
			imagettftext($im,10,0,10,54,$text_color0,$text_font1,$string2);
			imagettftext($im,10,0,10,74,$text_color0,$text_font1,$string3);
			imagettftext($im,10,0,10,94,$text_color0,$text_font1,$string4);
		break;
	}

	//------------------------------------------------------------------------------------------------------------+

	//------------------------------------------------------------------------------------------------------------+
	// NOW LET THE MAGIC HAPPEN AND PULL ALL THAT INTO AN IMAGE.
	make_img($im, $cache_enable, $cache);
}
?>
