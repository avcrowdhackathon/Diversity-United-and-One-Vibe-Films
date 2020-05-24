<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $el_class
 * @var $full_width
 * @var $full_height
 * @var $equal_height
 * @var $columns_placement
 * @var $content_placement
 * @var $parallax
 * @var $parallax_image
 * @var $css
 * @var $el_id
 * @var $video_bg
 * @var $video_bg_url
 * @var $video_bg_parallax
 * @var $parallax_speed_bg
 * @var $parallax_speed_video
 * @var $content - shortcode content
 * @var $css_animation
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Row
 */
$el_class = $full_height = $parallax_speed_bg = $parallax_speed_video = $full_width = $equal_height = $flex_row = $columns_placement = $content_placement = $parallax = $parallax_image = $css = $el_id = $video_bg = $video_bg_url = $video_bg_parallax = $css_animation = $overlay_color = $img_pattern = $text_color = $gradient = $gradient_color = $gradient_bg = $gradient_opacity = $tooltip_fullpage = '';
$video_start = 0;
$disable_element = '';
$output = $after_output = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
if(isset($atts['video_start'])) {
	$video_start = $atts['video_start'];
}

if($gradient == '1' && $gradient_color !== '') {
	$gradient_bg = '<canvas class="veso-gradient-bg"></canvas>';
}
wp_enqueue_script( 'wpb_composer_front_js' );

$el_class = $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );

$css_classes = array(
	'vc_row',
	'wpb_row',
	//deprecated
	'vc_row-fluid',
	$el_class,
	vc_shortcode_custom_css_class( $css ),
);

if ( 'yes' === $disable_element ) {
	if ( vc_is_page_editable() ) {
		$css_classes[] = 'vc_hidden-lg vc_hidden-xs vc_hidden-sm vc_hidden-md';
	} else {
		return '';
	}
}

if ( vc_shortcode_custom_css_has_property( $css, array(
		'border',
		'background',
	) ) || $video_bg || $parallax
) {

}

if ( !empty( $atts['gap'] ) ) {
	if($atts['gap'] == 'remove-gap') {
		$css_classes[] = 'remove-gap';
	} else {
		$css_classes[] = 'vc_column-gap-' . $atts['gap'];
	}
}

$wrapper_attributes = array();
// build attributes for wrapper
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}


if ( ! empty( $full_width ) ) {
	if($full_width == 'stretch_row_right') {
		$wrapper_attributes[] = 'data-vc-stretch-side="right"';
	} elseif($full_width == 'stretch_row_left') {
		$wrapper_attributes[] = 'data-vc-stretch-side="left"';
	} else {
		$wrapper_attributes[] = 'data-vc-full-width="true"';
	}
	$wrapper_attributes[] = 'data-vc-full-width-init="false"';
	if ( 'stretch_row_content' === $full_width ) {
		$wrapper_attributes[] = 'data-vc-stretch-content="true"';
	} elseif ( 'stretch_row_content_no_spaces' === $full_width ) {
		$wrapper_attributes[] = 'data-vc-stretch-content="true"';
		$css_classes[] = 'vc_row-no-padding';
	}
	$after_output .= '<div class="vc_row-full-width vc_clearfix"></div>';
}

if ( ! empty( $full_height ) ) {
	$css_classes[] = 'vc_row-o-full-height';
	if ( ! empty( $columns_placement ) ) {
		$flex_row = true;
		$css_classes[] = 'vc_row-o-columns-' . $columns_placement;
		if ( 'stretch' === $columns_placement ) {
			$css_classes[] = 'vc_row-o-equal-height';
		}
	}
}

if ( ! empty( $equal_height ) ) {
	$flex_row = true;
	$css_classes[] = 'vc_row-o-equal-height';
}

if ( ! empty( $content_placement ) ) {
	$flex_row = true;
	$css_classes[] = 'vc_row-o-content-' . $content_placement;
}

if ( ! empty( $flex_row ) ) {
	$css_classes[] = 'vc_row-flex';
}

$has_video_bg = ( ! empty( $video_bg ) && ! empty( $video_bg_url ) && vc_extract_youtube_id( $video_bg_url ) );

$parallax_speed = $parallax_speed_bg;
if ( $has_video_bg ) {
	$parallax = $video_bg_parallax;
	$parallax_image = $video_bg_url;
}
$gradient_color = validate_colors($gradient_color);
$parallax_content = '';
if ( ! empty( $parallax_image ) ) {
	if ( $has_video_bg ) {
		$parallax_image_src = $parallax_image;
	} else {
		$parallax_image_id = preg_replace( '/[^\d]/', '', $parallax_image );
		$parallax_image_src = wp_get_attachment_image_src( $parallax_image_id, 'large_bg' );
		if ( ! empty( $parallax_image_src[0] ) ) {
			$parallax_image_src = $parallax_image_src[0];
		}
	}
}

if(!$parallax) {
	$parallax_speed = 1;
}
if($has_video_bg) {
	$parallax_content = '<div data-veso-parallax="'.esc_attr( $parallax_speed ).'" style="background-image: url('.esc_attr( $parallax_image_src ).')"  data-video="'.esc_attr( $video_bg_url ).'" data-video-start="'.esc_attr($video_start).'" class="extended_bg veso-parallax veso-video-bg-row"><div class="row-image-overlay" style="background-color: '.esc_attr($overlay_color).'"></div></div>';
} else {
	if(!empty($parallax_image)) {
		if(!$img_pattern) {
			$parallax_content = '<div data-veso-parallax="'.esc_attr( $parallax_speed ).'" style="background-image: url('.esc_attr( $parallax_image_src ).')" class="extended_bg veso-parallax"><div class="row-image-overlay" style="background-color: '.esc_attr($overlay_color).'"></div></div>';

		} else {
		
			$parallax_content = '<div style="background-image: url('.esc_attr( $parallax_image_src ).')" class="extended_bg pattern_bg"><div class="row-image-overlay" style="background-color: '.esc_attr($overlay_color).'"></div></div>';			
		}
	} else {
		if($overlay_color) {
			$parallax_content = '<div class="extended_bg"><div class="row-image-overlay" style="background-color: '.esc_attr($overlay_color).';"></div></div>';
		}
	}
}

$css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( array_unique( $css_classes ) ) ), $this->settings['base'], $atts ) );
$wrapper_attributes[] = 'class="' . esc_attr( trim( $css_class ) ) . '" style="color: '.$text_color.'" data-overlay-color="'.$overlay_color.'" data-color="'.$text_color.'" data-gradient-colors="'.$gradient_color.'" data-gradient-orient="'.$gradient_orient.'" data-tooltip="'.$tooltip_fullpage.'" data-gradient-opacity="'.$gradient_opacity.'"';

$output .= '<div ' . implode( ' ', $wrapper_attributes ) . '>'.$parallax_content . $gradient_bg;
$output .= wpb_js_remove_wpautop( $content );
$output .= '</div>';
$output .= $after_output;
$output = '<div class="">'.$output.'</div>';

echo  $output;
