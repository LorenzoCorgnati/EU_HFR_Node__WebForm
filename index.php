<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
//$servername = "localhost";
//$username = "HFR_lorenzo";
//$password = "xWeLXHFQfvpBmDYO";
//$dbname = "HFR_node_db";

$servername_CDM = "localhost";
$username_CDM = "lorenzo";
$password_CDM = "4.BSUyMH58sV3fXM";
$dbname_CDM = "CDM_cruises_db";

// Create connection
$conn_CDM = mysql_connect($servername_CDM, $username_CDM, $password_CDM);
// Check connection
if (!$conn_CDM) {
    die("Connection failed: " . mysql_connect_error());
}

mysql_select_db ($dbname_CDM);

mysql_query("SET NAMES 'utf8'");

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
  <div id="header" style="background: url('images/header_prova2.png')">
    <h1>EU HFR NODE - Data Entry Web Form</h1><br>
    Web Form for managing HFR network information
  <!-- end #header --></div>
  
  <div id="mainContent">
    <!-- <h1> Contenuto principale</h1> -->
    <div id="splash">
    	<img src="/images/EU_HFR_Node_VF.png">
    <!-- end #splash --></div>
     <?php
    if(isset($_GET['logout'])) {
    	session_start();
	    session_destroy();
	    unset($_SESSION["login"]);
	
	    header('Location: index.php');
	    exit;
    }
 ?>
    <div id="login">
    	<p><b>Welcome to the EU HFR NODE Data Entry Web Form</p>
	  	<div id="form_login">
	  		Please login to insert or edit the information of the HFR networks you manage<br><br>
		<?php
		// inizializzazione della sessione
		session_start();
		// se la sessione di autenticazione è già impostata non sarà necessario effettuare il login
		// e il browser verrà reindirizzato alla pagina di scrittura dei post
		if (isset($_SESSION['login']))
		{
 			// reindirizzamento alla homepage in caso di login non necessario
 			header("Location: network_WF.php?usr=" . $username);
		} 
		
		// controllo sul parametro d'invio
		if(isset($_POST['submit']) && (trim($_POST['submit']) == "Login"))
		{ 
  			// controllo sui parametri di autenticazione inviati
  			if( !isset($_POST['username']) || $_POST['username']=="" )
  			{
    			echo "Please insert the username.";
  			}
  			elseif( !isset($_POST['password']) || $_POST['password'] =="")
  			{
    			echo "Please insert the password.";
  			}
  			else
  			{
    			// validazione dei parametri tramite filtro per le stringhe
    			$username = trim(filter_var($_POST['username'], FILTER_SANITIZE_STRING));
    			$password = trim(filter_var($_POST['password'], FILTER_SANITIZE_STRING));
    			$password = sha1($password);
				
				// esecuzione della query con le credenziali di accesso
				$sql_login = "SELECT id_login FROM login_tb WHERE username_login='" . $username . "' AND password_login='" . $password . "'";
				$result_login = mysql_query($sql_login, $conn_CDM) or die(mysql_error());
				$num_rows_login = mysql_num_rows($result_login);
				
    			// controllo sul risultato dell'interrogazione
        		if($num_rows_login==0)
    			{
        			// reindirizzamento alla homepage in caso di insuccesso
        			$mess = "Login failed";
          			header("Location: index.php?login_message=" . $mess);
    			}
    			else
    			{
          			// chiamata alla funzione per l'estrazione dei dati
      				$inserted_login = mysql_fetch_object($result_login);
      				
          			// creazione del valore di sessione
      				$_SESSION['login'] = $inserted_login;

        			// reindirizzamento alla pagina di amministrazione in caso di successo
          			header("Location: network_WF.php?usr=" . $username);
    			}
  			} 
		}
		else
		{
  			// form per l'autenticazione
  			?>
			<form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
			Username:<br />
			<input type="text" name="username"><br />
			Password:<br />
			<input type="password" name="password" size="20"><br />
			<input name="submit" type="submit" value="Login">
			</form>
  			<?
		}
		?>
		<br> <a href="registration.php">or click here for creating your account</a>
		<br><br> <a href="psw_recovery.php">Click here to recover your password in case you lost it</a>
    </div>
  	<!-- end #login --></div>
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
mysql_close($conn_CDM);
?>