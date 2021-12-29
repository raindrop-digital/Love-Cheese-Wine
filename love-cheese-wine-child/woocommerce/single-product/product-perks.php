<?php
/**
 * Product Perks
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="container-product py-4" id="product-perks">
    <div class="container-product-inner p-2">
        <div class="bs-row">
        <div class="bs-col-xs-12 bs-col-md-7 pb-2">
            <h3>Perks of subscribing</h3>
            <?php if( get_field('faq_intro') ): ?>
            <p><?php the_field('faq_intro'); ?></p>
            <?php endif; ?>
        </div>
        <div class="bs-col-xs-12">
            <?php
            // Check rows exists.
            if( have_rows('subscriber_perks') ):
 	
        // Loop through rows.
        while ( have_rows('subscriber_perks') ) : the_row();
		
        // Content variables
		$perkheader = get_sub_field('subscriber_perk_title');
		$perkcontent = get_sub_field('subscriber_perk_content');
        ?>
            <div class="bs-accordion js-accordion">
                <div class="bs-accordion-item js-accordion-item">
                    <div class="bs-accordion-header js-accordion-header">
                        <?php echo $perkheader; ?>
                    </div>
                    <div class="bs-accordion-body js-accordion-body">
                        <div class="bs-accordion-body-contents">
                            <?php echo $perkcontent; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
             endwhile; //End the loop ?>
        </div>
        <?php else :
        endif; ?>
    </div>
    </div>
</div>
