<?php
?>
<div class="wrap ays_results_list_table">
    <h1 class="wp-heading-inline">
        <?php
        echo __(esc_html(get_admin_page_title()),$this->plugin_name);
        ?>
    </h1>
    <a href="https://ays-pro.com/wordpress/quiz-maker/">
        <button class="disabled-button" style="float: right; margin-right: 5px;" title="This property aviable only in pro version" ><?php echo __('Export',$this->plugin_name)?></button>
    </a>
    <div class="nav-tab-wrapper">
        <a href="#tab1" class="nav-tab nav-tab-active"><?php echo __('Results',$this->plugin_name)?></a>
        <a href="#tab2" class="nav-tab"><?php echo __('Statistics',$this->plugin_name)?></a>
        <a href="#tab3" class="nav-tab"><?php echo __('Leaderboard',$this->plugin_name)?></a>
    </div>
    <div id="tab1" class="ays-quiz-tab-content ays-quiz-tab-content-active">
        <div id="poststuff">
            <div id="post-body" class="metabox-holder">
                <div id="post-body-content">
                    <div class="meta-box-sortables ui-sortable">
                        <?php
                            $this->results_obj->views();
                        ?>
                        <form method="post">
                            <?php
                            $this->results_obj->prepare_items();
                            $this->results_obj->search_box('Search', $this->plugin_name);
                            $this->results_obj->display();
                            ?>
                        </form>
                    </div>
                </div>
            </div>
            <br class="clear">
        </div>
    </div>

    <div id="tab2" class="ays-quiz-tab-content">
        <br>
        <div class="row" style="margin:0;">
            <div class="col-sm-12">
                <div class="pro_features">
                    <div>
                        <p>
                            <?php echo __("This feature is available only in ", $this->plugin_name); ?>
                            <a href="https://ays-pro.com/wordpress/quiz-maker/" target="_blank" title="PRO feature"><?php echo __("PRO version!!!", $this->plugin_name); ?></a>
                        </p>
                    </div>
                </div>
                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/chart_screen.png'?>" alt="Statistics" style="width:100%;">
            </div>
        </div>
    </div>
    
    <div id="tab3" class="ays-quiz-tab-content">
        <p class="ays-subtitle"><?php echo __('Leaderboard',$this->plugin_name)?></p>
        <hr>
        <?php 
            global $wpdb;
            $sql = "SELECT quiz_id, user_id, AVG(score) AS avg_score 
                    FROM {$wpdb->prefix}aysquiz_reports 
                    WHERE user_id != 0 
                    GROUP BY user_id  
                    ORDER BY avg_score DESC LIMIT 10";
            $result = $wpdb->get_results($sql, 'ARRAY_A');

            $c = 1;
            $content = "<div class='ays_lb_container'>
            <ul class='ays_lb_ul' style='width: 100%;'>
                <li class='ays_lb_li'>
                    <div class='ays_lb_pos'>Pos.</div>
                    <div class='ays_lb_user'>Name</div>
                    <div class='ays_lb_score'>Score</div>
                </li>";

            foreach ($result as $val) {
                $score = round($val['avg_score'], 2);
                $user = get_user_by('id', $val['user_id']);
                $user_name = $user->data->display_name ? $user->data->display_name : $user->user_login;

                $content .= "<li class='ays_lb_li'>
                                <div class='ays_lb_pos'>".$c.".</div>
                                <div class='ays_lb_user'>".$user_name."</div>
                                <div class='ays_lb_score'>".$score." %</div>
                            </li>";
                $c++;   
            }
            $content .= "</ul>
            </div>";
            echo $content;
        ?>
    </div>
    
    <div id="ays-results-modal" class="ays-modal">
        <div class="ays-modal-content">
            <div class="ays-quiz-preloader">
                <img class="loader" src="<?php echo AYS_QUIZ_ADMIN_URL; ?>/images/loaders/3-1.svg">
            </div>
            <div class="ays-modal-header">
                <span class="ays-close" id="ays-close-results">&times;</span>
                <h2><?php echo __("Results for", $this->plugin_name); ?></h2>
            </div>
            <div class="ays-modal-body" id="ays-results-body">
            </div>
        </div>
    </div>
    
</div>
