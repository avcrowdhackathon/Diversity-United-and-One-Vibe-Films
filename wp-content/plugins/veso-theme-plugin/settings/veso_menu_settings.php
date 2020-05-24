<?php

add_action('acf/input/admin_head', 'my_acf_admin_head');

function my_acf_admin_head() {
	
	?>
	<script type="text/javascript">
	(function($) {
		$(document).ready(function(){
			$('.acf-field-5953501be2b38 .acf-input').append( $('#postdivrich') );
		});
		
	})(jQuery);
	</script>
	<style type="text/css">
		.acf-field #wp-content-editor-tools {
			background: transparent;
			padding-top: 0;
		}
	</style>
	<?php    
	
}
