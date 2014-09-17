'use strict';

angular.module('datingMobile').controller('LoginCtrl', function ($rootScope, $scope, Init, Layout, appSettings, Api, appHistory) {

	$rootScope.dependOnLang(function() {
		$rootScope.actions = {
			text: $rootScope.l('login')
		};
	});
	$rootScope.leftBtn = {
		class: 'fa fa-arrow-left',
		click: function(){
			appHistory.goBack('start');
		}
	};

	$scope.form = {
		email: '',
		password: ''
	};

	$scope.restoreForm = {
		email: ''
	};

	$scope.showRestoreForm = false;

	$scope.toggleRestoreForm = function() {
		$scope.showRestoreForm = !$scope.showRestoreForm;
		if($scope.showRestoreForm) {
			$rootScope.leftBtn.click = function() {
				$scope.toggleRestoreForm();
			};
		} else {
			$rootScope.leftBtn.click = function(){
				appHistory.goBack('start');
			};
		}
	};

	$scope.isSaveDisabled = function() {
		return $scope.form.$invalid;
	};

	$scope.restore = function() {
		Api.query({module: 'users', method: 'restore'}, $scope.restoreForm).then(function(resp){
			Layout.addAlert('info', resp.messages, true);
			$scope.toggleRestoreForm();
		}, function(resp) {
			Layout.addAlert('danger', resp.errors, true);
		});
	};

	$scope.login = function(){
		Api.updateToken($scope.form.email, $scope.form.password).then(function(resp){
			Layout.removeAlerts(true);
			Init.setUp(true).then(function() {
				appHistory.goBack('main');
			}, function(resp) {
				console.log(resp);
			});
		}, function(err){
			Layout.addAlert('danger', err, true);
		});
	};

	$scope.logOff = function(){
		Api.query({module: 'users', method: 'logout'}).then(function(resp){
			Api.setToken(resp.data.token);
			Layout.removeAlerts(true);
			appSettings.save(false, 'userData');
			Init.setUp(true).then(function() {
				appHistory.goBack('start');
			});
		}, function(resp) {
			Layout.addAlert('danger', resp.errors, true);
		});
	};

});