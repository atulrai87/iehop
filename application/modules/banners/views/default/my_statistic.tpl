{include file="header.tpl"}

<div class="content-block">

	<h1>{l i='header_my_banner_statistic' gid='banners'}</h1>
	<div class="content-value">
			<div class="edit_block">
				<div class="r">
					<div class="f">{l i='stat_day' gid='banners'}:</div>
					<div class="v"><a href="{$navigation.prev}">{l i="nav_prev" gid='start'}</a> <b>{$navigation.current}</b> <a href="{$navigation.next}">{l i="nav_next" gid='start'}</a></div>
				</div>
				<div class="r">
					<div class="f">{l i='stat_overall_views' gid='banners'}:</div>
					<div class="v">{$statistic.all.view}</div>
				</div>
				<div class="r">
					<div class="f">{l i='stat_overall_clicks' gid='banners'}:</div>
					<div class="v">{$statistic.all.click}</div>
				</div>
			</div>
			<div id="jplot_div"></div>
			<table cellspacing="0" cellpadding="0" class="list">
			<tr>
				<th>{l i='stat_hour' gid='banners'}</th>
				<th class="w100">{l i='stat_views' gid='banners'}</th>
				<th class="w100">{l i='stat_clicks' gid='banners'}</th>
			</tr>
			{foreach item=item key=hour from=$statistic.hour}
			<tr class="stat">
				<td class="hour">{$hour}</td>
				<td class="views">{$item.view}</td>
				<td class="clicks">{$item.click}</td>
			</tr>
			{/foreach}
			</table>
	</div>
	<div class="b outside">
		<a href="{$site_url}users/account/banners" class="btn-link"><i class="icon-arrow-left icon-big edge hover"></i><i>{l i='link_back_to_my_banners' gid='banners'}</i></a>
	</div>
</div>
<div class="clr"></div>
<link rel="stylesheet" type="text/css" href="{$site_root}application/modules/banners/js/jqplot/jquery.jqplot.min.css" />
<!--[if lt IE 9]{js module=banners file='jqplot/excanvas.min.js'}<![endif]-->

<script type='text/javascript'>{literal}
$(function(){
	var viewPoints = [];
	var clicksPoints = [];
	var ticks = [];
	loadScripts(
		[
			"{/literal}{js module=banners file='jqplot/jquery.jqplot.min.js' return='path'}{literal}",
			"{/literal}{js module=banners file='jqplot/plugins/jqplot.canvasTextRenderer.min.js' return='path'}{literal}",
			"{/literal}{js module=banners file='jqplot/plugins/jqplot.canvasAxisLabelRenderer.min.js' return='path'}{literal}",
			"{/literal}{js module=banners file='jqplot/plugins/jqplot.highlighter.min.js' return='path'}{literal}",
			"{/literal}{js module=banners file='jqplot/plugins/jqplot.cursor.min.js' return='path'}{literal}"
		],
		function(){
			$("tr.stat").each(function(){
				var hour = parseInt($(this).find('td.hour').text());
				var views = parseInt($(this).find('td.views').text());
				var clicks = parseInt($(this).find('td.clicks').text());
				viewPoints.push([hour, views]);
				clicksPoints.push([hour, clicks]);
				ticks.push(hour);
			});

			var plot2 = $.jqplot ('jplot_div', [viewPoints, clicksPoints], {
				axesDefaults: {
					labelRenderer: $.jqplot.CanvasAxisLabelRenderer
				},
				seriesDefaults:{
					renderer:$.jqplot.BarRenderer,
					rendererOptions: {fillToZero: true}
				},
				legend: {
					show: true,
					placement: 'insideGrid'
				},
				series:[
					{
						lineWidth:5,
						markerOptions: { style:'dimaond' },
						label: "{/literal}{l i='stat_views' gid='banners' type='js'}{literal}"
					},
					{
						lineWidth:2,
						markerOptions: { style:"filledSquare", size:10 },
						label: "{/literal}{l i='stat_clicks' gid='banners' type='js'}{literal}"
					}
				],
				axes: {
					xaxis: {
						label: "{/literal}{l i='stat_hour' gid='banners' type='js'}{literal}",
						tickOptions: {formatString: '%d h'},
						ticks: ticks
					}
				},
				highlighter: {
					show: true,
					sizeAdjust: 7.5
				},
				cursor: {
					show: false
				}
			});
		},
		'plot2'
	);
});
{/literal}</script>
{include file="footer.tpl"}
