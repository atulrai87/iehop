<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Google Maps JavaScript API v3 Example: Geocoding Simple</title>
    
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    
  </head>
<style type="text/css">
#header{
	width:100%;
	height:70px;
	background-color:#5F9EA0;
	border:1px solid #dcdcdc;
}

#footer{
	width:100%;
	height:30px;
	background-color:#5F9EA0;
	border:1px solid #dcdcdc;
}

.clear{
	clear:both;
}


</style>
  <body>
	
	
	<div id="container">
		<?php 
			if(!empty($setError)){
				echo $setError; die;
			}
		?>
		<form name="gallery" id="gallery" action="" method="post" enctype="multipart/form-data">
			<table cellspacing="10" cellpadding="10" align="center" style="border:2px solid #dcdcdc;">
				<tr>
					<td>Image1: </td>
					<td><input type="file" name="image1" id="image1"></td>
				</tr>
				<tr>
					<td>Image2: </td>
					<td><input type="file" name="image2" id="image2"></td>
				</tr>
				<tr>
					<td>Image3: </td>
					<td><input type="file" name="image3" id="image3"></td>
				</tr>
				<tr>
					<td>Video1: </td>
					<td><input type="file" name="video1" id="video1"></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" name="submit" id="submit" value="submit"></td>
				</tr>
				
			</table>
		</form>
	</div>
    
    
  </body>
</html>
