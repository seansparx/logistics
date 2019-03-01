<?php

	echo '<pre>';

	$con = mysqli_connect("localhost", "CSS5811", "TxLjxBQjY5S5uMSV", "CSS5811");

	// Check connection
	if (mysqli_connect_errno()) {
		
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	else{
		
		print_r($con);
	}
	die;
?> 

<?php

//ini_set( 'mysqli.default_socket' , '' );

	phpinfo();
?>
