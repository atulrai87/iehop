{include file="header.tpl" load_type='ui'}
<div class="content-block">
<div class="view_events">
   	
   	
   	 {foreach item=item key=key from=$testlight}
   	 	  
		<li>
		{ $item.event_name }
		</li>
		{/foreach}
   	 
   	 
    
	
</div>
</div>
{include file="footer.tpl"}