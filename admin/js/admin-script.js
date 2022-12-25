jQuery(document).ready(function($) {
	var admin = null,
		self = null,
		map = $('#mapplic-admin-map'),
		codeeditor = null;

	map.on('mapstart', function(e, s) {
		self = s;
		admin = new MapplicAdmin().init();
	});

	// Media buttons
	$(document).on('click', '.media-button', function(e) {
		e.preventDefault();

		var button = this;
		var media_popup = wp.media({
			title: 'Select or Upload File',
			button: { text: 'Select' },
			multiple: false
		});

		media_popup.on('select', function() {
			var attachment = media_popup.state().get('selection').first().toJSON();
			$(button).closest('div').find('.input-text').val(attachment.url);
		}).open();
	});

	// New map type select
	$('#mapplic-new-type').on('change', function() {
		if (this.value != 'custom') $('#mapplic-mapfile').hide();
		else $('#mapplic-mapfile').show();
	});

	// load Mapplic
	map.mapplic({
		locations: true,
		sidebar: false,
		hovertip: false,
		search: true,
		minimap: true,
		slide: 0
	});
	self = map.data('mapplic');

	// admin class
	function MapplicAdmin() {
		
		this.init = function() {
			var s = this;

			this.controls();
			if (self) this.overwriteMethods();

			if (self.data.bottomLat && self.data.leftLng && self.data.topLat && self.data.rightLng) $('.landmark-geolocation').show();
			s.dragControls();

			map.on('mapready', function(e) {
				// interactive elements number
				$('#interactive-elements-number').text($('[id^=MLOC] > *[id], [id^=landmark] > *[id]', self.map).length);
			
				s.sidebarSort();
			});

			return this;
		}

		this.overwriteMethods = function() {
			self.showLocation = function(id, duration, check) {
				var location = self.location = self.l[id];
				if (!location) return false;

				self.el.trigger('locationopen', location);
			}

			self.addMarker = function(location) {
				var level = $('.mapplic-layer[data-floor="' + location.level + '"]', self.el);
				var marker = $('<a></a>').attr('href', '#').addClass('mapplic-pin').addClass(location.pin).css({'top': (location.y * 100) + '%', 'left': (location.x * 100) + '%'}).appendTo(level);
				marker.on('click touchend', function(e) {
					if (e.cancelable) e.preventDefault();
					self.showLocation(location.id, 600);
				});

				if (location.label) {
					if (location.label.match(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g)) marker.css('background-image', 'url(' + location.label + ')');
					else $('<span><span>' + location.label + '</span></span>').appendTo(marker);
				}
				if (location.reveal) marker.attr('data-reveal', location.reveal).css('visibility', 'hidden');
				if (location.category) {
					location.category = location.category.toString();
					marker.attr('data-category', location.category);
				}
				marker.attr('data-location', location.id);

				if (self.o.zoom && self.o.mousewheel) marker.on('mousewheel DOMMouseScroll', self.mouseWheel);
				if (location.styled) marker.addClass(location.styled);
				if (location.color && location.pin.indexOf('pin-text') > -1) marker.css('color', location.color);
				else if (location.color) marker.css({'background-color': location.color, 'border-color': location.color });
				
				if (location.id != 'init') location.el = marker;
				return marker;
			}
		}

		this.controls = function() {
			var s = this;

			// click on interactive element
			map.on('svgloaded', function(e, svg, id) {
				// svg element and floor id returned
				$(self.o.selector, svg).on('click touchend', function() {
					if (!self.dragging) {
						var id = $(this).attr('id'),
							location = self.l[id];
						
						if (location) {
							if ($('#landmark-settings .save-landmark:visible').val() == mapplic_localization.add) $('#landmark-settings .id-input').val(id);
							s.updateLocationPanel(location);
							$('.selected-pin').removeClass('selected-pin');
							$('.mapplic-pin[data-location="' + id + '"]', self.map).addClass('selected-pin');
						}
						else if (confirm('There\'s no location associated with "' + id + '" yet.\nWould you like to create a new one?')) s.clearLocationPanel(id);
					}
				});
			});

			// Sortable lists
			$('.sortable-list').sortable({
				placeholder: 'list-item-placeholder',
				forcePlaceholderSize: true,
				handle: '.list-item-handle'
			});

			$(document).on('keyup', '.title-input', function(e) {
				var text = $(this).val();
				if (text === '') text = 'undefined';

				$(this).closest('.list-item').find('.menu-item-title').text(text);
			});

			$(document).on('click', '.list-item:not(.predefined) .list-item-handle', function(e) {
				e.preventDefault();
				$(this).closest('.list-item').find('.list-item-settings').slideToggle(200);
				$(this).find('.menu-item-toggle').toggleClass('opened');
			});

			// Edit mode
			$('#mapplic-editmode').click(function() {
				$('.mapplic-rawedit').toggle();
				$('#mapplic-admin-map').toggle();
				$(this).val(function(i, text) { return text === mapplic_localization.raw ? mapplic_localization.map : mapplic_localization.raw; });
			});

			// Indentation
			$('#mapplic-indent').change(function() {
				var ischecked = $(this).is(':checked'),
					object = JSON.parse($('#mapplic-mapdata').val());
				if (ischecked) $('#mapplic-mapdata').val(JSON.stringify(object, null, 4));
				else $('#mapplic-mapdata').val(JSON.stringify(object));
			});
			
			// Color pickers
			s.initColorPicker('.mapplic-color-picker', 'body', false);
			s.initColorPicker('.mapplic-alpha-color-picker', 'body', true);

			// Code Editor
			codeeditor = wp.codeEditor.initialize($('#mapplic-customcss'), cm_settings);

			// Item actions
			$(document).on('click', '.item-cancel', function(e) {
				e.preventDefault();
				$(this).closest('.list-item-settings').slideToggle(200);
			});

			$(document).on('click', '.item-delete', function(e) {
				e.preventDefault();
				if (confirm('Are you sure you want to delete the selected item?')) {
					$(this).closest('.list-item').remove();
				}
			});

			// Floors
			$('#new-floor').click(function() {
				$('#floor-list .new-item').clone().removeClass('new-item').appendTo('#floor-list').find('.list-item-settings').slideDown(200);
			});

			// Styles
			$('#new-style').click(function() {
				var n = $('#style-list .new-item').clone().removeClass('new-item').appendTo('#style-list').find('.list-item-settings').slideDown(200);
				s.initColorPicker('.mapplic-alpha-color-picker-new', n, true);
			});

			// Categories
			$('#new-category').click(function() {
				var n = $('#category-list .new-item').clone().removeClass('new-item').appendTo('#category-list').find('.list-item-settings').slideDown(200);
				s.initColorPicker('.mapplic-color-picker-new', n, false);
			});

			// Pin switcher
			$('#pins-input > div').click(function() {
				$('#pins-input .selected').removeClass('selected');
				$(this).addClass('selected');

				// Show label field only when it's available
				if ($('.mapplic-pin', this).hasClass('pin-label')) $('#landmark-settings .label-input').show();
				else $('#landmark-settings .label-input').hide();

				var selected = $('.selected-pin');
				if (selected.length) {

					var data = self.l[$('.selected-pin').data('location')];
						pin = $('.mapplic-pin', this).data('pin');

					selected.attr('class', 'mapplic-pin selected-pin ' + pin);
					data.pin = pin;
				}
			});

			// Settings panel
			$('.help-toggle').mousedown(function(e) { e.preventDefault(); });
			$('.help-toggle').click(function() { $(this).parent().next('.help-content').toggle(100); });

			// Setting groups
			$('.settings-toggle').change(function() {
				var group = $('.settings-group[data-group="' + $(this).attr('data-setting') + '"]');
				if (this.checked) {
					$('input', group).prop('disabled', false);
					group.removeClass('disabled');
				}
				else {
					$('input', group).prop('disabled', true);
					group.addClass('disabled');
				}
			}).change();

			// Location actions
			$('#new-landmark').click(function() {
				s.clearLocationPanel();
			});

			$('.save-landmark').click(function() {
				var data = null,
					selected = $('.selected-pin');

				// No id specified
				if (!$('#landmark-settings .id-input').val()) {
					alert(mapplic_localization.missing_id);
					return false;
				}
				
				if (selected.length) {
					// Save existing landmark
					data = self.l[selected.data('location')];
					s.getLocationFields(data);

					$('.selected-pin').removeClass('selected-pin');
					$('#landmark-settings').hide();
				}
				else {
					// Add new landmark
					data = {};
					s.getLocationFields(data);

					data.x = (self.container.el.width() * 0.5 - self.x)/self.contentWidth/self.scale;
					data.y = (self.container.el.height() * 0.5 - self.y)/self.contentHeight/self.scale;

					s.newLocation(data);
					$(this).val(mapplic_localization.save);
				}
			});

			$('.duplicate-landmark').click(function() {
				var original = self.l[$('.selected-pin').data('location')];
					duplicate = jQuery.extend(true, {}, original);

				duplicate.id = prompt('Unique ID of the new landmark:', original.id + '-d');
				$('.selected-pin').removeClass('selected-pin');
				s.newLocation(duplicate);
				$('#landmark-settings .id-input').val(duplicate.id);
			});

			$(document).on('click', '.mapplic-dir-item > a', function() {
				var location = self.l[$(this).parent('.mapplic-dir-item').data('location')];

				self.switchLevel(location.level);
				self.moveTo(location.x, location.y, (location.zoom ? parseFloat(location.zoom)/self.o.maxscale : 1), 600);

				$('.selected-pin').removeClass('selected-pin');
				if (location.el) location.el.addClass('selected-pin');
				s.updateLocationPanel(location);
			});

			$('.delete-landmark').click(s.deleteSelected);
			$(document).keyup(function(e) {
				if(e.keyCode == 46) s.deleteSelected();
			});

			// list item click
			$(document).on('click', '.mapplic-dir-item > a', function() {
				var location = self.l[$(this).parent('.mapplic-dir-item').data('location')];

				self.switchLevel(location.level);
				self.moveTo(location.x, location.y, (location.zoom ? parseFloat(location.zoom)/self.o.maxscale : 1), 600);

				$('.selected-pin').removeClass('selected-pin');
				if (location.el) location.el.addClass('selected-pin');
				s.updateLocationPanel(location);
			});

			// CSV Load
			$('#mapplic-csvload').click(function() {
				var field = $('#mapplic-csvfile');

				if (!field.val()) alert('No CSV file selected.');
				else if (confirm('Are you sure you want to load the CSV file\'s content and remove the link?')) {
					Papa.parse(field.val(), {
						header: true,
						download: true,
						encoding: "UTF-8",
						skipEmptyLines: true,
						complete: function(results, file) {
							$.each(self.data.levels, function(index, level) {
								$.each(results.data, function(index, location) {
									if (!location.level) {
										var elem = $('[id^=MLOC] > *[id="' + location.id + '"], [id^=landmark] > *[id="' + location.id + '"]', self.map);
										if (elem && elem.closest('.mapplic-layer').data('floor')) location.level = elem.closest('.mapplic-layer').data('floor');
										else location.level = level.id;
									}
									if (level.id == location.level) {
										self.data.locations.push(location);
										self.l[location.id] = location;
									}
								});
							});
							field.val('');
							$('input[type=submit]')[0].click();
						}
					});
				}
			});
		}

		this.initColorPicker = function(selector, context, alpha) {
			$(selector, context).each(function() {
				var text = $(this).attr('data-text'),
					palette = ["#e9e9ed","#ffc857","#f24c00","#87b38d","#5688c7","#2b5c8c","#8d6b94","#474747"],
					cp = null;

				if (alpha) {
					$(this).attr('data-palette', palette.join('|'));
					cp = $(this).alphaColorPicker();
				}
				else cp = $(this).wpColorPicker({palettes:palette});

				if (text) cp.parent().parent().prev().find('.wp-color-result-text').html(text);
			});
		}

		this.sidebarSort = function() {
			if (self.sidebar && self.sidebar.dir && !self.o.alphabetic) {
				var oi = null;

				self.sidebar.dir.sortable({
					items: '.mapplic-dir-item',
					placeholder: 'list-item-placeholder',
					forcePlaceholderSize: true
				});

				self.sidebar.dir.on('sortstart', function(e, ui) {
					oi = ui.item.index();
				});

				self.sidebar.dir.on('sortupdate', function(e, ui) {
					var loc = self.l[ui.item.data('location')];
					
					if (self.data && self.data.locations) {
						var temp = self.data.locations[oi];
						self.data.locations[oi] = self.data.locations[ui.item.index()];
						self.data.locations[ui.item.index()] = temp;
					}
				});
			}
		}

		this.dragControls = function() {
			var s = this;

			// Pin drag
			$(document).on('mousedown', '.mapplic-element .mapplic-pin', function(event) {
				var pin = $(this),
					x = (event.pageX - self.map.offset().left) / self.scale,
					y = (event.pageY - self.map.offset().top) / self.scale;
				event.preventDefault();

				$('.selected-pin').removeClass('selected-pin');
				pin.addClass('selected-pin');

				$(document).on('mousemove', function(event) {
					x = (event.pageX - self.map.offset().left) / self.scale,
					y = (event.pageY - self.map.offset().top) / self.scale;

					pin.css({
						left: x + 'px',
						top: y + 'px'
					});
				});

				$(document).on('mouseup', function() {
					$(document).off('mousemove');
					$(document).off('mouseup');

					var location = self.l[pin.data('location')];

					location.x = (x/self.contentWidth).toFixed(4);
					location.y = (y/self.contentHeight).toFixed(4);

					s.updateLocationPanel(location);
				});
			});

			// Pin touch
			$(document).on('touchstart', '.mapplic-element .mapplic-pin', function(event) {
				var pin = $(this),
					x = (event.originalEvent.changedTouches[0].pageX - self.map.offset().left) / self.scale,
					y = (event.originalEvent.changedTouches[0].pageY - self.map.offset().top) / self.scale;

				$('.selected-pin').removeClass('selected-pin');
				pin.addClass('selected-pin');

				$(document).on('touchmove', function(event) {
					x = (event.originalEvent.changedTouches[0].pageX - self.map.offset().left) / self.scale,
					y = (event.originalEvent.changedTouches[0].pageY - self.map.offset().top) / self.scale;

					pin.css({
						left: x + 'px',
						top: y + 'px'
					});
				});

				$(document).on('touchend', function() {
					$(document).off('touchmove');
					$(document).off('touchend');

					var location = self.l[pin.data('location')];
					
					location.x = (x/self.contentWidth).toFixed(4);
					location.y = (y/self.contentHeight).toFixed(4);

					s.updateLocationPanel(location);
				});
			});
		}

		this.clearLocationPanel = function(id) {
			// Remove selection if any
			$('.selected-pin').removeClass('selected-pin');
			// Show empty landmark fields
			$('#landmark-settings').show();
			$('#landmark-settings input[type="text"]').val('');
			$('#landmark-settings .mapplic-landmark-field').val('');
			
			if (typeof id !== 'undefined') $('#landmark-settings .id-input').val(id);

			if ($('#wp-descriptioninput-wrap').hasClass('html-active')) $('#descriptioninput').val('');
			else tinyMCE.get('descriptioninput').setContent('');
			
			$('#landmark-settings .style-select').val('false');
			$('#landmark-settings .category-select').val('false');
			$('#landmark-settings .action-select').val('default');
			$('#landmark-settings .hide-input').prop('checked', false);
			// Change button text
			$('.save-landmark').val(mapplic_localization.add);
			$('.duplicate-landmark').hide();
		}

		this.updateLocationPanel = function(location) {
			$('#landmark-settings .title-input').val(location.title);
			$('#landmark-settings .id-input').val(location.id);
			$('#landmark-settings .post-input').val(location.post);
			
			if ($('#wp-descriptioninput-wrap').hasClass('html-active')) $('#descriptioninput').val(location.description);
			else {
				if (location.description) tinymce.get('descriptioninput').setContent(location.description);
				else tinymce.get('descriptioninput').setContent('');
			}

			$('#landmark-settings .landmark-lat').val(location.lat);
			$('#landmark-settings .landmark-lng').val(location.lng);

			$('#pins-input .selected').removeClass('selected');
			if (location.pin) {
				$('#pins-input div[data-pin="' + location.pin + '"]').parent('div').addClass('selected');
				// Show label field only when it's available
				if (location.pin.indexOf('pin-label') > -1) $('#landmark-settings .label-input').show();
				else $('#landmark-settings .label-input').hide();
			}
			
			$('#landmark-settings .label-input').val(location.label);
			$('#landmark-settings .link-input').val(location.link);

			$('#landmark-settings .fill-input').val(location.fill);
			if (location.fill) $('#landmark-settings .fill-input').wpColorPicker('color', location.fill);
			else $('#landmark-settings .wp-color-result').css('background-color', '');

			if (location.style) $('#landmark-settings .style-select').val(location.style);
			else $('#landmark-settings .style-select').val('');

			if (location.category && typeof location.category === 'string') $('#landmark-settings .category-select').val(location.category.split(','));
			else $('#landmark-settings .category-select').val('false');

			$('#landmark-settings .thumbnail-input').val(location.thumbnail);
			$('#landmark-settings .image-input').val(location.image);

			if (location.action) $('#landmark-settings .action-select').val(location.action);
			else $('#landmark-settings .action-select').val('tooltip');

			$('#landmark-settings .zoom-input').val(location.zoom);
			$('#landmark-settings .reveal-input').val(location.reveal);
			if (location.hide) $('#landmark-settings .hide-input').prop('checked', true);
			else $('#landmark-settings .hide-input').prop('checked', false);
			
			$('#landmark-settings .about-input').val(location.about);

			// Custom fields
			$('#landmark-settings .mapplic-landmark-field').each(function() {
				var field = $(this).data('field');
				$(this).val(location[field]);
			});

			// Update UI
			$('#landmark-settings').show(); // show fields
			$('.save-landmark').val(mapplic_localization.save); // button text
			$('.duplicate-landmark').show(); // duplicate
		}

		this.getLocationFields = function(data) {
			data.title 			= $('#landmark-settings .title-input').val();
			data.id 			= $('#landmark-settings .id-input').val();
			data.post 			= $('#landmark-settings .post-input').val();
			data.description 	= $('#wp-descriptioninput-wrap').hasClass('html-active') ? $('#descriptioninput').val() : tinyMCE.get('descriptioninput').getContent();
			data.lat 			= $('#landmark-settings .landmark-lat').val();
			data.lng 			= $('#landmark-settings .landmark-lng').val();
			data.pin 			= $('#pins-input .selected .mapplic-pin').data('pin');
			data.label 			= $('#landmark-settings .label-input').val();
			data.fill 			= $('#landmark-settings .fill-input').val();
			data.link 			= $('#landmark-settings .link-input').val();
			data.style 			= $('#landmark-settings .style-select').val();
			data.category 		= $('#landmark-settings .category-select').val();
			data.thumbnail 		= $('#landmark-settings .thumbnail-input').val();
			data.image 			= $('#landmark-settings .image-input').val();
			data.action 		= $('#landmark-settings .action-select').val();
			data.zoom 			= $('#landmark-settings .zoom-input').val();
			data.reveal 		= $('#landmark-settings .reveal-input').val();
			data.about 			= $('#landmark-settings .about-input').val();
			data.hide 			= $('#landmark-settings .hide-input').prop('checked');

			// Custom fields
			$('#landmark-settings .mapplic-landmark-field').each(function(){
				var field = $(this).data('field');
				data[field] = $(this).val();
			});
		}

		this.deleteSelected = function() {
			var data = self.l[$('.selected-pin').data('location')];
			// Remove the location and pin
			if (data) {
				data.id = null;
				$('.selected-pin').remove();
			}
			// Hide the settings
			$('#landmark-settings').hide();
		}

		this.newLocation = function(data) {
			/*
			$.each(self.data.levels, function(index, level) {
				if (level.id == self.level) {
					level.locations.push(data);
					self.l[data.id] = data;
					data.level = level.id;
					if (data.fill) data.color = data.fill;
				}
			});*/

			if (!self.data.locations) self.data.locations = [];

			self.data.locations.push(data);
			self.l[data.id] = data;
			data.level = self.level;
			if (data.fill) data.color = data.fill;

			var marker = self.addMarker(data);
			marker.addClass('selected-pin').css({'transform': 'scale(' + 1/self.scale + ')'});

			$('.duplicate-landmark').show();
		}
	}

	// Form submit
	var invalid;
	var errormsg;

	$('input[type=submit]').click(function(event) {
		if ($('#mapplic-admin-map').is(':visible')) {
			invalid = false;

			var newData = {};

			// required fields
			newData['mapwidth'] = $('#setting-mapwidth').val();
			newData['mapheight'] = $('#setting-mapheight').val();

			// text and selected
			$('#settings input[type="text"], #settings select, #settings textarea').each(function() {
				var setting = $(this).attr('data-setting');
				if (setting) {
					if ($(this).val()) newData[setting] = $(this).val();
					else delete newData[setting];
				}
			});

			// remove csv protocol
			if (newData['csv']) newData['csv'] = newData['csv'].replace(/^(https\:)/, '').replace(/^(http\:)/, '');

			// checkboxes
			$('#settings input[type="checkbox"]').each(function() {
				var setting = $(this).attr('data-setting');
				if (setting) newData[setting] = $(this).is(':checked');
			});

			// code editor
			if (codeeditor) newData['customcss'] = codeeditor.codemirror.getValue();

			// Geolocation
			var topLat = $('#geopos > #topLat').val(),
				leftLng = $('#geopos > #leftLng').val(),
				bottomLat = $('#geopos > #bottomLat').val(),
				rightLng = $('#geopos > #rightLng').val();

			if (topLat && !isNaN(topLat)) newData['topLat'] = topLat;
			if (leftLng && !isNaN(leftLng)) newData['leftLng'] = leftLng;
			if (bottomLat && !isNaN(bottomLat)) newData['bottomLat'] = bottomLat;
			if (rightLng && !isNaN(rightLng)) newData['rightLng'] = rightLng;

			// Fetching data
			newData['levels'] = getLevels();
			newData['styles'] = getStyles();
			newData['categories'] = getCategories();
			newData['locations'] = getLocations();

			// Trigger event
			$('#mapplic-admin-map').trigger('mapplic-savedata', [newData]);

			// Validation
			if (invalid) {
				alert(errormsg);
				event.preventDefault();
				return false;
			}

			// Saving
			$('#mapplic-mapdata').val(JSON.stringify(newData));
		}
	});

	var getLevels = function() {
		var levels = [];
		$('#floor-list .list-item:not(.new-item)').each(function() {
			var level = {};

			level['id']        = $('.id-input', this).val();
			level['title']     = $('.title-input', this).val();
			level['map']       = $('.map-input', this).val().replace(/^(https\:)/, '').replace(/^(http\:)/, '');
			level['minimap']   = $('.minimap-input', this).val();
			if ($('.show-input', this).is(':checked')) level['show']  = 'true';
			//level['locations'] = getLocations(level['id']);

			// Validation
			if (level['id'] == '') {
				if (!invalid) errormsg = 'The floor titled "' + level['title'] + '" has no ID.';
				invalid = true;
			}

			levels.push(level);
		});

		levels.reverse();

		return levels;
	}

	var getStyles = function() {
		var styles = [];
		$('#style-list .list-item:not(.new-item):not(.predefined)').each(function() {
			var style = {base: {}, hover:{}, active: {}};
			
			style['class'] = $('.class-input', this).val();

			style.base['fill'] = $('.base-fill', this).val();
			style.hover['fill'] = $('.hover-fill', this).val();
			style.active['fill'] = $('.active-fill', this).val();

			if ($(this).hasClass('predefined')) style.predefined = 'true';

			// Validation
			if (!/^([a-z_]|-[a-z_-])[a-z\d_-]*$/i.test(style['class'])) {
				if (!invalid) errormsg = '"' + style['class'] + '" is not a valid class name.';
				invalid = true;
			}

			styles.push(style);
		});

		return styles;
	}

	var getCategories = function() {
		var categories = [];
		$('#category-list .list-item:not(.new-item)').each(function() {
			var category = {};
			
			category['title'] = $('.title-input', this).val();
			category['id'] = $('.id-input', this).val();
			category['about'] = $('.about-input', this).val();
			category['icon'] = $('.icon-input', this).val();
			category['style'] = $('.style-select', this).val();
			if ($('.legend-input', this).is(':checked')) category['legend'] = 'true';
			if ($('.hide-input', this).is(':checked')) category['hide'] = 'true';
			if ($('.toggle-input', this).is(':checked')) category['toggle'] = 'true';
			if ($('.switchoff-input', this).is(':checked')) category['switchoff'] = 'true';
			category['color'] = $('.color-input', this).val();

			// Validation
			if (category['id'] == '') {
				if (!invalid) errormsg = 'The category titled "' + category['title'] + '" has no ID.';
				invalid = true;
			}

			categories.push(category);
		});

		return categories;
	}

	var getLocations = function(targetLevel) {
		var locations = [];
		
		if (typeof self.data.levels !== 'undefined') {
			$.each(self.data.levels, function(index, level) {
				if ((level.id == targetLevel) || !targetLevel) {
					$.each(level.locations, function(index, location) {
						if (location.id !== null) {
							delete location.el;
							delete location.marker;
							delete location.list;
							delete location.styled;
							for (var key in location) if (location[key] == '') delete location[key];

							locations.push(location);
						}
					});
				}
			});
		}

		$.each(self.data.locations, function(index, location) {
			if (location.id !== null) {
				delete location.el;
				delete location.marker;
				delete location.list;
				delete location.styled;
				for (var key in location) if (location[key] == '') delete location[key];

				locations.push(location);
			}		
		});
		
		return locations;
	}
});