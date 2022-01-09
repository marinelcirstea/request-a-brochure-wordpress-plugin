<?php
class RAB_Service{
    private $brochures_table = 'rab_brochures';
    private $brochure_requests_table = 'rab_brochure_requests';

    // BROCHURES_START
    public function create_brochures_table(){
        global $wpdb;
        $bt = $this->brochures_table;

        if($wpdb->get_var("SHOW TABLES LIKE $bt") != $bt){
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE $bt (
                id int NOT NULL AUTO_INCREMENT,
                brochure varchar(255) NOT NULL,
                active enum('true','false') NOT NULL DEFAULT 'true',
                PRIMARY KEY  (id)
              ) $charset_collate;";
              
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }
    // BROCHURES_END

    // BROCHURE_REQUESTS_START
    public function create_brochure_request_table(){
        global $wpdb;
        $bt = $this->brochures_table;
        $brt = $this->brochure_requests_table;

        if($wpdb->get_var("SHOW TABLES LIKE $brt") != $brt){
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE $brt (
                id int NOT NULL AUTO_INCREMENT,
                name varchar(255) NOT NULL,
                address varchar(255) NOT NULL,
                brochure_id int NOT NULL,
                status enum('new','dispatched','cancelled') NOT NULL DEFAULT 'new',
                FOREIGN KEY (brochure_id) REFERENCES $bt(id),
                PRIMARY KEY  (id)
            ) $charset_collate;";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }
    }
    // BROCHURE_REQUESTS_END
}

?>