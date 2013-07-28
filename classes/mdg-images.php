<?php
// this class will handle image sizes etc...

class MDG_Images {

	// add addition to title at the end of each array if required like so
	// array(381, 381, 'add me to title')
	public $image_sizes = array(

		// Project grid
		array(
			'width' => 220,
			'height'=> 130,
			'used_in' => array(
				'title' => 'Project Grid',
				'link'  => ''
			)
		),

		// Project slider thumbs
		array(
			'width' => 60,
			'height'=> 60,
			'used_in' => array(
				'title' => 'Project Slider thumbs',
				'link'  => ''
			)
		),

		// Project slider full
		array(
			'width' => '',
			'height'=> 475,
			'cropped' => 'false',
			'used_in' => array(
				'title' => 'Project Slider full',
				'link'  => ''
			)
		)
	);

	public function __construct() {

		$this->register_sizes();

		// ajax response to return the reference grid
		add_action( 'wp_ajax_mdg-image-reference-grid', array( $this, 'output_reference_grid' ) );

	}

	public function register_sizes() {
		// register the image sizes with wordpress

		// first set the thumb siz e and make sure that this theme supports thumbs
		if ( function_exists( 'add_theme_support' ) ) {
			add_theme_support( 'post-thumbnails' );
			set_post_thumbnail_size( 140, 140 ); // default Post Thumbnail dimensions
		}

		// now add the sizes
		if ( function_exists( 'add_image_size' ) ) {
			foreach ( $this->image_sizes as $image_size ) {
				$image_size_0 = isset( $image_size['width'] ) ? $image_size['width'] : '';
				$image_size_1 = isset( $image_size['height'] ) ? $image_size['height'] : '';
				$image_size_2 = isset( $image_size['title_override'] ) ? $image_size['title_override'] : '';
				$cropped   = isset( $image_size['cropped'] ) ? $image_size['cropped'] : true;

				add_image_size(
					$image_size_0 . 'x' . $image_size_1 . $image_size_2,   //title
					$image_size_0,            // width
					$image_size_1,            // height
					$cropped            // crop
				);
			}
			//add_image_size( 'homepage-thumb', 220, 180, true ); //(cropped)
		}
	}

	public function output_reference_grid() {

		echo $this->reference_grid_html();
		exit;
	}

	public function reference_grid_html() {
		// this method will take all of the image sizes and return
		// html for a reference grid for admin
		$html = '<ul class="image-reference-grid">';

		foreach ( $this->image_sizes as $image_size ) {
			$style = '';
			$width = 'width: '. $image_size['width'] . 'px !important;';
			$height= ' height: '. $image_size['height'] . 'px !important;';
			$style = $width . $height;

			$html .= '<li style="'.$style.'">';
			$html .= '<p>'.$image_size['width'] . 'x' . $image_size['height'] . '</p>';

			if ( isset( $image_size['used_in']['title'] ) ) {
				$html .= 'Used in: <a href="'.$image_size['used_in']['link'].'" target="_blank">'.$image_size['used_in']['title']. '</a>';
			}

			$html .= '</li>';
		}

		$html .= '</ul>';

		return $html;
	}
}

$mdg_images = new MDG_Images();
