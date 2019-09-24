<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
$servername = "localhost";
$username = "HFR_lorenzo";
$password = "xWeLXHFQfvpBmDYO";
$dbname = "HFR_node_db";

$servername_CDM = "localhost";
$username_CDM = "lorenzo";
$password_CDM = "4.BSUyMH58sV3fXM";
$dbname_CDM = "CDM_cruises_db";

// Create connection to EU HFR node DB
$conn = mysql_connect($servername, $username, $password);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysql_connect_error());
}

mysql_select_db ($dbname, $conn);

mysql_query("SET NAMES 'utf8'",$conn);

// Create connection to CDM DB
$conn_CDM = mysql_connect($servername_CDM, $username_CDM, $password_CDM);
// Check connection
if (!$conn_CDM) {
    die("Connection failed: " . mysql_connect_error());
}

mysql_select_db ($dbname_CDM, $conn_CDM);

mysql_query("SET NAMES 'utf8'", $conn_CDM);

// Query the database for retrieveing managed HFR network IDs
$sql_networks_info = "SELECT network_id FROM account_tb WHERE username='" . $_GET["usr"] . "'";
$result_networks_info = mysql_query($sql_networks_info, $conn) or die(mysql_error());
$networks_info = mysql_fetch_assoc($result_networks_info);
$networks = $networks_info['network_id'];

?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>The European HFR Node</title>

<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
<link href="/css/EU_HFR_WF_StyleSheet.css" rel="stylesheet" type="text/css">

</head>

<body class="twoColFixRtHdr">

<div id="container">
  <div id="header" style="background: url('images/header_prova2.png')">
    <h1>EU HFR NODE - Data Entry Web Form</h1><br>
    Web Form for managing HFR network information
  <!-- end #header --></div>
  
  <div id="mainContent">
    <h1>Edit your profile</h1>
    <div id="Buttons">
    	<a href="index.php?logout=true"><button style="height:40px; width:200px">Logout</button><a>
    		<br><br>
    	<?php echo("<a href=\"index.php?usr=" . $_GET['usr'] . "\"><button style=\"height:40px; width:200px\">Back to homepage</button><a>"); ?>
    		<br><br>
    	<?php echo("<a href=\"network_admin_request.php?usr=" . $_GET['usr'] . "\"><button style=\"height:100px; width:200px\">Request the management of an existing network</button><a>"); ?>
	    	<br><br>
  	</div>
    <div id="registration_form">
    	Please edit your information
    	<?php
				 
		// valorizzazione delle variabili con i parametri dal form
		if(isset($_POST['submit'])&&($_POST['submit']=="Save"))
		{
			// Check the email address
			if(isset($_POST['email'])){
				$email = addslashes(filter_var($_POST['email'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['confirm_email'])){
			    $confirm_email = addslashes(filter_var($_POST['confirm_email'], FILTER_SANITIZE_STRING));
			}
			if($email != $confirm_email){
				$mess = "You either missed to confirm your email in the requested field or the two emails you inserted do not match. Please restart the account modification.";
				header("Location: edit_account.php?usr=" . $_GET['usr'] . "&login_message=" . $mess);
			}
			else {							
				if(isset($_POST['password'])){
					if(!empty($_POST['password'])){
						$password = sha1(addslashes(filter_var($_POST['password'], FILTER_SANITIZE_STRING)));
					}
				}
				if(isset($_POST['name'])){
				   	$name = addslashes(filter_var($_POST['name'], FILTER_SANITIZE_STRING));
				}
				if(isset($_POST['surname'])){
				   	$surname = addslashes(filter_var($_POST['surname'], FILTER_SANITIZE_STRING));
				}
				if(isset($_POST['institution'])){
				   	$institution = addslashes(filter_var($_POST['institution'], FILTER_SANITIZE_STRING));
				}			

				// chiamata alla funzione per l'aggiornamento dei dati in EU HFR node DB
			    $sql_update = "UPDATE account_tb SET name=\"" .  $name . "\", surname=\"" . $surname . "\", institution=\"". $institution . "\", email=\"" . $email;		
				$sql_update.= "\" WHERE username=\"" . $_GET["usr"] . "\"";
				$update_query = mysql_query($sql_update, $conn) or die(mysql_error());
				
				// chiamata alla funzione per l'inserimento dei dati in CDM DB
				if(isset($_POST['password'])){
					if(!empty($_POST['password'])){
						$sql_update_CDM = "UPDATE login_tb SET password_login=\"" . $password;
						$sql_update_CDM.= "\" WHERE username_login=\"" . $_GET["usr"] . "\"";
						$update_query_CDM = mysql_query($sql_update_CDM, $conn_CDM) or die(mysql_error());
					}
				}
					
				$mess =  "The account information have been successfully updated.";	 
					
				// Send email for registration confirmation
				$msg = "Dear " . $name . ",\nyour account to the European HFR Node has been succesfully updated.\nBelow you find the details of your account.\nUsername: " . $_GET["usr"] . "\nName: " . $name . "\nSurname: " . $surname . "\nInstitution: " . $institution . "\nManaged HFR networks: " . $networks . "\n\nYou can keep on filling in the information of the HFR networks you manage in the web form.\n\nBest regards.\nThe EU HFR node team.";
				// use wordwrap() if lines are longer than 70 characters
				$msg = wordwrap($msg,70);
				// set headers
				$headers = "MIME-Version: 1.0" . "\r\n";
				//$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
				$headers .= 'From: <lorenzo.corgnati@sp.ismar.cnr.it>' . "\r\n";
				// send email
				mail($email,"EU HFR Node account update",$msg,$headers);
			
			    header("Location: index.php?login_message=" . $mess . "&logout=true");
			}
		}
		else
		{
			// form per l'inserimento	
			// recupero eventuali informazioni inserite in precedenza dal EU HFR node DB
			$sql_change_account = "SELECT * FROM account_tb WHERE username = '" . $_GET["usr"] . "'";				
			$result_change_account = mysql_query($sql_change_account, $conn) or die(mysql_error());
			$change_account = mysql_fetch_assoc($result_change_account);
			
			$change_username = $change_account["username"];
			$change_name = $change_account["name"];
			$change_surname = $change_account["surname"];
			$change_institution = $change_account["institution"];
			$change_email = $change_account["email"];
									
			?>
			<form action="<?php echo $_SERVER['PHP_SELF'] . "?usr=" . $_GET["usr"]; ?>" method="post">
			<?php echo("<br>Username: " . $change_username . "<br />"); ?>	
			<br>Password:<br>			
			<input name="password" type="text" size="80" value="<?php echo($change_password);?>"><br />	
			<br>Name:<br>
			<input name="name" type="text" size="80" value="<?php echo($change_name);?>"><br />		
			<br>Surname:<br>
			<input name="surname" type="text" size="80" value="<?php echo($change_surname);?>"><br />
			<br>Institution:<br>
			<input name="institution" type="text" size="80" value="<?php echo($change_institution);?>"><br />	
			<br>Email:<br>			
			<input name="email" type="text" size="80" value="<?php echo($change_email);?>"><br />		
			<br>Confirm email:<br>			
			<input name="confirm_email" type="text" size="80" value=""><br />	
			<?php echo("<br>Network IDs: " . $networks . "<br />"); ?>
			<input name="submit" type="submit" value="Save">
			</form>
			<?
		}
		?>
	<!-- end #registration_form --></div>    
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
mysql_close($conn);
mysql_close($conn_CDM);
?>