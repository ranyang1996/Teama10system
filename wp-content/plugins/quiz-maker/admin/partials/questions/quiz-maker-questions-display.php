<?php
global $wpdb;

$action = ( isset($_GET['action']) ) ? $_GET['action'] : '';
$id     = ( isset($_GET['question']) ) ? $_GET['question'] : null;

if($action == 'duplicate'){
    $this->questions_obj->duplicate_question($id);
}

?>

<div class="wrap ays_questions_list_table">
    <h1 class="wp-heading-inline">
        <?php
            echo __(esc_html(get_admin_page_title()),$this->plugin_name);
            echo sprintf( '<a href="?page=%s&action=%s" class="page-title-action">' . __('Add New', $this->plugin_name) . '</a>', esc_attr( $_REQUEST['page'] ), 'add');
        ?>
    </h1>

    <a href="https://ays-pro.com/wordpress/quiz-maker/" class="disabled-buttons-container" target="_blank" title="<?php echo __('This property aviable only in pro version',$this->plugin_name)?>">
        <button class="disabled-button"><?php echo __('Example',$this->plugin_name)?></button>
        <button class="disabled-button"><?php echo __('Export',$this->plugin_name)?></button>
        <button class="disabled-button"><?php echo __('Import',$this->plugin_name)?></button>
    </a>
    <hr/>
    <div id="poststuff">
        <div id="post-body" class="metabox-holder">
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <?php
                        $this->questions_obj->views();
                    ?>
                    <form method="post">
                        <?php
                            $this->questions_obj->prepare_items();
                            $this->questions_obj->search_box('Search', $this->plugin_name);
                            $this->questions_obj->display();
                        ?>
                    </form>
                </div>
            </div>
        </div>
        <br class="clear">
    </div>
</div>
