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

if($_GET["ntw"] != ''){
	$current_network_id = $_GET["ntw"];
	$sql_current_network = "SELECT * FROM network_tb WHERE network_id='" . $current_network_id . "'";
	$result_current_network = mysql_query($sql_current_network, $conn) or die(mysql_error());
	$current_network = mysql_fetch_assoc($result_current_network);
	$EU_HFR_processing_flag = $current_network['EU_HFR_processing_flag'];
}

if($_GET["sta"] != ''){
	$selected_station_id = $_GET["sta"];
	$sql_selected_station = "SELECT * FROM station_tb WHERE network_id='" . $current_network_id . "' AND station_id='" . $selected_station_id . "'";
	$result_selected_station = mysql_query($sql_selected_station, $conn) or die(mysql_error());
	$selected_station = mysql_fetch_assoc($result_selected_station);	
}

if($_POST["selected_station"] != ''){
	$selected_station_id = $_POST["selected_station"];
		
	if($selected_station_id == "Add new station"){
		header("Location: new_station.php?usr=" . $_GET["usr"] . "&ntw=" . $current_network_id);
	}	
	elseif($selected_station_id == "Select the station"){
		$mess = "No station has been selected. Please select one.";
		header("Location: station_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $current_network_id . "&login_message=" . $mess);
	}
	else{
		$sql_selected_station = "SELECT * FROM station_tb WHERE network_id='" . $current_network_id . "' AND station_id='" . $selected_station_id . "'";
		$result_selected_station = mysql_query($sql_selected_station, $conn) or die(mysql_error());
		$selected_station = mysql_fetch_assoc($result_selected_station);
	}	
}

// Query EU HFR node DB for retrieving the stations associated to the current network
$sql_stations = "SELECT * FROM station_tb WHERE network_id='" . $current_network_id . "'";
$result_stations = mysql_query($sql_stations, $conn) or die(mysql_error());
$num_rows_stations = mysql_num_rows($result_stations);
$num_fields_stations = mysql_num_fields($result_stations);

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
    <h1>HFR stations information</h1>
    <div id="Buttons">
    	<a href="index.php?logout=true"><button style="height:40px; width:200px">Logout</button><a>
    		<br><br>
    	<?php echo("<a href=\"edit_account.php?usr=" . $_GET['usr'] . "\"><button style=\"height:40px; width:200px\">Edit your profile</button><a>"); ?>
    		<br><br>
    	<?php echo("<a href=\"network_WF.php?usr=" . $_GET['usr'] . "&ntw=" . $current_network_id . "\"><button style=\"height:100px; width:200px\">Back to Network Web Form</button><a>"); ?>
  	</div>
    
    <div id="station_form">
    	<?php
    	// inizializzazione della sessione
		session_start();
		
		// controllo sul valore di sessione
		if (!isset($_SESSION['login']))
		{
			 // reindirizzamento alla homepage in caso di login mancato
			 header("Location: index.php");
		}		
		 
		// valorizzazione delle variabili con i parametri dal form
		if(isset($_POST['submit'])&&($_POST['submit']=="Save"))
		{
			if(isset($_POST['station_id']))
			{
		    	$station_id = addslashes(filter_var($_POST['station_id'], FILTER_SANITIZE_STRING));
		  	}
		  	if(isset($_POST['station_full_name']))
		  	{
		    	$station_full_name = addslashes(filter_var($_POST['station_full_name'], FILTER_SANITIZE_STRING));
		  	}
		  	if(isset($_POST['site_lon']))
		  	{
		    	$site_lon = addslashes(filter_var($_POST['site_lon'], FILTER_SANITIZE_STRING));
		    }
			if(isset($_POST['site_lat']))
			{
		    	$site_lat = addslashes(filter_var($_POST['site_lat'], FILTER_SANITIZE_STRING));
		  	}
		  	if(isset($_POST['operational_from']))
			{
		    	$operational_from = addslashes(filter_var($_POST['operational_from'], FILTER_SANITIZE_STRING));
		  	}
			if(isset($_POST['operational_to']))
			{
		    	$operational_to = addslashes(filter_var($_POST['operational_to'], FILTER_SANITIZE_STRING));
		  	}
		  	if(isset($_POST['manufacturer']))
		  	{
		    	$manufacturer = addslashes(filter_var($_POST['manufacturer'], FILTER_SANITIZE_STRING));
		  	}
			if(isset($_POST['EDMO_code']))
			  	{
			    	$EDMO_code = addslashes(filter_var($_POST['EDMO_code'], FILTER_SANITIZE_STRING));
			  	}
			if($EDMO_code!=0){
					if(isset($_POST['summary']))
					{
						$summary = addslashes(filter_var($_POST['summary'], FILTER_SANITIZE_STRING));
					}
					if($summary!=''){
						if(isset($_POST['DoA_estimation_method']))
						{
							$DoA_estimation_method = addslashes(filter_var($_POST['DoA_estimation_method'], FILTER_SANITIZE_STRING));
						}
						if($DoA_estimation_method!=''){
							if(isset($_POST['calibration_type']))
							{
								$calibration_type = addslashes(filter_var($_POST['calibration_type'], FILTER_SANITIZE_STRING));
							}
							if($calibration_type!='')
							{
								if(isset($_POST['calibration_link']))
								{
									$calibration_link = addslashes(filter_var($_POST['calibration_link'], FILTER_SANITIZE_STRING));
								}	
								if($calibration_link!='')
								{
									if(isset($_POST['last_calibration_date']))
									{
										$last_calibration_date = addslashes(filter_var($_POST['last_calibration_date'], FILTER_SANITIZE_STRING));
									}
									if(isset($_POST['institution_name']))
									{
										$institution_name = addslashes(filter_var($_POST['institution_name'], FILTER_SANITIZE_STRING));
									}
									if($institution_name!='')
									{
										if(isset($_POST['institution_website']))
										{
											$institution_website = addslashes(filter_var($_POST['institution_website'], FILTER_SANITIZE_STRING));
										}
										if($institution_website!=''){
											if(isset($_POST['transmit_central_frequency']))
											{
											  	$transmit_central_frequency = addslashes(filter_var($_POST['transmit_central_frequency'], FILTER_SANITIZE_STRING));
												}	
											if(isset($_POST['temporal_resolution']))
											{
												$temporal_resolution = addslashes(filter_var($_POST['temporal_resolution'], FILTER_SANITIZE_STRING));
											}
											if($temporal_resolution!=0){
												if(isset($_POST['radial_QC_velocity_threshold']))
												{
												  	$radial_QC_velocity_threshold = addslashes(filter_var($_POST['radial_QC_velocity_threshold'], FILTER_SANITIZE_STRING));
													}			
												if(isset($_POST['radial_QC_variance_threshold']))
												{
												  	$radial_QC_variance_threshold = addslashes(filter_var($_POST['radial_QC_variance_threshold'], FILTER_SANITIZE_STRING));
													}			
												if(isset($_POST['radial_QC_temporal_derivative_threshold']))
												{
												  	$radial_QC_temporal_derivative_threshold = addslashes(filter_var($_POST['radial_QC_temporal_derivative_threshold'], FILTER_SANITIZE_STRING));
													}
												if(isset($_POST['radial_QC_median_filter_RCLim']))
												{
												  	$radial_QC_median_filter_RCLim = addslashes(filter_var($_POST['radial_QC_median_filter_RCLim'], FILTER_SANITIZE_STRING));
													}
												if(isset($_POST['radial_QC_median_filter_AngLim']))
												{
												  	$radial_QC_median_filter_AngLim = addslashes(filter_var($_POST['radial_QC_median_filter_AngLim'], FILTER_SANITIZE_STRING));
													}
												if(isset($_POST['radial_QC_median_filter_CurLim']))
												{
												  	$radial_QC_median_filter_CurLim = addslashes(filter_var($_POST['radial_QC_median_filter_CurLim'], FILTER_SANITIZE_STRING));
													}			
												if(isset($_POST['radial_QC_average_radial_bearing_min']))
												{
												  	$radial_QC_average_radial_bearing_min = addslashes(filter_var($_POST['radial_QC_average_radial_bearing_min'], FILTER_SANITIZE_STRING));
													}
												if(isset($_POST['radial_QC_average_radial_bearing_max']))
												{
												  	$radial_QC_average_radial_bearing_max = addslashes(filter_var($_POST['radial_QC_average_radial_bearing_max'], FILTER_SANITIZE_STRING));
													}
												if(isset($_POST['radial_QC_radial_count_threshold']))
												{
												  	$radial_QC_radial_count_threshold = addslashes(filter_var($_POST['radial_QC_radial_count_threshold'], FILTER_SANITIZE_STRING));
													}						
												if(isset($_POST['number_of_range_cells']))
												{
												  	$number_of_range_cells = addslashes(filter_var($_POST['number_of_range_cells'], FILTER_SANITIZE_STRING));
													}			
												if(isset($_POST['radial_input_folder_path']))
												{
												  	$radial_input_folder_path = addslashes(filter_var($_POST['radial_input_folder_path'], FILTER_SANITIZE_STRING));
													}
												if(isset($_POST['radial_HFRnetCDF_folder_path']))
												{
												  	$radial_HFRnetCDF_folder_path = addslashes(filter_var($_POST['radial_HFRnetCDF_folder_path'], FILTER_SANITIZE_STRING));
													}
											 
												  if(isset($selected_station_id)){
												  	// chiamata alla funzione per l'aggiornamento dei dati
													$sql_update = "UPDATE station_tb SET station_full_name=\"" . $station_full_name . "\", site_lon=\"" . $site_lon;
													$sql_update.= "\", site_lat=\"" . $site_lat . "\", operational_from=\"" . $operational_from . "\", operational_to=\"" . $operational_to;
													$sql_update.= "\", manufacturer=\"" . $manufacturer . "\", EDMO_code=\"" . $EDMO_code . "\", summary=\"" . $summary . "\", DoA_estimation_method=\"" . $DoA_estimation_method;
													$sql_update.= "\", calibration_type=\"" . $calibration_type . "\", calibration_link=\"" . $calibration_link . "\", last_calibration_date=\"" . $last_calibration_date . "\", institution_website=\"" . $institution_website;
													$sql_update.= "\", institution_name=\"". $institution_name . "\", transmit_central_frequency=\"" . $transmit_central_frequency . "\", temporal_resolution=\"" . $temporal_resolution;
													$sql_update.= "\", radial_QC_velocity_threshold=\"" . $radial_QC_velocity_threshold . "\", radial_QC_variance_threshold=\"" . $radial_QC_variance_threshold;
													$sql_update.= "\", radial_QC_temporal_derivative_threshold=\"" . $radial_QC_temporal_derivative_threshold . "\", radial_QC_median_filter_RCLim=\"" . $radial_QC_median_filter_RCLim;
													$sql_update.= "\", radial_QC_median_filter_AngLim=\"" . $radial_QC_median_filter_AngLim . "\", radial_QC_median_filter_CurLim=\"" . $radial_QC_median_filter_CurLim;
													$sql_update.= "\", radial_QC_average_radial_bearing_min=\"" . $radial_QC_average_radial_bearing_min . "\", radial_QC_average_radial_bearing_max=\"" . $radial_QC_average_radial_bearing_max;
													$sql_update.= "\", radial_QC_radial_count_threshold=\"" . $radial_QC_radial_count_threshold . "\", number_of_range_cells=\"" . $number_of_range_cells;
													if($EU_HFR_processing_flag == 0){
														$sql_update.= "\", radial_input_folder_path=\"" . $radial_input_folder_path . "\", radial_HFRnetCDF_folder_path=\"" . $radial_HFRnetCDF_folder_path;	
													}		
													$sql_update.= "\" WHERE network_id=\"" . $current_network_id . "\" AND station_id=\"" . $selected_station_id . "\"";
													$update_query = mysql_query($sql_update, $conn) or die(mysql_error());
														
													// set void dates to NULL value
													if(($operational_from == '0000-00-00') || ($operational_from == '')){
														$sql_null_update = "UPDATE station_tb SET operational_from=NULL WHERE network_id=\"" . $current_network_id . "\" AND station_id=\"" . $selected_station_id . "\"";
														$null_update_query = mysql_query($sql_null_update, $conn) or die(mysql_error());
													}
													if(($operational_to == '0000-00-00') || ($operational_to == '')){
														$sql_null_update = "UPDATE station_tb SET operational_to=NULL WHERE network_id=\"" . $current_network_id . "\" AND station_id=\"" . $selected_station_id . "\"";
														$null_update_query = mysql_query($sql_null_update, $conn) or die(mysql_error());
													}
													if(($last_calibration_date == '0000-00-00') || ($last_calibration_date == '')){
														$sql_null_update = "UPDATE station_tb SET last_calibration_date=NULL WHERE network_id=\"" . $current_network_id . "\" AND station_id=\"" . $selected_station_id . "\"";
														$null_update_query = mysql_query($sql_null_update, $conn) or die(mysql_error());
													}	
														
													$mess = "The station information have been updated successfully.";
												}
									
												header("Location: station_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $current_network_id . "&sta=" . $selected_station_id . "&login_message=" . $mess);
											}
											else{
												$mess =  "No temporal resolution has been inserted. Please insert one.";
												header("Location: station_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $current_network_id . "&sta=" . $selected_station_id . "&login_message=" . $mess);
											}
										}
										else{
											$mess =  "No institution website has been inserted. Please insert one.";
											header("Location: station_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $current_network_id . "&sta=" . $selected_station_id . "&login_message=" . $mess);
										}
									}
									else{
										$mess =  "No institution name has been inserted. Please insert one.";
										header("Location: station_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $current_network_id . "&sta=" . $selected_station_id . "&login_message=" . $mess);
									}
								}	
								else{
									$mess =  "No calibration link has been inserted. Please insert one.";
									header("Location: station_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $current_network_id . "&sta=" . $selected_station_id . "&login_message=" . $mess);
								}	
							}
							else{
								$mess =  "No calibration type has been inserted. Please insert one.";
								header("Location: station_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $current_network_id . "&sta=" . $selected_station_id . "&login_message=" . $mess);
							}
						}
						else{
							$mess =  "No Direction of Arrival estimation method has been inserted. Please insert one.";
							header("Location: station_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $current_network_id . "&sta=" . $selected_station_id . "&login_message=" . $mess);
						}
					}
					else{
						$mess =  "No summary has been inserted. Please insert one.";
						header("Location: station_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $current_network_id . "&sta=" . $selected_station_id . "&login_message=" . $mess);
					}
				}
				else{
					$mess =  "No EDMO code has been inserted. Please insert one.";
					header("Location: station_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $current_network_id . "&sta=" . $selected_station_id . "&login_message=" . $mess);
				}
			}
		else
		{
			// menu a tendina
			?>
			<b>Select the station:</b>
			<form action="<?php echo $_SERVER['PHP_SELF'] . "?usr=" . $_GET["usr"] . "&ntw=" . $current_network_id; ?>" method="post">
			<select name="selected_station">
				<?php
				while ($row=mysql_fetch_array($result_stations)){
					$station=$row['station_id'];
					echo("<option value=\"" . $station . "\">" . $station . "</option>");
				}
				echo("<option value=\"Add new station\">Add new station</option>");
				if(isset($selected_station_id)){
					echo("<option selected=\"selected\" value=\"" . $selected_station_id . "\">" . $selected_station_id . "</option>");
				}
				else{
					echo("<option selected=\"selected\"> Select the station </option>");
				}
				?>
			</select>
			<input name="submit" type="submit" value="Select">
			</form>
			
			<?	
			// form per l'inserimento
			if($selected_station_id == "Add new station"){
				echo("<br><br><b>Please insert information about the new station from " . $current_network_id . " network</b>");
			}
			else {
				echo("<br><br><b>Please insert information about the " . $selected_station_id . " station from " . $current_network_id . " network (* = mandatory fields)</b>");
			}
			
			// recupero eventuali informazioni inserite in precedenza
			$previous = $selected_station;
			$previous_station_full_name = $previous["station_full_name"];
			$previous_site_lon = $previous["site_lon"];
			$previous_site_lat = $previous["site_lat"];
			$previous_operational_from = $previous["operational_from"];
			$previous_operational_to = $previous["operational_to"];
			$previous_EDMO_code = $previous["EDMO_code"];
			$previous_summary = $previous["summary"];
			$previous_DoA_estimation_method = $previous["DoA_estimation_method"];
			$previous_calibration_type = $previous["calibration_type"];
			$previous_calibration_link = $previous["calibration_link"];			
			$previous_last_calibration_date = $previous["last_calibration_date"];
			$previous_institution_name = $previous["institution_name"];
			$previous_institution_website = $previous["institution_website"];
			$previous_manufacturer = $previous["manufacturer"];	
			$previous_transmit_central_frequency = $previous["transmit_central_frequency"];		
			$previous_temporal_resolution = $previous["temporal_resolution"];		
			$previous_radial_QC_velocity_threshold = $previous["radial_QC_velocity_threshold"];
			$previous_radial_QC_variance_threshold = $previous["radial_QC_variance_threshold"];
			$previous_radial_QC_temporal_derivative_threshold = $previous["radial_QC_temporal_derivative_threshold"];			
			$previous_radial_QC_median_filter_RCLim = $previous["radial_QC_median_filter_RCLim"];
			$previous_radial_QC_median_filter_AngLim = $previous["radial_QC_median_filter_AngLim"];
			$previous_radial_QC_median_filter_CurLim = $previous["radial_QC_median_filter_CurLim"];			
			$previous_radial_QC_average_radial_bearing_min = $previous["radial_QC_average_radial_bearing_min"];
			$previous_radial_QC_average_radial_bearing_max = $previous["radial_QC_average_radial_bearing_max"];		
			$previous_radial_QC_radial_count_threshold = $previous["radial_QC_radial_count_threshold"];			
			$previous_number_of_range_cells = $previous["number_of_range_cells"];			
			$previous_radial_input_folder_path = $previous["radial_input_folder_path"];
			$previous_radial_HFRnetCDF_folder_path = $previous["radial_HFRnetCDF_folder_path"];
						
			?>
			<form action="<?php echo $_SERVER['PHP_SELF'] . "?usr=" . $_GET["usr"] . "&ntw=" . $current_network_id . "&sta=" . $selected_station_id; ?>" method="post">
			<?php 
			echo("<br>Network ID: " . $current_network_id . "<br />");
			?>
			<br>Station full name:<br>			
			<textarea name="station_full_name" cols="80" rows="5"><?php echo($previous_station_full_name);?></textarea><br />
			<br>Site longitude (decimal degrees):<br>
			<input name="site_lon" type="number" size="80" step="0.00001" min="-180" max="180" value="<?php echo($previous_site_lon);?>"><br />		
			<br>Site latitude (decimal degrees):<br>
			<input name="site_lat" type="number" size="80" step="0.00001" min="-90" max="90" value="<?php echo($previous_site_lat);?>"><br />
			<br>Operational from (YYYY-MM-DD):<br>			
			<input name="operational_from" type="text" size="80" value="<?php echo($previous_operational_from);?>"><br />
			<br>Operational to (YYYY-MM-DD):<br>			
			<input name="operational_to" type="text" size="80" value="<?php echo($previous_operational_to);?>"><br />	
			<br>Manufacturer:<br>
			<input name="manufacturer" type="text" size="80" value="<?php echo($previous_manufacturer);?>"><br />	
			<br><b>EDMO code of the institution responsible for the HFR station management and radial files generation*</b>:<br>
			<input name="EDMO_code" type="number" size="80" value="<?php echo($previous_EDMO_code);?>"><br />	
			<br><b>Institution responsible for the HFR station management and radial files generation*</b>:<br>			
			<input name="institution_name" type="text" size="80" value="<?php echo($previous_institution_name);?>"><br />	
			<br><b>Website of the institution responsible for the HFR station management and radial files generation*</b>:<br>			
			<input name="institution_website" type="text" size="80" value="<?php echo($previous_institution_website);?>"><br />		
			<br><b>Summary*</b>:<br>			
			<textarea name="summary" cols="80" rows="5"><?php echo($previous_summary);?></textarea><br />	
			<br><b>Direction of Arrival estimation method*</b>:<br>
			<input name="DoA_estimation_method" type="text" size="80" value="<?php echo($previous_DoA_estimation_method);?>"><br />		
			<br><b>Calibration type*</b>:<br>			
			<input name="calibration_type" type="text" size="80" value="<?php echo($previous_calibration_type);?>"><br />	
			<br><b>Calibration link*</b>:<br>			
			<input name="calibration_link" type="text" size="80" value="<?php echo($previous_calibration_link);?>"><br />	
			<br>Last calibration date (YYYY-MM-DD):<br>			
			<input name="last_calibration_date" type="text" size="80" value="<?php echo($previous_last_calibration_date);?>"><br />	
			<br>Transmit central frequency (MHz):<br>
			<input name="transmit_central_frequency" type="number" size="80" step="0.00001" min="0" max="100" value="<?php echo($previous_transmit_central_frequency);?>"><br />	
			<br><b>Temporal resolution (decimal minutes, e.g. 37'30"= 37.5 minutes)*</b>:<br>
			<input name="temporal_resolution" type="number" size="80" step="0.01" min="0" max="100" value="<?php echo($previous_temporal_resolution);?>"><br />
			<br>Radial QC velocity threshold (m/s):<br>
			<input name="radial_QC_velocity_threshold" type="number" size="80" step="0.01" min="0" max="100" value="<?php echo($previous_radial_QC_velocity_threshold);?>"><br />	
			<br>Radial QC variance threshold (m/s):<br>
			<input name="radial_QC_variance_threshold" type="number" size="80" step="0.01" min="0" max="100" value="<?php echo($previous_radial_QC_variance_threshold);?>"><br />	
			<br>Radial QC temporal derivative threshold (m/s):<br>
			<input name="radial_QC_temporal_derivative_threshold" type="number" size="80" step="0.01" min="0" max="100" value="<?php echo($previous_radial_QC_temporal_derivative_threshold);?>"><br />
			<br>Radial QC median filter Radius Circle (km):<br>
			<input name="radial_QC_median_filter_RCLim" type="number" size="80" step="0.01" min="0" max="100" value="<?php echo($previous_radial_QC_median_filter_RCLim);?>"><br />	
			<br>Radial QC median filter Angular Limit (degrees):<br>
			<input name="radial_QC_median_filter_AngLim" type="number" size="80" step="0.01" min="0" max="360" value="<?php echo($previous_radial_QC_median_filter_AngLim);?>"><br />
			<br>Radial QC median filter Current Limit (m/s):<br>
			<input name="radial_QC_median_filter_CurLim" type="number" size="80" step="0.01" min="0" max="100" value="<?php echo($previous_radial_QC_median_filter_CurLim);?>"><br />
			<br>Radial QC average radial bearing minimum (degrees):<br>
			<input name="radial_QC_average_radial_bearing_min" type="number" size="80" step="0.01" min="0" max="360" value="<?php echo($previous_radial_QC_average_radial_bearing_min);?>"><br />
			<br>Radial QC average radial bearing maximum (degrees):<br>
			<input name="radial_QC_average_radial_bearing_max" type="number" size="80" step="0.01" min="0" max="360" value="<?php echo($previous_radial_QC_average_radial_bearing_max);?>"><br />
			<br>Radial QC radial count threshold:<br>
			<input name="radial_QC_radial_count_threshold" type="number" size="80" value="<?php echo($previous_radial_QC_radial_count_threshold);?>"><br />	
			<br>Maximum range (km) - set it bigger than the real one, use it as an upper bound (e.g. 1.5*real max range):<br>
			<input name="number_of_range_cells" type="number" size="80" value="<?php echo($previous_number_of_range_cells);?>"><br />						
			<?php
			if($EU_HFR_processing_flag == 0){
				echo("<br>Radial files input folder path:<br>");			
				echo("<input name=\"radial_input_folder_path\" type=\"text\" size=\"80\" value=\"" . $previous_radial_input_folder_path . "\"><br />");			
				echo("<br>Radial netCDF output files folder path:<br>");		
				echo("<input name=\"radial_HFRnetCDF_folder_path\" type=\"text\" size=\"80\" value=\"" . $previous_radial_HFRnetCDF_folder_path . "\"><br />");			
			}
			?>	
			<input name="submit" type="submit" value="Save">
			</form>
			<?
		}
		?>
	<!-- end #station_form --></div>    
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
