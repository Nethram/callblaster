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

?>

<html>
<?php
//$basepath="/var/www/html/callblastergpl/";

require_once('connection.php');
$config = parse_ini_file("config.ini",true);
$interval = $config['callblaster']['interval'];

if($_POST['action']=="Upload and Initiate Calls")
{
	if(!isset($_FILES['csvFile']) or $_FILES['csvFile']['error']>0)
	{
		echo "File upload error : ".$_FILES['csvFile']['error'];
	}
	else
	{
		$ts=time();
		$dest = $basepath."files/".$ts.$_FILES['csvFile']['name'];
		move_uploaded_file($_FILES['csvFile']['tmp_name'],$dest);
		
		$msg = "Recieved File $dest at ".date("r",time());
		file_put_contents("logs/uploads.txt",$msg,FILE_APPEND);
		
		
		
		$csv = array();
		$lines = file($dest, FILE_IGNORE_NEW_LINES);
		
		foreach ($lines as $key => $value)
		{
		    $csv[$key] = str_getcsv($value);
		}
		
		$audioIndex = count($csv[0])-2; 
		$phoneIndex = count($csv[0])-1;
		$itemCount = count($csv,0);
		$fields=implode(",",$csv[0]);
		$query = "insert into logs(fields,time,status,options,type,csvFile) values('$fields',NOW(),'upload','Nil','heading','$dest')";
		$result = mysql_query($query) or die("Database Error");

		
		
		echo "Records Found : ".($itemCount-1)."<br>";
		for($i=1;$i<=$itemCount-1;$i++)
		{
			$config = parse_ini_file("config.ini",true);
			$interval = $config['callblaster']['interval'];
			$number = $csv[$i][$phoneIndex];
			$audio = $csv[$i][$audioIndex];
			$fields = implode(",",$csv[$i]);
			$query = "insert into logs(fields,time,status,options,type,csvFile) values('$fields',NOW(),'Dialling','Nil','field','$dest')";
			$result = mysql_query($query) or die("Database Error");
			$id = mysql_insert_id();
			$phone = "1".$number;
			$callFile = "Channel: SIP/Sonetel/$phone\n";
			$callFile .= "MaxRetries: 2\n";
			$callFile .= "WaitTime: 30\n";
			$callFile .= "Context: callblaster\n";
			$callFile .= "Extension: 333\n";
			$callFile .= "Set: userAudio=$audio\n";
			$callFile .= "Set: userNumber=$number\n";
			$callFile .= "Set: dbid=$id\n";
			$callFileName = $number."_".time().".call";
			file_put_contents("/tmp/$callFileName",$callFile);
			$time=date("c",time());
			try
			{
				exec("mv /tmp/$callFileName /var/spool/asterisk/outgoing/$callFileName");
				$msg = $time." -- Call file to 1".$number." created -- CSV file: $dest\n";
				$status="Dialled";
			}
			catch(Exception $e)
			{
				$msg=$time." -- ERROR:".$e->getMessage()." -- CSV file : $dest\n";
				$status="Dial Failed";
			}
			
			$query = "update logs set status='$status', time=NOW() where autoID='$id'";
			$result = mysql_query($query) or die("Database Error");
			file_put_contents("logs/callLog.txt",$msg,FILE_APPEND);

			sleep($interval);
		}
				
	}
}

?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">
function updateLogger(file)
{
	
	$.post("readLog.php",{action:"getLog",file:file},function(data,status){
		
		
		$('#logger').html(data);
	});
	

}

$(document).ready(function(){

	var t = setInterval(function(){updateLogger("<?php echo urlencode($dest); ?>");},1000);
});

</script>
<center>
<h2>Dialling Screen</h2>
<h4><a href="index.php">Back to Home Page</a></h4></center>
<div style="border-style:double" id="logger"></div>
</html>
