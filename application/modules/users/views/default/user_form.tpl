{strip}
<div class="edit_block">
	<form method="post" enctype="multipart/form-data">
		{if $action eq 'personal'}
			{if !$not_editable_fields.user_type}
				<div class="r">
					<div class="f">{l i='field_user_type' gid='users'}:</div>
					<div class="v">
						<select name="user_type">
							{foreach item=item key=key from=$user_types.option}<option value="{$key}"{if $key eq $data.user_type || (!$data.user_type && $key == 2)} selected{/if}>{$item}</option>{/foreach}
						</select>
					</div>
				</div>
			{/if}
			{if !$not_editable_fields.looking_user_type}
				<div class="r">
					<div class="f">{l i='field_looking_user_type' gid='users'}:</div>
					<div class="v">
						<select name="looking_user_type">
							{foreach item=item key=key from=$user_types.option}<option value="{$key}"{if $key eq $data.looking_user_type || (!$data.looking_user_type && $key == 2)} selected{/if}>{$item}</option>{/foreach}
						</select>
					</div>
				</div>
			{/if}
			{if !$not_editable_fields.age_min && !$not_editable_fields.age_max}
				<div class="r">
					<div class="f">{l i='field_partner_age' gid='users'}: </div>
					<div class="v">
						{if !$not_editable_fields.age_min}
							{l i='from' gid='users'}&nbsp;
							<select name="age_min" class="short">
								{foreach from=$age_range item=age}
									<option value="{$age}"{if $age == $data.age_min} selected{/if}>{$age}</option>
								{/foreach}
							</select>&nbsp;
						{/if}
						{if !$not_editable_fields.age_max}
							{l i='to' gid='users'}&nbsp;
							<select name="age_max" class="short">
								{foreach from=$age_range item=age}
									<option value="{$age}"{if $age == $data.age_max} selected{/if}>{$age}</option>
								{/foreach}
							</select>
						{/if}
					</div>
				</div>
			{/if}
			{if !$not_editable_fields.nickname}
				<div class="r">
					<div class="f">{l i='field_nickname' gid='users'}: </div>
					<div class="v"><input type="text" name="nickname" value="{$data.nickname|escape}"></div>
				</div>
			{/if}
			{if !$not_editable_fields.fname}
				<div class="r">
					<div class="f">{l i='field_fname' gid='users'}: </div>
					<div class="v"><input type="text" name="fname" value="{$data.fname|escape}"></div>
				</div>
			{/if}
			{if !$not_editable_fields.sname}
				<div class="r">
					<div class="f">{l i='field_sname' gid='users'}: </div>
					<div class="v"><input type="text" name="sname" value="{$data.sname|escape}"></div>
				</div>
			{/if}
			<div class="r">
				<div class="f">{l i='field_icon' gid='users'}: </div>
				<div class="v">
					<input type="file" name="user_icon">
					{if $data.user_logo || $data.user_logo_moderation}
						<br><input type="checkbox" name="user_icon_delete" value="1" id="uichb"><label for="uichb">{l i='field_icon_delete' gid='users'}</label><br>
						{if $data.user_logo_moderation}<img src="{$data.media.user_logo_moderation.thumbs.middle}">{else}<img src="{$data.media.user_logo.thumbs.middle}">{/if}
					{/if}
				</div>
			</div>
			{if !$not_editable_fields.birth_date}
				<div class="r">
					<div class="f">{l i='birth_date' gid='users'}: </div>
					<div class="v"><input type='text' value='{$data.birth_date}' name="birth_date" id="datepicker" maxlength="10"></div>
				</div>
			{/if}
			<div class="r">
				<div class="f">{l i='field_region' gid='users'}: </div>
				<div class="v">{country_select select_type='city' id_country=$data.id_country id_region=$data.id_region id_city=$data.id_city}</div>
			</div>
		{else}
			{/strip}{include file="custom_form_fields.tpl" module="users"}{strip}
		{/if}

		<div class="r">
			<div class="f">&nbsp;</div>
			<div class="v"><input type="submit" value="{if $edit_mode}{l i='btn_save' gid='start' type='button'}{else}{l i='btn_register' gid='start' type='button'}{/if}" name="btn_register"></div>
		</div>
	</form>
	{/strip}
	
	<script type='text/javascript'>{literal}
		$(function(){
			now = new Date();
			yr =  (new Date(now.getYear() - 80, 0, 1).getFullYear()) + ':' + (new Date(now.getYear() - 18, 0, 1).getFullYear());
			$( "#datepicker" ).datepicker({
				dateFormat :'yy-mm-dd',
				changeYear: true,
				changeMonth: true,
				yearRange: yr
			});
		});
	</script>{/literal}
</div>