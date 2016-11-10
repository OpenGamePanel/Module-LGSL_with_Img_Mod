<?php

 /*----------------------------------------------------------------------------------------------------------\
 |                                                                                                            |
 |                      [ LIVE GAME SERVER LIST ] [ © RICHARD PERRY FROM GREYCUBE.COM ]                       |
 |                                                                                                            |
 |    Released under the terms and conditions of the GNU General Public License Version 3 (http://gnu.org)    |
 |                                                                                                            |
 \-----------------------------------------------------------------------------------------------------------*/

//------------------------------------------------------------------------------------------------------------+

  if (!defined("LGSL_ADMIN")) { exit("DIRECT ACCESS ADMIN FILE NOT ALLOWED"); }

  require "lgsl_class.php";

  global $db;
  
  $lgsl_type_list     = lgsl_type_list(); asort($lgsl_type_list);
  $lgsl_protocol_list = lgsl_protocol_list();

  $id        = 0;
  $last_type = "source";
  $zone_list = array(0,1,2,3,4,5,6,7,8);

//------------------------------------------------------------------------------------------------------------+

  if (!function_exists("fsockopen") && !$lgsl_config['feed']['method'])
  {
    if ((function_exists("curl_init") && function_exists("curl_setopt") && function_exists("curl_exec")))
    {
      $output = "<div style='text-align:center'><br /><br /><b>FSOCKOPEN IS DISABLED - YOU MUST ENABLE THE FEED OPTION</b><br /><br /></div>".lgsl_help_info(); return;
    }
    else
    {
      $output = "<div style='text-align:center'><br /><br /><b>FSOCKOPEN AND CURL ARE DISABLED - LGSL WILL NOT WORK ON THIS HOST</b><br /><br /></div>".lgsl_help_info(); return;
    }
  }

//------------------------------------------------------------------------------------------------------------+

  if ($_POST && get_magic_quotes_gpc()) { $_POST = lgsl_stripslashes_deep($_POST); }

//------------------------------------------------------------------------------------------------------------+

  if (!empty($_POST['lgsl_save_1']) || !empty($_POST['lgsl_save_2']))
  {
    if (!empty($_POST['lgsl_save_1']))
    {
      // LOAD SERVER CACHE INTO MEMORY
      $results = $db->resultQuery("SELECT * FROM `OGP_DB_PREFIXlgsl`");
      foreach($results as $row)
      {
        $servers["{$row['type']}:{$row['ip']}:{$row['q_port']}"] = array($row['status'], $row['cache'], $row['cache_time']);
      }
    }

    // EMPTY SQL TABLE
    $db->query("TRUNCATE `OGP_DB_PREFIXlgsl`");

    // CONVERT ADVANCED TO NORMAL DATA FORMAT
    if (!empty($_POST['lgsl_management']))
    {
      $form_lines = explode("\r\n", trim($_POST['form_list']));

      foreach ($form_lines as $form_key => $form_line)
      {
        list($_POST['form_type']    [$form_key],
             $_POST['form_ip']      [$form_key],
             $_POST['form_c_port']  [$form_key],
             $_POST['form_q_port']  [$form_key],
             $_POST['form_s_port']  [$form_key],
             $_POST['form_zone']    [$form_key],
             $_POST['form_disabled'][$form_key],
             $_POST['form_comment'] [$form_key]) = explode(":", "{$form_line}:::::::");
      }
    }

    foreach ($_POST['form_type'] as $form_key => $not_used)
    {
      // COMMENTS LEFT IN THEIR NATIVE ENCODING WITH JUST HTML SPECIAL CHARACTERS CONVERTED
      $_POST['form_comment'][$form_key] = lgsl_htmlspecialchars($_POST['form_comment'][$form_key]);

      $type       = $db->real_escape_string(               strtolower(trim($_POST['form_type']    [$form_key])));
      $ip         = $db->real_escape_string(                          trim($_POST['form_ip']      [$form_key]));
      $c_port     = $db->real_escape_string(                   intval(trim($_POST['form_c_port']  [$form_key])));
      $q_port     = $db->real_escape_string(                   intval(trim($_POST['form_q_port']  [$form_key])));
      $s_port     = $db->real_escape_string(                   intval(trim($_POST['form_s_port']  [$form_key])));
      $zone       = $db->real_escape_string(                          trim($_POST['form_zone']    [$form_key]));
      $disabled   = isset($_POST['form_disabled'][$form_key])? intval(trim($_POST['form_disabled'][$form_key])) : "0";
      $comment    = $db->real_escape_string(                          trim($_POST['form_comment'] [$form_key]));

      // CACHE INDEXED BY TYPE:IP:Q_PORT SO IF THEY CHANGE THE CACHE IS IGNORED
      list($status, $cache, $cache_time) = isset($servers["{$type}:{$ip}:{$q_port}"]) ? $servers["{$type}:{$ip}:{$q_port}"] : array("0", "", "");

      $status     = $db->real_escape_string($status);
      $cache      = $db->real_escape_string($cache);
      $cache_time = $db->real_escape_string($cache_time);

      // THIS PREVENTS PORTS OR WHITESPACE BEING PUT IN THE IP WHILE ALLOWING IPv6
      if     (preg_match("/(\[[0-9a-z\:]+\])/iU", $ip, $match)) { $ip = $match[1]; }
      elseif (preg_match("/([0-9a-z\.\-]+)/i", $ip, $match))    { $ip = $match[1]; }

      list($c_port, $q_port, $s_port) = lgsl_port_conversion($type, $c_port, $q_port, $s_port);

      // DISCARD SERVERS WITH AN EMPTY IP AND AUTO DISABLE SERVERS WITH SOMETHING WRONG
      if     (!$ip)                               { continue; }
      elseif ($c_port < 1 || $c_port > 99999)     { $disabled = 1; $c_port = 0; }
      elseif ($q_port < 1 || $q_port > 99999)     { $disabled = 1; $q_port = 0; }
      elseif (!isset($lgsl_protocol_list[$type])) { $disabled = 1; }

      $db->query("INSERT INTO `OGP_DB_PREFIXlgsl` (`type`,`ip`,`c_port`,`q_port`,`s_port`,`zone`,`disabled`,`comment`,`status`,`cache`,`cache_time`) VALUES ('{$type}','{$ip}','{$c_port}','{$q_port}','{$s_port}','{$zone}','{$disabled}','{$comment}','{$status}','{$cache}','{$cache_time}')");
    }
  }

//------------------------------------------------------------------------------------------------------------+

  if (!empty($_POST['lgsl_map_image_paths']))
  {
    $server_list = lgsl_query_cached_all("s");

    foreach ($server_list as $server)
    {
      if (!$server['b']['status']) { continue; }

      $image_map = lgsl_image_map($server['b']['status'], $server['b']['type'], $server['s']['game'], $server['s']['map'], FALSE);

      $output .= "
      <div>
        <a href='{$image_map}'> {$image_map} </a>
      </div>";
    }

    $output .= "
    <form method='post' action=''>
      <div>
        <br />
        <br />
        <input type='hidden' name='lgsl_management' value='{$_POST['lgsl_management']}' />
        <input type='submit' name='lgsl_return' value='RETURN TO ADMIN' />
        <br />
        <br />
      </div>
    </form>";

    return;
  }

//------------------------------------------------------------------------------------------------------------+

  if ((!empty($_POST['lgsl_management']) && empty($_POST['lgsl_switch'])) || (empty($_POST['lgsl_management']) && !empty($_POST['lgsl_switch'])) || (!isset($_POST['lgsl_management']) && $lgsl_config['management']))
  {
    $output .= "
    <form method='post' action=''>
      <div style='text-align:center'>
        <b>TYPE : IP : C PORT : Q PORT : S PORT : ZONES : DISABLED : COMMENT</b>
        <br />
        <br />
      </div>
      <div style='text-align:center'>
        <textarea name='form_list' cols='90' rows='30' wrap='off' spellcheck='false' style='width:95%; height:500px; font-size:1.2em; font-family:courier new, monospace'>\r\n";

//---------------------------------------------------------+
        $result = $db->resultQuery("SELECT * FROM `OGP_DB_PREFIXlgsl` ORDER BY `id` ASC");

        foreach($result as $row)
        {
          $output .=
          lgsl_string_html(str_pad($row['type'],     15, " ")).":".
          lgsl_string_html(str_pad($row['ip'],       30, " ")).":".
          lgsl_string_html(str_pad($row['c_port'],   6,  " ")).":".
          lgsl_string_html(str_pad($row['q_port'],   6,  " ")).":".
          lgsl_string_html(str_pad($row['s_port'],   7,  " ")).":".
          lgsl_string_html(str_pad($row['zone'],     7,  " ")).":".
          lgsl_string_html(str_pad($row['disabled'], 2,  " ")).":".
                                   $row['comment']            ."\r\n";
        }
//---------------------------------------------------------+
        $output .= "
        </textarea>
      </div>
      <div style='text-align:center'>
        <input type='hidden' name='lgsl_management' value='1' />
        <table cellspacing='20' cellpadding='0' style='text-align:center;margin:auto'>
          <tr>
            <td><input type='submit' name='lgsl_save_1'          value='Save - Keep Cache' />  </td>
            <td><input type='submit' name='lgsl_save_2'          value='Save - Reset Cache' /> </td>
            <td><input type='submit' name='lgsl_map_image_paths' value='Map Image Paths' />    </td>
            <td><input type='submit' name='lgsl_switch'          value='Normal Management' />  </td>
          </tr>
        </table>
      </div>
    </form>";

    $output .= lgsl_help_info();

    return $output;
  }

//------------------------------------------------------------------------------------------------------------+

  $output .= "
  <form method='post' action=''>
    <div style='text-align:center; overflow:auto'>
      <table cellspacing='5' cellpadding='0' style='margin:auto'>
        <tr>
          <td style='text-align:center; white-space:nowrap'>[ ID ]             </td>
          <td style='text-align:center; white-space:nowrap'>[ Game Type ]      </td>
          <td style='text-align:center; white-space:nowrap'>[ IP ]             </td>
          <td style='text-align:center; white-space:nowrap'>[ Connection Port ]</td>
          <td style='text-align:center; white-space:nowrap'>[ Query Port ]     </td>
          <td style='text-align:center; white-space:nowrap'>[ Software Port ]  </td>
          <td style='text-align:center; white-space:nowrap'>[ Zones ]          </td>
          <td style='text-align:center; white-space:nowrap'>[ Disabled ]       </td>
          <td style='text-align:center; white-space:nowrap'>[ Comment ]        </td>
        </tr>";

//---------------------------------------------------------+

      $result = $db->resultQuery("SELECT * FROM `OGP_DB_PREFIXlgsl` ORDER BY `id` ASC");

      foreach($result as $row)
      {
        $id = $row['id']; // ID USED AS [] ONLY RETURNS TICKED CHECKBOXES

        $output .= "
        <tr>
          <td>
            <a href='".lgsl_link($id)."' style='text-decoration:none'>{$id}</a>
          </td>
          <td>
            <select name='form_type[{$id}]'>";
//---------------------------------------------------------+
            foreach ($lgsl_type_list as $type => $description)
            {
              $output .= "
              <option ".($type == $row['type'] ? "selected='selected'" : "")." value='{$type}'>{$description}</option>";
            }

            if (!isset($lgsl_type_list[$row['type']]))
            {
              $output .= "
              <option selected='selected' value='".lgsl_string_html($row['type'])."'>".lgsl_string_html($row['type'])."</option>";
            }
//---------------------------------------------------------+
            $output .= "
            </select>
          </td>
          <td style='text-align:center'><input type='text' name='form_ip[{$id}]'     value='".lgsl_string_html($row['ip'])."'     size='15' maxlength='255' /></td>
          <td style='text-align:center'><input type='text' name='form_c_port[{$id}]' value='".lgsl_string_html($row['c_port'])."' size='5'  maxlength='5'   /></td>
          <td style='text-align:center'><input type='text' name='form_q_port[{$id}]' value='".lgsl_string_html($row['q_port'])."' size='5'  maxlength='5'   /></td>
          <td style='text-align:center'><input type='text' name='form_s_port[{$id}]' value='".lgsl_string_html($row['s_port'])."' size='5'  maxlength='5'   /></td>
          <td>
            <select name='form_zone[$id]'>";
//---------------------------------------------------------+
            foreach ($zone_list as $zone)
            {
              $output .= "
              <option ".($zone == $row['zone'] ? "selected='selected'" : "")." value='{$zone}'>{$zone}</option>";
            }

            if (!isset($zone_list[$row['zone']]))
            {
              $output .= "
              <option selected='selected' value='".lgsl_string_html($row['zone'])."'>".lgsl_string_html($row['zone'])."</option>";
            }
//---------------------------------------------------------+
//---------------------------------------------------------+
            $output .= "
            </select>
          </td>
          <td style='text-align:center'><input type='checkbox' name='form_disabled[{$id}]' value='1' ".(empty($row['disabled']) ? "" : "checked='checked'")." /></td>
          <td style='text-align:center'><input type='text'     name='form_comment[{$id}]'  value='{$row['comment']}' size='20' maxlength='255' /></td>
        </tr>";

        $last_type = $row['type']; // SET LAST TYPE ( $row EXISTS ONLY WITHIN THE LOOP )
      }
//---------------------------------------------------------+
        $id ++; // NEW SERVER ID CONTINUES ON FROM LAST

        $output .= "
        <tr>
          <td>NEW</td>
          <td>
            <select name='form_type[{$id}]'>";
//---------------------------------------------------------+
            foreach ($lgsl_type_list as $type => $description)
            {
              $output .= "
              <option ".($type == $last_type ? "selected='selected'" : "")." value='{$type}'>{$description}</option>";
            }
//---------------------------------------------------------+
            $output .= "
            </select>
          </td>
          <td style='text-align:center'><input type='text' name='form_ip[{$id}]'     value=''  size='15' maxlength='255' /></td>
          <td style='text-align:center'><input type='text' name='form_c_port[{$id}]' value=''  size='5'  maxlength='5'   /></td>
          <td style='text-align:center'><input type='text' name='form_q_port[{$id}]' value=''  size='5'  maxlength='5'   /></td>
          <td style='text-align:center'><input type='text' name='form_s_port[{$id}]' value='0' size='5'  maxlength='5'   /></td>
          <td>
            <select name='form_zone[{$id}]'>";
//---------------------------------------------------------+
            foreach ($zone_list as $zone)
            {
              $output .= "
              <option value='{$zone}'>{$zone}</option>";
            }
//---------------------------------------------------------+
            $output .= "
            </select>
          </td>
          <td style='text-align:center'><input type='checkbox' name='form_disabled[{$id}]' value='' /></td>
          <td style='text-align:center'><input type='text'     name='form_comment[{$id}]'  value='' size='20' maxlength='255' /></td>
        </tr>
      </table>

      <input type='hidden' name='lgsl_management' value='0' />
      <table cellspacing='20' cellpadding='0' style='text-align:center;margin:auto'>
        <tr>
          <td><input type='submit' name='lgsl_save_1'          value='Save - Keep Cache' />  </td>
          <td><input type='submit' name='lgsl_save_2'          value='Save - Reset Cache' /> </td>
          <td><input type='submit' name='lgsl_map_image_paths' value='Map Image Paths' />    </td>
          <td><input type='submit' name='lgsl_switch'          value='Advanced Management' /></td>
        </tr>
      </table>
    </div>
  </form>";

  $output .= lgsl_help_info();

//------------------------------------------------------------------------------------------------------------+

  function lgsl_help_info()
  {
    return "
    <div style='text-align:center; line-height:1em; font-size:1em;'>
      <br /><br />
      <a href='http://www.greycube.com/help/readme/lgsl/'>[ LGSL ONLINE README ]</a>  <br /><br />
      - To remove a server, delete the IP, then click Save.                           <br /><br />
      - Leave the query port blank to have LGSL try to fill it in for you.            <br /><br />
      - Software port is only needed for a few games so it being set 0 is normal.     <br /><br />
      - Edit the lgsl_config.php to set the background colors and other options.      <br /><br />
      <table cellspacing='10' cellpadding='0' style='border:1px solid; margin:auto; text-align:left'>
        <tr>
          <td> <a href='http://php.net/fsockopen'>FSOCKOPEN</a>           </td>
          <td> Enabled: ".(function_exists("fsockopen") ? "YES" : "NO")." </td>
          <td> ( Required for direct querying of servers )                </td>
        </tr>
        <tr>
          <td> <a href='http://php.net/curl'>CURL</a>                                                                                         </td>
          <td> Enabled: ".((function_exists("curl_init") && function_exists("curl_setopt") && function_exists("curl_exec")) ? "YES" : "NO")." </td>
          <td> ( Used for the feed when fsockopen is disabled )                                                                               </td>
        </tr>
        <tr>
          <td> <a href='http://php.net/mbstring'>MBSTRING</a>                       </td>
          <td> Enabled: ".(function_exists("mb_convert_encoding") ? "YES" : "NO")." </td>
          <td> ( Used to show UTF-8 server and player names correctly )             </td>
        </tr>
        <tr>
          <td> <a href='http://php.net/bzip2'>BZIP2</a>                      </td>
          <td> Enabled: ".(function_exists("bzdecompress") ? "YES" : "NO")." </td>
          <td> ( Used to show Source server settings over a certain size )   </td>
        </tr>
        <tr>
          <td> <a href='http://php.net/zlib'>ZLIB</a>                        </td>
          <td> Enabled: ".(function_exists("gzuncompress") ? "YES" : "NO")." </td>
          <td> ( Required for America's Army 3 )                             </td>
        </tr>
      </table>
      <br /><br />
      <br /><br />
    </div>";
  }

//------------------------------------------------------------------------------------------------------------+

  function lgsl_stripslashes_deep($value)
  {
    $value = is_array($value) ? array_map('lgsl_stripslashes_deep', $value) : stripslashes($value);
    return $value;
  }

//------------------------------------------------------------------------------------------------------------+

  function lgsl_htmlspecialchars($string)
  {
    // PHP4 COMPATIBLE WAY OF CONVERTING SPECIAL CHARACTERS WITHOUT DOUBLE ENCODING EXISTING ENTITIES
    $string = str_replace("\x05\x06", "", $string);
    $string = preg_replace("/&([a-z\d]{2,7}|#\d{2,5});/i", "\x05\x06$1", $string);
    $string = htmlspecialchars($string, ENT_QUOTES);
    $string = str_replace("\x05\x06", "&", $string);

    return $string;
  }

//------------------------------------------------------------------------------------------------------------+
