<?php
error_reporting(E_ALL);
//ini_set('display_errors', 'On');
?>

<?php

	function makeMulTable(&$validatedVariables) {
		$minCand = $_GET['min-multiplicand'];
		$maxCand = $_GET['max-multiplicand'];
		$minMul = $_GET['min-multiplier'];
		$maxMul = $_GET['max-multiplier'];

		if($minCand == NULL) {
			echo "<p>Missing parameter [min-multiplicand]</p>";
			$validatedVariables = false;
		}
		if($maxCand == NULL) {
			echo "<p>Missing parameter [max-multiplicand]</p>";
			$validatedVariables = false;
		}
		if($minMul == NULL) {
			echo "<p>Missing parameter [min-multiplier]</p>";
			$validatedVariables = false;
		}
		if($maxMul == NULL) {
			echo "<p>Missing parameter [max-multiplier]</p>";
			$validatedVariables = false;
		}
		
		//If the variables are strings yet numeric the function converts the values to integers.
		if (is_numeric($minCand)) {
			$minCand = intval($minCand);
		}
		else {
			//echo "<p>min-multiplicand must be an integer.</p>";
			$validatedVariables = false;
		}

		if (is_numeric($maxCand)) {
			$maxCand = intval($maxCand);
		}	
		else {
			//echo "<p>max-multiplicand must be an integer.</p>";
			$validatedVariables = false;
		}	

		if (is_numeric($minMul)) {
			$minMul = intval($minMul);
		}		
		else {
			//echo "<p>min-multiplier must be an integer.</p>";
			$validatedVariables = false;
		}

		if (is_numeric($maxMul)) {
			$maxMul = intval($maxMul);
		}
		else {
			//echo "<p>max-multiplier must be an interger.</p>";
			$validatedVariables = false;
		}

		//checking to see if the variables are integers.
		if (is_int($minCand) == false) {
			echo "<p>[min-multiplicand] must be an integer.</p>";
			$validatedVariables = false;
		}
		if (is_int($maxCand) == false) {
			echo "<p>[max-multiplicand] must be an integer.</p>";
			$validatedVariables = false;
		}
		if (is_int($minMul) == false) {
			echo "<p>[min-multiplier] must be an integer.</p>";
			$validatedVariables = false;
		}
		if (is_int($maxMul) == false) {
			echo "<p>[max-multiplier] must be an interger.</p>";
			$validatedVariables = false;
		}	

		if ($validatedVariables == true) {
			multTable($minCand, $maxCand, $minMul, $maxMul);
		}

		if($maxCand < $minCand ) {
			echo "<p>Minimum [multiplicand] larger than maximum</p>";
			$validatedVariables = false;
		}
		else if($maxMul < $minMul) {
			echo "<p>Minimum [multiplier] larger than maximum</p>";
			$validatedVariables = false;
		}
	}

	function multTable($minCand, $maxCand, $minMul, $maxMul) {
		$tW = ($maxMul - $minMul) + 2; 		//The table width
		$tH = ($maxCand - $minCand) + 2;	//The table height
		$result = 0;

		echo "<p>PHP Multiplication Table.</p>";
		echo "<table><tr><td></td>";
		for ($x = $minMul; $x <= $maxMul; $x++) {
			echo "<td><b>$x</b></td>";
		}

		for ($y = $minCand; $y <= $maxCand; $y++) {
			echo "<tr><td><b>$y<b></td>";

			for ($x = $minMul; $x <= $maxMul; $x++) {
				$result = $x * $y;
				echo "<td>$result</td>";
			}
			echo "</tr>"; 

		}
		echo "</tr></table>";
	}

	$method = $_SERVER['REQUEST_METHOD'];

	//http://stackoverflow.com/questions/359047/php-detecting-request-type-get-post-put-or-delete
	$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));	

	switch ($method) {
		case 'GET':
			//echo "<p>We have gotten a GET method.</p>";
			break;
		case 'POST':
			//echo "<p>We have a POST method.</p>";
			break;
		default:
			break;
	}

	$validatedVariables = true;

	makeMulTable($validatedVariables);
?>