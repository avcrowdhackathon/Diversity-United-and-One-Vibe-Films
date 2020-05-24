<?php
/**
 * Shortcode attributes
 * @var $atts
 * @var $height
 * @var $height2
 * @var $el_class
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Empty_space
 */
$height = $height2 = $el_class = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class = 'vc_empty_space ' . $this->getExtraClass( $el_class ). vc_shortcode_custom_css_class( $css, ' ' );

$pattern = '/^(\d*(?:\.\d+)?)\s*(px|\%|in|cm|mm|em|rem|ex|pt|pc|vw|vh|vmin|vmax)?$/';

$regexr = preg_match( $pattern, $height, $matches );
$value = isset( $matches[1] ) ? (float) $matches[1] : (float) WPBMap::getParam( 'vc_empty_space', 'height' );
$unit = isset( $matches[2] ) ? $matches[2] : 'px';
$height = $value . $unit;
$inline_css = ( (float) $height >= 0 ) ? ' style="height: ' . esc_attr( $height ) . '"' : '';

$regexr2 = preg_match( $pattern, $height2, $matches2 );
$value2 = isset( $matches2[1] ) ? (float) $matches2[1] : (float) WPBMap::getParam( 'vc_empty_space', 'height2' );
$unit2 = isset( $matches2[2] ) ? $matches2[2] : 'px';


if($height2 !== '') {
	$height2 = $value2 . $unit2;
	$inline_css2 = ' style="height: ' . esc_attr( $height2 ) . '"';
	$empty_mobile = '<div class="hide-for-large" '.$inline_css2.'></div>';
	$class_hidden = 'show-for-large';
} else {
	$empty_mobile = '';
	$class_hidden = '';
}

$class .= $this->getExtraClass( $el_class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class, $this->settings['base'], $atts );

?>
	<div class="<?php echo esc_attr( trim( $css_class ) ); ?>" >
		<span class="vc_empty_space_inner"></span>
		<div class="<?php echo esc_attr($class_hidden) ;?>" <?php echo  $inline_css; ?>></div>
		<?php echo  $empty_mobile ;?>
	</div>
<?php echo  $this->endBlockComment( $this->getShortcode() ) . "\n";
