<?php
$servername = "150.145.136.104";
$username = "HFRwebformUser";
$password = "!_e2MKonpy5paMTgR9_!";
$dbname = "HFR_node_db";

// Create connection to EU HFR node DB
$conn = mysql_connect($servername, $username, $password);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysql_connect_error());
}

mysql_select_db ($dbname, $conn);

mysql_query("SET NAMES 'utf8'",$conn);

// Retrieve information about the requesting username
$sql_requesting_username = "SELECT * FROM account_tb WHERE username='" . $_GET["usr"] . "'";
$result_requesting_username = mysql_query($sql_requesting_username, $conn) or die(mysql_error());
$requesting_username = mysql_fetch_assoc($result_requesting_username);
$name_req = $requesting_username["name"];
$surname_req = $requesting_username["surname"];
$institution_req = $requesting_username["institution"];
$email_req = $requesting_username["email"];

// Retrieve information about the HFR EU node administrator
$sql_admin_username = "SELECT * FROM account_tb WHERE username='admin'";
$result_admin_username = mysql_query($sql_admin_username, $conn) or die(mysql_error());
$admin_username = mysql_fetch_assoc($result_admin_username);
$email_node_admin = $admin_username["email"];

// Retrieve the username information of the selected network manager
$sql_all_accounts = "SELECT * FROM account_tb";
$result_all_accounts = mysql_query($sql_all_accounts, $conn) or die(mysql_error());

while ($accounts_row=mysql_fetch_array($result_all_accounts)){
	$networks = $accounts_row['network_id'];
	if($accounts_row['username'] != 'admin'){
		$stripped = str_replace(' ', '', $networks);
		$network_array = explode(',', $stripped);
		$num_networks = count($network_array);
		
		if($network_array[0] != ''){
			for($i = 0; $i < $num_networks; $i++){	
				if(($network_array[$i] == $_GET['ntw'])){
					$name_network_admin = $accounts_row["name"];
					$email_network_admin = $accounts_row["email"];
					// Send email to the selected network manager (cc HFR EU node admin)
					$msg = "Dear " . $name_network_admin . ",\n" . $name_req . " " . $surname_req . " from " . $institution_req . " requested the management permits for the network " . $_GET['ntw'] . ".\nPlease inform the EU HFR node team about your decision by writing to " . $email_node_admin . "\n\nBest regards.\nThe EU HFR node team.";
					// use wordwrap() if lines are longer than 70 characters
					$msg = wordwrap($msg,70);
					// set headers
					$headers = "MIME-Version: 1.0" . "\r\n";
					//$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
					$headers .= 'From: <lorenzo.corgnati@sp.ismar.cnr.it>' . "\r\n";
					$headers .= 'Cc: ' . $email_node_admin . "\r\n";
					
					// send email
					mail($email_network_admin,"EU HFR Node network administration request",$msg,$headers);
				}
			}
		}
	}
}


// Send email to the selected network manager (cc HFR EU node admin)
$msg_req = "Dear " . $name_req . ",\nthe administrator of the network " . $_GET['ntw'] . ", you asked the management for, has been emailed with your request. You will be informed about the final decision.\n\nBest regards.\nThe EU HFR node team.";
// use wordwrap() if lines are longer than 70 characters
$msg_req = wordwrap($msg_req,70);
// set headers
$headers_req = "MIME-Version: 1.0" . "\r\n";
//$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers_req .= 'From: <lorenzo.corgnati@sp.ismar.cnr.it>' . "\r\n";
// send email
mail($email_req,"EU HFR Node network administration request",$msg_req,$headers_req);
			
$mess = "The network administration request has been succesfully sent.";					    
header("Location: index.php?usr=" . $_GET['usr'] . "&login_message=" . $mess);

mysql_close($conn);
?>
