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
	$selected_network_id = $_GET["ntw"];
	$sql_selected_network = "SELECT * FROM network_tb WHERE network_id='" . $selected_network_id . "'";
	$result_selected_network = mysql_query($sql_selected_network, $conn) or die(mysql_error());
	$selected_network = mysql_fetch_assoc($result_selected_network);
	$EU_HFR_processing_flag = $selected_network['EU_HFR_processing_flag'];
}

if($_POST["selected_network"] != ''){
	$selected_network_id = $_POST["selected_network"];
	if($selected_network_id == "Add new network"){
		header("Location: new_network.php?usr=" . $_GET["usr"]);
	}
	elseif($selected_network_id == "Select the network"){
		$mess = "No network has been selected. Please select one.";
		header("Location: network_WF.php?usr=" . $_GET["usr"] . "&login_message=" . $mess);
	}
	else{
		$sql_selected_network = "SELECT * FROM network_tb WHERE network_id='" . $selected_network_id . "'";
		$result_selected_network = mysql_query($sql_selected_network, $conn) or die(mysql_error());
		$selected_network = mysql_fetch_assoc($result_selected_network);
	}
}

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
	$num_networks = count($network_array);
}
else{
	$stripped = str_replace(' ', '', $networks);
	$network_array = explode(',', $stripped);
	$num_networks = count($network_array);
}
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>The European HFR Node</title>

<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
<link href="/css/EU_HFR_WF_StyleSheet.css" rel="stylesheet" type="text/css">

<script type="text/javascript">
function getfolder(e) {
    var files = e.target.files;
    var path = files[0].webkitRelativePath;
    var Folder = path.split("/");
    alert(Folder[0]);
}
</script>

</head>

<body class="twoColFixRtHdr">

<div id="container">
  <div id="header" style="background: url('images/header_prova2.png')">
    <h1>EU HFR NODE - Data Entry Web Form</h1><br>
    Web Form for managing HFR network information
  <!-- end #header --></div>
  
  <div id="mainContent">
    <h1>HFR networks information</h1>
    <div id="Buttons">
    	<a href="index.php?logout=true"><button style="height:40px; width:200px">Logout</button><a>
    		<br><br>
    	<?php echo("<a href=\"edit_account.php?usr=" . $_GET['usr'] . "\"><button style=\"height:40px; width:200px\">Edit your profile</button><a>"); ?>
    		<br><br>
    	<?php
	    if(isset($selected_network_id)){
	    	echo("<a href=\"station_WF.php?usr=" . $_GET['usr'] . "&ntw=" . $selected_network_id . "\"><button style=\"height:100px; width:200px\">Station Web Form</button><a>");
		}
	  	?>
  	</div>
    
    <div id="network_form">
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
		if($_GET['opf'] != '')
		{
			$_SESSION['operational_from'] = $_GET['opf'];
		}
		if($_GET['opt'] != '')
		{
			$_SESSION['operational_to'] = $_GET['opt'];
		}
		if($_GET['EDS'] != '')
		{
			$_SESSION['EDIOS_Series_id'] = $_GET['EDS'];
		}
		if($_GET['EDM'] != '')
		{
			$_SESSION['EDMO_code'] = $_GET['EDM'];
		}
		if($_GET['mtp'] != '')
		{
			$_SESSION['metadata_page'] = $_GET['mtp'];
		}
		if($_GET['tit'] != '')
		{
			$_SESSION['title'] = $_GET['tit'];
		}
		if($_GET['sum'] != '')
		{
			$_SESSION['summary'] = $_GET['sum'];
		}
		if($_GET['inm'] != '')
		{
			$_SESSION['institution_name'] = $_GET['inn'];
		}
		if($_GET['cit'] != '')
		{
			$_SESSION['citation_statement'] = $_GET['cit'];
		}
		if($_GET['lic'] != '')
		{
			$_SESSION['license'] = $_GET['lic'];
		}
		if($_GET['ack'] != '')
		{
			$_SESSION['acknowledgment'] = $_GET['ack'];
		}
		if($_GET['vel'] != '')
		{
			$_SESSION['total_QC_velocity_threshold'] = $_GET['vel'];
		}
		if($_GET['gdp'] != '')
		{
			$_SESSION['total_QC_GDOP_threshold'] = $_GET['gdp'];
		}
		if($_GET['var'] != '')
		{
			$_SESSION['total_QC_variance_threshold'] = $_GET['var'];
		}
		if($_GET['tdr'] != '')
		{
			$_SESSION['total_QC_temporal_derivative_threshold'] = $_GET['tdr'];
		}
		if($_GET['ddn'] != '')
		{
			$_SESSION['total_QC_data_density_threshold'] = $_GET['ddn'];
		}
		if($_GET['prj'] != '')
		{
			$_SESSION['project'] = $_GET['prj'];
		}
		if($_GET['inw'] != '')
		{
			$_SESSION['institution_website'] = $_GET['inw'];
		}
		if($_GET['cnn'] != '')
		{
			$_SESSION['contributor_name'] = $_GET['cnn'];
		}
		if($_GET['cnr'] != '')
		{
			$_SESSION['contributor_role'] = $_GET['cnr'];
		}
		if($_GET['cne'] != '')
		{
			$_SESSION['contributor_email'] = $_GET['cne'];
		}
		if($_GET['com'] != '')
		{
			$_SESSION['comment'] = $_GET['com'];
		}
		if($_GET['ntn'] != '')
		{
			$_SESSION['network_name'] = $_GET['ntn'];
		}
		if($_GET['are'] != '')
		{
			$_SESSION['area'] = $_GET['are'];
		}
		if($_GET['lnn'] != '')
		{
			$_SESSION['geospatial_lon_min'] = $_GET['lnn'];
		}
		if($_GET['lnx'] != '')
		{
			$_SESSION['geospatial_lon_max'] = $_GET['lnx'];
		}
		if($_GET['ltn'] != '')
		{
			$_SESSION['geospatial_lat_min'] = $_GET['ltn'];
		}
		if($_GET['ltx'] != '')
		{
			$_SESSION['geospatial_lat_max'] = $_GET['ltx'];
		}
		if($_GET['grs'] != '')
		{
			$_SESSION['grid_resolution'] = $_GET['grs'];
		}
		if($_GET['trs'] != '')
		{
			$_SESSION['temporal_resolution'] = $_GET['trs'];
		}
		if($_GET['rbg'] != '')
		{
			$_SESSION['region_bigram'] = $_GET['rbg'];
		}
		if($_GET['csr'] != '')
		{
			$_SESSION['combination_search_radius'] = $_GET['csr'];
		}
		if($_GET['tip'] != '')
		{
			$_SESSION['total_input_folder_path'] = $_GET['tip'];
		}
		if($_GET['thp'] != '')
		{
			$_SESSION['total_HFRnetCDF_folder_path'] = $_GET['thp'];
		}
		if($_GET['tmp'] != '')
		{
			$_SESSION['total_mat_folder_path'] = $_GET['tmp'];
		}
		 
		// valorizzazione delle variabili con i parametri dal form
		if(isset($_POST['submit'])&&($_POST['submit']=="Save"))
		{
			// archiviazione delle informazioni già inserite nella variabili di sessione			
			if(isset($_POST['operational_from']))
			{
				$_SESSION['operational_from'] = addslashes(filter_var($_POST['operational_from'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['operational_to']))
			{
				$_SESSION['operational_to'] = addslashes(filter_var($_POST['operational_to'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['EDIOS_Series_id']))
			{
				$_SESSION['EDIOS_Series_id'] = addslashes(filter_var($_POST['EDIOS_Series_id'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['EDMO_code']))
			{
				$_SESSION['EDMO_code'] = addslashes(filter_var($_POST['EDMO_code'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['metadata_page']))
			{
				$_SESSION['metadata_page'] = addslashes(filter_var($_POST['metadata_page'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['title']))
			{
				$_SESSION['title'] = addslashes(filter_var($_POST['title'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['summary']))
			{
				$_SESSION['summary'] = addslashes(filter_var($_POST['summary'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['institution_name']))
			{
				$_SESSION['institution_name'] = addslashes(filter_var($_POST['institution_name'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['citation_statement']))
			{
				$_SESSION['citation_statement'] = addslashes(filter_var($_POST['citation_statement'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['license']))
			{
				$_SESSION['license'] = addslashes(filter_var($_POST['license'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['acknowledgment']))
			{
				$_SESSION['acknowledgment'] = addslashes(filter_var($_POST['acknowledgment'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['total_QC_velocity_threshold']))
			{
				$_SESSION['total_QC_velocity_threshold'] = addslashes(filter_var($_POST['total_QC_velocity_threshold'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['total_QC_GDOP_threshold']))
			{
				$_SESSION['total_QC_GDOP_threshold'] = addslashes(filter_var($_POST['total_QC_GDOP_threshold'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['total_QC_variance_threshold']))
			{
				$_SESSION['total_QC_variance_threshold'] = addslashes(filter_var($_POST['total_QC_variance_threshold'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['total_QC_temporal_derivative_threshold']))
			{
				$_SESSION['total_QC_temporal_derivative_threshold'] = addslashes(filter_var($_POST['total_QC_temporal_derivative_threshold'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['total_QC_data_density_threshold']))
			{
				$_SESSION['total_QC_data_density_threshold'] = addslashes(filter_var($_POST['total_QC_data_density_threshold'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['project']))
			{
				$_SESSION['project'] = addslashes(filter_var($_POST['project'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['institution_website']))
			{
				$_SESSION['institution_website'] = addslashes(filter_var($_POST['institution_website'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['contributor_name']))
			{
				$_SESSION['contributor_name'] = addslashes(filter_var($_POST['contributor_name'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['contributor_role']))
			{
				$_SESSION['contributor_role'] = addslashes(filter_var($_POST['contributor_role'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['contributor_email']))
			{
				$_SESSION['contributor_email'] = addslashes(filter_var($_POST['contributor_email'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['comment']))
			{
				$_SESSION['comment'] = addslashes(filter_var($_POST['comment'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['network_name']))
			{
				$_SESSION['network_name'] = addslashes(filter_var($_POST['network_name'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['area']))
			{
				$_SESSION['area'] = addslashes(filter_var($_POST['area'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['geospatial_lon_min']))
			{
				$_SESSION['geospatial_lon_min'] = addslashes(filter_var($_POST['geospatial_lon_min'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['geospatial_lon_max']))
			{
				$_SESSION['geospatial_lon_max'] = addslashes(filter_var($_POST['geospatial_lon_max'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['geospatial_lat_min']))
			{
				$_SESSION['geospatial_lat_min'] = addslashes(filter_var($_POST['geospatial_lat_min'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['geospatial_lat_max']))
			{
				$_SESSION['geospatial_lat_max'] = addslashes(filter_var($_POST['geospatial_lat_max'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['grid_resolution']))
			{
				$_SESSION['grid_resolution'] = addslashes(filter_var($_POST['grid_resolution'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['temporal_resolution']))
			{
				$_SESSION['temporal_resolution'] = addslashes(filter_var($_POST['temporal_resolution'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['region_bigram']))
			{
				$_SESSION['region_bigram'] = addslashes(filter_var($_POST['region_bigram'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['EU_HFR_processing_flag']))
			{
				$_SESSION['EU_HFR_processing_flag'] = addslashes(filter_var($_POST['EU_HFR_processing_flag'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['combination_search_radius']))
			{
				$_SESSION['combination_search_radius'] = addslashes(filter_var($_POST['combination_search_radius'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['total_input_folder_path']))
			{
				$_SESSION['total_input_folder_path'] = addslashes(filter_var($_POST['total_input_folder_path'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['total_HFRnetCDF_folder_path']))
			{
				$_SESSION['total_HFRnetCDF_folder_path'] = addslashes(filter_var($_POST['total_HFRnetCDF_folder_path'], FILTER_SANITIZE_STRING));
			}
			if(isset($_POST['total_mat_folder_path']))
			{
				$_SESSION['total_mat_folder_path'] = addslashes(filter_var($_POST['total_mat_folder_path'], FILTER_SANITIZE_STRING));
			}
			// String for recover session data
			$recoverSession = "&err=1&opf=" . $_SESSION['operational_from'] . "&opt=" . $_SESSION['operational_to'] . "&EDS=" . $_SESSION['EDIOS_Series_id']
												 . "&EDM=" . $_SESSION['EDMO_code'] . "&mtp=" . $_SESSION['metadata_page'] . "&tit=" . $_SESSION['title'] . "&sum=" . $_SESSION['summary']
												 . "&inn=" . $_SESSION['institution_name'] . "&cit=" . $_SESSION['citation_statement'] . "&lic=" . $_SESSION['license']
												 . "&ack=" . $_SESSION['acknowledgment'] . "&vel=" . $_SESSION['total_QC_velocity_threshold'] . "&gdp=" . $_SESSION['total_QC_GDOP_threshold']
												 . "&var=" . $_SESSION['total_QC_variance_threshold'] . "&tdr=" . $_SESSION['total_QC_temporal_derivative_threshold'] . "&ddn=" . $_SESSION['total_QC_data_density_threshold']
												 . "&prj=" . $_SESSION['project'] . "&inw=" . $_SESSION['institution_website'] . "&cnn=" . $_SESSION['contributor_name'] . "&cnr=" . $_SESSION['contributor_role']
												 . "&cne=" . $_SESSION['contributor_email'] . "&com=" . $_SESSION['comment'] . "&ntn=" . $_SESSION['network_name'] . "&are=" . $_SESSION['area']
												 . "&lnn=" . $_SESSION['geospatial_longitude_min'] . "&lnx=" . $_SESSION['geospatial_longitude_max'] . "&ltn=" . $_SESSION['geospatial_latitude_min']
											   . "&ltx=" . $_SESSION['geospatial_latitude_max'] . "&grs=" . $_SESSION['grid_resolution'] . "&trs=" . $_SESSION['temporal_resolution']
                         . "&rbg=" . $_SESSION['region_bigram'] . "&csr=" . $_SESSION['combination_search_radius'] . "&tip=" . $_SESSION['total_input_folder_path']
												 . "&thp=" . $_SESSION['total_HFRnetCDF_folder_path'] . "&tmp=" . $_SESSION['total_mat_folder_path'];

			// check esistenza e valorizzazione delle variabili con i parametri dal form
			if(isset($selected_network_id)){
				if(isset($_POST['EDIOS_Series_id']))
				{
					$EDIOS_Series_id = addslashes(filter_var($_POST['EDIOS_Series_id'], FILTER_SANITIZE_STRING));
			  	}
				if($EDIOS_Series_id!=''){
					if(isset($_POST['EDMO_code']))
				  	{
				    	$EDMO_code = addslashes(filter_var($_POST['EDMO_code'], FILTER_SANITIZE_STRING));
				  	}
					if($EDMO_code!=0){
					 	if(isset($_POST['metadata_page']))
						{
					    	$metadata_page = addslashes(filter_var($_POST['metadata_page'], FILTER_SANITIZE_STRING));
					    }
						if($metadata_page!=''){
							if(isset($_POST['title']))
							{
								$title = addslashes(filter_var($_POST['title'], FILTER_SANITIZE_STRING));
						  	}
							if($title!=''){
								if(isset($_POST['summary']))
								{
									$summary = addslashes(filter_var($_POST['summary'], FILTER_SANITIZE_STRING));
							  	}
								if($summary!=''){
									if(isset($_POST['institution_name']))
									{
										$institution_name = addslashes(filter_var($_POST['institution_name'], FILTER_SANITIZE_STRING));
								  	}
									if($institution_name!=''){
										if(isset($_POST['citation_statement']))
										{
											$citation_statement = addslashes(filter_var($_POST['citation_statement'], FILTER_SANITIZE_STRING));
									  	}
										if($citation_statement!=''){
											if(isset($_POST['license']))
											{
												$license = addslashes(filter_var($_POST['license'], FILTER_SANITIZE_STRING));
										  	}
											if($license!=''){
												if(isset($_POST['acknowledgment']))
												{
													$acknowledgment = addslashes(filter_var($_POST['acknowledgment'], FILTER_SANITIZE_STRING));
												}
												if($acknowledgment!=''){
													if(isset($_POST['total_QC_velocity_threshold']))
													{
														$total_QC_velocity_threshold = addslashes(filter_var($_POST['total_QC_velocity_threshold'], FILTER_SANITIZE_STRING));
													}
													if(isset($_POST['total_QC_GDOP_threshold']))
													{
														$total_QC_GDOP_threshold = addslashes(filter_var($_POST['total_QC_GDOP_threshold'], FILTER_SANITIZE_STRING));
													}
													if(isset($_POST['total_QC_variance_threshold']))
													{
												    	$total_QC_variance_threshold = addslashes(filter_var($_POST['total_QC_variance_threshold'], FILTER_SANITIZE_STRING));
												  	}
													if(isset($_POST['total_QC_temporal_derivative_threshold']))
													{
												    	$total_QC_temporal_derivative_threshold = addslashes(filter_var($_POST['total_QC_temporal_derivative_threshold'], FILTER_SANITIZE_STRING));
												  	}
													if(isset($_POST['total_QC_data_density_threshold']))
													{
												    	$total_QC_data_density_threshold = addslashes(filter_var($_POST['total_QC_data_density_threshold'], FILTER_SANITIZE_STRING));
												  	}
													if(isset($_POST['geospatial_lon_min']))
													{
												    	$geospatial_lon_min = addslashes(filter_var($_POST['geospatial_lon_min'], FILTER_SANITIZE_STRING));
												  	}
													if($geospatial_lon_min!=0){
														if(isset($_POST['geospatial_lon_max']))
														{
													    	$geospatial_lon_max = addslashes(filter_var($_POST['geospatial_lon_max'], FILTER_SANITIZE_STRING));
													  	}
														if($geospatial_lon_max!=0){
															if(isset($_POST['geospatial_lat_min']))
															{
														    	$geospatial_lat_min = addslashes(filter_var($_POST['geospatial_lat_min'], FILTER_SANITIZE_STRING));
														  	}
															if($geospatial_lat_min!=0){
																if(isset($_POST['geospatial_lat_max']))
																{
															    	$geospatial_lat_max = addslashes(filter_var($_POST['geospatial_lat_max'], FILTER_SANITIZE_STRING));
															  	}
																if($geospatial_lat_max!=0){
																	if(isset($_POST['total_input_folder_path']))
																	{
																    	$total_input_folder_path = addslashes(filter_var($_POST['total_input_folder_path'], FILTER_SANITIZE_STRING));
																  	}
																	if(isset($_POST['total_HFRnetCDF_folder_path']))
																	{
																    	$total_HFRnetCDF_folder_path = addslashes(filter_var($_POST['total_HFRnetCDF_folder_path'], FILTER_SANITIZE_STRING));
																  	}
																	if(isset($_POST['total_mat_folder_path']))
																	{
																		$total_mat_folder_path = addslashes(filter_var($_POST['total_mat_folder_path'], FILTER_SANITIZE_STRING));
																  	}
																  	if(isset($_POST['operational_from']))
																	{
																    	$operational_from = addslashes(filter_var($_POST['operational_from'], FILTER_SANITIZE_STRING));
																  	}
																	if(isset($_POST['operational_to']))
																	{
																    	$operational_to = addslashes(filter_var($_POST['operational_to'], FILTER_SANITIZE_STRING));
																  	}
																	if(isset($_POST['project']))
																	{
																		$project = addslashes(filter_var($_POST['project'], FILTER_SANITIZE_STRING));
																  	}
																	if(isset($_POST['institution_website']))
																	{
																    	$institution_website = addslashes(filter_var($_POST['institution_website'], FILTER_SANITIZE_STRING));
																  	}
																	if($institution_website!=''){
																		if(isset($_POST['contributor_name']))
																		{	
																		$contributor_name = addslashes(filter_var($_POST['contributor_name'], FILTER_SANITIZE_STRING));
																		}
																		if($contributor_name!=''){
																			if(isset($_POST['contributor_role']))
																			{
																			$contributor_role = addslashes(filter_var($_POST['contributor_role'], FILTER_SANITIZE_STRING));
																			}
																			if($contributor_role!=''){
																				if(isset($_POST['contributor_email']))
																				{
																				$contributor_email = addslashes(filter_var($_POST['contributor_email'], FILTER_SANITIZE_STRING));
																				}
																				if($contributor_email!=''){				
																					if(isset($_POST['comment']))
																					{
																				    	$comment = addslashes(filter_var($_POST['comment'], FILTER_SANITIZE_STRING));
																				  	}
																					if(isset($_POST['network_name']))
																					{
																				    	$network_name = addslashes(filter_var($_POST['network_name'], FILTER_SANITIZE_STRING));
																				  	}
																					if(isset($_POST['area']))
																					{
																					    $area = addslashes(filter_var($_POST['area'], FILTER_SANITIZE_STRING));
																				  	}
																					if(isset($_POST['grid_resolution']))
																					{
																				    	$grid_resolution = addslashes(filter_var($_POST['grid_resolution'], FILTER_SANITIZE_STRING));
																				  	}
																					if($grid_resolution!=0){
																						if(isset($_POST['temporal_resolution']))
																						{
																							$temporal_resolution = addslashes(filter_var($_POST['temporal_resolution'], FILTER_SANITIZE_STRING));
																				  		}
																						if($temporal_resolution!=0){
																							if(isset($_POST['region_bigram']))
																							{
																						    	$region_bigram = addslashes(filter_var($_POST['region_bigram'], FILTER_SANITIZE_STRING));
																						  	}
																							if(isset($_POST['combination_search_radius']))
																							{
																						    	$combination_search_radius = addslashes(filter_var($_POST['combination_search_radius'], FILTER_SANITIZE_STRING));
																						  	}
																							if($combination_search_radius==0){
																								$combination_search_radius = 2*$grid_resolution;
																							}
																							// check if the selected id is already present in the DB
																						    if(mysql_num_rows($result_selected_network) > 0){
																						   		// chiamata alla funzione per l'aggiornamento dei dati
																							    $sql_update = "UPDATE network_tb SET operational_from=\"" . $operational_from . "\", operational_to=\"" . $operational_to . "\", EDIOS_Series_id=\"";
																								$sql_update.= $EDIOS_Series_id . "\", EDMO_code=\"" . $EDMO_code . "\", metadata_page=\"" . $metadata_page;
																								$sql_update.= "\", title=\"" . $title . "\", summary=\"" . $summary . "\", institution_name=\"". $institution_name . "\", citation_statement=\"" . $citation_statement;
																								$sql_update.= "\", license=\"" . $license . "\", acknowledgment=\"" . $acknowledgment . "\", total_QC_velocity_threshold=\"" . $total_QC_velocity_threshold;
																								$sql_update.= "\", total_QC_GDOP_threshold=\"" . $total_QC_GDOP_threshold . "\", total_QC_variance_threshold=\"" . $total_QC_variance_threshold;
																								$sql_update.= "\", total_QC_temporal_derivative_threshold=\"" . $total_QC_temporal_derivative_threshold . "\", total_QC_data_density_threshold=\"" . $total_QC_data_density_threshold;
																								$sql_update.= "\", project=\"" . $project . "\", institution_website=\"" . $institution_website . "\", comment=\"" . $comment . "\", network_name=\"" . $network_name;
																								$sql_update.= "\", contributor_name=\"" . $contributor_name . "\", contributor_role=\"" . $contributor_role . "\", contributor_email=\"". $contributor_email;
																								$sql_update.= "\", area=\"" . $area . "\", geospatial_lon_min=\"" . $geospatial_lon_min . "\", geospatial_lon_max=\"". $geospatial_lon_max;
																								$sql_update.= "\", geospatial_lat_min=\"" . $geospatial_lat_min . "\", geospatial_lat_max=\"" . $geospatial_lat_max . "\", grid_resolution=\"" . $grid_resolution;
																								$sql_update.= "\", temporal_resolution=\"" . $temporal_resolution . "\", region_bigram=\"" . $region_bigram . "\", combination_search_radius=\"" . $combination_search_radius;
																								if($EU_HFR_processing_flag == 0){
																									$sql_update.= "\", total_input_folder_path=\"" . $total_input_folder_path . "\", total_HFRnetCDF_folder_path=\"" . $total_HFRnetCDF_folder_path . "\", total_mat_folder_path=\"" . $total_mat_folder_path;
																								}		
																								$sql_update.= "\" WHERE network_id=\"" . $selected_network_id . "\"";
																								$update_query = mysql_query($sql_update, $conn) or die(mysql_error());
																											
																								// set void dates to NULL value
																								if(($operational_from == '0000-00-00') || ($operational_from == '')){
																									$sql_null_update = "UPDATE network_tb SET operational_from=NULL WHERE network_id=\"" . $selected_network_id . "\"";
																									$null_update_query = mysql_query($sql_null_update, $conn) or die(mysql_error());
																								}
																								if(($operational_to == '0000-00-00') || ($operational_to == '')){
																									$sql_null_update = "UPDATE network_tb SET operational_to=NULL WHERE network_id=\"" . $selected_network_id . "\"";
																									$null_update_query = mysql_query($sql_null_update, $conn) or die(mysql_error());
																								}					
																							
																								$mess = "The network information have been updated successfully.";
																								header("Location: network_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $selected_network_id . "&login_message=" . $mess);
																							}
																						}
																						else{
																							$mess =  "No temporal resolution has been inserted. Please insert one.";
																							header("Location: network_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $selected_network_id . "&login_message=" . $mess . $recoverSession);
																						}
																					}
																					else{
																						$mess =  "No grid resolution has been inserted. Please insert one.";
																						header("Location: network_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $selected_network_id . "&login_message=" . $mess . $recoverSession);
																					}
																				}
																				else{
																					$mess =  "No contributor email has been inserted. Please insert one.";
																					header("Location: network_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $selected_network_id . "&login_message=" . $mess . $recoverSession);
																				}
																			}
																			else{
																				$mess =  "No contributor role has been inserted. Please insert one.";
																				header("Location: network_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $selected_network_id . "&login_message=" . $mess . $recoverSession);
																			}
																		}
																		else{
																			$mess =  "No contributor name has been inserted. Please insert one.";
																			header("Location: network_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $selected_network_id . "&login_message=" . $mess . $recoverSession);
																		}
																	}
																	else{
																		$mess =  "No institution website has been inserted. Please insert one.";
																		header("Location: network_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $selected_network_id . "&login_message=" . $mess . $recoverSession);
																	}
																}
																else{
																	$mess =  "No geospatial latitude maximum has been inserted. Please insert one.";
																	header("Location: network_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $selected_network_id . "&login_message=" . $mess . $recoverSession);
																}
															}
															else{
																$mess =  "No geospatial latitude minimum has been inserted. Please insert one.";
																header("Location: network_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $selected_network_id . "&login_message=" . $mess . $recoverSession);
															}
														}
														else{
															$mess =  "No geospatial longitude maximum has been inserted. Please insert one.";
															header("Location: network_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $selected_network_id . "&login_message=" . $mess . $recoverSession);
														}
													}
													else{
														$mess =  "No geospatial longitude minimum has been inserted. Please insert one.";
														header("Location: network_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $selected_network_id . "&login_message=" . $mess . $recoverSession);
													}
												}
												else{
													$mess =  "No acknowledgment has been inserted. Please insert one.";
													header("Location: network_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $selected_network_id . "&login_message=" . $mess . $recoverSession);
												}
											}
											else{
												$mess =  "No license has been inserted. Please insert one.";
												header("Location: network_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $selected_network_id . "&login_message=" . $mess . $recoverSession);
											}
										}
										else{
											$mess =  "No citation statement has been inserted. Please insert one.";
											header("Location: network_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $selected_network_id . "&login_message=" . $mess . $recoverSession);
										}
									}
									else{
										$mess =  "No institution name has been inserted. Please insert one.";
										header("Location: network_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $selected_network_id . "&login_message=" . $mess . $recoverSession);
									}
								}
								else{
									$mess =  "No summary has been inserted. Please insert one.";
									header("Location: network_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $selected_network_id . "&login_message=" . $mess . $recoverSession);
								}
							}
							else{
								$mess =  "No title has been inserted. Please insert one.";
								header("Location: network_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $selected_network_id . "&login_message=" . $mess . $recoverSession);
							}
						}
						else{
							$mess =  "No metadata page has been inserted. Please insert one.";
							header("Location: network_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $selected_network_id . "&login_message=" . $mess . $recoverSession);
						}
					}
					else{
						$mess =  "No EDMO code has been inserted. Please insert one.";
						header("Location: network_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $selected_network_id . "&login_message=" . $mess . $recoverSession);
					}
				}
				else{
					$mess =  "No EDIOS Serie ID has been inserted. Please insert one.";
					header("Location: network_WF.php?usr=" . $_GET["usr"] . "&ntw=" . $selected_network_id . "&login_message=" . $mess . $recoverSession);
				}
			}
			else{
				$mess = "No network has been selected. Please select one.";
				header("Location: network_WF.php?usr=" . $_GET["usr"] . "&login_message=" . $mess . $recoverSession);
			}
		}
		else
		{
			// menu a tendina
			?>
			<b>Select the network:</b>
			<form action="<?php echo $_SERVER['PHP_SELF'] . "?usr=" . $_GET["usr"]; ?>" method="post">
			<select name="selected_network">
				<?php
				for($i = 0; $i < $num_networks; $i++){
					$network=$network_array[$i];
					if($network != ''){
						echo("<option value=\"" . $network . "\">" . $network . "</option>");
					}
				}
				echo("<option value=\"Add new network\">Add new network</option>");
				if(isset($selected_network_id)){
					echo("<option selected=\"selected\" value=\"" . $selected_network_id . "\">" . $selected_network_id . "</option>");
				}
				else{
					echo("<option selected=\"selected\"> Select the network </option>");
				}
				?>
			</select>
			<input name="submit" type="submit" value="Select">
			</form>
			
			<?	
			// form per l'inserimento
			echo("<br><br><b>Please insert information about the " . $selected_network_id . " network (* = mandatory fields)</b>");
			
			// recupero eventuali informazioni inserite in precedenza
			if($_GET['err'] == 1)
			{
				$previous = $_SESSION;
			}
			else
			{
				$previous = $selected_network;
			}
			$previous_operational_from = $previous["operational_from"];
			$previous_operational_to = $previous["operational_to"];
			$previous_EDIOS_Series_id = $previous["EDIOS_Series_id"];
			$previous_EDMO_code = $previous["EDMO_code"];
			$previous_metadata_page = $previous["metadata_page"];
			$previous_title = $previous["title"];
			$previous_summary = $previous["summary"];
			$previous_institution_name = $previous["institution_name"];
			$previous_citation_statement = $previous["citation_statement"];
			$previous_license = $previous["license"];
			$previous_acknowledgment = $previous["acknowledgment"];
			$previous_total_QC_velocity_threshold = $previous["total_QC_velocity_threshold"];
			$previous_total_QC_GDOP_threshold = $previous["total_QC_GDOP_threshold"];
			$previous_total_QC_variance_threshold = $previous["total_QC_variance_threshold"];
			$previous_total_QC_temporal_derivative_threshold = $previous["total_QC_temporal_derivative_threshold"];
			$previous_total_QC_data_density_threshold = $previous["total_QC_data_density_threshold"];
			$previous_project = $previous["project"];
			$previous_institution_website = $previous["institution_website"];
			$previous_contributor_name = $previous["contributor_name"];
			$previous_contributor_role = $previous["contributor_role"];
			$previous_contributor_email = $previous["contributor_email"];
			$previous_comment = $previous["comment"];
			$previous_network_name = $previous["network_name"];
			$previous_area = $previous["area"];
			$previous_geospatial_lon_min = $previous["geospatial_lon_min"];
			$previous_geospatial_lon_max = $previous["geospatial_lon_max"];
			$previous_geospatial_lat_min = $previous["geospatial_lat_min"];
			$previous_geospatial_lat_max = $previous["geospatial_lat_max"];
			$previous_grid_resolution = $previous["grid_resolution"];
			$previous_temporal_resolution = $previous["temporal_resolution"];
			$previous_region_bigram = $previous["region_bigram"];
			$previous_EU_HFR_processing_flag = $previous["EU_HFR_processing_flag"];
			$previous_combination_search_radius = $previous["combination_search_radius"];
			$previous_total_input_folder_path = $previous["total_input_folder_path"];
			$previous_total_HFRnetCDF_folder_path = $previous["total_HFRnetCDF_folder_path"];
			$previous_total_mat_folder_path = $previous["total_mat_folder_path"];
						
			?>
			<form action="<?php echo $_SERVER['PHP_SELF'] . "?usr=" . $_GET["usr"] . "&ntw=" . $selected_network_id; ?>" method="post">
			<br>Operational from (YYYY-MM-DD):<br>			
			<input name="operational_from" type="text" size="80" value="<?php echo($previous_operational_from);?>"><br />
			<br>Operational to (YYYY-MM-DD):<br>			
			<input name="operational_to" type="text" size="80" value="<?php echo($previous_operational_to);?>"><br />	
			<br><b>EDIOS Series ID*</b>:<br>			
			<input name="EDIOS_Series_id" type="text" size="80" value="<?php echo($previous_EDIOS_Series_id);?>"><br />	
			<br><b>EDMO code of the institution responsible for the HFR network management and total files generation*</b>:<br>
			(In case of shared coordination, insert here only the reference of the institution responsible for total data production)<br>
			<input name="EDMO_code" type="number" size="80" value="<?php echo($previous_EDMO_code);?>"><br />
			<br><b>Institution responsible for the HFR network management and total files generation*</b>:<br>
			(In case of shared coordination, insert here only the reference of the institution responsible for total data production)<br>			
			<input name="institution_name" type="text" size="80" value="<?php echo($previous_institution_name);?>"><br />	
			<br><b>Website of the institution responsible for the HFR network management and total files generation*</b>:<br>
			(In case of shared coordination, insert here only the reference of the institution responsible for total data production)<br>			
			<input name="institution_website" type="text" size="80" value="<?php echo($previous_institution_website);?>"><br />
			<br><b>Contributor names*</b>:<br>
			(Insert a semi-colon separated list of names)<br>
			<input name="contributor_name" type="text" size="80" value="<?php echo($previous_contributor_name);?>"><br />	
			<br><b>Contributor roles*</b>:<br>
			(Insert a semi-colon separated list of roles)<br>
			<input name="contributor_role" type="text" size="80" value="<?php echo($previous_contributor_role);?>"><br />
			<br><b>Contributor emails*</b>:<br>
			(Insert a semi-colon separated list of email addresses)<br>
			<input name="contributor_email" type="text" size="80" value="<?php echo($previous_contributor_email);?>"><br />
			<br><b>Metadata page*</b>:<br>
			<input name="metadata_page" type="text" size="80" value="<?php echo($previous_metadata_page);?>"><br />	
			<br><b>Title*</b>:<br>			
			<input name="title" type="text" size="80" value="<?php echo($previous_title);?>"><br />						
			<br><b>Summary*</b>:<br>			
			<textarea name="summary" cols="80" rows="5"><?php echo($previous_summary);?></textarea><br />
			<br><b>Citation statement*</b>:<br>			
			<textarea name="citation_statement" cols="80" rows="5"><?php echo($previous_citation_statement);?></textarea><br />		
			<br><b>License*</b>:<br>			
			<textarea name="license" cols="80" rows="5"><?php echo($previous_license);?></textarea><br />
			<br><b>Acknowledgment*</b>:<br>			
			<textarea name="acknowledgment" cols="80" rows="5"><?php echo($previous_acknowledgment);?></textarea><br />
			<br>Total QC velocity threshold (m/s):<br>
			<input name="total_QC_velocity_threshold" type="number" size="80" step="0.01" min="0" max="100" value="<?php echo($previous_total_QC_velocity_threshold);?>"><br />	
			<br>Total QC GDOP threshold:<br>
			<input name="total_QC_GDOP_threshold" type="number" size="80" step="0.01" min="0" max="100" value="<?php echo($previous_total_QC_GDOP_threshold);?>"><br />	
			<br>Total QC variance threshold (m/s):<br>
			<input name="total_QC_variance_threshold" type="number" size="80" step="0.01" min="0" max="100" value="<?php echo($previous_total_QC_variance_threshold);?>"><br />	
			<br>Total QC temporal derivative threshold (m/s):<br>
			<input name="total_QC_temporal_derivative_threshold" type="number" size="80" step="0.01" min="0" max="100" value="<?php echo($previous_total_QC_temporal_derivative_threshold);?>"><br />	
			<br>Total QC data density threshold:<br>
			<input name="total_QC_data_density_threshold" type="number" size="80" value="<?php echo($previous_total_QC_data_density_threshold);?>"><br />	
			<br>Projects:<br>			
			<input name="project" type="text" size="80" value="<?php echo($previous_project);?>"><br />
			<br>Comment:<br>			
			<textarea name="comment" cols="80" rows="5"><?php echo($previous_comment);?></textarea><br />
			<br>HFR network name:<br>			
			<input name="network_name" type="text" size="80" value="<?php echo($previous_network_name);?>"><br />
			<br>Area:<br>			
			<input name="area" type="text" size="80" value="<?php echo($previous_area);?>"><br />
			<br><b>Geospatial longitude minimum (decimal degrees)*</b>:<br>
			<input name="geospatial_lon_min" type="number" size="80" step="0.00001" min="-180" max="180" value="<?php echo($previous_geospatial_lon_min);?>"><br />				
			<br><b>Geospatial longitude maximum (decimal degrees)*</b>:<br>
			<input name="geospatial_lon_max" type="number" size="80" step="0.00001" min="-180" max="180" value="<?php echo($previous_geospatial_lon_max);?>"><br />			
			<br><b>Geospatial latitude minimum (decimal degrees)*</b>:<br>
			<input name="geospatial_lat_min" type="number" size="80" step="0.00001" min="-90" max="90" value="<?php echo($previous_geospatial_lat_min);?>"><br />
			<br><b>Geospatial latitude maximum (decimal degrees)*</b>:<br>
			<input name="geospatial_lat_max" type="number" size="80" step="0.00001" min="-90" max="90" value="<?php echo($previous_geospatial_lat_max);?>"><br />
			<br><b>Grid resolution (km)*</b>:<br>
			<input name="grid_resolution" type="number" size="80" step="0.01" min="0" max="100" value="<?php echo($previous_grid_resolution);?>"><br />
			<br><b>Temporal resolution (decimal minutes, e.g. 37'30"= 37.5 minutes)*</b>:<br>
			<input name="temporal_resolution" type="number" size="80" step="0.01" min="0" max="100" value="<?php echo($previous_temporal_resolution);?>"><br />
			<br>Region bigram:<br>			
			<input name="region_bigram" type="text" size="80" value="<?php echo($previous_region_bigram);?>"><br />
			<br>Combination search radius (km):<br>
			<input name="combination_search_radius" type="number" size="80" step="0.01" min="0" max="100" value="<?php echo($previous_combination_search_radius);?>"><br />			
			<?php
			if($previous_EU_HFR_processing_flag == 0){
				echo("<br>Total files input folder path:<br>");			
				echo("<input name=\"total_input_folder_path\" type=\"text\" size=\"80\" value=\"" . $previous_total_input_folder_path . "\"><br />");			
				echo("<br>Total netCDF output files folder path:<br>");		
				echo("<input name=\"total_HFRnetCDF_folder_path\" type=\"text\" size=\"80\" value=\"" . $previous_total_HFRnetCDF_folder_path . "\"><br />");			
				echo("<br>Total mat output files folder path:<br>");		
				echo("<input name=\"total_mat_folder_path\" type=\"text\" size=\"80\" value=\"" . $previous_total_mat_folder_path . "\"><br />");
				// echo("<input name=\"total_mat_folder_path\" type=\"file\" onchange=\"getfolder(event)\" webkitdirectory mozdirectory msdirectory odirectory directory><br />");
			}
			?>	
			<input name="submit" type="submit" value="Save">
			</form>
			<?
		}
		?>
	<!-- end #network_form --></div>    
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
