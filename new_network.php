<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
$servername_HFR = "150.145.136.104";
$username_HFR = "HFRwebformUser";
$password_HFR = "!_e2MKonpy5paMTgR9_!";
$dbname_HFR = "HFR_node_db";

// Create connection to EU HFR node DB
$conn_HFR = mysqli_connect($servername_HFR, $username_HFR, $password_HFR, $dbname_HFR);
// Check connection
if (!$conn_HFR) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set the desired charset after establishing a connection
mysqli_set_charset($conn_HFR, 'utf8');

if($_GET["usr"] != ''){
	$username = $_GET["usr"];
}

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
    <h1>Add a new HFR network</h1>
    <div id="Buttons">
    	<a href="https://cnrsc-my.sharepoint.com/:b:/g/personal/lorenzo_corgnati_cnr_it/Ecoa9zTYUmVNiXuDxiKWslQBqwbtuG3r1RVwdI5Dy-E9zg?e=qclGcb" target="_blank"><button style="height:40px; width:200px">Webform User Manual</button><a>
    		<br><br>
    	<a href="index.php?logout=true"><button style="height:40px; width:200px">Logout</button><a>
    		<br><br>
    	<?php echo("<a href=\"edit_account.php?usr=" . $_GET['usr'] . "\"><button style=\"height:40px; width:200px\">Edit your profile</button><a>"); ?>
    		<br><br>
    	<?php echo("<a href=\"network_WF.php?usr=" . $username . "\"><button style=\"height:100px; width:200px\">Back to Network Web Form</button><a>"); ?>
    		<br><br>
  	</div>
    <div id="new_network_form">
    	<?php				 
		// valorizzazione delle variabili con i parametri dal form
		if(isset($_POST['submit'])&&($_POST['submit']=="Add"))
		{
			if(isset($_POST['network_id'])){
		    	$network_id = addslashes(filter_var($_POST['network_id'], FILTER_SANITIZE_STRING));
		  	}
		  	if($network_id!=''){
		  		// Check if the network ID already exists
				$sql_networks = "SELECT * FROM network_tb WHERE network_id='$network_id'";
				$result_networks = mysqli_query($conn_HFR, $sql_networks) or die(mysqli_error());
				$count_networks = mysqli_num_rows($result_networks);  
				
				if ($count_networks>0){
						$mess = "The network ID you inserted already exists. Please choose a different one. Network IDs MUST be equal to the EDIOS Series ID of the HFR network";
						header("Location: new_network.php?usr=" . $username . "&login_message=" . $mess);
				}
				else{
					$network_id_check = 0;
					// Check if the network IDs contain the prefix HFR-
					$num_HFR = substr_count($network_id, 'HFR-');
					if($num_HFR==0){
						$mess =  "The network ID you inserted does not contain the prefix HFR-. Please insert a network ID containing the prefix HFR-. The network ID MUST be equal to the EDIOS Series ID of the HFR network.";
						header("Location: new_network.php?usr=" . $username . "&login_message=" . $mess);
					}
					else{
						// Retrieve information about the username
						$sql_username_info = "SELECT * FROM account_tb WHERE username='$username'";
						$result_username_info = mysqli_query($conn_HFR, $sql_username_info) or die(mysqli_error());
						$username_info = mysqli_fetch_assoc($result_username_info);
						
						$name = $username_info["name"];
						$surname = $username_info["surname"];
						$institution = $username_info["institution"];
						$email = $username_info["email"];
						$previous_network_id = $username_info["network_id"];
						if($previous_network_id == ''){
							$updated_network_id = $network_id;
						}
						else{
							$updated_network_id = $previous_network_id . ", " . $network_id;
						}
						
						// chiamata alla funzione per l'aggiornamento dei dati del profilo
						$sql_update = "UPDATE account_tb SET network_id=\"" . $updated_network_id . "\" WHERE username=\"" . $username . "\"";
						$update_query = mysqli_query($conn_HFR, $sql_update) or die(mysqli_error());
						
						// Insert new network into network_tb table
						$sql_insert = "INSERT INTO network_tb (network_id) VALUES (\"" . $network_id . "\")";
						$insert_query = mysqli_query($conn_HFR, $sql_insert) or die(mysqli_error());
						
						$mess =  "The new network has been added to the networks you manage. An email with the details of your updated profile has been sent to you.";
													
						// Send email for registration confirmation
						$msg = "Dear " . $name . ",\nyour European HFR Node account has been succesfully updated with a new network.\nBelow you find the details of your account.\nUsername: " . $username . "\nName: " . $name . "\nSurname: " . $surname . "\nInstitution: " . $institution . "\nManaged HFR networks: " . $updated_network_id . "\n\nYou can start filling in the information of the HFR networks you manage in the web form.\n\nBest regards.\nThe EU HFR node team.";
						// use wordwrap() if lines are longer than 70 characters
						$msg = wordwrap($msg,70);
						// set headers
						$headers = "MIME-Version: 1.0" . "\r\n";
						//$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
						$headers .= 'From: <lorenzo.corgnati@sp.ismar.cnr.it>' . "\r\n";
						// send email
						mail($email,"EU HFR Node new network",$msg,$headers);
							    
						header("Location: network_processing.php?usr=" . $username .  "&ntw=" . $network_id . "&login_message=" . $mess);
					}
				}
			}
			else{
				$mess =  "No network ID has been inserted. Please insert one.";
				header("Location: new_network.php?usr=" . $username . "&login_message=" . $mess);
			}
		}
		else
		{
			// form per l'inserimento del network ID										
			?>
			<form action="<?php echo $_SERVER['PHP_SELF'] . "?usr=" . $_GET['usr']; ?>" method="post">
			<br>New network ID (network ID MUST be equal to the EDIOS Series ID of the HFR network):<br>			
			<input name="network_id" type="text" value=""><br />	
			<input name="submit" type="submit" value="Add">
			</form>
			<br><br>An email will be sent to you with the updated details of your account.
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
mysqli_close($conn_HFR);
?>
