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
                active tinyint NOT NULL DEFAULT 1,
                PRIMARY KEY  (id)
              ) $charset_collate;";
              
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }

    public function drop_brochures_table(){
        global $wpdb;
        $bt = $this->brochures_table;
        $wpdb->query("DROP TABLE IF EXISTS $bt");
    }

    public function get_all_brochures(){
        global $wpdb;
        $bt = $this->brochures_table;
        $sql = "SELECT * FROM $bt";
        return $wpdb->get_results($sql);
    }
    
    public function get_active_brochures(){
        global $wpdb;
        $bt = $this->brochures_table;
        $sql = "SELECT * FROM $bt WHERE active > 0";
        return $wpdb->get_results($sql);
    }

    public function create_brochure(string $brochure){
        global $wpdb;
        $bt = $this->brochures_table;
        $res = $wpdb->insert($bt, array(
            'brochure' => $brochure,
        ));
        return $res;
    }

    public function update_brochure_status(int $id, int $status){
        global $wpdb;
        $bt = $this->brochures_table;
        $res = $wpdb->update($bt, array(
            'active' => $status,
        ), array(
            'id' => $id,
        ));
        return $res;
    }

    public function delete_brochure(int $id){
        global $wpdb;
        $bt = $this->brochures_table;
        $res = $wpdb->delete($bt, array(
            'id' => $id,
        ));
        return $res;
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
                request_id varchar(255) NOT NULL,
                status enum('new','dispatched','cancelled') NOT NULL DEFAULT 'new',
                FOREIGN KEY (brochure_id) REFERENCES $bt(id),
                PRIMARY KEY  (id)
            ) $charset_collate;";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }
    }

    public function drop_brochure_requests_table(){
        global $wpdb;
        $brt = $this->brochure_requests_table;
        $wpdb->query("DROP TABLE IF EXISTS $brt");
    }

    public function get_all_brochure_requests(){
        global $wpdb;
        $brt = $this->brochure_requests_table;
        $sql = "SELECT * FROM $brt ORDER BY brochure_id";

        return $wpdb->get_results($sql);
    }

    public function create_brochure_request(string $name, string $address, array $brochures){
        global $wpdb;
        $brt = $this->brochure_requests_table;
        $request_id = uniqid();

        $sql = "INSERT INTO $brt (name, address, brochure_id, request_id) VALUES ";
        $values = array();
        foreach($brochures as $brochure){
            $values[] = "('$name', '$address', $brochure, '$request_id')";
        }
        $sql .= implode(',', $values);
        $res = $wpdb->query($sql);

        return $res;
    }

    public function update_brochure_request_status(string $request_id, string $status){
        global $wpdb;
        $brt = $this->brochure_requests_table;
        $sql = "UPDATE $brt SET status = '$status' WHERE request_id = '$request_id'";
        $res = $wpdb->query($sql);
        return $res;
    }

    public function delete_brochure_request(string $request_id){
        global $wpdb;
        $brt = $this->brochure_requests_table;
        $res = $wpdb->delete($brt, array(
            'request_id' => $request_id,
        ));
        return $res;
    }
    // BROCHURE_REQUESTS_END
}

?>