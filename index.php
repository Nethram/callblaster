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

require_once ('config.php');

if($_POST['action']=="Update Config")
{
	$content = "[callblaster]\n"."interval=".$_POST['interval']."\n[press1]\n"."context=".$_POST['context1']."\n"."extension=".$_POST['exten1']."\n";
	$content.= "[press2]\n"."context=".$_POST['context2']."\n"."extension=".$_POST['exten2']."\n";
	
	file_put_contents("config.ini",$content);
	
}

$config = parse_ini_file("config.ini",true);
$interval = $config['callblaster']['interval'];
$context1 = $config['press1']['context'];
$exten1 = $config['press1']['extension'];
$context2 = $config['press2']['context'];
$exten2 = $config['press2']['extension'];
?>
<html>
	<head>
		<title>Call Blaster</title>
	</head>
	<body>
		<center><h2>Call Blaster Management Panel</h2></center>
		<h3>Config</h3>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script>
$(document).ready(function(){
	$("#pause-btn").click(function(){
		var act=$("#pause-btn").val();
		var chng='Start';
		var chngval='start';
	  	$.post("control.php",{action:act},function(data){
	  		if(act=='start'){
	  			chngval='pause';
	  			chng='Pause';
	  		}
	  	$("#pause-btn").val(chngval);
	  	$("#pause-btn").html(chng);
		});
	})
	
	$("#stop-btn").click(function(){
	  $.post("control.php",{action:'stop'},function(data){
	  	$("#pause-btn").attr('disabled','disabled');
	  	$("#stop-btn").attr('disabled','disabled');
		alert("Call blasting Stopped");
		});
	})
	
	
})

	
	
</script>
<div style="float: right">
<button id="pause-btn" value="pause">Pause</button><button id="stop-btn">Stop</button>
</div>		
		<form method="post" action="index.php">
			<table>
			<tr>
				<td>Call Interval :</td><td> <input type="text" name="interval" value="<?php echo $interval; ?>"/></td>
			</tr>
			<tr>
				<td>Redirect on Press 1 : </td>
			</tr>
			<tr>
				<td>Context : <input type="text" name="context1" value="<?php echo $context1; ?>"/></td><td>Extension : <input type="text" name="exten1" value="<?php echo $exten1; ?>"/></td>
			</tr>
			<tr><td>Redirect on press 2 : </td></tr>
			<tr>
				<td>Context : <input type="text" name="context2" value="<?php echo $context2; ?>" /></td><td>Extension : <input type="text" name="exten2" value="<?php echo $exten2; ?>"/></td>
			</tr>
			<tr>
				<td><input type="submit" name="action" value="Update Config" /></td>
			</tr>
			</table>
		</form>
		<div style="border-style:double;"></div>
		<h3>Upload and make Calls</h3>
		<form method="post" action="performCalls.php" enctype="multipart/form-data">
			
			CSV File : <input type="file" name="csvFile" /><br><br>
			<b>File should not contain spaces</b><br><br>
			<input type="submit" name="action" value="Upload and Initiate Calls" />
			
		
		</form>
		<div style="border-style:double;"></div>
		<h3>Call Logs</h3>
		<?php list_logs(); ?>
		<div style="border-style:double;"></div>

		<h3>Audio Files</h3>
		<a href="audioFile.php">View/Manage Audio Files</a>

	</body>

</html>
<?php

function list_logs()
{
  exec("ls -t files/",$files);
  echo("<table>");
  foreach($files as $file)
  {
    if(preg_match("/csv$/",$file))
    {
      $size     = filesize("files/$file")/1024;
      $filedate = date ("m/d/Y H:i:s", filemtime("files/$file"));
	$link="downloadLog.php?file=".urlencode($basepath."/files/$file");
      echo "<tr> <td> <a href='$link'>".$file."</a> </td> <td> $filedate </td> <td> $size kB</td>";
    }
  }
  echo("</table>");
}



?>
