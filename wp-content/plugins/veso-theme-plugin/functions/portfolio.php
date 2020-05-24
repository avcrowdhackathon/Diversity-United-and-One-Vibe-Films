<?php

function veso_get_portfolio_markup($src, $bg = false, $hover = 'hover1', $text_hover = false, $post, $gallery = 'lightbox') {
	if($text_hover) {
		$hover_class = 'text-on-hover';
	} else {
		$hover_class = 'text-below';
	}

	if(get_field('veso_portfolio_custom_link', $post->ID) == '') {
		$url = get_permalink($post->ID);
	} else {
		$url = get_field('veso_portfolio_custom_link', $post->ID);
	}

	$portfolio_gallery = get_field('veso_portfolio_gallery', $post->ID);

	$images = $link = $figcaption = '';
	if($gallery == 'lightbox') {
		if($portfolio_gallery) {
			$images = '<div class="gallery-lightbox">';
			foreach ($portfolio_gallery as $image) {
				$caption = get_the_excerpt($image['ID']);
				$img = $image['url'];
				$width = $image['sizes']['large-width'];
				$height = $image['sizes']['large-height'];
				if($caption != '') {
					$figcaption = '<figure><figcaption class="hide">'.$caption.'</figcaption></figure>';
				}
				$images .= '<a href="'.esc_url($img).'" data-size="'.$width.'x'.$height.'">'.$figcaption.'</a>';
			}
			$images .= '</div>';
		} else {
			$img_attr = wp_get_attachment_image_src(get_post_thumbnail_id( $post ), 'full');
			$caption = get_the_excerpt(get_post_thumbnail_id( $post ));
			if($caption != '') {
				$figcaption = '<figure><figcaption class="hide">'.$caption.'</figcaption>';
			}
			$link = '<a class="portfolio-link single-image-lightbox" href="'.esc_url($src).'" data-size="'.$img_attr[1].'x'.$img_attr[2].'">'.$figcaption.'</a>';
		}
		
	} elseif ($gallery == 'portfolio_page') {
		$link = '<a class="portfolio-link" href="'.$url.'"></a>';
	} else {
		$img_attr = wp_get_attachment_image_src(get_post_thumbnail_id( $post ), 'full');
		$caption = get_the_excerpt(get_post_thumbnail_id( $post ));
		if($caption != '') {
			$figcaption = '<figure><figcaption class="hide">'.$post->post_title.'</figcaption></figure>';
		}
		$link = '<a class="portfolio-link images-lightbox" href="'.esc_url($src).'" data-size="'.$img_attr[1].'x'.$img_attr[2].'">'.$figcaption.'</a>';
	}
	$output = '<div class="veso-portfolio-item '.esc_attr($hover_class).' '.esc_attr($hover).' open-'.esc_attr( $gallery ).'">'.$link;
	$bg_hover = get_field('veso_portfolio_bg', $post->ID);
	$color_hover = "";
	if($bg_hover !== '' && $hover == 'hover4') {
		$bg_color = 'style="background-color: '.$bg_hover.'"';
		if($text_hover) {
			$color_hover = 'style="color: '.get_field('veso_portfolio_color', $post->ID).'"';
		}
	} else {
		$bg_color = '';
	}
	if($bg) {
		$output .= '<div class="image-wrapper"><div class="portfolio-hover-img" '.$bg_color.'></div><span class="portfolio-img b-lazy" data-src="'.$src.'"></span></div>';
	} else {
		$post_thumbnail_id = get_post_thumbnail_id( $post );

		$image = wp_get_attachment_image_src($post_thumbnail_id, 'full');
		if(is_array($image)) {
			$imgUrl = $image[0];
			$width = $image[1];
			$height = $image[2];
			$output .= '<div class="image-wrapper"><div class="portfolio-hover-img" '.$bg_color.'></div><img class="b-lazy" alt="" height="'.$height.'" width="'.$width.'" src="'.veso_image_preloader($width,$height).'" data-src="'.esc_html($src).'"></div>';
		}
	}

	$categories_list = '';
	$postCats = get_the_terms( $post->ID, 'veso_portfolio_categories' );
	if (is_array($postCats)) {
		foreach ($postCats as $c => $category) {
			$categories_list .= '<li><a class="veso-hover-text" href="' . esc_url(get_category_link($category->term_id)) . '" title="' . esc_attr($category->name) . '">' . esc_html($category->name) . '</a></li>';
		}
	} 

	$output .= '
		<div class="portfolio-text" '.$color_hover.'>
			<h3 class="portfolio-title" data-title="'.esc_attr($post->post_title).'"><span>'.$post->post_title.'</span></h3>
			<ul class="portfolio-category">
				'.$categories_list.'
			</ul>
		</div>
		'.$images.'
	</div>';

	return $output; 
}