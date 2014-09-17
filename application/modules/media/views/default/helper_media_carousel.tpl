{strip}
{if $media_carousel_data.media}
	{if $media_carousel_data.header}<h2>{$media_carousel_data.header}</h2>{/if}

	<script type="text/javascript">{literal}
		$(function(){
			loadScripts(
				["{/literal}{js file='jquery.jcarousel.min.js' return='path'}{literal}", "{/literal}{js file='init_carousel_controls.js' return='path'}{literal}"],
				function(){
					var data = {/literal}{json_encode data=$media_carousel_data.carousel}{literal};
					$('#media_carousel_'+data.rand).removeClass('hide');
					
					carousel{/literal}{$media_carousel_data.rand}{literal} = $('#media_carousel_'+data.rand).find('.jcarousel').jcarousel({
						animation: 250
					});

					carousel_controls{/literal}{$media_carousel_data.rand}{literal} = new init_carousel_controls({
						carousel: carousel{/literal}{$media_carousel_data.rand}{literal},
						carousel_images_count: data.visible,
						carousel_total_images: data.users_count,
						btnNext: '#directionright_'+data.rand,
						btnPrev: '#directionleft_'+data.rand,
						scroll: data.scroll
					});
				},
				['carousel_controls{/literal}{$media_carousel_data.rand}{literal}', 'carousel{/literal}{$media_carousel_data.rand}{literal}']
			);
		});
	</script>{/literal}
	
	{assign var=media_carousel_thumb_name value=$media_carousel_data.carousel.thumb_name}
	<div id="media_carousel_{$media_carousel_data.rand}" class="carousel-wrapper hide{if $media_carousel_data.carousel.class} {$media_carousel_data.carousel.class}{/if}">
		<div id="directionleft_{$media_carousel_data.rand}" class="direction left hover-icon">
			<div class="icon-arrow-left icon-big edge hover w"></div>
		</div>
		<div class="dimp100 box-sizing plr50">
			<div class="jcarousel" dir="{$_LANG.rtl}">
				<ul>
					{foreach from=$media_carousel_data.media item=item}
						<li>
							<div class="user">
								<div class="photo">
									{if $item.video_content}<div class="overlay-icon pointer" data-click="view-media" data-id-media="{$item.id}" data-place="site_gallery"><i class="icon-play-sign w icon-4x opacity60"></i></div>{/if}
									<img class="pointer" data-click="view-media" data-id-media="{$item.id}" data-place="site_gallery" src="{if $item.media}{$item.media.mediafile.thumbs[$media_carousel_thumb_name]}{elseif $item.video_content}{$item.video_content.thumbs[$media_carousel_thumb_name]}{/if}" />
								</div>
							</div>
							<div class="descr hide">
								<div><a href="{seolink module='users' method='view' data=$item.user_info}">{$item.user_info.output_name}</a>, {$item.user_info.age}</div>
								{if $item.user_info.location}<div>{$item.user_info.location}</div>{/if}
							</div>
						</li>
					{/foreach}
				</ul>
			</div>
		</div>
		<div id="directionright_{$media_carousel_data.rand}" class="direction right hover-icon">
			<div class="icon-arrow-right icon-big edge hover w"></div>
		</div>
	</div>
{/if}
{/strip}