<?php
/*
 *
 * OGP - Open Game Panel
 * Copyright (C) 2008 - 2010 The OGP Development Team
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

function exec_ogp_module()
{
	echo "<h2>LGSL</h2>";
	global $output, $lgsl_server_id;

	$output = "";

	$s = isset($_GET['s']) ? $_GET['s'] : "";

	if     (is_numeric($s)) { $lgsl_server_id = $s; require "lgsl_files/lgsl_details.php"; }
	elseif ($s == "add")    {                       require "lgsl_files/lgsl_add.php";     }
	else                    {                       require "lgsl_files/lgsl_list.php";    }

	echo $output;

	unset($output);
}
?>