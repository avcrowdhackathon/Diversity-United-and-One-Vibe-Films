<?php

class Veso_Import {
	private static $instance = null;

	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	private function __construct() {
		add_action( 'admin_menu', array($this, 'veso_addPage')); 
	}

	function veso_addPage() {
		add_menu_page('Import Demo', 'Import Demo', 'manage_options', 'veso_import', array($this,'veso_importer') );
		add_options_page('Import Demo', 'Import Demo', 'manage_options', 'veso_import', array($this,'veso_importer'));
	}

	function veso_importer() {
		$output = '';
		if(isset($_POST['select-demo'])) {
			$demo = $_POST['select-demo'];
			$this->import();
			$output = '<h3 style="text-align: left;">Import success</h3>';
		} else {
			$output = '<h3 style="text-align: left;">Import demo</h3>';
		}

		echo '<div style="max-width: 1200px; padding: 20px 20px 0; "><form method="POST">';
		echo $output;
		echo '<p>Press button below to start import process, next go to<br/>';
		echo 'Settings > Reading > Static page and select your Demo version there</br>';
		echo 'ie. Studio or Words</p>';
		echo '<p><strong>Note:</strong> An import process will delete your existing content!</p></div>';
		echo '<input type="radio" name="select-demo" value="1" checked="checked" style="visibility:hidden"/>';
		echo '<div class="clear"></div><input type="submit" value="Import demo" class="button button-primary" style="margin-left: 20px;" />';
		echo '</form></div></div>';
		echo '<style scoped>.demo:nth-child(3n-2) {clear: left; } .demo label { display: block; padding: 10px;  } .selected label { box-shadow: 0 0 30px #000; } .demo input { display: none; }</style>';
		echo '<script>
		(function($){
			"use strict";
			$(".demo").click(function(){ $(".demo.selected").removeClass("selected"); $(this).addClass("selected"); } )
		})(jQuery)
		</script>';

	}

	function addPage() {
		add_menu_page('Puruno Export', 'Puruno Export', 'manage_options', 'puruno-export' );
		add_options_page('Puruno Export', 'Puruno Export', 'manage_options', 'puruno-export', array($this, 'export'));
	}



	function import() {
		$target_file = VESO_PLUG_DIR_URI.'import/demo/demo_content.txt';
		$target_file_path = VESO_PLUG_DIR.'import/demo/demo_content.txt';
		global $wpdb;
		global $wp_filesystem;
		WP_Filesystem();
		$context = wp_upload_dir();
		$content = wp_remote_get($target_file) ;
		if($content instanceof WP_Error) {
			$content = file_get_contents($target_file_path);
		} else {
			$content = wp_remote_retrieve_body($content);
			if(strlen($content) < 300) {
				$content = file_get_contents($target_file_path);
			}
		}

		$content = json_decode($content, true);

		foreach($content['options'] as $option) {
			update_option( $option['option_name'], maybe_unserialize($option['option_value']), 'yes' );
		}

		// posts
		$wpdb->get_results("TRUNCATE TABLE $wpdb->posts");
		foreach($content['posts'] as $post) {
			$wpdb->insert($wpdb->posts, $post);
		}

		// postmeta
		$wpdb->get_results("TRUNCATE TABLE $wpdb->postmeta");
		foreach($content['postmeta'] as $postmeta) {
			$wpdb->insert($wpdb->postmeta, $postmeta);
		}

		// terms
		$wpdb->get_results("TRUNCATE TABLE $wpdb->terms");
		foreach($content['terms'] as $terms) {
			$wpdb->insert($wpdb->terms, $terms);
		}

		// termmeta
		$wpdb->get_results("TRUNCATE TABLE $wpdb->termmeta");
		foreach($content['termmeta'] as $termmeta) {
			$wpdb->insert($wpdb->termmeta, $termmeta);
		}

		// term_relationships
		$wpdb->get_results("TRUNCATE TABLE $wpdb->term_relationships");
		foreach($content['term_relationships'] as $term_relationships) {
			$wpdb->insert($wpdb->term_relationships, $term_relationships);
		}

		// term_taxonomy
		$wpdb->get_results("TRUNCATE TABLE $wpdb->term_taxonomy");
		foreach($content['term_taxonomy'] as $term_taxonomy) {
			$wpdb->insert($wpdb->term_taxonomy, $term_taxonomy);
		}


		$url = plugin_dir_url( __DIR__ ).'img/imported_veso.jpg';
		$imageId = $this->fetch_media($url);
		$wpdb->get_results("UPDATE $wpdb->postmeta SET meta_value = $imageId WHERE meta_key = '_thumbnail_id'");

		$wpdb->get_results("DELETE FROM $wpdb->options WHERE option_name = 'options_veso_email_list_0_email_address' OR option_name = '_options_veso_email_list_0_email_address' OR option_name = 'options_veso_email_list' OR option_name = '_options_veso_email_list' OR option_name = 'options_veso_newsletter_key' OR option_name = '_options_veso_newsletter_key'");
		$wpdb->get_results("UPDATE $wpdb->postmeta SET meta_value = 'importedmedia/importedmedia.jpg' WHERE meta_key = '_wp_attached_file'" );

		$array = 'a:5:{s:5:"width";i:1920;s:6:"height";i:1080;s:4:"file";s:31:"importedmedia/importedmedia.jpg";s:5:"sizes";a:8:{s:9:"thumbnail";a:4:{s:4:"file";s:25:"importedmedia-150x150.jpg";s:5:"width";i:150;s:6:"height";i:150;s:9:"mime-type";s:10:"image/jpeg";}s:6:"medium";a:4:{s:4:"file";s:25:"importedmedia-300x169.jpg";s:5:"width";i:300;s:6:"height";i:169;s:9:"mime-type";s:10:"image/jpeg";}s:12:"medium_large";a:4:{s:4:"file";s:25:"importedmedia-768x432.jpg";s:5:"width";i:768;s:6:"height";i:432;s:9:"mime-type";s:10:"image/jpeg";}s:5:"large";a:4:{s:4:"file";s:26:"importedmedia-1024x576.jpg";s:5:"width";i:1024;s:6:"height";i:576;s:9:"mime-type";s:10:"image/jpeg";}s:14:"shop_thumbnail";a:4:{s:4:"file";s:25:"importedmedia-180x180.jpg";s:5:"width";i:180;s:6:"height";i:180;s:9:"mime-type";s:10:"image/jpeg";}s:12:"shop_catalog";a:4:{s:4:"file";s:25:"importedmedia-300x300.jpg";s:5:"width";i:300;s:6:"height";i:300;s:9:"mime-type";s:10:"image/jpeg";}s:11:"shop_single";a:4:{s:4:"file";s:25:"importedmedia-600x600.jpg";s:5:"width";i:600;s:6:"height";i:600;s:9:"mime-type";s:10:"image/jpeg";}s:9:"veso-food";a:4:{s:4:"file";s:25:"importedmedia-900x680.jpg";s:5:"width";i:900;s:6:"height";i:680;s:9:"mime-type";s:10:"image/jpeg";}}s:10:"image_meta";a:12:{s:8:"aperture";s:1:"0";s:6:"credit";s:0:"";s:6:"camera";s:0:"";s:7:"caption";s:0:"";s:17:"created_timestamp";s:1:"0";s:9:"copyright";s:0:"";s:12:"focal_length";s:1:"0";s:3:"iso";s:1:"0";s:13:"shutter_speed";s:1:"0";s:5:"title";s:0:"";s:11:"orientation";s:1:"1";s:8:"keywords";a:0:{}}}';

		$wpdb->get_results("UPDATE $wpdb->postmeta SET meta_value = '$array' WHERE meta_key = '_wp_attachment_metadata'" );
		$wpdb->get_results("UPDATE $wpdb->posts SET post_title = 'Placeholder image' WHERE post_type = 'attachment'");
	}



	public function fetch_media($file_url) {
		require_once(ABSPATH . 'wp-load.php');
		require_once(ABSPATH . 'wp-admin/includes/image.php');
		global $wpdb;
		$artDir = 'wp-content/uploads/importedmedia/';

		if(!file_exists(ABSPATH.$artDir)) {
			mkdir(ABSPATH.$artDir);
		}

		$arr = explode(".", $file_url);
		$ext = array_pop($arr);
		$new_filename = 'importedmedia.'.$ext;

		if (@fclose(@fopen($file_url, "r"))) {
			copy($file_url, ABSPATH.$artDir.$new_filename);

			$siteurl = get_option('siteurl');
			$file_info = getimagesize(ABSPATH.$artDir.$new_filename);

			$artdata = array();
			$artdata = array(
				'post_author' => 1, 
				'post_date' => current_time('mysql'),
				'post_date_gmt' => current_time('mysql'),
				'post_title' => $new_filename, 
				'post_status' => 'inherit',
				'comment_status' => 'closed',
				'ping_status' => 'closed',
				'post_name' => sanitize_title_with_dashes(str_replace("_", "-", $new_filename)),
				'post_modified' => current_time('mysql'),
				'post_modified_gmt' => current_time('mysql'),
				'post_type' => 'attachment',
				'guid' => $siteurl.'/'.$artDir.$new_filename,
				'post_mime_type' => $file_info['mime'],
				'post_excerpt' => '',
				'post_content' => ''
			);

			$uploads = wp_upload_dir();
			$save_path = $uploads['basedir'].'/importedmedia/'.$new_filename;

			$attach_id = wp_insert_attachment( $artdata, $save_path );

			if ($attach_data = wp_generate_attachment_metadata( $attach_id, $save_path)) {
				wp_update_attachment_metadata($attach_id, $attach_data);

			}
		}
		else {
			return false;
		}

		return $attach_id;
	}
}

Veso_Import::get_instance();
