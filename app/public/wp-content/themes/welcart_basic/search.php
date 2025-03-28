<?php
/**
 * Search Template
 *
 * @package Welcart
 * @subpackage Welcart_Basic
 */

get_header();
?>

	<section id="primary" class="site-content">
		<div id="content" role="main">

			<header class="page-header">
				<h1 class="page-title">
					<?php printf( __( 'Search Results for: %s', 'welcart_basic' ), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?>
				</h1>
			</header><!-- .page-header -->

		<?php if ( have_posts() ) : ?>

			<div class="search-li type-grid">

			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<div class="itemimg">
						<a href="<?php the_permalink(); ?>">
							<?php usces_the_itemImage( 0, 300, 300 ); ?>
							<?php do_action( 'usces_theme_favorite_icon' ); ?>
						</a>
						<?php welcart_basic_campaign_message(); ?>
					</div>
					<div class="itemprice"><?php usces_the_firstPriceCr(); ?><?php usces_guid_tax(); ?></div>
					<?php usces_crform_the_itemPriceCr_taxincluded(); ?>
					<?php if ( ! usces_have_zaiko_anyone() ) : ?>
					<div class="itemsoldout">
						<?php welcart_basic_soldout_label( get_the_ID() ); ?>
					</div>
					<?php endif; ?>
					<div class="itemname"><a href="<?php the_permalink(); ?>"  rel="bookmark"><?php usces_the_itemName(); ?></a></div>

				</article>
				<?php
			endwhile;
			?>

			</div><!-- .search-li -->

			<?php
			$args           = array(
				'type'      => 'list',
				'prev_text' => __( ' &laquo; ', 'welcart_basic' ),
				'next_text' => __( ' &raquo; ', 'welcart_basic' ),
			);
			$paginate_links = paginate_links( $args );
			if ( $paginate_links ) :
				?>
				<div class="pagination_wrapper">
					<?php echo wp_kses_post( $paginate_links ); ?>
				</div><!-- .pagination_wrapper -->
				<?php
			endif;
			?>

		<?php else : ?>

			<p><?php esc_html_e( 'No posts found.', 'usces' ); ?></p>

		<?php endif; ?>

		</div><!-- #content -->
	</section><!-- #primary -->

<?php
get_sidebar();
get_footer();
