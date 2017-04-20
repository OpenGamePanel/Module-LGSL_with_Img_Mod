<?php
/*
 *
 * OGP - Open Game Panel
 * Copyright (C) 2008 - 2016 The OGP Development Team
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

define('module_name', "Utilities");
define('ping', "Ping");
define('traceroute', "Traceroute");
define('network_tools', "Network Tools");
define('sourcemod_admins', "Sourcemod Admins");
define('steam_converter', "SteamID Converter");
define('your_ip', "Your IP Address:");
define('loading_agents', "Loading Online Agents...");
define('loading_failed', "Loading Agents Failed.");
define('agents_offline', "All agents are offline.");
define('no_commands', "Sorry, your user account has no commands available.");
define('remote_target', "Target Address:");
define('command', "Command:");
define('select_agent', "Select Agent:");
define('chdir_failed', "Error: chdir() returned false.");
define('agent_invalid', "Invalid agent specified.");
define('networktools_agent_offline', "Unable to execute your command on the selected agent because it is offline.");
define('target_empty', "No remote target given.");
define('command_empty', "No command selected.");
define('command_unavilable', "The selected command is unavailable on the selected agent.");
define('target_invalid', "Invalid IP/hostname entered.");
define('exec_failed', "Timed out while waiting for a response.");
define('command_no_access', "You do not have access to this command. This incident will be logged.");
define('command_hacking_attempt', "Blacklisted characters entered. This incident will be logged.");
define('command_bad_characters', "Attempted to execute a command with malicious characters. Input received: %s %s");
define('command_no_permissions', "Attempted to execute a command with insufficient permissions. Input received: %s %s");
define('command_executed', "Successfully sent the following command: %s %s");
define('no_servers', "You have no servers assigned to your account.");
define('select_server', "Select Server:");
define('select_server_option', "Select...");
define('steamid', "Steam ID:");
define('immunity', "Immunity:");
define('sourcemod_perms', "Sourcemod Permissions:");
define('sourcemod_perm_root', "Sourcemod Root Flag");
define('sourcemod_perm_custom', "Sourcemod Custom Flags");
define('sourcemod_flag_a', "Reserved slot access.");
define('sourcemod_flag_b', "Generic admin; required for admins.");
define('sourcemod_flag_c', "Kick other players.");
define('sourcemod_flag_d', "Ban other players.");
define('sourcemod_flag_e', "Remove bans.");
define('sourcemod_flag_f', "Slay/harm other players.");
define('sourcemod_flag_g', "Change the map or major gameplay features.");
define('sourcemod_flag_h', "Change most CVARs.");
define('sourcemod_flag_i', "Execute config files.");
define('sourcemod_flag_j', "Special chat privileges.");
define('sourcemod_flag_k', "Start or create votes.");
define('sourcemod_flag_l', "Set a password on the server.");
define('sourcemod_flag_m', "Use RCON commands.");
define('sourcemod_flag_n', "Change sv_cheats or use cheating commands.");
define('sourcemod_flag_o', "Custom Group 1.");
define('sourcemod_flag_p', "Custom Group 2.");
define('sourcemod_flag_q', "Custom Group 3.");
define('sourcemod_flag_r', "Custom Group 4.");
define('sourcemod_flag_s', "Custom Group 5.");
define('sourcemod_flag_t', "Custom Group 6.");
define('rcon_reload_admins_failed', "Failed to reload the admin cache via RCON; is it online?");
define('reload_admins_failed', "Failed to reload the admin cache; \"sm_reloadadmins\" is an unknown command.");
define('reload_admins_success', "Successfully added %s to admins_simple.ini and reloaded the admin cache.");
define('add_success_no_rcon', "Successfully added %s to your admins_simple.ini file, but unable to reload the admin cache.");
define('writefile_error', "There was an unknown error writing to: %s");
define('remotefile_nonexistent', "Unable to add a new admin. Admin file: %s doesn\'t exist on this server.");
define('empty_flag_list', "You didn\'t select any admin flags.");
define('invalid_steam_format', "The SteamID you entered doesn\'t match the required pattern.");
define('selected_server_offline', "Unable to add an admin, the agent controlling the selected server is offline.");
define('malformed_form', "You submitted a form with malformed hidden elements - unable to add an admin.");
define('empty_form_data', "Please fill out all elements of the form.");
define('server_not_selected', "You haven\'t selected a server.");
define('invalid_steamid', "You have entered an invalid Steam ID.");
define('invalid_immunity', "You entered an invalid immunity value.");
define('submit', "Submit");
define('post_failed', "The POST action failed. Unable to retrieve a response.");
?>