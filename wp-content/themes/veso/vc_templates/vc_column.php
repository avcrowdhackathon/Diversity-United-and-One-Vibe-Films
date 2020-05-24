<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $el_id
 * @var $el_class
 * @var $width
 * @var $css
 * @var $offset
 * @var $content - shortcode content
 * @var $css_animation
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Column
 */
$el_class = $el_id = $width = $css = $offset = $css_animation = $overlay_color = $overlay_color_hover = $parallax = $parallax_speed = $parallax_content = $parallax_image = $show_on_hover = $show_on_hover_class = $text_color = $parallax_speed_bg = $parallax_bg_size = $gradient = $gradient_color = $gradient_bg = $gradient_opacity = '';
$output = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

if($gradient == '1' && $gradient_color !== '') {
	$gradient_bg = '<canvas class="veso-gradient-bg"></canvas>';
}

$width = wpb_translateColumnWidthToSpan( $width );
$width = vc_column_offset_class_merge( $offset, $width );

$css_classes = array(
	$this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation ),
	'wpb_column',
	'vc_column_container',
	$width,
);

if ( vc_shortcode_custom_css_has_property( $css, array(
	'border',
	'background',
) ) ) {

}

$wrapper_attributes = array();

$parallax_speed = $parallax_speed_bg;

$parallax_content = '';
if ( ! empty( $parallax_image ) ) {
	$parallax_image_id = preg_replace( '/[^\d]/', '', $parallax_image );
	$parallax_image_src = wp_get_attachment_image_src( $parallax_image_id, 'large' );
	if ( ! empty( $parallax_image_src[0] ) ) {
		$parallax_image_src = $parallax_image_src[0];
	}
}
if(!$parallax) {
	$parallax_speed = 1;
}
if($show_on_hover) {
	$show_on_hover_class = 'show-on-hover';
}
$overlay_hover = '';
$overlay_parent_hover_class = '';
if($overlay_color_hover) {
	$overlay_hover = '<div style="background-color: '.$overlay_color_hover.';" class="column-overlay-hover"></div>';
	$overlay_parent_hover_class = 'column-hoverable';
}

if(!empty($parallax_image)) {
	$parallax_content = '<div data-veso-parallax="'.esc_attr( $parallax_speed ).'" style="background-image: url('.esc_attr( $parallax_image_src ).'); background-size: '.esc_attr( $parallax_bg_size ).'" class="extended_bg veso-parallax"></div>';

}

$gradient_color = validate_colors($gradient_color);
$css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( $css_classes ) ), $this->settings['base'], $atts ) );
$wrapper_attributes[] = 'class="' . esc_attr( trim( $css_class ) ) . '"  style="color: '.$text_color.'" data-gradient-colors="'.$gradient_color.'" data-gradient-orient="'.$gradient_orient.'" data-gradient-opacity="'.$gradient_opacity.'"';
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
$output .= '<div ' . implode( ' ', $wrapper_attributes ) . '>'. $gradient_bg;
$output .= '<div class="vc_column-inner ' . esc_attr( trim( vc_shortcode_custom_css_class( $css ) ) ) . ' '.$show_on_hover_class.'">'.$parallax_content;
if($overlay_parent_hover_class || $overlay_color || $overlay_hover) {
	$output .= '<div class="column-overlay '.$overlay_parent_hover_class.'" style="background: '.$overlay_color.'">'.$overlay_hover.'</div>';
}
$output .= '<div class="wpb_wrapper">';
$output .= wpb_js_remove_wpautop( $content );
$output .= '</div>';
$output .= '</div>';
$output .= '</div>';

echo  $output;
