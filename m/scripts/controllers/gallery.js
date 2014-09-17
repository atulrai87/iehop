'use strict';

angular.module('datingMobile').controller('GalleryCtrl', function($rootScope, $routeSegment, $scope, $window, $timeout, Api, Data, appHistory, appSettings, Layout) {
	$rootScope.actions = {};

	$rootScope.leftBtn = {
		class: 'fa fa-arrow-left',
		click: function() {
			appHistory.goBack('profile/' + ($scope.userId || ''));
		}
	};

	// Если это собственная галерея, данные пользователя берутся из appSettings
	if (parseInt($routeSegment.$routeParams.userId)) {
		$scope.userId = $routeSegment.$routeParams.userId;
		Api.query({module: 'users', method: 'get'}, {id: $scope.userId}).then(function(resp) {
			$scope.user = resp.data;
		});
		$scope.canEdit = false;
	} else {
		$scope.user = appSettings.get('userData');
		$scope.userId = $scope.user.id;
		$scope.canEdit = true;
	}

	var updateHeader = function(pos) {
		$rootScope.actions.text = $rootScope.l('header_photo') + ' '
				+ $rootScope.l('n_of_m').replace('[n]', pos).replace('[m]', $scope.photos.length);
	};

	$scope.itemNum = parseInt($routeSegment.$routeParams.itemId);

	$scope.setList = function() {
		$rootScope.actions = {
			items: $scope.canEdit ? [{
					text: $rootScope.l('gallery_btn_add'),
					click: function() {
						var frmImg = $window.document.getElementById('frm-img');
						$timeout(function() {
							frmImg.click();
						});
					}
				}] : null,
			text: $rootScope.l('page_gallery')
		};
		Layout.enableSideMenu();
		$scope.carousel = false;
		$rootScope.rightBtn = {
			class: 'fa fa-play',
			click: function() {
				$scope.setSlider();
			}
		};
	};

	$scope.setSlider = function(index) {
		Layout.disableSideMenu();
		index = index || 0;
		if ($scope.photos && index < $scope.photos.length) {
			$scope.slideIndex = index;
			updateHeader(index + 1);
		}

		$scope.carousel = true;
		var listBtn = {
			class: 'fa fa-th',
			click: function() {
				$scope.setList();
			}
		};
		$rootScope.rightBtn = listBtn;

		$scope.fileSelect = function(file) {
			Data.gallery = {
				multiUpload: file[0]
			};
			$rootScope.go('gallery/edit/');
		};

		if ($scope.canEdit) {
			var setActions = function() {
				var deleteMedia = function() {
					var imgId = $scope.photos[$scope.slideIndex].id;
					Api.query({module: 'media', method: 'delete_media'}, {id: imgId}).then(function(resp) {
						Layout.addAlert('info', resp.messages);
						$scope.photos.splice($scope.slideIndex, 1);
						if (0 === $scope.photos.length) {
							$rootScope.go('gallery/edit', false);
							return;
						} else {
							updateHeader($scope.slideIndex + 1);
						}
						// Переключаемся на предыдущую фотографию
						if ($scope.slideIndex > 0) {
							$scope.slideIndex--;
						}
					}, function(resp) {
						Layout.addAlert('danger', resp.errors);
					});
				};
				$rootScope.actions.items = [{
					text: $rootScope.l('gallery_btn_add'),
					click: function() {
						var frmImg = $window.document.getElementById('frm-img');
						$timeout(function() {
							frmImg.click();
						});
					}
				}];

				if ($scope.photos.length > 0) {
					$rootScope.actions.items = $rootScope.actions.items.concat([{
							text: $rootScope.l('gallery_btn_edit'),
							click: function() {
								$rootScope.go('gallery/edit/' + $scope.photos[$scope.slideIndex].id);
							}
						}, {
							text: $rootScope.l('gallery_btn_delete'),
							click: function() {
								Layout.confimDelete(deleteMedia);
							}
						}]);
				}
			};
			setActions();
		}
	};

	$scope.slideIndex = 0;
	$scope.$watch('slideIndex', function(newVal, oldVal) {
		if (newVal === oldVal) {
			return false;
		}
		if ($scope.slideIndex > 0) {
			Layout.disableSideMenu();
		} else {
			Layout.enableSideMenu();
		}
		updateHeader($scope.slideIndex + 1);
	});

	$scope.carousel = true;
	$scope.photos = [];

	var data = {
		user_id: $scope.userId,
		param: 'all',
		page: 1,
		album_id: 0,
		direction: 'desc',
		gallery_name: 'mediagallery',
		order: 'date_add',
		place: 'user_gallery'
	};

	Api.query({module: 'media', method: 'get_list'}, data).then(function(resp) {
		$scope.photos = resp.data.media;
		if ($scope.photos.length) {
			$scope.setSlider($scope.itemNum - 1);
		} else {
			if (!$scope.canEdit) {
				Layout.addAlert('info', $rootScope.l('gallery_no_photos'));
				appHistory.goBack('profile/' + ($scope.userId || ''));
			} else {
				Layout.addAlert('info', $rootScope.l('gallery_add_photo'), true);
				$rootScope.go('gallery/edit', false);
			}
		}
	});

}).controller('GalleryEditCtrl', function($rootScope, $scope, $routeSegment, $q, Api, Data, appHistory, Layout) {

	$scope.imgId = parseInt($routeSegment.$routeParams.itemId);
	$scope.isSaveDisabled = false;
	$rootScope.leftBtn = {
		class: 'fa fa-arrow-left',
		click: function() {
			appHistory.goBack('profile');
		}
	};

	$scope.form = {
		description: '',
		permissions: {}
	};

	$scope.fileSelect = function(file) {
		$scope.form.multiUpload = file[0];
	};

	var getItem = function() {
		var data = {
			media_id: $scope.imgId,
			without_position: true
		};

		Api.query({module: 'media', method: 'get_media_content'}, data).then(function(resp) {
			$scope.form.permissions = resp.data.media.permissions;
			$scope.form.description = resp.data.media.description;
			$scope.preview = resp.data.media.media.mediafile.thumbs.middle;
		}, function(resp) {
			Layout.addAlert('danger', resp.errors);
		});
	};

	if ($scope.imgId) {
		// Передан айдишник — редактирование
		$rootScope.actions = {
			text: $rootScope.l('page_gallery_edit_photo')
		};
		getItem($scope.imgId);
	} else {
		// Не передан — добавление.
		$rootScope.actions = {
			text: $rootScope.l('page_gallery_add_photo')
		};
		// Инпут с выбором картинки может быть на другой странице
		if (Data.gallery) {
			$scope.form.multiUpload = Data.gallery.multiUpload;
		}
	}

	var upload = function() {
		var dfd = $q.defer();
		var data = {
			multiUpload: $scope.form.multiUpload,
			id_album: '',
			permissions: $scope.form.permissions,
			description: $scope.form.description
		};
		Api.query({module: 'media', method: 'save_image'}, data, 'multiUpload', data.multiUpload).then(function(resp) {
			if(Data.gallery) {
				delete Data.gallery;
			}
			$rootScope.go('gallery', false);
			dfd.resolve();
		}, function(resp) {
			Layout.addAlert('danger', resp.errors);
			$scope.isSaveDisabled = false;
			dfd.reject();
		});
		return dfd.promise;
	};

	var saveDescription = function() {
		var dfd = $q.defer();
		var data = {
			id: $scope.imgId,
			description: $scope.form.description
		};
		Api.query({module: 'media', method: 'save_description'}, data).then(function(resp) {
			dfd.resolve();
		}, function(resp) {
			Layout.addAlert('danger', resp.errors);
			$scope.isSaveDisabled = true;
			dfd.reject();
		});
		return dfd.promise;
	};

	var savePermissions = function() {
		var data = {
			photo_id: $scope.imgId,
			permissions: $scope.form.permissions
		};
		Api.query({module: 'media', method: 'save_permissions'}, data).then(function() {
		}, function(resp) {
			Layout.addAlert('danger', resp.errors);
			$scope.isSaveDisabled = true;
		});
	};

	var getPermissionsList = function() {
		Api.query({module: 'media', method: 'get_permissions_list'}).then(function(resp) {
			$scope.accesses = resp.data.option;
		});
	};
	getPermissionsList();

	$scope.submit = function() {
		$scope.isSaveDisabled = true;
		if ($scope.imgId) {
			// Редактирование существующей
			// TODO:
			// 1) Проверять, менялось ли что-то на странице дабы не отправлять лишних запросов
			// 2) Сохранять одним запросом
			saveDescription();
			savePermissions();
			appHistory.goBack('gallery');
		} else {
			// Загрузка новой
			upload();
		}
	};

});