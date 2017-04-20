<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
	<div class="container">
	<div class="header">
		<h2>The Image Resizer</h2>
		</div>
		<div class="desc">
		<p> This is a PHP based web application where you can resize an image to half of its dimension and then place a text of your choice at the center of the image.Developed by <b>Sourajit Mukherjee</b></p>
		<b>Instructions :</b> File size should be between 1KB to 50MB<br>
		File Formats accepted are : .jpg,.jpeg,.png,.bmp,.gif
		<br>Smaller the text size better text adjust is obtained.<br>
		To download click on image and then proceed accordingly. 

		</div>

		<form role="form" action="upload.php" method="POST" enctype="multipart/form-data">
		<table class="uptable" width="100%">
			<tr>
				<th><b>File Upload</b><hr></th>
				<th><b>Message<b><hr></th>
			</tr>
			<tr>
				<td><input type="file" name="pic" class="pic" required></td>
				<td>
					Type the text to be displayed at the center of the image<br><br>
					<input type="text" name="msg" placeholder="Message" class="msg" required>
				</td>
			</tr>
		</table>
		<div class="center">
			<input type="submit" name="submit" class="submit" value="Submit">
		</div>
			
		</form>
		<?php
		if(isset($_GET['status'])){
			$value=array("There was an error in processing your file","File Uploaded Successfully","File size not in range","The file format should be .jpg,.jpeg,.png,.gif or .bmp","File not uploaded","File exists","Please enter the message");
			echo "<b>Status</b><br>".$value[$_GET['status']];
		}
		?>
		<table class="display" width="100%">
		<tr>
			<th><b>Original</b><hr></th>
			<th><b>Edited</b><hr></th>

		</tr>
		<?php
			$con=mysqli_connect("localhost","root","","images") or die(mysqli_error($con));
			$result=mysqli_query($con,"SELECT * FROM locations");
			$row=mysqli_fetch_array($result);
			while($row){
				
				$ori=$row['original'];
				$edit=$row['edited'];
				$row=mysqli_fetch_array($result);
			?>
			<tr>
				<td ><a href="<?php echo $ori; ?>"><img src="<?php echo $ori; ?>" width="600"  height="300"></a></td>
				<td ><a href="<?php echo $edit; ?>"><img src="<?php echo $edit; ?>" width="400"  height="200"></a></td>
			</tr>
				
			<?php
			}
		?>
		</table>
		</div>
	</body>
</html>