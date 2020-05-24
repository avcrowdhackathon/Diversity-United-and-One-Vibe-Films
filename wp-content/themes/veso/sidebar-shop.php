<?php
if ( ! is_active_sidebar( 'shop' ) ) {
	return;
}

?>

<aside id="secondary" class="widget-area">
	<?php dynamic_sidebar( 'shop' ); ?>
</aside>