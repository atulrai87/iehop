if(typeof MultiRequest !== 'undefined'){
	MultiRequest.initAction({
		gid: 'users_lists_request',
		params: {module: 'users_lists', model: 'Users_lists_model', method: 'get_request_notifications'},
		paramsFunc: function(){return {};},
		callback: function(resp){
			if(resp && resp.length){
				for(var i in resp){
					var options = {
						title: resp[i].title,
						text: resp[i].text,
						image: resp[i].user_icon,
						image_link: resp[i].user_link,
						sticky: true,
						time: 15000
					};
					notifications.show(options);
				}
			}
		},
		period: 12,
		status: 0
	});
	MultiRequest.initAction({
		gid: 'users_lists_accept',
		params: {module: 'users_lists', model: 'Users_lists_model', method: 'get_accept_notifications'},
		paramsFunc: function(){return {}},
		callback: function(resp){
			if(resp && resp.length){
				for(var i in resp){
					var req = resp[i];
					var gritter_id = $.gritter.add({
						title: req.title,
						text: req.text,
						image: req.user_icon,
						image_link: req.user_link,
						sticky: false,
						time: 15000
					});
					$('#gritter-item-'+gritter_id).on('click', 'a', function(){
						$.gritter.remove(gritter_id);
					});
				}
			}
		},
		period: 12,
		status: 0
	});
	
	if(id_user){
		MultiRequest.enableAction('users_lists_request').enableAction('users_lists_accept');
	}
	$(document).on('users:login', function(){
		MultiRequest.enableAction('users_lists_request').enableAction('users_lists_accept');
	}).on('users:logout, session:guest', function(){
		MultiRequest.disableAction('users_lists_request').disableAction('users_lists_accept');
	});
}
