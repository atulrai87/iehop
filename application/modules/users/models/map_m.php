<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class map_m extends CI_Model {

	function insert($tablename, $insert_arr){
		$this->db->insert($tablename, $insert_arr);
		$id = $this->db->insert_id();
		return $id;
	}

	function get_all($tablename){
		$rec = $this->db->get($tablename);
		return $rec->result();
	}

	function delete_record($tablename, $where){
		$this->db->where($where);
		return $this->db->delete($tablename); 
	}
	
	function get_all_jointbl(){
		$sql = "select loc.*, gal.locationid, gal.image1, gal.image2, gal.image3, gal.video1
				from locations2 as loc 
				left join gallery as gal on gal.locationid  = loc.id
		";
		$result = $this->db->query($sql);
		$result = $result->result();
		
		return $result;
	}
}	
