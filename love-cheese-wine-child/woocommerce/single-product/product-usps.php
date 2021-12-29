<?php
/**
 * Product USPs
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="container-product pt-2 py-1">
<div class="container-product-inner" id="product-usps">
    <div class="bs-row">

<?php if( have_rows('product_selling_points', 'option') ): ?>
    <?php while( have_rows('product_selling_points', 'option') ): the_row(); 

        // Get sub field values.
        $deliverymessage = get_sub_field('delivery_message', 'option');
        $packagingmessage = get_sub_field('packaging_message', 'option');
        $productmessage = get_sub_field('product_message', 'option');

        ?>
        <div class="bs-col-xs-12 bs-col-md-4 pb-2 bs-flex bs-flex-direction-row">
            <i class="fad fa-shipping-fast usp-icon"></i>
            <div class="usp-value"><?php echo $deliverymessage; ?></div>
        </div>
        <div class="bs-col-xs-12 bs-col-md-4 pb-2 bs-flex bs-flex-direction-row">
            <i class="fad fa-badge-check usp-icon"></i>
            <div class="usp-value"><?php echo $productmessage; ?></div>
        </div>
        <div class="bs-col-xs-12 bs-col-md-4 pb-2 bs-flex bs-flex-direction-row">
            <i class="fad fa-box-full usp-icon"></i>
            <div class="usp-value"><?php echo $packagingmessage; ?></div>
        </div>
    <?php endwhile; ?>
<?php endif; ?>
</div>
</div>
</div>