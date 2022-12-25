<?php

// Validations
if (!function_exists('mplc_equals')) {
	function mplc_equals(&$field, $equals = null, $true = null, $false = '') {
		if (isset($field) && (($field == $equals) || is_null($equals))) echo (is_null($true)) ? $field : $true;
		else echo $false;
	}
}

if (!function_exists('mplc_checked')) {
	function mplc_checked(&$field) {
		if (isset($field) && (($field == 'true') || $field == true)) echo ' checked';
	}
}

if (!function_exists('mplc_field')) {
	function mplc_field(&$field) {
		echo isset($field) ? $field : '';
	}
}

// Actions
if (!function_exists('mplc_actions')) {
	function mplc_actions() {
		$actions = array(
			'tooltip' => __('Tooltip', 'mapplic'),
			'open-link' => __('Open link', 'mapplic'),
			'open-link-new-tab' => __('Open link in new tab', 'mapplic'),
			'lightbox' => __('Lightbox', 'mapplic'),
			'external' => __('External', 'mapplic'),
			'image' => __('Image', 'mapplic'),
			'reveal' => __('Reveal', 'mapplic'),
			'none' => __('None', 'mapplic'),
			'disabled' => __('Disabled', 'mapplic'),
			'select' => __('Select', 'mapplic')
		);

		$actions = apply_filters('mapplic_actions', $actions);

		return $actions;
	}
}

// Location metabox
function mapplic_landmark_box($post, $param) {
	if (user_can_richedit()) $data = json_decode($post->post_content, true);
	else $data = json_decode(stripslashes(html_entity_decode($post->post_content)), true);
	if (!is_array($data)) return;

	if (isset($data['styles'])) $styles = $data['styles'];
	if (isset($data['categories'])) $categories = $data['categories'];

	// Custom marker types
	$pins = array(
		'pin-text pin-label pin-lg'
	);
	$pins = apply_filters('mapplic_pins', $pins);
	?>

	<div id="landmark-settings">
		<div>
			<input type="button" class="delete-landmark button" value="<?php _e('Delete', 'mapplic'); ?>">
			<input type="button" class="save-landmark button button-primary right" value="<?php _e('Save', 'mapplic'); ?>">
		</div>
		<div class="clear"></div>
		<hr>

		<label><strong><?php _e('Title', 'mapplic'); ?>:</strong><input type="text" class="title-input input-text"></label>
		<label><strong><?php _e('ID (unique)', 'mapplic'); ?>:</strong><input type="text" class="id-input input-text"></label>
		<input type="text" class="post-input input-text" placeholder="<?php _e('WordPress post ID (optional)', 'mapplic'); ?>">
		<?php wp_editor('', 'descriptioninput', array('drag_drop_upload' => true)); ?>

		<?php do_action('mapplic_landmark_fields'); // Custom fields ?>

		<div class="landmark-geolocation">
			<p><strong><?php _e('Geolocation', 'mapplic'); ?></strong></p>
			<input type="text" class="landmark-lat input-text geopos-field" placeholder="Latitude">
			<input type="text" class="landmark-lng input-text geopos-field" placeholder="Longitude">
		</div>

		<p><strong><?php _e('Marker', 'mapplic'); ?></strong></p>
		<div>
			<div id="pins-input">
				<div><div class="mapplic-pin hidden" data-pin="hidden"></div><span>Hidden</span></div>
				<div><div class="mapplic-pin pin-pulse pin-label" data-pin="pin-pulse pin-label">P</div><span>Pulse</span></div>
				<div><div class="mapplic-pin pin-marker pin-label" data-pin="pin-marker pin-label">P</div><span>Circles</span></div>
				<div><div class="mapplic-pin pin-ribbon pin-label" data-pin="pin-ribbon pin-label">Text</div><span>Ribbon</span></div>
				<div><div class="mapplic-pin pin-dot pin-label" data-pin="pin-dot pin-label">Text</div><span>Dot</span></div>
				<p>Circular</p>
				<div><div class="mapplic-pin pin-sm" data-pin="pin-sm"></div><span>Small</span></div>
				<div><div class="mapplic-pin pin-label" data-pin="pin-label">M</div><span>Medium</span></div>
				<div><div class="mapplic-pin pin-lg pin-label" data-pin="pin-lg pin-label">L</div><span>Large</span></div>
				<div><div class="mapplic-pin pin-bordered pin-label" data-pin="pin-bordered pin-label">B</div><span>Border</span></div>
				<div><div class="mapplic-pin pin-triangle pin-label" data-pin="pin-triangle pin-label">P</div><span>Pin</span></div>
				<p>Rounded</p>
				<div><div class="mapplic-pin pin-rounded pin-sm" data-pin="pin-rounded pin-sm"></div><span>Small</span></div>
				<div><div class="mapplic-pin pin-rounded pin-label" data-pin="pin-rounded pin-label">M</div><span>Medium</span></div>
				<div><div class="mapplic-pin pin-rounded pin-lg pin-label" data-pin="pin-rounded pin-lg pin-label">L</div><span>Large</span></div>
				<div><div class="mapplic-pin pin-rounded pin-bordered pin-label" data-pin="pin-rounded pin-bordered pin-label">B</div><span>Border</span></div>
				<div><div class="mapplic-pin pin-rounded pin-triangle pin-label" data-pin="pin-rounded pin-triangle pin-label">P</div><span>Pin</span></div>
				<p>Square</p>
				<div><div class="mapplic-pin pin-square pin-sm" data-pin="pin-square pin-sm"></div><span>Small</span></div>
				<div><div class="mapplic-pin pin-square pin-label" data-pin="pin-square pin-label">M</div><span>Medium</span></div>
				<div><div class="mapplic-pin pin-square pin-lg pin-label" data-pin="pin-square pin-lg pin-label">L</div><span>Large</span></div>
				<div><div class="mapplic-pin pin-square pin-bordered pin-label" data-pin="pin-square pin-bordered pin-label">B</div><span>Border</span></div>
				<div><div class="mapplic-pin pin-square pin-triangle pin-label" data-pin="pin-square pin-triangle pin-label">P</div><span>Pin</span></div>
				<p>Custom</p>
			<?php foreach ($pins as &$pin) : ?>
				<div><div class="mapplic-pin <?php echo $pin; ?>" data-pin="<?php echo $pin; ?>">m</div></div>
			<?php endforeach; ?>
			</div>
		</div>
		<input type="text" class="label-input input-text" placeholder="<?php _e('Label, icon or image URL', 'mapplic'); ?>">
		<input type="text" class="mapplic-alpha-color-picker fill-input" data-text="<?php _e('Base Color', 'mapplic'); ?>">

		<p><strong><?php _e('Attributes', 'mapplic'); ?></strong></p>
		<label><?php _e('Link', 'mapplic'); ?>:<input type="text" class="link-input input-text"></label>

		<?php if (!empty($styles)) : ?>
		<label><?php _e('Style', 'mapplic'); ?>
			<select class="style-select input-select">
				<option value="">(<?php _e('No style', 'mapplic'); ?>)</option>
			<?php foreach ($styles as &$style) : ?>
				<option value="<?php echo $style['class']; ?>"><?php echo $style['class']; ?></option>
			<?php endforeach; ?>
			</select>
		</label>
		<?php endif; ?>

		<?php if (!empty($categories)) : ?>
		<label><?php _e('Groups', 'mapplic'); ?>
			<select class="category-select input-select" multiple>
			<?php foreach ($categories as &$category) : ?>
				<option value="<?php echo $category['id']; ?>"><?php echo $category['title']; ?></option>
			<?php endforeach; ?>
			</select>
		</label>
		<?php endif; ?>

		<div>
			<label><?php _e('Image', 'mapplic'); ?><br>
				<input type="text" class="input-text image-input buttoned" value="">
				<button class="button media-button"><span class="dashicons dashicons-format-image"></span></button>
			</label>
		</div>

		<div>
			<label><?php _e('Thumbnail', 'mapplic'); ?><br>
				<input type="text" class="input-text thumbnail-input buttoned" value="">
				<button class="button media-button"><span class="dashicons dashicons-format-image"></span></button>
			</label>
		</div>

		<label><?php _e('Action', 'mapplic'); ?>
			<select class="action-select input-select">
				<option value="default" selected>(<?php _e('Default', 'mapplic'); ?>)</option>
				<?php 
					foreach (mplc_actions() as $value => $action) : 
				?>
				<option value="<?php echo $value; ?>"<?php if ($data['action'] == $value) echo ' selected'; ?>><?php echo $action; ?></option>
				<?php endforeach; ?>
			</select>
		</label>

		<label><?php _e('Zoom Level', 'mapplic'); ?><input type="text" class="zoom-input input-text" placeholder="Auto"></label>

		<label><?php _e('Reveal Zoom', 'mapplic'); ?><input type="text" class="reveal-input input-text" placeholder="Disabled"></label>

		<label><input type="checkbox" class="hide-input"<?php mplc_equals($data['hide'], 'true', ' checked', ''); ?>> <?php _e('Hide from directory', 'mapplic'); ?></label>

		<label><?php _e('About', 'mapplic'); ?>:<input type="text" class="about-input input-text" placeholder="Text visible in directory"></label>

		<input type="button" class="duplicate-landmark button right" value="<?php _e('Duplicate', 'mapplic'); ?>">
	</div>

	<input type="button" id="new-landmark" class="button" value="<?php _e('Add New', 'mapplic'); ?>">
	
	<?php
	unset($pins);
	unset($category);
}

// Floors Metabox
function mapplic_floors_box($post, $param) {
	if (user_can_richedit()) $data = json_decode($post->post_content, true);
	else $data = json_decode(stripslashes(html_entity_decode($post->post_content)), true);
	if (!is_array($data)) return;

	$floors = array_reverse($data['levels']);
	?>

	<ul id="floor-list" class="sortable-list">
		<li class="list-item new-item">
			<div class="list-item-handle">
				<span class="menu-item-title"><?php _e('New Floor', 'mapplic'); ?></span>
				<a href="#" class="menu-item-toggle"></a>
			</div>
			<div class="list-item-settings">
				<label>
					<?php _e('Name', 'mapplic'); ?><br><input type="text" class="input-text title-input" value="<?php _e('New Floor', 'mapplic'); ?>">
				</label>
				<label><?php _e('ID (unique)', 'mapplic'); ?><br><input type="text" class="input-text id-input" value=""></label>

				<div>
					<label><?php _e('Map', 'mapplic'); ?><br>
						<input type="text" class="input-text map-input buttoned" value="">
						<button class="button media-button"><span class="dashicons dashicons-upload"></span></button>
					</label>
				</div>

				<div>
					<label><?php _e('Minimap', 'mapplic'); ?><br>
						<input type="text" class="input-text minimap-input buttoned" value="">
						<button class="button media-button"><span class="dashicons dashicons-upload"></span></button>
					</label>
				</div>

				<div>
					<a href="#" class="item-delete"><?php _e('Delete'); ?></a>
					<span class="meta-sep"> | </span>
					<a href="#" class="item-cancel"><?php _e('Cancel'); ?></a>
				</div>
			</div>
		</li>
	
	<?php foreach ($floors as &$floor) : ?>

		<li class="list-item">
			<div class="list-item-handle">
				<span class="menu-item-title"><?php echo $floor['title']; ?></span>
				<a href="#" class="menu-item-toggle"></a>
			</div>
			<div class="list-item-settings">
				<label><?php _e('Name', 'mapplic'); ?><br><input type="text" class="input-text title-input" value="<?php echo $floor['title']; ?>"></label>
				<label><?php _e('ID (unique)', 'mapplic'); ?><br><input type="text" class="input-text id-input" value="<?php echo $floor['id']; ?>" disabled></label>

				<?php $shown = (isset($floor['show']) && ($floor['show'] == 'true')) ? 'checked' : ''; ?>
				<label>
					<input type="radio" name="shown-floor" class="show-input" <?php echo $shown; ?> value="<?php echo $floor['id']; ?>"> <?php _e('Show by default', 'mapplic'); ?>
				</label>

				<div>
					<label><?php _e('Map', 'mapplic'); ?><br>
						<input type="text" class="input-text map-input buttoned" value="<?php echo $floor['map']; ?>">
						<button class="button media-button"><span class="dashicons dashicons-upload"></span></button>
					</label>
				</div>

				<div>
					<label>Minimap<br>
						<input type="text" class="input-text minimap-input buttoned" value="<?php echo $floor['minimap']; ?>">
						<button class="button media-button"><span class="dashicons dashicons-upload"></span></button>
					</label>
				</div>

				<div>
					<a href="#" class="item-delete"><?php _e('Delete'); ?></a>
					<span class="meta-sep"> | </span>
					<a href="#" class="item-cancel"><?php _e('Cancel'); ?></a>
				</div>
			</div>
		</li>

	<?php endforeach; ?>
	</ul>
	<input type="button" id="new-floor" class="button" value="<?php _e('New Floor', 'mapplic'); ?>">
	<input type="submit" name="submit" class="button button-primary form-submit right" value="<?php _e('Save', 'mapplic'); ?>">
	<div class="clear"></div>
	<?php
	unset($floor);
}

// Styles metabox
function mapplic_styles_box($post, $param) {
	if (user_can_richedit()) $data = json_decode($post->post_content, true);
	else $data = json_decode(stripslashes(html_entity_decode($post->post_content)), true);
	if (!is_array($data)) return;
	?>
	<ul id="style-list" class="sortable-list">

		<li class="list-item new-item">
			<div class="list-item-handle">
				<span class="menu-item-title"><?php _e('New Style', 'mapplic'); ?></span>
				<a href="#" class="menu-item-toggle"></a>
			</div>
			<div class="list-item-settings">

				<label><?php _e('Class', 'mapplic'); ?><br><input type="text" class="input-text class-input" value=""></label>

				<input type="text" class="mapplic-alpha-color-picker-new base-fill" data-text="<?php _e('Base Color', 'mapplic'); ?>">
				
				<input type="text" class="mapplic-alpha-color-picker-new hover-fill" data-text="<?php _e('Hover Color', 'mapplic'); ?>">

				<input type="text" class="mapplic-alpha-color-picker-new active-fill" data-text="<?php _e('Active Color', 'mapplic'); ?>">

				<div>
					<a href="#" class="item-delete"><?php _e('Delete'); ?></a>
					<span class="meta-sep"> | </span>
					<a href="#" class="item-cancel"><?php _e('Cancel'); ?></a>
				</div>
			</div>
		</li>

	<?php if (isset($data['styles']) && !empty($data['styles'])) : 
		$styles = $data['styles'];
		$styles = apply_filters('mapplic_styles', $styles);
	?>
	<?php foreach ($styles as &$style) : ?>
		<li class="list-item<?php echo isset($style['predefined']) ? ' predefined' : ''; ?>">
			<div class="list-item-handle">
				<span class="menu-item-title"><span class="mapplic-style-rect mapplic-pin <?php echo $style['class']; ?>"></span><?php echo $style['class']; ?></span>
				<a href="#" class="menu-item-toggle"></a>
			</div>
			<div class="list-item-settings">
				<label><?php _e('Class Name', 'mapplic'); ?><br><input type="text" class="input-text class-input" value="<?php echo $style['class']; ?>"></label>

				<input type="text" class="mapplic-alpha-color-picker base-fill" data-text="<?php _e('Base Color', 'mapplic'); ?>" value="<?php echo isset($style['base']['fill']) ? $style['base']['fill'] : ''; ?>">
				
				<input type="text" class="mapplic-alpha-color-picker hover-fill" data-text="<?php _e('Hover Color', 'mapplic'); ?>" value="<?php echo isset($style['hover']['fill']) ? $style['hover']['fill'] : ''; ?>">

				<input type="text" class="mapplic-alpha-color-picker active-fill" data-text="<?php _e('Active Color', 'mapplic'); ?>" value="<?php echo isset($style['active']['fill']) ? $style['active']['fill'] : ''; ?>">

				<div>
					<a href="#" class="item-delete"><?php _e('Delete'); ?></a>
					<span class="meta-sep"> | </span>
					<a href="#" class="item-cancel"><?php _e('Cancel'); ?></a>
				</div>
			</div>
		</li>
	<?php endforeach; ?>
	<?php else: ?>
		<p><i><?php _e('There are no reusable styles yet. Styles can be assigned to locations, groups, or set as default.'); ?></i></p>
	<?php endif; ?>
	</ul>
	<input type="button" id="new-style" class="button" value="<?php _e('New Style', 'mapplic'); ?>">
	<input type="submit" name="submit" class="button button-primary form-submit right" value="<?php _e('Save', 'mapplic'); ?>">
	<div class="clear"></div>	
	<?php
	unset($style);
}

// Categories metabox
function mapplic_categories_box($post, $param) {
	if (user_can_richedit()) $data = json_decode($post->post_content, true);
	else $data = json_decode(stripslashes(html_entity_decode($post->post_content)), true);
	if (!is_array($data)) return;
	?>
	<ul id="category-list" class="sortable-list">

		<li class="list-item new-item">
			<div class="list-item-handle">
				<span class="menu-item-title"><?php _e('New Group', 'mapplic'); ?></span>
				<a href="#" class="menu-item-toggle"></a>
			</div>
			<div class="list-item-settings">

				<label><?php _e('Name', 'mapplic'); ?><br><input type="text" class="input-text title-input" value="<?php _e('New Group', 'mapplic'); ?>"></label>
				<label><?php _e('ID (unique)', 'mapplic'); ?><br><input type="text" class="input-text id-input" value=""></label>
				<label><?php _e('About', 'mapplic'); ?><br><input type="text" class="input-text about-input" value="" placeholder="<?php _e('Text visible in directory', 'mapplic'); ?>"></label>
				<div>
					<label><?php _e('Icon', 'mapplic'); ?><br>
						<input type="text" class="input-text icon-input buttoned" value="">
						<button class="button media-button"><span class="dashicons dashicons-format-image"></span></button>
					</label>
				</div>

				<?php if (!empty($data['styles'])) : ?>
				<label><?php _e('Style', 'mapplic'); ?>
					<select class="style-select input-select">
						<option value="">(No Style)</option>
					<?php foreach ($data['styles'] as &$style) : ?>
						<option value="<?php echo $style['class']; ?>"><?php echo $style['class']; ?></option>
					<?php endforeach; ?>
					</select>
				</label>
				<?php endif; ?>

				<label><input type="checkbox" class="legend-input"><?php _e('Add to legend', 'mapplic'); ?></label>
				<label><input type="checkbox" class="hide-input"><?php _e('Hide from directory', 'mapplic'); ?></label>
				<label><input type="checkbox" class="toggle-input"><?php _e('Enable toggle mode', 'mapplic'); ?></label>
				<label><input type="checkbox" class="switchoff-input"><?php _e('Switch off by default', 'mapplic'); ?></label>
				<input type="text" class="mapplic-color-picker-new color-input" data-default-color="#aaaaaa">

				<div>
					<a href="#" class="item-delete"><?php _e('Delete'); ?></a>
					<span class="meta-sep"> | </span>
					<a href="#" class="item-cancel"><?php _e('Cancel'); ?></a>
				</div>
			</div>
		</li>

	<?php foreach ($data['categories'] as &$category) : ?>
		<li class="list-item">
			<div class="list-item-handle">
				<span class="menu-item-title"><?php echo $category['title']; ?></span>
				<a href="#" class="menu-item-toggle"></a>
			</div>
			<div class="list-item-settings">

				<label><?php _e('Name', 'mapplic'); ?><br><input type="text" class="input-text title-input" value="<?php echo $category['title']; ?>"></label>
				<label><?php _e('ID (unique)', 'mapplic'); ?><br><input type="text" class="input-text id-input" value="<?php echo $category['id']; ?>"></label>
				<label><?php _e('About', 'mapplic'); ?><br><input type="text" class="input-text about-input" value="<?php mplc_field($category['about']); ?>" placeholder="<?php _e('Text visible in directory', 'mapplic'); ?>"></label>
				<div>
					<label><?php _e('Icon', 'mapplic'); ?><br>
						<input type="text" class="input-text icon-input buttoned" value="<?php mplc_field($category['icon']); ?>">
						<button class="button media-button"><span class="dashicons dashicons-format-image"></span></button>
					</label>
				</div>

				<?php if (!empty($data['styles'])) : ?>
				<label><?php _e('Style', 'mapplic'); ?>
					<select class="style-select input-select">
						<option value="">(No Style)</option>
					<?php foreach ($data['styles'] as &$style) : ?>
						<option value="<?php echo $style['class']; ?>"<?php mplc_equals($category['style'], $style['class'], ' selected', ''); ?>><?php echo $style['class']; ?></option>
					<?php endforeach; ?>
					</select>
				</label>
				<?php endif; ?>

				<label><input type="checkbox" class="legend-input"<?php mplc_equals($category['legend'], 'true', ' checked', ''); ?>><?php _e('Add to legend', 'mapplic'); ?></label>
				<label><input type="checkbox" class="hide-input"<?php mplc_equals($category['hide'], 'true', ' checked', ''); ?>><?php _e('Hide from directory', 'mapplic'); ?></label>
				<label><input type="checkbox" class="toggle-input"<?php mplc_equals($category['toggle'], 'true', ' checked', ''); ?>><?php _e('Enable toggle mode', 'mapplic'); ?></label>
				<label><input type="checkbox" class="switchoff-input"<?php mplc_equals($category['switchoff'], 'true', ' checked', ''); ?>><?php _e('Switch off by default', 'mapplic'); ?></label>
				<input type="text" class="mapplic-color-picker color-input" value="<?php echo isset($category['color']) ? $category['color'] : ''; ?>" data-default-color="#aaaaaa">

				<div>
					<a href="#" class="item-delete"><?php _e('Delete'); ?></a>
					<span class="meta-sep"> | </span>
					<a href="#" class="item-cancel"><?php _e('Cancel'); ?></a>
				</div>
			</div>
		</li>
	<?php endforeach; ?>
	</ul>
	<input type="button" id="new-category" class="button" value="<?php _e('New Group', 'mapplic'); ?>">
	<input type="submit" name="submit" class="button button-primary form-submit right" value="<?php _e('Save', 'mapplic'); ?>">
	<div class="clear"></div>	
	<?php
	unset($category);
}

// Geoposition metabox
function mapplic_geoposition_box($post, $param) {
	if (user_can_richedit()) $data = json_decode($post->post_content, true);
	else $data = json_decode(stripslashes(html_entity_decode($post->post_content)), true);
	if (!is_array($data)) return;
	?>
	<div id="geopos">
		<div class="geopos-corner tl"></div>
		<input type="text" class="geopos-field" id="topLat" placeholder="Top Latitude" value="<?php mplc_equals($data['topLat']); ?>">
		<div class="geopos-corner tr"></div><br>
		<input type="text" class="geopos-field" id="leftLng" placeholder="Left Longitude" value="<?php mplc_equals($data['leftLng']); ?>">
		<input type="text" class="geopos-field" id="rightLng" placeholder="Right Longitude" value="<?php mplc_equals($data['rightLng']); ?>">
		<br><div class="geopos-corner bl"></div>
		<input type="text" class="geopos-field" id="bottomLat" placeholder="Bottom Latitude" value="<?php mplc_equals($data['bottomLat']); ?>">
		<div class="geopos-corner br"></div>
	</div>
	<?php
}

// Settings metabox
function mapplic_settings_box($post, $param) {
	if (user_can_richedit()) $data = json_decode($post->post_content, true);
	else $data = json_decode(stripslashes(html_entity_decode($post->post_content)), true);
	if (!is_array($data)) return;

	if (!is_numeric($data['mapwidth']) || !is_numeric($data['mapheight'])) :
	?>
		<div class="notice notice-error">
			<p><?php _e('Map file dimensions either not set or invalid!', 'mapplic'); ?></p>
		</div>
	<?php
	endif;
	?>

	<p><?php _e('Number of interactive SVG map elements:', 'mapplic'); ?> <strong id="interactive-elements-number">0</strong></p>
	<h4><?php _e('Map container', 'mapplic'); ?> <span class="dashicons dashicons-editor-help help-toggle"></span></h4>
	<p class="help-content"><i>Three value types accepted, example: <b>auto</b> (default), <b>600px</b> (fixed, defined in pixels) and <b>80%</b> (percent of the browser height).</i></p>
	
	<label>
		<?php _e('Height', 'mapplic'); ?><br>
		<input type="text" data-setting="height" value="<?php echo isset($data['height']) ? $data['height'] : ''; ?>" placeholder="auto">
	</label>
	<label>
		<?php _e('Min Height', 'mapplic'); ?><br>
		<input type="text" data-setting="minheight" value="<?php echo isset($data['minheight']) ? $data['minheight'] : ''; ?>" placeholder="400">
	</label>
	<label>
		<?php _e('Max Height', 'mapplic'); ?><br>
		<input type="text" data-setting="maxheight" value="<?php echo isset($data['maxheight']) ? $data['maxheight'] : ''; ?>" placeholder="800">
	</label>

	<br>
	<code>[mapplic id="<?php echo $post->ID; ?>" h="<span id="h-attribute"><?php echo (empty($data['height']) || $data['height'] == '') ? 'auto' : $data['height']; ?></span>"]</code>

	<h4><?php _e('Map file dimensions (REQUIRED)', 'mapplic'); ?></h4>
	<label>
		<?php _e('File Width', 'mapplic'); ?><br>
		<input type="text" id="setting-mapwidth" value="<?php echo $data['mapwidth']; ?>" placeholder="<?php _e('REQUIRED', 'mapplic'); ?>"><span> px</span>
	</label>
	<label>
		<?php _e('File Height', 'mapplic'); ?><br>
		<input type="text" id="setting-mapheight" value="<?php echo $data['mapheight']; ?>" placeholder="<?php _e('REQUIRED', 'mapplic'); ?>"><span> px</span>
	</label>

	<!-- General -->
	<h4><?php _e('General', 'mapplic'); ?></h4>
	<label>
		<?php _e('Portrait breakpoint', 'mapplic'); ?><br>
		<input type="text" data-setting="portrait" value="<?php echo isset($data['portrait']) ? $data['portrait'] : ''; ?>" placeholder="<?php _e('860 (Default)', 'mapplic'); ?>">
	</label>
	<label>
		<?php _e('Default action', 'mapplic'); ?><br>
		<select data-setting="action">
			<?php foreach (mplc_actions() as $value => $action) : ?>
			<option value="<?php echo $value; ?>"<?php if (isset($data['action']) && ($data['action'] == $value)) echo ' selected'; ?>><?php echo $action; ?></option>
			<?php endforeach; ?>
		</select>
	</label>

	<?php if (!empty($data['styles'])) : ?>
	<label><?php _e('Default style', 'mapplic'); ?><br>
		<select data-setting="defaultstyle">
			<option value="">(<?php _e('No style', 'mapplic'); ?>)</option>
		<?php foreach ($data['styles'] as &$style) : ?>
			<option value="<?php echo $style['class']; ?>"<?php mplc_equals($data['defaultstyle'], $style['class'], ' selected', ''); ?>><?php echo $style['class']; ?></option>
		<?php endforeach; ?>
		</select>
	</label>
	<?php endif; ?>
	
	<input type="text" data-setting="fillcolor" class="mapplic-color-picker" data-text="<?php _e('Default fill color', 'mapplic'); ?>" value="<?php echo isset($data['fillcolor']) ? $data['fillcolor'] : ''; ?>"><br><br>

	<label>
		<?php _e('More button text', 'mapplic'); ?><br>
		<input type="text" data-setting="moretext" value="<?php echo isset($data['moretext']) ? $data['moretext'] : ''; ?>" placeholder="<?php _e('More', 'mapplic'); ?>">
	</label>
	<label>
		<input type="checkbox" data-setting="fullscreen"<?php mplc_checked($data['fullscreen']); ?>> <?php _e('Enable fullscreen', 'mapplic'); ?>
	</label>
	<label>
		<input type="checkbox" data-setting="hovertip"<?php mplc_checked($data['hovertip']); ?>> <?php _e('Hover tooltip', 'mapplic'); ?>
	</label>
	<label>
		<input type="checkbox" data-setting="hovertipdesc"<?php mplc_checked($data['hovertipdesc']); ?>> <?php _e('Description in hover tooltip', 'mapplic'); ?>
	</label>
	<label>
		<input type="checkbox" data-setting="smartip"<?php mplc_checked($data['smartip']); ?>> <?php _e('Smart tooltip', 'mapplic'); ?>
	</label>
	<label>
		<input type="checkbox" data-setting="deeplinking"<?php mplc_checked($data['deeplinking']); ?>> <?php _e('Deeplinking', 'mapplic'); ?>
	</label>
	<label>
		<input type="checkbox" data-setting="linknewtab"<?php mplc_checked($data['linknewtab']); ?>> <?php _e('Open links in new tab', 'mapplic'); ?>
	</label>
	<label>
		<input type="checkbox" data-setting="minimap"<?php mplc_checked($data['minimap']); ?>> <?php _e('Enable minimap', 'mapplic'); ?>
	</label>
	<label>
		<input type="checkbox" data-setting="animations"<?php mplc_checked($data['animations']); ?>> <?php _e('Animations', 'mapplic'); ?>
	</label>

	<!-- Zoom options -->
	<h4><?php _e('Zoom options', 'mapplic'); ?></h4>
	<label>
		<input type="checkbox" class="settings-toggle" data-setting="zoom"<?php mplc_checked($data['zoom']); ?>> <?php _e('Enable zoom', 'mapplic'); ?>
	</label>
	<div class="settings-group" data-group="zoom">
		<label>
			<span><?php _e('Maximum zoom level', 'mapplic'); ?></span><br>
			<input type="text" data-setting="maxscale" value="<?php echo isset($data['maxscale']) ? $data['maxscale'] : '3'; ?>" placeholder="<?php _e('No zoom', 'mapplic'); ?>">
		</label>
		<label>
			<span><?php _e('Zoom margin', 'mapplic'); ?></span><br>
			<input type="text" data-setting="zoommargin" value="<?php echo isset($data['zoommargin']) ? $data['zoommargin'] : ''; ?>" placeholder="<?php _e('200 (Default)', 'mapplic'); ?>">
		</label>
		<label>
			<input type="checkbox" data-setting="zoombuttons"<?php mplc_checked($data['zoombuttons']); ?>> <span><?php _e('Zoom buttons', 'mapplic'); ?></span>
		</label>
		<label>
			<input type="checkbox" data-setting="clearbutton"<?php mplc_checked($data['clearbutton']); ?>> <span><?php _e('Clear button', 'mapplic'); ?></span>
		</label>
		<label>
			<input type="checkbox" data-setting="zoomoutclose"<?php mplc_checked($data['zoomoutclose']); ?>> <span><?php _e('Zoom out when closing popup', 'mapplic'); ?></span>
		</label>
		<label>
			<input type="checkbox" data-setting="closezoomout"<?php mplc_checked($data['closezoomout']); ?>> <span><?php _e('Close popup when zoomed out', 'mapplic'); ?></span>
		</label>
		<label>
			<input type="checkbox" data-setting="mousewheel"<?php mplc_checked($data['mousewheel']); ?>> <span><?php _e('Mouse wheel', 'mapplic'); ?></span>
		</label>
		<label>
			<input type="checkbox" data-setting="mapfill"<?php mplc_checked($data['mapfill']); ?>> <span><?php _e('Always fill the container', 'mapplic'); ?></span>
		</label>
	</div>

	<!-- Sidebar options -->
	<h4><?php _e('Sidebar options', 'mapplic'); ?></h4>
	<label>
		<input type="checkbox" class="settings-toggle" data-setting="sidebar"<?php mplc_checked($data['sidebar']); ?>> <?php _e('Enable sidebar', 'mapplic'); ?>
	</label>
	<div class="settings-group" data-group="sidebar">
		<label>
			<input type="checkbox" data-setting="search"<?php mplc_checked($data['search']); ?>> <span><?php _e('Search field', 'mapplic'); ?></span>
		</label>
		<label>
			<input type="checkbox" data-setting="searchdescription"<?php mplc_checked($data['searchdescription']); ?>> <span><?php _e('Search description', 'mapplic'); ?></span>
		</label>
		<label>
			<span><?php _e('Minimum keyword length', 'mapplic'); ?></span><br>
			<input type="text" data-setting="searchlength" value="<?php echo isset($data['searchlength']) ? $data['searchlength'] : ''; ?>" placeholder="<?php _e('1 (Default)', 'mapplic'); ?>">
		</label>
		<label>
			<input type="checkbox" data-setting="alphabetic"<?php mplc_checked($data['alphabetic']); ?>> <span><?php _e('Alphabetically ordered list', 'mapplic'); ?></span>
		</label>
		<label>
			<input type="checkbox" data-setting="thumbholder"<?php mplc_checked($data['thumbholder']); ?>> <span><?php _e('Thumbnail placeholder', 'mapplic'); ?></span>
		</label>
		<label>
			<input type="checkbox" data-setting="sidebartoggle"<?php mplc_checked($data['sidebartoggle']); ?>> <span><?php _e('Enable sidebar toggle', 'mapplic'); ?></span>
		</label>
		<label>
			<input type="checkbox" data-setting="filtersopened"<?php mplc_checked($data['filtersopened']); ?>> <span><?php _e('Open filters by default', 'mapplic'); ?></span>
		</label>
		<label>
			<input type="checkbox" data-setting="highlight"<?php mplc_checked($data['highlight']); ?>> <span><?php _e('Highlight map on filter', 'mapplic'); ?></span>
		</label>
	</div>

	<!-- UI -->
	<h4><?php _e('User Interface', 'mapplic'); ?></h4>
	<input type="text" data-setting="basecolor" class="mapplic-color-picker" data-text="<?php _e('Base color', 'mapplic'); ?>" value="<?php echo isset($data['basecolor']) ? $data['basecolor'] : ''; ?>"><br><br>
	<input type="text" data-setting="bgcolor" class="mapplic-color-picker" data-text="<?php _e('Primary background', 'mapplic'); ?>" value="<?php echo isset($data['bgcolor']) ? $data['bgcolor'] : ''; ?>"><br><br>
	<input type="text" data-setting="bgcolor2" class="mapplic-color-picker" data-text="<?php _e('Secondary background', 'mapplic'); ?>" value="<?php echo isset($data['bgcolor2']) ? $data['bgcolor2'] : ''; ?>"><br><br>
	<input type="text" data-setting="headingcolor" class="mapplic-color-picker" data-text="<?php _e('Headings color', 'mapplic'); ?>" value="<?php echo isset($data['headingcolor']) ? $data['headingcolor'] : ''; ?>"><br><br>
	<input type="text" data-setting="textcolor" class="mapplic-color-picker" data-text="<?php _e('Text color', 'mapplic'); ?>" value="<?php echo isset($data['textcolor']) ? $data['textcolor'] : ''; ?>"><br><br>
	<input type="text" data-setting="accentcolor" class="mapplic-color-picker" data-text="<?php _e('Accent color', 'mapplic'); ?>" value="<?php echo isset($data['accentcolor']) ? $data['accentcolor'] : ''; ?>"><br>

	<!-- CSV Support -->
	<h4><?php _e('CSV Support', 'mapplic'); ?> <span class="dashicons dashicons-editor-help help-toggle"></span></h4>
	<p class="help-content"><i><a href="https://www.mapplic.com/docs/#csv" target="_blank">You can link a CSV file or load its content. Click here</a> to learn more about CSV Support.</i></p>
	
	<label><?php _e('Link CSV file', 'mapplic'); ?><br>
		<input type="text" id="mapplic-csvfile" data-setting="csv" class="input-text buttoned" value="<?php echo isset($data['csv']) ? $data['csv'] : ''; ?>">
		<button class="button media-button"><span class="dashicons dashicons-media-spreadsheet"></span></button>
	</label><br>

	<input type="button" id="mapplic-csvload" class="button" value="<?php _e('Load Data', 'mapplic'); ?>">

	<!-- Custom CSS -->
	<h4><?php _e('Custom CSS', 'mapplic'); ?></h4>
	<textarea id="mapplic-customcss" data-setting="customcss" spellcheck="false"><?php echo isset($data['customcss']) ? $data['customcss'] : ''; ?></textarea>

	<?php
	do_action('mapplic_settings', $data);
}
?>