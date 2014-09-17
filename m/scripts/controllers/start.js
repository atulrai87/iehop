'use strict';

angular.module('datingMobile').controller('StartCtrl', function ($rootScope, appSettings){
	// Главная страница до авторизации

	$rootScope.dependOnLang(function() {
		$rootScope.actions = {
			text: $rootScope.l('start')
		};
	});

	$rootScope.rightBtn = {
		icon: 'fa fa-search',
		href: 'search'
	};

	if('true' === appSettings.get('isLogged')) {
		$rootScope.go('main');
	}

});