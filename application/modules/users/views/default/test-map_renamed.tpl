{literal}
 <style type="text/css">
 /* body { font: normal 10pt Helvetica, Arial; }  */
 #map { width: 1062px; height: 440px; border: 0px; padding: 0px; }
 </style>
 
 <script src="http://maps.google.com/maps/api/js?v=3&sensor=false" type="text/javascript"></script>
 
 <script type="text/javascript">

 var icon = new google.maps.MarkerImage("http://www.google.com/mapfiles/marker.png",
 new google.maps.Size(32, 32), new google.maps.Point(0, 0),
 new google.maps.Point(16, 32));
 var center = null;
 var map = null;
 var currentPopup;
 var bounds = new google.maps.LatLngBounds();
 function addMarker(lat, lng, info ,jobseeker) {
 var pt = new google.maps.LatLng(lat, lng);
 bounds.extend(pt);
 var marker = new google.maps.Marker({
 position: pt,
 icon: icon,
 map: map
 });
 var popup = new google.maps.InfoWindow({
 content: info + jobseeker,
 maxWidth: 300
 });
 google.maps.event.addListener(marker, "mouseover", function() {
 if (currentPopup != null) {
 currentPopup.close();
 currentPopup = null;
 }
 popup.open(map, marker);
 currentPopup = popup;
 });
 google.maps.event.addListener(popup, "closeclick", function() {
 //map.panTo(center);
 currentPopup = null;
 });
 }
 function initMap() {
 map = new google.maps.Map(document.getElementById("map"), {
 center: new google.maps.LatLng(39.828175, -98.5795),
 zoom: 4,
 mapTypeId: google.maps.MapTypeId.ROADMAP,
 mapTypeControl: false,
 mapTypeControlOptions: {
 style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR
 },
 navigationControl: true,
 navigationControlOptions: {
 style: google.maps.NavigationControlStyle.SMALL
 }
 });
 {/literal}	
 {foreach from=$user_info key=k item=new_item}
addMarker('{$new_item->lat}','{$new_item->lang}','{$new_item->location}','{$new_item->datetime}');
    {/foreach}
{literal}			 			  
 center = bounds.getCenter();
 //map.fitBounds(bounds);
 map.toString();
 }

 </script>
  {/literal}	
 <body onLoad="initMap()"> 
 <div style="position:absolute;top:400px;">
  <section class="map-area">
	 <div class="holder">
         <div id="map"></div>
     </div>
  </section>
 <div>
 <br/>
 <br/>
    <form name="input" method="POST" id="add_location" action="http://162.144.100.131/~iehop/users/saveUserLocation">
	<label>Location:</label> 
	<input type="text" name="location">
	<label>Country:</label> 
	<input type="text" name="country">
	<label>State:</label> 
	<input type="text" name="state">
	<label>City:</label> 
	<input type="text" name="city">
	<input type="submit" value="Submit">
	</form>
    </div>
		</div>	
{php}echo "hello!"{/php}				
{php}echo "hello!"{/php}				
{php}echo "hello!"{/php}				
{php}echo "hello!"{/php}				
{php}echo "hello!"{/php}				
{echo $user_info;} 	

<table>
<tr>
  <td>ID</td>
  <td>Location</td> 
   <td>Date Time</td> 
</tr>
    {foreach from=$user_info key=k item=new_item}
    <tr>
        <td>{$new_item->id}</td>
        <td>{$new_item->location}</td>
		<td>{$new_item->datetime}</td>
    </tr>
    {/foreach}
</table>
		

    
