<!DOCTYPE html>
    <head>
		<link rel="stylesheet" media="screen" type="text/css" href="style.css"/>
        <title>Search</title>
        <meta charset = "utf-8" />
    </head>
	
	<body>
	<div id="container">
		<div id="menu3"
			<?php include_once('header.php'); ?>
		</div>
		
		<div id="main3">
		
		<form method="post">
			<p>Title <input type="text" name="title"/></p>
			<p>Genre <input type="text" name="genre"/></p>
			<p>Rating <input type="text" name="rating"/></p>
			<p>Year <input type="text" name="year"/></p>
			<p><button type="submit" name="submit">Search</button></p>
        </form>
		</div>
		
		<?php
		// When the 'Search' button is pressed.
		if (isset($_POST['submit'])) {
			
			echo "<div id='table'>";
			
			// Makes variables for all search items and adds slashes before any escape characters.
			$title = addslashes($_POST["title"]);
			$genre = addslashes($_POST["genre"]);
			$rating = addslashes($_POST["rating"]);
			$year = addslashes($_POST["year"]);
			
			// Connects to movies database.
			$conn = @mysqli_connect("localhost", "root", "usbw", "movies");
		
			// If a connection to the database could not be established, a message is displayed and the script is exited.
			if (!$conn) {
				
				echo "Could not connect to the database.";
				exit();
			}
				
			$searchFor = "title LIKE '%$title%' AND 
						genre LIKE '%$genre%' AND 
						rating LIKE '%$rating%' AND 
						year LIKE '%$year%'";
			$sql = "SELECT * FROM movies_tbl WHERE $searchFor";
			if ($result = mysqli_query($conn, $sql)) {
				
				// If no matching movie could be found.
				if (mysqli_num_rows($result) == 0) {
					
					echo "That movie could not be found.";
				}
				
				// If a match is found.
				else
				{
					// Creates headers for result table.
					echo "<table><tr>
							<th>ID</th><th>Title</th>
							<th>Studio</th><th>Status</th>
							<th>Sound</th><th>Versions</th>
							<th>Price</th><th>Rating</th>
							<th>Year</th><th>Genre</th>
							<th>Aspect</th>
							</tr>";

					// Writes all search results to table.
					foreach ($result as $row) {

						echo "<tr>
							<td>".$row['ID'].          "</td><td>".$row['title']."</td>
							<td>".$row['studio'].      "</td><td>".$row['status']."</td>
							<td>".$row['sound'].       "</td><td>".$row['versions']."</td>
							<td>".$row['recRetPrice']. "</td><td>".$row['rating']."</td>
							<td>".$row['year'].        "</td><td>".$row['genre']."</td>
							<td>".$row['aspect'].      "</td>
							</tr>";
					}
					echo "</table>";
				}
				
				mysqli_free_result($result);
				
			} else {
				
				echo "To search the database, enter a movie's details in the text boxes above.";
			} // End of search movies query.
			
			// Increments searchNo for all search results.
			$sql = "UPDATE movies_tbl SET searchNo = searchNo + 1 WHERE $searchFor";
			if (mysqli_query($conn, $sql) === true) {}
			mysqli_close($conn);
			
			echo "</div>";
		}
		?>
	
		<p>
			<?php include_once('footer.php'); ?>
		</p>
		
	</div>
	</body>
</html>