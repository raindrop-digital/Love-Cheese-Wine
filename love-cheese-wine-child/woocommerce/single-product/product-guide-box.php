<?php
/**
 * Product Box Guide
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="container-product py-4" id="product-box">
    <div class="container-product-inner p-2">
        <div class="bs-row">
            <?php
        // Check rows exists.
            if( have_rows('in_the_box_section') ): while ( have_rows('in_the_box_section') ) : the_row(); 
            
            $imagebox = get_sub_field('in_the_box_image');  ?>

            <div class="bs-col-xs-12 bs-col-md-7 bs-flex bs-flex-" id="box-image">
            <?php if( !empty( $imagebox ) ): ?>
            <img src="<?php echo esc_url($imagebox['url']); ?>" alt="<?php echo esc_attr($imagebox['alt']); ?>" />
            <?php endif; ?>
        </div>
        <div class="bs-col-xs-12 bs-col-md-5 bs-flex bs-flex-direction-col bs-justify-content-center" id="box-content">
            <h3>Guide to your box</h3>
        <ul class="list__counter">
        <?php  if( have_rows('in_the_box_includes') ): while ( have_rows('in_the_box_includes') ) : the_row();   
            
                $repeaterbox = get_sub_field('in_the_box_value'); ?>

                    <li class="list__counter-item"><?php echo $repeaterbox; ?></li>

            <?php endwhile; endif; ?>
            </ul>
            </div>
            <?php endwhile; endif; ?>
        </div>
    </div>
</div>