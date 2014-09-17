function seoUrlCreator(optionArr){
	this.properties = {
		siteUrl: '',
		urlID: 'url_block',
		data: {},
		temp_data: {},
		fixedClass: 'fixed',
		textClass: 'text',
		tplClass: 'tpl',
		optClass: 'opt',
		editBlockTextID: 'url_text_edit',
		editBlockTplID: 'url_tpl_edit',
		hiddenID: 'url_data',
		showForm: true
	}

	var _self = this;

	this.errors = {
	}

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		_self.create_url();
	}

	this.create_url = function(){
		$('#'+_self.properties.urlID).append('<li class="'+_self.properties.fixedClass+'">'+_self.properties.siteUrl+'</li>');

		$('#'+_self.properties.urlID).sortable({
			items: 'li.sortable',
			forcePlaceholderSize: true,
			placeholder: 'limiter',
			revert: true,
			update: function(event, ui) { 
				_self.refresh_data();
			},
			start: function( event, ui ){
				_self.properties.showForm = false;
			},
			stop: function( event, ui ){
				_self.properties.showForm = true;
			}
		});
				
		for(var part in _self.properties.data){
			_self.save_li(0, _self.properties.data[part]);
		}
			
	//	$('#'+_self.properties.urlID + '> li.sortable').bind('click', function(){
	//		var id = $(this).attr('id');
	//		_self.show_form($(this));
	//	});

		_self.serialize_data();
	}
	
	this.save_li = function(id, data){
		var liClass = liVarname = liValue = liOutput = liVartype = '';
		var liType = data.type;
		var liNum = data.var_num;

		if(data.type == 'text'){
			liClass = _self.properties.textClass;
			liValue = liOutput = data.value;
		}else if(data.type == 'tpl'){
			liClass = _self.properties.tplClass;
			liVarname = data.var_name;
			liVartype = data.var_type;
			liValue = data.var_default;
			liOutput = '['+liVarname+'|'+liValue+']';
		}else{
			liClass = _self.properties.optClass;
			liVarname = data.var_name;
			liVartype = data.var_type;
			liValue = data.var_default;
			liOutput = '['+liVarname+'|'+liValue+']';
		}
		if(!id){
			id = 'li'+ $('#'+_self.properties.urlID+' > li').length;
			$('#'+_self.properties.urlID).append('<li id="'+id+'" class="sortable '+liClass+'" li-type="'+liType+'" li-num="'+liNum+'" li-varname="'+liVarname+'" li-value="'+liValue+'" li-vartype="'+liVartype+'">'+liOutput+'</li>');
			$('#'+id).bind('click', function(){
				if($('#'+id).find('input').length > 0 || !_self.properties.showForm) return;
				_self.show_form($(this).attr('id'));
			});
			$('#'+_self.properties.urlID).sortable('refresh');
		}else{
			$('#'+id).attr({
				'li-value': liValue
			}).text(liOutput);
		}
	}
	
	this.remove_li = function(id){
		$('#'+id).remove();
		$('#'+_self.properties.urlID).sortable('refresh');
	}
	
	this.refresh_data = function(){

		_self.properties.data = [];
		$('#'+_self.properties.urlID + '> li.sortable').each(function(i){
			var liType = $(this).attr('li-type');
			if(liType == 'text'){
				_self.properties.data.push({
					type: 'text',
					value: $(this).attr('li-value'),
					var_num: '',
					var_name: '',
					var_type: '',
					var_default: ''
				});
			}else if(liType == 'tpl'){
				_self.properties.data.push({
					type: 'tpl',
					value: '',
					var_num: $(this).attr('li-num'),
					var_name: $(this).attr('li-varname'),
					var_type: $(this).attr('li-vartype'),
					var_default: $(this).attr('li-value')
				});
			}else if(liType == 'opt'){
				_self.properties.data.push({
					type: 'opt',
					value: '',
					var_num: $(this).attr('li-num'),
					var_name: $(this).attr('li-varname'),
					var_type: $(this).attr('li-vartype'),
					var_default: $(this).attr('li-value')
				});
			}			
		});
		_self.serialize_data();
	}

	this.clear_url = function(){
		$('#'+_self.properties.urlID + '>li').each(function(){
			$(this).remove();
		});
	}
	
	this.show_form = function(id){
		var item = $('#'+id);
		var type = item.attr('li-type');
		var value = item.attr('li-value');
		if(value.length <= 0) value='empty';

		if(type == 'text'){
			var output = '<input type="text" name="value" value="'+value+'">';
		}else{
			var output = '['+item.attr('li-varname')+'|<input type="text" name="value" value="'+value+'">]';
		}
		output = output + '<a href="#" class="icn-confirm"></a> <a href="#" class="icn-delete"></a>';
		item.html(output);
		item.find('a.icn-confirm').bind('click', function(){
			_self.save_form(id);
			return false;	
		});
		item.find('a.icn-delete').bind('click', function(){
			_self.delete_form(id);
			return false;	
		});
		$('#'+_self.properties.urlID).sortable('disable');
	}
	
	this.cancel_form = function(id){
		var item = $('#'+id);
		var type = item.attr('li-type');
		var value = item.attr('li-value');
		if(value.length <= 0) value='empty';

		if(type == 'text'){
			var output = value;
		}else{
			var output = '['+item.attr('li-varname')+'|'+value+']';
		}		
		item.html(output);
		_self.refresh_data();
		$('#'+_self.properties.urlID).sortable('enable');
	}
	
	this.save_form = function(id){
		var item = $('#'+id);
		var ret = _self.save_block(id, item.attr('li-type'), item.find('input').val());
		if(ret){
			_self.cancel_form(id);
		}
	}
	
	this.delete_form = function(id){
		var index = 0;
		_self.remove_li(id);
		_self.refresh_data();
		$('#'+_self.properties.urlID).sortable('enable');
	}

	this.save_block = function(id, type, text, var_count, var_type, var_name){
		if(type == 'text'){
			if(text.length <= 0) return false;
			var data = {
				type: 'text',
				value: text,
				var_num: '',
				var_name: '',
				var_type: '',
				var_default: ''
			}
		}else if(type == 'tpl'){
			if(text.length <= 0) text='empty';
			var data = {
				type: 'tpl',
				value: '',
				var_num: var_count,
				var_name: var_name,
				var_type: var_type,
				var_default: text
			}
		}else if(type == 'opt'){
			if(text.length <= 0) text='empty';
			var data = {
				type: 'opt',
				value: '',
				var_num: var_count,
				var_name: var_name,
				var_type: var_type,
				var_default: text
			}
		}
		_self.save_li(id, data);
		_self.refresh_data();
		return true;
	}

	this.serialize_data = function(){
		var ret = '{';
		var index = 0;
		for(var id in _self.properties.data ){
			ret += '"'+index+'":{';
			ret += '"type":"'+_self.properties.data[id].type+'",';
			ret += '"value":"'+_self.properties.data[id].value+'",';
			ret += '"var_num":"'+_self.properties.data[id].var_num+'",';
			ret += '"var_name":"'+_self.properties.data[id].var_name+'",';
			ret += '"var_type":"'+_self.properties.data[id].var_type+'",';
			ret += '"var_default":"'+_self.properties.data[id].var_default+'"';
			ret += '},';
			index++;
		}
		if(ret.length>1)
			ret = ret.substring(0, ret.length-1);
		ret += "}";
		$('#'+_self.properties.hiddenID).val(ret);
	}

	_self.Init(optionArr);
}