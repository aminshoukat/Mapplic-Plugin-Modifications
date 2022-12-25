<?php

// Legacy support
function mapplic_restore_old_maps() {
	global $wpdb;

	$old_table = $wpdb->prefix . 'custommaps';
	$query = "SELECT * FROM $old_table WHERE status > 0";

	$old_maps = $wpdb->get_results($query, 'ARRAY_A');

	foreach ($old_maps as &$old_map) {
		$new_map = array(
			'post_type' 	=> 'mapplic_map',
			'post_title' 	=> $old_map['title'],
			'post_status' 	=> 'publish',
			'filter' 		=> true
		);
		$post_id = wp_insert_post($new_map);
		$table = $wpdb->prefix . 'posts';
		$wpdb->update($table, array('post_content' => $old_map['data']), array('ID' => $post_id));
	}
}

// Preinstalled example maps
function mapplic_add_example_maps() {
	$exampledir = plugins_url() . '/mapplic/images/examples';
	$mapdir = plugins_url() . '/mapplic/maps';

	$maps = array(
		'[Example] US States'  => '{"mapwidth":"959","mapheight":"593","minimap":true,"sidebar":false,"search":false,"hovertip":true,"categories":[],"levels":[{"id":"states","title":"States","map":"' . $mapdir . '/usa.svg","minimap":"' . $mapdir . '/usa-mini.jpg","locations":[{"id":"ca","title":"California","description":"<p>California state.</p>","link":"http://en.wikipedia.org/wiki/California","pin":"hidden no-fill","x":"0.0718","y":"0.4546","category":"false","action":"tooltip"},{"id":"wa","title":"Washington","description":"<p>The Evergreen State</p>","link":"http://en.wikipedia.org/wiki/Washington_(state)","pin":"hidden","x":"0.1331","y":"0.0971"},{"id":"nv","title":"Nevada","description":"Nevada is officially known as the \"Silver State\" due to the importance of silver to its history and economy","link":"http://en.wikipedia.org/wiki/Nevada","pin":"hidden","x":"0.1484","y":"0.3973"},{"id":"il","title":"Illinois","description":"<p>Three U.S. presidents have been elected while living in Illinois</p>","link":"http://en.wikipedia.org/wiki/Illinois","pin":"hidden","x":"0.6209","y":"0.4316","category":null},{"id":"ny","title":"New York","description":"New York is a state in the Northeastern and Mid-Atlantic regions of the United States.","link":"http://en.wikipedia.org/wiki/NewYork","pin":"hidden","x":"0.8472","y":"0.2680"},{"id":"ma","title":"Massachusetts","description":"Officially the Commonwealth of Massachusetts, is a state in the New England region of the northeastern United States.","link":"http://en.wikipedia.org/wiki/Massachusetts","pin":"hidden","x":"0.9049","y":"0.2625"},{"id":"ga","title":"Georgia","description":"Georgia is known as the Peach State and the Empire State of the South.","link":"http://en.wikipedia.org/wiki/Georgia_(U.S._state)","pin":"hidden","x":"0.7517","y":"0.6885"},{"id":"fl","title":"Florida","description":"The state capital is Tallahassee, the largest city is Jacksonville, and the largest metropolitan area is the Miami metropolitan area.","link":"http://en.wikipedia.org/wiki/Florida","pin":"hidden","x":"0.8001","y":"0.8486"},{"id":"tx","title":"Texas","description":"<p>The Lone Star State <a href=\"http://www.codecanyon.net\">Canyon</a></p>","link":"http://en.wikipedia.org/wiki/Texas","pin":"hidden","x":"0.4512","y":"0.7694","zoom":"2","category":null},{"id":"losangeles","title":"Los Angeles","description":"<p>The city of Angels</p>","x":"0.0892","y":"0.5742","zoom":"2","category":"false","action":"tooltip","pin":"circular pin-md pin-label","label":"LA"},{"id":"houston","title":"Houston","description":"<p>Space City</p>","x":"0.4962","y":"0.8127","zoom":"2","pin":"circular","category":"false","action":"tooltip"},{"id":"chicago","title":"Chicago","description":"<p>The windy city</p>","x":"0.6418","y":"0.3489","zoom":"2","pin":"circular","category":"false","action":"tooltip"},{"id":"newyork","title":"New York","description":"<p>The big apple</p>","x":"0.8827","y":"0.3322","zoom":"2","pin":"circular pin-md pin-label","label":"NY","category":"false","action":"tooltip"}]}],"maxscale":4,"zoombuttons":true,"fullscreen":false,"mapfill":false,"zoom":true,"alphabetic":false,"clearbutton":true,"mousewheel":true,"deeplinking":true,"fillcolor":"#343f4b","action":"tooltip"}',


		'[Example] Mall' => '{"mapwidth":"1000","mapheight":"500","action":"tooltip","defaultstyle":"light","maxscale":"2","zoommargin":"200","fullscreen":true,"hovertip":true,"hovertipdesc":false,"smartip":false,"deeplinking":true,"linknewtab":false,"minimap":false,"animations":false,"zoom":true,"zoombuttons":true,"clearbutton":true,"zoomoutclose":false,"closezoomout":true,"mousewheel":true,"mapfill":false,"sidebar":true,"search":true,"searchdescription":false,"alphabetic":true,"thumbholder":true,"sidebartoggle":false,"filtersopened":false,"highlight":false,"customcss":"","levels":[{"id":"underground","title":"Underground","map":"' . $exampledir . '/mall/mall-underground.svg","minimap":""},{"id":"ground-floor","title":"Ground Floor","map":"' . $exampledir . '/mall/mall-ground.svg","minimap":"","show":"true"},{"id":"first-floor","title":"First Floor","map":"' . $exampledir . '/mall/mall-first.svg","minimap":""}],"styles":[{"base":{"fill":""},"hover":{"fill":"#f4f4f4"},"active":{"fill":"#fff"},"class":"light"},{"base":{"fill":"#818181"},"hover":{"fill":"#666"},"active":{"fill":"#555"},"class":"dark"}],"categories":[{"title":"Fashion","id":"fashion","about":"Cothing & Accessories","icon":"","style":"","color":"#FF9595"},{"title":"Health","id":"health","about":"Health & Cosmetics","icon":"","style":"","color":"#FFB985"},{"title":"Food","id":"food","about":"Fast-foods & Restaurants","icon":"","style":"","color":"#A5C882"},{"title":"Department Stores","id":"dep","about":"Wide wariety of goods","icon":"","style":"","color":"#8DB3DC"},{"title":"Others","id":"others","about":"","icon":"","style":"","color":"#D3B8F8"},{"title":"Logos","id":"nopointer-logos","about":"","icon":"","style":"","legend":"true","hide":"true","toggle":"true","color":""}],"locations":[{"id":"gap","title":"GAP","about":"Lorem ipsum","description":"<p>Lorem ipsum</p>","category":"fashion","x":"0.3781","y":"0.4296","pin":"hidden","action":"tooltip","color":"#FF9595","level":"underground"},{"id":"petco","title":"Petco","about":"Lorem ipsum","description":"<p>Lorem ipsum</p>","category":"others","x":"0.5206","y":"0.2646","pin":"hidden","action":"tooltip","color":"#D3B8F8","level":"underground","style":"dark"},{"id":"nordstrom","title":"Nordstrom","about":"Lorem ipsum dolor sit amet.","description":"<p>Recognizing the need is the primary condition for design.</p>","x":"0.7856","y":"0.2022","zoom":"3","pin":"hidden","action":"tooltip","level":"ground-floor","style":"dark"},{"id":"macys","title":"Macy\'s","description":"<p>Macy\'s <i>department</i> store</p>","category":"dep","x":"0.1861","y":"0.5935","zoom":"2","pin":"hidden","action":"tooltip","color":"#8DB3DC","level":"ground-floor","style":"dark"},{"id":"jcpenney","title":"JCPenney","about":"Lorem ipsum","description":"<p>JCPenney store</p>","category":"dep","link":"https://1.envato.market/R5Nv","x":"0.6616","y":"0.6925","zoom":"3","pin":"hidden","action":"tooltip","color":"#8DB3DC","level":"ground-floor","style":"dark"},{"id":"walgreens","title":"Walgreens","about":"Lorem ipsum","description":"<p>At the corner of Happy &amp; Healthy</p>","category":"health","x":"0.4623","y":"0.5603","pin":"hidden","action":"tooltip","color":"#FFB985","level":"ground-floor"},{"id":"sephora","title":"Sephora","about":"Lorem ipsum","description":"<p>Makeup, fragrance, skincare</p>","category":"health","link":"https://1.envato.market/R5Nv","x":"0.7541","y":"0.5207","pin":"hidden","action":"tooltip","color":"#FFB985","level":"ground-floor"},{"id":"geox","title":"Geox","about":"Lorem ipsum","description":"<p>Lorem ipsum dolor sit amet</p>","category":"fashion","link":"https://1.envato.market/R5Nv","x":"0.3927","y":"0.5663","pin":"hidden","action":"tooltip","color":"#FF9595","level":"ground-floor"},{"id":"hnm","title":"H&M","about":"Lorem ipsum","description":"<p>Lorem ipsum</p>","category":["fashion"],"x":"0.6434","y":"0.4284","pin":"hidden","action":"tooltip","color":"#FF9595","level":"ground-floor"},{"id":"adidas","title":"Adidas","about":"Lorem ipsum","description":"<p>Lorem ipsum</p>","category":"fashion","x":"0.3688","y":"0.3909","pin":"hidden","action":"tooltip","color":"#FF9595","level":"ground-floor"},{"id":"massimodutti","title":"Massimo Dutti","about":"Lorem ipsum","description":"<p>Lorem ipsum</p>","category":"fashion","x":"0.6243","y":"0.2655","pin":"hidden","action":"tooltip","color":"#FF9595","level":"ground-floor"},{"id":"starbucks","title":"Starbucks","about":"Lorem ipsum","description":"<p>The coffee company</p>","category":"food","x":"0.5431","y":"0.5240","pin":"hidden","action":"tooltip","color":"#A5C882","level":"ground-floor"},{"id":"zara","title":"Zara","about":"Lorem ipsum","description":"<p>Lorem ipsum</p>","link":"https://1.envato.market/R5Nv","x":"0.4777","y":"0.2681","pin":"hidden","action":"tooltip","level":"ground-floor"},{"id":"applebees","title":"Applebee\'s","about":"Lorem ipsum","description":"<p>See you tomorrow</p>","category":"food","x":"0.7515","y":"0.2148","pin":"hidden","action":"tooltip","color":"#A5C882","level":"first-floor","style":"dark"},{"id":"kfc","title":"KFC","about":"Lorem ipsum","description":"<p>Kentucky Fried Chicken</p>","category":"food","x":"0.7457","y":"0.4971","pin":"hidden","action":"tooltip","color":"#A5C882","level":"first-floor"},{"id":"mcdonalds","title":"McDonald\'s","about":"Lorem ipsum","description":"<p>Additional information</p>","link":"https://1.envato.market/R5Nv","x":"0.7388","y":"0.3764","pin":"hidden","action":"tooltip","level":"first-floor"},{"id":"pizzahut","title":"Pizza Hut","about":"Lorem ipsum","description":"<p>Make it great</p>","category":"food","x":"0.7082","y":"0.5311","pin":"hidden","action":"tooltip","color":"#A5C882","level":"first-floor"},{"id":"subway","title":"Subway","about":"Lorem ipsum","description":"<p>Eat fresh.</p>","category":"food","x":"0.6250","y":"0.2669","pin":"hidden","action":"tooltip","color":"#A5C882","level":"first-floor"},{"id":"cvs","title":"CVS Pharmacy","about":"Lorem ipsum","description":"<p>Lorem ipsum <a href=\"http://www.codecanyon.net\">dolor sit</a> amet, consectetur.</p>","category":"health","link":"https://1.envato.market/R5Nv","x":"0.4972","y":"0.2288","pin":"hidden","action":"tooltip","color":"#FFB985","level":"first-floor"},{"id":"pullnbear","title":"Pull & Bear","about":"Lorem ipsum","description":"<p>Lorem ipsum</p>","category":"fashion","x":"0.4846","y":"0.2946","pin":"hidden","action":"tooltip","color":"#FF9595","level":"first-floor"},{"id":"amc","title":"AMC Theatres","about":"Lorem ipsum","description":"<p>Additional information</p>","category":"others","x":"0.6607","y":"0.6720","pin":"hidden","action":"tooltip","color":"#D3B8F8","level":"first-floor","style":"dark"},{"id":"atnt","title":"AT&T","about":"Lorem ipsum","description":"<p>Additional information</p>","category":"others","x":"0.3700","y":"0.5430","pin":"hidden","action":"tooltip","color":"#D3B8F8","level":"first-floor"}]}',

		'[Example] Real Estate' => '{"mapwidth":"1300","mapheight":"1000","minimap":false,"clearbutton":true,"zoombuttons":true,"sidebar":false,"search":false,"hovertip":true,"mousewheel":true,"fullscreen":true,"deeplinking":true,"mapfill":false,"zoom":true,"alphabetic":false,"action":"tooltip","categories":[{"title":"Sold","id":"sold","about":"","style":"sold","color":""},{"title":"Reserved","id":"reserved","about":"","style":"reserved","color":""}],"styles":[{"base":{"fill":"rgba(142,186,94,0.8)"},"hover":{"fill":"#8eba5e"},"active":{"fill":"#7aa855"},"class":"default"},{"base":{"fill":"rgba(234,117,117,0.6)"},"hover":{"fill":"rgba(234,117,117,0.8)"},"active":{"fill":"#ea7575"},"class":"sold"},{"base":{"fill":"rgba(234,194,140,0.6)"},"hover":{"fill":"rgba(234,194,140,0.8)"},"active":{"fill":"#eac28c"},"class":"reserved"}],"levels":[{"id":"lots","title":"Lots","map":"' . $exampledir . '/lots/lots.svg","minimap":"","locations":[{"id":"lot1","title":"Lot 1","pin":"hidden","description":"<p>Status: <b style=\"color: #8eba5e;\">Available</b><br />Size: <b>850 sqm</b><br />Please get in touch for an Offer.</p>","link":"#more","x":"0.4856","y":"0.4677","action":"tooltip","category":null},{"id":"lot2","title":"Lot 2","pin":"hidden","description":"<p>Status: <b style=\"color: #ea7575;\">SOLD</b><br />Size: <b>850 sqm</b><br />This lot is no longer available.</p>","x":"0.4774","y":"0.3857","category":["sold"],"action":"tooltip","style":null},{"id":"lot3","title":"Lot 3","pin":"hidden","description":"<p>Status: <b style=\"color: #eac28c;\">Reserved</b><br />Size: <b>850 sqm</b><br />Please get in touch for more info.</p>","link":"#more","x":"0.4696","y":"0.3229","category":["reserved"],"action":"tooltip","style":null},{"id":"lot4","title":"Lot 4","pin":"hidden","description":"<p>Status: <b style=\"color: #8eba5e;\">Available</b><br>Size: <b>850 sqm</b><br>Please get in touch for an Offer.</p>","link":"#more","x":"0.4625","y":"0.2577"},{"id":"lot5","title":"Lot 5","pin":"hidden","description":"<p>Status: <b style=\"color: #8eba5e;\">Available</b><br />Size: <b>850 sqm</b><br />Please get in touch for an Offer.</p>","link":"#more","x":"0.4650","y":"0.1835","action":"tooltip","category":null}]}],"maxscale":"1.8","zoomoutclose":true,"topLat":"","leftLng":"","bottomLat":"","rightLng":"","closezoomout":true,"thumbholder":false,"hidenofilter":false,"linknewtab":false,"smartip":false,"undefined":false,"defaultstyle":"default","highlight":false,"searchdescription":false}',

		'[Example] Apartment' => '{"mapwidth":"2000","mapheight":"2000","minimap":true,"sidebar":true,"search":true,"hovertip":true,"categories":[{"id":"furniture","title":"Furniture","color":"#4cd3b8","show":"false"},{"id":"rooms","title":"Rooms","color":"#63aa9c"}],"levels":[{"id":"lower","title":"Lower","map":"' . $exampledir . '/apartment/lower.jpg","minimap":"' . $exampledir . '/apartment/lower-small.jpg","locations":[{"id":"coffeetable","title":"Coffee Table","description":"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>","category":"furniture","link":"http://codecanyon.net/user/sekler?ref=sekler","x":"0.2067","y":"0.4660","zoom":"3","pin":"no-fill","action":"tooltip"},{"id":"stairstop","title":"Stairs","description":"<p>Let\'s go upstairs!</p>","category":"furniture","x":"0.5057","y":"0.6173","zoom":"3","pin":"no-fill","action":"tooltip"},{"id":"diningtable","title":"Dining Table","description":"<p>An eight-person dining table with an image.</p>","category":"rooms","x":"0.4746","y":"0.2883","zoom":"3","pin":"no-fill","action":"tooltip"},{"id":"coffeemachine","title":"Coffee Machine","description":"<p>Coffee Machine</p>","category":"furniture","x":"0.6792","y":"0.3459","zoom":"3","pin":"no-fill","action":"tooltip"},{"id":"workingtable","title":"Working Table","description":"It\'s the perfect home workspace you\'ve always dreamed about.","category":"furniture","x":"0.6285","y":"0.1480","zoom":"3"},{"id":"kitchen","title":"Kitchen","description":"<p>Welcome to the kitchen.</p>","category":"rooms","link":"http://codecanyon.net/user/sekler?ref=sekler","pin":"hidden","x":"0.6650","y":"0.4600","zoom":"3","action":"tooltip"},{"id":"dining","title":"Dining room","description":"<p>The main living room</p><!--<iframe width=\"320\" height=\"180\" src=\"//www.youtube.com/embed/HGy9i8vvCxk\" frameborder=\"0\" allowfullscreen></iframe>-->","category":"rooms","pin":"hidden","x":"0.2966","y":"0.3795","zoom":"3","action":"tooltip"}]},{"id":"upper","title":"Upper","map":"' . $exampledir . '/apartment/upper.jpg","minimap":"' . $exampledir . '/apartment/upper-small.jpg","locations":[{"id":"livingup","title":"Living room","description":"<p>I could spend the whole day here!</p>","category":"rooms","x":"0.4900","y":"0.3600","zoom":"2","pin":"no-fill","action":"tooltip"},{"id":"kingbed","title":"King bed","description":"<p>A king size bed situated in the main bedroom on the first floor.</p>","category":"furniture","x":"0.6564","y":"0.2782","zoom":"3","pin":"no-fill","action":"tooltip"},{"id":"bathroom","title":"Bathroom","description":"<p>Take a bath!</p>","category":"rooms","pin":"hidden","x":"0.7843","y":"0.4035","mapfill":"true","zoom":"3","action":"tooltip"}]}],"maxscale":1,"zoombuttons":true,"fullscreen":false,"mapfill":true,"zoom":true,"alphabetic":false,"clearbutton":true,"mousewheel":true,"deeplinking":false,"fillcolor":"","action":"tooltip"}'
	);

	global $wpdb;
	$table = $wpdb->prefix . 'posts';
	
	foreach ($maps as $title => $map) {
		$new_map = array(
			'post_type' 	=> 'mapplic_map',
			'post_title' 	=> $title,
			'post_status' 	=> 'publish',
			'filter' 		=> true
		);
		$post_id = wp_insert_post($new_map);
		$wpdb->update($table, array('post_content' => $map), array('ID' => $post_id));
	}
}

// New map type select
function mapplic_new_map_type() {
	// Built-in maps
	$maps = array(
		'world' 		=> __('World', 'mapplic'),
		'continents' 	=> __('Continents', 'mapplic'),
		'europe' 		=> __('Europe', 'mapplic'),
		'usa' 			=> __('USA', 'mapplic'),
		'world-us'		=> __('World + US', 'mapplic'),
		'canada' 		=> __('Canada', 'mapplic'),
		'australia' 	=> __('Australia', 'mapplic'),
		'france' 		=> __('France', 'mapplic'),
		'germany' 		=> __('Germany', 'mapplic'),
		'uk' 			=> __('United Kingdom', 'mapplic'),
		'italy' 		=> __('Italy', 'mapplic'),
		'netherlands' 	=> __('Netherlands', 'mapplic'),
		'switzerland' 	=> __('Switzerland', 'mapplic'),
		'russia' 		=> __('Russia', 'mapplic'),
		'china' 		=> __('China', 'mapplic'),
		'brazil' 		=> __('Brazil', 'mapplic')
	);
	?>
	<h3><?php _e('Select Map Type', 'mapplic'); ?></h3>
	<p><?php _e('Create a custom map using your own file(s) or select one of the built-in maps.', 'mapplic'); ?></p>
	<select id="mapplic-new-type" name="new-map-type">
		<option value="custom"><?php _e('Custom', 'mapplic'); ?></option>
	<?php foreach ($maps as $key => $map) : ?>
		<option value="<?php echo $key; ?>"><?php echo $map; ?></option>
	<?php endforeach; ?>
	</select>
	<br><br>

	<div id="mapplic-mapfile">
		<h3><?php _e('Define Map File', 'mapplic'); ?></h3>
		<p><?php _e('Upload or select map file from library. SVG, JPG and PNG formats supported.', 'mapplic'); ?></p>
		<label class="field-small">
			<b><?php _e('Name', 'mapplic'); ?></b><br>
			<input type="text" name="new-map-name" class="input-text title-input" value="My Map">
		</label>
		<label class="field-small">
			<b><?php _e('ID (required)', 'mapplic'); ?></b><br>
			<input type="text" name="new-map-id" class="input-text id-input" value="my-map">
		</label>

		<div class="field-medium">
			<label><b><?php _e('Map File (required)', 'mapplic'); ?></b><br>
				<input type="text" name="new-map" class="input-text map-input buttoned" value="">
				<button class="button media-button"><span class="dashicons dashicons-upload"></span></button>
			</label>
		</div>

		<label class="field-small">
			<b><?php _e('Width (reqiured)', 'mapplic'); ?></b><br>
			<input type="text" name="new-mapwidth" class="input-text" value="">
		</label>
		<label class="field-small">
			<b><?php _e('Height (reqiured)', 'mapplic'); ?></b><br>
			<input type="text" name="new-mapheight" class="input-text" value="">
		</label>
	</div>
	<?php
}

// Built-in Maps
function mapplic_map_type($map) {
	$mapdir = plugins_url() . '/mapplic/maps';
	$maps = array(
		'custom' => '{"mapwidth":"' . $_POST['new-mapwidth'] . '","mapheight":"' . $_POST['new-mapheight'] . '","minimap":false,"clearbutton":true,"zoombuttons":true,"zoomoutclose":true,"closezoomout":true,"sidebar":false,"search":false,"hovertip":true,"mousewheel":true,"fullscreen":false,"deeplinking":true,"mapfill":false,"zoom":true,"alphabetic":false,"action":"tooltip","categories":[],"levels":[{"id":"' . $_POST['new-map-id'] . '","title":"' . $_POST['new-map-name'] . '","map":"' . $_POST['new-map'] . '"}],"locations":[]}',

		'world' => '{"mapwidth":"1200","mapheight":"760","minimap":false,"zoombuttons":true,"sidebar":false,"search":false,"hovertip":true,"fullscreen":false,"mapfill":false,"zoom":true,"alphabetic":false,"categories":[],"levels":[{"id":"world","title":"World","map":"' . $mapdir . '/world.svg","minimap":"","locations":[{"id":"us","title":"USA","description":"<p>United States</p>","category":"false","action":"tooltip","pin":"hidden","x":"0.2287","y":"0.5149"}]}],"maxscale":4,"clearbutton":true,"mousewheel":true,"deeplinking":true,"fillcolor":"#343f4b","action":"tooltip"}',

		'continents' => '{"mapwidth":"400","mapheight":"220","minimap":false,"zoombuttons":false,"sidebar":false,"search":false,"hovertip":true,"fullscreen":false,"mapfill":false,"zoom":false,"alphabetic":false,"categories":[],"levels":[{"id":"continents","title":"Continents","map":"' . $mapdir . '/world-continents.svg","minimap":"","show":"true","locations":[{"id":"europe","title":"Europe","description":"<p>Example landmark.</p>","category":"false","action":"tooltip","pin":"hidden","x":"0.5494","y":"0.2492"}]}],"maxscale":4,"clearbutton":true,"mousewheel":false,"deeplinking":false,"fillcolor":"#343f4b","action":"tooltip"}',

		'europe' => '{"mapwidth":"841.68","mapheight":"595.2","minimap":false,"zoombuttons":true,"sidebar":true,"search":true,"hovertip":true,"fullscreen":false,"mapfill":true,"zoom":true,"alphabetic":true,"categories":[],"levels":[{"id":"europe","title":"Europe","map":"' . $mapdir . '/europe.svg","minimap":"","locations":[{"id":"ch","title":"Switzerland","description":"<p>Example country</p>","fill":"#343f4b","action":"tooltip","pin":"hidden","x":"0.5097","y":"0.7013"},{"id":"barcelona","title":"Barcelona","lat":"41.384420","lng":"2.175258","pin":"pin-transparent pin-md pin-label","label":"B","action":"default","x":0.5,"y":0.5}]}],"maxscale":"4","clearbutton":true,"mousewheel":true,"deeplinking":true,"fillcolor":"#343f4b","action":"tooltip","smartip":false,"linknewtab":false,"zoomoutclose":false,"closezoomout":false,"searchdescription":false,"thumbholder":false,"hidenofilter":false,"highlight":false,"topLat":"73.21243308208258","leftLng":"-49.62887515592035","bottomLat":"28.176699739870482","rightLng":"63.870051833093875","styles":[],"routes":{"opened":false}}',

		'usa' => '{"mapwidth":"960","mapheight":"600","minimap":true,"zoombuttons":true,"sidebar":false,"search":false,"hovertip":true,"fullscreen":false,"mapfill":false,"zoom":true,"alphabetic":false,"categories":[],"levels":[{"id":"usa","title":"USA","map":"' . $mapdir . '/usa.svg","minimap":"' . $mapdir . '/usa-mini.jpg","locations":[{"id":"il","title":"Illinois","description":"<p>Example state.</p>","category":"false","action":"tooltip","pin":"hidden","x":"0.6226","y":"0.4248"},{"id":"los-angeles","title":"Los Angeles","description":"<p>Welcome to LA!</p>","pin":"circular pin-md pin-label","label":"LA","category":"false","action":"default","x":"0.0898","y":"0.5749","fill":"#937ed7"},{"id":"new-york","title":"New York","description":"<p>Welcome to NY!</p>","pin":"circular pin-md pin-label","label":"NY","category":"false","action":"default","x":"0.8810","y":"0.3339","fill":"#937ed7"},{"id":"ca","title":"California","description":"<p>California state.</p>","pin":"hidden","category":"false","action":"default","x":"0.0806","y":"0.4705"}]}],"maxscale":3,"clearbutton":true,"mousewheel":true,"deeplinking":true,"fillcolor":"#343f4b","action":"tooltip"}',

		'world-us' => '{"mapwidth":"2400","mapheight":"1697","minimap":false,"clearbutton":true,"zoombuttons":true,"sidebar":false,"search":false,"hovertip":true,"mousewheel":true,"fullscreen":false,"deeplinking":true,"mapfill":false,"zoom":true,"alphabetic":false,"action":"tooltip","categories":[],"levels":[{"id":"world-us","title":"World + US","map":"' . $mapdir . '/world-us.svg","minimap":"","locations":[{"id":"stockholm","title":"Stockholm","description":"<p>Test (geo)location.</p>","lat":"59.329157","lng":"18.068701","pin":"circular","action":"default","x":0.5,"y":0.5}]}],"maxscale":"3","zoomoutclose":false,"closezoomout":false,"thumbholder":false,"fillcolor":"","csv":"","topLat":"84.83382568312074","leftLng":"-181.9372873759479","bottomLat":"-67.8325574122815","rightLng":"201.2439555495492"}',

		'canada' => '{"mapwidth":"640","mapheight":"600","minimap":false,"zoombuttons":false,"sidebar":false,"search":false,"hovertip":true,"fullscreen":false,"mapfill":false,"zoom":false,"alphabetic":false,"categories":[],"levels":[{"id":"canada","title":"Canada","map":"' . $mapdir . '/canada.svg","minimap":"","locations":[{"id":"ca-ab","title":"Alberta","description":"<p>Example province</p>","category":"false","action":"tooltip","pin":"hidden","x":"0.2528","y":"0.6790"}]}],"maxscale":4,"clearbutton":true,"mousewheel":false,"deeplinking":true,"fillcolor":"#343f4b","action":"tooltip"}',

		'australia' => '{"mapwidth":"600","mapheight":"540","minimap":false,"zoombuttons":true,"sidebar":false,"search":false,"hovertip":true,"fullscreen":false,"mapfill":false,"zoom":true,"alphabetic":false,"categories":[],"levels":[{"id":"australia","title":"Australia","map":"' . $mapdir . '/australia.svg","minimap":"","locations":[{"id":"qld","title":"Queensland","description":"<p>Example territory.</p>","category":"false","action":"tooltip","pin":"hidden","x":"0.7544","y":"0.3667"},{"id":"melbourne","title":"Melbourne","description":"<p>City of Melbourne.</p>","pin":"circular","category":"false","action":"default","x":"0.7586","y":"0.7752"}]}],"maxscale":3,"clearbutton":true,"mousewheel":true,"deeplinking":true,"fillcolor":"#343f4b","action":"tooltip"}',

		'france' => '{"mapwidth":"800","mapheight":"800","minimap":false,"zoombuttons":true,"sidebar":false,"search":false,"hovertip":true,"fullscreen":false,"mapfill":false,"zoom":true,"alphabetic":false,"categories":[],"levels":[{"id":"france","title":"France","map":"' . $mapdir . '/france.svg","locations":[{"id":"fr-18","title":"Department 18","description":"<p>Example department.</p>","category":"false","action":"tooltip","pin":"hidden","x":"0.4861","y":"0.4494"}]}],"maxscale":2,"clearbutton":true,"mousewheel":true,"deeplinking":true,"fillcolor":"#343f4b","action":"tooltip"}',

		'germany' => '{"mapwidth":"598","mapheight":"598","minimap":false,"zoombuttons":true,"sidebar":false,"search":false,"hovertip":true,"fullscreen":false,"mapfill":false,"zoom":true,"alphabetic":false,"categories":[],"levels":[{"id":"germany","title":"Germany","map":"' . $mapdir . '/germany.svg","minimap":"","locations":[{"id":"de-ni","title":"Niedersachsen","description":"<p>Example land.</p>","category":"false","action":"tooltip","x":"0.3980","y":"0.2674","pin":"hidden"},{"id":"de-by","title":"Bayern","pin":"hidden","category":"false","action":"tooltip","x":"0.6381","y":"0.7738","description":"<p>Bavaria.</p>"}]}],"maxscale":2,"clearbutton":true,"mousewheel":true,"deeplinking":true,"fillcolor":"#343f4b","action":"tooltip"}',


		'uk' => '{"mapwidth":"900","mapheight":"1179","minimap":false,"zoombuttons":true,"sidebar":false,"search":false,"hovertip":true,"fullscreen":true,"mapfill":false,"zoom":true,"alphabetic":false,"categories":[],"levels":[{"id":"uk","title":"United Kingdom","map":"' . $mapdir . '/uk.svg","minimap":"","locations":[{"id":"manchester","title":"Manchester","description":"<p>Donec et pretium justo</p>","pin":"circular pin-md pin-label","label":"M","lat":"53.479687","lng":"-2.245026","x":0.6101917297981584,"y":0.6660427891883045},{"id":"liverpool","title":"Liverpool","description":"<p>This is an example marker with <b>zoom reveal</b>.</p>","pin":"circular pin-md pin-label","label":"L","reveal":"1","lat":"53.407283","lng":"-2.991689","x":0.5677943722523553,"y":0.6713119279858741},{"id":"north-west","title":"North West","description":"<p>It is the third most populated region after the South East and Greater London.</p>","pin":"hidden","x":"0.5894","y":"0.6247","action":"tooltip"},{"id":"london","title":"Greater London","description":"<p>London is divided into the small City of London and the much wider Greater London.</p>","pin":"hidden","style":"defaultstyle","x":"0.7319","y":"0.8068"},{"id":"south-east","title":"South East","description":"<p>Lorem ipsum.</p>","pin":"hidden","style":"defaultstyle","x":"0.6975","y":"0.8291"}]}],"maxscale":"2","clearbutton":true,"mousewheel":true,"deeplinking":true,"fillcolor":"#343f4b","action":"tooltip","smartip":false,"linknewtab":false,"zoomoutclose":false,"closezoomout":false,"searchdescription":false,"thumbholder":false,"hidenofilter":false,"highlight":false,"topLat":"61.67728290005463","leftLng":"-12.991157691204458","bottomLat":"48.6433269995204","rightLng":"4.61991628182219","styles":[],"routes":{"opened":false}}',

		'italy' => '{"mapwidth":"800","mapheight":"1000","minimap":false,"zoombuttons":true,"sidebar":false,"search":false,"hovertip":true,"fullscreen":false,"mapfill":false,"zoom":true,"alphabetic":false,"categories":[],"levels":[{"id":"italy","title":"Italy","map":"' . $mapdir . '/italy.svg","minimap":"","locations":[{"id":"tuscany","title":"Tuscany","description":"<p>Example.</p>","category":"false","action":"tooltip","pin":"hidden","x":"0.3897","y":"0.3401"},{"id":"milan","title":"Milan","pin":"circular","category":"false","action":"tooltip","x":"0.2379","y":"0.1703"}]}],"maxscale":4,"clearbutton":true,"mousewheel":true,"deeplinking":true,"fillcolor":"#343f4b","action":"tooltip"}',

		'switzerland' => '{"mapwidth":"640","mapheight":"420","minimap":true,"zoombuttons":true,"sidebar":false,"search":false,"hovertip":true,"fullscreen":false,"mapfill":false,"zoom":true,"alphabetic":false,"categories":[],"levels":[{"id":"switzerland","title":"Switzerland","map":"' . $mapdir . '/switzerland.svg","minimap":"' . $mapdir . '/switzerland-mini.jpg","locations":[{"id":"BE","title":"Bern","description":"<p>Canton of Bern.</p>","category":"false","action":"tooltip","pin":"hidden","x":"0.3680","y":"0.4808"},{"id":"zurich","title":"Zürich","description":"<p>City of Zürich.</p>","pin":"circular pin-md pin-label","category":"false","action":"default","x":"0.5562","y":"0.2341","label":"ZH"}]}],"maxscale":3,"clearbutton":true,"mousewheel":true,"deeplinking":true,"fillcolor":"#343f4b","action":"tooltip"}',

		'netherlands' => '{"mapwidth":"598","mapheight":"598","bottomLat":"50.67500192979909","leftLng":"2.8680356443589807","topLat":"53.62609096857893","rightLng":"7.679884929662812","categories":[],"levels":[{"id":"netherlands","title":"Netherlands","map":"' . $mapdir . '/netherlands.svg","minimap":"","locations":[{"id":"nl-ut","title":"Utrecht","about":"Province","description":"<p>Utrecht province description.</p>","pin":"hidden","x":"0.4819","y":"0.5238","category":"false","action":"default"},{"id":"amsterdam","title":"Amsterdam","about":"Capital city","description":"<p>Amsterdam, the capital city.</p>","pin":"transparent pin-md pin-label","link":"http://www.codecanyon.net/user/sekler","lng":"4.896911","lat":"52.373412","x":0.4216415010830731,"y":0.4326147922230017,"label":"AM","fill":"#8224e3","category":"false","action":"lightbox"}]}],"maxscale":3,"minimap":false,"zoombuttons":true,"sidebar":true,"search":false,"hovertip":true,"fullscreen":false,"mapfill":false,"zoom":true,"alphabetic":false,"clearbutton":true,"mousewheel":true,"deeplinking":true,"fillcolor":"#343f4b","action":"tooltip"}',

		'russia' => '{"mapwidth":"1100","mapheight":"640","minimap":true,"zoombuttons":true,"sidebar":false,"search":false,"hovertip":true,"fullscreen":false,"mapfill":false,"zoom":true,"alphabetic":false,"categories":[],"levels":[{"id":"russia","title":"Russia","map":"' . $mapdir . '/russia.svg","minimap":"' . $mapdir . '/russia-mini.jpg","locations":[{"id":"YAN","title":"Yamalia","description":"<p>Example</p>","category":"false","action":"tooltip","pin":"hidden","x":"0.4019","y":"0.5905"},{"id":"MOW","title":"Moscow","description":"<p>The capital city.</p>","pin":"circular","category":"false","action":"default","x":"0.1267","y":"0.5924"}]}],"maxscale":4,"clearbutton":true,"mousewheel":true,"deeplinking":true,"fillcolor":"#343f4b","action":"tooltip"}',

		'china' => '{"mapwidth":"860","mapheight":"700","minimap":true,"zoombuttons":true,"sidebar":false,"search":false,"hovertip":true,"fullscreen":false,"mapfill":false,"zoom":true,"alphabetic":false,"categories":[],"levels":[{"id":"china","title":"China","map":"' . $mapdir . '/china.svg","minimap":"' . $mapdir . '/china-mini.jpg","locations":[{"id":"SD","title":"Shandong","description":"<p>Example.</p>","category":"false","action":"tooltip","pin":"hidden","x":"0.7626","y":"0.4833"}]}],"maxscale":4,"clearbutton":true,"mousewheel":true,"deeplinking":true,"fillcolor":"#343f4b","action":"tooltip"}',

		'brazil' => '{"mapwidth":"800","mapheight":"800","minimap":true,"zoombuttons":true,"sidebar":false,"search":false,"hovertip":true,"fullscreen":false,"mapfill":false,"zoom":true,"alphabetic":false,"categories":[],"levels":[{"id":"brazil","title":"Brazil","map":"' . $mapdir . '/brazil.svg","minimap":"' . $mapdir . '/brazil-mini.jpg","locations":[{"id":"br-ba","title":"Bahia","description":"<p>Bahia state.</p>","category":"false","action":"tooltip","pin":"hidden","x":"0.7964","y":"0.4429"}]}],"maxscale":2,"clearbutton":true,"mousewheel":true,"deeplinking":true,"fillcolor":"#343f4b","action":"tooltip"}'
	);
	
	return $maps[$map];
}
?>