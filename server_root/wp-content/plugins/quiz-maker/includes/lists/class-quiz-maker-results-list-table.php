<?php
ob_start();
class Results_List_Table extends WP_List_Table{
    private $plugin_name;
    private $title_length;

    /**
     * The wp nonce of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $ays_quiz_nonce
     */
    private $ays_quiz_nonce;

    private $current_user_can_edit;

    /** Class constructor */
    public function __construct($plugin_name) {
        $this->plugin_name = $plugin_name;
        $this->title_length = Quiz_Maker_Admin::get_listtables_title_length('results');
        $this->current_user_can_edit = Quiz_Maker_Admin::quiz_maker_capabilities();

        parent::__construct( array(
            'singular' => __( 'Result', 'quiz-maker' ), //singular name of the listed records
            'plural'   => __( 'Results', 'quiz-maker' ), //plural name of the listed records
            'ajax'     => false //does this table support ajax?
        ) );
        add_action( 'admin_notices', array( $this, 'results_notices' ) );
        // add_filter( 'hidden_columns', array( $this, 'get_hidden_columns'), 10, 2 );

        $this->ays_quiz_nonce = wp_create_nonce('ays_quiz_admin_results_list_table_nonce');

        if( empty($this->ays_quiz_nonce) ){
            add_action('init', function () {
                $this->ays_quiz_nonce = wp_create_nonce('ays_quiz_admin_results_list_table_nonce');
            }, 1);
        }
    }

    /**
     * Override of table nav to avoid breaking with bulk actions & according nonce field
     */
    public function display_tablenav( $which ) {
        // Run a security check.
        if (empty($this->ays_quiz_nonce) || ! wp_verify_nonce( $this->ays_quiz_nonce, 'ays_quiz_admin_results_list_table_nonce' ) ) {
            // This nonce is not valid.
            wp_die('Nonce verification failed!');
        }

        if( !is_user_logged_in()){
            wp_die(  esc_html__( 'Something went wrong', 'quiz-maker' ) );
        }

        // Verify unauthorized requests
        if( !current_user_can( $this->current_user_can_edit ) ){
            wp_die(  esc_html__( 'Something went wrong', 'quiz-maker' ) );
        }
        ?>
        <div class="tablenav <?php echo esc_attr( $which ); ?>">
            
            <div class="alignleft actions">
                <?php $this->bulk_actions( $which ); ?>
            </div>
            
            <?php
            $this->extra_tablenav( $which );
            $this->pagination( $which );
            ?>
            <br class="clear" />
        </div>
        <?php
    }

    public function extra_tablenav( $which ){

        // Run a security check.
        if (empty($this->ays_quiz_nonce) || ! wp_verify_nonce( $this->ays_quiz_nonce, 'ays_quiz_admin_results_list_table_nonce' ) ) {
            // This nonce is not valid.
            wp_die('Nonce verification failed!');
        }

        if( !is_user_logged_in()){
            wp_die(  esc_html__( 'Something went wrong', 'quiz-maker' ) );
        }

        // Verify unauthorized requests
        if( !current_user_can( $this->current_user_can_edit ) ){
            wp_die(  esc_html__( 'Something went wrong', 'quiz-maker' ) );
        }

        global $wpdb;
        $titles_sql = "SELECT {$wpdb->prefix}aysquiz_quizes.title,
                              {$wpdb->prefix}aysquiz_quizes.id 
                       FROM {$wpdb->prefix}aysquiz_quizes ORDER BY {$wpdb->prefix}aysquiz_quizes.title ASC";
        $quiz_titles = $wpdb->get_results($titles_sql);
        
        $users_sql = "SELECT {$wpdb->prefix}aysquiz_reports.user_id
                      FROM {$wpdb->prefix}aysquiz_reports
                      GROUP BY user_id";
        $users = $wpdb->get_results($users_sql);
        $quiz_id = null;
        $user_id = null;
        if( isset( $_GET['filterby'] )){
            $quiz_id = absint( sanitize_text_field( $_GET['filterby'] ) );
        }
        if( isset( $_GET['filterbyuser'] )){
            $user_id = absint( sanitize_text_field( $_GET['filterbyuser'] ) );
        }

        $cat_titles_sql = "SELECT {$wpdb->prefix}aysquiz_quizcategories.title,{$wpdb->prefix}aysquiz_quizcategories.id FROM {$wpdb->prefix}aysquiz_quizcategories ORDER BY {$wpdb->prefix}aysquiz_quizcategories.title ASC";
        $cat_titles = $wpdb->get_results($cat_titles_sql);
        $cat_id = null;
        if( isset( $_GET['filterbycategory'] )){
            $cat_id = absint( sanitize_text_field( $_GET['filterbycategory'] ) );
        }
        $categories_select = array();
        foreach($cat_titles as $key => $cat_title){
            $cat_selected = "";
            if($cat_id === absint($cat_title->id)){
                $cat_selected = "selected";
            }
            $categories_select[$cat_title->id]['title'] = $cat_title->title;
            $categories_select[$cat_title->id]['selected'] = $cat_selected;
            $categories_select[$cat_title->id]['id'] = $cat_title->id;
        }
        // sort($categories_select);

        ?>
        <div id="quiz-filter-div-<?php echo esc_attr( $which ); ?>" class="alignleft actions bulkactions">
            <select name="filterby-<?php echo esc_attr( $which ); ?>" id="bulk-action-quiz-selector-<?php echo esc_attr( $which ); ?>">
                <option value=""><?php echo esc_html__('Select Quiz','quiz-maker')?></option>
                <?php
                    foreach($quiz_titles as $key => $q_title){
                        $selected = "";
                        if($quiz_id === intval($q_title->id)){
                            $selected = "selected";
                        }
                        echo "<option ".esc_attr($selected)." value='".esc_attr($q_title->id)."'>".esc_html($q_title->title)."</option>";
                    }
                ?>
            </select>

            <select name="filterbyuser-<?php echo esc_attr( $which ); ?>" id="bulk-action-user-selector-<?php echo esc_attr( $which ); ?>">
                <option value=""><?php echo esc_html__('Select User','quiz-maker')?></option>
                <?php
                    foreach($users as $key => $user){
                        $selected = "";
                        if($user_id === intval($user->user_id)){
                            $selected = "selected";
                        }
                        if(intval($user->user_id) == 0){
                            $name = __( 'Guest', 'quiz-maker' );
                        }else{
                            $wpuser = get_userdata( intval($user->user_id) );
                            if($wpuser !== false){
                                $name = $wpuser->data->display_name;
                            }else{
                                continue;
                            }
                        }
                        echo "<option ".esc_attr($selected)." value='".esc_attr($user->user_id)."'>".esc_html($name)."</option>";
                    }
                ?>
            </select>
            
            <select name="filterbycategory-<?php echo esc_attr( $which ); ?>" id="bulk-action-category-selector-<?php echo esc_attr( $which ); ?>">
                <option value=""><?php echo esc_html__('Select Category','quiz-maker')?></option>
                <?php
                    foreach($categories_select as $key => $cat_title){
                        echo "<option ".esc_attr($cat_title['selected'])." value='".esc_attr($cat_title['id'])."'>".esc_html($cat_title['title'])."</option>";
                    }
                ?>
            </select>
            <input type="button" id="doaction-<?php echo esc_attr( $which ); ?>" class="ays-quiz-question-tab-all-filter-button-<?php echo esc_attr( $which ); ?> button" value="<?php echo esc_attr( __( "Filter", 'quiz-maker' ) ); ?>">
        </div>
        <a style="" href="?page=<?php echo esc_attr( $_REQUEST['page'] ) ?>" class="button"><?php echo esc_html__( "Clear filters", 'quiz-maker' ); ?></a>
        <?php
    }
    
    protected function get_views() {

        // Run a security check.
        if (empty($this->ays_quiz_nonce) || ! wp_verify_nonce( $this->ays_quiz_nonce, 'ays_quiz_admin_results_list_table_nonce' ) ) {
            // This nonce is not valid.
            wp_die('Nonce verification failed!');
        }

        if( !is_user_logged_in()){
            wp_die(  esc_html__( 'Something went wrong', 'quiz-maker' ) );
        }

        // Verify unauthorized requests
        if( !current_user_can( $this->current_user_can_edit ) ){
            wp_die(  esc_html__( 'Something went wrong', 'quiz-maker' ) );
        }

        $published_count = $this->readed_records_count();
        $unpublished_count = $this->unread_records_count();
        $all_count = $this->all_record_count();
        $selected_all = "";
        $selected_0 = "";
        $selected_1 = "";
        if( isset( $_REQUEST['fstatus'] ) && is_numeric( $_REQUEST['fstatus'] ) && ! is_null( sanitize_text_field( $_REQUEST['fstatus'] ) ) ){

            $fstatus  = absint( $_REQUEST['fstatus'] );

            switch( $fstatus ){
                case 0:
                    $selected_0 = " style='font-weight:bold;' ";
                    break;
                case 1:
                    $selected_1 = " style='font-weight:bold;' ";
                    break;
                default:
                    $selected_all = " style='font-weight:bold;' ";
                    break;
            }
        }else{
            $selected_all = " style='font-weight:bold;' ";
        }

        $admin_url = get_admin_url( null, 'admin.php' );
        $get_properties = http_build_query($_GET);

        $status_links_url = $admin_url . "?" . $get_properties;
        $read_url = esc_url( add_query_arg('fstatus', 1, $status_links_url) );
        $unread_url = esc_url( add_query_arg('fstatus', 0, $status_links_url) );
        
        $status_links = array(
            "all"       => "<a ".$selected_all." href='?page=".esc_attr( $_REQUEST['page'] )."'>". __( 'All', 'quiz-maker' )." (".$all_count.")</a>",
            "readed"    => "<a ".$selected_1." href='". $read_url ."'>". __( 'Read', 'quiz-maker' )." (".$published_count.")</a>",
            "unreaded"  => "<a ".$selected_0." href='". $unread_url ."'>". __( 'Unread', 'quiz-maker' )." (".$unpublished_count.")</a>"
        );
        return $status_links;
    }    

    /**
     * Retrieve customers data from the database
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return mixed
     */
    public function get_reports( $per_page = 50, $page_number = 1 ) {

        // Run a security check.
        if (empty($this->ays_quiz_nonce) || ! wp_verify_nonce( $this->ays_quiz_nonce, 'ays_quiz_admin_results_list_table_nonce' ) ) {
            // This nonce is not valid.
            wp_die('Nonce verification failed!');
        }

        if( !is_user_logged_in()){
            wp_die(  esc_html__( 'Something went wrong', 'quiz-maker' ) );
        }

        // Verify unauthorized requests
        if( !current_user_can( $this->current_user_can_edit ) ){
            wp_die(  esc_html__( 'Something went wrong', 'quiz-maker' ) );
        }

        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}aysquiz_reports";

        $sql .= $this->get_where_condition();

        if ( ! empty( $_REQUEST['orderby'] ) ) {

            $order_by = ( isset( $_REQUEST['orderby'] ) && sanitize_text_field( $_REQUEST['orderby'] ) != '' ) ? sanitize_text_field( $_REQUEST['orderby'] ) : 'id';
            $order_by_order = ( ! empty( $_REQUEST['order'] ) && strtolower( $_REQUEST['order'] ) == 'asc' ) ? ' ASC' : ' DESC';
            if($order_by == 'score'){
                $order_by = 'CAST(score as UNSIGNED)';
                $order_by .= $order_by_order;
                $sql .= ' ORDER BY ' . $order_by;
            }else{
                $order_by .= $order_by_order;
                $sql_orderby = sanitize_sql_orderby($order_by);

                if ( $sql_orderby ) {
                    $sql .= ' ORDER BY ' . $sql_orderby;
                } else {
                    $sql .= ' ORDER BY id DESC';
                }
            }

        }
        else{
            $sql .= ' ORDER BY id DESC';
        }

        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

        return $result;
    }

    public function get_where_condition( $flag = true ){

        // Run a security check.
        if (empty($this->ays_quiz_nonce) || ! wp_verify_nonce( $this->ays_quiz_nonce, 'ays_quiz_admin_results_list_table_nonce' ) ) {
            // This nonce is not valid.
            wp_die('Nonce verification failed!');
        }

        if( !is_user_logged_in()){
            wp_die(  esc_html__( 'Something went wrong', 'quiz-maker' ) );
        }

        // Verify unauthorized requests
        if( !current_user_can( $this->current_user_can_edit ) ){
            wp_die(  esc_html__( 'Something went wrong', 'quiz-maker' ) );
        }

        global $wpdb;

        $quizzes_table = esc_sql( $wpdb->prefix . "aysquiz_quizes");

        $where = array();
        $sql = '';

        $search = ( isset( $_REQUEST['s'] ) ) ? esc_sql( $wpdb->esc_like( sanitize_text_field( $_REQUEST['s'] ) ) ) : false;
        if( $search ){
            $s = array();
            $s[] = sprintf( " `user_name` LIKE '%%%s%%' ", esc_sql( $wpdb->esc_like( $search ) ) );
            $s[] = sprintf( " `user_email` LIKE '%%%s%%' ", esc_sql( $wpdb->esc_like( $search ) ) );
            $s[] = sprintf( " `user_phone` LIKE '%%%s%%' ", esc_sql( $wpdb->esc_like( $search ) ) );
            $s[] = sprintf( " `score` LIKE '%%%s%%' ", esc_sql( $wpdb->esc_like( $search ) ) );

            $where[] = ' ( ' . implode(' OR ', $s) . ' ) ';
        }

        if( $flag && isset( $_REQUEST['fstatus'] ) && is_numeric( $_REQUEST['fstatus'] ) && ! is_null( sanitize_text_field( $_REQUEST['fstatus'] ) ) ){

            $fstatus = absint( $_REQUEST['fstatus'] );

            switch( $fstatus ){
                case 0:
                    $where[] = ' `read` = 0 ';
                    break;
                case 1:                    
                    $where[] = ' `read` = 1 ';
                    break;
            }
        }

        if(! empty( $_REQUEST['filterby'] ) && absint( intval( $_REQUEST['filterby'] ) ) > 0){
            $cat_id = absint( sanitize_text_field( $_REQUEST['filterby'] ) );
            $where[] = ' `quiz_id` = '.$cat_id.' ';
        }

        if( isset( $_REQUEST['filterbyuser'] ) ){
            $user_id = absint( sanitize_text_field( $_REQUEST['filterbyuser'] ) );
            $where[] = ' `user_id` = '.$user_id.' ';
        }

        if(! empty( $_REQUEST['filterbycategory'] ) && $_REQUEST['filterbycategory'] > 0){
            $cat_id = absint( sanitize_text_field( $_REQUEST['filterbycategory'] ) );
            $where[] = ' `quiz_id` IN( (SELECT q_r.id FROM '. $quizzes_table .' AS q_r WHERE `quiz_category_id` = '.$cat_id.' ) )';
        }
        
        if( ! empty($where) ){
            $sql = " WHERE " . implode( " AND ", $where );
        }
        return $sql;
    }    

    public function get_report_by_id( $id ){
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}aysquiz_reports WHERE id=" . absint( intval( $id ) );

        $result = $wpdb->get_row($sql, 'ARRAY_A');

        return $result;
    }


    /**
     * Delete a customer record.
     *
     * @param int $id customer ID
     */
    public function delete_reports( $id ) {

        // Run a security check.
        if (empty($this->ays_quiz_nonce) || ! wp_verify_nonce( $this->ays_quiz_nonce, 'ays_quiz_admin_results_list_table_nonce' ) ) {
            // This nonce is not valid.
            wp_die('Nonce verification failed!');
        }

        if( !is_user_logged_in()){
            wp_die(  esc_html__( 'Something went wrong', 'quiz-maker' ) );
        }

        // Verify unauthorized requests
        if( !current_user_can( $this->current_user_can_edit ) ){
            wp_die(  esc_html__( 'Something went wrong', 'quiz-maker' ) );
        }

        global $wpdb;
        $wpdb->delete(
            "{$wpdb->prefix}aysquiz_reports",
            array( 'id' => intval($id) ),
            array( '%d' )
        );
    }


    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public function record_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}aysquiz_reports";
        $sql .= $this->get_where_condition();
        return $wpdb->get_var( $sql );
    }
    
    public function all_record_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}aysquiz_reports";
        $sql .= $this->get_where_condition( false );

        return $wpdb->get_var( $sql );
    }
    
    public function unread_records_count() {

        // Run a security check.
        if (empty($this->ays_quiz_nonce) || ! wp_verify_nonce( $this->ays_quiz_nonce, 'ays_quiz_admin_results_list_table_nonce' ) ) {
            // This nonce is not valid.
            wp_die('Nonce verification failed!');
        }

        if( !is_user_logged_in()){
            wp_die(  esc_html__( 'Something went wrong', 'quiz-maker' ) );
        }

        // Verify unauthorized requests
        if( !current_user_can( $this->current_user_can_edit ) ){
            wp_die(  esc_html__( 'Something went wrong', 'quiz-maker' ) );
        }

        global $wpdb;

        $quizzes_table = esc_sql( $wpdb->prefix . "aysquiz_quizes");

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}aysquiz_reports ";

        $where = array();

        $where[] = ' `read` = 0 ';

        $search = ( isset( $_REQUEST['s'] ) ) ? esc_sql( $wpdb->esc_like( sanitize_text_field( $_REQUEST['s'] ) ) ) : false;
        if( $search ){
            $s = array();
            $s[] = sprintf( " `user_name` LIKE '%%%s%%' ", esc_sql( $wpdb->esc_like( $search ) ) );
            $s[] = sprintf( " `user_email` LIKE '%%%s%%' ", esc_sql( $wpdb->esc_like( $search ) ) );
            $s[] = sprintf( " `user_phone` LIKE '%%%s%%' ", esc_sql( $wpdb->esc_like( $search ) ) );
            $s[] = sprintf( " `score` LIKE '%%%s%%' ", esc_sql( $wpdb->esc_like( $search ) ) );

            $where[] = ' ( ' . implode(' OR ', $s) . ' ) ';
        }

        if(! empty( $_REQUEST['filterby'] ) && absint( intval( $_REQUEST['filterby'] ) ) > 0){
            $cat_id = absint( sanitize_text_field( $_REQUEST['filterby'] ) );
            $where[] = ' `quiz_id` = '.$cat_id.' ';
        }

        if( isset( $_REQUEST['filterbyuser'] ) ){
            $user_id = absint( sanitize_text_field( $_REQUEST['filterbyuser'] ) );
            $where[] = ' `user_id` = '.$user_id.' ';
        }

        if(! empty( $_REQUEST['filterbycategory'] ) && $_REQUEST['filterbycategory'] > 0){
            $cat_id = absint( sanitize_text_field( $_REQUEST['filterbycategory'] ) );
            $where[] = ' `quiz_id` IN( (SELECT q_r.id FROM '. $quizzes_table .' AS q_r WHERE `quiz_category_id` = '.$cat_id.' ) )';
        }
        
        if( ! empty($where) ){
            $sql .= " WHERE " . implode( " AND ", $where );
        }

        return $wpdb->get_var( $sql );
    }
    
    public function readed_records_count() {

        // Run a security check.
        if (empty($this->ays_quiz_nonce) || ! wp_verify_nonce( $this->ays_quiz_nonce, 'ays_quiz_admin_results_list_table_nonce' ) ) {
            // This nonce is not valid.
            wp_die('Nonce verification failed!');
        }

        if( !is_user_logged_in()){
            wp_die(  esc_html__( 'Something went wrong', 'quiz-maker' ) );
        }

        // Verify unauthorized requests
        if( !current_user_can( $this->current_user_can_edit ) ){
            wp_die(  esc_html__( 'Something went wrong', 'quiz-maker' ) );
        }

        global $wpdb;

        $quizzes_table = esc_sql( $wpdb->prefix . "aysquiz_quizes");

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}aysquiz_reports ";
        
        $where = array();

        $where[] = ' `read` = 1 ';

        $search = ( isset( $_REQUEST['s'] ) ) ? esc_sql( $wpdb->esc_like( sanitize_text_field( $_REQUEST['s'] ) ) ) : false;
        if( $search ){
            $s = array();
            $s[] = sprintf( " `user_name` LIKE '%%%s%%' ", esc_sql( $wpdb->esc_like( $search ) ) );
            $s[] = sprintf( " `user_email` LIKE '%%%s%%' ", esc_sql( $wpdb->esc_like( $search ) ) );
            $s[] = sprintf( " `user_phone` LIKE '%%%s%%' ", esc_sql( $wpdb->esc_like( $search ) ) );
            $s[] = sprintf( " `score` LIKE '%%%s%%' ", esc_sql( $wpdb->esc_like( $search ) ) );

            $where[] = ' ( ' . implode(' OR ', $s) . ' ) ';
        }

        if(! empty( $_REQUEST['filterby'] ) && absint( intval( $_REQUEST['filterby'] ) ) > 0){
            $cat_id = absint( sanitize_text_field( $_REQUEST['filterby'] ) );
            $where[] = ' `quiz_id` = '.$cat_id.' ';
        }

        if( isset( $_REQUEST['filterbyuser'] ) ){
            $user_id = absint( sanitize_text_field( $_REQUEST['filterbyuser'] ) );
            $where[] = ' `user_id` = '.$user_id.' ';
        }

        if(! empty( $_REQUEST['filterbycategory'] ) && $_REQUEST['filterbycategory'] > 0){
            $cat_id = absint( sanitize_text_field( $_REQUEST['filterbycategory'] ) );
            $where[] = ' `quiz_id` IN( (SELECT q_r.id FROM '. $quizzes_table .' AS q_r WHERE `quiz_category_id` = '.$cat_id.' ) )';
        }
        
        if( ! empty($where) ){
            $sql .= " WHERE " . implode( " AND ", $where );
        }

        return $wpdb->get_var( $sql );
    }

    /**
     * Mark as read a customer record.
     *
     * @param int $id customer ID
     */
    public function ays_quiz_mark_as_read( $id ) {

        // Run a security check.
        if (empty($this->ays_quiz_nonce) || ! wp_verify_nonce( $this->ays_quiz_nonce, 'ays_quiz_admin_results_list_table_nonce' ) ) {
            // This nonce is not valid.
            wp_die('Nonce verification failed!');
        }

        if( !is_user_logged_in()){
            wp_die(  esc_html__( 'Something went wrong', 'quiz-maker' ) );
        }

        // Verify unauthorized requests
        if( !current_user_can( $this->current_user_can_edit ) ){
            wp_die(  esc_html__( 'Something went wrong', 'quiz-maker' ) );
        }

        global $wpdb;
        $reports_table = $wpdb->prefix . "aysquiz_reports";

        if (! is_null($id)) {
            $id = absint( intval( $id ) );
        }

        $read = 1;
        $result = $wpdb->update(
            $reports_table,
            array(
                'read' => $read,
            ),
            array( 'id' => $id ),
            array(
                '%d'
            ),
            array( '%d' )
        );
    }

    /**
     * Mark as unread a customer record.
     *
     * @param int $id customer ID
     */
    public function mark_as_unread_reports( $id ) {
        // Run a security check.
        if (empty($this->ays_quiz_nonce) || ! wp_verify_nonce( $this->ays_quiz_nonce, 'ays_quiz_admin_results_list_table_nonce' ) ) {
            // This nonce is not valid.
            wp_die('Nonce verification failed!');
        }

        if( !is_user_logged_in()){
            wp_die(  esc_html__( 'Something went wrong', 'quiz-maker' ) );
        }

        // Verify unauthorized requests
        if( !current_user_can( $this->current_user_can_edit ) ){
            wp_die(  esc_html__( 'Something went wrong', 'quiz-maker' ) );
        }

        global $wpdb;
        $reports_table = $wpdb->prefix . "aysquiz_reports";

        if (! is_null($id)) {
            $id = absint( sanitize_text_field( $id ) );
        } else {
            return;
        }

        $read = 0;
        $result = $wpdb->update(
            $reports_table,
            array(
                'read' => $read,
            ),
            array( 'id' => $id ),
            array(
                '%d'
            ),
            array( '%d' )
        );
    }


    /** Text displayed when no customer data is available */
    public function no_items() {
        echo esc_html__( 'There are no results yet.', 'quiz-maker' );
    }


    /**
     * Render a column when no column specific method exist.
     *
     * @param array $item
     * @param string $column_name
     *
     * @return mixed
     */
    public function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'quiz_id':
            case 'quiz_category_id':
            case 'user_id':
            case 'user_ip':
            case 'user_name':
            case 'user_email':
            case 'user_phone':
            case 'start_date':
            case 'end_date':
            case 'duration':
            case 'status':
            case 'id':
                return $item[ $column_name ];
                break;
            case 'score':
                return $item[ $column_name ] . " %";
                break;
            default:
                return print_r( $item, true ); //Show the whole array for troubleshooting purposes
        }
    }

    /**
     * Render the bulk edit checkbox
     *
     * @param array $item
     *
     * @return string
     */
    function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" class="ays_result_delete" name="bulk-delete[]" value="%s" />', $item['id']
        );
    }


    /**
     * Method for name column
     *
     * @param array $item an array of DB data
     *
     * @return string
     */
    function column_quiz_id( $item ) {

        // Run a security check.
        if (empty($this->ays_quiz_nonce) || ! wp_verify_nonce( $this->ays_quiz_nonce, 'ays_quiz_admin_results_list_table_nonce' ) ) {
            // This nonce is not valid.
            wp_die('Nonce verification failed!');
        }

        if( !is_user_logged_in()){
            wp_die(  esc_html__( 'Something went wrong', 'quiz-maker' ) );
        }

        // Verify unauthorized requests
        if( !current_user_can( $this->current_user_can_edit ) ){
            wp_die(  esc_html__( 'Something went wrong', 'quiz-maker' ) );
        }

        global $wpdb;
        
        $delete_nonce = wp_create_nonce( $this->plugin_name . '-delete-result' );
        
        $result = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}aysquiz_quizes WHERE id={$item['quiz_id']}", "ARRAY_A");
        if($item['read'] == 0){
            $result_read = "style='font-weight:bold;'";
        }else{
            $result_read = "";
        }

        $result_title = stripcslashes($result['title']);

        $q = esc_attr($result_title);
        $results_title_length = intval( $this->title_length );

        $restitle = Quiz_Maker_Admin::ays_restriction_string("word",$result_title, $results_title_length);
        if($result == null){
            $title = __( 'Quiz has been deleted', 'quiz-maker' );
        }else{
            // $title = sprintf( '<a href="javascript:void(0)" data-result="%d" class="%s" '.$result_read.'>%s</a>', absint( $item['id'] ), 'ays-show-results', $restitle);
            $title = sprintf( '<a href="javascript:void(0)" title="%s" data-result="%d" class="%s" '.$result_read.'>%s</a><input type="hidden" value="%d" class="ays_result_read">', $q, absint( $item['id'] ), 'ays-show-results', $restitle,  $item['read']);
        }
        // $title = sprintf( '<a href="javascript:void(0)" data-result="%d" class="%s">%s</a><input type="hidden" value="%d" class="ays_result_read">', absint( $item['id'] ), 'ays-show-results', $name, $item['read']);
        $quiz_id =  isset($result['quiz_id']) ? $result['quiz_id'] : 0;
        $actions = array(
            'view-details' => sprintf( '<a href="javascript:void(0);" data-result="%d" class="%s">%s</a>', absint( $item['id'] ), 'ays-show-results', 'View details'),
            'delete' => sprintf( '<a class="ays_confirm_del" data-message="this report" href="?page=%s&action=%s&result=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['id'] ), $delete_nonce )
        );
        
        // $actions = array(
        //     'delete' => sprintf( '<a href="?page=%s&action=%s&result=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['id'] ), $delete_nonce )
        // );

        return $title . $this->row_actions( $actions );
    }

    function column_user_id( $item ) {
        $user_id = intval($item['user_id']);
        if($user_id == 0){
            $name = __( "Guest", 'quiz-maker' );
        }else{
            $name = '';
            $user = get_userdata($user_id);
            if ($user !== false) {
                $name = $user->data->display_name;
            } else {
                $name = "<span style='color: red;'>" . __( "Deleted user", 'quiz-maker' ) . "</span>";
            }
        }
        return $name;
    }
    
    function column_quiz_rate( $item ) {
        global $wpdb;

        $delete_nonce = wp_create_nonce( $this->plugin_name . '-delete-result' );

        $options = json_decode($item['options'], true);
        $rate_id = (isset($options['rate_id'])) ? $options['rate_id'] : null;
        if($rate_id !== null){
            $margin_of_icon = "style='margin-left: 5px;'";
            $result = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}aysquiz_rates WHERE id={$rate_id}", "ARRAY_A");

            $res_options = (isset( $result['options'] ) && $result['options'] != '') ? $result['options'] : '';

            if($this->isJSON($res_options)){
                $review_json = json_decode($res_options, true);
                $review = $review_json['reason'];
            }elseif($res_options != ''){
                $review = $res_options;
            }else{
                $review = (isset( $result['review'] ) && $result['review'] != '') ? $result['review'] : '';
            }
            $reason = htmlentities(stripslashes(wpautop($review)));
            if($reason == ''){
                $reason = __("No review provided", 'quiz-maker');
            }
            $score = (isset( $result['score'] ) && $result['score'] != '') ? $result['score'] : '';
            $title = '';
            if ( $score != '' ) {
                $title = "
                <span data-result='".absint( $item['id'] )."' class='ays-show-rate-avg'>
                    $score
                    <a class='ays_help' $margin_of_icon data-template='<div class=\"rate_tooltip tooltip\" role=\"tooltip\"><div class=\"arrow\"></div><div class=\"rate-tooltip-inner tooltip-inner\"></div></div>' data-toggle='tooltip' data-html='true' title='$reason'><i class='ays_fa ays_fa_info_circle'></i></a>                        
                </span>";
            }
        }else{
            $margin_of_icon = '';
            $reason = __("No rate provided", 'quiz-maker');
            $score = '';
            $title = "";
        }
        return $title;
    }
    
    function column_duration( $item ) {
        global $wpdb;

        $delete_nonce = wp_create_nonce( $this->plugin_name . '-delete-result' );

        $options = json_decode($item['options'], true);
        $passed_time = (isset($options['passed_time'])) ? $options['passed_time'] : null;
        if($passed_time !== null){
            $title = $passed_time;
        }else{
            $title = __('No data', 'quiz-maker');
        }
        return $title;
    }

    function column_status( $item ) {
        global $wpdb;
        if( !isset( $item['quiz_id'] ) || intval( $item['quiz_id'] ) == 0 ){
            return '';
        }

        $sql = "SELECT options FROM " . $wpdb->prefix . "aysquiz_quizes WHERE id=" . intval( $item['quiz_id'] );
        $quiz_options = $wpdb->get_var( $sql );
        $quiz_options = $quiz_options != '' ? json_decode( $quiz_options, true ) : array();
        $pass_score = isset( $quiz_options['pass_score'] ) && $quiz_options['pass_score'] != '' ? absint( $quiz_options['pass_score'] ) : 0;
        $score = absint( $item['score'] );

        $status = '';
        if( $pass_score != 0 ){
            if( $score >= $pass_score ){
                $status = "<span style='color:green;font-weight:900;'><i class='ays_fa ays_fa_check' style='color:green;font-size: 18px'></i> " . __( "Passed", 'quiz-maker' ) . "</span>";
            }else{
                $status = "<span style='color:brown;font-weight:900;'><i class='ays_fa ays_fa_times' style='font-size: 18px'></i> " . __( "Failed", 'quiz-maker' ) . "</span>";
            }
        }

        return $status;
    }

    function column_quiz_category_id( $item ) {
        global $wpdb;

        $quizzes_table = esc_sql( $wpdb->prefix . "aysquiz_quizes");

        $quiz_id = (isset( $item['quiz_id'] ) && $item['quiz_id'] > 0) ? absint( sanitize_text_field( $item['quiz_id'] ) ) : null;

        $cat_title = __( 'Uncategorized', 'quiz-maker' );
        if ( ! is_null( $quiz_id ) ) {
            $sql = "SELECT quiz_category_id FROM ". $quizzes_table ." WHERE `id` = ".$quiz_id;
            $quiz_category_id = $wpdb->get_var($sql);

            if ( ! is_null( $quiz_category_id ) ) {
                $sql = "SELECT title FROM {$wpdb->prefix}aysquiz_quizcategories WHERE id=" . absint( sanitize_text_field( $quiz_category_id ) );

                $cat_title = $wpdb->get_var($sql);
            }
        }
        return $cat_title;
    }

    function isJSON($string){
       return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }

    
    function ays_get_average_of_rates($id){
        global $wpdb;
        $sql = "SELECT AVG(`score`) AS avg_score FROM {$wpdb->prefix}aysquiz_rates WHERE quiz_id= $id";
        $result = $wpdb->get_var($sql);
        return $result;
    }

    /**
     *  Associative array of columns
     *
     * @return array
     */
    function get_columns() {
        $columns = array(
            'cb'                => '<input type="checkbox" />',
            'quiz_id'           => __( 'Quiz', 'quiz-maker' ),
            'quiz_category_id'  => __( 'Category', 'quiz-maker' ),
            'user_id'           => __( 'WP User', 'quiz-maker' ),
            'user_ip'           => __( 'User IP', 'quiz-maker' ),
            'user_name'         => __( 'Name', 'quiz-maker' ),
            'user_email'        => __( 'Email', 'quiz-maker' ),
            'user_phone'        => __( 'Phone', 'quiz-maker' ),
            'quiz_rate'         => __( 'Rate', 'quiz-maker' ),
            'start_date'        => __( 'Start', 'quiz-maker' ),
            'end_date'          => __( 'End', 'quiz-maker' ),
            'duration'          => __( 'Duration', 'quiz-maker' ),
            'score'             => __( 'Score', 'quiz-maker' ),
            'status'            => __( 'Status', 'quiz-maker' ),
            'id'                => __( 'ID', 'quiz-maker' ),
        );

        return $columns;
    }


    /**
     * Columns to make sortable.
     *
     * @return array
     */
    public function get_sortable_columns() {
        $sortable_columns = array(
            'quiz_id'       => array( 'quiz_id', true ),
            'user_id'       => array( 'user_id', true ),
            'user_ip'       => array( 'user_ip', true ),
            'start_date'    => array( 'start_date', true ),
            'score'         => array( 'score', true ),
            'user_name'     => array( 'user_name', true ),
            'user_email'    => array( 'user_email', true ),
            'user_phone'    => array( 'user_phone', true ),
            'end_date'      => array( 'end_date', true ),
            'id'            => array( 'id', true ),
        );

        return $sortable_columns;
    }

    /**
     * Columns to make sortable.
     *
     * @return array
     */
    public function get_hidden_columns() {
        $sortable_columns = array(
            'user_phone',
            'end_date',
            'status',
            'id'
        );

        return $sortable_columns;
    }

    /**
     * Returns an associative array containing the bulk action
     *
     * @return array
     */
    public function get_bulk_actions() {
        $actions = array(
            'bulk-mark-as-read' => __( 'Mark as read', 'quiz-maker'),
            'mark-as-unread'    => __( 'Mark as unread', 'quiz-maker'),
            'bulk-delete'       => __( 'Delete', 'quiz-maker'),
        );

        return $actions;
    }

    
    /**
     * Handles data query and filter, sorting, and pagination.
     */
    public function prepare_items() {

        // Run a security check.
        if (empty($this->ays_quiz_nonce) || ! wp_verify_nonce( $this->ays_quiz_nonce, 'ays_quiz_admin_results_list_table_nonce' ) ) {
            // This nonce is not valid.
            wp_die('Nonce verification failed!');
        }

        if( !is_user_logged_in()){
            wp_die(  esc_html__( 'Something went wrong', 'quiz-maker' ) );
        }

        // Verify unauthorized requests
        if( !current_user_can( $this->current_user_can_edit ) ){
            wp_die(  esc_html__( 'Something went wrong', 'quiz-maker' ) );
        }

        $this->_column_headers = $this->get_column_info();

        /** Process bulk action */
        $this->process_bulk_action();

        $per_page     = $this->get_items_per_page( 'quiz_results_per_page', 50 );

        $current_page = $this->get_pagenum();
        $total_items  = $this->record_count();

        $this->set_pagination_args( array(
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page'    => $per_page //WE have to determine how many items to show on a page
        ) );

        $this->items = $this->get_reports( $per_page, $current_page );
    }

    public function process_bulk_action() {

        // Detect when a bulk action is being triggered.
        $action = $this->current_action();
        if ( ! $action ) {
            return;
        }

        if( !is_user_logged_in()){
            return;
        }

        // Verify unauthorized requests
        if( !current_user_can( $this->current_user_can_edit ) ){
            return;
        }

        if( current_user_can( $this->current_user_can_edit ) && is_user_logged_in() ){

            //Detect when a bulk action is being triggered...
            $message = 'deleted';
            if ( 'delete' === $this->current_action() ) {

                // In our file that handles the request, verify the nonce.
                $nonce = esc_attr( $_REQUEST['_wpnonce'] );

                if ( ! wp_verify_nonce( $nonce, $this->plugin_name . '-delete-result' ) ) {
                    die( 'Go get a life script kiddies' );
                }
                else {
                    $this->delete_reports( absint( $_GET['result'] ) );

                    // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
                    // add_query_arg() return the current url

                    $url = esc_url_raw( remove_query_arg(array('action', 'result', '_wpnonce')  ) ) . '&status=' . $message;
                    wp_safe_redirect( $url );
                    exit;
                }

            }

            // If the delete bulk action is triggered
            if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
                || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
            ) {

                $delete_ids = ( isset( $_POST['bulk-delete'] ) && ! empty( $_POST['bulk-delete'] ) ) ? esc_sql( $_POST['bulk-delete'] ) : array();

                // loop over the array of record IDs and delete them
                foreach ( $delete_ids as $id ) {
                    $this->delete_reports( $id );

                }

                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
                // add_query_arg() return the current url

                $url = esc_url_raw( remove_query_arg(array('action', 'result', '_wpnonce')  ) ) . '&status=' . $message;
                wp_safe_redirect( $url );
                exit;
            } elseif ((isset($_POST['action']) && $_POST['action'] == 'bulk-mark-as-read')
                      || (isset($_POST['action2']) && $_POST['action2'] == 'bulk-mark-as-read')
            ) {

                $results_ids = ( isset( $_POST['bulk-delete'] ) && ! empty( $_POST['bulk-delete'] ) ) ? esc_sql( $_POST['bulk-delete'] ) : array();

                // loop over the array of record IDs and mark as read them

                foreach ( $results_ids as $id ) {
                    $this->ays_quiz_mark_as_read( $id );
                }

                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
                // add_query_arg() return the current url
                $url = esc_url_raw( remove_query_arg(array('action', 'result', '_wpnonce')  ) );
                wp_safe_redirect( $url );
                exit;
            } elseif ((isset($_POST['action']) && $_POST['action'] == 'mark-as-unread')
                      || (isset($_POST['action2']) && $_POST['action2'] == 'mark-as-unread')
            ) {

                $results_ids = ( isset( $_POST['bulk-delete'] ) && ! empty( $_POST['bulk-delete'] ) ) ? esc_sql( $_POST['bulk-delete'] ) : array();

                // loop over the array of record IDs and mark as unread them

                foreach ( $results_ids as $id ) {
                    $this->mark_as_unread_reports( $id );
                }

                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
                // add_query_arg() return the current url
                $url = esc_url_raw( remove_query_arg(array('action', 'result', '_wpnonce')  ) );
                wp_safe_redirect( $url );
                exit;
            }
        } else {
            return;
        }
    }

    public function results_notices(){

        // Run a security check.
        if (empty($this->ays_quiz_nonce) || ! wp_verify_nonce( $this->ays_quiz_nonce, 'ays_quiz_admin_results_list_table_nonce' ) ) {
            // This nonce is not valid.
            wp_die('Nonce verification failed!');
        }

        if( !is_user_logged_in()){
            wp_die(  esc_html__( 'Something went wrong', 'quiz-maker' ) );
        }

        // Verify unauthorized requests
        if( !current_user_can( $this->current_user_can_edit ) ){
            wp_die(  esc_html__( 'Something went wrong', 'quiz-maker' ) );
        }
        
        if( empty($_REQUEST['status']) ){
            return;
        }
        
        $status = (isset($_REQUEST['status'])) ? sanitize_text_field( $_REQUEST['status'] ) : '';

        if ( empty( $status ) )
            return;

        if ( 'created' == $status )
            $updated_message = esc_html( __( 'Quiz created.', 'quiz-maker' ) );
        elseif ( 'updated' == $status )
            $updated_message = esc_html( __( 'Quiz saved.', 'quiz-maker' ) );
        elseif ( 'deleted' == $status )
            $updated_message = esc_html( __( 'Result(s) deleted.', 'quiz-maker' ) );

        if ( empty( $updated_message ) )
            return;

        ?>
        <div class="ays-quiz-admin-notice notice notice-success is-dismissible">
            <p> <?php echo esc_html($updated_message); ?> </p>
        </div>
        <?php
    }
}
