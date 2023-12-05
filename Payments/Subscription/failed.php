<?php

	if (isset($_GET['ukayra_id'])) {
		echo "UkayraID: " . $_GET['ukayra_id'] . "<br />";

		if (isset($_GET['method'])) {
			echo "Method: " . $_GET['method'] . "<br />";
		}

		if (isset($_GET['message'])) {
			echo "Error Message: " . $_GET['message'];
		}
	} else {
		echo "Failed Page";
	}

	echo "<a href='http://192.168.137.1:80/Bulakbuy00/Payments/index.php'>Back to main</a>";
?>