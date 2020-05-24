<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Shortcode attributes
 * @var $atts
 * @var $content - shortcode content
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Cta
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
$this->buildTemplate( $atts, $content );
$containerClass = trim( 'vc_cta3-container ' . esc_attr( implode( ' ', $this->getTemplateVariable( 'container-class' ) ) ) );
$cssClass = trim( 'vc_general ' . esc_attr( implode( ' ', $this->getTemplateVariable( 'css-class' ) ) ) );
$wrapper_attributes = array();
if ( ! empty( $atts['el_id'] ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $atts['el_id'] ) . '"';
}

?>
<section class="<?php echo esc_attr( $containerClass ); ?>" <?php echo implode( ' ', $wrapper_attributes ); ?>>
	<div class="<?php echo esc_attr( $cssClass ); ?>" style="background: <?php echo esc_attr($atts['custom_background']); ?>; color: <?php echo esc_attr($atts['custom_text']); ?>"<?php
	if ( $this->getTemplateVariable( 'inline-css' ) ) {
		echo ' style="' . esc_attr( implode( ' ', $this->getTemplateVariable( 'inline-css' ) ) ) . '"';
	}
	?>>
		<?php echo  $this->getTemplateVariable( 'icons-top' ); ?>
		<?php echo  $this->getTemplateVariable( 'icons-left' ); ?>
		<div class="vc_cta3_content-container">
			<?php if($atts['add_button'] == 'left' || $atts['add_button'] == 'top') {
				$inline_prefix = $inline_suffix = "";
				if($atts['add_button'] == 'left') {
					$inline_prefix = '<div class="vc_btn3-container vc_btn3-inline">';
					$inline_suffix = '</div>';
				}
				echo '<div class="vc_cta3-actions">'.$inline_prefix.'';
				echo do_shortcode( '[veso_button btn_text="'.$atts['btn_text'].'" btn_link="'.$atts['btn_link'].'" btn_type="'.$atts['btn_type'].'" btn_align="'.$atts['btn_align'].'" btn_size="'.$atts['btn_size'].'" btn_color="'.$atts['btn_color'].'" bg_btn="'.$atts['bg_btn'].'" text_color="'.$atts['text_color'].'"]', false );
				echo '</div>'.$inline_suffix.'';
			}
			?>
			<div class="vc_cta3-content">
				<header class="vc_cta3-content-header">
					<?php echo  $this->getTemplateVariable( 'heading1' ); ?>
					<?php echo  $this->getTemplateVariable( 'heading2' ); ?>
				</header>
				<?php echo  $this->getTemplateVariable( 'content' ); ?>
			</div>
			<?php if($atts['add_button'] == 'right' || $atts['add_button'] == 'bottom') {
				$inline_prefix = $inline_suffix = "";
				if($atts['add_button'] == 'right') {
					$inline_prefix = '<div class="vc_btn3-container vc_btn3-inline">';
					$inline_suffix = '</div>';
				}
				echo '<div class="vc_cta3-actions">'.$inline_prefix.'';
				echo do_shortcode( '[veso_button btn_text="'.$atts['btn_text'].'" btn_link="'.$atts['btn_link'].'" btn_type="'.$atts['btn_type'].'" btn_align="'.$atts['btn_align'].'" btn_size="'.$atts['btn_size'].'" btn_color="'.$atts['btn_color'].'" bg_btn="'.$atts['bg_btn'].'" text_color="'.$atts['text_color'].'"]', false );
				echo '</div>'.$inline_suffix.'';
			}
			?>
		</div>
		<?php echo  $this->getTemplateVariable( 'icons-bottom' ); ?>
		<?php echo  $this->getTemplateVariable( 'icons-right' ); ?>
	</div>
</section>