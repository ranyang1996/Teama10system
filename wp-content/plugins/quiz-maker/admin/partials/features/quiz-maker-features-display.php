<?php
/**
 * Created by PhpStorm.
 * User: biggie18
 * Date: 7/30/18
 * Time: 12:08 PM
 */
?>

<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php echo __(esc_html(get_admin_page_title()), $this->plugin_name); ?>
    </h1>

    <div class="ays-quiz-features-wrap">
        <div class="comparison">
            <table>
                <thead>
                    <tr>
                        <th class="tl tl2"></th>
                        <th class="product" style="background:#69C7F1; border-top-left-radius: 5px; border-left:0px;">
                            <span style="display: block"><?php echo __('Personal',$this->plugin_name)?></span>
                            <img src="<?php echo AYS_QUIZ_ADMIN_URL . '/images/avatars/personal_avatar.png'; ?>" alt="Free" title="Free" width="100"/>
                        </th>
                        <th class="product" style="background:#69C7F1;">
                            <span style="display: block"><?php echo  __('Business',$this->plugin_name)?></span>
                            <img src="<?php echo AYS_QUIZ_ADMIN_URL . '/images/avatars/business_avatar.png'; ?>" alt="Business" title="Business" width="100"/>
                        </th>
                        <th class="product" style="border-top-right-radius: 5px; border-right:0px; background:#69C7F1;">
                            <span style="display: block"><?php echo __('Developer',$this->plugin_name)?></span>
                            <img src="<?php echo AYS_QUIZ_ADMIN_URL . '/images/avatars/pro_avatar.png'; ?>" alt="Developer" title="Developer" width="100"/>
                        </th>
                    </tr>
                    <tr>
                        <th></th>
                        <th class="price-info">
                            <div class="price-now"><span><?php echo __('Free',$this->plugin_name)?></span></div>
                        </th>
                        <th class="price-info">
                            <div class="price-now"><span>$29</span>
                            </div>
                        </th>
                        <th class="price-info">
                            <div class="price-now"><span>$69</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td colspan="4"><?php echo __('Support for',$this->plugin_name)?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Support for',$this->plugin_name)?></td>
                        <td><?php echo __('1 site',$this->plugin_name)?></td>
                        <td><?php echo __('5 site',$this->plugin_name)?></td>
                        <td><?php echo __('Unlimited sites',$this->plugin_name)?></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="3"><?php echo __('Upgrade for',$this->plugin_name)?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('Upgrade for',$this->plugin_name)?></td>
                        <td><?php echo __('1 months',$this->plugin_name)?></td>
                        <td><?php echo __('12 months',$this->plugin_name)?></td>
                        <td><?php echo __('Lifetime',$this->plugin_name)?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="4"><?php echo __('Support for',$this->plugin_name)?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Support for',$this->plugin_name)?></td>
                        <td><?php echo __('1 months',$this->plugin_name)?></td>
                        <td><?php echo __('12 months',$this->plugin_name)?></td>
                        <td><?php echo __('Lifetime',$this->plugin_name)?></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="3"><?php echo __('Install on unlimited sites',$this->plugin_name)?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Install on unlimited sites',$this->plugin_name)?></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                <tr>
                    <td> </td>
                    <td colspan="4"><?php echo __('Reports in dashboard',$this->plugin_name)?></td>
                </tr>
                <tr>
                    <td><?php echo __('Reports in dashboard',$this->plugin_name)?></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td> </td>
                    <td colspan="3"><?php echo __('User locations',$this->plugin_name)?></td>
                </tr>
                <tr class="compare-row">
                    <td><?php echo __('User locations',$this->plugin_name)?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo __('Export and import questions',$this->plugin_name)?></td>
                </tr>
                <tr>
                    <td><?php echo __('Export and import questions',$this->plugin_name)?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo __('Export results to CSV',$this->plugin_name)?></td>
                </tr>
                <tr>
                    <td><?php echo __('Export results to CSV',$this->plugin_name)?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo __('Image answers',$this->plugin_name)?></td>
                </tr>
                <tr>
                    <td><?php echo __('Image answers',$this->plugin_name)?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo __('Send email to user',$this->plugin_name)?></td>
                </tr>
                <tr>
                    <td><?php echo __('Send email to user',$this->plugin_name)?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo __('Send email to admin',$this->plugin_name)?></td>
                </tr>
                <tr>
                    <td><?php echo __('Send email to admin',$this->plugin_name)?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo __('Result text according to result',$this->plugin_name)?></td>
                </tr>
                <tr>
                    <td><?php echo __('Result text according to result',$this->plugin_name)?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo __('Results with charts',$this->plugin_name)?></td>
                </tr>
                <tr>
                    <td><?php echo __('Results with charts',$this->plugin_name)?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo __('Send certificate',$this->plugin_name)?></td>
                </tr>
                <tr>
                    <td><?php echo __('Send certificate',$this->plugin_name)?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo __('Custom Attributes',$this->plugin_name)?></td>
                </tr>
                <tr>
                    <td><?php echo __('Custom Attributes',$this->plugin_name)?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo __('Mailchimp integration',$this->plugin_name)?></td>
                </tr>
                <tr>
                    <td><?php echo __('Mailchimp integration',$this->plugin_name)?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo __('Quiz Widget',$this->plugin_name)?></td>
                </tr>
                <tr>
                    <td><?php echo __('Quiz Widget',$this->plugin_name)?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo __('Schedule quiz',$this->plugin_name)?></td>
                </tr>
                <tr>
                    <td><?php echo __('Schedule quiz',$this->plugin_name)?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo __('WooCommerce integration',$this->plugin_name)?></td>
                </tr>
                <tr>
                    <td><?php echo __('WooCommerce integration',$this->plugin_name)?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo __('Campaign Monitor integration',$this->plugin_name)?></td>
                </tr>
                <tr>
                    <td><?php echo __('Campaign Monitor integration',$this->plugin_name)?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo __('Zapier integration',$this->plugin_name)?></td>
                </tr>
                <tr>
                    <td><?php echo __('Zapier integration',$this->plugin_name)?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo __('Slack integration',$this->plugin_name)?></td>
                </tr>
                <tr>
                    <td><?php echo __('Slack integration',$this->plugin_name)?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo __('ActiveCampaign integration',$this->plugin_name)?></td>
                </tr>
                <tr>
                    <td><?php echo __('ActiveCampaign integration',$this->plugin_name)?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo __('User page shortcode',$this->plugin_name)?></td>
                </tr>
                <tr>
                    <td><?php echo __('User page shortcode',$this->plugin_name)?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo __('Email configuration',$this->plugin_name)?></td>
                </tr>
                <tr>
                    <td><?php echo __('Email configuration',$this->plugin_name)?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo __('Question weight/points',$this->plugin_name)?></td>
                </tr>
                <tr>
                    <td><?php echo __('Question weight/points',$this->plugin_name)?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo __('Answer weight/points',$this->plugin_name)?></td>
                </tr>
                <tr>
                    <td><?php echo __('Answer weight/points',$this->plugin_name)?></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo __('Copy content protection',$this->plugin_name)?></td>
                </tr>
                <tr>
                    <td><?php echo __('Copy content protection',$this->plugin_name)?></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4"><?php echo __('PayPal integration',$this->plugin_name)?></td>
                </tr>
                <tr>
                    <td><?php echo __('PayPal integration',$this->plugin_name)?></td>
                    <td><span>–</span></td>
                    <td><span>–</span></td>
                    <td><i class="ays_fa ays_fa_check"></i></td>
                </tr>
                <tr>
                    <td> </td>
                </tr>
                <tr>
                    <td></td>
                    <td><a href="https://wordpress.org/plugins/quiz-maker/" class="price-buy"><?php echo __('Download',$this->plugin_name)?><span class="hide-mobile"></span></a></td>
                    <td><a href="https://ays-pro.com/wordpress/quiz-maker/" class="price-buy"><?php echo __('Buy now',$this->plugin_name)?><span class="hide-mobile"></span></a></td>
                    <td><a href="https://ays-pro.com/wordpress/quiz-maker/" class="price-buy"><?php echo __('Buy now',$this->plugin_name)?><span class="hide-mobile"></span></a></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="ays-quiz-row">
            <div class="ays-quiz-col-4">
                <a href="<?php echo AYS_QUIZ_ADMIN_URL . '/images/features/pro1-compressor.png'; ?>" class="open-lightbox">
                    <img src="<?php echo AYS_QUIZ_ADMIN_URL . '/images/features/pro1-compressor.png'; ?>" width="100%" alt="PRO Feature 1" title="PRO Feature 1" />
                </a>
            </div>
            <div class="ays-quiz-col-4">
                <a href="<?php echo AYS_QUIZ_ADMIN_URL . '/images/features/pro2-compressor.png'; ?>" class="open-lightbox">
                    <img src="<?php echo AYS_QUIZ_ADMIN_URL . '/images/features/pro2-compressor.png'; ?>" width="100%" alt="PRO Feature 2" title="PRO Feature 2" />
                </a>
            </div>
            <div class="ays-quiz-col-4">
                <a href="<?php echo AYS_QUIZ_ADMIN_URL . '/images/features/pro3-compressor.png'; ?>" class="open-lightbox">
                    <img src="<?php echo AYS_QUIZ_ADMIN_URL . '/images/features/pro3-compressor.png'; ?>" width="100%" alt="PRO Feature 3" title="PRO Feature 3" />
                </a>
            </div>
            <div class="ays-quiz-col-4">
                <a href="<?php echo AYS_QUIZ_ADMIN_URL . '/images/features/pro4-compressor.png'; ?>" class="open-lightbox">
                    <img src="<?php echo AYS_QUIZ_ADMIN_URL . '/images/features/pro4-compressor.png'; ?>" width="100%" alt="PRO Feature 4" title="PRO Feature 4" />
                </a>
            </div>
        </div>
    </div>
</div>

