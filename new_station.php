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

mysql_query("SET NAMES 'utf8'",$conn_HFR);

if($_GET["usr"] != ''){
	$username = $_GET["usr"];
}

if($_GET["ntw"] != ''){
	$current_network_id = $_GET["ntw"];
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
    <?php echo("<h1>Add a new HFR station from " . $current_network_id . " network</h1>"); ?>
    <div id="Buttons">
    	<a href="index.php?logout=true"><button style="height:40px; width:200px">Logout</button><a>
    		<br><br>
    	<?php echo("<a href=\"edit_account.php?usr=" . $_GET['usr'] . "\"><button style=\"height:40px; width:200px\">Edit your profile</button><a>"); ?>
    		<br><br>
    	<?php echo("<a href=\"network_WF.php?usr=" . $username . "&ntw=" . $current_network_id . "\"><button style=\"height:100px; width:200px\">Back to Network Web Form</button><a>"); ?>
    		<br><br>
    	<?php echo("<a href=\"station_WF.php?usr=" . $username . "&ntw=" . $current_network_id . "\"><button style=\"height:100px; width:200px\">Back to Station Web Form</button><a>"); ?>
    		<br><br>
  	</div>
    <div id="new_station_form">
    	<?php				 
		// valorizzazione delle variabili con i parametri dal form
		if(isset($_POST['submit'])&&($_POST['submit']=="Add"))
		{
			if(isset($_POST['station_id'])){
		    	$station_id = addslashes(filter_var($_POST['station_id'], FILTER_SANITIZE_STRING));
		  	}
		  	if($station_id!=''){
		  		// Check if the station ID already exists
				$sql_stations = "SELECT * FROM station_tb WHERE station_id='$station_id'";
				$result_stations = mysql_query($sql_stations, $conn_HFR) or die(mysql_error());
				$count_stations = mysql_num_rows($result_stations);  
				
				if ($count_stations>0){
						$mess = "The station ID you inserted already exists. Please choose a different one. Station ID MUST be equal to the EDIOS Platform ID of the HFR station.";
						header("Location: new_station.php?usr=" . $username . "&ntw=" . $current_network_id . "&login_message=" . $mess);
				}
				else{
					// Insert new network into network_tb table
					$sql_insert = "INSERT INTO station_tb (station_id, network_id) VALUES (\"" . $station_id . "\",\"" . $current_network_id . "\")";
					$insert_query = mysql_query($sql_insert, $conn_HFR) or die(mysql_error());
						
					$mess =  "The new station has been added";
							    
					header("Location: station_WF.php?usr=" . $username . "&ntw=" . $current_network_id . "&login_message=" . $mess);
				}
			}
			else{
				$mess =  "No station ID has been inserted. Please insert one.";
				header("Location: new_station.php?usr=" . $username . "&ntw=" . $current_network_id . "&login_message=" . $mess);
			}
		}
		else
		{
			// form per l'inserimento										
			?>
			<form action="<?php echo $_SERVER['PHP_SELF'] . "?usr=" . $_GET['usr'] . "&ntw=" . $current_network_id; ?>" method="post">
			<br>New station ID (station ID MUST be equal to the EDIOS Platform ID of the HFR station):<br>			
			<input name="station_id" type="text" value=""><br />	
			<input name="submit" type="submit" value="Add">
			</form>
			<br><br>After having added the new station please fill in the information from the Station Web Form.
			<?
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
mysql_close($conn_HFR);
?>
