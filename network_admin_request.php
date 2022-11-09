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

if($_POST["selected_network"] != ''){
	$selected_network_id = $_POST["selected_network"];
	if($selected_network_id == "Select the network"){
		$mess = "No network has been selected. Please select one.";
		header("Location: network_admin_request.php?usr=" . $_GET["usr"] . "&login_message=" . $mess);
	}
	else{
		$mess = "The administrator of the network you ask the management has been informed of your request. You will be informed about the final decision.";
		header("Location: network_admin_request_mailing.php?usr=" . $_GET["usr"] . "&ntw=" . $selected_network_id . "&login_message=" . $mess);
	}
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
    <h1>Request the administration of an existing network</h1>
    <div id="Buttons">
    	<a href="https://cnrsc-my.sharepoint.com/:b:/g/personal/lorenzo_corgnati_cnr_it/Ecoa9zTYUmVNiXuDxiKWslQBqwbtuG3r1RVwdI5Dy-E9zg?e=qclGcb" target="_blank"><button style="height:40px; width:200px">Webform User Manual</button><a>
    		<br><br>
    	<a href="index.php?logout=true"><button style="height:40px; width:200px">Logout</button><a>
    		<br><br>
    	<?php echo("<a href=\"edit_account.php?usr=" . $_GET['usr'] . "\"><button style=\"height:40px; width:200px\">Edit your profile</button><a>"); ?>
    		<br><br>
    	<?php echo("<a href=\"network_WF.php?usr=" . $_GET['usr'] . "\"><button style=\"height:100px; width:200px\">Back to Network Web Form</button><a>"); ?>
    		<br><br>
  	</div>
    <div id="network_admin_request_form">
		<?php
		// Retrieve all the existing networksIDs
		$sql_all_networks = "SELECT network_id FROM network_tb";
		$result_all_networks = mysqli_query($conn, $sql_all_networks) or die(mysqli_error());
		
		// menu a tendina
		?>
		<b>Select the existing network you want request to manage:</b>
		<form action="<?php echo $_SERVER['PHP_SELF'] . "?usr=" . $_GET["usr"]; ?>" method="post">
		<select name="selected_network">
			<?php
				while ($row=mysqli_fetch_array($result_all_networks)){
					$network=$row['network_id'];
					echo("<option value=\"" . $network . "\">" . $network . "</option>");
				}
				echo("<option selected=\"selected\"> Select the network </option>");
			?>
			</select>
			<input name="submit" type="submit" value="Select">
			</form>
	<!-- end #network_admin_request_form --></div>    
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
