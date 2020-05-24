<?php
include_once VESO_PLUG_DIR.'shortcodes/veso-additional-info.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-blog.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-button.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-carousel.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-cascade-images.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-column.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-countdown.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-counter.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-cta.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-empty-space.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-gallery-carousel.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-gallery-masonry.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-gmaps.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-interactive-box.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-newsletter.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-pie.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-portfolio-carousel.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-portfolio-element.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-portfolio-masonry.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-presentation-box.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-pricing-table.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-progress-bar.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-row.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-shares.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-slider.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-social-profiles.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-split-slider.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-team.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-toggle.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-tta-accordion.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-tta-tabs.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-tta-tour.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-video-lightbox.php';
include_once VESO_PLUG_DIR.'shortcodes/veso-fullscreen-links.php';

class Veso_Shortcodes { 
	function __construct() {
		if(!function_exists('vc_map'))
			return false;

		new Veso_Additional_Info;
		new Veso_Blog;
		new Veso_Button;
		new Veso_Carousel;
		new Veso_Cascade_Images;
		new Veso_Countdown;
		new Veso_Counter;
		new Veso_Gallery_Carousel;
		new Veso_Gallery_Masonry;
		new Veso_Gmaps;
		new Veso_Interactive_Box;
		new Veso_Newsletter;
		new Veso_Portfolio_Carousel;
		new Veso_Portfolio_Element;
		new Veso_Portfolio_Masonry;
		new Veso_Presentation_Box;
		new Veso_Pricing_Table;
		new Veso_Shares;
		new Veso_Slider;
		new Veso_Social_Profiles;
		new Veso_Split_Slider;
		new Veso_Team;
		new Veso_Video_Lightbox;
		new Veso_Fullscreen_Links;
	}
}


if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
    class WPBakeryShortCode_veso_carousel extends WPBakeryShortCodesContainer {
    }
}
if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
    class WPBakeryShortCode_veso_slider extends WPBakeryShortCodesContainer {
    }
}
// if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
//     class WPBakeryShortCode_veso_split_slider extends WPBakeryShortCodesContainer {
//     }
// }