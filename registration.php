<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
$servername_HFR = "150.145.136.104";
$username_HFR = "HFRwebformUser";
$password_HFR = "!_e2MKonpy5paMTgR9_!";
$dbname_HFR = "HFR_node_db";

// Create connection to EU HFR node DB
$conn_HFR = mysql_connect($servername_HFR, $username_HFR, $password_HFR);
// Check connection
if (!$conn_HFR) {
    die("Connection failed: " . mysql_connect_error());
}

mysql_select_db ($dbname_HFR, $conn_HFR);

mysql_query("SET NAMES 'utf8'", $conn_HFR);

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
    <h1>Registration form</h1>
    <div id="Buttons">
    	<a href="index.php"><button style="height:40px; width:200px">Back to homepage</button><a>
    		<br><br>
  	</div>
    <div id="registration_form">
    	Please insert your information
    	<?php
				 
		// valorizzazione delle variabili con i parametri dal form
		if(isset($_POST['submit'])&&($_POST['submit']=="Save"))
		{
			if(isset($_POST['username'])){
		    	$username = addslashes(filter_var($_POST['username'], FILTER_SANITIZE_STRING));
		  	}
			// Check if the username is empty
			if($username!=''){
				// Check if the username already exists
				$sql_usernames = "SELECT * FROM account_tb WHERE username='$username'";
				$result_usernames = mysql_query($sql_usernames, $conn_HFR) or die(mysql_error());
				$count_usernames = mysql_num_rows($result_usernames);  
				
				if ($count_usernames>0){
						$mess = "The username already exists. Please choose a different one.";
						header("Location: registration.php?login_message=" . $mess);
				}
				else {
					// Check the email address
					if(isset($_POST['email'])){
				    	$email = addslashes(filter_var($_POST['email'], FILTER_SANITIZE_STRING));
				  	}
				  	if($email!=''){
				  		// Check if the username already exists
						$sql_emails = "SELECT * FROM account_tb WHERE email='$email'";
						$result_emails = mysql_query($sql_emails, $conn_HFR) or die(mysql_error());
						$count_emails = mysql_num_rows($result_emails);
						
						if($count_emails>0){
							$mess = "The email you inserted already exists in the database. An email will be sent to you where you can find your username and your account details. Please use your username to recover your password, in case you forgot it.";
							
							// Retrieve user details
							$emails = mysql_fetch_assoc($result_emails);
							$username_rec = $emails['username'];
							$name_rec = $emails['name'];
							$surname_rec = $emails['surname'];
							$institution_rec = $emails['institution'];
							$network_id_rec = $emails['network_id'];
							
							// Send email for password recovery
							$msg_rec = "Dear " . $name_rec . ",\nyou are already registered to the European HFR Node with this email.\nIt seems that you are trying to create a new account with the same email.\nBelow you find the details of your account.\nUsername: " . $username_rec . "\nName: " . $name_rec . "\nSurname: " . $surname_rec . "\nInstitution: " . $institution_rec . "\nManaged HFR networks: " . $network_id_rec . "\n\nPlease use your username to recover your password, in case you forgot it.\n\nBest regards.\nThe EU HFR node team.";
							// use wordwrap() if lines are longer than 70 characters
							$msg_rec = wordwrap($msg_rec,70);
							// set headers
							$headers = "MIME-Version: 1.0" . "\r\n";
							//$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
							$headers .= 'From: <lorenzo.corgnati@sp.ismar.cnr.it>' . "\r\n";
							// send email
							mail($email,"EU HFR Node registration",$msg_rec,$headers);
													
							header("Location: index.php?login_message=" . $mess);
						}
						else{
							if(isset($_POST['confirm_email'])){
						    	$confirm_email = addslashes(filter_var($_POST['confirm_email'], FILTER_SANITIZE_STRING));
						  	}
							if($email != $confirm_email){
								$mess = "The confirmation email you inserted does not match your email address. Please restart the registration.";
								header("Location: registration.php?login_message=" . $mess);
							}
							else {							
								if(isset($_POST['password'])){
									if($_POST['password']==''){
										$password = '';
									}
									else{
										$password = sha1(addslashes(filter_var($_POST['password'], FILTER_SANITIZE_STRING)));
									}
							    }
								if($password!=''){
								  	if(isset($_POST['name'])){
								    	$name = addslashes(filter_var($_POST['name'], FILTER_SANITIZE_STRING));
								  	}
									if($name!=''){
									  	if(isset($_POST['surname'])){
									    	$surname = addslashes(filter_var($_POST['surname'], FILTER_SANITIZE_STRING));
									    }
										if($surname!=''){
											if(isset($_POST['institution'])){
										    	$institution = addslashes(filter_var($_POST['institution'], FILTER_SANITIZE_STRING));
										  	}
											if($institution!=''){
												if(isset($_POST['network_id'])){
											    	$network_id = addslashes(filter_var($_POST['network_id'], FILTER_SANITIZE_STRING));
											  	}				
												// chiamata alla funzione per l'inserimento dei dati in EU HFR node DB
												$sql_insert = "INSERT INTO account_tb (username, name, surname, institution, email, network_id) VALUES (\"" . $username . "\",\"" . $name . "\",\"" . $surname . "\",\"" . $institution . "\",\"" . $email . "\",\"" . $network_id . "\")";
												$insert_query = mysql_query($sql_insert, $conn_HFR) or die(mysql_error());
																	
												// chiamata alla funzione per l'inserimento dei dati in CDM DB
												$sql_insert_psw = "INSERT INTO login_tb (username_login, password_login) VALUES (\"" . $username . "\",\"" . $password . "\")";
												$insert_query_psw = mysql_query($sql_insert_psw, $conn_HFR) or die(mysql_error());
																	
												$mess =  "The account information have been successfully inserted.";	 
																	
												// Send email for registration confirmation
												$msg = "Dear " . $name . ",\nyou have been succesfully registered to the European HFR Node.\nBelow you find the details of your account.\nUsername: " . $username . "\nName: " . $name . "\nSurname: " . $surname . "\nInstitution: " . $institution . "\nManaged HFR networks: " . $network_id . "\n\nYou can start filling in the information of the HFR networks you manage in the web form.\n\nBest regards.\nThe EU HFR node team.";
												// use wordwrap() if lines are longer than 70 characters
												$msg = wordwrap($msg,70);
												// set headers
												$headers = "MIME-Version: 1.0" . "\r\n";
												//$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
												$headers .= 'From: <lorenzo.corgnati@sp.ismar.cnr.it>' . "\r\n";
												// send email
												mail($email,"EU HFR Node registration",$msg,$headers);
																    
												header("Location: index.php?login_message=" . $mess);
											}
											else{
												$mess =  "No institution has been inserted. Please insert one.";
												header("Location: registration.php?login_message=" . $mess);
											}
										}
										else{
											$mess =  "No surname has been inserted. Please insert one.";
											header("Location: registration.php?login_message=" . $mess);
										}
									}
									else{
										$mess =  "No name has been inserted. Please insert one.";
										header("Location: registration.php?login_message=" . $mess);
									}
								}
								else{
									$mess =  "No password has been inserted. Please insert one.";
									header("Location: registration.php?login_message=" . $mess);
								}
							}
						}
					}
					else{
						$mess =  "No email has been inserted. Please insert one.";
						header("Location: registration.php?login_message=" . $mess);
					}
				}
			}
			else{
				$mess =  "No username has been inserted. Please insert one.";
				header("Location: registration.php?login_message=" . $mess);
			}
		}
		else
		{
			// form per l'inserimento						
			?>
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<br>Username:<br>			
			<input name="username" type="text" value=""><br />	
			<br>Password:<br>			
			<input name="password" type="text" value=""><br />	
			<br>Name:<br>
			<input name="name" type="text" value=""><br />	
			<br>Surname:<br>
			<input name="surname" type="text" value=""><br />
			<br>Institution:<br>
			<input name="institution" type="text" value=""><br />	
			<br>Email:<br>			
			<input name="email" type="text" value=""><br />		
			<br>Confirm email:<br>			
			<input name="confirm_email" type="text" value=""><br />				
			<input name="submit" type="submit" value="Save">
			</form>
			<?
		}
		?>
		<br><br>
		You can request the management of an existing network in the page <b>"Edit your profile"</b>.
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
mysql_close($conn_HFR);
?>
