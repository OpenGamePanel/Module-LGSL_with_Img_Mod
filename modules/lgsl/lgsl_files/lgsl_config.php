<?php
//------------------------------------------------------------------------------------------------------------+
//[ PREPARE CONFIG - DO NOT CHANGE OR MOVE THIS ]

  global $lgsl_config; $lgsl_config = array();

//------------------------------------------------------------------------------------------------------------+
//[ BACKGROUND COLORS: TEXT HARD TO READ ? CHANGE THESE TO CONTRAST THE FONT COLOR / www.colorpicker.com ]

  $lgsl_config['background'][1] = "background-color:#e4eaf2";
  $lgsl_config['background'][2] = "background-color:#f4f7fa";

//------------------------------------------------------------------------------------------------------------+
//[ SHOW LOCATION FLAGS: 0=OFF 1=GEO-IP "GB"=MANUALLY SET COUNTRY CODE FOR SPEED ]

  $lgsl_config['locations'] = 1;

//------------------------------------------------------------------------------------------------------------+
//[ SHOW TOTAL SERVERS AND PLAYERS AT BOTTOM OF LIST: 0=OFF 1=ON ]

  $lgsl_config['list']['totals'] = 1;

//------------------------------------------------------------------------------------------------------------+
//[ SORTING OPTIONS ]

  $lgsl_config['sort']['servers'] = "id";   // OPTIONS: id  type  zone  players  status
  $lgsl_config['sort']['players'] = "name"; // OPTIONS: name  score

//------------------------------------------------------------------------------------------------------------+
// [ HIDE OFFLINE SERVERS: 0=SHOW 1=HIDE

  $lgsl_config['hide_offline'][0] = 0;
  $lgsl_config['hide_offline'][1] = 0;
  $lgsl_config['hide_offline'][2] = 0;
  $lgsl_config['hide_offline'][3] = 0;
  $lgsl_config['hide_offline'][4] = 0;
  $lgsl_config['hide_offline'][5] = 0;
  $lgsl_config['hide_offline'][6] = 0;
  $lgsl_config['hide_offline'][7] = 0;
  $lgsl_config['hide_offline'][8] = 0;

//------------------------------------------------------------------------------------------------------------+
//[ HOSTING FIXES ]

  $lgsl_config['direct_index'] = 0;  // 1=link to index.php instead of the folder
  $lgsl_config['no_realpath']  = 0;  // 1=do not use the realpath function
  $lgsl_config['url_path']     = ""; // full url to /lgsl_files/ for when auto detection fails

//------------------------------------------------------------------------------------------------------------+
//[ ADVANCED SETTINGS ]

  $lgsl_config['management']    = 0;         // 1=show advanced management in the admin by default
  $lgsl_config['host_to_ip']    = 0;         // 1=show the servers ip instead of its hostname
  $lgsl_config['public_add']    = 2;         // 1=servers require approval OR 2=servers shown instantly
  $lgsl_config['public_feed']   = 1;         // 1=feed requests can add new servers to your list
  $lgsl_config['cache_time']    = 1;        // seconds=time before a server needs updating
  $lgsl_config['live_time']     = 5;         // seconds=time allowed for updating servers per page load
  $lgsl_config['timeout']       = 0;         // 1=gives more time for servers to respond but adds loading delay
  $lgsl_config['retry_offline'] = 0;         // 1=repeats query when there is no response but adds loading delay
  $lgsl_config['cms']           = "sa";      // sets which CMS specific code to use

//------------------------------------------------------------------------------------------------------------+
//[ TRANSLATION ]
	if( isset($_SESSION['users_lang']) and $_SESSION['users_lang'] != "-" )
		define("LANG", $_SESSION['users_lang']);
	elseif( isset($GLOBALS['panel_language']) )
		define("LANG",$GLOBALS['panel_language']);
	else
		define("LANG","English");
	
	if( LANG == "Spanish" )
	{
		// SPANISH
		$lgsl_config['text']['vsd'] = "PULSE AQUI PARA VER LOS DETALLES DEL SERVIDOR";
		$lgsl_config['text']['slk'] = "CONECTAR AL SERVIDOR";
		$lgsl_config['text']['sts'] = "Estado:";
		$lgsl_config['text']['adr'] = "Dirección:";
		$lgsl_config['text']['cpt'] = "Puerto:";
		$lgsl_config['text']['qpt'] = "Peticiones:";
		$lgsl_config['text']['typ'] = "Tipo:";
		$lgsl_config['text']['gme'] = "Juego:";
		$lgsl_config['text']['map'] = "Mapa:";
		$lgsl_config['text']['plr'] = "Jugadores:";
		$lgsl_config['text']['npi'] = "NO HAY INFO DE JUGADORES";
		$lgsl_config['text']['nei'] = "NO HAY INFO EXTRA";
		$lgsl_config['text']['ehs'] = "Configuración";
		$lgsl_config['text']['ehv'] = "Valor";
		$lgsl_config['text']['onl'] = "EN LINEA";
		$lgsl_config['text']['onp'] = "EN LINEA CON CONTRASEÑA";
		$lgsl_config['text']['nrs'] = "NO HAY RESPUESTA";
		$lgsl_config['text']['pen'] = "PETICION EN CURSO";
		$lgsl_config['text']['zpl'] = "JUGADORES:";
		$lgsl_config['text']['mid'] = "IDENTIFICADOR INVALIDO";
		$lgsl_config['text']['nnm'] = "--";
		$lgsl_config['text']['nmp'] = "--";
		$lgsl_config['text']['tns'] = "Servidores:";
		$lgsl_config['text']['tnp'] = "Jugadores:";
		$lgsl_config['text']['tmp'] = "Max Jugadores:";
		$lgsl_config['text']['asd'] = "LA ADICION DE SERVIDORES PUBLICA ESTA DEHABILITADA";
		$lgsl_config['text']['awm'] = "ESTE AREA TE PERMITE PROBAR Y AÑADIR SERVIDORES DE JUEGOS A LA LISTA";
		$lgsl_config['text']['ats'] = "Probar servidor";
		$lgsl_config['text']['aaa'] = "YA SE AÑADIO EL SERVIDOR Y ESTA EN ESPERA DE SER APROBADO";
		$lgsl_config['text']['aan'] = "YA SE AÑADIO EL SERVIDOR";
		$lgsl_config['text']['anr'] = "NO RESPONDE - ASEGURESE DE HABER INTRODUCIDO LA INFORMACION CORRECTA";
		$lgsl_config['text']['ada'] = "EL SERVIDOR SE AÑADIO POR APROVACION DE UN ADMINISTRADOR";
		$lgsl_config['text']['adn'] = "SERVIDOR AÑADIDO";
		$lgsl_config['text']['asc'] = "CORRECTO - PORFAVOR CONFIRME EL SERVIDOR";
		$lgsl_config['text']['aas'] = "Añadir Servidor";
		$lgsl_config['text']['loc'] = "Hubicación:";
	}
	else
	{
		// ENGLISH
		$lgsl_config['text']['vsd'] = "CLICK TO VIEW SERVER DETAILS";
		$lgsl_config['text']['slk'] = "GAME LINK";
		$lgsl_config['text']['sts'] = "Status:";
		$lgsl_config['text']['adr'] = "Address:";
		$lgsl_config['text']['cpt'] = "Connection Port:";
		$lgsl_config['text']['qpt'] = "Query Port:";
		$lgsl_config['text']['typ'] = "Type:";
		$lgsl_config['text']['gme'] = "Game:";
		$lgsl_config['text']['map'] = "Map:";
		$lgsl_config['text']['plr'] = "Players:";
		$lgsl_config['text']['npi'] = "NO PLAYER INFO";
		$lgsl_config['text']['nei'] = "NO EXTRA INFO";
		$lgsl_config['text']['ehs'] = "Setting";
		$lgsl_config['text']['ehv'] = "Value";
		$lgsl_config['text']['onl'] = "ONLINE";
		$lgsl_config['text']['onp'] = "ONLINE WITH PASSWORD";
		$lgsl_config['text']['nrs'] = "NO RESPONSE";
		$lgsl_config['text']['pen'] = "WAITING TO BE QUERIED";
		$lgsl_config['text']['zpl'] = "PLAYERS:";
		$lgsl_config['text']['mid'] = "INVALID SERVER ID";
		$lgsl_config['text']['nnm'] = "--";
		$lgsl_config['text']['nmp'] = "--";
		$lgsl_config['text']['tns'] = "Servers:";
		$lgsl_config['text']['tnp'] = "Players:";
		$lgsl_config['text']['tmp'] = "Max Players:";
		$lgsl_config['text']['asd'] = "PUBLIC ADDING OF SERVERS IS DISABLED";
		$lgsl_config['text']['awm'] = "THIS AREA ALLOWS YOU TO TEST AND THEN ADD ONLINE GAME SERVERS TO THE LIST";
		$lgsl_config['text']['ats'] = "Test Server";
		$lgsl_config['text']['aaa'] = "SERVER ALREADY ADDED AND NEEDS ADMIN APPROVAL";
		$lgsl_config['text']['aan'] = "SERVER ALREADY ADDED";
		$lgsl_config['text']['anr'] = "NO RESPONSE - MAKE SURE YOU ENTERED THE CORRECT DETAILS";
		$lgsl_config['text']['ada'] = "SERVER HAS BEEN ADDED FOR ADMIN APPROVAL";
		$lgsl_config['text']['adn'] = "SERVER HAS BEEN ADDED";
		$lgsl_config['text']['asc'] = "SUCCESS - PLEASE CONFIRM ITS THE CORRECT SERVER";
		$lgsl_config['text']['aas'] = "Add Server";
		$lgsl_config['text']['loc'] = "Location:";
	}
//------------------------------------------------------------------------------------------------------------+
