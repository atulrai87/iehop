function ListsLinks(optionArr){
	if(!ListsLinks.instance){
		ListsLinks.instance = this;
	}else if(ListsLinks.instance.properties.singleton){
		return ListsLinks.instance;
	}
	
	this.properties = {
		id_dest_user: '',
		siteUrl: '',
		singleton: 1,
		url: '',
		request_window: null
	};

	var _self = this;

	this.Init = function(options){
		_self.properties = $.extend(true, _self.properties, options);
		
		_self.properties.request_window = new loadingContent({
			loadBlockWidth: '300px',
			linkerObjID: 'users_lists_links_'+_self.properties.id_dest_user,
			loadBlockLeftType: 'center',
			loadBlockTopType: 'center',
			closeBtnClass: 'w'
		});


		$(document).on('click', '#users_lists_links_'+_self.properties.id_dest_user+' a', function(){
			var method = $(this).attr('method');
			(method === 'request') ? _self.friendsRequest(method) : _self.ajaxAction(method);
		}).on('click', '#users_lists_links_request_'+_self.properties.id_dest_user+' .button input[type="button"]', function(e){
			_self.properties.request_window.hide_load_block();
			_self.ajaxAction('request', $('#users_lists_links_request_'+_self.properties.id_dest_user).find('.text').find('textarea').val());
		}).on('keypress keyup change blur', '#users_lists_links_request_'+_self.properties.id_dest_user+' .text textarea', function(e){
			_self.countChars(this);
		});
	};
	
	this.uninit = function(){
		$(document)
			.off('click', '#users_lists_links_'+_self.properties.id_dest_user+' a')
			.off('click', '#users_lists_links_request_'+_self.properties.id_dest_user+' .button input[type="button"]')
			.off('keypress keyup change blur', 'users_lists_links_request_'+_self.properties.id_dest_user+' .text textarea');
		_self.properties.request_window.destroy();
		
		ListsLinks.instance = undefined;
	};
	
	this.ajaxAction = function(method, comment){
		comment = comment || '';
		$.ajax({
			url: _self.properties.siteUrl+_self.properties.url+'ajax_'+method+'/'+_self.properties.id_dest_user,
			data: {comment: comment},
			success: function(resp){
				if(resp.html){
					$('#users_lists_links_'+_self.properties.id_dest_user).html(resp.html);
				}
				console.log(resp);
			},
			type: 'POST',
			dataType: 'json',
			async: false
		});
		return this;
	};
	
	this.friendsRequest = function(method){
		$.ajax({
			url: _self.properties.siteUrl+_self.properties.url+'ajax_request_block/'+_self.properties.id_dest_user,
			success: function(data){
				_self.properties.request_window.show_load_block(data);
			},
			dataType: 'html',
			type: 'POST'
		});
		return this;
	};

	this.countChars = function(obj){
		var msg_length = $(obj).val().length;
		var max_count = parseInt($(obj).attr('maxcount'));
		if(msg_length > max_count) {
			$(obj).val($(obj).val().substring(0, max_count)).scrollTop(1000);
		}
		msg_length = $(obj).val().length;
		$(obj).parents('.popup-form').find('.char-counter').html(max_count - msg_length);
		return this;
	};

	
	_self.Init(optionArr);
	
	return _self;
}