<?php
/**
 * Google Maps
 */

class Veso_Gmaps {

	public function __construct(){
		add_shortcode( 'veso_gmaps', array( $this, 'google_maps' ) );
		add_action('wp_enqueue_scripts',array($this,'front_scripts') );

		add_action('init', array($this, 'google_map_config'));
	}
	function front_scripts(){
		global $post;
		$map_exists = false;
		if(!is_404() && !is_search()){
			if($post) {
				
				if(strpos($post->post_content, 'veso_gmaps')) {
					$map_exists = true;
				}
			}
		}

		if($map_exists == false && get_field('veso_enable_footer', 'option') == true) {
			$footer = get_field('veso_default_footer', 'option');
			if($footer && isset($footer->post_content)) {
				if(strpos($footer->post_content, 'veso_gmaps')) {
					$map_exists = true;
				}
			}
		}
		if($map_exists == true) {
			$key = '';
			$apiKey = get_field('veso_google_maps_key', 'option');
			if($apiKey != '') {
				$key = '?key='.$apiKey;
			}
			wp_register_script("google-maps-shortcode","https://maps.googleapis.com/maps/api/js$key","1.0",array(),false);
			wp_enqueue_script('google-maps-shortcode');
		}
	}

	public function google_maps( $atts, $content = null ) {
		$width = $size = $map_type = $lat = $lng = $zoom = $controls = $top_margin = $marker_icon = $icon_img = $output = $map_style = $scrollwheel = $el_class = '';
		$body_accent_color = get_field('veso_accent_color', 'option');
		extract(shortcode_atts(array(
			"size" => "300px",
			"veso_contact_markers" => "57.477773 -4.224721000000045",
			"map_type" => "ROADMAP",
			"lat" => "50.84984",
			"lng" => "16.47568",
			"scrollwheel" => "false",
			"controls" => "false",
			"map_zoom" => 14,
			"marker_icon" => "theme",
			"icon_img" => "",
			"map_style" => "",
			"el_class" => "",
			'marker_color' => $body_accent_color,
		), $atts));

		$marker_lat = $lat;
		$marker_lng = $lng;
		if($marker_icon == "default"){
			$icon_url = "";
			$markerCode = 'var marker = new google.maps.Marker(mapOptions)';
		} elseif($marker_icon == 'theme') {
			$icon_url = "";
			$markerCode = 'var marker = new CustomMarker(mapOptions);';
		} else {
			$markerCode = 'var marker = new google.maps.Marker(mapOptions)';
			$ico_img = wp_get_attachment_image_src( $icon_img, 'large');
			if(is_array($ico_img) && isset($ico_img[0])) {
				$icon_url = $ico_img[0];
			}
		}

		$id = "map_".uniqid();
		$wrap_id = "wrap_".$id;
		$map_type = strtoupper($map_type);
		$map_height = (substr($size, -1)!="%" && substr($size, -2)!="px" ? $size . "px" : $size);
		$output .= '<div id="'.$wrap_id.'" class="map-wrapper '.$el_class.' animate-text" style=" '.($map_height!='' ? 'height: ' . $map_height . ';' : '').'">

			<div id="' . $id . '" class="veso-google-map" ' . ($map_height!='' ? ' style="' . ($map_height!='' ? 'height:' . $map_height . ';' : '') .'' : '') . '">
			</div>
		</div>';

		$veso_contact_locations = array();
		$veso_contact_markers = explode(",", $veso_contact_markers);
		foreach ($veso_contact_markers as $value) {
			$value = trim($value);
			$veso_contact_locations[] = explode(" ", $value); 
		}

		$script = "
		(function($){
			'use strict';


		CustomMarker.prototype = new google.maps.OverlayView();

		function CustomMarker(opts) {
		    this.setValues(opts);
		}

		CustomMarker.prototype.draw = function() {
		    var self = this;
		    var div = this.div;
		    if (!div) {
		        div = this.div = jQuery('' +
		            '<div>' +
		            '<div class=\"shadow\"></div>' +
		            '<div class=\"pulse\"></div>' +
		            '<div class=\"pin-wrap\">' +
		            '<div class=\"pin\"></div>' +
		            '</div>' +
		            '</div>' +
		            '')[0];
		        this.pinWrap = this.div.getElementsByClassName('pin-wrap');
		        this.pin = this.div.getElementsByClassName('pin');
		        this.pinShadow = this.div.getElementsByClassName('shadow');
		        div.style.position = 'absolute';
		        div.style.cursor = 'pointer';
		        var panes = this.getPanes();
		        panes.overlayImage.appendChild(div);
		        google.maps.event.addDomListener(div, \"click\", function(event) {
		            google.maps.event.trigger(self, \"click\", event);
		        });
		    }
		    var point = this.getProjection().fromLatLngToDivPixel(this.position);
		    if (point) {
		        div.style.left = point.x + 'px';
		        div.style.top = point.y + 'px';
		    }
		};

		var map_$id = null;
		try
		{			
			var map_$id = null;
			var mapOptions = '';
			var locations = ".json_encode($veso_contact_locations).";
			var mapLocations = $.extend({}, locations );

			mapOptions = 
			{
				scaleControl: true,
				streetViewControl: $controls,
				mapTypeControl: $controls,
				panControl: $controls,
				zoomControl: $controls,
				scrollwheel: $scrollwheel,
				";
				if($map_style == ""){
					$script .= "mapTypeId: google.maps.MapTypeId.$map_type,";
				} else {
					$script .= " mapTypeControlOptions: {
				  		mapTypeIds: [google.maps.MapTypeId.$map_type, 'map_style']
					}";
				}
			$script .= "};";
			if($map_style !== ""){
			$script .= 'var styles = '.rawurldecode(base64_decode(strip_tags($map_style))).';
					var styledMap = new google.maps.StyledMapType(styles,
				    	{name: "Styled Map"});
					';
			}
			$script .= "var map_$id = new google.maps.Map(document.getElementById('$id'),mapOptions);";
			if($map_style !== ""){
			$script .= "map_$id.mapTypes.set('map_style', styledMap);
							 map_$id.setMapTypeId('map_style');";
			}
			
			$script .= "
			var latLngBounds = new google.maps.LatLngBounds();
			$.each(mapLocations, function(key, value) {
				var markerImage = value.marker;
				var latLng = new google.maps.LatLng(value[0], value[1]);
				latLngBounds.extend(latLng);

				var mapOptions = {
					position: latLng,
					animation:  google.maps.Animation.DROP,
					map: map_$id,
					icon: '$icon_url'
				};

				".$markerCode."
			});
			map_$id.setZoom(1);
			map_$id.initialZoom = true;
			// var listener = google.maps.event.addListener(map_$id, 'idle', function() {
			$(window).load(function(){

				map_$id.fitBounds(latLngBounds);
				var markers = Object.keys(mapLocations);
				var markerCount = markers.length;
				map_$id.initialZoom = false;
				if(markerCount == 1) {
					map_$id.setZoom($map_zoom);
				}
				if(map_$id.getZoom() > 15 && markerCount > 1) {
					map_$id.setZoom(16);
				}
				// google.maps.event.removeListener(listener);
				setTimeout(function(){

					$('#$id .pin').css('border-color', '$marker_color');
					$('#$id .pulse').css('color', '$marker_color');
				}, 10)
			})
			// });
			$(window).on('resize', function() {
				map_$id.fitBounds(latLngBounds);
				var markers = Object.keys(mapLocations);
				var markerCount = markers.length;
				map_$id.setZoom(map_$id.getZoom() -1);
				map_$id.initialZoom = false;
				if(markerCount == 1) {
					map_$id.setZoom($map_zoom);
				}
				if(map_$id.getZoom() > 15 && markerCount > 1) {
					map_$id.setZoom(16);
				}
			});

		}
		catch(e){};
		})(jQuery);
		";
		wp_add_inline_script('veso-global', $script);

		return $output;

	}

	function google_map_config() {
		if(!function_exists('vc_map')) {
			return;
		}
		vc_map( array(
			'name' => __( 'Veso Google Maps', 'veso-theme-plugin' ),
			'base' => 'veso_gmaps',
			"class" => "",
			"icon" => get_template_directory_uri().'/assets/images/logo-icon.png',
			"category" => __( "Content", "veso-theme-plugin"),
			'description' => __( 'Map block', 'veso-theme-plugin' ),
			'params' => array(
				array(
					'type'     => 'textarea',
					'param_name'       => 'veso_contact_markers',
					'heading'    => __( 'Markers positions', 'veso-theme-plugin' ),
					'value'  => '57.477773 -4.224721000000045',
					
					'description' => __('Insert latitude and longitude of a point. An example of website where you get geolocation: <a href="http://mondeca.com/index.php/en/any-place-en">Click here</a>. If you want to add multi contact, insert comma between positions.', 'veso-theme-plugin'),
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Map height', 'veso-theme-plugin' ),
					'param_name' => 'size',
					'value' => '300px',
					'admin_label' => true,
					'description' => __( 'Enter map height in pixels.', 'veso-theme-plugin' ),
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __('Map type', 'veso-theme-plugin'),
					"param_name" => "map_type",
					"admin_label" => true,
					"value" => array(__("Roadmap", 'veso-theme-plugin') => "ROADMAP", __("Satellite", 'veso-theme-plugin') => "SATELLITE", __("Hybrid", 'veso-theme-plugin') => "HYBRID", __("Terrain", 'veso-theme-plugin') => "TERRAIN"),
				),
				array(
					"type" => "dropdown",
					"heading" => __("Map Zoom", 'veso-theme-plugin'),
					"param_name" => "map_zoom",
					'description' => __('Insert map zoom if is only one marker point.', 'veso-theme-plugin'),
					"value" => array(
						1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20
					),
					"std" => 14,
				),
				array(
					"type" => "dropdown",
					"heading" => __("Map zoom on mouse wheel scroll", 'veso-theme-plugin'),
					"param_name" => "scrollwheel",
					"value" => array(__("Disable", 'veso-theme-plugin') => "false", __("Enable", 'veso-theme-plugin') => "true"),
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Show controls", 'veso-theme-plugin'),
					"param_name" => "controls",
					"value" => array(__("Disable", 'veso-theme-plugin') => "false", __("Enable", 'veso-theme-plugin') => "true"),
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Marker/Point icon", 'veso-theme-plugin'),
					"param_name" => "marker_icon",
					"value" => array(
						__("Use Theme Marker", 'veso-theme-plugin') => "theme",
						__("Use Google Default", 'veso-theme-plugin') => "default",
						__("Upload Custom", 'veso-theme-plugin') => "custom"),
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Marker color", 'veso-theme-plugin'),
					"param_name" => "marker_color",		
					"value" => "",
					'description' => __( 'Select color for map marker.', 'veso-theme-plugin' ),
					"dependency" => array("element" => "marker_icon","value" => 'theme'),		
				),

				array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __("Upload Image Icon:", 'veso-theme-plugin'),
					"param_name" => "icon_img",
					"admin_label" => true,
					"value" => "",
					"description" => __("Upload the custom image icon.", 'veso-theme-plugin'),
					"dependency" => array("element" => "marker_icon","value" => array("custom")),
				),
				array(
					"type" => "textarea_raw_html",
					"class" => "",
					"heading" => "Google Styled Map JSON",
					"param_name" => "map_style",
					"value" => "",
					"description" => __("<a target='_blank' href='https://snazzymaps.com/'>Click here</a> to get the style JSON code for styling your map.", 'veso-theme-plugin'),
					"group" => __("Custom style", 'veso-theme-plugin'),
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Extra class name', 'veso-theme-plugin' ),
					'param_name' => 'el_class',
					'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'veso-theme-plugin' ),
				),
			),
		) );
	}

}
