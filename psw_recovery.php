<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
$servername = "150.145.136.104";
$username = "HFRwebformUser";
$password = "!_e2MKonpy5paMTgR9_!";
$dbname = "HFR_node_db";

// Create connection to EU HFR node DB
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set the desired charset after establishing a connection
mysqli_set_charset($conn, 'utf8');

?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>The European HFR Node</title>

<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
<link href="/css/EU_HFR_WF_StyleSheet.css" rel="stylesheet" type="text/css">

</head>

<body class="twoColFixRtHdr">

<div id="container">
  <div id="header" style="background: url('images/header_2022.png')">
    <h1>EU HFR NODE - Data Entry Web Form</h1><br>
    Web Form for managing HFR network information
  <!-- end #header --></div>
  
  <div id="mainContent">
    <h1>Password recovery form</h1>
    <div id="Buttons">
    	<a href="https://cnrsc-my.sharepoint.com/:b:/g/personal/lorenzopaolo_corgnati_cnr_it/Ecoa9zTYUmVNiXuDxiKWslQBqwbtuG3r1RVwdI5Dy-E9zg?e=bylH89" target="_blank"><button style="height:40px; width:200px">Webform User Manual</button><a>
    		<br><br>
    	<a href="index.php"><button style="height:40px; width:200px">Back to homepage</button><a>
    		<br><br>
  	</div>
    <div id="psw_recovery_form">
    	<?php
				 
		// valorizzazione delle variabili con i parametri dal form
		if(isset($_POST['submit'])&&($_POST['submit']=="Send"))
		{
			if(isset($_POST['username'])){
		    	$username = addslashes(filter_var($_POST['username'], FILTER_SANITIZE_STRING));
		  	}
			// Retrieve information related to the username
			$sql_recovery = "SELECT * FROM account_tb WHERE username='$username'";
			$result_recovery = mysqli_query($conn, $sql_recovery) or die(mysqli_error());
			$recovery = mysqli_fetch_assoc($result_recovery);  
			
			$name = $recovery["name"];
			$surname = $recovery["surname"];
			$institution = $recovery["institution"];
			$email = $recovery["email"];
			$network_id = $recovery["network_id"];			
			
			// Set temporary password
			$length = 10;
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		    $charactersLength = strlen($characters);
		    $randomString = '';
		    for ($i = 0; $i < $length; $i++) {
		        $randomString .= $characters[rand(0, $charactersLength - 1)];
		    }
			$password = sha1($randomString);
			
			// chiamata alla funzione per l'aggiornamento dei dati in CDM DB
			$sql_update_psw = "UPDATE login_tb SET password_login=\"" . $password . "\" WHERE username_login=\"" . $username . "\"";
			$update_query_psw = mysqli_query($conn, $sql_update_psw) or die(mysqli_error());
			
			$mess =  "The temporary password has been successfully updated and sent to you.";
										
			// Send email for registration confirmation
			$msg = "Dear " . $name . ",\na temporary password has been generated for your European HFR Node account.\n\nThe temporary password is: ". $randomString . "\n\nPlease change it as soon as you can.\n\nBelow you find the details of your account.\nUsername: " . $username . "\nName: " . $name . "\nSurname: " . $surname . "\nInstitution: " . $institution . "\nManaged HFR networks: " . $network_id . "\n\nBest regards.\nThe EU HFR node team.";
			// use wordwrap() if lines are longer than 70 characters
			$msg = wordwrap($msg,70);
			// set headers
			$headers = "MIME-Version: 1.0" . "\r\n";
			//$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= 'From: <lorenzo.corgnati@sp.ismar.cnr.it>' . "\r\n";
			// send email
			mail($email,"EU HFR Node password recovery",$msg,$headers);
				    
			header("Location: index.php?login_message=" . $mess);
			
		}
		else
		{
			// form per l'inserimento										
			?>
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<br>Please enter your username:<br>			
			<input name="username" type="text" value=""><br />	
			<input name="submit" type="submit" value="Send">
			</form>
			<br><br>An email will be sent to you with a temporary password.
			<div id="recovery_warning">
				<p><b>You are highly recommended to change it after the first login.</p>
			</div>
			<?php
		}
		?>
	<!-- end #psw_recovery_form --></div>    
  	<!-- end #mainContent --></div>
	<!-- Questo elemento di clearing deve seguire immediatamente il div #mainContent al fine di forzare il div #container a contenere tutti i float di livello inferiore --><br class="clearfloat" />
  <div id="footer">
    <p><b>CNR-ISMAR Institute of Marine Sciences - National Research Council of Italy</b> :: S.S. Lerici / Forte Santa Teresa, 19032 Pozzuolo di Lerici (SP) - Italy
    <br>Web Form development :: Lorenzo Corgnati :: lorenzo.corgnati@sp.ismar.cnr.it</p>
  <!-- end #footer --></div>
<!-- end #container --></div>

<?php 
if($_GET["login_message"] != ''){
	echo "<script language=\"javascript\">"; 
	echo "alert(\"" . $_GET["login_message"] . "\")";
	echo "</script>";
}

?>

</body>
</html>
<?php
mysqli_close($conn);
?>
