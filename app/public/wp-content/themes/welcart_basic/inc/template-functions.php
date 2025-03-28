<?php
/**
 * Template Functions
 *
 * @package Welcart
 * @subpackage Welcart_Basic
 */

/**
 * Get Cart Url
 *
 * @return string
 */
function welcart_basic_get_cart_url() {
	global $usces;
	$cart_url = USCES_CART_URL . $usces->delim;
	return $cart_url;
}

/**
 * Is Cart Page
 *
 * @return bool
 */
function welcart_basic_is_cart_page() {
	global $usces;
	if ( $usces->is_cart_page( $_SERVER['REQUEST_URI'] ) ) {
		if ( 'search_item' === $usces->page ) {
			return false;
		}
		return true;
	}
	return false;
}

/**
 * Is Poplink Page
 *
 * @return bool
 */
function welcart_basic_is_poplink_page() {
	$wcpl = get_option( 'wcpl' );
	if ( empty( $wcpl ) || is_admin() ) {
		return false;
	}

	global $wp_query;
	$flag = false;
	if ( isset( $wcpl['setup']['enabled_page'] ) ) {
		foreach ( (array) $wcpl['setup']['enabled_page'] as $enabled_page ) {
			if ( $wp_query->$enabled_page ) {
				$flag = true;
			}
		}
	}
	return $flag;
}

/**
 * Is Member Page
 *
 * @return bool
 */
function welcart_basic_is_member_page() {
	global $usces;
	if ( $usces->is_member_page( $_SERVER['REQUEST_URI'] ) ) {
		if ( 'search_item' === $usces->page ) {
			return false;
		}
		return true;
	}
	return false;
}

/**
 * Get Item Chargingtype
 *
 * @param int $post_id post_id.
 * @return mixed
 */
function welcart_basic_get_item_chargingtype( $post_id ) {
	global $usces;
	$charging = $usces->getItemChargingType( $post_id );
	return $charging;
}

/**
 * Get Item Division
 *
 * @param int $post_id post_id.
 * @return string
 */
function welcart_basic_get_item_division( $post_id ) {
	global $usces;
	$division = $usces->getItemDivision( $post_id );
	if ( ! defined( 'WCEX_DLSELLER' ) ) {
		$division = 'shipped';
	}
	return $division;
}

/**
 * Have Ex Order
 *
 * @return bool
 */
function welcart_basic_have_ex_order() {
	$ex_order = false;
	if ( defined( 'WCEX_DLSELLER' ) ) {
		$ex_order = ( ! dlseller_have_dlseller_content() && ! dlseller_have_continue_charge() ) ? false : true;
	} elseif ( defined( 'WCEX_AUTO_DELIVERY' ) ) {
		$ex_order = wcad_have_regular_order();
	}
	return $ex_order;
}

/**
 * Have Shipped
 *
 * @return bool
 */
function welcart_basic_have_shipped() {
	$shipped = true;
	if ( defined( 'WCEX_DLSELLER' ) ) {
		$shipped = dlseller_have_shipped();
	}
	return $shipped;
}

/**
 * DL Seller Content
 *
 * @return bool
 */
function welcart_basic_have_dlseller_content() {
	if ( function_exists( 'dlseller_has_terms' ) ) {
		$dlseller_content = ( defined( 'WCEX_DLSELLER' ) && dlseller_have_dlseller_content() && dlseller_has_terms() ) ? true : false;
	} else {
		$dlseller_content = ( defined( 'WCEX_DLSELLER' ) && dlseller_have_dlseller_content() ) ? true : false;
	}
	return $dlseller_content;
}

/**
 * Auto Delivery History
 */
function welcart_basic_autodelivery_history() {
	$autodelivery_history = '';
	if ( defined( 'WCEX_AUTO_DELIVERY' ) ) {
		$autodelivery_history = wcad_autodelivery_history( 'return' );
	}
	if ( ! empty( $autodelivery_history ) ) {
		echo wp_kses_post( $autodelivery_history );
	}
}

/**
 * Single page for Auto Delivery
 * usces_action_single_item_outform
 */
function welcart_basic_action_single_item_outform() {
	global $post, $usces;

	if ( 'regular' === $usces->getItemChargingType( $post->ID ) ) :
		if ( usces_have_zaiko_anyone( $post->ID ) ) :
			$product      = wel_get_product( $post->ID );
			$regular_unit = $product['wcad_regular_unit'];
			if ( 'day' === $regular_unit ) {
				$regular_unit_name = __( 'Daily', 'autodelivery' );
			} elseif ( 'month' === $regular_unit ) {
				$regular_unit_name = __( 'Monthly', 'autodelivery' );
			} else {
				$regular_unit_name = '';
			}
			$regular_interval  = $product['wcad_regular_interval'];
			$regular_frequency = $product['wcad_regular_frequency'];

			usces_the_item();
			ob_start();
			?>
			<div id="wc_regular">
				<p class="wcr_tlt"><?php esc_html_e( 'Regular Purchase', 'autodelivery' ); ?></p>
				<div class="field">
					<table class="autodelivery">
						<tr>
							<th><?php echo apply_filters( 'wcad_filter_item_single_label_interval', __( 'Interval', 'autodelivery' ) ); ?></th>
							<td><?php echo esc_html( $regular_interval ); ?><?php echo esc_html( $regular_unit_name ); ?></td>
						</tr>
					<?php if ( 1 < (int) $regular_frequency ) : ?>
						<tr>
							<th><?php echo apply_filters( 'wcad_filter_item_single_label_frequency', __( 'Frequency', 'autodelivery' ) ); ?></th>
							<td><?php echo esc_html( $regular_frequency ); ?><?php esc_html_e( 'times', 'autodelivery' ); ?></td>
						</tr>
					<?php else : ?>
						<tr>
							<th><?php echo apply_filters( 'wcad_filter_item_single_label_frequency_free', __( 'Frequency', 'autodelivery' ) ); ?></th>
							<td><?php echo apply_filters( 'wcad_filter_item_single_value_frequency_free', __( 'Free cycle', 'autodelivery' ) ); ?></td>
						</tr>
					<?php endif; ?>
					</table>
				</div>

				<form action="<?php echo esc_url( USCES_CART_URL ); ?>" method="post">

				<?php while ( usces_have_skus() ) : ?>
					<div class="skuform">
					<?php if ( '' !== usces_the_itemSkuDisp( 'return' ) ) : ?>
						<div class="skuname"><?php usces_the_itemSkuDisp(); ?></div>
					<?php endif; ?>

					<?php if ( usces_is_options() ) : ?>
						<dl class="item-option">
						<?php while ( usces_have_options() ) : ?>
							<dt><?php usces_the_itemOptName(); ?></dt>
							<dd><?php wcad_the_itemOption( usces_getItemOptName(), '' ); ?></dd>
						<?php endwhile; ?>
						</dl>
					<?php endif; ?>

						<?php usces_the_itemGpExp(); ?>

						<div class="field">
							<div class="zaikostatus"><?php esc_html_e( 'stock status', 'usces' ); ?> : <?php usces_the_itemZaikoStatus(); ?></div>
							<div class="field_price">
							<?php if ( usces_the_itemCprice( 'return' ) > 0 ) : ?>
								<span class="field_cprice"><?php usces_the_itemCpriceCr(); ?></span>
							<?php endif; ?>
								<?php wcad_the_itemPriceCr(); ?><?php usces_guid_tax(); ?>
							</div>
							<?php wcad_crform_the_itemPriceCr_taxincluded(); ?>
						</div>

					<?php if ( ! usces_have_zaiko() ) : ?>
						<div class="itemsoldout"><?php echo apply_filters( 'usces_filters_single_sku_zaiko_message', __( 'At present we cannot deal with this product.', 'welcart_basic' ) ); ?></div>
					<?php else : ?>
						<div class="c-box">
							<span class="quantity"><?php esc_html_e( 'Quantity', 'usces' ); ?><?php wcad_the_itemQuant(); ?></span><span class="unit_regular"><?php usces_the_itemSkuUnit(); ?></span>
							<span class="cart-button"><?php wcad_the_itemSkuButton( '&#xf07a;&nbsp;&nbsp;' . __( 'Apply for a regular purchase', 'autodelivery' ), 0 ); ?></span>
						</div>
					<?php endif; ?>
						<div class="error_message"><?php usces_singleitem_error_message( $post->ID, usces_the_itemSku( 'return' ) ); ?></div>
					</div><!-- .skuform -->
				<?php endwhile; ?>
				<?php echo apply_filters( 'wcad_single_item_multi_sku_after_field', null ); ?>
				<?php do_action( 'wcad_action_single_item_inform' ); ?>
				</form>
			</div>
			<?php
			$html = ob_get_contents();
			ob_end_clean();

			$html = apply_filters( 'welcart_basic_filter_single_item_autodelivery', $html );
			echo $html;
		endif;
	endif;
}

/**
 * Single page for Autodelivery and SKU Select
 * wcex_sku_select_filter_single_item_autodelivery
 *
 * @param string $html HTML form.
 * @return string
 */
function welcart_basic_single_item_autodelivery_sku_select( $html ) {
	global $post, $usces;

	if ( 'regular' === $usces->getItemChargingType( $post->ID ) ) :
		if ( usces_have_zaiko_anyone( $post->ID ) ) :
			$product      = wel_get_product( $post->ID );
			$regular_unit = $product['wcad_regular_unit'];
			if ( 'day' === $regular_unit ) {
				$regular_unit_name = __( 'Daily', 'autodelivery' );
			} elseif ( 'month' === $regular_unit ) {
				$regular_unit_name = __( 'Monthly', 'autodelivery' );
			} else {
				$regular_unit_name = '';
			}

			$regular_interval  = $product['wcad_regular_interval'];
			$regular_frequency = $product['wcad_regular_frequency'];

			usces_the_item();
			ob_start();
			?>
			<div id="wc_regular">
				<p class="wcr_tlt"><?php esc_html_e( 'Regular Purchase', 'autodelivery' ); ?></p>
				<div class="field">
					<table class="autodelivery">
						<tr>
							<th><?php echo esc_html( apply_filters( 'wcad_filter_item_single_label_interval', __( 'Interval', 'autodelivery' ) ) ); ?></th>
							<td><?php echo esc_html( $regular_interval ); ?><?php echo esc_html( $regular_unit_name ); ?></td>
						</tr>
					<?php if ( 1 < (int) $regular_frequency ) : ?>
						<tr>
							<th><?php echo esc_html( apply_filters( 'wcad_filter_item_single_label_frequency', __( 'Frequency', 'autodelivery' ) ) ); ?></th>
							<td><?php echo esc_html( $regular_frequency ); ?><?php esc_html_e( 'times', 'autodelivery' ); ?></td>
						</tr>
					<?php else : ?>
						<tr>
							<th><?php echo esc_html( apply_filters( 'wcad_filter_item_single_label_frequency_free', __( 'Frequency', 'autodelivery' ) ) ); ?></th>
							<td><?php echo esc_html( apply_filters( 'wcad_filter_item_single_value_frequency_free', __( 'Free cycle', 'autodelivery' ) ) ); ?></td>
						</tr>
					<?php endif; ?>
					</table>
				</div>

				<form action="<?php echo esc_url( USCES_CART_URL ); ?>" method="post">
					<div class="skuform" id="skuform_regular">

						<?php wcex_auto_delivery_sku_select_form(); ?>

					<?php if ( usces_is_options() ) : ?>
						<dl class="item-option">
							<?php while ( usces_have_options() ) : ?>
							<dt><?php usces_the_itemOptName(); ?></dt>
							<dd><?php wcad_the_itemOption( usces_getItemOptName(), '' ); ?></dd>
							<?php endwhile; ?>
						</dl>
					<?php endif; ?>

						<?php wcex_sku_select_the_itemGpExp( '', true ); ?>

						<div class="field">
							<div class="zaikostatus"><?php esc_html_e( 'stock status', 'usces' ); ?> : <span class="ss_stockstatus_regular"><?php usces_the_itemZaikoStatus(); ?></span></div>
							<div class="field_price">
							<?php if ( usces_the_itemCprice( 'return' ) > 0 ) : ?>
								<span class="field_cprice ss_cprice_regular"><?php usces_the_itemCpriceCr(); ?></span>
							<?php endif; ?>
								<span class="sell_price ss_price_regular"><?php wcad_the_itemPriceCr(); ?></span><?php usces_guid_tax(); ?>
							</div>
							<?php wcex_sku_select_crform_the_itemRPriceCr_taxincluded(); ?>
						</div>

						<div id="checkout_box">
							<div class="itemsoldout"><?php echo esc_html( apply_filters( 'usces_filters_single_sku_zaiko_message', __( 'At present we cannot deal with this product.', 'welcart_basic' ) ) ); ?></div>
							<div class="c-box">
								<span class="quantity"><?php esc_html_e( 'Quantity', 'usces' ); ?><?php wcad_the_itemQuant(); ?></span><span class="unit_regular"><?php usces_the_itemSkuUnit(); ?></span>
								<span class="cart-button"><?php wcad_the_itemSkuButton( '&#xf07a;&nbsp;&nbsp;' . __( 'Apply for a regular purchase', 'autodelivery' ), 0 ); ?></span>
							</div>
						</div>
						<div class="error_message"><?php usces_singleitem_error_message( $post->ID, usces_the_itemSku( 'return' ) ); ?></div>
						<div class="wcss_loading"></div>
					</div><!-- .skuform -->
					<?php echo esc_html( apply_filters( 'welcart_single_item_multi_sku_after_field', null ) ); ?>
					<?php do_action( 'wcad_action_single_item_inform' ); ?>
				</form>
			</div>
			<?php
			$html = ob_get_contents();
			ob_end_clean();
		endif;
	endif;

	$html = apply_filters( 'welcart_basic_filter_single_item_autodelivery_sku_select', $html );
	return $html;
}

/**
 * Is Available Point
 *
 * @return bool
 */
function welcart_basic_is_available_point() {
	$res = true;
	if ( function_exists( 'usces_is_available_point' ) ) {
		$res = usces_is_available_point();
	} else {
		if ( defined( 'WCEX_DLSELLER_VERSION' ) && function_exists( 'dlseller_have_continue_charge' ) ) {
			if ( dlseller_have_continue_charge() ) {
				$res = false;
			}
		}
	}
	return $res;
}

/**
 * Password Policy Message
 */
function welcart_basic_password_policy_message() {
	if ( function_exists( 'usces_password_policy_message' ) ) {
		usces_password_policy_message();
	}
}

if ( ! function_exists( 'welcart_basic_soldout_label' ) ) {
	/**
	 * Soldout label
	 *
	 * @param int    $post_id Post ID.
	 * @param string $out Return value or echo.
	 * @return string|void
	 */
	function welcart_basic_soldout_label( $post_id, $out = '' ) {
		global $usces;

		$stock_status = __( 'Sold Out', 'usces' );
		$skus         = wel_get_skus( $post_id );
		if ( 1 === count( (array) $skus ) ) {
			$stock = $skus[0]['stock'];
			if ( 2 !== (int) $stock ) {
				$stock_status = $usces->zaiko_status[ $stock ];
			}
		}
		$stock_status = apply_filters( 'welcart_basic_filter_soldout_label', $stock_status, $post_id, $skus );
		if ( 'return' === $out ) {
			return $stock_status;
		} else {
			echo esc_html( $stock_status );
		}
	}
}
