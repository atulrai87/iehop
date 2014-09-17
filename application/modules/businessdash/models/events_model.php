<?php
class Events_model extends Model {


    function Events_model()
    {
	parent::Model();
	$this->CI = & get_instance();
    }
    
    public function saveEvent($data){
        $this->db->insert('events', $data);
    }
    
    public function fetchEvents() {
        $query = $this->db->query("select * from events where start_date >= now() or end_date >= now()");
        $result = $query->result();
        return $result;
    }
    
    public function searchEvents($data) {
        
        $queryString = "select * from events where ";
        if(!empty($_POST) && $_POST['country'] != 0 && $_POST['country']!=""){
            $queryString .= "country=".$_POST['country']." and ";
        } if(!empty($_POST) && $_POST['region'] != 0 && $_POST['region']!=""){
            $queryString .= "region=".$_POST['region']." and ";
        } if(!empty($_POST) && $_POST['city'] != 0 && $_POST['city']!=""){
            $queryString .= "city=".$_POST['city']." and ";
        } if( !empty($_POST)){
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