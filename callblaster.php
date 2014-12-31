#!/usr/bin/php
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

require('connection.php');
require($agipath.'phpagi.php');
error_reporting(E_ALL);


$agi = new AGI();

$dbid = $agi->get_variable("dbid");
$dbid=$dbid['data'];
$userNumber = $agi->get_variable("userNumber");
$userNumber = $userNumber['data'];

$audio = $agi->get_variable("userAudio");

if($audio=='')
$audio=$welcomeSound;
else
$audio = $basepath."audio/".$audio['data'];

$msg = date("r",time()). " -- Call in progress -- Number:$userNumber  -- Audio:$audio\n";
file_put_contents($basepath."logs/callLog.txt",$msg,FILE_APPEND);

$query = "update logs set status='Connected' where autoID='$dbid'";
$result = mysql_query($query) or die("Database Error");

$keys="Nil";
$count=0;
do
{
	if($count>2)break;
	$result = $agi->get_data("$audio",5000,1);
	$keys = $result['result'];
	if($keys=="1" or $keys=="2") break;
	$count++;
}while(($keys!=1 and $keys!=2));


$query = "update logs set options='$keys' where autoID='$dbid'";
$result = mysql_query($query) or die("Database Error");
$msg = date("r",time()). " -- User pressed $keys -- Number:$userNumber  -- Audio:$audio\n";
file_put_contents($basepath."logs/callLog.txt",$msg,FILE_APPEND);
if($keys==1 or $keys=="1")
{
	
	$agi->exec_goto($context_1,$exten_1,$priority_1);

	$query = "update logs set status='Transferred' where autoID='$dbid'";
	$result = mysql_query($query) or die("Database Error");
}
if($keys==2 or $keys=="2")
{
	$agi->exec_goto($context_2,$exten_2,$priority_2);
	
	$query = "update logs set status='Transferred' where autoID='$dbid'";
	$result = mysql_query($query) or die("Database Error");


}

$query = "update logs set status='Completed' where autoID='$dbid'";
$result = mysql_query($query) or die("Database Error");

?>
