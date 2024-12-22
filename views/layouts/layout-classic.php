<?php defined('ABSPATH') || die(); // Prevent direct access. ?>

<div class="pfaqm-faq-wrapper pfaqm-faq-layout-1">
    <?php
    if(!empty($faqs)):
        foreach( $faqs as $faq ) : 
            ?>
            <div class="pfaqm-faq-item">
                <div class="pfaqm-faq-header">
                    <span class="pfaqm-faq-question"><?php echo esc_html($faq->post_title); ?></span>
                    <span class="pfaqm-faq-icon"></span>
                </div>
                <div class="pfaqm-faq-content" style="overflow: hidden; height: 0; transition: height 0.3s ease;">
                    <div class="pfaqm-faq-answer"><?php echo wp_kses_post($faq->post_content); ?></div>
                </div>
            </div>
        <?php 
        endforeach;
    else:
        echo esc_html__('No FQA Found!', 'product-faq-manager');
    endif;
    ?>
</div>
