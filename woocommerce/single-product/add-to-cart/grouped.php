<?php
/**
 * Grouped product add to cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $product;

// Put grouped products into an array
$grouped_products = array();
$quantites_required = false;

foreach ( $product->get_children() as $child_id ) {
	$child_product = $product->get_child( $child_id );

	if ( ! $child_product->is_sold_individually() && ! $child_product->is_type('external') )
		$quantites_required = true;

	$grouped_products[] = array(
		'product' => $child_product,
		'availability' => $child_product->get_availability()
	);
}
?>

<?php do_action('woocommerce_before_add_to_cart_form'); ?>

<form action="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="cart" method="post" enctype='multipart/form-data'>
	<table cellspacing="0" class="group_table">
		<tbody>
			<?php foreach ( $grouped_products as $child_product ) : ?>
				<tr>
					<td>
						<?php if ( $child_product['product']->is_type('external') ) : ?>

							<a href="<?php echo esc_url( $child_product['product']->get_product_url() ); ?>" rel="nofollow" class="button alt"><?php echo apply_filters('single_add_to_cart_text', esc_html( $child_product['product']->get_button_text() ), 'external'); ?></a>

						<?php elseif ( ! $quantites_required ) : ?>

							<a href="<?php echo esc_url( $child_product['product']->add_to_cart_url() ); ?>" rel="nofollow" class="single_add_to_cart_button button alt"><?php echo apply_filters( 'single_add_to_cart_text', __( 'Add to cart', 'woocommerce' ), $child_product['product']->product_type ); ?></a>

						<?php else : ?>

							<?php woocommerce_quantity_input( array( 'input_name' => 'quantity['.$child_product['product']->id.']', 'input_value' => '0' ) ); ?>

						<?php endif; ?>
					</td>

					<td class="label"><label for="product-<?php echo $child_product['product']->id; ?>"><?php

						if ($child_product['product']->is_visible())
							echo '<a href="' . get_permalink( $child_product['product']->id ) . '">' . $child_product['product']->post->post_title . '</a>';
						else
							echo $child_product['product']->post->post_title;

					?></label></td>

					<?php do_action ( 'woocommerce_grouped_product_list_before_price', $child_product['product'] ); ?>

					<td class="price"><?php echo $child_product['product']->get_price_html(); ?>
					<?php echo apply_filters( 'woocommerce_stock_html', '<small class="stock '.$child_product['availability']['class'].'">'.$child_product['availability']['availability'].'</small>', $child_product['availability']['availability'] ); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php if ( $quantites_required ) : ?>

		<?php do_action('woocommerce_before_add_to_cart_button'); ?>

		<button type="submit" class="single_add_to_cart_button button alt"><?php echo apply_filters('single_add_to_cart_text', __( 'Add to cart', 'woocommerce' ), $product->product_type); ?></button>

		<?php do_action('woocommerce_after_add_to_cart_button'); ?>

	<?php endif; ?>

</form>

<?php do_action('woocommerce_after_add_to_cart_form'); ?>