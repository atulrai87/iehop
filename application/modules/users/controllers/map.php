<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Map extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	
	function __construct(){
		parent::__construct();
		$this->load->model('map_m');
    }
	
	public function index()
	{
		//$this->map_m->insert('test', 'test');
		$this->load->view('map');
	}
	
	function add_location_details(){
		$data_arr = array(
			'location_address' => $this->input->post('inputAddressVal'),
			'lat' => $this->input->post('lat'),
			'lng' => $this->input->post('lng'),
			'userId' => 1
		);
		$insert = $this->map_m->insert('locations', $data_arr);
		if($insert){
			echo 'success';
		}else{
			echo 'error';
		}
		exit();
	}
	
	function get_all_locations(){
		$result = $this->map_m->get_all('locations');
		//echo '<pre>'; print_r($result);
		if($result){
			$out =  array();
			foreach($result as $data){
				$out[] = $data;
			}
			echo json_encode($out);
		}else{
			echo 'No Records';
		}
		die;
	}
	
	function delete_location(){
		$where = array(
			'id'=> $this->input->post('delId')
		);
		$delete = $this->map_m->delete_record('locations', $where);
		if($delete){
			echo 'success';
		}else{
			echo 'Error';
		}
		die;
	}
	
	/*Functions for map2.*/
	function my_locations_details(){
		$this->load->view('map2');
	}
	
	function add_details_new_location(){
		echo 1;exit();
		$insertArr = array(
			'my_location' => $this->input->post('my_location'),
			'my_location_latlng' => $this->input->post('my_location_latlng'),
			
			'ehopped_location' => $this->input->post('ehopped_location'),
			'ehopped_location_latlng' => $this->input->post('ehopped_location_latlng'),
			
			'visited_location' => $this->input->post('visited_location'),
			'visited_location_latlng' => $this->input->post('visited_location_latlng'),
			
			'would_like_to_visit' => $this->input->post('would_like_to_visit'),
			'would_like_to_visit_latlng' => $this->input->post('would_like_to_visit_latlng'),
			
			'userid'	=> 1
		);
		$insert = $this->map_m->insert('locations2', $insertArr);
		if($insert){
			echo 'success';
		}else{
			echo 'error';
		}
		
		exit();
		
		die;
	}
	
	function get_all_locations2(){
		$result = $this->map_m->get_all_jointbl();
		if($result){
			$out =  array();
			foreach($result as $data){
				$out[] = $data;
			}
			echo json_encode($out);
		}else{
			echo 'No Records';
		}
		die;
	}
	
	function view_iframe_form(){
		$config['setError'] = '';
		if($_POST){ 
			//print_r($_REQUEST);die;
			
			$image1 = $_FILES['image1']['name'];
			$image2 = $_FILES['image2']['name'];
			$image3 = $_FILES['image3']['name'];
			$video1 = $_FILES['video1']['name'];
			
			//move_uploaded_file($_FILES["image1"]["tmp_name"][$f], "uploads/" . $testname);
			$image1Filename = time().rand(). $_FILES["image1"]["name"];
			$image2Filename = time().rand(). $_FILES["image2"]["name"];
			$image3Filename = time().rand(). $_FILES["image3"]["name"];
			$video1Filename = time().rand(). $_FILES["video1"]["name"];
			
			
			$filepath = $_SERVER['DOCUMENT_ROOT'].'/gmap/iehop/uploads/';
			$moveImg1 = move_uploaded_file($_FILES["image1"]["tmp_name"], $filepath .$image1Filename);
			$moveImg2 = move_uploaded_file($_FILES["image2"]["tmp_name"], $filepath .$image2Filename);
			$moveImg3 = move_uploaded_file($_FILES["image3"]["tmp_name"], $filepath .$image3Filename);
			$moveVid1 = move_uploaded_file($_FILES["video1"]["tmp_name"], $filepath .$video1Filename);
			
			if($moveImg1 || $moveImg2 || $moveImg3 || $moveVid1){
				
				$dataArr = array(
					'locationid'=> $this->input->get('fieldId'),
					'image1'	=> $image1Filename,
					'image2'	=> $image2Filename,
					'image3'	=> $image3Filename,
					'video1'	=> $video1Filename
				);
				
				$insert = $this->map_m->insert('gallery', $dataArr);
				if($insert){
					$config['setError'] = '<span style="color:#008000;font-size:15px;">Files Uploaded Successfully. </span>';
				}else{
					$config['setError'] = '<span style="color:#FF0000;font-size:15px;">Error Uploading File. Please Try Again Later !!</span>';
				}	
			}
		}
		$this->load->view('iframe_gallery_form', $config);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
