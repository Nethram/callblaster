<?php
/**
* @file
*
* All Callblaster code is released under the GNU General Public License.
* See COPYRIGHT.txt and LICENSE.txt.
*
*....................
* www.nethram.com
*/

//Database configuration
//.............................................
$db_host="localhost";
$db_name="callblaster";
$db_user="root";
$db_pass="passw0rd";
//.............................................



//paths
//..............................................

$basepath="/var/www/html/callblastergpl/";

$agipath="/var/lib/asterisk/agi-bin/";

$welcomeSound = $basepath."audio/testmenu";
//sound file without extension
//..............................................



//agi configurations
$config = parse_ini_file("config.ini",true);
$exten_1=$config['press1']['extension'];
$context_1=$config['press1']['context'];
$priority_1="1";


$exten_2=$config['press2']['extension'];
$context_2=$config['press2']['context'];
$priority_2="1";

?>
