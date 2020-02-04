<?php
	//võtame vastu saadetud info
	$rating = $_REQUEST["rating"];
	
	$response = "Läks hästi, hinne: " .$rating;
	echo $response;