<?php
function veso_get_share_links($data = array()) {
	if(function_exists('get_field')) {
		$list = get_field('veso_social_shares', 'option');
	} else {
		$list = array('facebook', 'twitter', 'google_plus');
	}
	if(isset($list) && $list !== '') {
		$s = array_flip($list);
		$title = $url = $description = $image = '';
		$title       = isset( $data['title'] ) ? $data['title'] : '';
		$url         = isset( $data['url'] ) ? $data['url'] : '';
		$description = isset( $data['description'] ) ? $data['description'] : '';
		$image       = isset( $data['image'] ) ? $data['image'] : '';
		if(is_array($image)) {
			if(isset($image[0])) {
				$image = $image[0];
			} else {
				$image = '';
			}
		}
		$facebookParams = array(
			'u' => $url,
		);
		$twitterParams = array(
			'text' => $title,
		);
		$googleParams = array(
			'url' => $url,
		);
		$tumblrParams = array(
			'url' => $url,
			'name' => $title,
			'description' => $description
		);
		$pinterestParams = array(
			'media' => $image,
			'url' => $url,
			'is_video' => '0',
			'description' => $description,
		);
		$facebookUrl = 'https://www.facebook.com/sharer/sharer.php?'.http_build_query($facebookParams);
		$twitterUrl = 'https://www.twitter.com/share?'.http_build_query($twitterParams);
		$googleUrl = 'https://plus.google.com/share?'.http_build_query($googleParams);
		$tumblrUrl = 'http://www.tumblr.com/share/link?'.http_build_query($tumblrParams);
		$pinterestUrl = 'https://pinterest.com/pin/create/bookmarklet/?'.http_build_query($pinterestParams);
		$output = '<ul class="social-shares">
		'.(((isset($s['facebook']))) ? '<li><a href="'.esc_url($facebookUrl).'" class="no-rd social-share"><i class="fa fa-facebook"></i></a></li>' : '').''.(((isset($s['twitter']))) ? '<li><a href="'.esc_url($twitterUrl).'" class="no-rd social-share"><i class="fa fa-twitter"></i></a></li>' : '').''.(((isset($s['google_plus']))) ? '<li><a href="'.esc_url($googleUrl).'" class="no-rd social-share"><i class="fa fa-google-plus"></i></a></li>' : '').''.(((isset($s['tumblr']))) ? '<li><a href="'.esc_url($tumblrUrl).'" class="no-rd social-share"><i class="fa fa-tumblr"></i></a></li>' : '').''.(((isset($s['pinterest']))) ? '<li><a href="'.esc_url($pinterestUrl).'" class="no-rd social-share"><i class="fa fa-pinterest"></i></a></li>' : '');
		$output .= '</ul>';
		return trim($output);
	}
}