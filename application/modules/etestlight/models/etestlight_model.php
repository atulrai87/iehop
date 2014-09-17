<?php
class Etestlight_Model extends Model {
    public function testlite(){
        $query = $this->db->query("select * from pg_events where start_date >= now() or end_date >= now()");
        $result = $query->result_array();
        return $result;
        
    }
    
}