<!DOCTYPE html>
    <head>
		<link rel="stylesheet" media="screen" type="text/css" href="style.css"/>
        <title>Top 10 Searches</title>
        <meta charset = "utf-8" />
    </head>
	
	<body>
	<div id="container">
		<div id="menu2"
			<?php include_once('header.php'); ?>
		</div>
		
		<div id= "main2">
		<?php
		// Connects to movies database.
		$conn = @mysqli_connect("localhost", "root", "usbw", "movies");
		
		// If a connection to the database could not be established, a message is displayed and the script is exited.
		if (!$conn) {
			
			echo "Could not connect to the database.";
			exit();
		}
		
		// Gets top 10 searches.
		$sql = "SELECT title, searchNo FROM `movies_tbl` ORDER BY searchNo DESC LIMIT 10";
		if ($result = mysqli_query($conn, $sql)) {
			
			// Creates the base image for the graph.
			$image = @imagecreate(900, 470)
			or die ("Cannot Initialize new GD image stream.");
			
			// Sets background colour, text colour, and font.
			$backgroundColour = imagecolorallocate($image, 5, 24, 34);
			$textColour = imagecolorallocate($image, 255, 255, 255);
			$font = "assets\Source_Sans_Pro\SourceSansPro-Light.ttf";
			
			// Creates array with colours for the key.
			$boxColours = array(imagecolorallocate($image, 255, 0, 0),
								imagecolorallocate($image, 255, 102, 0),
								imagecolorallocate($image, 255, 255, 0),
								imagecolorallocate($image, 0, 255, 0),
								imagecolorallocate($image, 0, 0, 255),
								imagecolorallocate($image, 102, 0, 255),
								imagecolorallocate($image, 153, 0, 204),
								imagecolorallocate($image, 255, 255, 255),
								imagecolorallocate($image, 128, 128, 128),
								imagecolorallocate($image, 0, 0, 0));
			
			$count;

			// Finds highest searched movie.
			$max;
			foreach ($result as $row) {

				$max = ($row['searchNo'] > $max ? $row['searchNo'] : $max);
			}
			
			foreach ($result as $row) {

				// Increments counter variable for each row.
				++$count;

				$searchNo = $row['searchNo'];
				
				// Variable for movie title. If title is longer than 25 characters, an elipses is added after the 25th character.
				$label = substr($row['title'], 0, 25);
				$label = ($row['title'] != $label ? $label .= "..." : $label);
				
				// Gets starting point for box in the key on the right.
				$boxPos = $count * 35;

				// Draws key on right side of graph.
				imagefilledrectangle($image, 660, $boxPos + 24, 670, $boxPos + 34, $boxColours[$count - 1]); // Draws coloured box to represent title in the graph.
				imagerectangle($image, 660, $boxPos + 24, 670, $boxPos + 34, $boxColours[9]); // Draws black border around box.
				imagettftext($image, 12, 0, 680, 35 + $count * 35, $textColour, $font, $label);// Writes title next to box.
				
				// Centre, start, end, and height of column.
				$centre = (650 / 11) * $count;
				$start = $centre - 20;
				$end = $centre + 20;
				$height = 430 - ((430 / $max) * $searchNo) / 1.3; // Gets relative height for column.
				// $height;

				// // If there are no searches, make the height inline with the x margin.
				// if ($max == 0 )
				// {
				// 	$height = 430;
				// }

				// // If there are searches, work out height relative to max.
				// // The highest searched item is always the same height regardless of its value. (THIS IS INCORRECT)
				// else
				// {
				// 	$height = 430 - $searchNo * (430 / ($max + 10));
				// }
				
				// Works out the x value that will centre the searchNo.
				$bbox = imagettfbbox(12, 0, $font, $searchNo);
				$x = $bbox[0] - ($bbox[4] / 2) + $centre;

				// Draws column for number of searches.
				imagerectangle($image, $start, 430, $end, $height, $boxColours[7]);
				// Draws labels for column.
				imagefilledrectangle($image, $start + 15, 450, $end - 15, 440, $boxColours[$count - 1]); // Draws coloured box to represent title.
				imagerectangle($image, $start + 15, 450, $end - 15, 440, $boxColours[9]); // Draws black border around box.
				imagettftext($image, 12, 0, $x, 470, $textColour, $font, $searchNo); // Writes search number under box.
			}
			
			// Draws x margin.
			imageline($image, 20, 430, 630, 430, $textColour);
			
			// Exports, displays, then destroys the graph image.
			imagepng($image, "assets/graph.png");
			echo "<img src='assets/graph.png'>";
			imagedestroy($image);
			
			mysqli_free_result($result);
		} // End of retrieve top 10 searches query.

		// When 'Reset' button is pressed, set all search numbers to 0.
		if (isset($_POST['submit'])) {
		
			$sql = "UPDATE movies_tbl SET searchNo = 0";

			if (mysqli_query($conn, $sql) == true) {
			}
		}

		mysqli_close($conn);
		?>

		</div>
		<form method="post">
			<p><button type="submit" name="submit">Reset</button></p>
        </form>
		
		<p>
			<?php include_once('footer.php'); ?>
		</p>
	</div>
	</body>
</html>