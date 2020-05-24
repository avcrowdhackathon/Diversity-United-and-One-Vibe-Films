<?php

class Veso_Post_Types {

	function __construct() {
		add_action('init', array($this, 'veso_taxonomy'));
		add_action('admin_head', array($this, 'veso_admin_column_width'));
		add_filter('manage_veso_menu_posts_columns', array($this, 'veso_add_category_column' ));
		add_filter('manage_veso_footer_posts_columns', array($this, 'veso_add_category_column' ));
		add_filter('manage_veso_portfolio_posts_columns', array($this, 'veso_add_category_column' ));
		add_filter('manage_veso_portfolio_posts_columns', array($this, 'veso_add_image_column' ));
		add_filter('manage_veso_menu_posts_columns', array($this, 'veso_add_image_column' ));
		add_action('manage_posts_custom_column', array($this, 'veso_display_category' ), 10, 2);
		add_action('manage_posts_custom_column', array($this, 'veso_display_posts_image' ), 10, 2);

		add_action('admin_menu', array($this, 'veso_portfolio_sort'));
		add_action('wp_ajax_veso_portfolio_order', array($this, 'veso_saveFoodMenuOrder'));
		add_action( 'save_post', array($this, 'save_veso_menu_meta'), 10, 3 );
	}

	function veso_taxonomy() {
		/**
		 *  Footer Taxonomy
		 */

		register_post_type(
			'veso_footer',
			array(
				'labels' => array(
					'name' 			=> __('Footer', 'veso-theme-plugin'),
					'singular_name' => __('Footer', 'veso-theme-plugin'),
					'all_items'		=> __('Footers', 'veso-theme-plugin'),
					'add_new'		=> __('Add Footer', 'veso-theme-plugin'),
					'add_new_item'	=> __('Add Footer', 'veso-theme-plugin'),
					'edit_item'		=> __('Edit Footer', 'veso-theme-plugin')
				),
				'public'		=> true,
				'publicly_queryable' => true,
				'exclude_from_search' => true,
				'query_var' => true,
				'show_ui'		=> true,
				'can_export' => true,
				'capability_type' => 'post',
				'has_archive' => true,
				'supports'		=> array('title', 'editor'),
			)
		);

		$vesoPortfolioTaxSlug = get_field('veso_portfolio_category_slug', 'option');
		if($vesoPortfolioTaxSlug) {
			$portfolioTaxSlug = $vesoPortfolioTaxSlug;
		} else {
			$portfolioTaxSlug = 'veso_portfolio_categories';
		}

		$vesoPortfolioTagSlug = get_field('veso_portfolio_tag_slug', 'option');
		if($vesoPortfolioTagSlug) {
			$portfolioTagSlug = $vesoPortfolioTagSlug;
		} else {
			$portfolioTagSlug = 'veso_portfolio_tags';
		}

		$vesoPortfolioItemSlug = get_field('veso_portfolio_slug', 'option');
		if($vesoPortfolioItemSlug) {
			$portfolioItemSlug = $vesoPortfolioItemSlug;
		} else {
			$portfolioItemSlug = 'veso_portfolio';
		}

		register_taxonomy(
			'veso_portfolio_categories',
			'veso_portfolio',
			array(
				'public'		=> true,
				'publicly_queryable' => true,
				'has_archive' => true,
				'hierarchical'	=> true,
				'label'			=> __( 'Portfolio Categories', 'veso-theme-plugin' ),
				'query_var'		=> true,
				'show_ui'		=> true,
				'rewrite'		=> array(
					'slug'		=> $portfolioTaxSlug,
				),
			)
		);
		register_taxonomy(
			'veso_portfolio_tags',
			'veso_portfolio',
			array(
				'public'		=> true,
				'publicly_queryable' => true,
				'has_archive' => true,
				'hierarchical'	=> false,
				'label'			=> __( 'Portfolio Tags', 'veso-theme-plugin' ),
				'query_var'		=> true,
				'show_ui'		=> true,
				'update_count_callback' => '_update_post_term_count',
				'rewrite'		=> array(
					'slug'		=> $portfolioTagSlug,
				),
			)
		);

		register_post_type(
			'veso_portfolio',
			array(
				'labels' => array(
					'name' 			=> __('Portfolio', 'veso-theme-plugin'),
					'singular_name' => __('Portfolio', 'veso-theme-plugin'),
					'all_items'		=> __('All Portfolios', 'veso-theme-plugin'),
					'add_new'		=> __('Add Portfolio', 'veso-theme-plugin'),
					'add_new_item'	=> __('Add Portfolio', 'veso-theme-plugin'),
					'edit_item'		=> __('Edit Portfolio', 'veso-theme-plugin')
				),
				'public'		=> true,
				'publicly_queryable' => true,
				'exclude_from_search' => false,
				'query_var' => true,
				'show_ui'		=> true,
				'can_export' => true,
				'capability_type' => 'post',
				'has_archive' => true,
				'supports'		=> array('title', 'editor', 'thumbnail'),
				'taxonomies'	=> array('veso_portfolio_categories, veso_portfolio_tags'),
				'rewrite'		=> array(
					'slug'		=> $portfolioItemSlug,
				),
			)
		);

		register_post_type(
			'veso_mega_menu',
			array(
				'labels' => array(
					'name' 			=> __('Mega Menu', 'veso-theme-plugin'),
					'singular_name' => __('Mega Menu', 'veso-theme-plugin'),
					'all_items'		=> __('All Mega Menus', 'veso-theme-plugin'),
					'add_new'		=> __('Add Mega Menu', 'veso-theme-plugin'),
					'add_new_item'	=> __('Add Mega Menu', 'veso-theme-plugin'),
					'edit_item'		=> __('Edit Mega Menu', 'veso-theme-plugin')
				),
				'public'		=> true,
				'publicly_queryable' => true,
				'exclude_from_search' => true,
				'query_var' => true,
				'show_ui'		=> true,
				'can_export' => true,
				'capability_type' => 'post',
				'has_archive' => false,
				'supports'		=> array('title', 'editor', 'thumbnail'),
			)
		);
	}

	public function veso_add_image_column($columns) {
		$res = array_slice($columns, 0, 1, true) + array("image" => "Image") + array_slice($columns, 1, count($columns) - 1, true) ;
		return $res;
	}

	public function veso_add_category_column($columns) {
		$res = array_slice($columns, 0, 2, true) + array("category" => "Category (ID)") + array_slice($columns, 1, count($columns) - 1, true) ;
		return $res;
	}

	public function veso_display_category($column, $post_id) {
		$type = get_post_type($post_id);
		if ($type == 'veso_menu') {
			if ($column == 'category') {
				$categories = get_the_terms($post_id, 'veso_menu_categories');
				if($categories !== false) {
					foreach($categories as $cat) {
						$url = admin_url('edit.php?veso_menu_categories='.$cat->slug.'&post_type=veso_menu');
						echo '<strong><a class="row-title" href="'.$url.'" title="Edit '.htmlspecialchars($cat->name).'">'.$cat->name.' ('.$cat->term_id.')</a>, </strong>';
					}
				}
			}
		}
		if ($type == 'veso_portfolio') {
			if ($column == 'category') {
				$categories = get_the_terms($post_id, 'veso_portfolio_categories');
				if($categories !== false) {
					foreach($categories as $cat) {
						$url = admin_url('edit.php?veso_gallery_categories='.$cat->slug.'&post_type=veso_portfolio');
						echo '<strong><a class="row-title" href="'.$url.'" title="Edit '.htmlspecialchars($cat->name).'">'.$cat->name.' ('.$cat->term_id.')</a>, </strong>';
					}
				}
			}
		}
	}


	public function veso_admin_column_width() {
		if (strstr($_SERVER['SCRIPT_NAME'], 'edit.php')) {
			echo '<style type="text/css">.column-image { text-align: left; width:60px !important; overflow:hidden }</style>';	
		}
	}

	/* Display custom image column */
	public function veso_display_posts_image($column, $post_id) {
		$type = get_post_type($post_id);
		if ($type == 'veso_menu') {
			if ($column == 'image') {
				$image = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'thumbnail');
				if (!empty($image[0])) {
					echo '<img src="' . $image[0] . '" style="width:50px;" />';
				}
			}
		}
		if ($type == 'veso_portfolio') {
			if ($column == 'image') {
				$image = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'thumbnail');
				if (!empty($image[0])) {
					echo '<img src="' . $image[0] . '" style="width:50px;" />';
				}
			}
		}

	}

	function veso_portfolio_sort() {
		add_submenu_page('edit.php?post_type=veso_portfolio', 'Custom Post Type Admin', __('Reorder Portfolio Items', 'veso-theme-plugin'), 'edit_posts', basename(__FILE__), array($this,'reorder_food_menu'));
	}

	function reorder_food_menu() {
		wp_enqueue_script('jquery-ui-sortable');
		global $post;

		$order = get_option('veso_portfolio_categories_order');
		if($order != false && $order != '') {
			$order = explode(',', $order);
			$terms = get_terms('veso_portfolio_categories', array('hide_empty'=>true, 'include'=>$order, 'orderby'=>'include'));
			$termsExclude = get_terms('veso_portfolio_categories', array('hide_empty'=>true, 'exclude'=>$order));
		} else {
			$terms = get_terms('veso_portfolio_categories', array('hide_empty'=>true));
		}

		$termsArray = array();

		echo '<div class="reorder-category-wrapper"><div id="reorder_food_menu">';

		foreach ($terms as $term) {
			$termsArray[$term->term_id] = array('id'=>$term->term_id, 'name'=>$term->name, 'slug'=>$term->slug);
		}
		if(isset($termsExclude) && is_array($termsExclude)) {
			foreach ($termsExclude as $term) {
				$termsArray[$term->term_id] = array('id'=>$term->term_id, 'name'=>$term->name, 'slug'=>$term->slug);
			}
		}

		echo '<br/>';

		echo '<div class="reorder-category"></h2><hr/>' . "\r\n";
		$the_query = new WP_Query(array('posts_per_page'=>-1, 'post_type'=>'veso_portfolio', 'order'=>'ASC', 'orderby'=>'meta_value_num', 'meta_key'=>'veso_portfolio_order'));	

		if ($the_query->have_posts()) {
			while ($the_query->have_posts()) {
				$the_query->the_post();
				$args = array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'names');
				$terms = wp_get_post_terms( $post->ID, 'veso_portfolio_categories', $args ); 
				$terms = implode(', ', $terms);
				echo '<div class="ui-state-default" id="element-'.$post->ID.'" data-id="'.$post->ID.'" style="height: 30px; line-height: 30px; padding: 0 10px; margin: 2px 0; ">'.$post->post_title.'  <small style="float: right">'.$terms.'</small><br/></div>' . "\r\n";
			}

			wp_reset_postdata();
		}
		echo "</div>\r\n";

		echo "</div>\r\n";
		echo '<button class="button button-primary button-large update-order">'.__( 'Update', 'veso-theme-plugin').'</button></div>';
		?>
		<script type="text/javascript">
		jQuery(document).ready(function() {

			function reorderMenu() {
				var sortCategories = {};
				jQuery.each(jQuery('.reorder-category'), function(i, el) {
					var category = 1;
					sortCategories[category] = [];
					jQuery.each(jQuery(el).find('.ui-state-default'), function(i, el) {
						sortCategories[category].push(jQuery(el).data('id'));
					});
				});
			return sortCategories;
		}
		function categoriesOrder() {
			var categories = ''
			jQuery.each(jQuery('.reorder-category'), function(i, el) {
				var category = jQuery(el).data('category');
				categories += category+',';
			});
			return categories;
		}
		jQuery('#reorder_food_menu').sortable({
			items: '.reorder-category'
		});

		jQuery('.reorder-category').sortable({
			items: 'div'
		});

		jQuery('.update-order').click(function(e) {
			e.preventDefault();
			reorderMenu();
			categoriesOrder();
			jQuery.post(ajaxurl, { action : 'veso_portfolio_order', order : reorderMenu(), categories : categoriesOrder() }, function(data) {
				jQuery('.update-order').data('old', jQuery('.update-order').html()).html(data.message);
				setTimeout(function() {
					jQuery('.update-order').html(jQuery('.update-order').data('old'));
				},3000);
			}, 'JSON');
		});
	});
</script>
<?php
}

	function veso_saveFoodMenuOrder() {
		if (isset($_POST['action']) && $_POST['action'] == 'veso_portfolio_order') {
			if (isset($_POST['order'])) {
				$terms = get_terms('veso_portfolio_categories', array('hide_empty'=>true));

				foreach($_POST['order'] as $id => $category) {
					foreach($category as $order => $item) {
						update_post_meta( $item, 'veso_portfolio_order', $order);
					}
				}
				echo json_encode(array('message'=>__('Changes saved', 'veso-theme-plugin')));
			}
		}

		die();
	}


	function save_veso_menu_meta( $post_id, $post, $update ) {
		$post_type = get_post_type($post_id);

		// If this isn't a 'book' post, don't update it.
		if ( "veso_portfolio" != $post_type ) return;
		add_post_meta( $post_id, 'veso_portfolio_order', 999, false );
	}

}