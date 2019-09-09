<!DOCTYPE html>
    <head>
		<link rel="stylesheet" media="screen" type="text/css" href="style.css"/>
        <title>Populate Database</title>
        <meta charset = "utf-8" />
    </head>
	
	<body>
	<div id="container">
		<div id="menu1"
			<?php include_once('header.php'); ?>
		</div>
	
		<div id="main1">
		<p>
			An SQL database named 'movies' will be created.
			Inside will be a table named 'movies_tbl'.
			All the movies from the CSV file 'Movies.csv' will be added to the table.
			The CSV file is located in the assets folder.
		</p>
		
		<!-- Button for creating database. -->
		<form method="post">
            <p><button type="submit" name="submit">Do it</button></p>
        </form>
		
		<?php
		if (isset($_POST['submit'])) {
			
			// Makes a connection to the base level of phpMyAdmin
			$conn = @mysqli_connect("localhost", "root", "usbw");

			// If a connection to phpMyAdmin could not be established, a message is displayed and the script is exited.
			if (!$conn) {

				echo "Could not connect to phpMyAdmin.";
				exit();
			}


			// Creates the movies database.
			$sql = "CREATE DATABASE IF NOT EXISTS movies";
			// Checks if the query can be completed.
			if (mysqli_query($conn, $sql) === true) {

				// Changes the connection to be in the database.
				$conn = @mysqli_connect("localhost", "root", "usbw", "movies");

				// If a connection to the database could not be established, a message is displayed and the script is exited.
				if (!$conn) {

					echo "Could not connect to the database.";
					exit();
				}

				// Creates movies_tbl.
				$sql = "CREATE TABLE IF NOT EXISTS movies_tbl (
															ID INTEGER(4),
															title VARCHAR(100),
															studio VARCHAR(50),
															status VARCHAR(20),
															sound VARCHAR(20),
															versions VARCHAR(20),
															recRetPrice DECIMAL(3,2),
															rating VARCHAR(5),
															year VARCHAR(4),
															genre VARCHAR(50),
															aspect VARCHAR(6),
															searchNo INTEGER(6) DEFAULT 0
															)";
															
				
				// Checks if the query can be completed.
				if (mysqli_query($conn, $sql) === TRUE) {
				
					// Deletes all the values from table.
					$sql = "DELETE FROM movies_tbl";
				
					// Checks if the query can be completed.
					if (mysqli_query($conn, $sql) === true) {
						
						// Loads csv with Movies into table.
						$sql = "LOAD DATA LOCAL INFILE 'assets/Movies.csv'
								INTO TABLE movies_tbl
								FIELDS TERMINATED BY ','
								ENCLOSED BY '\"'
								LINES TERMINATED BY '\r\n'
								IGNORE 1 ROWS";
						
						if (mysqli_query($conn, $sql) === true) {
						}
					} // End of delete values.
				} // End of create table.
			} // End of create database.

			// Closes the connection to the database.
			mysqli_close($conn);
		}
		?>
		</div>
	
		<p>
			<?php include_once('footer.php'); ?>
		</p>
	</div>
	</body>
</html>