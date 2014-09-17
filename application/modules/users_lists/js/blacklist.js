function blacklist(optionArr){
	this.properties = {
		siteUrl: '/',
		blacklistUserButton: '#user_delete',
		blacklistUserUrl: 'users_lists/ajax_add_blacklist',
		userId: 0,
	};
	
	var _self = this;

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		_self.init_objects();
		_self.init_controls();
	};

	this.uninit = function(){
		_self.blacklist_user_btn.off('click');
	}
	
	this.init_objects = function(){
		_self.blacklist_user_btn = $(_self.properties.blacklistUserButton);
	}
	
	this.init_controls = function(){
		_self.blacklist_user_btn.off('click').on('click', function(){
			_self.blacklist_user();
		})
	}
	
	this.blacklist_user = function(){
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.blacklistUserUrl + '/' + _self.properties.userId,
			type: 'GET',
			dataType : 'json',
			cache: false,
			success: function(data){
				if(data.error){
					error_object.show_error_block(data.error, 'error');
				} else{
					error_object.show_error_block(data.success, 'success');
					_self.blacklist_user_btn.parent().hide().next().hide();
				}
			}
		});
	}
	
	_self.Init(optionArr);
}
