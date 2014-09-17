<?php
class Events_Model extends Model {
    public function saveEvent($data){
        $this->db->insert('pg_events', $data);
    }
    
    public function fetchEvents() {
        $query = $this->db->query("select * from pg_events where start_date >= now() or end_date >= now()");
        $result = $query->result();
        return $result;
    }
    
    public function searchEvents($data) {
        
        $queryString = "select * from pg_events where ";
        if(!empty($_POST) && $_POST['country'] != 0 && $_POST['country']!=""){
            $queryString .= "country=".$_POST['country']." and ";
        } if(!empty($_POST) && $_POST['region'] != 0 && $_POST['region']!=""){
            $queryString .= "region=".$_POST['region']." and ";
        } if(!empty($_POST) && $_POST['city'] != 0 && $_POST['city']!=""){
            $queryString .= "city=".$_POST['city']." and ";
        } if( !empty($_POST) && $_POST['event_type'] != 0 && $_POST['event_type']!=""){
            $queryString .= "event_type=".$_POST['event_type'];
        } else {
            $queryString .= "1";
        }
        $finalQuery = "select * from ($queryString) as a where a.start_date >= now() or a.end_date >= now()";
        
        $query = $this->db->query($finalQuery);
        $result = $query->result();
        return $result;
    }
}