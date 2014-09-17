{include file="header.tpl" load_type='ui'}
	<div class="content-block">

		<h1>{seotag tag='header_text'}</h1>
		<p class="header-comment">{l i='text_register' gid='users'}</p>
		<div class="edit_block">
			<form action="" method="post" enctype="multipart/form-data">
				<div class="r">
					<div class="f">{l i='field_user_type' gid='users'}:</div>
					<div class="v">
						<select name="user_type">
							{foreach item=item key=key from=$user_types.option}<option value="{$key}"{if $key eq $data.user_type} selected{/if}>{$item}</option>{/foreach}
						</select>
					</div>
				</div>
				<div class="r">
					<div class="f">{l i='field_looking_user_type' gid='users'}:</div>
					<div class="v">
						<select name="looking_user_type">
							{foreach item=item key=key from=$user_types.option}<option value="{$key}"{if $key eq $data.looking_user_type || (!$data.looking_user_type && $key == 2)} selected{/if}>{$item}</option>{/foreach}
						</select>
					</div>
				</div>
				<div class="r">
					<div class="f">{l i='field_email' gid='users'}: </div>
					<div class="v"><input type="text" name="email" value="{$data.email|escape}"></div>
				</div>
				<div class="r">
					<div class="f">{l i='field_nickname' gid='users'}: </div>
					<div class="v"><input type="text" name="nickname" value="{$data.nickname|escape}"></div>
				</div>
				<div class="r">
					<div class="f">{l i='field_password' gid='users'}: </div>
					<div class="v"><input type="password" name="password" value="{$data.password}"></div>
				</div>
				<div class="r">
					<div class="f">{l i='field_repassword' gid='users'}: </div>
					<div class="v"><input type="password" name="repassword"></div>
				</div>
				<div class="r">
					<div class="f">{l i='birth_date' gid='users'}: </div>
					<div class="v"><input type='text' value='{$data.birth_date}' name="birth_date" id="datepicker" maxlength="10"></div>
				</div>
				{helper func_name=get_user_subscriptions_form module=subscriptions func_param=register}
				<br>
				<div class="r">
					<div class="f">{l i='field_captcha' gid='users'}: </div>
					<div class="v captcha">{$data.captcha_image} <input type="text" name="captcha_confirmation" value="" maxlength="{$data.captcha_word_length}" /></div>
				</div>
				<div class="r">
					<div class="v">
						<input id="confirmation" type='checkbox' value='1' name="confirmation" {if $data.confirmation}checked {/if}/>
						<label for="confirmation">{l i='field_confirmation' gid='users'}.</label>
						{depends module=content}&nbsp;<a href="{seolink module='content' method='view'}legal-terms">{l i='terms_and_conditions' gid='users'}</a>{/depends}
						<span class="pginfo msg confirmation"></span>
					</div>
				</div>
				<div class="r">
					<div class="f">&nbsp;</div>
					<div class="v"><input type="submit" value="{l i='btn_register' gid='start' type='button'}" name="btn_register"></div>
				</div>
			</form>

			<script type='text/javascript'>{literal}
				$(function(){
					var date_now = new Date();
					var date_min = new Date(date_now.getYear() - 80, 0, 1);
					var date_max = new Date(date_now.getYear() - 18, 0, 1);
					var yr =  (date_min.getFullYear()) + ':' + (date_max.getFullYear());
					$( "#datepicker" ).datepicker({
						dateFormat :'yy-mm-dd',
						changeYear: true,
						changeMonth: true,
						yearRange: yr,
						defaultDate: date_max
					});
				});
			</script>{/literal}
	</div>

		{block name=show_social_networks_like module=social_networking}
		{block name=show_social_networks_share module=social_networking}
		{block name=show_social_networks_comments module=social_networking}
	</div>
{include file="footer.tpl"}
