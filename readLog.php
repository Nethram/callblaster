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


require_once("connection.php");

if($_REQUEST['action']=="getLog")
{

	$file = trim(urldecode($_REQUEST['file']));
	
	$query = "select * from logs where csvFile='$file' and type='heading'";

	$result = mysql_query($query);
	
	$ret='<table cellspacing="5" cellpadding="5"><thead>';

	if($result and mysql_num_rows($result)>0)
	{
		$row = mysql_fetch_assoc($result);
		$head = explode(",",$row['fields']);
		
		for($i=0;$i<count($head);$i++)
		$ret.="<th>".$head[$i]."</th>";
		
		$ret.="<th>Time</th><th>Status</th><th>Option Choosen</th>";
	}
	
	$ret.="</thead>";
	
	$query = "select * from logs where csvFile='$file' and type='field' and status!='Completed' and time>DATE_SUB(NOW(),INTERVAL 5 MINUTE)";
	$result = mysql_query($query);
	
	if($result and mysql_num_rows($result)>0)
	{
		for($i=0;$i<mysql_num_rows($result);$i++)
		{
			$row=mysql_fetch_assoc($result);
			$fields = explode(",",$row['fields']);
			$ret.="<tr align='center'>";
			for($j=0;$j<count($fields);$j++)
			{
				$ret.="<td>".$fields[$j]."</td>";
			}
			$ret.="<td>".$row['time']."</td><td>".$row['status']."</td><td>".$row['options']."</td>";
			
			$ret.="</tr>";
		}
	}
	$ret.="</table>";
	echo $ret;
	
	
	
	
	

}




?>
