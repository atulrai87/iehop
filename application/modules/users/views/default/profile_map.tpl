{include file="header.tpl" load_type='ui'}
	<div class="content-block">
		{literal}
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

#left_panel{
	float: left;
	border: 1px solid #dcdcdc;
	width: 29%;
	height: 500px;
	margin-right:10px;
	background-color:#F5F5F5;
}

#left_panel table{
	font-family: times new roman;
	font-size: 14px;
}

#left_panel input{
	width:150px;
}

#left_panel select{
	width:200px;
}

#right_panel{
	float: left;
	border: 1px solid #dcdcdc;
	width: 69%;
	height: 500px;
	background-color:#F5F5F5;
}

		 </style>
		 
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
		 
		 <script type="text/javascript">

		var base_url = '{/literal}{$site_url}{literal}';
      var map, infowindow;
      var geocoder;
      var bounds;
      var markersArr = [];
      
      var markerLat1 = markerLng1 = markerLat2 = markerLng2 = markerLat3 = markerLng3 = markerLat4 = markerLng4 = '';
      
      function initialize() {
		geocoder = new google.maps.Geocoder();
		bounds = new google.maps.LatLngBounds();
		infowindow = new google.maps.InfoWindow();
		
        var latlng = new google.maps.LatLng(37.090240, -95.712891);
        var mapOptions = {
          zoom: 4,
          center: latlng,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        
        map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
        
        
		var optionsAutocomplete = { componentRestrictions: {country: ''} };
		var input1 = (document.getElementById("my_location"));
		var autocomplete1 = new google.maps.places.Autocomplete(input1);
		
		google.maps.event.addListener(autocomplete1, 'place_changed', function() {
			var place1 = autocomplete1.getPlace();
			markerLat1 = place1.geometry.location.lat();
			markerLng1 = place1.geometry.location.lng();
			$('#my_location_latlng').val(markerLat1+','+markerLng1);
			
			var marker = new google.maps.Marker({
                map: map,
                position: place1.geometry.location,
                icon:base_url+'images/7.png'
            });
		});
		
		var input2 = (document.getElementById("ehopped_location"));
		var autocomplete2 = new google.maps.places.Autocomplete(input2);
		google.maps.event.addListener(autocomplete2, 'place_changed', function() {
			var place2 = autocomplete2.getPlace();
			markerLat2 = place2.geometry.location.lat();
			markerLng2 = place2.geometry.location.lng();
			$('#ehopped_location_latlng').val(markerLat2+','+markerLng2);
			
			var marker = new google.maps.Marker({
                map: map,
                position: place2.geometry.location,
                icon:base_url+'images/2.png'
            });
            
		});
		
		var input3 = (document.getElementById("visited_location"));
		var autocomplete3 = new google.maps.places.Autocomplete(input3);
		google.maps.event.addListener(autocomplete3, 'place_changed', function() {
			var place3 = autocomplete3.getPlace();
			markerLat3 = place3.geometry.location.lat();
			markerLng3 = place3.geometry.location.lng();
			$('#visited_location_latlng').val(markerLat3+','+markerLng3);
			
			var marker = new google.maps.Marker({
                map: map,
                position: place3.geometry.location,
                icon:base_url+'images/1.png'
            });
		});
		
		var input4 = (document.getElementById("would_like_to_visit"));
		var autocomplete4 = new google.maps.places.Autocomplete(input4);
        google.maps.event.addListener(autocomplete4, 'place_changed', function() {
			var place4 = autocomplete4.getPlace();
			markerLat4 = place4.geometry.location.lat();
			markerLng4 = place4.geometry.location.lng();
			$('#would_like_to_visit_latlng').val(markerLat4+','+markerLng4);
			
			var marker = new google.maps.Marker({
                map: map,
                position: place4.geometry.location,
                icon:base_url+'images/6.png'
            });
		}); 
		
		get_all_records();   
    }
	
      
	function add_locations_data(){
		var formData = $('form#my_locations_form').serialize();
		
		if(markerLat1 == '' || markerLng1 == '' || markerLat2 == '' ||  markerLng2 == '' || markerLat3 == '' || markerLng3 == '' || markerLat4 == '' || markerLng4 == ''){
			alert("All Fields Should Be Filled Out !!");
			return false;
		}
		
		$.ajax({
			url:base_url+'map/add_details_new_location',
			type:'post',
			data:formData,
			success:function(data){
				if(data == 'success'){
					alert('All Details Added Successfully.');
					get_all_records();
				}else{
					alert('Error Occured. Please Try Again Later !!');
				}
			}
		});
	}
	
	function get_all_records(){
		clearMarkers();
		
		$.ajax({
			url:base_url+'map/get_all_locations2',
			type:'post',
			success:function(data){
				var data = JSON.parse(data);
				var dataLength = data.length;
				var countVals = 0;
				for(var i = 0; i < dataLength; i++){
					countVals++;
					var markerDbId = data[i].id; //marker's record id in database.
					var image1	= data[i].image1;
					var image2	= data[i].image2;
					var image3	= data[i].image3;
					var video1  = data[i].video1;
					
					
					var my_location = data[i].my_location;
					var my_location_latlng = data[i].my_location_latlng.split(',');
					my_location_latlng = new google.maps.LatLng(my_location_latlng[0], my_location_latlng[1]); 
					
					
					var ehopped_location  = data[i].ehopped_location;
					var ehopped_location_latlng = data[i].ehopped_location_latlng.split(',');
					ehopped_location_latlng = new google.maps.LatLng(ehopped_location_latlng[0], ehopped_location_latlng[1]); 
					
					
					var visited_location	 = data[i].visited_location;
					var visited_location_latlng = data[i].visited_location_latlng.split(',');
					visited_location_latlng = new google.maps.LatLng(visited_location_latlng[0], visited_location_latlng[1]); 
					
					var would_like_to_visit = data[i].would_like_to_visit;
					var would_like_to_visit_latlng = data[i].would_like_to_visit_latlng.split(',');
					would_like_to_visit_latlng = new google.maps.LatLng(would_like_to_visit_latlng[0], would_like_to_visit_latlng[1]); 
					
					var marker1IconPath = base_url+'images/7.png';
					if(countVals == dataLength){
						marker1IconPath = base_url+'images/MyLocationMarker.png';
					}
					marker1 = new google.maps.Marker({
						map: map,
						position:my_location_latlng,
						location_name:my_location,
						markerDbId:markerDbId,
						image1:image1,
						image2:image2,
						image3:image3,
						video1:video1,
						icon: base_url+'images/7.png'
					});
					marker2 = new google.maps.Marker({
						map: map,
						position:ehopped_location_latlng,
						location_name:ehopped_location,
						markerDbId:markerDbId,
						image1:image1,
						image2:image2,
						image3:image3,
						video1:video1,
						icon:base_url+'images/2.png'
					});
					marker3 = new google.maps.Marker({
						map: map,
						position:visited_location_latlng,
						location_name:visited_location,
						markerDbId:markerDbId,
						image1:image1,
						image2:image2,
						image3:image3,
						video1:video1,
						icon:base_url+'images/1.png'
					});
					marker4 = new google.maps.Marker({
						map: map,
						position:would_like_to_visit_latlng,
						location_name:would_like_to_visit,
						markerDbId:markerDbId,
						image1:image1,
						image2:image2,
						image3:image3,
						video1:video1,
						icon:base_url+'images/6.png'
					});
					
					markersArr.push(marker1);
					markersArr.push(marker2);
					markersArr.push(marker3);
					markersArr.push(marker4);
					
					bindInfoWindow(marker1, 1);
					bindInfoWindow(marker2, 2);
					bindInfoWindow(marker3, 3);
					bindInfoWindow(marker4, 4);	
				}
			}
		});
	}
	
	function bindInfoWindow(marker, type){
		
		google.maps.event.addListener(marker, "click", function () { console.log(marker)
				var html =  '<div>';
					html += 	'<table cellpadding="1" cellspacing="1" style="border:1px solid #dcdcdc;padding:2px;" >';
					html += 		'<tr><td>'+this.location_name+'</td></tr>';
					if(type == 2 || type == 3){
						
						if(marker.image1 == null && marker.image2 == null && marker.image3 == null && marker.video1 == null)
							html += 		'<tr><td><span style="color:#3E78FD;cursor:pointer;" onclick="display_gallery_form('+marker.markerDbId+')">Add More Details Here..</span></td></tr>';
							html += 	'<tr><td>';
						
						if(marker.image1)
							html += '<img src="http://medimegastore.com/gmap/iehop/uploads/'+marker.image1+'" width="50px">';
						if(marker.image2)
							html += '<img src="http://medimegastore.com/gmap/iehop/uploads/'+marker.image2+'" width="50px">';
						if(marker.image3)
							html += '<img src="http://medimegastore.com/gmap/iehop/uploads/'+marker.image3+'" width="50px">';
						if(marker.video1)
							html += '<img src="images/video_default.png" width="50px">';
						
						html += '</td></tr>';
						
					}
					
					html += 	'</table>';
					html += '</div>';
					
				infowindow.setContent(html);
				infowindow.open(map, this);
		});
	}
	
	function display_gallery_form(markerDbId){
		var iframe = '<img src="images/close.png" width="20px" align="right" style="cursor:pointer;" onclick="close_iframe_window()">';
		iframe += '<iframe src="'+base_url+'map/view_iframe_form?fieldId='+markerDbId+'" name="" width="100%" height="100%" frameborder="0" >';
		
		$('#iframe_containner').show().html(iframe);
	}
	
	function clearMarkers(){
		if(markersArr.length > 0){
			for(i in markersArr){
				markersArr[i].setMap(null);
			}
		}
	}
	
	function close_iframe_window(){
		$('#iframe_containner').hide().html('');
	}
	
	$(document).ready(function(){
		$('#my_locations_form')[0].reset();
		initialize();
		$('#iframe_containner').css({top:'25%',left:'50%',margin:'-'+($('#myDiv').height() / 2)+'px 0 0 -'+($('#iframe_containner').width() / 2)+'px'});
		$(".main .content .mb20.mt5").hide();
	});
	
		 </script>
		  {/literal}	
		<div>
			<div id="iframe_containner" style="z-index:9999;position:absolute;border:3px solid #A9A9A9;padding:10px;border-radius:5px;background-color:#E6E6FA;display:none;width:450px;height:330px;">test</div>

	
	<div id="left_panel" style="float:left;">
		<form name="my_locations_form" id="my_locations_form" action="" method="post">
		<table cellspacing="5" cellpadding="5" align="center">
			<tr>
				<td><img src="{$site_url}images/7.png" align="top" width="15">&nbsp;My Location</td>
				<td>
					<input type="text" name="my_location" id="my_location" >
					<input type="hidden" name="my_location_latlng" id="my_location_latlng" value="">
				</td>
			</tr>
			<tr>
				<td><img src="{$site_url}images/2.png" align="top" width="15">&nbsp;Places I have eHopped to</td>
				<td>
					<input type="text" name="ehopped_location" id="ehopped_location" >
					<input type="hidden" name="ehopped_location_latlng" id="ehopped_location_latlng" value="">
				</td>
			</tr>
			<tr>
				<td><img src="{$site_url}images/1.png" align="top" width="15">&nbsp;Places I have visited</td>
				<td>
					<input type="text" name="visited_location" id="visited_location" >
					<input type="hidden" name="visited_location_latlng" id="visited_location_latlng" value="">
				</td>
			</tr>
			<tr>
				<td><img src="{$site_url}images/6.png" align="top" width="15">&nbsp;Places I would like to visit</td>
				<td>
					<input type="text" name="would_like_to_visit" id="would_like_to_visit" >
					<input type="hidden" name="would_like_to_visit_latlng" id="would_like_to_visit_latlng" value="">
				</td>
			</tr>
			<tr>
				<td colspan="2"><input type="button" name="btn" id="btn" value="Add Locations" onclick="add_locations_data()" ></td>
			</tr>
			
		</table>
		</form>
	</div>
    <div id="right_panel" style="float:left;">
		<div  id="map_canvas" style="height:500px;width:100%;"></div>
    </div>
    

	</div>
{include file="footer.tpl"}