<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
$servername = "localhost";
$username = "HFR_lorenzo";
$password = "xWeLXHFQfvpBmDYO";
$dbname = "HFR_node_db";

// Create connection to EU HFR node DB
$conn = mysql_connect($servername, $username, $password);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysql_connect_error());
}

mysql_select_db ($dbname, $conn);

mysql_query("SET NAMES 'utf8'",$conn);

// Query EU HFR node DB for retrieving the networks associated to the current username
$sql_username_info = "SELECT * FROM account_tb WHERE username='" . $_GET["usr"] . "'";
$result_username_info = mysql_query($sql_username_info, $conn) or die(mysql_error());
$username_info = mysql_fetch_assoc($result_username_info);
$networks = $username_info['network_id'];

if($networks == "*"){
	$sql_all_networks = "SELECT network_id FROM network_tb";
	$result_all_networks = mysql_query($sql_all_networks, $conn) or die(mysql_error());	
	$j = 0;
	while ($all_networks_row=mysql_fetch_array($result_all_networks)){
		$network_array[$j]=$all_networks_row['network_id'];
		$j++;
	}
}
else{
	$network_array = explode(',', $networks);
	$num_networks = count($network_array);
}

$current_network = $_POST["selected_network"];
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
    <?php
    echo("flag: " . $flag);
    echo("\r\nnetworks: " . $networks);
	echo("\r\nresult_all_networks: " . $result_all_networks);
	echo("\r\nnetwork_array[0]: " . $network_array[0]);
	echo("\r\nnetwork_array[1]: " . $network_array[1]);
	echo("\r\nnetwork_array[2]: " . $network_array[2]);
	echo("\r\nnetwork_array[3]: " . $network_array[3]);
	echo("\r\ncount: " . count($network_array));
    ?>  
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
?>