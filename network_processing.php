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

if($_GET["ntw"] != ''){
	$network_id = $_GET["ntw"];
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
  <div id="header" style="background: url('images/header_prova2.png')">
    <h1>EU HFR NODE - Data Entry Web Form</h1><br>
    Web Form for managing HFR network information
  <!-- end #header --></div>
  
  <div id="mainContent">
    <h1>Choose the processing options for your new HFR network</h1>
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
    	if(isset($_POST['submit'])&&($_POST['submit']=="Submit"))
		{
			// valorizzazione delle variabili con i parametri dal form
			if(isset($_POST['push_data'])){
				
				// TO BE DELETED
				$mess =  "push_data=" . $_POST['push_data'];
				header("Location: network_WF.php?usr=" . $username . "&login_message=" . $mess);
					
				if($_POST["push_data"] == "Yes"){
					// Update network_tb table with EU_HFR_processing_flag=1
					$sql_update = "UPDATE network_tb SET EU_HFR_processing_flag=1 WHERE network_id=\"" . $network_id . "\"";
					$update_query = mysqli_query($conn_HFR, $sql_update) or die(mysqli_error());
					$mess =  "The processing options has been added to your new network.";
					header("Location: network_WF.php?usr=" . $username . "&login_message=" . $mess);
				}	
				elseif($_POST["push_data"] == "No"){
					// Update network_tb table with EU_HFR_processing_flag=0
					$sql_update = "UPDATE network_tb SET EU_HFR_processing_flag=0 WHERE network_id=\"" . $network_id . "\"";
					$update_query = mysqli_query($conn_HFR, $sql_update) or die(mysqli_error());
					$mess =  "The processing options has been added to your new network.";
					header("Location: network_WF.php?usr=" . $username . "&login_message=" . $mess);
				}
					
			}
			else{
				$mess = "No choice has been made on data processing. Please choose your option.";
				header("Location: network_processing.php?usr=" . $username . "&ntw=" . $network_id . "&login_message=" . $mess);
			}
		}
		else
		{
			// radio buttons for the processing options								
		?>
			<form action="<?php echo $_SERVER['PHP_SELF'] . "?usr=" . $_GET['usr'] . "&ntw=" . $network_id; ?>" method="post">
				<fieldset>
					<?php
					echo("<legend><b>Will you push radial and total data from the " . $network_id . " network to the EU HFR node for QC and conversion?</b></legend>");
					?>
					(If you choose "No", you will be responsible for running the software tools for QC and conversion of your radial and total data.)
					<br><br>
					Yes <input type="radio" name="push_data" value="Yes"/>
					No  <input type="radio" name="push_data" value="No"/>
				</fieldset>
				<br>
				<input name="submit" type="submit" value="Submit">
			</form>
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
