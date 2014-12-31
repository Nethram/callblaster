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
	<h2>Demo Call</h2>

<?php
require_once 'config.php';
if($_REQUEST['action']=='Get Demo Call')
{

	$file = $_REQUEST['file'];
	
?>
	<form method="post" action="demoCall.php">
		
		<input type="hidden" name="file" value="<?php echo $file; ?>"/>
		<input type="text" name="phone" placeholder="Enter phone number without + with country code Eg: 11234567890" size="80" /><br><br>
		<input type="submit" name="action" value="Call" />
	
	</form>

<?php
}
if($_REQUEST['action']=="Call")
{
	$phone=$_REQUEST['phone'];
	$file = $basepath."audio/".$_REQUEST['file'];
	
	 $exten = pathinfo($file);
	 $exten = '.'.$exten['extension'];
	                 
	 $fileName= $basepath."audio/".basename($file, $exten);
	                                 
	
	$callFile = "Channel: SIP/Sonetel/$phone\n";
	$callFile .= "Application: Playback\n";
	$callFile .= "Data: $fileName\n";
	file_put_contents("/tmp/demoCall.call",$callFile);
	exec("mv /tmp/demoCall.call /var/spool/asterisk/outgoing/demoCall.call");
	echo "<script type='text/javascript'>alert('Call initiated'); window.location='audioFile.php';</script>";
	
}



?>
</html>
