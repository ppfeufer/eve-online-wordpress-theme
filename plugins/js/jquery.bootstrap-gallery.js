/**
 * Bootstrap Gallery Plugin
 */

(function($) {
	'use strict';

	$.fn.bootstrapGallery = function(options) {
		var settings = $.extend({}, $.fn.bootstrapGallery.defaults, options);
		var id = generateId();
		var classesString = settings.classes;
		var classesArray = classesString.split(' ');
		var clicked = {};

		function getCurrentUl() {
			return 'ul[data-bsp-ul-id="' + clicked.ulId + '"][data-bsp-ul-index="' + clicked.ulIndex + '"]';
		}

		function generateId() {
			//http://fiznool.com/blog/2014/11/16/short-id-generation-in-javascript/
			var ALPHABET = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			var ID_LENGTH = 4;
			var out = '';

			for(var i = 0; i < ID_LENGTH; i++) {
				out += ALPHABET.charAt(Math.floor(Math.random() * ALPHABET.length));
			}

			return 'bsp-' + out;
		}

		function createModalWrap() {
			if($('#bootstrapGalleryModal-' + id).length !== 0) {
				return false;
			}

			var modal = '';
				modal += '<div class="modal fade modal-bootstrap-photo-gallery" id="bootstrapGalleryModal-' + id + '" tabindex="-1" role="dialog"';
				modal += 'aria-labelledby="myModalLabel" aria-hidden="true">';
				modal += '<div class="modal-dialog"><div class="modal-content">';
				modal += '<div class="modal-body"></div></div></div></div>';

			$('body').append(modal);
		}

		function showHideControls() {
			var total = $(getCurrentUl() + ' li[data-bsp-li-index]').length;

			if(total === clicked.nextImg) {
				$('a.next').hide();
			} else {
				$('a.next').show();
			}

			if(clicked.prevImg === -1) {
				$('a.previous').hide();
			} else {
				$('a.previous').show();
			}
		}

		function closeModal() {
			$('#bootstrapGalleryModal-' + id).modal('hide');
		}

		function showModal() {
//			var src = $(this).find('img').attr('src');
			var src = $(this).find('figure').attr('data-fullsizeimage');

			// fix for lazy loading ...
//			var lazySource = $(this).find('img').attr('data-lazy-src') || '';
//			if(lazySource !== '') {
//				src = lazySource;
//			}

			var largeImg = $(this).find('img').attr('data-bsp-large-src');
			var caption = $(this).find('figcaption').text();

			if(typeof largeImg === 'string') {
				src = largeImg;
			}

			var index = $(this).attr('data-bsp-li-index');
			var ulIndex = $(this).parent('ul').attr('data-bsp-ul-index');
			var ulId = $(this).parent('ul').attr('data-bsp-ul-id');

			clicked.img = src;
			clicked.caption = caption;
			clicked.prevImg = parseInt(index) - parseInt(1);
			clicked.nextImg = parseInt(index) + parseInt(1);
			clicked.ulIndex = ulIndex;
			clicked.ulId = ulId;

			$('#bootstrapGalleryModal-' + id).modal();

			var img = '<img src="' + clicked.img + '" class="img-responsive"/>';
			var html = '<figure>';
				html += img;

				if(clicked.caption !== '') {
					html += '<figcaption>';
					html += clicked.caption;
					html += '<a href="' + clicked.img + '" title="Open Image in a new Window" class="modal-gallery open-modal-image link-external" target="_blank"><span class="glyphicon glyphicon-link"></span></a>';
					html += '</figcaption>';
				}

			html += '</figure>';
				html += '<span class="glyphicon glyphicon-remove-circle modal-close-icon"></span>';
				html += '<div class="modal-navigation clearfix">';
				html += '<a class="btn btn-default controls next pull-right" data-bsp-id="' + clicked.ulId + '" href="' + (clicked.nextImg) + '">next &raquo;</a>';
				html += '<a class="btn btn-primary controls previous pull-left" data-bsp-id="' + clicked.ulId + '" href="' + (clicked.prevImg) + '">&laquo; prev</a>';
				html += '</div>';

			$('#bootstrapGalleryModal-' + id + ' .modal-body').html(html);
			$('.glyphicon-remove-circle').on('click', closeModal);

			showHideControls();
		}

		function nextPrevHandler() {
			var ul = $(getCurrentUl());
			var index = $(this).attr('href');

//			var src = ul.find('li[data-bsp-li-index="' + index + '"] img').attr('src');
			var src = ul.find('li[data-bsp-li-index="' + index + '"] figure').attr('data-fullsizeimage');

			// fix for lazy loading ...
//			var lazySource = ul.find('li[data-bsp-li-index="' + index + '"] img').attr('data-lazy-src') || '';
//			if(lazySource !== '') {
//				src = lazySource;
//			}

			var largeImg = ul.find('li[data-bsp-li-index="' + index + '"] img').attr('data-bsp-large-src');

			if(typeof largeImg === 'string') {
				src = largeImg;
			}

			$('.modal-body img').attr('src', src);

			// precessing caption and downloadlink
			var caption = ul.find('li[data-bsp-li-index="' + index + '"] figcaption').text();
			$('.modal-body figcaption').remove();
			if(caption !== '') {
				$('.modal-body figure').append('<figcaption>' + caption + '<a href="' + src + '" title="Open Image in a new Window" class="modal-gallery open-modal-image link-external" target="_blank"><span class="glyphicon glyphicon-link"></span></a></figcaption>');
			}

			clicked.prevImg = parseInt(index) - 1;
			clicked.nextImg = parseInt(clicked.prevImg) + 2;

			if($(this).hasClass('previous')) {
				$(this).attr('href', clicked.prevImg);
				$('a.next').attr('href', clicked.nextImg);
			} else {
				$(this).attr('href', clicked.nextImg);
				$('a.previous').attr('href', clicked.prevImg);
			}

			// console.log(clicked);
			showHideControls();

			return false;
		}

		function clearModalContent() {
			$('#bootstrapGalleryModal-' + id + ' .modal-body').html('');

			clicked = {};
		}

		function insertClearFix(el, x) {
			var index = (x + 1);

			$.each(classesArray, function (e) {
				switch (classesArray[e]) {
					//large
					case 'col-lg-1':
						if($(el).next('li.clearfix').length === 0) {
							$(el).after('<li class="clearfix visible-lg-block"></li>');
						}
						break;

					case 'col-lg-2':
						if(index % 6 === 0) {
							$(el).after('<li class="clearfix visible-lg-block"></li>');
						}
						break;

					case 'col-lg-3':
						if(index % 4 === 0) {
							$(el).after('<li class="clearfix visible-lg-block"></li>');
						}
						break;

					case 'col-lg-4':
						if(index % 3 === 0) {
							$(el).after('<li class="clearfix visible-lg-block"></li>');
						}
						break;

					case 'col-lg-5':
					case 'col-lg-6':
						if(index % 2 === 0) {
							$(el).after('<li class="clearfix visible-lg-block"></li>');
						}
						break;

					//medium
					case 'col-md-1':
						if($(el).next('li.clearfix').length === 0) {
							$(el).after('<li class="clearfix visible-md-block"></li>');
						}
						break;

					case 'col-md-2':
						if(index % 6 === 0) {
							$(el).after('<li class="clearfix visible-md-block"></li>');
						}
						break;

					case 'col-md-3':
						if(index % 4 === 0) {
							$(el).after('<li class="clearfix visible-md-block"></li>');
						}
						break;

					case 'col-md-4':
						if(index % 3 === 0) {
							$(el).after('<li class="clearfix visible-md-block"></li>');
						}
						break;

					case 'col-md-5':
					case 'col-md-6':
						if(index % 2 === 0) {
							$(el).after('<li class="clearfix visible-md-block"></li>');
						}
						break;

					//small
					case 'col-sm-1':
						if($(el).next('li.clearfix').length === 0) {
							$(el).after('<li class="clearfix visible-sm-block"></li>');
						}
						break;

					case 'col-sm-2':
						if(index % 6 === 0) {
							$(el).after('<li class="clearfix visible-sm-block"></li>');
						}
						break;

					case 'col-sm-3':
						if(index % 4 === 0) {
							$(el).after('<li class="clearfix visible-sm-block"></li>');
						}
						break;

					case 'col-sm-4':
						if(index % 3 === 0) {
							$(el).after('<li class="clearfix visible-sm-block"></li>');
						}
						break;

					case 'col-sm-5':
					case 'col-sm-6':
						if(index % 2 === 0) {
							$(el).after('<li class="clearfix visible-sm-block"></li>');
						}
						break;

					//x-small
					case 'col-xs-1':
						if($(el).next('li.clearfix').length === 0) {
							$(el).after('<li class="clearfix visible-xs-block"></li>');
						}
						break;

					case 'col-xs-2':
						if(index % 6 === 0) {
							$(el).after('<li class="clearfix visible-xs-block"></li>');
						}
						break;

					case 'col-xs-3':
						if(index % 4 === 0) {
							$(el).after('<li class="clearfix visible-xs-block"></li>');
						}
						break;

					case 'col-xs-4':
						if(index % 3 === 0) {
							$(el).after('<li class="clearfix visible-xs-block"></li>');
						}
						break;

					case 'col-xs-5':
					case 'col-xs-6':
						if(index % 2 === 0) {
							$(el).after('<li class="clearfix visible-xs-block"></li>');
						}
						break;
				}
			});
		}

		this.each(function(i) {
			//ul
			var items = $(this).find('li');

			$(this).attr('data-bsp-ul-id', id);
			$(this).attr('data-bsp-ul-index', i);

			items.each(function (x) {
				insertClearFix(this, x);

				$(this).addClass(classesString);
				$(this).attr('data-bsp-li-index', x);
				$(this).find('img').addClass('img-responsive');

				if(settings.hasModal === true) {
					$(this).addClass('bootstrap-gallery-modal');
					$(this).on('click', showModal);
				}
			});
		});

		if(settings.hasModal === true) {
			//this is for the next / previous buttons
			$(document).on('click', 'a.controls[data-bsp-id="' + id + '"]', nextPrevHandler);
			$(document).on('hidden.bs.modal', '#bootstrapGalleryModal-' + id, clearModalContent);
			//start init methods

			createModalWrap();
		}

		return this;
	};

	/*defaults*/
	$.fn.bootstrapGallery.defaults = {
		'classes': 'col-lg-2 col-md-2 col-sm-3 col-xs-4',
		'hasModal': true
	};
}(jQuery));