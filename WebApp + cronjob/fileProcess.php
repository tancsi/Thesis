<?php
$api_key = 'AIzaSyAzyD0yMm2SlBVDEJDJvjJKJpXHQxHRoRk';
function calculateDistance($api_key, $waypoints) {
    $waypoints_str = implode('|', array_map(function($point) {
        return $point[0] . ',' . $point[1];
    }, $waypoints));

    $url = "https://maps.googleapis.com/maps/api/directions/json?origin={$waypoints[0][0]},{$waypoints[0][1]}&destination={$waypoints[count($waypoints) - 1][0]},{$waypoints[count($waypoints) - 1][1]}&waypoints=optimize:true|{$waypoints_str}&key={$api_key}";

    $response = file_get_contents($url);
    $data = json_decode($response, true);

    if ($data["status"] == "OK") {
        $totalDistance = 0;
        foreach ($data["routes"] as $route) {
            foreach ($route["legs"] as $leg) {
                $totalDistance += $leg["distance"]["value"];
            }
        }
        $totalDistanceKm = $totalDistance / 1000; 
        return $totalDistanceKm;
    } else {
        echo "Error: " . $data["status"];
        return null;
    }
}
$noFile=glob("upload/*.csv");
if(count($noFile)>0)
{
	foreach (glob("upload/*.csv") as $filename) {
	$row = 1;
		if (($handle = fopen($filename, "r")) !== FALSE) {
			$cord=array();
			include "kapcsolat.php";
			$insertStudentId="";
			$trip_num=0;
			while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
				$num = count($data);
				if($row>2)
				{
					$cord[] = [$data[1], $data[2]];
					$seconds = $data[0] / 1000;
					$insertDate= date("Y/m/d H:i:s", $seconds);
					$insertStudentId= explode("_",$data[4])[0];
					$engine_load= floatval(str_replace(',', '.', str_replace('%', '', $data[10])));
					$rpm=intval(str_replace('RPM', '', $data[12]));
					$speed= intval(str_replace('km/h', '', $data[21]));
					$trip_num=$data[5];
					$query= "Insert into `obd_data` Values('0','$insertStudentId','$data[6]','$insertDate','$data[1]','$data[2]','$data[3]','$engine_load','$rpm','$speed','$trip_num')";
					$t=$db->query($query)or die($db->error);
				} 
				$row++;
				
			}
			fclose($handle);
			$arrayCount=ceil(sizeof($cord)/24);
			$totalDistance=0;
			$totalMotorwayDistance=0;
			for($i=0;$i<$arrayCount;$i++)
			{
				$startIndex=$i*24;
				$tmparray=array_slice($cord,$startIndex,25);
				$tmpdistance=calculateDistance($api_key, $tmparray);
				if(isMotorway($tmparray))
				{
					$totalMotorwayDistance+=$tmpdistance;
				}
				$totalDistance+=$tmpdistance;
			}
			$query= "Insert into `trip_summary` Values('$insertStudentId','$trip_num','$totalDistance','$totalMotorwayDistance')";
			$t=$db->query($query)or die($db->error);
		}
		rename($filename,'already_processed/'.str_replace("upload/", "",$filename));		
	}
}else{
	echo "No file to process!";
}

function isMotorway($coordinates){
	$motorwaycounter=0;
	foreach ($coordinates as $coordinate) {
		$latitude = $coordinate[0];
		$longitude = $coordinate[1];

		$url = "https://overpass-api.de/api/interpreter?data=[out:json];way(around:10,$latitude,$longitude)[highway];out;";

		$response = file_get_contents($url);
		$data = json_decode($response, true);

		if (!empty($data['elements'])) {
			$roadType = $data['elements'][0]['tags']['highway'];
			if($roadType=='motorway')
			{
				$motorwaycounter+=1;
			}	
		}
	}
	if($motorwaycounter>=12)
	{
		return true;
	}else{
		return false;
	}
}
?>
