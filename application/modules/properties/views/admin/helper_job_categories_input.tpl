{literal}
    <style>
	* {
	    vertical-align: top;
	}
	.job-category-box {
	    border: 1px solid #006599;
	    width: 240px;
	    min-height: 30px;
	    border-image: initial;
	}
	.job-category_select_item {
	    line-height: 2;
	    height: 29px;
	    cursor: pointer;
	    padding: 0 0 0 5px;
	}
	.job-category_select_item:hover {
	    background-color: #C8DEF4;
	}
	.without_top_border {
	    border-top: 0px;
	}
	.job-category-box input[type="text"] {
	    line-height: 2;
	    width: AUTO;
	    height: 29px;
	    padding: 0 0 0 5px;
	    border: 0;
	    border-image: initial;
	}
	
	.job-category-box input[type="submit"] {
	    background: url({/literal}{$site_root}{literal}/application/views/default/default/img/keywords-loupe.png) 7px 7px no-repeat !important;
	    width: 30px;
	    height: 30px;
	    border: 0;
	    border-image: initial;
	    cursor: pointer;
	}
	.job-category-box input[type="submit"]:hover {
	    background: url({/literal}{$site_root}{literal}/application/views/default/default/img/keywords-loupe.png) 7px -18px no-repeat !important;
	}
    </style>
{/literal}

<div class="job-category-box">
    <input type="text" name="job_category_name" id="job_category_text_{$job_categories_helper_data.rand}">
    <input type="submit" id="job_category_open_{$job_category_helper_data.rand}" name="submit" value="">
	 <input type="hidden" name="{$country_helper_data.var_city_name}" id="city_hidden_{$country_helper_data.rand}" value="{$country_helper_data.city.id}">
</div>
<div class="job-category-box without_top_border" id="job_category_select_{$job_categories_helper_data.rand}" style="display: none;">

</div>