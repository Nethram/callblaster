<?php 

if(isset($_POST['action'])){
	$condition=$_POST['action'];
	if($condition=="pause"){
		$reset_controls="pause=true\nstop=false";
		file_put_contents('control.ini', $reset_controls);
		$controls=parse_ini_file('control.ini');
		print_r($controls);
	}
	if($condition=="start"){
		$reset_controls="pause=false\nstop=false";
		file_put_contents('control.ini', $reset_controls);
		$controls=parse_ini_file('control.ini');
		print_r($controls);
	}
	if($condition=="stop"){
		$reset_controls="pause=false\nstop=true";
		file_put_contents('control.ini', $reset_controls);
		$controls=parse_ini_file('control.ini');
		print_r($controls);
	}
	
	
}

