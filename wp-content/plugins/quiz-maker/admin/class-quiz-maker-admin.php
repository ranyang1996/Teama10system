<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Quiz_Maker
 * @subpackage Quiz_Maker/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Quiz_Maker
 * @subpackage Quiz_Maker/admin
 * @author     AYS Pro LLC <info@ays-pro.com>
 */

class Quiz_Maker_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;


    private $quizes_obj;
    private $quiz_categories_obj;
    private $questions_obj;
    private $question_categories_obj;
    private $results_obj;
    private $settings_obj;
    
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version){

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        add_filter('set-screen-option', array(__CLASS__, 'set_screen'), 10, 3);
    }

    /**
     * Register the styles for the admin menu area.
     *
     * @since    1.0.0
     */
    public function admin_menu_styles(){
        
        echo "<style>
            .ays_menu_badge{
                color: #fff;
                display: inline-block;
                font-size: 10px;
                line-height: 14px;
                text-align: center;
                background: #ca4a1f;
                margin-left: 5px;
                border-radius: 20px;
                padding: 2px 5px;
            }

            #adminmenu a.toplevel_page_quiz-maker div.wp-menu-image img {
                width: 32px;
                padding: 1px 0 0;
                transition: .3s ease-in-out;
            }
        </style>";

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles($hook_suffix){
        wp_enqueue_style($this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'css/admin.css', array(), $this->version, 'all');
        wp_enqueue_style('sweetalert-css', '//cdn.jsdelivr.net/npm/sweetalert2@7.26.29/dist/sweetalert2.min.css', array(), $this->version, 'all');

        if (false === strpos($hook_suffix, $this->plugin_name))
            return;

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Quiz_Maker_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Quiz_Maker_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style('wp-color-picker');
        // You need styling for the datepicker. For simplicity I've linked to the jQuery UI CSS on a CDN.
        wp_register_style( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css' );
        wp_enqueue_style( 'jquery-ui' );

        wp_enqueue_style('animate.css', plugin_dir_url(__FILE__) . 'css/animate.css', array(), $this->version, 'all');
        wp_enqueue_style('ays_code_mirror', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.48.4/codemirror.css', array(), $this->version, 'all');
        wp_enqueue_style('ays_quiz_font_awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', array(), $this->version, 'all');
        wp_enqueue_style('ays_quiz_bootstrap', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', array(), $this->version, 'all');
        wp_enqueue_style('ays-qm-select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name.'-data-bootstrap', plugin_dir_url(__FILE__) . 'css/dataTables.bootstrap4.min.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name.'-jquery-datetimepicker', plugin_dir_url(__FILE__) . 'css/jquery-ui-timepicker-addon.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/quiz-maker-admin.css', array(), time()/*$this->version*/, 'all');
        wp_enqueue_style($this->plugin_name."-loaders", plugin_dir_url(__FILE__) . 'css/loaders.css', array(), time()/*$this->version*/, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts($hook_suffix){
        if (false !== strpos($hook_suffix, "plugins.php")){
            wp_enqueue_script( 'sweetalert-js', '//cdn.jsdelivr.net/npm/sweetalert2@7.26.29/dist/sweetalert2.all.min.js', array('jquery'), $this->version, true );
            wp_enqueue_script($this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'js/admin.js', array('jquery'), $this->version, true);
            wp_localize_script($this->plugin_name . '-admin',  'quiz_maker_admin_ajax', array('ajax_url' => admin_url('admin-ajax.php')));
        }

        if (false === strpos($hook_suffix, $this->plugin_name))
            return;

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Quiz_Maker_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Quiz_Maker_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-effects-core');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-datepicker');

        wp_enqueue_media();
        wp_enqueue_script('wp-color-picker-alpha', plugin_dir_url(__FILE__) . 'js/wp-color-picker-alpha.min.js', array('wp-color-picker'), $this->version, true);
        wp_enqueue_script('ays_quiz_popper', plugin_dir_url(__FILE__) . 'js/popper.min.js', array('jquery'), $this->version, false);
        wp_enqueue_script('ays_quiz_bootstrap', plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', array('jquery'), $this->version, false);
        wp_enqueue_script('select2js', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js', array('jquery'), $this->version, true);
        wp_enqueue_script('sweetalert-js', '//cdn.jsdelivr.net/npm/sweetalert2@7.26.29/dist/sweetalert2.all.min.js', array('jquery'), $this->version, true );
        wp_enqueue_script('ays-datatable-js', '//cdn.datatables.net/1.10.19/js/jquery.dataTables.js', array('jquery'), $this->version, true);
		wp_enqueue_script( $this->plugin_name."-db4.min.js", plugin_dir_url( __FILE__ ) . 'js/dataTables.bootstrap4.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name."-jquery.datetimepicker.js", plugin_dir_url( __FILE__ ) . 'js/jquery-ui-timepicker-addon.js', array( 'jquery' ), $this->version, true );
        wp_enqueue_script($this->plugin_name . '-ajax', plugin_dir_url(__FILE__) . 'js/quiz-maker-admin-ajax.js', array('jquery'), $this->version, true);
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'js/quiz-maker-admin.js',
            array('jquery', 'wp-color-picker'),
            $this->version,
            true
        );
        wp_localize_script($this->plugin_name . '-ajax', 'quiz_maker_ajax', array('ajax_url' => admin_url('admin-ajax.php')));

    }

    function codemirror_enqueue_scripts($hook) {
        if (false === strpos($hook, $this->plugin_name)){
            return;
        }
        if(function_exists('wp_enqueue_code_editor')){
            $cm_settings['codeEditor'] = wp_enqueue_code_editor(array(
                'type' => 'text/css',
                'codemirror' => array(
                    'inputStyle' => 'contenteditable',
                    'theme' => 'cobalt',
                )
            ));
            
            wp_localize_script('jquery', 'cm_settings', $cm_settings);
        
            wp_enqueue_script('wp-theme-plugin-editor');
            wp_enqueue_style('wp-codemirror');
        }
    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu(){

        /*
         * Add a settings page for this plugin to the Settings menu.
         *
         * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
         *
         *        Administration Menus: http://codex.wordpress.org/Administration_Menus
         *
         */
        global $wpdb;
        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}aysquiz_reports WHERE `read` = 0";
        $unread_results_count = $wpdb->get_var($sql);        
        $menu_item = ($unread_results_count == 0) ? 'Quiz Maker' : 'Quiz Maker' . '<span class="ays_menu_badge ays_results_bage">' . $unread_results_count . '</span>';
        
        add_menu_page(
            'Quiz Maker', 
            $menu_item, 
            'manage_options', 
            $this->plugin_name, 
            array($this, 'display_plugin_quiz_page'), 
            AYS_QUIZ_ADMIN_URL . '/images/icons/icon-128x128.png', 
            6
        );
    }
    
    public function add_plugin_quizzes_submenu(){
        $hook_quiz_maker = add_submenu_page(
            $this->plugin_name,
            __('Quizzes', $this->plugin_name),
            __('Quizzes', $this->plugin_name),
            'manage_options',
            $this->plugin_name,
            array($this, 'display_plugin_quiz_page')
        );

        add_action("load-$hook_quiz_maker", array($this, 'screen_option_quizes'));
    }

    public function add_plugin_questions_submenu(){
        $hook_questions = add_submenu_page(
            $this->plugin_name,
            __('Questions', $this->plugin_name),
            __('Questions', $this->plugin_name),
            'manage_options',
            $this->plugin_name . '-questions',
            array($this, 'display_plugin_questions_page')
        );

        add_action("load-$hook_questions", array($this, 'screen_option_questions'));
    }

    public function add_plugin_quiz_categories_submenu(){
        $hook_quiz_categories = add_submenu_page(
            $this->plugin_name,
            __('Quiz Categories', $this->plugin_name),
            __('Quiz Categories', $this->plugin_name),
            'manage_options',
            $this->plugin_name . '-quiz-categories',
            array($this, 'display_plugin_quiz_categories_page')
        );

        add_action("load-$hook_quiz_categories", array($this, 'screen_option_quiz_categories'));
    }

    public function add_plugin_questions_categories_submenu(){
        $hook_questions_categories = add_submenu_page(
            $this->plugin_name,
            __('Question Categories', $this->plugin_name),
            __('Question Categories', $this->plugin_name),
            'manage_options',
            $this->plugin_name . '-question-categories',
            array($this, 'display_plugin_question_categories_page')
        );

        add_action("load-$hook_questions_categories", array($this, 'screen_option_questions_categories'));
    }

    public function add_plugin_custom_fields_submenu(){
        $hook_quiz_categories = add_submenu_page(
            $this->plugin_name,
            __('Custom Fields', $this->plugin_name),
            __('Custom Fields', $this->plugin_name),
            'manage_options',
            $this->plugin_name . '-quiz-attributes',
            array($this, 'display_plugin_quiz_attributes_page')
        );
    }

    public function add_plugin_orders_submenu(){
        $hook_quiz_orders = add_submenu_page(
            $this->plugin_name,
            __('Orders', $this->plugin_name),
            __('Orders', $this->plugin_name),
            'manage_options',
            $this->plugin_name . '-quiz-orders',
            array($this, 'display_plugin_orders_page')
        );
    }

    public function add_plugin_results_submenu(){
        global $wpdb;
        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}aysquiz_reports WHERE `read` = 0";
        $unread_results_count = $wpdb->get_var($sql);
        $results_text = __('Results', $this->plugin_name);
        $menu_item = ($unread_results_count == 0) ? $results_text : $results_text . '<span class="ays_menu_badge ays_results_bage">' . $unread_results_count . '</span>';
        $hook_results = add_submenu_page(
            $this->plugin_name,
            $results_text,
            $menu_item,
            'manage_options',
            $this->plugin_name . '-results',
            array($this, 'display_plugin_results_page')
        );

        add_action("load-$hook_results", array($this, 'screen_option_results'));
    }

    public function add_plugin_dashboard_submenu(){
        $hook_quizes = add_submenu_page(
            $this->plugin_name,
            __('Dashboard', $this->plugin_name),
            __('Dashboard', $this->plugin_name),
            'manage_options',
            $this->plugin_name . '-dashboard',
            array($this, 'display_plugin_setup_page')
        );
    }

    public function add_plugin_general_settings_submenu(){
        $hook_settings = add_submenu_page( $this->plugin_name,
            __('General Settings', $this->plugin_name),
            __('General Settings', $this->plugin_name),
            'manage_options',
            $this->plugin_name . '-settings',
            array($this, 'display_plugin_settings_page') 
        );
        add_action("load-$hook_settings", array($this, 'screen_option_settings'));
    }

    public function add_plugin_featured_plugins_submenu(){
        add_submenu_page( $this->plugin_name,
            __('Featured Plugins', $this->plugin_name),
            __('Featured Plugins', $this->plugin_name),
            'manage_options',
            $this->plugin_name . '-featured-plugins',
            array($this, 'display_plugin_featured_plugins_page') 
        );
    }

    public function add_plugin_quiz_features_submenu(){
        add_submenu_page(
            $this->plugin_name,
            __('PRO Features', $this->plugin_name),
            __('PRO Features', $this->plugin_name),
            'manage_options',
            $this->plugin_name . '-quiz-features',
            array($this, 'display_plugin_quiz_features_page')
        );
    }

    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */
    public function add_action_links($links){
        /*
        *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
        */
        $settings_link = array(
            '<a href="' . admin_url('admin.php?page=' . $this->plugin_name) . '">' . __('Settings', $this->plugin_name) . '</a>',
            '<a href="https://quiz-plugin.com/wordpress-quiz-plugin-free-demo/" target="_blank">' . __('Demo', $this->plugin_name) . '</a>',
            '<a href="https://ays-pro.com/wordpress/quiz-maker" target="_blank" style="color:red;">' . __('BUY NOW', $this->plugin_name) . '</a>',
            );
        return array_merge($settings_link, $links);

    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */
    public function display_plugin_setup_page(){
        include_once('partials/quiz-maker-admin-display.php');
    }

    public function display_plugin_quiz_categories_page(){
        $action = (isset($_GET['action'])) ? sanitize_text_field($_GET['action']) : '';

        switch ($action) {
            case 'add':
                include_once('partials/quizes/actions/quiz-maker-quiz-categories-actions.php');
                break;
            case 'edit':
                include_once('partials/quizes/actions/quiz-maker-quiz-categories-actions.php');
                break;
            default:
                include_once('partials/quizes/quiz-maker-quiz-categories-display.php');
        }
    }
    
    public function display_plugin_quiz_attributes_page(){
        include_once('partials/attributes/quiz-maker-attributes-display.php');
    }
    
    public function display_plugin_quiz_page(){
        $action = (isset($_GET['action'])) ? sanitize_text_field($_GET['action']) : '';

        switch ($action) {
            case 'add':
                include_once('partials/quizes/actions/quiz-maker-quizes-actions.php');
                break;
            case 'edit':
                include_once('partials/quizes/actions/quiz-maker-quizes-actions.php');
                break;
            default:
                include_once('partials/quizes/quiz-maker-quizes-display.php');
        }
    }

    public function display_plugin_question_categories_page(){
        $action = (isset($_GET['action'])) ? sanitize_text_field($_GET['action']) : '';

        switch ($action) {
            case 'add':
                include_once('partials/questions/actions/quiz-maker-questions-categories-actions.php');
                break;
            case 'edit':
                include_once('partials/questions/actions/quiz-maker-questions-categories-actions.php');
                break;
            default:
                include_once('partials/questions/quiz-maker-question-categories-display.php');
        }
    }

    public function display_plugin_questions_page(){
        $action = (isset($_GET['action'])) ? sanitize_text_field($_GET['action']) : '';

        switch ($action) {
            case 'add':
                include_once('partials/questions/actions/quiz-maker-questions-actions.php');
                break;
            case 'edit':
                include_once('partials/questions/actions/quiz-maker-questions-actions.php');
                break;
            default:
                include_once('partials/questions/quiz-maker-questions-display.php');
        }
    }
    
    public function display_plugin_orders_page(){

        include_once('partials/orders/quiz-maker-orders-display.php');
    }
    
    public function display_plugin_settings_page(){        
        include_once('partials/settings/quiz-maker-settings.php');
    }

    public function display_plugin_quiz_features_page(){
        include_once('partials/features/quiz-maker-features-display.php');
    }

    public function display_plugin_featured_plugins_page(){
        include_once('partials/features/quiz-maker-plugin-featured-display.php');
    }

    public function display_plugin_results_page(){
        include_once('partials/results/quiz-maker-results-display.php');
    }

    public static function set_screen($status, $option, $value){
        return $value;
    }

    public function screen_option_quizes(){
        $option = 'per_page';
        $args = array(
            'label' => __('Quizzes', $this->plugin_name),
            'default' => 20,
            'option' => 'quizes_per_page'
        );

        add_screen_option($option, $args);
        $this->quizes_obj = new Quizes_List_Table($this->plugin_name);
        $this->settings_obj = new Quiz_Maker_Settings_Actions($this->plugin_name);
    }

    public function screen_option_quiz_categories(){
        $option = 'per_page';
        $args = array(
            'label' => __('Quiz Categories', $this->plugin_name),
            'default' => 20,
            'option' => 'quiz_categories_per_page'
        );

        add_screen_option($option, $args);
        $this->quiz_categories_obj = new Quiz_Categories_List_Table($this->plugin_name);
    }

    public function screen_option_questions(){
        $option = 'per_page';
        $args = array(
            'label' => __('Questions', $this->plugin_name),
            'default' => 20,
            'option' => 'questions_per_page'
        );

        add_screen_option($option, $args);
        $this->questions_obj = new Questions_List_Table($this->plugin_name);
        $this->settings_obj = new Quiz_Maker_Settings_Actions($this->plugin_name);
    }

    public function screen_option_questions_categories(){
        $option = 'per_page';
        $args = array(
            'label' => __('Question Categories', $this->plugin_name),
            'default' => 20,
            'option' => 'question_categories_per_page'
        );

        add_screen_option($option, $args);
        $this->question_categories_obj = new Question_Categories_List_Table($this->plugin_name);
    }

    public function screen_option_results(){
        $option = 'per_page';
        $args = array(
            'label' => __('Results', $this->plugin_name),
            'default' => 50,
            'option' => 'quiz_results_per_page'
        );

        add_screen_option($option, $args);
        $this->results_obj = new Results_List_Table($this->plugin_name);
    }

    public function screen_option_settings(){
        $this->settings_obj = new Quiz_Maker_Settings_Actions($this->plugin_name);
    }

    /**
     * Adding questions from modal to table
     */
    public function add_question_rows(){
        error_reporting(0);
        $this->quizes_obj = new Quizes_List_Table($this->plugin_name);
        if ((isset($_REQUEST["add_question_rows"]) && wp_verify_nonce($_REQUEST["add_question_rows"], 'add_question_rows'))||(isset($_REQUEST["add_question_rows_top"]) && wp_verify_nonce($_REQUEST["add_question_rows_top"], 'add_question_rows_top'))) {
            $question_ids = $_REQUEST["ays_questions_ids"];
            $rows = array();
            $ids = array();
            if (!empty($question_ids)) {
                $question_categories = $this->get_questions_categories();
                $question_categories_array = array();
                foreach($question_categories as $cat){
                    $question_categories_array[$cat['id']] = $cat['title'];
                }
                foreach ($question_ids as $question_id) {
                    $data = $this->quizes_obj->get_published_questions_by('id', absint(intval($question_id)));
                    $table_question = (strip_tags(stripslashes($data['question'])));
                    $table_question = $this->ays_restriction_string("word", $table_question, 8);
                    $edit_question_url = "?page=".$this->plugin_name."-questions&action=edit&question=".$data['id'];
                    $rows[] = '<tr class="ays-question-row ui-state-default" data-id="' . $data['id'] . '">
                        <td class="ays-sort"><i class="ays_fa ays_fa_arrows" aria-hidden="true"></i></td>
                        <td>                        
                            <a href="'. $edit_question_url .'" target="_blank" class="ays-edit-question" title="'. __('Edit question', $this->plugin_name) .'">
                                ' . $table_question . '
                            </a> 
                        </td>
                        <td>' . $data['type'] . '</td>
                        <td>' . $question_categories_array[$data['category_id']] . '</td>
                        <td>' . stripslashes($data['id']) . '</td>
                        <td>
                            <input type="checkbox" class="ays_del_tr">
                            <a href="'. $edit_question_url .'" target="_blank" class="ays-edit-question" title="'. __('Edit question', $this->plugin_name) .'">
                                <i class="ays_fa ays_fa_pencil_square" aria-hidden="true"></i>
                            </a>
                            <a href="javascript:void(0)" class="ays-delete-question" data-id="' . $data['id'] . '">
                                <i class="ays_fa ays_fa_minus_square" aria-hidden="true"></i>
                            </a>
                        </td>
                   </tr>';
                    $ids[] = $data['id'];
                }
                ob_end_clean();
                $ob_get_clean = ob_get_clean();
                echo json_encode(array(
                    'status' => true,
                    'rows' => $rows,
                    'ids' => $ids
                ));
                wp_die();
            } else {
                ob_end_clean();
                $ob_get_clean = ob_get_clean();
                echo json_encode(array(
                    'status' => true,
                    'rows' => '',
                    'ids' => array()
                ));
                wp_die();
            }
        }
    }

    /**
     * @return string
     */
    public function ays_quick_start(){
        error_reporting(0);
        global $wpdb;

        $data = $_REQUEST;
        $quiz_title = $_REQUEST['ays_quiz_title'];
        $questions = $_REQUEST['ays_quick_question'];
        $answers_correct = $_REQUEST['ays_quick_answer_correct'];
        $answers = $_REQUEST['ays_quick_answer'];

        $answers_table = $wpdb->prefix . 'aysquiz_answers';
        $questions_table = $wpdb->prefix . 'aysquiz_questions';
        $quizes_table = $wpdb->prefix . 'aysquiz_quizes';

        $max_id = $this->get_max_id('quizes');
        $ordering = ( $max_id != NULL ) ? ( $max_id + 1 ) : 1;
        
        $questions_ids = '';
        $create_date = current_time( 'mysql' );
        $user_id = get_current_user_id();
        $user = get_userdata($user_id);
        $author = array(
            'id' => $user->ID,
            'name' => $user->data->display_name
        );
        $options = array(
            'author' => $author,
        );
        foreach ($questions as $question_key => $question) {
            $wpdb->insert($questions_table, array(
                'category_id'   => 1,
                'question'      => $question,
                'published'     => 1,
                'type'          => 'radio',
                'create_date'   => $create_date,
                'options'       => json_encode($options)
            ));
            $question_id = $wpdb->insert_id;
            $questions_ids .= $question_id . ',';
            foreach ($answers[$question_key] as $key => $answer) {
                $wpdb->insert($answers_table, array(
                    'question_id' => $question_id,
                    'answer' => $answer,
                    'correct' => ($answers_correct[$question_key][$key] == "true") ? 1 : 0,
                    'ordering' => $key
                ));
            }
        }
        $questions_ids = rtrim($questions_ids, ",");
        $wpdb->insert($quizes_table, array(
            'title' => $quiz_title,
            'question_ids' => $questions_ids,
            'published' => 1,
            'options' => json_encode(array(
                'quiz_theme' => 'classic_light',
                'color' => '#27AE60',
                'bg_color' => '#fff',
                'text_color' => '#000',
                'height' => 350,
                'width' => 400,
                'timer' => 100,
                'information_form' => 'disable',
                'form_name' => '',
                'form_email' => '',
                'form_phone' => '',
                'enable_logged_users' => 'off',
                'image_width' => '',
                'image_height' => '',
                'enable_correction' => 'off',
                'enable_questions_counter' => 'on',
                'limit_users' => 'off',
                'limitation_message' => '',
                'redirect_url' => '',
                'redirection_delay' => '',
                'enable_progress_bar' => 'on',
                'randomize_questions' => 'off',
                'randomize_answers' => 'off',
                'enable_questions_result' => 'on',
                'enable_average_statistical' => 'on',
                'enable_next_button' => 'on',
                'enable_previous_button' => 'on',
                'custom_css' => '',
                'enable_restriction_pass' => 'off',
                'restriction_pass_message' => '',
                'user_role' => '',
                'result_text' => '',
                'enable_result' => 'off',
                'enable_timer' => 'off',
                'enable_pass_count' => 'on',
                'enable_quiz_rate' => 'off',
                'enable_rate_avg' => 'off',
                'enable_rate_comments' => 'off',
                'hide_score' => 'off',
                'rate_form_title' => '',
                'enable_box_shadow' => 'on',
                'box_shadow_color' => '#000',
                'quiz_border_radius' => '0',
                'quiz_bg_image' => '',
                'enable_border' => 'off',
                'quiz_border_width' => '1',
                'quiz_border_style' => 'solid',
                'quiz_border_color' => '#000',
                'quiz_timer_in_title' => 'off',
                'enable_restart_button' => 'on',
                'quiz_loader' => 'default',
                'create_date' => $create_date,
                'author' => $author,
                'autofill_user_data' => 'off',
                'quest_animation' => 'shake',
                'form_title' => '',
                'enable_bg_music' => 'off',
                'quiz_bg_music' => '',
                'answers_font_size' => '15',
                'show_create_date' => 'on',
                'show_author' => 'on',
                'enable_early_finish' => 'off',
                'answers_rw_texts' => 'on_passing',
                'disable_store_data' => 'off',
                'enable_background_gradient' => 'off',
                'background_gradient_color_1' => '#000',
                'background_gradient_color_2' => '#fff',
                'quiz_gradient_direction' => 'vertical',
                'redirect_after_submit' => 'off',
                'submit_redirect_url' => '',
                'submit_redirect_delay' => '',
                'progress_bar_style' => 'second',
                'enable_exit_button' => 'off',
                'exit_redirect_url' => '',
                'image_sizing' => 'cover',
                'quiz_bg_image_position' => 'center center',
                'custom_class' => '',
                'enable_social_buttons' => 'on',
                'enable_social_links' => 'off',
                'social_links' => array(
                    'linkedin_link' => '',
                    'facebook_link' => '',
                    'twitter_link' => ''
                ),
                'show_quiz_title' => 'on',
                'show_quiz_desc' => 'on',
                'show_login_form' => 'off',
                'mobile_max_width' => '',
                'limit_users_by' => 'ip',
                'activeInterval' => '',
                'deactiveInterval' => '',
                'active_date_check' => 'off',
                'active_date_message' => __("The quiz has expired!", $this->plugin_name),
                'explanation_time' => '4',
                'enable_clear_answer' => 'off',
            )),
            'quiz_category_id' => 1,
            'ordering' => $ordering,
            ));
        $quiz_id = $wpdb->insert_id;
        ob_end_clean();
        $ob_get_clean = ob_get_clean();
        echo json_encode(array(
            'status' => true,
            'quiz_id'=>$quiz_id
        ));
        wp_die();
    }
    
    public static function get_max_id($table) {
        global $wpdb;
        $quiz_table = $wpdb->prefix . 'aysquiz_'.$table;

        $sql = "SELECT max(id) FROM {$quiz_table}";

        $result = intval($wpdb->get_var($sql));

        return $result;
    }
    
    public function ays_show_results(){
        global $wpdb;
        error_reporting(0);
        $results_table = $wpdb->prefix . "aysquiz_reports";
        $questions_table = $wpdb->prefix . "aysquiz_questions";

        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'ays_show_results') {
            $id = absint(intval($_REQUEST['result']));
            $results = $wpdb->get_row("SELECT * FROM {$results_table} WHERE id={$id}", "ARRAY_A");            
            $score = $results['score'];
            // $user_id = intval($results['user_id']);
            $user_id = isset($results['user_id']) ? intval($results['user_id']) : null;
            $quiz_id = isset($results['quiz_id']) ? intval($results['quiz_id']) : null;
            
            $user = get_user_by('id', $user_id);
            
            $user_ip = $results['user_ip'];
            $options = json_decode($results['options']);
            $user_attributes = $options->attributes_information;
            $start_date = $results['start_date'];
            $duration = $options->passed_time;
            $rate_id = isset($options->rate_id) ? $options->rate_id : null;
            $rate = $this->ays_quiz_rate($rate_id);
            $calc_method = isset($options->calc_method) ? $options->calc_method : 'by_correctness';
            
            $json = json_decode(file_get_contents("http://ipinfo.io/{$user_ip}/json"));
            $country = $json->country;
            $region = $json->region;
            $city = $json->city;
            $from = $city . ', ' . $region . ', ' . $country . ', ' . $user_ip;
            
            $row = "<table id='ays-results-table'>";
            
            $row .= '<tr class="ays_result_element">
                        <td colspan="4"><h1>' . __('User Information',$this->plugin_name) . '</h1></td>
                    </tr>';
            
            $row .= '<tr class="ays_result_element">
                        <td>User IP</td>
                        <td colspan="3">' . $from . '</td>
                    </tr>';
            
            $user_name = $user_id === 0 ? __( "Guest", $this->plugin_name ) : $user->data->display_name;
            if($user_id !== 0){
                $row .= '<tr class="ays_result_element">
                        <td>User ID</td>
                        <td colspan="3">' . $user_id . '</td>                    
                    </tr>';
            }
            $row .= '<tr class="ays_result_element">
                    <td>User</td>
                    <td colspan="3">' . $user_name . '</td>
                </tr>';
            
            if(isset($results['user_email']) && $results['user_email'] !== ''){
                $row .= "<tr class=\"ays_result_element\">
                        <td>".__('Email',$this->plugin_name)."</td>
                        <td colspan='3'>".stripslashes($results['user_email'])."</td>
                     </tr>";
            }
            if(isset($results['user_name']) && $results['user_name'] !== ''){
                $row .= "<tr class=\"ays_result_element\">
                        <td>".__('Name',$this->plugin_name)."</td>
                        <td colspan='3'>".stripslashes($results['user_name'])."</td>
                     </tr>";
            }
            if(isset($results['user_phone']) && $results['user_phone'] !== ''){
                $row .= "<tr class=\"ays_result_element\">
                        <td>".__('Phone',$this->plugin_name)."</td>
                        <td colspan='3'>".stripslashes($results['user_phone'])."</td>
                     </tr>";
            }
            if ($user_attributes !== null) {

                foreach ($user_attributes as $name => $value) {
                    $attr_value = stripslashes($value) == '' ? '-' : stripslashes($value);
                    $row .= '<tr class="ays_result_element">
                            <td>' . stripslashes($name) . '</td>
                            <td colspan="3">' . $attr_value . '</td>
                        </tr>';
                }
            }
            
            $row .= '<tr class="ays_result_element">
                        <td colspan="4"><h1>' . __('Quiz Information',$this->plugin_name) . '</h1></td>
                    </tr>';
            if(isset($rate['score'])){
                $rate_html = '<tr style="vertical-align: top;" class="ays_result_element">
                    <td>'.__('Rate',$this->plugin_name).'</td>
                    <td>'. __("Rate Score", $this->plugin_name).":<br>" . $rate['score'] . '</td>
                    <td colspan="2" style="max-width: 200px;">'. __("Review", $this->plugin_name).":<br>" . $rate['review'] . '</td>
                </tr>';
            }else{
                $rate_html = '<tr class="ays_result_element">
                    <td>'.__('Rate',$this->plugin_name).'</td>
                    <td colspan="3">' . $rate['review'] . '</td>
                </tr>';
            }
            $row .= '<tr class="ays_result_element">
                        <td>'.__('Start date',$this->plugin_name).'</td>
                        <td colspan="3">' . $start_date . '</td>
                    </tr>                        
                    <tr class="ays_result_element">
                        <td>'.__('Duration',$this->plugin_name).'</td>
                        <td colspan="3">' . $duration . '</td>
                    </tr>
                    <tr class="ays_result_element">
                        <td>'.__('Score',$this->plugin_name).'</td>
                        <td colspan="3">' . $score . '%</td>
                    </tr>'.$rate_html;

                    
            if(! empty($options->correctness)){
                $row .= '<tr class="ays_result_element">
                            <td colspan="4"><h1>' . __('Questions',$this->plugin_name) . '</h1></td>
                        </tr>';                
                $index = 1;
                $user_exp = array();
                if($results['user_explanation'] != '' || $results['user_explanation'] !== null){
                    $user_exp = json_decode($results['user_explanation'], true);
                }
                foreach ($options->correctness as $key => $option) {
                    if (strpos($key, 'question_id_') !== false) {
                        $question_id = absint(intval(explode('_', $key)[2]));
                        $question = $wpdb->get_row("SELECT * FROM {$questions_table} WHERE id={$question_id}", "ARRAY_A");
                        $correct_answers = $this->get_correct_answers($question_id);
                        $text_type = $this->question_is_text_type($question_id);
                        if($text_type){
                            $user_answered = $this->get_user_text_answered($options->user_answered, $key);                            
                        }else{
                            $user_answered = $this->get_user_answered($options->user_answered, $key);
                        }
                        $ans_point = $option;
                        $ans_point_class = 'success';
                        if(is_array($user_answered)){
                            $user_answered = $user_answered['message'];
                            $ans_point = '-';
                            $ans_point_class = 'error';
                        }
                        $tr_class = "ays_result_element";
                        if(isset($user_exp[$question_id])){
                            $tr_class = "";
                        }

                        $not_influance_check = isset($question['not_influence_to_score']) && $question['not_influence_to_score'] == 'on' ? false : true;
                        if (!$not_influance_check) {
                            $not_influance_check_td = ' colspan="2" ';
                        }else{
                            $not_influance_check_td = '';                            
                        }
                        
                        if($calc_method == 'by_correctness'){
                            if ($option == true) {
                                $row .= '<tr class="'.$tr_class.'">
                                            <td>'.__('Question',$this->plugin_name).' ' . $index . ' :<br/>' . (do_shortcode(stripslashes($question["question"]))) . '</td>';
                                if($text_type != 'number'){
                                    $c_answers = explode('%%%', $correct_answers);
                                    $c_answer = $c_answers[0];
                                    foreach($c_answers as $c_ans){
                                        if(strtolower(trim($user_answered)) == strtolower(trim($c_ans))){
                                            $c_answer = $c_ans;
                                            break;
                                        }
                                    }
                                    $row .='<td>'.__('Correct answer',$this->plugin_name).':<br/>';
                                    $row .= '<p class="success">' . htmlentities(do_shortcode(stripslashes($c_answer))) . '</p>';
                                    $row .='</td>';
                                }else{
                                    $row .='<td>'.__('Correct answer',$this->plugin_name).':<br/><p class="success">' . htmlentities(do_shortcode(stripslashes($correct_answers))) . '</p></td>';
                                }
                                $row .='<td '.$not_influance_check_td.'>'.__('User answered',$this->plugin_name).':<br/><p class="success">' . htmlentities(do_shortcode(stripslashes($user_answered))) . '</p></td>';
                                if ($not_influance_check) {
                                    $row .='<td>
                                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                                                    <circle class="path circle" fill="none" stroke="#73AF55" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
                                                    <polyline class="path check" fill="none" stroke="#73AF55" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "/>
                                                </svg>
                                                <p class="success">'.__('Succeed',$this->plugin_name).'!</p>
                                            </td>';
                                }
                                $row .= '</tr>';
                            } else {
                                $row .= '<tr class="'.$tr_class.'">
                                            <td>'.__('Question',$this->plugin_name).'' . $index . ' :<br/>' . (do_shortcode(stripslashes($question["question"]))) . '</td>';
                                if($text_type != 'number'){
                                    $c_answers = explode('%%%', $correct_answers);
                                    $row .= '<td>'.__('Correct answer',$this->plugin_name).':<br/>';
                                    $row .= '<p class="success">' . htmlentities(do_shortcode(stripslashes($c_answers[0]))) . '</p>';
                                    $row .= '</td>';
                                }else{
                                    $row .= '<td>'.__('Correct answer',$this->plugin_name).':<br/><p class="success">' . htmlentities(do_shortcode(stripslashes($correct_answers))) . '</p></td>';
                                }
                                $row .= '<td '.$not_influance_check_td.'>'.__('User answered',$this->plugin_name).':<br/><p class="error">' . htmlentities(do_shortcode(stripslashes($user_answered))) . '</p></td>';
                                if ($not_influance_check) {
                                    $row .='<td>
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                                            <circle class="path circle" fill="none" stroke="#D06079" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
                                            <line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="34.4" y1="37.9" x2="95.8" y2="92.3"/>
                                            <line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="95.8" y1="38" x2="34.4" y2="92.2"/>
                                        </svg>
                                        <p class="error">'.__('Failed',$this->plugin_name).'!</p>
                                    </td>';
                                }
                                $row .= '</tr>';
                            }
                        }elseif($calc_method == 'by_points'){
                            $row .= '<tr class="'.$tr_class.'">
                                        <td>'.__('Question',$this->plugin_name).' ' . $index . ' :<br/>' . (do_shortcode(stripslashes($question["question"]))) . '</td>
                                        <td>'.__('User answered',$this->plugin_name).':<br/><p class="'.$ans_point_class.'">' . htmlentities(do_shortcode(stripslashes($user_answered))) . '</p></td>
                                        <td>'.__('Answer point',$this->plugin_name).':<br/><p class="'.$ans_point_class.'">' . htmlentities($ans_point) . '</p></td>
                                    </tr>';
                            
                        }
                        $index++;
                        if(isset($user_exp[$question_id])){
                            $row .= '<tr class="ays_result_element">
                                <td>'.__('User explanation for this question',$this->plugin_name).'</td>
                                <td colspan="3">'.$user_exp[$question_id].'</td>
                            </tr>';
                        }
                    }
                }
            }
            $row .= "</table>";
            
            $sql = "UPDATE $results_table SET `read`=1 WHERE `id`=$id";
            $wpdb->get_var($sql);

            ob_end_clean();
            $ob_get_clean = ob_get_clean();
            echo json_encode(array(
                "status" => true,
                "rows" => $row
            ));
            wp_die();
        }
    }
    
    protected function ays_quiz_rate( $id ) {
        global $wpdb;
        if($id === '' || $id === null){
            $reason = __("No rate provided", $this->plugin_name);
            $output = array(
                "review" => $reason,
            );
        }else{
            $rate = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}aysquiz_rates WHERE id={$id}", "ARRAY_A");
            $output = array();
            if($rate !== null){
                $review = $rate['review'];
                $reason = stripslashes($review);
                if($reason == ''){
                    $reason = __("No review provided", $this->plugin_name);
                }
                $score = $rate['score'];
                $output = array(
                    "score" => $score,
                    "review" => $reason,
                );
            }else{
                $reason = __("No rate provided", $this->plugin_name);
                $output = array(
                    "review" => $reason,
                );
            }
        }
        return $output;
    }

    public function get_correct_answers($id){
        global $wpdb;
        $answers_table = $wpdb->prefix . "aysquiz_answers";
        $correct_answers = $wpdb->get_results("SELECT answer FROM {$answers_table} WHERE correct=1 AND question_id={$id}");
        $text = "";
        foreach ($correct_answers as $key => $correct_answer) {
            if ($key == (count($correct_answers) - 1))
                $text .= $correct_answer->answer;
            else
                $text .= $correct_answer->answer . ',';
        }
        return $text;
    }

    public function get_user_answered($user_choice, $key){
        global $wpdb;
        $answers_table = $wpdb->prefix . "aysquiz_answers";
        $choices = $user_choice->$key;
        
        if($choices == ''){
            return array(
                'message' => __( "The user have not answered to this question.", $this->plugin_name ),
                'status' => false
            );
        }
        $text = array();
        if (is_array($choices)) {
            foreach ($choices as $choice) {
                $result = $wpdb->get_row("SELECT answer FROM {$answers_table} WHERE id={$choice}", 'ARRAY_A');
                $text[] = $result['answer'];
            }
            $text = implode(', ', $text);
        } else {
            $result = $wpdb->get_row("SELECT answer FROM {$answers_table} WHERE id={$choices}", 'ARRAY_A');
            $text = $result['answer'];
        }
        return $text;
    }

    public function get_user_text_answered($user_choice, $key){
        if($user_choice->$key == ""){
            $choices = array(
                'message' => __( "The user have not answered to this question.", $this->plugin_name ),
                'status' => false
            );
        }else{
            $choices = trim($user_choice->$key);
        }
        
        return $choices;
    }
    
    public function question_is_text_type($question_id){
        global $wpdb;
        $questions_table = $wpdb->prefix . "aysquiz_questions";
        $question_id = absint(intval($question_id));
        $text_types = array('text', 'short_text', 'number');
        $get_answers = $wpdb->get_var("SELECT type FROM {$questions_table} WHERE id={$question_id}");
        if (in_array($get_answers, $text_types)) {
            return $get_answers;
        }
        return false;
    }

    /**
     * Changes previos version db structure
     */
    public function ays_change_db_questions(){
        global $wpdb;
        $quiz_table = $wpdb->prefix . 'aysquiz_quizes';

        $sql = "SELECT id, question_ids FROM {$quiz_table}";

        $rows = $wpdb->get_results($sql, 'ARRAY_A');


        foreach ($rows as $key => $row) {
            if (strpos($row['question_ids'], '***') !== false) {
                $question_ids = implode(',', explode('***', $row['question_ids']));
                $wpdb->update(
                    $quiz_table,
                    array('question_ids' => $question_ids),
                    array('id' => $row['id']),
                    array('%s'),
                    array('%d')
                );
            }
        }
    }
    
    public function get_questions_categories(){
        global $wpdb;
        $categories_table = $wpdb->prefix . "aysquiz_categories";
        $get_cats = $wpdb->get_results("SELECT * FROM {$categories_table}", ARRAY_A);
        return $get_cats;
    }

    public static function ays_get_quiz_options(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'aysquiz_quizes';
        $res = $wpdb->get_results("SELECT id, title FROM $table_name");
        $aysGlobal_array = array();

        foreach ($res as $ays_res_options) {
            $aysStatic_array = array();
            $aysStatic_array[] = $ays_res_options->id;
            $aysStatic_array[] = $ays_res_options->title;
            $aysGlobal_array[] = $aysStatic_array;
        }
        return $aysGlobal_array;
    }

    function ays_quiz_register_tinymce_plugin($plugin_array){
        $plugin_array['ays_quiz_button_mce'] = AYS_QUIZ_BASE_URL . 'ays_quiz_shortcode.js';
        return $plugin_array;
    }

    function ays_quiz_add_tinymce_button($buttons){
        $buttons[] = "ays_quiz_button_mce";
        return $buttons;
    }

    function gen_ays_quiz_shortcode_callback(){
        error_reporting(0);
        $shortcode_data = $this->ays_get_quiz_options();
        ob_end_clean();
        $ob_get_clean = ob_get_clean();
        ?>
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <title><?php echo __('Quiz Maker', $this->plugin_name); ?></title>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <script language="javascript" type="text/javascript"
                    src="<?php echo site_url(); ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
            <script language="javascript" type="text/javascript"
                    src="<?php echo site_url(); ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
            <script language="javascript" type="text/javascript"
                    src="<?php echo site_url(); ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>

            <?php
            wp_print_scripts('jquery');
            ?>
            <base target="_self">
        </head>
        <body id="link" onLoad="tinyMCEPopup.executeOnLoad('init();');document.body.style.display='';" dir="ltr"
              class="forceColors">
        <div class="select-sb">

            <table align="center">
                <tr>
                    <td><label for="ays_quiz">Quiz Maker</label></td>
                    <td>
                     <span>
                               <select id="ays_quiz" style="padding: 2px; height: 25px; font-size: 16px;width:100%;">
                           <option>--Select Quiz--</option>
                                   <?php
                                   echo "<pre>";
                                   print_r($shortcode_data);
                                   echo "</pre>";
                                   ?>
                                   <?php foreach ($shortcode_data as $index => $data)
                                       echo '<option id="' . $data[0] . '" value="' . $data[0] . '"  class="ays_quiz_options">' . $data[1] . '</option>';
                                   ?>
                               </select>
                           </span>
                    </td>
                </tr>
            </table>
        </div>
        <div class="mceActionPanel">
            <input type="submit" id="insert" name="insert" value="Insert" onClick="quiz_insert_shortcode();"/>
        </div>
        <script>

        </script>
        <script type="text/javascript">
            function quiz_insert_shortcode() {
                var tagtext = '[ays_quiz id="' + document.getElementById('ays_quiz')[document.getElementById('ays_quiz').selectedIndex].id + '"]';
                window.tinyMCE.execCommand('mceInsertContent', false, tagtext);
                tinyMCEPopup.close();
            }
        </script>
        </body>
        </html>
        <?php
        die();
    }
    
    public function vc_before_init_actions() {
        require_once( AYS_QUIZ_DIR.'pb_templates/quiz_maker_wpbvc.php' );
    }

    public function quiz_maker_el_widgets_registered() {
        wp_enqueue_style($this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'css/admin.css', array(), $this->version, 'all');
        // We check if the Elementor plugin has been installed / activated.
        if ( defined( 'ELEMENTOR_PATH' ) && class_exists( 'Elementor\Widget_Base' ) ) {
            // get our own widgets up and running:
            // copied from widgets-manager.php
            if ( class_exists( 'Elementor\Plugin' ) ) {
                if ( is_callable( 'Elementor\Plugin', 'instance' ) ) {
                    $elementor = Elementor\Plugin::instance();
                    if ( isset( $elementor->widgets_manager ) ) {
                        if ( method_exists( $elementor->widgets_manager, 'register_widget_type' ) ) {
                            $widget_file   = 'plugins/elementor/quiz_maker_elementor.php';
                            $template_file = locate_template( $widget_file );
                            if ( !$template_file || !is_readable( $template_file ) ) {
                                $template_file = AYS_QUIZ_DIR.'pb_templates/quiz_maker_elementor.php';
                            }
                            if ( $template_file && is_readable( $template_file ) ) {
                                require_once $template_file;
                                Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_Quiz_Maker_Elementor() );
                            }
                        }
                    }
                }
            }
        }
    }
    
    public function deactivate_plugin_option(){
        error_reporting(0);
        $request_value = $_REQUEST['upgrade_plugin'];
        $upgrade_option = get_option('ays_quiz_maker_upgrade_plugin','');
        if($upgrade_option === ''){
            add_option('ays_quiz_maker_upgrade_plugin',$request_value);
        }else{
            update_option('ays_quiz_maker_upgrade_plugin',$request_value);
        }
        ob_end_clean();
        $ob_get_clean = ob_get_clean();
        echo json_encode(array(
            'option' => get_option('ays_quiz_maker_upgrade_plugin', '')
        ));
        wp_die();
    }
    
    public static function ays_restriction_string($type, $x, $length){
        $output = "";
        switch($type){
            case "char":                
                if(strlen($x)<=$length){
                    $output = $x;
                } else {
                    $output = substr($x,0,$length) . '...';
                }
                break;
            case "word":
                $res = explode(" ", $x);
                if(count($res)<=$length){
                    $output = implode(" ",$res);
                } else {
                    $res = array_slice($res,0,$length);
                    $output = implode(" ",$res) . '...';
                }
            break;
        }
        return $output;
    }
    
    public static function validateDate($date, $format = 'Y-m-d H:i:s'){
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
    
    // Title change function in dashboard
    public function change_dashboard_title( $admin_title ) {
        
        global $current_screen;
        global $wpdb;
        
        if(strpos($current_screen->id, $this->plugin_name) === false){
            return $admin_title;
        }
        $action = (isset($_GET['action'])) ? sanitize_text_field($_GET['action']) : '';        
        $quiz_id = (isset($_GET['quiz'])) ? absint(intval($_GET['quiz'])) : null;
        $question_id = (isset($_GET['question'])) ? absint(intval($_GET['question'])) : null;
        $question_cat_id = (isset($_GET['question_category'])) ? absint(intval($_GET['question_category'])) : null;
        $quiz_cat_id = (isset($_GET['quiz_category'])) ? absint(intval($_GET['quiz_category'])) : null;
        
        if($quiz_id !== null){
            $id = $quiz_id;
        }elseif($question_id !== null){
            $id = $question_id;
        }elseif($question_cat_id !== null){
            $id = $question_cat_id;
        }elseif($quiz_cat_id !== null){
            $id = $quiz_cat_id;
        }else{
            $id = null;
        }
        
        $current = explode($this->plugin_name, $current_screen->id);
        $current = trim($current[count($current)-1], "-");
        $sql = '';
        switch($current){
            case "":
                $page = __("Quiz", $this->plugin_name);
                if($id !== null){
                    $sql = "SELECT * FROM ".$wpdb->prefix."aysquiz_quizes WHERE id=".$id;
                }
                break;
            case "questions":
                $page = __("Question", $this->plugin_name);;
                if($id !== null){
                    $sql = "SELECT * FROM ".$wpdb->prefix."aysquiz_questions WHERE id=".$id;
                }
                break;
            case "quiz-categories":
                $page = __("Category", $this->plugin_name);;
                if($id !== null){
                    $sql = "SELECT * FROM ".$wpdb->prefix."aysquiz_quizcategories WHERE id=".$id;
                }
                break;
            case "question-categories":
                $page = __("Category", $this->plugin_name);;
                if($id !== null){
                    $sql = "SELECT * FROM ".$wpdb->prefix."aysquiz_categories WHERE id=".$id;
                }
                break;
            default:
                $page = '';
                $sql = '';
                break;
        }
        $results = null;
        if($sql != ""){
            $results = $wpdb->get_row($sql, "ARRAY_A");
        }
        $change_title = null;
        switch($action){
            case "add":
                $change_title = __("Add New", $this->plugin_name) ." ‹ ".$page;
                break;
            case "edit":
                if($results !== null){
                    $title = "";
                    if($current == "questions"){
                        $title = $results['question'];
                    }else{                        
                        $title = $results['title'];
                    }
                    $title = strip_tags($title);
                    $change_title = $this->ays_restriction_string("word", $title, 5) ." ‹ ". __("Edit", $this->plugin_name) . " ".$page;
                }
                break;
            default:
                $change_title = $admin_title;
                break;
        }
        if($change_title === null){
            $change_title = $admin_title;
        }
        
        return $change_title;

    }    
    
    public function quiz_maker_add_dashboard_widgets() {
        if(current_user_can('manage_options')){ // Administrator
            wp_add_dashboard_widget( 
                'quiz-maker', 
                'Quiz Maker Status', 
                array( $this, 'quiz_maker_dashboard_widget' )
            );

            // Globalize the metaboxes array, this holds all the widgets for wp-admin
            global $wp_meta_boxes;

            // Get the regular dashboard widgets array 
            // (which has our new widget already but at the end)
            $normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];

            // Backup and delete our new dashboard widget from the end of the array
            $example_widget_backup = array( 
                'quiz-maker' => $normal_dashboard['quiz-maker'] 
            );
            unset( $normal_dashboard['example_dashboard_widget'] );

            // Merge the two arrays together so our widget is at the beginning
            $sorted_dashboard = array_merge( $example_widget_backup, $normal_dashboard );

            // Save the sorted array back into the original metaboxes 
            $wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
        }
    } 

    /**
     * Create the function to output the contents of our Dashboard Widget.
     */
    public function quiz_maker_dashboard_widget() {
        global $wpdb;
        $questions_count = Questions_List_Table::record_count();
        $quizzes_count = Quizes_List_Table::record_count();
        $results_count = Results_List_Table::unread_records_count();
        
        $questions_label = intval($questions_count) == 1 ? "question" : "questions";
        $quizzes_label = intval($quizzes_count) == 1 ? "quiz" : "quizzes";
        $results_label = intval($results_count) == 1 ? "new result" : "new results";
        
        // Display whatever it is you want to show.
        ?>
        <ul class="ays_quiz_maker_dashboard_widget">
            <li class="ays_dashboard_widget_item">
                <a href="<?php echo "admin.php?page=".$this->plugin_name; ?>">
                    <img src="<?php echo AYS_QUIZ_ADMIN_URL."/images/icons/icon-128x128.png"; ?>" alt="Quizzes">
                    <span><?php echo $quizzes_count; ?></span>
                    <span><?php echo __($quizzes_label, $this->plugin_name); ?></span>
                </a>
            </li>
            <li class="ays_dashboard_widget_item">
                <a href="<?php echo "admin.php?page=".$this->plugin_name."-questions" ?>">
                    <img src="<?php echo AYS_QUIZ_ADMIN_URL."/images/icons/question2.png"; ?>" alt="Questions">
                    <span><?php echo $questions_count; ?></span>
                    <span><?php echo __($questions_label, $this->plugin_name); ?></span>
                </a>
            </li>
            <li class="ays_dashboard_widget_item">
                <a href="<?php echo "admin.php?page=".$this->plugin_name."-results" ?>">
                    <img src="<?php echo AYS_QUIZ_ADMIN_URL."/images/icons/users2.png"; ?>" alt="Results">
                    <span><?php echo $results_count; ?></span>
                    <span><?php echo __($results_label, $this->plugin_name); ?></span>
                </a>
            </li>
        </ul>
        <div style="padding:10px;font-size:14px;border-top:1px solid #ccc;">
            <?php echo "Works version ".AYS_QUIZ_VERSION." of "; ?>
            <a href="<?php echo "admin.php?page=".$this->plugin_name ?>">Quiz Maker</a>
        </div>
    <?php
    }
    
    public static function ays_query_string($remove_items){
        $query_string = $_SERVER['QUERY_STRING'];
        $query_items = explode( "&", $query_string );
        foreach($query_items as $key => $value){
            $item = explode("=", $value);
            foreach($remove_items as $k => $i){
                if(in_array($i, $item)){
                    unset($query_items[$key]);
                }
            }
        }
        return implode( "&", $query_items );
    }
    
    public function quiz_maker_admin_footer($a){
        if(isset($_REQUEST['page'])){
            if(false !== strpos($_REQUEST['page'], $this->plugin_name)){
                ?>
                <p style="font-size:13px;text-align:center;font-style:italic;">
                    <span style="margin-left:0px;margin-right:10px;" class="ays_heart_beat"><i class="ays_fa ays_fa_heart_o animated"></i></span>
                    <span><?php echo __( "If you love our plugin, please do big favor and rate us on", $this->plugin_name); ?></span> 
                    <a target="_blank" href='https://wordpress.org/support/plugin/quiz-maker/reviews/'>WordPress.org</a>
                    <span class="ays_heart_beat"><i class="ays_fa ays_fa_heart_o animated"></i></span>
                </p>
            <?php
            }
        }
    }
}
