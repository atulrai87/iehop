{if $ga_default_account_id}
{literal}
<script type="text/javascript">
 
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '{/literal}{$ga_default_account_id}{literal}']);
  _gaq.push(['_trackPageview']);
 
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
 
</script>
{/literal}
{/if}
{if $tracker_code}{$tracker_code}{/if}
