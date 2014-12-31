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

require_once'config.php';

$connection = mysql_connect($db_host,$db_user,$db_pass) or die("ERROR connecting to database");
mysql_select_db($db_name) or die("Error connecting to database");

$query="CREATE TABLE IF NOT EXISTS `logs` (
  `autoID` int(11) NOT NULL AUTO_INCREMENT,
  `fields` blob NOT NULL,
  `time` datetime NOT NULL,
  `status` text NOT NULL,
  `options` text NOT NULL,
  `type` text NOT NULL,
  `csvFile` text NOT NULL,
  PRIMARY KEY (`autoID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=63089 ;
";

mysql_query($query);


?>
	