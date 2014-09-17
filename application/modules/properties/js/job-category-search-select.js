function jobCategorySearchSelect(optionArr){ 
	this.properties = {
		siteUrl: '',
		rand: '', 
		categories: [],
		load_form: 'properties/ajax_get_category_search_form/',
		load_data: 'properties/ajax_get_category_data/',
		id_main: '',
		id_span: '',
		id_open: '',
		id_hidden_category: '',
		id_items: 'category_select_items',
		id_back: 'category_select_back',
		id_close: 'category_close_link',
		id_left: 'category_max_left_block',
		hidden_name: 'id_category',
		raw_data: [],
		
		contentObj: new loadingContent({loadBlockWidth: '680px', closeBtnPadding: 15})
	}
	var _self = this;

	this.errors = {
	}

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		_self.properties.id_main = 'category_select_'+_self.properties.rand;
		_self.properties.id_span = 'category_text_'+_self.properties.rand;
		_self.properties.id_open = 'category_open_'+_self.properties.rand;
		_self.properties.id_hidden_category = 'category_hidden_'+_self.properties.rand;

		$('#'+_self.properties.id_open).unbind().bind('click', function(){
			_self.open_form();
			return false;
		});
		
		$('#'+_self.properties.id_back).off('click').on('click', function(){
			_self.clear_selection();
			return false;
		});
		
		$('#'+_self.properties.id_items+' li.sup .handle').off('click').on('click', function(){
			_self.toggle_sub($(this).parent().attr('index'));
			return false;
		});
		
		$('#'+_self.properties.id_items+' li.sup .itm').off('click').on('click', function(){
			_self.set_value($(this).parent().attr('index'), 'toggle');
			return false;
		});
		
		$('#'+_self.properties.id_items+' li.sub').off('click').on('click', function(){
			_self.set_value($(this).attr('index'), 'toggle');
			return false;
		});
		
		_self.update_selected_count(0);		
	}

	this.open_form = function(){
		
		var url =  _self.properties.siteUrl+_self.properties.load_form;

		$.ajax({
			url: url, 
			cache: false,
			success: function(data){
				_self.properties.contentObj.show_load_block(data);

				_self.load_categories(0);

				$('#'+_self.properties.id_close).unbind().bind('click', function(){
					_self.properties.contentObj.hide_load_block();
					return false;
				});

			}
		});
		
	}

	this.load_categories = function(variable){
		$.ajax({
			url: _self.properties.siteUrl+_self.properties.load_data + variable,
			dataType: 'json',
			cache: false,
			success: function(data){
				
				if(variable){
					$('#'+_self.properties.id_items + ' li[index='+variable+'] ul').remove();
					$('#'+_self.properties.id_items + ' li[index='+variable+']').append('<ul class="hide"></ul>');
				}
				
				for(var id in data.categories ){
					//// save in raw
					_self.properties.raw_data[data.categories[id].id] = data.categories[id];
					
					if(data.categories[id].parent == '0'){
						$('#'+_self.properties.id_items).append('<li class="sup" index="'+data.categories[id].id+'"><div class="handle i-c"></div><div class="itm">'+data.categories[id].name+'<span></span></div></li>');
					}else{
						$('#'+_self.properties.id_items + ' li[index='+variable+'] ul').append('<li class="sub" index="'+data.categories[id].id+'">'+data.categories[id].name+'</li>');
					}
					if(_self.properties.categories[data.categories[id].id] == 1){
						_self.set_value(data.categories[id].id, 'no-toggle');
					}
				}

				if(variable){
					_self.open_sub(variable);	
				}else{
					_self.set_defaults();
				}
			}
		});
	}
	
	this.set_defaults = function(){
		var parents = [];
		for(var id in _self.properties.categories){
			if(_self.properties.categories[id] == 1 && _self.properties.raw_data[id] && _self.properties.raw_data[id].parent != '0'){
				parents[parseInt(_self.properties.raw_data[id].parent)] = 1;
			}
		}
		
		for(var parentId in parents){
			if(parents[parentId] == 1 ){
				_self.load_categories(parentId);	
			}
		}
	}
	
	this.set_value = function(value, set_type){
		/// если уже установлено - то unset_value и return
		if(set_type == 'toggle' && $('#sel_'+_self.properties.rand+'_'+value).length > 0){
			_self.unset_value(value);
			return;
		}
		
		/// устанавливаем поле hidden
		if(!$('#sel_'+_self.properties.rand+'_'+value).length){
			$('#'+_self.properties.id_main).append('<input type="hidden" name="'+_self.properties.hidden_name+'[]" value="'+value+'" id="sel_'+_self.properties.rand+'_'+value+'" >');
			$('#sel_'+_self.properties.rand+'_'+value).change();
		}
		
		/// если есть надкатегория то делаем unset_value сап категории, если есть подкатегории то  unset_value их
		if(_self.properties.raw_data[value].parent){
			if(_self.properties.raw_data[value].parent != '0'){
				_self.unset_value(_self.properties.raw_data[value].parent);
			}else{
				$('#'+_self.properties.id_items + ' li[index='+value+'] ul li').each(function(){
					_self.unset_value($(this).attr('index'));
				});
			}
		}
		
		/// добавляем в категории
		_self.properties.categories[value] = 1;

		/// помечаем как выделенный
		$('#'+_self.properties.id_items+' li[index='+value+']').addClass('selected');
		
		_self.update_selected_count(0);
		
		/// если есть надкатегория то апдейтим количество выбранных 
		if(_self.properties.raw_data[value].parent != '0'){
			_self.update_selected_count(_self.properties.raw_data[value].parent);
		}
		
		return false;
	}
	
	this.unset_value = function(value){
		
		/// снимаем поле hidden
		$('#sel_'+_self.properties.rand+'_'+value).remove();
		
		/// удаляем из категории
		_self.properties.categories[value] = 0;

		/// снимаем выделение
		$('#'+_self.properties.id_items+' li[index='+value+']').removeClass('selected');
		
		_self.update_selected_count(0);

		/// если есть надкатегория то апдейтим количество выбранных 
		if(_self.properties.raw_data[value].parent != '0'){
			_self.update_selected_count(_self.properties.raw_data[value].parent);
		}
		
		return false;
	}
	
	this.toggle_sub = function(value){
		/// если надо открыть:
		if($('#'+_self.properties.id_items+' li[index='+value+'] .handle').hasClass('i-c')){
			if($('#'+_self.properties.id_items+' li[index='+value+'] ul > li').length){
				/// 	если подкаталог уже не пуст просто открываем
				_self.open_sub(value);
			}else{
				/// 	если подкаталог еще пуст - грузим подкатегории и затем открываем
				_self.load_categories(value);
			}
			
		}else{
			
			/// если надо закрыть: просто сварачиваем
			$('#'+_self.properties.id_items+' li[index='+value+'] .handle').removeClass('i-o').addClass('i-c');
			$('#'+_self.properties.id_items+' li[index='+value+'] ul').slideUp();
		}

		return false;
	}
	
	this.open_sub = function(value){
		$('#'+_self.properties.id_items+' li[index='+value+'] .handle').removeClass('i-c').addClass('i-o');
		$('#'+_self.properties.id_items+' li[index='+value+'] ul').slideDown();
	}
	
	this.clear_selection = function(){
		for(i in _self.properties.categories){
			if(_self.properties.categories[i] == 1){
				_self.unset_value(i);
			}
		}
	}

	this.update_selected_count = function(value){
		if(!value || value == 0){
			$('#'+_self.properties.id_span).html(_self.sum(_self.properties.categories));
		}else{
			var selected_for_category = 0;
			$('#'+_self.properties.id_items+' li[index='+value+'] ul li').each(function(){
				var val = $(this).attr('index');
				if(_self.properties.categories[val] == 1){
					selected_for_category++;
				}
			});
			if(selected_for_category > 0){
				$('#'+_self.properties.id_items+' li[index='+value+'] span').html(' ('+selected_for_category+')');
				$('#'+_self.properties.id_items+' li[index='+value+']').addClass('sub-selected');
			}else{
				$('#'+_self.properties.id_items+' li[index='+value+'] span').html('');
				$('#'+_self.properties.id_items+' li[index='+value+']').removeClass('sub-selected');
			}
		}
	}
	
	this.sum = function(arr){
		var sum=0;
		for(i in arr){ sum += parseInt(arr[i]);}
		return sum;
	}
	_self.Init(optionArr);
}


