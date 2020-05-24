<?php 
	if(function_exists('get_field')) {
		$body_color = get_field('veso_text_color', 'option');
	} else {
		$body_color = '';
	}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> data-color="<?php echo esc_attr($body_color); ?>">
<div class="transition-overlay"></div>
	<?php get_template_part( 'template-parts/navigation/navigation', 'top' ); ?>
	<?php get_template_part( 'template-parts/navigation/navigation', 'fixed' ); ?>
	<?php get_template_part( 'template-parts/navigation/navigation', 'mobile' ); ?>
	<div class="page-wrapper">