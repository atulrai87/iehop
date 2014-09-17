{strip}
{literal}
<style>
body{background-position: center 38px !important}
#nav{
	font-size: 13px;
	height:38px;
	background:#6e6f71 url({/literal}{$site_root}{$img_folder}{literal}demo/settings_nav_bg.gif) bottom repeat-x;
	margin-bottom: 5px;
}
#nav .settings_nav{width: 980px; margin: 0px auto;}

.back_site{float: left; padding: 0px 10px}
.back_site a{	background:url({/literal}{$site_root}{$img_folder}{literal}demo/back_site.gif) 0 7px no-repeat;	color:#ffffff; line-height: 2.4;	white-space: normal; padding-left: 10px}

.choice_btn{ float: left; background:url({/literal}{$site_root}{$img_folder}{literal}demo/choice_btn_left.gif) center left no-repeat; margin: 5px 0px; padding-left: 2px; }
.choice_btn a{ color:#ffffff; padding: 0px 15px; text-decoration: none; line-height: 1.7;}
.choice_btn_left{margin-right:10px;height:25px; background:url({/literal}{$site_root}{$img_folder}{literal}demo/choice_btn_right.gif) center right no-repeat; position: relative;}
.choice_btn li{list-style:none; cursor: pointer;}
.choice_btn span{color: #ffffff;background: url({/literal}{$site_root}{$img_folder}{literal}demo/arrowDown.png) center right no-repeat; padding:0 20px; margin-right: 20px; line-height:1.7;	white-space: normal; line-height: 22px; }

.rate_btn{ float: right; background:url({/literal}{$site_root}{$img_folder}{literal}demo/rate_btn_left.gif) center left no-repeat; margin: 5px 10px; padding-left: 29px;	cursor:pointer; }
.rate_btn_left{height:25px; background:url({/literal}{$site_root}{$img_folder}{literal}demo/choice_btn_right.gif) center right no-repeat; padding-right: 10px;}
.rate_btn span{color: #ffffff;padding:0 20px 0 10px; line-height:1.7; white-space: normal;}


.bn_btn{ float: right; background:url({/literal}{$site_root}{$img_folder}{literal}demo/bn_btn_left.gif) center left no-repeat; padding-left:2px;	cursor:pointer; margin: 5px 0px;}
.bn_btn_left{height:25px;background:url({/literal}{$site_root}{$img_folder}{literal}demo/bn_btn_right.gif) center right no-repeat;}
.bn_btn span{ color: #ffffff; padding:0 20px; line-height:1.7; white-space: normal; }

.sub_menu{padding:0;margin:4px 0 0;background:#ffffff;border:1px solid #464748;position: absolute;top:20px; left:-1px;width:165px;z-index:98;}
.sub_menu li.s_drop{position: relative; padding-left:20px; background:url({/literal}{$site_root}{$img_folder}{literal}demo/nex_c.gif) 150px 11px no-repeat;}
.sub_menu2{padding:0;margin:4px 0 0;background:#ffffff;border:1px solid #464748;position: absolute;top:-5px; left:160px;width:190px;z-index:99;}
.sub_menu2 li{color:#000000;border-bottom:1px solid #d2d2d2;}
.sub_menu li{padding-left:10px;color:#000000;font-family: Tahoma;font-size:11px;padding: 5px 10px;border-bottom:1px solid #d2d2d2;}
.sub_menu li:hover{background:#c2e6c6;color:#000000;}
.sub_menu li.s_drop:hover{color:#000000;background:#c2e6c6 url({/literal}{$site_root}{$img_folder}{literal}demo/nex_c.gif) 150px 11px no-repeat;}
.sub_menu li.sub{padding:5px 5px 5px 20px;}
.sub_menu a{color: #208B2F; text-decoration: underline;}
#nav span, #nav li {font-family: Tahoma;font-size:11px;}
</style>
{/literal}
{/strip}
<script type="text/javascript" src="{$site_root}{$img_folder}demo/jquery.dropdownPlain.js"></script>
<div id="nav">
	<div class="settings_nav">
		<div class="bn_btn" onclick="document.location.href='http://www.datingpro.com/dating/pricing.php';">
			<div class="bn_btn_left">
				<span>Buy now</span>
			</div>
		</div>
		
		<div class="rate_btn"  onclick="window.open('http://www.pilotgroup.net/questionnaire/feedback.php?fid=76&pid=2061&mode=light','feedback_questionnaire','width=600,resizable=yes,scrollbars=1'); return false;">
			<div class="rate_btn_left">
				<span>Evaluate this product!</span>
			</div>
		</div>

		<div class="back_site"><a data-pjax="0" href="http://www.datingpro.com/dating/">Back to a site</a></div>

		<div class="choice_btn">
			<div class="choice_btn_left"><a data-pjax="0" href="{$site_url}admin">Admin Mode</a></div>
		</div>
		<ul class="demo-dropdown choice_btn">
			<li class="choice_btn_left">
				<span>Template color scheme</span>
				<ul class="sub_menu" style="visibility: hidden; display: none;">
					<li><a href="{$site_url}themes/change_color_scheme/default/index-page">Default</a></li>
					<li><a href="{$site_url}themes/change_color_scheme/default/mediumturquoise">MediumTurquoise</a></li>
					<li><a href="{$site_url}themes/change_color_scheme/default/lavender">Lavender</a></li>
					<li><a href="{$site_url}themes/change_color_scheme/default/girls">Girls</a></li>
					<li><a href="{$site_url}themes/change_color_scheme/default/jewish">Jewish Singles</a></li>
					<li><a href="{$site_url}themes/change_color_scheme/default/lovers">Hobby</a></li>
					<li><a href="{$site_url}themes/change_color_scheme/default/neighbourhood">Neighbors</a></li>
					<li><a href="{$site_url}themes/change_color_scheme/default/blackonwhite">Black on white</a></li>
					<li><a href="{$site_url}themes/change_color_scheme/default/deepblue">Deep blue</a></li>
					<li><a href="{$site_url}themes/change_color_scheme/default/companions">Companions</a></li>
					<li><a href="{$site_url}themes/change_color_scheme/default/community">Community</a></li>
					<li><a href="{$site_url}themes/change_color_scheme/default/christian">Christian singles</a></li>
				</ul>
			</li>
		</ul>
	</div>
</div>

{literal}
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function()
{ (i[r].q=i[r].q||[]).push(arguments)}

,i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-43414725-1', 'auto',
{'allowLinker': true}

);
ga('require', 'displayfeatures');
ga('require', 'linker');
ga('linker:autoLink', ['payproglobal.com',
'livechatinc.com', 'pilotgroup.zendesk.com',
'paypal.com', 'yandex.ru', 'webmoney.ru', 'qiwi.com', 'mopay.com',
'datingpro.com', 'datingsoftware.ru', 'datingpro.fr', 'pgdatingsoftware.de', 'dating-soft.com',
'realtysoft.pro', 'pgrealestate.ru', 'realestatescript.de', 'realestatescript.es', 'emlakscripti.biz.tr',
'jobsoftpro.com', 'pilotgroup.net'] );
ga('send', 'pageview');

</script>
<script type="text/javascript">
	(function() {
		var load_script = document.createElement('script');
		load_script.type = 'text/javascript'; 
		load_script.async = true; 
		load_script.src = '{/literal}{$site_root}{$img_folder}{literal}demo/jquery.dropdownPlain.js';
		var dp = document.getElementsByTagName('script')[0];
		dp.parentNode.insertBefore(load_script, dp);
	})();
	
	var __lc = {};
	__lc.license = 1083102;
	(function() { var lc = document.createElement('script'); lc.type = 'text/javascript'; lc.async = true; lc.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'cdn.livechatinc.com/tracking.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(lc, s); })();
 
	  /* kissmetrics */
	var _kmq = _kmq || [];
	 var _kmk = _kmk || '6d00fd30f5b61692ebe3ddeb42986d90ff05a6e7';
	 function _kms(u){
	 setTimeout(function()
	{ var d = document, f = d.getElementsByTagName('script')[0], s = d.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = u; f.parentNode.insertBefore(s, f); } 
	, 1);
	 }
	 _kms('//i.kissmetrics.com/i.js');
	 _kms('//doug1izaerwt3.cloudfront.net/' + _kmk + '.1.js');
</script>
{/literal}