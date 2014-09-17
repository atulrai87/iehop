function jobCategorySelect(optionArr){ 
	this.properties = {
		siteUrl: '',
		rand: '', 
		categories: [],
		categories_all: [],
		load_form: 'properties/ajax_get_category_form/',
		load_data: 'properties/ajax_get_category_data/',
		current_category: [],
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
				
		max: 5,
		level: 2,
		output: 'max',

		errors: {
			max_items_reached: 'Max items count is reached'
		},
		
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

		if(_self.properties.level == 1){
			//// set action onclick on first level category
			$('#'+_self.properties.id_items+' li').off('click').on('click', function(){
				_self.set_value($(this).attr('index'), 'toggle');
				return false;
			});
			
		}else{
			//// set toggle onclick on first level
			$('#'+_self.properties.id_items+' li.sup').off('click').on('click', function(){
				_self.toggle_sub($(this).attr('index'));
				return false;
			});
		
			//// set action onclick on next level category
			$('#'+_self.properties.id_items+' li.sub').off('click').on('click', function(){
				_self.set_value($(this).attr('index'), 'toggle');
				return false;
			});
		}
		
		//_self.update_selected_count(0);		
	}

	this.open_form = function(){
		var url =  _self.properties.siteUrl+_self.properties.load_form + _self.properties.max;

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
				
				_self.update_selected_count();
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
					
					if(_self.properties.level == 1){
						$('#'+_self.properties.id_items).append('<li index="'+data.categories[id].id+'">'+data.categories[id].name+'</li>');
					}else	if(data.categories[id].parent == '0'){
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
				}else {
					_self.set_defaults();
				}
			}
		});
	}

	this.set_defaults = function(){
		if(_self.properties.level > 1){
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
		}else{
			for(var id in _self.properties.categories){
				if(_self.properties.categories[id] == 1 && _self.properties.raw_data[id] && _self.properties.raw_data[id].parent != '0'){
					_self.unset_value(id);
				}
			}
		}
	}
	
	this.set_value = function(value, set_type){
		/// если уже установлено - то unset_value и return
		if(set_type == 'toggle' && $('#sel_'+_self.properties.rand+'_'+value).length > 0){
			_self.unset_value(value);
			return;
		}
		
		/// если max = 1 то unset все в categories
		if(_self.properties.max == 1){
			for(var id in _self.properties.categories){
				_self.unset_value(id);
			}
		}
		
		/// проверяем на возможность выбора
		var sum = _self.get_all_sum();
		if(_self.properties.max - sum < 1 && _self.properties.categories[value] != 1){
			return;
		}
		
		/// устанавливаем поле hidden
		if(_self.properties.max == 1 && _self.properties.level == 1)
			var hidden_name = _self.properties.hidden_name;
		else
			var hidden_name = _self.properties.hidden_name+'[]';

		var parentID = _self.properties.raw_data[value].parent;
		
		if(!$('#sel_'+_self.properties.rand+'_'+value).length){
			$('#'+_self.properties.id_main).append('<input type="hidden" name="'+hidden_name+'" value="'+value+'" id="sel_'+_self.properties.rand+'_'+value+'" >');
			$('#sel_'+_self.properties.rand+'_'+value).change();
		}

		if(parentID != '0' && !$('#sel_'+_self.properties.rand+'_'+parentID).length){
			$('#'+_self.properties.id_main).append('<input type="hidden" name="'+hidden_name+'" value="'+parentID+'" id="sel_'+_self.properties.rand+'_'+parentID+'" >');
			$('#sel_'+_self.properties.rand+'_'+parentID).change();
		}
		
		/// добавляем в категории
		_self.properties.categories[value] = 1;
		if(parentID != '0'){
			_self.properties.categories[parentID] = 1;
		}

		/// помечаем как выделенный
		$('#'+_self.properties.id_items+' li[index='+value+']').addClass('selected');
		
		_self.update_selected_count(0);
		
		/// если есть надкатегория то апдейтим количество выбранных 
		if(parentID != '0'){
			_self.update_selected_count(parentID);
		}
		
		return false;
	}
	
	this.unset_value = function(value){
		
		/// снимаем поле hidden
		$('#sel_'+_self.properties.rand+'_'+value).remove();
		
		/// удаляем из категорий
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
			var sum = _self.get_all_sum();
			
			if(_self.properties.max == '1'){
				var hdr = $('#'+_self.properties.id_items+' li.selected:first').text();
				$('#'+_self.properties.id_span).html(hdr);
			}else{
				$('#'+_self.properties.id_span).html(sum);
			}
			$('#'+_self.properties.id_left).html(_self.properties.max - sum);
		
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
				
				///// удаляем из hidden
				if($('#sel_'+_self.properties.rand+'_'+value).length){
					$('#sel_'+_self.properties.rand+'_'+value).remove();
				}
				
				/// удаляем из категорий
				_self.properties.categories[value] = 0;
				
			}
		}
	}	
	
	this.get_all_sum = function(){
		var isParent = false;
		var sum = 0;

		for(id in _self.properties.categories){ 
			if(_self.properties.categories[id] != 1) continue;
			isParent = (_self.properties.raw_data[id].parent == '0')? true : false;
			
			if((_self.properties.level == 1 && isParent) || (_self.properties.level > 1 && !isParent)){
				sum += 1;
			}
		}
		return sum;
	}
		
	this.sum = function(arr){
		var sum=0;
		for(i in arr){ sum += parseInt(arr[i]);}
		return sum;
	}
	_self.Init(optionArr);
}


