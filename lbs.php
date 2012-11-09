<?php
class LBS {
	static $EARTH_RADIUS = 6371;
	public static function GetDistance($lat1, $lng1, $lat2, $lng2)  
	{  
		$EARTH_RADIUS = self::$EARTH_RADIUS;  
		$radLat1 = deg2rad($lat1);  
		$radLat2 = deg2rad($lat2);  
		$a = $radLat1 - $radLat2;  
		$b = deg2rad($lng1) - deg2rad($lng2);  
		
		// Haversine( 维度差值 )  + 角度2的余弦 * 角度1的余弦 *  Haversine( 经度差值  )
		// Haversine(x) = x/2 的正弦的平方 = ( 1 - x的余弦 ) /2
		$s = pow(sin($a/2),2) +  cos($radLat1)*cos($radLat2)*pow(sin($b/2),2);
		$s = 2 * asin(sqrt( $s ));  
		$s = $s *$EARTH_RADIUS;  
		$s = round($s * 10000) / 10000;  
		return $s;  
	}

	function returnSquarePoint($lng, $lat,$distance = 0.5){
		$dlng = 2 * asin(sin($distance / (2 * self::EARTH_RADIUS)) / cos(deg2rad($lat)));
		$dlng = rad2deg($dlng);
		$dlat = $distance/self::EARTH_RADIUS;
		$dlat = rad2deg($dlat);	
		return array(
			'lt'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),
			'rt'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),
			'lb'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),
			'rb'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng)
		);
	}



}
// 弧度转角度 php build in funciton deg2rad()
function rad($d)  
{  
	return $d * 3.1415926535898 / 180.0; 
}  

function GetDistance($lat1, $lng1, $lat2, $lng2)  
{  
	$EARTH_RADIUS = 6371;  
	$radLat1 = deg2rad($lat1);  
	$radLat2 = deg2rad($lat2);  
	$a = $radLat1 - $radLat2;  
	$b = deg2rad($lng1) - deg2rad($lng2);  
	
	// Haversine( 维度差值 )  + 角度2的余弦 * 角度1的余弦 *  Haversine( 经度差值  )
	// Haversine(x) = x/2 的正弦的平方 = ( 1 - x的余弦 ) /2
	$s = pow(sin($a/2),2) +  cos($radLat1)*cos($radLat2)*pow(sin($b/2),2);
	$s = 2 * asin(sqrt( $s ));  
	$s = $s *$EARTH_RADIUS;  
	$s = round($s * 10000) / 10000;  
	return $s;  
}

define(EARTH_RADIUS, 6371);
function returnSquarePoint($lng, $lat,$distance = 0.5){
	$dlng = 2 * asin(sin($distance / (2 * EARTH_RADIUS)) / cos(deg2rad($lat)));
	$dlng = rad2deg($dlng);
	$dlat = $distance/EARTH_RADIUS;
	$dlat = rad2deg($dlat);	
	return array(
		'left-top'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),
		'right-top'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),
		'left-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),
		'right-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng)
	);
}

echo LBS::GetDistance(100.1,200.2,300.3,400.4);