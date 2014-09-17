'use strict';

angular.module('datingMobile')
	.factory('Init', function($rootScope, $q, appSettings, Helpers, Api, backend, appHistory, Layout) {
		var _self = this;
		var _now = new Date().getTime();
		_self.initMenu = function() {
			if ($rootScope.apd.isLogged) {
				$rootScope.mainMenu = {
					items: [
						{href: 'main', icon: 'fa-th-large', text: $rootScope.l('page_home')},
						{href: 'search', icon: 'fa-search', text: $rootScope.l('page_search')},
						{href: 'profile', icon: 'fa-user', text: $rootScope.l('page_my_profile')},
						{href: 'im', icon: 'fa-comments', text: $rootScope.l('page_im'), indicator: 'new_messages'},
						{href: 'friends', icon: 'fa-users', text: $rootScope.l('page_friends'), indicator: 'new_friends'},
						{href: 'services/my', icon: 'fa-file-text-o', text: $rootScope.l('page_account')},
						{href: 'services', icon: 'fa-file-text-o', text: $rootScope.l('page_services')},
						{href: 'settings', icon: 'fa-cog', text: $rootScope.l('page_settings')}
					]
				};
			} else {
				$rootScope.mainMenu = {
					items: [
						{href: 'start', icon: 'fa-th-large', text: $rootScope.l('page_index')},
						{href: 'search', icon: 'fa-search', text: $rootScope.l('page_search')},
						{href: 'settings', icon: 'fa-cog', text: $rootScope.l('page_settings')}
					]
				};
			}

			// TODO: Перенести в Layout
			Layout.setActiveMenuItem();
			$rootScope.$on('$routeChangeStart', function() {
				// Сбрасываем верхнюю панель
				$rootScope.leftBtn = false;
				$rootScope.rightBtn = false;

				Layout.enableSideMenu();
				Layout.hideSideMenu();
				Layout.setActiveMenuItem();
				Layout.removeAlerts();
				Layout.removeTopMessage();
			});
		};

		_self.initBackend = function() {
			// Обновление индикаторов
			backend.reset();
			if ($rootScope.apd.isLogged) {
				backend.add({
					name: 'indicators',
					data: {
						module: 'mobile',
						model: 'mobile_model',
						method: 'get_indicators',
						indicators: [
							'new_messages',
							'new_friends'
						]
					},
					callback: function(resp) {
						$rootScope.indicators = resp;
					}
				});
			}
			backend.start();
		};

		_self.initSettings = function(force) {
			force = force || false;
			var _settings = {
				l: appSettings.get('l'),
				lang: appSettings.get('lang'),
				langs: appSettings.get('langs'),
				data: appSettings.get('data'),
				userData: appSettings.get('userData'),
				isLogged: 'false' !== appSettings.get('isLogged'),
				time: parseInt(appSettings.get('time'))
			};
			$rootScope.apd = _settings;

			// Чтобы если обновляются ланги, все об этом знали
			$rootScope.$watch('apd.l', function(newVal, oldVal) {
				if(newVal === oldVal) {
					return false;
				};
				$rootScope.$broadcast('langsUpdated');
			});

			var _deferred = $q.defer();

			var _updateSettings = force || Helpers.isObjEmpty(_settings.l) ||
				Helpers.isObjEmpty(_settings.data) ||
				Helpers.isObjEmpty(_settings.userData) ||
				_settings.time.isNaN ||
				(_settings.time + 3 * 1000 < _now);
			if (_updateSettings) {
				var langId;
				if (appSettings.get('lang')) {
					langId = appSettings.get('lang').id;
				}
				Api.query({module: 'mobile', method: 'init', uri: langId}, {}).then(function(resp) {
					angular.extend(_settings, resp.data);
					_settings.isLogged = false !== _settings.userData;
					appSettings.save(_settings.l, 'l');
					appSettings.save(_settings.properties, 'properties');
					_settings.data.cssUrl += 'mobile-ltr.css';
					appSettings.save(_settings.data, 'data');
					appSettings.save(_settings.userData, 'userData');
					appSettings.save(_settings.language, 'lang');
					appSettings.save(_settings.languages, 'langs');
					appSettings.save(_settings.isLogged, 'isLogged');
					appSettings.save(_now, 'time');
					$rootScope.apd = _settings;
					_deferred.resolve(_settings);
				}, function() {
					console.log('initSettings error');
				});
			} else {
				$rootScope.apd = _settings;
				_deferred.resolve(_settings);
			}
			return _deferred.promise;
		};

		_self.checkIm = function() {
			if ($rootScope.apd.isLogged) {
				$rootScope.imDisabled = false;
				Api.query({module: 'im', method: 'get_im_status'}).then(function(resp) {
					if(undefined === resp.data) {
						console.error('checkIm error');
						return false;
					}
					$rootScope.imDisabled = !!!resp.data.im_on;
				}, function() {
					$rootScope.imDisabled = true;
				});
			}
		};

		_self.setUp = function(force) {
			var deferred = $q.defer();
			_self.initSettings(force).then(function(settings) {
				_self.checkIm();
				_self.initBackend();
				_self.initMenu();
				appHistory.init();
				deferred.resolve(settings);
			});
			return deferred.promise;
		};

		return _self;
	});