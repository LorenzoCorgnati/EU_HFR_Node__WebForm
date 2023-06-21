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

if($_GET["ntw"] != ''){
	$current_network_id = $_GET["ntw"];
	$sql_current_network = "SELECT * FROM network_tb WHERE network_id='" . $current_network_id . "'";
	$result_current_network = mysqli_query($conn, $sql_current_network) or die(mysqli_error());
	$current_network = mysqli_fetch_assoc($result_current_network);
	$EU_HFR_processing_flag = $current_network['EU_HFR_processing_flag'];
}

if($_GET["sta"] != ''){
	$selected_station_id = $_GET["sta"];
	$sql_selected_station = "SELECT * FROM station_tb WHERE network_id='" . $current_network_id . "' AND station_id='" . $selected_station_id . "'";
	$result_selected_station = mysqli_query($conn, $sql_selected_station) or die(mysqli_error());
	$selected_station = mysqli_fetch_assoc($result_selected_station);	
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
		$result_selected_station = mysqli_query($conn, $sql_selected_station) or die(mysqli_error());
		$selected_station = mysqli_fetch_assoc($result_selected_station);
	}	
}

// Query EU HFR node DB for retrieving the stations associated to the current network
$sql_stations = "SELECT * FROM station_tb WHERE network_id='" . $current_network_id . "'";
$result_stations = mysqli_query($conn, $sql_stations) or die(mysqli_error());
$num_rows_stations = mysqli_num_rows($result_stations);
$num_fields_stations = mysqli_num_fields($result_stations);

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
    <h1>HFR stations information</h1>
    <div id="Buttons">
    	<a href="https://cnrsc-my.sharepoint.com/:b:/g/personal/lorenzopaolo_corgnati_cnr_it/Ecoa9zTYUmVNiXuDxiKWslQBqwbtuG3r1RVwdI5Dy-E9zg?e=bylH89" target="_blank"><button style="height:40px; width:200px">Webform User Manual</button><a>
    		<br><br>
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

		// recupero delle informazioni inserite nel form
		if($_GET['sfn'] != '')
		{
			$_SESSION['station_full_name'] = $_GET['sfn'];
		}
		if($_GET['sln'] != '')
		{
			$_SESSION['site_lon'] = $_GET['sln'];
		}
		if($_GET['slt'] != '')
		{
			$_SESSION['site_lat'] = $_GET['slt'];
		}
		if($_GET['opf'] != '')
		{
			$_SESSION['operational_from'] = $_GET['opf'];
		}
		if($_GET['opt'] != '')
		{
			$_SESSION['operational_to'] = $_GET['opt'];
		}
		if($_GET['EDM'] != '')
		{
			$_SESSION['EDMO_code'] = $_GET['EDM'];
		}
		if($_GET['sum'] != '')
		{
			$_SESSION['summary'] = $_GET['sum'];
		}
		if($_GET['Dem'] != '')
		{
			$_SESSION['DoA_estimation_method'] = $_GET['Dem'];
		}
		if($_GET['clt'] != '')
		{
			$_SESSION['calibration_type'] = $_GET['clt'];
		}
		if($_GET['cll'] != '')
		{
			$_SESSION['calibration_link'] = $_GET['cll'];
		}
		if($_GET['lcd'] != '')
		{
			$_SESSION['last_calibration_date'] = $_GET['lcd'];
		}
		if($_GET['inn'] != '')
		{
			$_SESSION['institution_name'] = $_GET['inn'];
		}
		if($_GET['inw'] != '')
		{
			$_SESSION['institution_website'] = $_GET['inw'];
		}
		if($_GET['mnf'] != '')
		{
			$_SESSION['manufacturer'] = $_GET['mnf'];
		}
		if($_GET['tcf'] != '')
		{
			$_SESSION['transmit_central_frequency'] = $_GET['tcf'];
		}
		if($_GET['ntx'] != '')
		{
			$_SESSION['number_of_transmit_antennas'] = $_GET['ntx'];
		}
		if($_GET['nrx'] != '')
		{
			$_SESSION['number_of_receive_antennas'] = $_GET['nrx'];
		}
		if($_GET['trs'] != '')
		{
			$_SESSION['temporal_resolution'] = $_GET['trs'];
		}
		if($_GET['vel'] != '')
		{
			$_SESSION['radial_QC_velocity_threshold'] = $_GET['vel'];
		}
		if($_GET['var'] != '')
		{
			$_SESSION['radial_QC_variance_threshold'] = $_GET['var'];
		}
		if($_GET['tdr'] != '')
		{
			$_SESSION['radial_QC_temporal_derivative_threshold'] = $_GET['tdr'];
		}
		if($_GET['mfR'] != '')
		{
			$_SESSION['radial_QC_median_filter_RCLim'] = $_GET['mfR'];
		}
		if($_GET['mfA'] != '')
		{
			$_SESSION['radial_QC_median_filter_AngLim'] = $_GET['mfA'];
		}
		if($_GET['mfC'] != '')
		{
			$_SESSION['radial_QC_median_filter_CurLim'] = $_GET['mfC'];
		}
		if($_GET['rbn'] != '')
		{
			$_SESSION['radial_QC_average_radial_bearing_min'] = $_GET['rbn'];
		}
		if($_GET['rbx'] != '')
		{
			$_SESSION['radial_QC_average_radial_bearing_max'] = $_GET['rbx'];
		}
		if($_GET['rdc'] != '')
		{
			$_SESSION['radial_QC_radial_count_threshold'] = $_GET['rdc'];
		}
		if($_GET['nrc'] != '')
		{
			$_SESSION['number_of_range_cells'] = $_GET['nrc'];
		}
		if($_GET['rip'] != '')
		{
			$_SESSION['radial_input_folder_path'] = $_GET['rip'];
		}
		if($_GET['rhp'] != '')
		{
			$_SESSION['radial_HFRnetCDF_folder_path'] = $_GET['rhp'];
		}
		 
		// valorizzazione delle variabili con i parametri dal form
		if(isset($_POST['submit'])&&($_POST['submit']=="Save"))
		{
			// archiviazione delle informazioni già inserite nella variabili di sessione			
			if(isset($_POST['station_full_name']))
			{
				$_SESSION['station_full_name'] = addslashes(filter_var($_POST['station_full_name'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['site_lon']))
			{
				$_SESSION['site_lon'] = addslashes(filter_var($_POST['site_lon'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['site_lat']))
			{
				$_SESSION['site_lat'] = addslashes(filter_var($_POST['site_lat'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['operational_from']))
			{
				$_SESSION['operational_from'] = addslashes(filter_var($_POST['operational_from'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['operational_to']))
			{
				$_SESSION['operational_to'] = addslashes(filter_var($_POST['operational_to'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['EDMO_code']))
			{
				$_SESSION['EDMO_code'] = addslashes(filter_var($_POST['EDMO_code'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['summary']))
			{
				$_SESSION['summary'] = addslashes(filter_var($_POST['summary'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['DoA_estimation_method']))
			{
				$_SESSION['DoA_estimation_method'] = addslashes(filter_var($_POST['DoA_estimation_method'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['calibration_type']))
			{
				$_SESSION['calibration_type'] = addslashes(filter_var($_POST['calibration_type'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['calibration_link']))
			{
				$_SESSION['calibration_link'] = addslashes(filter_var($_POST['calibration_link'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['last_calibration_date']))
			{
				$_SESSION['last_calibration_date'] = addslashes(filter_var($_POST['last_calibration_date'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['institution_name']))
			{
				$_SESSION['institution_name'] = addslashes(filter_var($_POST['institution_name'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['institution_website']))
			{
				$_SESSION['institution_website'] = addslashes(filter_var($_POST['institution_website'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['manufacturer']))
			{
				$_SESSION['manufacturer'] = addslashes(filter_var($_POST['manufacturer'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['transmit_central_frequency']))
			{
				$_SESSION['transmit_central_frequency'] = addslashes(filter_var($_POST['transmit_central_frequency'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['number_of_transmit_antennas']))
			{
				$_SESSION['number_of_transmit_antennas'] = addslashes(filter_var($_POST['number_of_transmit_antennas'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['number_of_receive_antennas']))
			{
				$_SESSION['number_of_receive_antennas'] = addslashes(filter_var($_POST['number_of_receive_antennas'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['temporal_resolution']))
			{
				$_SESSION['temporal_resolution'] = addslashes(filter_var($_POST['temporal_resolution'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['radial_QC_velocity_threshold']))
			{
				$_SESSION['radial_QC_velocity_threshold'] = addslashes(filter_var($_POST['radial_QC_velocity_threshold'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['radial_QC_variance_threshold']))
			{
				$_SESSION['radial_QC_variance_threshold'] = addslashes(filter_var($_POST['radial_QC_variance_threshold'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['radial_QC_temporal_derivative_threshold']))
			{
				$_SESSION['radial_QC_temporal_derivative_threshold'] = addslashes(filter_var($_POST['radial_QC_temporal_derivative_threshold'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['radial_QC_median_filter_RCLim']))
			{
				$_SESSION['radial_QC_median_filter_RCLim'] = addslashes(filter_var($_POST['radial_QC_median_filter_RCLim'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['radial_QC_median_filter_AngLim']))
			{
				$_SESSION['radial_QC_median_filter_AngLim'] = addslashes(filter_var($_POST['radial_QC_median_filter_AngLim'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['radial_QC_median_filter_CurLim']))
			{
				$_SESSION['radial_QC_median_filter_CurLim'] = addslashes(filter_var($_POST['radial_QC_median_filter_CurLim'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['radial_QC_average_radial_bearing_min']))
			{
				$_SESSION['radial_QC_average_radial_bearing_min'] = addslashes(filter_var($_POST['radial_QC_average_radial_bearing_min'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['radial_QC_average_radial_bearing_max']))
			{
				$_SESSION['radial_QC_average_radial_bearing_max'] = addslashes(filter_var($_POST['radial_QC_average_radial_bearing_max'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['radial_QC_radial_count_threshold']))
			{
				$_SESSION['radial_QC_radial_count_threshold'] = addslashes(filter_var($_POST['radial_QC_radial_count_threshold'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['number_of_range_cells']))
			{
				$_SESSION['number_of_range_cells'] = addslashes(filter_var($_POST['number_of_range_cells'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['radial_input_folder_path']))
			{
				$_SESSION['radial_input_folder_path'] = addslashes(filter_var($_POST['radial_input_folder_path'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['radial_HFRnetCDF_folder_path']))
			{
				$_SESSION['radial_HFRnetCDF_folder_path'] = addslashes(filter_var($_POST['radial_HFRnetCDF_folder_path'], FILTER_SANITIZE_STRING));
			}

		// String for recover session data
			$recoverSession = "&err=1&sfn=" . $_SESSION['station_full_name'] . "&sln=" . $_SESSION['site_lon'] . "&slt=" . $_SESSION['site_lat'] . "&opf=" . $_SESSION['operational_from']
												 . "&opt=" . $_SESSION['operational_to'] . "&EDM=" . $_SESSION['EDMO_code'] . "&sum=" . $_SESSION['summary'] . "&Dem=" . $_SESSION['DoA_estimation_method']
												 . "&clt=" . $_SESSION['calibration_type']  . "&cll=" . $_SESSION['calibration_link'] . "&lcd=" . $_SESSION['last_calibration_date'] . "&inn=" . $_SESSION['institution_name']
												 . "&inw=" . $_SESSION['institution_website'] . "&mnf=" . $_SESSION['manufacturer'] . "&tcf=" . $_SESSION['transmit_central_frequency']
												 . "&ntx=" . $_SESSION['number_of_transmit_antennas']. "&nrx=" . $_SESSION['number_of_receive_antennas']
												 . "&trs=" . $_SESSION['temporal_resolution'] . "&vel=" . $_SESSION['radial_QC_velocity_threshold'] . "&var=" . $_SESSION['radial_QC_variance_threshold']
												 . "&tdr=" . $_SESSION['radial_QC_temporal_derivative_threshold'] . "&mfR=" . $_SESSION['radial_QC_median_filterRCLim'] . "&mfA=" . $_SESSION['radial_QC_median_filterAngLim']
												 . "&mfC=" . $_SESSION['radial_QC_median_filterCurLim'] . "&rbn=" . $_SESSION['radial_QC_average_radial_bearing_min']
												 . "&rbx=" . $_SESSION['radial_QC_average_radial_bearing_max'] . "&rdc=" . $_SESSION['radial_QC_raial_count_threshold'] . "&nrc=" . $_SESSION['number_of_range_cells']
												 . "&rip=" . $_SESSION['radial_input_folder_path']. "&rhp=" . $_SESSION['radial_HFRnetCDF_folder_path'];

			// check esistenza e valorizzazione delle variabili con i parametri dal form
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
										if(isset($_POST['number_of_transmit_antennas']))
										{
											$number_of_transmit_antennas = addslashes(filter_var($_POST['number_of_transmit_antennas'], FILTER_SANITIZE_STRING));
										}
										if(isset($_POST['number_of_receive_antennas']))
										{
											$number_of_receive_antennas = addslashes(filter_var($_POST['number_of_receive_antennas'], FILTER_SANITIZE_STRING));
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
												$sql_update.= "\", site_lat=\"" . $site_lat;
												$sql_update.= "\", manufacturer=\"" . $manufacturer . "\", EDMO_code=\"" . $EDMO_code . "\", summary=\"" . $summary . "\", DoA_estimation_method=\"" . $DoA_estimation_method;
												$sql_update.= "\", calibration_type=\"" . $calibration_type . "\", calibration_link=\"" . $calibration_link . "\", institution_website=\"" . $institution_website;
												$sql_update.= "\", institution_name=\"" . $institution_name . "\", transmit_central_frequency=\"" . $transmit_central_frequency . "\", temporal_resolution=\"" . $temporal_resolution;
												$sql_update.= "\", number_of_transmit_antennas=\"" . $number_of_transmit_antennas . "\", number_of_receive_antennas=\"" . $number_of_receive_antennas;
												$sql_update.= "\", radial_QC_velocity_threshold=\"" . $radial_QC_velocity_threshold . "\", radial_QC_variance_threshold=\"" . $radial_QC_variance_threshold;
												$sql_update.= "\", radial_QC_temporal_derivative_threshold=\"" . $radial_QC_temporal_derivative_threshold . "\", radial_QC_median_filter_RCLim=\"" . $radial_QC_median_filter_RCLim;
												$sql_update.= "\", radial_QC_median_filter_AngLim=\"" . $radial_QC_median_filter_AngLim . "\", radial_QC_median_filter_CurLim=\"" . $radial_QC_median_filter_CurLim;
												$sql_update.= "\", radial_QC_average_radial_bearing_min=\"" . $radial_QC_average_radial_bearing_min . "\", radial_QC_average_radial_bearing_max=\"" . $radial_QC_average_radial_bearing_max;
												$sql_update.= "\", radial_QC_radial_count_threshold=\"" . $radial_QC_radial_count_threshold . "\", number_of_range_cells=\"" . $number_of_range_cells;
												if($EU_HFR_processing_flag == 0){
													$sql_update.= "\", radial_input_folder_path=\"" . $radial_input_folder_path . "\", radial_HFRnetCDF_folder_path=\"" . $radial_HFRnetCDF_folder_path;	
												}		
												$sql_update.= "\" WHERE network_id=\"" . $current_network_id . "\" AND station_id=\"" . $selected_station_id . "\"";
												$update_query = mysqli_query($conn, $sql_update) or die(mysqli_error());
														
												// set void dates to NULL value
												if(($operational_from == '0000-00-00') || ($operational_from == '')){
													$sql_null_update = "UPDATE station_tb SET operational_from=NULL WHERE network_id=\"" . $current_network_id . "\" AND station_id=\"" . $selected_station_id . "\"";
													$null_update_query = mysqli_query($conn, $sql_null_update) or die(mysqli_error());
												}
												else{
													$sql_opf_update = "UPDATE station_tb SET operational_from=\"" . $operational_from . "\" WHERE network_id=\"" . $current_network_id . "\" AND station_id=\"" . $selected_station_id . "\"";
													$opf_update_query = mysqli_query($conn, $sql_opf_update) or die(mysqli_error());
												}
												if(($operational_to == '0000-00-00') || ($operational_to == '')){
													$sql_null_update = "UPDATE station_tb SET operational_to=NULL WHERE network_id=\"" . $current_network_id . "\" AND station_id=\"" . $selected_station_id . "\"";
													$null_update_query = mysqli_query($conn, $sql_null_update) or die(mysqli_error());
												}
												else{
													$sql_opt_update = "UPDATE station_tb SET operational_to=\"" . $operational_to . "\" WHERE network_id=\"" . $current_network_id . "\" AND station_id=\"" . $selected_station_id . "\"";
													$opt_update_query = mysqli_query($conn, $sql_opt_update) or die(mysqli_error());
												}
												if(($last_calibration_date == '0000-00-00') || ($last_calibration_date == '')){
													$sql_null_update = "UPDATE station_tb SET last_calibration_date=NULL WHERE network_id=\"" . $current_network_id . "\" AND station_id=\"" . $selected_station_id . "\"";
													$null_update_query = mysqli_query($conn, $sql_null_update) or die(mysqli_error());
												}
												else{
													$sql_lcd_update = "UPDATE station_tb SET last_calibration_date=\"" . $last_calibration_date . "\" WHERE network_id=\"" . $current_network_id . "\" AND station_id=\"" . $selected_station_id . "\"";
													$lcd_update_query = mysqli_query($conn, $sql_lcd_update) or die(mysqli_error());
												}	
														
												$mess = "The station information have been updated successfully.";
											}
									
											header("Location: station_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $current_network_id . "&sta=" . $selected_station_id . "&login_message=" . $mess);
										}
										else{
											$mess =  "No temporal resolution has been inserted. Please insert one.";
											header("Location: station_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $current_network_id . "&sta=" . $selected_station_id . "&login_message=" . $mess . $recoverSession);
										}
									}
									else{
										$mess =  "No institution website has been inserted. Please insert one.";
										header("Location: station_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $current_network_id . "&sta=" . $selected_station_id . "&login_message=" . $mess . $recoverSession);
									}
								}
								else{
									$mess =  "No institution name has been inserted. Please insert one.";
									header("Location: station_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $current_network_id . "&sta=" . $selected_station_id . "&login_message=" . $mess . $recoverSession);
								}
							}	
							else{
								$mess =  "No calibration link has been inserted. Please insert one.";
								header("Location: station_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $current_network_id . "&sta=" . $selected_station_id . "&login_message=" . $mess . $recoverSession);
							}	
						}
						else{
							$mess =  "No calibration type has been inserted. Please insert one.";
							header("Location: station_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $current_network_id . "&sta=" . $selected_station_id . "&login_message=" . $mess . $recoverSession);
						}
					}
					else{
						$mess =  "No Direction of Arrival estimation method has been inserted. Please insert one.";
						header("Location: station_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $current_network_id . "&sta=" . $selected_station_id . "&login_message=" . $mess . $recoverSession);
					}
				}
				else{
					$mess =  "No summary has been inserted. Please insert one.";
					header("Location: station_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $current_network_id . "&sta=" . $selected_station_id . "&login_message=" . $mess . $recoverSession);
				}
			}
			else{
				$mess =  "No EDMO code has been inserted. Please insert one.";
				header("Location: station_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $current_network_id . "&sta=" . $selected_station_id . "&login_message=" . $mess . $recoverSession);
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
			while ($row=mysqli_fetch_array($result_stations)){
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
			
		<?php	
			// form per l'inserimento
			if($selected_station_id == "Add new station"){
				echo("<br><br><b>Please insert information about the new station from " . $current_network_id . " network</b>");
			}
			else {
				echo("<br><br><b>Please insert information about the " . $selected_station_id . " station from " . $current_network_id . " network (* = mandatory fields)</b>");
			}
				
			// recupero eventuali informazioni inserite in precedenza
			if($_GET['err'] == 1)
			{
				$previous = $_SESSION;
			}
			else
			{
				$previous = $selected_station;
			}
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
			$previous_number_of_transmit_antennas = $previous["number_of_transmit_antennas"];	
			$previous_number_of_receive_antennas = $previous["number_of_receive_antennas"];
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
		<br>Number of transmit antennas:<br>
		<input name="number_of_transmit_antennas" type="number" size="80" step="1" min="0" max="100" value="<?php echo($previous_number_of_transmit_antennas);?>"><br />	
		<br>Number of receive antennas:<br>
		<input name="number_of_receive_antennas" type="number" size="80" step="1" min="0" max="100" value="<?php echo($previous_number_of_receive_antennas);?>"><br />	
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
		<br>Maximum number of range cells - set it bigger than the real one, use it as an upper bound (e.g. 1.5*real maximum number of range cells):<br>
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
		<?php
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
mysqli_close($conn);
?>
