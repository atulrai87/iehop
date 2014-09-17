{include file="header.tpl" load_type='ui'}
<div class="content-block">
<link href='<?php echo base_url()?>application/js/event_calendar/css/fullcalendar.css' rel='stylesheet' />
<link href='<?php echo base_url()?>application/js/event_calendar/css/fullcalendar.print.css' rel='stylesheet' media='print' />
<link href='<?php echo base_url()?>application/js/event_calendar/css/custom.css' rel='stylesheet' />
<script src='<?php echo base_url()?>application/js/event_calendar/moment.min.js'></script>
<script src='<?php echo base_url()?>application/js/event_calendar/jquery.min.js'></script>
<script src='<?php echo base_url()?>application/js/event_calendar/fullcalendar.min.js'></script>
{literal}
<script>
    $(document).ready(function() {

		$('#calendar').fullCalendar({
			defaultDate: '2014-09-12',
			editable: false,
			eventLimit: true, // allow "more" link when too many events
			events: [
				{/literal}{foreach item=item key=key from=$events}{literal}
				{
					title: "{/literal}{$item->event_name}{literal}",
					start: "{/literal}{$item->start_date}{literal}",
					end: "{/literal}{$item->end_date}{literal}",
				},
				{/literal}{/foreach}{literal}
			]
		});
		
	});
</script>
{/literal}
<div class="view_events">
    <div style="float:right">
    <input type="button" value="Add New Event" onclick="window.location='<?php echo base_url()?>events/newEvent'"/>
    </div>
	<div class="clr" style="padding-bottom:10px"></div>
    <div class="event_left">
	<form action="<?php echo base_url() ?>events/searchEvent" method="post" >
        <div>Location</div>
        <div><select name="country" style="margin-bottom:5px">
            <option value="">Select Country</option>
            <option value="1">Afghanistan</option>
			<option value="5">Andorra</option>
			<option value="8">Antarctica</option>
			<option value="10">Argentina</option>
			<option value="12">Aruba</option>
			<option value="14">Australia</option>
			<option value="15">Austria</option>
			<option value="17">Bahamas, The</option>
			<option value="20">Bangladesh</option>
			<option value="21">Barbados</option>
			<option value="24">Belgium</option>
			<option value="25">Belize</option>
			<option value="28">Bhutan</option>
			<option value="30">Bosnia and Herzegovina</option>
			<option value="33">Brazil</option>
			<option value="36">Brunei</option>
			<option value="37">Bulgaria</option>
			<option value="39">Burma</option>
			<option value="43">Canada</option>
			<option value="48">Chile</option>
			<option value="49">China</option>
			<option value="53">Colombia</option>
			<option value="59">Costa Rica</option>
			<option value="61">Croatia</option>
			<option value="62">Cuba</option>
			<option value="64">Czech Republic</option>
			<option value="68">Dominican Republic</option>
			<option value="70">Ecuador</option>
			<option value="71">Egypt</option>
			<option value="72">El Salvador</option>
			<option value="80">Fiji</option>
			<option value="82">France</option>
			<option value="84">French Polynesia</option>
			<option value="90">Germany</option>
			<option value="92">Gibraltar</option>
			<option value="94">Greece</option>
			<option value="95">Greenland</option>
			<option value="96">Grenada</option>
			<option value="99">Guatemala</option>
			<option value="103">Guyana</option>
			<option value="104">Haiti</option>
			<option value="107">Honduras</option>
			<option value="108">Hong Kong</option>
			<option value="111">Iceland</option>
			<option value="112">India</option>
			<option value="113">Indonesia</option>
			<option value="115">Iraq</option>
			<option value="116">Ireland</option>
			<option value="118">Israel</option>
			<option value="119">Italy</option>
			<option value="120">Jamaica</option>
			<option value="122">Japan</option>
			<option value="126">Jordan</option>
			<option value="128">Kazakhstan</option>
			<option value="129">Kenya</option>
			<option value="133">Korea, South</option>
			<option value="134">Kuwait</option>
			<option value="135">Kyrgyzstan</option>
			<option value="137">Latvia</option>
			<option value="138">Lebanon</option>
			<option value="139">Lesotho</option>
			<option value="265">lithuania</option>
			<option value="143">Lithuania</option>
			<option value="144">Luxembourg</option>
			<option value="146">Macedonia</option>
			<option value="147">Madagascar</option>
			<option value="150">Maldives</option>
			<option value="158">Mexico</option>
			<option value="162">Monaco</option>
			<option value="163">Mongolia</option>
			<option value="164">Montenegro</option>
			<option value="166">Morocco</option>
			<option value="171">Nepal</option>
			<option value="172">Netherlands</option>
			<option value="175">New Zealand</option>
			<option value="176">Nicaragua</option>
			<option value="183">Norway</option>
			<option value="184">Oman</option>
			<option value="185">Pakistan</option>
			<option value="188">Panama</option>
			<option value="191">Paraguay</option>
			<option value="192">Peru</option>
			<option value="193">Philippines</option>
			<option value="195">Poland</option>
			<option value="196">Portugal</option>
			<option value="198">Qatar</option>
			<option value="200">Romania</option>
			<option value="201">Russia</option>
			<option value="211">Saudi Arabia</option>
			<option value="212">Senegal</option>
			<option value="213">Serbia</option>
			<option value="216">Singapore</option>
			<option value="257">slovenia</option>
			<option value="223">Spain</option>
			<option value="225">Sri Lanka</option>
			<option value="226">Sudan</option>
			<option value="230">Sweden</option>
			<option value="231">Switzerland</option>
			<option value="232">Syria</option>
			<option value="233">Taiwan</option>
			<option value="236">Thailand</option>
			<option value="240">Trinidad and Tobago</option>
			<option value="243">Turkey</option>
			<option value="244">Turkmenistan</option>
			<option value="248">Ukraine</option>
			<option value="249">United Arab Emirates</option>
			<option value="250">United Kingdom</option>
			<option value="251">United States</option>
			<option value="252">Uruguay</option>
			<option value="253">Uzbekistan</option>
			<option value="255">Venezuela</option>
			<option value="256">Vietnam</option>
			<option value="261">Western Sahara</option>
			<option value="264">Zimbabwe</option>
        </select></div>
        <div><select name="region" style="margin-bottom:5px">
            <option value="">Select Region</option>
			<option value="1487">Bihar State of</option>
			<option value="1466">Himachal Pradesh</option>
			<option value="7592">No region</option>
			<option value="1459">State of Andhra Pradesh</option>
			<option value="1483">State of Arunachal Pradesh</option>
			<option value="1460">State of Assam</option>
			<option value="1490">State of Chhattisgarh</option>
			<option value="1486">State of Goa</option>
			<option value="1464">State of Gujarat</option>
			<option value="1465">State of Haryana</option>
			<option value="1467">State of Jammu and Kashmir</option>
			<option value="1491">State of Jharkhand</option>
			<option value="1473">State of Karnataka</option>
			<option value="1468">State of Kerala</option>
			<option value="1488">State of Madhya Pradesh</option>
			<option value="1470">State of Maharashtra</option>
			<option value="1471">State of Manipur</option>
			<option value="1472">State of Meghalaya</option>
			<option value="1484">State of Mizoram</option>
			<option value="1474">State of Nagaland</option>
			<option value="1475">State of Orissa</option>
			<option value="1477">State of Punjab</option>
			<option value="1478">State of Rajasthan</option>
			<option value="1482">State of Sikkim</option>
			<option value="1479">State of Tamil Nadu</option>
			<option value="1480">State of Tripura</option>
			<option value="1489">State of Uttar Pradesh</option>
			<option value="1492">State of Uttaranchal</option>
			<option value="1481">State of West Bengal</option>
			<option value="1461">Union Territory of Chandigarh</option>
			<option value="1463">Union Territory of Delhi</option>
			<option value="1469">Union Territory of Lakshadweep</option>
			<option value="1476">Union Territory of Pondicherry</option>
		</select></div>
        <div><select name="city">
			<option value="0">Select City</option>
			<option value="653951">Alipur</option>
			<option value="653952">Azadpur</option>
			<option value="653953">Badarpur</option>
			<option value="653954">Baqargarh</option>
			<option value="653955">Bawana</option>
			<option value="653956">Chhatarpur</option>
			<option value="653957">Connaught Place</option>
			<option value="653958">Delhi</option>
			<option value="653959">Dera Mandi</option>
			<option value="653960">Dhandasa</option>
			<option value="653961">Dichaon Kalan</option>
			<option value="653962">Dindarput</option>
			<option value="653963">Isharheri</option>
			<option value="653964">Jafarpur Kalan</option>
			<option value="653965">Jawaharnagar</option>
			<option value="653966">Jharoda Kalan</option>
			<option value="653967">Kair</option>
			<option value="653968">Kalkaji Devi</option>
			<option value="653969">Karol Bagh</option>
			<option value="653970">Khaira</option>
			<option value="653971">Kharkhari Nahar</option>
			<option value="653972">Mahrauli</option>
			<option value="653973">Mandhela Kalan</option>
			<option value="653974">Mandhela Khurd</option>
			<option value="653975">Mitraon</option>
			<option value="653976">Mukmelpur</option>
			<option value="653977">Najafgarh</option>
			<option value="653978">Nangloi Jat</option>
			<option value="653979">Narela</option>
			<option value="653980">New Delhi</option>
			<option value="653981">Nilwal</option>
			<option value="653982">Paharganj</option>
			<option value="653983">Palam</option>
			<option value="653984">Paprawat</option>
			<option value="653985">Roshanpura</option>
			<option value="653986">Sabzi Mandi</option>
			<option value="653987">Samalkha</option>
			<option value="653988">Shakurpur</option>
			<option value="653989">Soreda</option>
			<option value="653990">Surkhpur</option>
			<option value="653991">Tughlakabad</option>
		</select></div>
        <br /><br /><br />
        <div>Event Type</div>
        <div><select name="event_type" style="margin-bottom:5px">			
			<option value="0">Select Type</option>
			<option value="1">Cultural</option>
			<option value="2">Sporting</option>
			<option value="3">Events</option>
			<option value="4">Misc</option>
		</select></div>
        <input type="submit" value="Search" />
		</form>
    </div>
    <div class="event_right">
        <div id='calendar' style="width:99%"></div>
    </div>
	<div class="clr"></div>
	<div class="event_list">
        Event List<br/>
        <ul>
		{foreach item=item key=key from=$events}
		<li>{$item->event_name}</li>
		{/foreach}
       </ul>
	</div>
</div>
	</div>
{include file="footer.tpl"}