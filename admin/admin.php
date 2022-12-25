<?php

if (!class_exists('MapplicAdmin')) :

class MapplicAdmin {

	public function __construct() {
		// Actions
		add_action('admin_enqueue_scripts', array($this, 'mapplic_admin_enqueue_scripts_styles'));
		add_action('manage_mapplic_map_posts_custom_column' , array($this, 'mapplic_column_shortcode'), 10, 2);
		add_action('edit_form_after_editor', array($this, 'mapplic_backend_map'));
		add_action('add_meta_boxes_mapplic_map', array($this, 'mapplic_metaboxes'));
		add_action('in_admin_footer', array($this, 'mapplic_add_logo'));
		add_action('plugins_loaded', array($this, 'mapplic_activation'));

		// Filters
		add_filter('upload_mimes', array($this, 'mapplic_mime_types'));
		add_filter('manage_edit-mapplic_map_columns', array($this, 'mapplic_add_column_shortcode'));
		add_filter('wp_insert_post_data', array($this, 'mapplic_save_map'), 99, 2);

		// Includes
		include('maps.php');
		include('metaboxes.php');
	}

	public function mapplic_add_logo() {
		if (get_post_type() == 'mapplic_map') {
			echo '<a class="mapplic-logo" href="//www.mapplic.com" target="_blank"><img src="' . plugins_url('../images/logo.svg', __FILE__) . '"></a><small style="vertical-align: super;"> ' . Mapplic::$version . '</small><br>';
		}
	}

	public function mapplic_admin_enqueue_scripts_styles() {
		if (get_post_type() == 'mapplic_map') {
			// Disable autosave
			wp_dequeue_script('autosave');

			// Media uploader
			wp_enqueue_media();

			// Code Editor
			$cm_settings['codeEditor'] = wp_enqueue_code_editor(array('type' => 'text/css'));
			wp_localize_script('jquery', 'cm_settings', $cm_settings);
			
			wp_enqueue_script('wp-theme-plugin-editor');
			wp_enqueue_style('wp-codemirror');

			// Admin style
			wp_register_style('mapplic-style', plugins_url('../core/mapplic.css', __FILE__), false, Mapplic::$version);
			wp_enqueue_style('mapplic-admin-style', plugin_dir_url(__FILE__) . 'css/admin-style.css', array('mapplic-style'), Mapplic::$version);
			wp_enqueue_style('alpha-color-picker', plugin_dir_url(__FILE__) . 'css/alpha-color-picker.css', array('wp-color-picker'), null);

			// Admin scripts
			wp_register_script('mousewheel', plugins_url('../js/jquery.mousewheel.js', __FILE__), false, null);
			wp_enqueue_script('mapplic-admin', plugins_url('../core/mapplic.js', __FILE__), array('jquery', 'mousewheel'), Mapplic::$version, true);
			wp_enqueue_script('alpha-color-picker', plugins_url('js/alpha-color-picker.js', __FILE__), array('wp-color-picker'));
			wp_enqueue_script('mapplic-admin-script', plugins_url('js/admin-script.js', __FILE__), array('jquery', 'alpha-color-picker'), Mapplic::$version);
			wp_enqueue_script('csvparser', plugins_url('../js/csvparser.js', __FILE__), false, '5.0.2');
			$mapplic_localization = array(
				'add' => __('Add', 'mapplic'),
				'save' => __('Save', 'mapplic'),
				'search' => __('Search', 'mapplic'),
				'not_found' => __('Nothing found. Please try a different search.', 'mapplic'),
				'map' => __('Map', 'mapplic'),
				'raw' => __('Raw', 'mapplic'),
				'missing_id' => __('Landmark ID is required and must be unique!', 'mapplic'),
				'iconfile' => plugins_url('../core/images/icons.svg', __FILE__)
			);
			wp_localize_script('mapplic-admin-script', 'mapplic_localization', $mapplic_localization);
		}
	}

	public function mapplic_mime_types($mimes) {
		$mimes['svg'] = 'image/svg+xml';
		$mimes['csv'] = 'text/csv';
		return $mimes;
	}

	public static function mapplic_activation() {
		if (!get_option('mapplic-version')) {
			// First Activation
			//mapplic_restore_old_maps();
			mapplic_add_example_maps();
			add_option('mapplic-version', Mapplic::$version);
		}
	}

	// Column Shortcode
	public function mapplic_column_shortcode($column, $post_id) {
		if ($column == 'shortcode') echo '[mapplic id="' . $post_id . '"]';
	}

	public function mapplic_add_column_shortcode($columns) {
		$new_columns = array();
		foreach ($columns as $key => $title) {
			if ($key == 'date') $new_columns['shortcode'] = __('Shortcode', 'mapplic');
			$new_columns[$key] = $title;
		}
		return $new_columns;
	}

	// Map edit
	public function mapplic_backend_map($post) {
		if ($post->post_type == 'mapplic_map') {
			if (user_can_richedit()) $mapdata = htmlentities($post->post_content, ENT_QUOTES, 'UTF-8');
			else $mapdata = $post->post_content;

			echo '<div class="mapplic-rawedit"><label class="right"><input type="checkbox" id="mapplic-indent"></input>' . __('Indent', 'mapplic') . '</label>';
			echo '<textarea name="mapplic-mapdata" id="mapplic-mapdata" rows="20" spellcheck="false">' . $mapdata . '</textarea></div>';
			$screen = get_current_screen();
			if ($screen->action != 'add') {
				echo '<div id="mapplic-admin-map" data-mapdata="' . $mapdata . '"></div>';
				submit_button();
				echo '<input type="button" id="mapplic-editmode" class="button" value="' . __('Raw', 'mapplic') .'">';
			}
			else mapplic_new_map_type();
		}
	}

	public function mapplic_metaboxes($post) {
		$screen = get_current_screen();
		if ($screen->action != 'add') {
			add_meta_box('landmark', __('Location', 'mapplic'), 'mapplic_landmark_box', 'mapplic_map', 'side', 'core');
			add_meta_box('floors', __('Floors', 'mapplic'), 'mapplic_floors_box', 'mapplic_map', 'side', 'core');
			add_meta_box('style', __('Styles', 'mapplic'), 'mapplic_styles_box', 'mapplic_map', 'side', 'core');
			add_meta_box('categories', __('Groups', 'mapplic'), 'mapplic_categories_box', 'mapplic_map', 'side', 'core');
			add_meta_box('geoposition', __('Geoposition', 'mapplic'), 'mapplic_geoposition_box', 'mapplic_map', 'side', 'core');
			add_meta_box('settings', __('Settings', 'mapplic'), 'mapplic_settings_box', 'mapplic_map', 'normal', 'core');
			remove_meta_box('submitdiv', 'mapplic_map', 'side');
		}
	}

	public function mapplic_save_map($data, $postarr) {
		if (!isset($postarr['ID']) || !$postarr['ID']) return $data;
		if (($data['post_type'] == 'mapplic_map') && ($data['post_status'] != 'trash')) {
			if (isset($_POST['new-map-type'])) $data['post_content'] = mapplic_map_type($_POST['new-map-type']); // New
			else if (isset($_POST['mapplic-mapdata'])) $data['post_content'] = $_POST['mapplic-mapdata'];
		}
		return $data;
	}
}

endif;
?>