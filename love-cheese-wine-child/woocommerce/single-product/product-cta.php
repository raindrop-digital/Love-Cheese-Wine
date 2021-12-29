<?php
/**
 * Product CTA
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php if( have_rows('product_cta', 'option') ): ?>
    <?php while( have_rows('product_cta', 'option') ): the_row(); 

        // Get sub field values.
        $ctaimage = get_sub_field('cta_background_image', 'option');
        $ctatext = get_sub_field('cta_title', 'option');
        $ctacontent = get_sub_field('cta_content', 'option');

        ?>

<div class="container-product" id="product-cta" style="background-image: url(<?php echo $ctaimage; ?>);">
<div class="container-product-inner">
    <div class="bs-row">
        <div class="bs-col-xs-12 bs-col-md-7">
            <div class="heading__white mb-xs-1"><h3><?php echo $ctatext; ?></h3></div>
            <div class="paragraph__white mb-xs-1 product-cta-text"><p><?php echo $ctacontent; ?></p></div>
            <div class="btn-cta-wrap"><a href="#singlebasket" class="button btn-cta">Subscribe Now</a></div>
        </div>
    </div>
</div>
</div>
    <?php endwhile; ?>
<?php endif; ?>
