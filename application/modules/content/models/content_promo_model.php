<?php
/**
* Countries promo data model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('CONTENT_PROMO_TABLE')) define('CONTENT_PROMO_TABLE', DB_PREFIX.'content_promo');

class Content_promo_model extends Model
{
	var $CI;
	var $DB;

	var $fields = array(
		"id",
		"id_lang",
		"content_type",
		"promo_text",
		"promo_image",
		"promo_flash",
		"block_width",
		"block_width_unit",
		"block_height",
		"block_height_unit",
		"block_align_hor",
		"block_align_ver",
		"block_image_repeat",
	);
	
	var $upload_gid = "promo-content-img";
	var $file_upload_gid = "promo-content-flash";

	function __construct(){
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
	}

	public function get_promo($id_lang){
		$data = array();
		$result = $this->DB->select(implode(", ", $this->fields))->from(CONTENT_PROMO_TABLE)->where("id_lang", $id_lang)->get()->result_array();
		if(!empty($result)){
			$data = $this->format_promo($result[0]);
		}
		return $data;	
	}
	
	public function format_promo($data){
		$data["postfix"] = $data["id_lang"];

		if(!empty($data["promo_image"])){
			$this->CI->load->model('Uploads_model');
			$data["media"]["promo_image"] = $this->CI->Uploads_model->format_upload($this->upload_gid, $data["postfix"], $data["promo_image"]);
		}
		
		if(!empty($data["promo_flash"])){
			$this->CI->load->model('File_uploads_model');
			$data["media"]["promo_flash"] = $this->CI->File_uploads_model->format_upload($this->file_upload_gid, $data["postfix"], $data["promo_flash"]);
		}
		
		if($data["block_width_unit"] != "auto"){
			$styles["width"] = $data["block_width"].$data["block_width_unit"];
		}
		
		if($data["block_height_unit"] != "auto"){
			$styles["height"] = $data["block_height"].$data["block_height_unit"];
		}
		
		if(!empty($data["promo_image"]) && $data["content_type"] == 't'){
			$styles["background-image"] = "url('".$data["media"]["promo_image"]["file_url"]."')";
			$styles["background-position"] = $data["block_align_hor"]." ".$data["block_align_ver"];
			$styles["background-repeat"] = $data["block_image_repeat"];
		}
		if(!empty($styles)){
			$data["styles"] = $styles;
			$data["style_str"] = '';
			foreach($styles as $selector=>$value){
				$data["style_str"] .= $selector.': '.$value."; ";
			}
		}

		return $data;
	}
	
	public function save_promo($id_lang, $data, $img_file='', $flash_file=''){
		if(!$id_lang) return false;
		
		$data['id_lang'] = $id_lang;	
	
		if (!$this->exists_promo($id_lang)){
			$this->DB->insert(CONTENT_PROMO_TABLE, $data);
		}else{
			$this->DB->where('id_lang', $id_lang);
			$this->DB->update(CONTENT_PROMO_TABLE, $data);
		}
		
		if(!empty($img_file) && isset($_FILES[$img_file]) && is_array($_FILES[$img_file]) && is_uploaded_file($_FILES[$img_file]["tmp_name"])){
			$this->CI->load->model("Uploads_model");
			$img_return = $this->CI->Uploads_model->upload($this->upload_gid, $id_lang."/", $img_file);

			if(empty($img_return["errors"])){
				$img_data["promo_image"] = $img_return["file"];
				$this->save_promo($id_lang, $img_data);
			}
		}

		if(!empty($flash_file) && isset($_FILES[$flash_file]) && is_array($_FILES[$flash_file]) && is_uploaded_file($_FILES[$flash_file]["tmp_name"])){
			$this->CI->load->model("File_uploads_model");
			$flash_return = $this->CI->File_uploads_model->upload($this->file_upload_gid, $id_lang."/", $flash_file);

			if(empty($flash_return["errors"])){
				$flash_data["promo_flash"] = $flash_return["file"];
				$this->save_promo($id_lang, $flash_data);
			}
		}

		return true;
	}

	public function validate_promo($data, $img_file='', $flash_file=''){
		$return = array("errors"=> array(), "data" => array());

		if(isset($data["id_lang"])){
			$return["data"]["id_lang"] = intval($data["id_lang"]);
		}

		if(isset($data["content_type"])){
			$return["data"]["content_type"] = trim(strip_tags($data["content_type"]));
		}

		if(isset($data["block_width"])){
			$return["data"]["block_width"] = intval($data["block_width"]);
		}

		if(isset($data["block_width_unit"])){
			$return["data"]["block_width_unit"] = strval($data["block_width_unit"]);
			if(!$return["data"]["block_width_unit"]){
				$return["data"]["block_width_unit"] = 'auto';
			}
		}

		if(isset($data["block_height"])){
			$return["data"]["block_height"] = intval($data["block_height"]);
		}

		if(isset($data["block_height_unit"])){
			$return["data"]["block_height_unit"] = strval($data["block_height_unit"]);
			if(!$return["data"]["block_height_unit"]){
				$return["data"]["block_height_unit"] = 'auto';
			}
		}

		if(isset($data["block_align_hor"])){
			$return["data"]["block_align_hor"] = strval($data["block_align_hor"]);
			if(!$return["data"]["block_align_hor"]){
				$return["data"]["block_align_hor"] = 'center';
			}
		}

		if(isset($data["block_align_ver"])){
			$return["data"]["block_align_ver"] = strval($data["block_align_ver"]);
			if(!$return["data"]["block_align_ver"]){
				$return["data"]["block_align_ver"] = 'center';
			}
		}

		if(isset($data["block_image_repeat"])){
			$return["data"]["block_image_repeat"] = strval($data["block_image_repeat"]);
			if(!$return["data"]["block_image_repeat"]){
				$return["data"]["block_image_repeat"] = 'no-repeat';
			}
		}

		if(isset($data["promo_text"])){
			$return["data"]["promo_text"] = trim($data["promo_text"]);
		}

		if(!empty($img_file) && isset($_FILES[$img_file]) && is_array($_FILES[$img_file]) && is_uploaded_file($_FILES[$img_file]["tmp_name"])){
			$this->CI->load->model("Uploads_model");
			$img_return = $this->CI->Uploads_model->validate_upload($this->upload_gid, $img_file);
			if(!empty($img_return["error"])){
				$return["errors"][] = implode("<br>", $img_return["error"]);
			}
		}

		if(!empty($flash_file) && isset($_FILES[$flash_file]) && is_array($_FILES[$flash_file]) && is_uploaded_file($_FILES[$flash_file]["tmp_name"])){
			$this->CI->load->model("File_uploads_model");
			$flash_return = $this->CI->File_uploads_model->validate_upload($this->file_upload_gid, $flash_file);

			if(!empty($flash_return["error"])){
				$return["errors"][] = implode("<br>", $flash_return["error"]);
			}
		}

		return $return;
		
	}
	
	public function delete_promo($id_lang){
		$this->DB->where('id_lang', $id_lang);
		$this->DB->delete(CONTENT_PROMO_TABLE);
		return;		
	}
	
	public function exists_promo($id_lang){
		$this->DB->select('COUNT(*) AS cnt')->from(CONTENT_PROMO_TABLE)->where('id_lang', $id_lang);
		$result = $this->DB->get()->result();
		if(!empty($result) && intval($result[0]->cnt) > 0){
			return true;
		}else{
			return false;
		}
	}
	
	//// dynamic blocks methods
	public function _dynamic_block_get_content_promo($params=array(), $view=''){
		$this->CI->load->helper('content');
		return get_content_promo($view);
	}
	
	//// methods for langs
	public function lang_dedicate_module_callback_add($id_lang) {
		$default_id_lang = $this->CI->pg_language->get_default_lang_id();
		$default_data = $this->get_promo($default_id_lang);
		
		$data = array(
			"id_lang" => $id_lang,
			"content_type" => $default_data["content_type"],
			"promo_text" => $default_data["promo_text"],
			"block_width" => $default_data["block_width"],
			"block_width_unit" => $default_data["block_width_unit"],
			"block_height" => $default_data["block_height"],
			"block_height_unit" => $default_data["block_height_unit"],
			"block_align_hor" => $default_data["block_align_hor"],
			"block_align_ver" => $default_data["block_align_ver"],
			"block_image_repeat" => $default_data["block_image_repeat"],
		);
		
		$validate_data = $this->validate_promo($data);
		if(empty($validate_data["errors"]) && !$this->exists_promo($id_lang)){
			$this->save_promo($id_lang, $validate_data["data"]);
		}
	}
	
	public function lang_dedicate_module_callback_delete($id_lang) {
		$this->delete_promo($id_lang);
	}
	
}