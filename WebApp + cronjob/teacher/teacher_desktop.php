<?php
ini_set('session.gc_maxlifetime', 7200);
ini_set('session.cookie_lifetime', 7200);
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $username = $_SESSION['username'];
	$teacher_id=$_SESSION['teacher_id'];
} else {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Routes</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add custom CSS for the menu -->
    <link rel="stylesheet" type="text/css" href="../styles/teacher_desktop.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
	<link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet">
	<link href="../styles/select2/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
    <!-- Navbar with advanced menu -->
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #333;">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="teacher_desktop.php"><i class="fas fa-road"></i> Trips</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="student_management.php"><i class="fas fa-users"></i> Student Management</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
				<li class="nav-item delimiter"></li>
				<li class="nav-item">
					<span class="nav-link no-hover">
						<i class="fas fa-user"></i> <?php echo $username; ?>
					</span>
				</li>
            </ul>
        </div>
    </nav>

    <!-- Main content -->
    <div class="content" id="content">
		<h1>Trips</h1>
		 <div class="form-group" id="adjustHeight">
			<div class="form-group row">
				 <div class="col-md-6">
				 	<label for="student-select">Students:</label>
					<select class="js-example-basic-single"id="student-select" name="students" style="width:300px;">
					</select>
				</div>
				<div class="col-md-6" id="routes-select-container" style="display: none;">
					<label for="routes-select">Routes:</label>
					<select class="js-example-basic-single" id="routes-select" name="routes" style="width: 300px;"></select>
				</div>
			</div>
			<div class="row" id="sum-div-show-h1" style="margin-top:50px;display:none;">
			<h1>Summary</h1>
			</div>
			  <div class="row" id="sum-div-show" style="display:none;">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total time driven</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totaltime"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1"style="color:#007bff!important">Total distance driven</div>
                                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800" id="totaldistance"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1"style="color:#007bff!important">Total distance driven on highway</div>
                                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800" id="totaldistancehighway"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
              </div>
			  <div class="row" id="unique-h1" style="margin-top:50px;display:none;">
				<h1>Selected Trip Details</h1>
			  </div>
			  <div class="row" id="uniqe-trips" style="display:none;">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Duration of the trip</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="onetime"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1"style="color:#007bff!important">Distance of the trip</div>
                                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800" id="onedistance"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1"style="color:#007bff!important">Max speed of the trip</div>
                                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800" id="onemaxspeed"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1"style="color:#007bff!important">Average speed of the trip</div>
                                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800" id="oneaveragespeed"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1"style="color:#007bff!important">Distance driven on highway</div>
                                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800" id="onehighway"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>  
              </div>
		</div>
		<div style="display:none; position:relative; height:630px;" id='mapContainer'>
			<div id="map"></div>
		<div>
    </div>

    <!-- Add Bootstrap and Font Awesome icons -->
    <!-- Add Bootstrap and jQuery scripts at the end of the file -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script src="../styles/select2/dist/js/select2.min.js"></script>
	<script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
	<script>
	$(document).ready(function() {
		
		function get_students(){
			$('#student-select').select2();
			
			$.ajax({
				url: 'get_routes_students.php',
				type: 'POST',
				data: { teacher_id: <?php echo $teacher_id; ?> },
				dataType: 'json',
				success: function(data) {
					var select2Options = [];
					 select2Options.push({
						 id: '',
						 text: 'Choose one',
						 disabled: true,
						 selected: true
					});

					data.forEach(function(student) {
						var optionText = student.first_name + ' ' + student.last_name + ' (' + student.user_id + ')';
						select2Options.push({
							id: student.user_id,
							text: optionText
						});
					});
					$('#student-select').select2({
						data: select2Options
					});
				},
				error: function(xhr, status, error) {
					console.error('Error fetching students:', error);
				}
			});
		}
		get_students();
		function getTotaltime(selectedUserId){	
			$.ajax({
				url: 'get_total_time.php',
				type: 'POST',
				data: { user_id:selectedUserId },
				dataType: 'json',
				success: function(data){
					if (data.length === 0) {
						$('#sum-div-show-h1').hide();
						$('#sum-div-show').hide();
						$('#totaltime').text="";
					}else{
						$('#sum-div-show-h1').show();
						$('#sum-div-show').show();
						$('#totaltime').text(data[0].total_duration_minutes + ' minutes');
						$('#unique-h1').hide();
						$('#uniqe-trips').hide();
						$('#onetime').text="";
						$('#onemaxspeed').text="";
						$('#oneaveragespeed').text="";
					}
				},
				error: function(xhr, status, error) {
					console.error('Error fetching time:', error);
				}
			});
		}
		function getTotalDistance(selectedUserId){	
			$.ajax({
				url: 'get_total_distance.php',
				type: 'POST',
				data: { user_id:selectedUserId },
				dataType: 'json',
				success: function(data){
					if (data.length === 0) {
						$('#totaldistance').text="";
					}else{
						$('#totaldistance').text(Number(data[0].total_distance).toFixed(3) + ' km');
					}
				},
				error: function(xhr, status, error) {
					console.error('Error fetching time:', error);
				}
			});
		}
		function getTotalDistanceHighway(selectedUserId){	
			$.ajax({
				url: 'get_total_distance_highway.php',
				type: 'POST',
				data: { user_id:selectedUserId },
				dataType: 'json',
				success: function(data){
					if (data.length === 0) {
						$('#totaldistancehighway').text="";
					}else{
						$('#totaldistancehighway').text(Number(data[0].total_distance_highway).toFixed(3) + ' km');
					}
				},
				error: function(xhr, status, error) {
					console.error('Error fetching time:', error);
				}
			});
		}
		function getOneTripDetails(selectedTrip){	
			$.ajax({
				url: 'get_one_trip_details.php',
				type: 'POST',
				data: { user_id:selectedTrip.split("_")[1], trip_num:selectedTrip.split("_")[0] },
				dataType: 'json',
				success: function(data){
						$('#unique-h1').show();
						$('#uniqe-trips').show();
						$('#onetime').text(data[0].trip_duration_minutes + ' minutes');
						$('#onemaxspeed').text(data[0].max_speed + ' km/h');
						$('#oneaveragespeed').text(data[0].avg_speed + ' km/h');
				},
				error: function(xhr, status, error) {
					console.error('Error fetching time:', error);
				}
			});
		}
		function getOneTripDistance(selectedTrip){	
			$.ajax({
				url: 'get_one_trip_distance.php',
				type: 'POST',
				data: { user_id:selectedTrip.split("_")[1], trip_num:selectedTrip.split("_")[0] },
				dataType: 'json',
				success: function(data){
						$('#onedistance').text(Number(data[0].distance).toFixed(3) + ' km');
				},
				error: function(xhr, status, error) {
					console.error('Error fetching time:', error);
				}
			});
		}
		function getOneTripDistanceHighway(selectedTrip){	
			$.ajax({
				url: 'get_one_trip_distance_highway.php',
				type: 'POST',
				data: { user_id:selectedTrip.split("_")[1], trip_num:selectedTrip.split("_")[0] },
				dataType: 'json',
				success: function(data){
						$('#onehighway').text(Number(data[0].highway_distance).toFixed(3) + ' km');
				},
				error: function(xhr, status, error) {
					console.error('Error fetching time:', error);
				}
			});
		}
		function updateRoutes(selectedUserId) {
			$.ajax({
				url: 'get_routes.php',
				type: 'POST',
				data: { user_id: selectedUserId },
				dataType: 'json',
				success: function(data) {
					var routeOptions = [];

					if (data.length === 0) {
						$('#unique-h1').hide();
						$('#uniqe-trips').hide();
						$('#onetime').text="";
						$('#onemaxspeed').text="";
						$('#oneaveragespeed').text="";;
						routeOptions.push({
							id: '',
							text: 'No trip yet'
						});
					} else {
						routeOptions.push({
							id: '',
							text: 'Trips of ' + data[0].user_id,
							disabled: true,
							selected: true
						});

						data.forEach(function(route) {
							var formattedDate = new Date(route.first_date).toLocaleDateString('hu-HU');
							routeOptions.push({
								id: route.trip_num+'_'+route.user_id,
								text: 'Trip ' + route.trip_num + ' (' + formattedDate + ')'
							});
						});
					}
					$('#routes-select').empty().select2({
						data: routeOptions
					});
					$('#routes-select-container').show();
				},
				error: function(xhr, status, error) {
					console.error('Error fetching routes:', error);
				}
			});
		}
		function drawMap(js_routes){
			var max_distance=0, maxi,maxj;
			for(var i=0;i<js_routes.length-1;i++){
				for(var j=i+1;j<js_routes.length;j++){
					let d_lat=js_routes[j]["latitude"]-js_routes[i]["latitude"];
					let d_long=js_routes[j]["longitude"]-js_routes[i]["longitude"];
					let distance=Math.sqrt(d_lat*d_lat+d_long*d_long);
					if(distance>max_distance){
						max_distance=distance;
						maxi=i;
						maxj=j;
					}
				}
			}
			var avg_lat=(parseFloat(js_routes[maxj]["latitude"])+parseFloat(js_routes[maxi]["latitude"]))/2;
			var avg_long=(parseFloat(js_routes[maxj]["longitude"])+parseFloat(js_routes[maxi]["longitude"]))/2;
			const points = [];
			for(var i=0;i<js_routes.length;i++){
				points.push({ coordinates: [js_routes[i]["longitude"], js_routes[i]["latitude"]], title: "Time: "+js_routes[i]["time"]+"<br>Speed: "+js_routes[i]["speed"]+"km/h<br>Engine RPM: "+js_routes[i]["engine_rpm"]+"RPM<br>Engine Load: "+js_routes[i]["engine_load"]+"%<br>Altitude: "+js_routes[i]["altitude"] });
			}

			mapboxgl.accessToken = 'pk.eyJ1IjoidGFuY3NpMTk5OCIsImEiOiJjbGxiM2EwMDIwM3lsM2Vyc2w3M2F5Nms2In0.qKSSE0Pv60ONHaeda8Yhtw';
			const map = new mapboxgl.Map({
				container: 'map',
				style: 'mapbox://styles/mapbox/streets-v12',
				center: [avg_long, avg_lat],
				zoom: 13
			});
			map.on('load', () => {
				const lineCoordinates = js_routes.map(point => [point.longitude, point.latitude]);
				map.addSource('route', {
					'type': 'geojson',
					'data': {
						'type': 'Feature',
						'properties': {},
						'geometry': {
							'type': 'LineString',
							'coordinates': lineCoordinates
						}
					}
				});
				map.addLayer({
					id: 'my-line',
					type: 'line',
					source: 'route',
					paint: {
					  'line-color': 'black',
					  'line-width': 3,
					  'line-dasharray': [2, 1],
					},
				});
				var markers=[];
				points.forEach(point => {
					const marker = new mapboxgl.Marker()
						.setLngLat(point.coordinates)
						.setPopup(new mapboxgl.Popup().setHTML(`<h3>${point.title}</h3>`))
						.addTo(map);
					markers.push(marker);
				});
				 if (markers.length > 0) {
					const bounds = new mapboxgl.LngLatBounds();
					markers.forEach(marker => {
						bounds.extend(marker.getLngLat());
					});
					map.fitBounds(bounds, { padding: 50 });
				}
			});			
		}
		
		
		function get_trip(selectedTrip){	
			$.ajax({
				url: 'get_trip.php',
				type: 'POST',
				data: { user_id:selectedTrip.split("_")[1], trip_num:selectedTrip.split("_")[0] },
				dataType: 'json',
				success: function(data){
					$('#mapContainer').show();
					drawMap(data);
				},
				error: function(xhr, status, error) {
					console.error('Error fetching students:', error);
				}
			});
		}

		$('#routes-select').on('change', function() {
			var selectedTrip =$(this).val();
			getOneTripDetails(selectedTrip);
			getOneTripDistance(selectedTrip);
			getOneTripDistanceHighway(selectedTrip);			
			get_trip(selectedTrip);
		});

		$('#student-select').on('select2:select', function (e) {
			var selectedUserId = e.params.data.id;
			$('#mapContainer').hide()
			updateRoutes(selectedUserId);
			getTotaltime(selectedUserId);
			getTotalDistance(selectedUserId);
			getTotalDistanceHighway(selectedUserId);
		});
		
});
	</script>
</body>
</html>
