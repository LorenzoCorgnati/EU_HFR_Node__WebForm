<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
$servername = "localhost";
$username = "lorenzo";
$password = "4.BSUyMH58sV3fXM";
$dbname = "CDM_cruises_db";

// Create connection
$conn = mysql_connect($servername, $username, $password);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysql_connect_error());
}

mysql_select_db ($dbname);

mysql_query("SET NAMES 'utf8'");

$sql_cruise = "SELECT * FROM cruises_tb WHERE cruise_id='" . $_GET["cru"] . "'";
$result_cruise = mysql_query($sql_cruise, $conn) or die(mysql_error());
$num_rows_cruise = mysql_num_rows($result_cruise);
$current_cruise = mysql_fetch_assoc($result_cruise);
?>


<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Cruise Report Maker</title>

<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
<link href="/css/CDM_StyleSheet.css" rel="stylesheet" type="text/css">

</head>

<body class="twoColFixRtHdr">

<div id="container">
  <div id="header" style="background: url('images/header3.png')">
    <h1>CRUISE REPORT MAKER</h1><br>
    Online editor for scientific cruise reports
  <!-- end #header --></div>
  <div id="sidebar1">
    <?php echo("<h3>" . $current_cruise["cruise_name"] . " Research Cruise</h3><br><br>"); ?>
    <?php echo("<p><b>Participant Institutes:</b><br> " . $current_cruise["partners"]); ?>
    <?php echo("<p><b>Project Responsible:</b><br> " . $current_cruise["cruise_resp"]); ?>
    <?php echo("<p><b>Head of Mission:</b><br> " . $current_cruise["head_mission"]); ?>
    <?php echo("<p><b>Chief Scientist:</b><br> " . $current_cruise["chief_scientist"]); ?>
    <?php echo("<p><b>Study area:</b><br> " . $current_cruise["cruise_area"]); ?>
    <?php echo("<p><b>Research Vessel:</b><br> " . $current_cruise["rv_name"]); ?>
    <?php echo("<p><b>Profiling instruments:</b><br> " . $current_cruise["instruments"]); ?>
    <?php echo("<p><b>Cruise start:</b><br> " . $current_cruise["start_date"] . " from " . $current_cruise["start_harbour"]); ?>
    <?php echo("<p><b>Cruise expected end:</b><br> " . $current_cruise["end_date"] . " in " . $current_cruise["end_harbour"]); ?>    	
  <!-- end #sidebar1 --></div>
  <div id="mainContent">
    <h1> Login to Cruise Report Maker</h1>
    <div id="form_login">
		<?php
		// inizializzazione della sessione
		session_start();
		// se la sessione di autenticazione è già impostata non sarà necessario effettuare il login
		// e il browser verrà reindirizzato alla pagina di scrittura dei post
		if (isset($_SESSION['login']))
		{
 			// reindirizzamento alla homepage in caso di login non necessario
 			header("Location: CruiseReportMaker.php?cru=" . $current_cruise['cruise_id']);
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
				$result_login = mysql_query($sql_login, $conn) or die(mysql_error());
				$num_rows_login = mysql_num_rows($result_login);
				
    			// controllo sul risultato dell'interrogazione
        		if($num_rows_login==0)
    			{
        			// reindirizzamento alla homepage in caso di insuccesso
        			$mess = "Login failed";
          			header("Location: index.php?cru=" . $current_cruise['cruise_id'] . "&login_message=" . $mess);
    			}
    			else
    			{
          			// chiamata alla funzione per l'estrazione dei dati
      				$inserted_login = mysql_fetch_object($result_login);
      				
          			// creazione del valore di sessione
      				$_SESSION['login'] = $inserted_login;

        			// reindirizzamento alla pagina di amministrazione in caso di successo
          			header("Location: CruiseReportMaker.php?cru=" . $current_cruise['cruise_id']);
    			}
  			} 
		}
		else
		{
  			// form per l'autenticazione
  			?>
			<form action="<?php echo $_SERVER['PHP_SELF'] . "?cru=" . $current_cruise['cruise_id'] ; ?>" method="POST">
			Username:<br />
			<input name="username" type="text"><br />
			Password:<br />
			<input name="password" type="password" size="20"><br />
			<input name="submit" type="submit" value="Login">
			</form>
  			<?
		}
		?>
    </div>
	<!-- end #mainContent --></div>
	<!-- Questo elemento di clearing deve seguire immediatamente il div #mainContent al fine di forzare il div #container a contenere tutti i float di livello inferiore --><br class="clearfloat" />
  <div id="footer">
    <p><b>CNR-ISMAR Institute of Marine Sciences - National Research Council of Italy</b> :: S.S. Lerici / Forte Santa Teresa, 19032 Pozzuolo di Lerici (SP) - Italy    	
</p>
  <!-- end #footer --></div>
<!-- end #container --></div>
</body>
</html>
<?php
mysql_close($conn);
?>