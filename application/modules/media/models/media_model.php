<?php
/**
* Media model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Mikhail Chernov <mchernov@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: mchernov $
**/

if (!defined('BASEPATH')) exit('No direct script access allowed');

define('MEDIA_TABLE', DB_PREFIX.'media');

class Media_model extends Model
{
	private $CI;
	private $DB;

	private $_fields = array(
		'id',
		'type_id',
		'id_user',
		'id_owner',
		'id_parent',
		'mediafile',
		'upload_gid',
		'mime',
		'date_add',
		'permissions',
		'fname',
		'description',
		'media_video',
		'media_video_image' ,
		'media_video_data' ,
		'status',
		'comments_count',
		'is_adult',
		'views',
		'settings'
	);
	private $_fields_str;
	private $_fields_for_copy = array(
		'type_id',
		'id_owner',
		'mediafile',
		'upload_gid',
		'mime',
		'date_add',
		'permissions',
		'fname',
		'description',
		'media_video',
		'media_video_image' ,
		'media_video_data' ,
		'status',
		'is_adult',
		'settings'
	);

	private $_user_id = false;
	public $album_type_id = 0;
	public $file_config_gid = '';
	public $video_config_gid = '';
	public $wall_event_media_limit = 8;
	public $moderation_type = 'media_content';

	public $format_user = false;

	private $_cache = array(
		'parent_media_list_ids' => array(),
	);

	function __construct(){
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;

		$this->_fields_str = implode(', ', $this->_fields);
		$this->initialize();
	}

	public function initialize($album_type_gid = 'media_type'){
		$this->CI->load->model('media/models/Album_types_model');
		$album_type = $this->CI->Album_types_model->get_album_type_by_gid($album_type_gid);
		$this->album_type_id = $album_type['id'];
		$this->file_config_gid = $album_type['gid_upload_type'];
		$this->video_config_gid = $album_type['gid_upload_video'];
		$this->_user_id = $this->session->userdata('user_id');
	}


	function get_media_by_id($media_id, $formatted = true, $format_owner = false, $format_user = false) {
		$result = $this->DB->select(implode(", ", $this->_fields))->from(MEDIA_TABLE)->where("id", $media_id)->get()->result_array();
		if(empty($result)){
			return array();
		}else{
			if($formatted){
				$result = $this->format_items(array(0 => $result[0]), $format_owner, $format_user);
			}
			return $result[0];
		}
	}

	public function copy_media($media_id){
		if(!$media = $this->get_media_by_id($media_id, false)){
			return false;
		}

		if($media['id_parent']){
			$media_id = $media['id_parent'];
			if(!$media = $this->get_media_by_id($media_id, false)){
				return false;
			}
		}
		$media_attrs = array_intersect_key($media, array_flip($this->_fields_for_copy));
		$media_attrs['id_user'] = $this->_user_id;
		$media_attrs['id_parent'] = $media_id;
		$media_attrs['comments_count'] = 0;
		$media_attrs['views'] = 0;
		if($media_attrs['permissions'] == 1){
			$media_attrs['permissions'] = 0;
		}
		if($media_attrs['permissions'] == 2){
			$media_attrs['permissions'] = 1;
		}
		$return = $this->save_image(null, $media_attrs);

		return $return['errors'] ? 0 : $return['id'];
	}

	public function validate_image($data, $file_name = ''){
		$return = array('errors' => array(), 'data' => array(), 'form_error' => 0);

		if(isset($data["permissions"])){
			$return["data"]["permissions"] = intval($data["permissions"]);

			if(empty($return["data"]["permissions"]) ){
				$return["errors"][] = l('error_permissions_empty', 'media');
			}
		}

		if(!empty($data["fname"])){
			$return["data"]["fname"] = trim(strip_tags($data["fname"]));
		}

		if(isset($data["description"])){
			$return["data"]["description"] = trim(strip_tags($data["description"]));
		}

		$this->CI->load->model('moderation/models/Moderation_badwords_model');
		$bw_count = $this->CI->Moderation_badwords_model->check_badwords($this->moderation_type, $return["data"]["fname"]);
		$bw_count = $bw_count || $this->CI->Moderation_badwords_model->check_badwords($this->moderation_type, $return["data"]["description"]);
		if($bw_count){
			$return["errors"][] = l('error_badwords_message', 'media');
			$return['form_error'] = 1;
		}

		if (!empty($file_name) ){
			if(isset($_FILES[$file_name]) && is_array($_FILES[$file_name]) && !($_FILES[$file_name]["error"]) ){
				$this->CI->load->model("Uploads_model");
				$file_return = $this->CI->Uploads_model->validate_upload($this->file_config_gid, $file_name);
				if(!empty($file_return["error"])){
					$return["errors"][] = (is_array($file_return["error"]))?implode("<br>", $file_return["error"]):$file_return["error"];
				}
				$return["data"]['mime'] = $_FILES[$file_name]["type"];
			}elseif($_FILES[$file_name]["error"]){
				$return["errors"][] = $_FILES[$file_name]["error"];
			}else{
				$return["errors"][] = "empty file";
			}
		}
		return $return;
	}

	public function validate_video($data, $video_name = ''){
		$return = array("errors" => array(), "data" => array(), 'form_error' => 0);

		if(isset($data["permissions"])){
			$return["data"]["permissions"] = strip_tags($data["permissions"]);

			if(empty($return["data"]["permissions"]) ){
				$return["errors"][] = l('error_permissions_empty', 'media');
			}
		}

		if(isset($data["fname"])){
			$return["data"]["fname"] = strip_tags($data["fname"]);

			if(empty($return["data"]["fname"]) ){
				$return["errors"][] = l('error_fname_empty', 'media');
			}
		}

		if(isset($data["description"])){
			$return["data"]["description"] = strip_tags($data["description"]);
		}

		$this->CI->load->model('moderation/models/Moderation_badwords_model');
		$bw_count = $this->CI->Moderation_badwords_model->check_badwords($this->moderation_type, $return["data"]["fname"]);
		$bw_count = $bw_count || $this->CI->Moderation_badwords_model->check_badwords($this->moderation_type, $return["data"]["description"]);
		if($bw_count){
			$return["errors"][] = l('error_badwords_message', 'media');
			$return['form_error'] = 1;
		}

		$embed_data = array();
		if(!empty($data["embed_code"])){
			$this->load->library('VideoEmbed');
			$embed_data = $this->videoembed->get_video_data($data["embed_code"]);
			if($embed_data !== false){
				$embed_data["string_to_save"] = $this->videoembed->get_string_from_video_data($embed_data);
				$embed_data['upload_type'] = 'embed';
				$this->CI->load->model('uploads/models/Uploads_model');
				$return["data"]["media_video_image"] = $this->CI->Uploads_model->generate_filename('.jpg');
				$return["data"]["media_video_data"] = serialize(array('data' => $embed_data));
				$return["data"]["media_video"] = 'embed';
				$return["data"]["fname"] = $embed_data['service'];
			}else{
				$return["errors"][] = l('error_embed_wrong', 'media');
			}
		}

		if(!empty($video_name) && !$embed_data){
			if(isset($_FILES[$video_name]) && is_array($_FILES[$video_name]) && !($_FILES[$video_name]["error"])){
				$this->CI->load->model("Video_uploads_model");
				$video_return = $this->CI->Video_uploads_model->validate_upload($this->video_config_gid, $video_name);
				if(!empty($video_return["error"])){
					$return["errors"][] = (is_array($video_return["error"]))?implode("<br>", $video_return["error"]):$video_return["error"];
				}
				$return["data"]['mime'] = $_FILES[$video_name]["type"];
			}elseif(!empty($_FILES[$video_name]["error"])){
				$return["errors"][] = $_FILES[$video_name]["error"];
			}else{
				$return["errors"][] = l('error_file_empty', 'media');
			}
		}

		return $return;
	}

	/**
	 * Save image object
	 *
	 * @param array $attrs
	 * @return bool
	 */
	public function save_image($id, $data, $file_name="", $moderation = false){
		$return = array('errors' => '');

		if(!empty($id)){
			$this->DB->where('id', $id);
			$this->DB->update(MEDIA_TABLE, $data);
			$this->CI->load->model('media/models/Media_album_model');
			$this->CI->Media_album_model->update_media_album_items($data, $id);
		}else{
			$data["date_add"] = date('Y-m-d H:i:s');
			$this->DB->insert(MEDIA_TABLE, $data);
			$id = $this->DB->insert_id();
		}
		$return['id'] = $id;

		if(!empty($file_name) && !empty($id) && isset($_FILES[$file_name]) && is_array($_FILES[$file_name]) && is_uploaded_file($_FILES[$file_name]["tmp_name"])){
			$this->CI->load->model("Uploads_model");
			$img_return = $this->CI->Uploads_model->upload($this->file_config_gid, $data['id_owner'], $file_name);
			if(empty($img_return["errors"])){
				$img_data["mediafile"] = $img_return["file"];
				$img_data["fname"] = $img_return["file"];
				$upload = $this->CI->Uploads_model->format_upload($this->file_config_gid, $data['id_owner'], $img_return["file"]);
				$image_size = getimagesize($upload["file_path"]);
				$img_data["settings"]["width"] = $image_size[0];
				$img_data["settings"]["height"] = $image_size[1];
				$img_data["settings"] = serialize($img_data["settings"]);
				$this->save_image($id, $img_data);
				$return['file'] = $img_return["file"];

				//wall event
				if(!$moderation && !empty($data['status'])){
					$this->_create_wall_event($id);
				}
			} else {
				$return['errors'] = $img_return["errors"];
			}
		}
		return $return;
	}

	public function update_children($id_parent, $data){
		if(empty($id_parent)){
			return false;
		}

		$result = false;
		$media_ids = $this->get_media_ids(null, null, null, array('where' => array('id_parent' => $id_parent)));
		if($media_ids){
			$this->DB->where('id_parent', $id_parent)->update(MEDIA_TABLE, $data);
			$result = $this->DB->affected_rows();

			/* update media albums */
			$this->CI->load->model('media/models/Media_album_model');
			$this->CI->Media_album_model->update_media_album_items($data, $media_ids);
			/* */
		}

		return $result;
	}

	public function update_children_permissions($id_media, $permissions = null){
		if(is_null($permissions)){
			if(!$media = $this->get_media_by_id($id_media)){
				return false;
			}
			$permissions = $media['permissions'];
		}
		if($permissions == 1){
			$permissions = 0;
		}
		if($permissions == 2){
			$permissions = 1;
		}

		$result = false;
		$media_ids = $this->get_media_ids(null, null, null, array('where' => array('id_parent' => $id_media)));
		if($media_ids){
			$this->DB->set('permissions', $permissions)->where('id_parent', $id_media)->update(MEDIA_TABLE);
			$result = $this->DB->affected_rows();

			/* update media albums */
			$data['permissions'] = $permissions;
			$this->CI->load->model('media/models/Media_album_model');
			$this->CI->Media_album_model->update_media_album_items($data, $media_ids);
			/* */
		}

		return $result;
	}

	/**
	 * Save image object
	 *
	 * @param array $attrs
	 * @return bool
	 */
	public function save_video($id, $data, $video_name="", $create_event = false){
		$return = array('errors' => '');
		
		if($data['media_video'] == 'embed'){
			$this->CI->load->model("Video_uploads_model");
			$data = $this->CI->Video_uploads_model->upload_embed_video_image($this->video_config_gid, $data);
		}
		
		if(!empty($id)){
			$this->DB->where('id', $id);
			$this->DB->update(MEDIA_TABLE, $data);
			$this->CI->load->model('media/models/Media_album_model');
			$this->CI->Media_album_model->update_media_album_items($data, $id);
		}else{
			$data["date_add"] = date('Y-m-d H:i:s');
			$this->DB->insert(MEDIA_TABLE, $data);
			$id = $this->DB->insert_id();
		}
		$return['id'] = $id;
		if(!empty($video_name) && !empty($id) && isset($_FILES[$video_name]) && is_array($_FILES[$video_name]) && is_uploaded_file($_FILES[$video_name]["tmp_name"])){
			$this->CI->load->model("Video_uploads_model");
			$video_return = $this->CI->Video_uploads_model->upload($this->video_config_gid, $data['id_owner'], $video_name, $id, $video_data, 'generate');

			if(empty($video_return["errors"])){
				$video_data["fname"] = $video_return["file"];
				$this->save_video($id, $video_data);
				$return['file'] = $video_return["file"];
			} else {
				$return['errors'] = $video_return["errors"];
			}
		}

		if($create_event){
			$this->_create_wall_event($id);
		}

		return $return;
	}

	/**
	 * Get objects list
	 * banners - default return all object
	 * @return array
	 */
	public function get_media($page = 1, $items_on_page = 20, $order_by = null, $params = array(), $filter_object_ids = null, $format_items = true, $format_owner = false, $format_user = false, $safe_format = false){
		$this->DB->select(implode(", ", $this->_fields));
		$this->DB->from(MEDIA_TABLE);

		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value){
				$this->DB->where($field, $value);
			}
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value){
				$this->DB->where_in($field, $value);
			}
		}

		if(isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])){
			foreach($params["where_sql"] as $value){
				$this->DB->where($value);
			}
		}

		if(isset($params['limit']['count'])){
			if(isset($params['limit']['from'])){
				$this->DB->limit($params['limit']['count'], $params['limit']['from']);
			}else{
				$this->DB->limit($params['limit']['count']);
			}
		}

		if(isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)){
			$filter_object_ids = array_slice($filter_object_ids, 0, 5000);
			$this->DB->where_in("id", $filter_object_ids);
		}

		if(is_array($order_by) && count($order_by)>0){
			foreach($order_by as $field => $dir){
				if(in_array($field, $this->_fields)){
					$this->DB->order_by($field." ".$dir);
				}
			}
		}

		if(!is_null($page) ){
			$page = intval($page)?intval($page):1;
			$this->DB->limit($items_on_page, $items_on_page*($page-1));
		}
		$result = $this->DB->get()->result_array();

		if($format_items){
			$result = $this->format_items($result, $format_owner, $format_user, $safe_format);
		}

		return $result;
	}

	public function get_media_by_key($page = 1, $items_on_page = 20, $order_by = null, $params = array(), $filter_object_ids = null, $format_items = true, $format_owner = false, $format_user = false, $safe_format = false){
		$media = $this->get_media($page, $items_on_page, $order_by, $params, $filter_object_ids, $format_items, $format_owner, $format_user, $safe_format);
		$media_by_key = array();
		foreach($media as $m){
			$media_by_key[$m['id']] = $m;
		}
		return $media_by_key;
	}


	/**
	 * Get objects ids list
	 * banners - default return all object
	 * @return array
	 */
	public function get_media_ids($page = 1, $items_on_page = 20, $order_by = null, $params = array(), $filter_object_ids = null){
		$objects = array();
		$this->DB->select('id');
		$this->DB->from(MEDIA_TABLE);

		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value){
				$this->DB->where($field, $value);
			}
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value){
				$this->DB->where_in($field, $value);
			}
		}

		if(isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])){
			foreach($params["where_sql"] as $value){
				$this->DB->where($value);
			}
		}

		if(isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)){
			$this->DB->where_in("id", $filter_object_ids);
		}

		if(is_array($order_by) && count($order_by)>0){
			foreach($order_by as $field => $dir){
				if(in_array($field, $this->_fields)){
					$this->DB->order_by($field." ".$dir);
				}
			}
		}

		if(!is_null($page) ){
			$page = intval($page)?intval($page):1;
			$this->DB->limit($items_on_page, $items_on_page*($page-1));
		}
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			foreach($results as $result){
				$objects[] = $result['id'];
			}
			//$objects = $this->format_items($objects);
		}
		return $objects;
	}

	public function format_items($data, $format_owner = false, $format_user = false, $safe_format = false){
		if(empty($data) || !is_array($data)) return array();

		$init_format_user = $this->format_user;
		if($this->format_user){
			$format_owner = $format_user = true;
		}
		$this->format_user = $format_owner || $format_user;
		$this->CI->load->model('Uploads_model');
		$this->CI->load->model('Video_uploads_model');
		if ($this->format_user){
			$this->CI->load->model('Users_model');
		}
		$users_ids = array();
		foreach($data as $key => &$item){
			if ($item['upload_gid'] == $this->file_config_gid){
				if(!empty($item["mediafile"]) && $item['permissions'] > 0){
					$item["media"]["mediafile"] = $this->CI->Uploads_model->format_upload($item['upload_gid'], $item['id_owner'], $item['mediafile']);
				}else{
					$item["media"]["mediafile"] = $this->CI->Uploads_model->format_default_upload($item['upload_gid']);
				}
			} elseif ($item['upload_gid'] == $this->video_config_gid) {
				if(!empty($item["media_video_data"])){
					$item["media_video_data"] = unserialize($item["media_video_data"]);
				}

				if(!empty($item["media_video"])){
					$media_video = ($item["media_video"] == 'embed') ? $item['media_video_data']['data'] : $item['media_video'];
					$item["video_content"] = $this->CI->Video_uploads_model->format_upload($this->video_config_gid, $item['id_owner'], $media_video, $item['media_video_image'], $item['media_video_data']['data']['upload_type']);
				}
			}
			if ($this->format_user){
				if($format_user){
					$users_ids[$item['id_user']] = $item['id_user'];
				}
				if($format_owner){
					$users_ids[$item['id_owner']] = $item['id_owner'];
				}
			}
			$item["settings"] = $item["settings"] ? (array)unserialize($item["settings"]) : array();
		}

		if($users_ids){
			$users = $this->Users_model->get_users_list_by_key(null, null, null, array(), $users_ids, false);
			$users = $this->Users_model->format_users($users, $safe_format);
			$users[0] = $this->Users_model->format_default_user(1);
			foreach($data as $key => &$item){
				if ($this->format_user){
					if($format_user){
						$item['user_info'] = !empty($users[$item['id_user']]) ? $users[$item['id_user']] : $users[0];
					}
					if($format_owner){
						$item['owner_info'] = !empty($users[$item['id_owner']]) ? $users[$item['id_owner']] : $users[0];
					}
				}
			}
		}
		$this->format_user = $init_format_user;
		return $data;
	}

	/*
	 * Work like get_media method, but return number of objects
	 * necessary for pagination
	 * banners - default return all object
	 */
	public function get_media_count($params = array(), $filter_object_ids = null)
	{
		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value){
				$this->DB->where($field, $value);
			}
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value){
				$this->DB->where_in($field, $value);
			}
		}

		if(isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])){
			foreach($params["where_sql"] as $value){
				$this->DB->where($value);
			}
		}

		if(isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)){
			$this->DB->where_in("id", $filter_object_ids);
		}

		if(isset($params['limit']['count'])){
			if(isset($params['limit']['from'])){
				$this->DB->limit($params['limit']['count'], $params['limit']['from']);
			}else{
				$this->DB->limit($params['limit']['count']);
			}
			return count($this->DB->select('*')->from(MEDIA_TABLE)->get()->result_array());
		}else{
			return $this->DB->count_all_results(MEDIA_TABLE);
		}
	}

	////// video callback
	public function video_callback($id, $status, $data, $errors){
		$media_data = $this->get_media_by_id($id);

		if(isset($data["video"])){
			$update["media_video"] = $data["video"];
		}
		if(isset($data["image"])){
			$update["media_video_image"] = $data["image"];
		}

		$update["media_video_data"] = $media_data["media_video_data"];

		if($status == 'start'){
			$update["media_video_data"] = array();
		}

		if(!isset($update["media_video_data"]["data"])){
			$update["media_video_data"]["data"] = array();
		}

		if(!empty($data)){
			$update["media_video_data"]["data"] = array_merge($update["media_video_data"]["data"], $data);
		}

		$update["media_video_data"]["status"] = $status;
		$update["media_video_data"]["errors"] = $errors;
		$update["media_video_data"] = serialize($update["media_video_data"]);
		$this->save_video($id, $update);
		return;
	}

	public function delete_media($media_id){
		$media = $this->get_media_by_id($media_id);
		if(!$media){
			return;
		}

		$this->CI->load->model('media/models/Media_album_model');
		$this->CI->load->model('Moderation_model');

		$this->DB->where('id', $media['id'])->delete(MEDIA_TABLE);
		$this->CI->Media_album_model->delete_media_from_all_albums($media_id);
		$this->CI->Moderation_model->delete_moderation_item_by_obj($this->moderation_type, $media_id);

		if(!empty($media['mediafile'])){
			$this->update_children($media['id'], array('mediafile' => ''));
			if($media['id_user'] == $media['id_owner']){
				$this->DB->where('mediafile', $media['mediafile']);
				$this->DB->delete(MEDIA_TABLE);
				$this->CI->load->model('Uploads_model');
				$this->CI->Uploads_model->delete_upload($this->file_config_gid, $media['id_owner'], $media['mediafile']);
			}
		}

		if(!empty($media['media_video'])){
			$this->update_children($media['id'], array('media_video' => ''));
			if($media['id_user'] == $media['id_owner']){
				if($media['media_video'] !== 'embed'){
					$this->DB->where('media_video', $media['media_video']);
					$this->DB->delete(MEDIA_TABLE);
					$this->CI->load->model('Video_uploads_model');
					$this->CI->Video_uploads_model->delete_upload($this->video_config_gid, $media['id_owner'], $media['media_video'], $media['media_video_data']['data']['upload_type']);
				}elseif($media['media_video'] === 'embed'){
					$this->CI->load->model('Video_uploads_model');
					$this->CI->Video_uploads_model->delete_embed_video_image($this->video_config_gid, $media['id_owner'], $media['media_video_image']);
				}
			}
		}

		$children_ids = $this->get_parent_media_list_ids($media_id);

		if(!empty($children_ids) && is_array($children_ids)){
			foreach($children_ids as $children_id){
				$this->delete_media($children_id);
			}
		}
	}

	public function get_parent_media_list_ids($id){
		if(empty($this->_cache['parent_media_list_ids'][$id])) {
			$this->DB->select('id');
			$this->DB->from(MEDIA_TABLE);
			$this->DB->where('id_parent', $id);

			$results = $this->DB->get()->result_array();
			$objects = array();
			if(!empty($results) && is_array($results)){
				foreach($results as $result){
					$objects[] = $result['id'];
				}
			}
			$this->_cache['parent_media_list_ids'][$id] = $objects;
		}
		return $this->_cache['parent_media_list_ids'][$id];
	}

	///// moderation functions
	public function _moder_get_list($object_ids) {
		$params["where_in"]["id"] = $object_ids;
		$media = $this->get_media(null, null, null, $params);

		if (!empty($media)) {
			foreach ($media as $m) {
				$return[$m["id"]] = $m;
			}
			return $return;
		} else {
			return array();
		}
	}

	public function _moder_set_status($object_id, $status) {
		$new_attrs = array();
		switch ($status) {
			case 0:
				$new_attrs['status'] = -1;
				break;
			case 1:
				$new_attrs['status'] = 1;
				break;
		}
		$this->save_image($object_id, $new_attrs);
		if($status){
			$this->_create_wall_event($object_id);
		}
	}

	public function _moder_mark_adult($object_id){
		$attrs['is_adult'] = 1;
		$this->save_image($object_id, $attrs);
	}

	public function comments_count_callback($count, $id = 0){
		if($id){
			$this->DB->where('id', $id);
		}
		$data['comments_count'] = $count;
		$this->DB->update(MEDIA_TABLE, $data);
	}

	public function comments_object_callback($id = 0){
		$return = array();
		$object = $this->get_media(null, null, null, null, (array)$id);
		if (!empty($object)) {
			foreach($object as $media){//echo "<pre>"; print_r($media); echo "</pre>";
				if ($media["upload_gid"]=="gallery_image") {
					$return["body"] = "<img src='".$media['media']['mediafile']['thumbs']['great']."'/>";
				} else {
					$return["body"] = $media['video_content']['embed'];
				}
				$return["author"] =  $media['user_info']['output_name'];
			}
		}
		return $return;
	}

	public function get_list($user_id, $param = 'all', $page = 1, $album_id = 0, $use_permissions = true, $order_by = array('date_add' => 'DESC')){
		$return = array('content' => '', 'have_more' => 0);

		$where = array();
		if($use_permissions){
			$perm = $this->get_user_permissions($user_id);
			$where['where']['permissions >='] = $perm;
		}
		$show_adult = ($user_id && $user_id == $this->_user_id) ? true : $this->session->userdata('show_adult');
		if(!$show_adult){
			$where['where']['is_adult'] = 0;
		}
		switch($param){
			case 'photo' : $where['where']['upload_gid'] = $this->file_config_gid; break;
			case 'video' : $where['where']['upload_gid'] = $this->video_config_gid; break;
			case 'favorites' :
				$this->CI->load->model('media/models/Albums_model');
				$defaullt_album = $this->CI->Albums_model->get_default($this->_user_id);
				$album_id = $defaullt_album['id'];
				break;
		}
		$where['where']['id_user'] = intval($user_id);
		if ($where['where']['id_user'] != $this->_user_id){
			$where['where']['status'] = 1;
		}
		$tpl_data = array();
		$media_ids = array();
		if ($album_id){
			$this->CI->load->model('media/models/Media_album_model');
			$media_ids = $this->CI->Media_album_model->get_media_ids_in_album($album_id);

			$this->CI->load->model('media/models/Albums_model');
			$album = $this->CI->Albums_model->get_album_by_id($album_id);
			$tpl_data['album'] = $album;
		}
		$this->CI->load->helper('sort_order');
		$items_on_page = $this->CI->pg_module->get_module_config('media', 'items_per_page');
		$media_count = (!$album_id || $media_ids) ? $this->get_media_count($where, $media_ids) : 0;
		$exists_page = get_exists_page_number($page, $media_count, $items_on_page);
		$next_page = get_exists_page_number($exists_page + 1, $media_count, $items_on_page);
		if ($next_page > $exists_page){
			$return['have_more'] = 1;
		}

		$media = (!$album_id || $media_ids) ? $this->get_media($exists_page, $items_on_page, $order_by, $where, $media_ids) : array();
		$tpl_data['media'] = $media;
		$tpl_data['media_count'] = $media_count;

		if($this->CI->router->is_api_class) {
			return $tpl_data;
		} else {
			$this->CI->template_lite->assign($tpl_data);
			if ($where['where']['id_user'] == $this->_user_id){
				$return['content'] = trim($this->CI->template_lite->fetch_final('list', 'user', 'media'));
			} else {
				$return['content'] = trim($this->CI->template_lite->fetch_final('view_list', 'user', 'media'));
			}
			return $return;
		}

	}

	public function get_gallery_list($count, $param = 'all', $loaded_count = 0, $album_id = 0, $order_by = array('date_add' => 'DESC'), $params = array()){
		$return = array('content' => '', 'have_more' => 0);

		if(!$count){
			return $return;
		}

		$params['where']['status'] = 1;
		$params['where']['id_parent'] = 0;
		$params['where']['permissions >='] = $this->get_user_permissions(0);

		if(!$this->session->userdata('show_adult')){
			$params['where']['is_adult'] = 0;
		}
		switch($param){
			case 'photo' : $params['where']['upload_gid'] = $this->file_config_gid; break;
			case 'video' : $params['where']['upload_gid'] = $this->video_config_gid; break;
			case 'favorites':
				unset($params['where']['id_parent']);
				$this->CI->load->model('media/models/Albums_model');
				$album = $this->CI->Albums_model->get_default($this->_user_id);
				$album_id = $album['id'];
				$params['where']['id_user'] = $this->_user_id;
				$params['where']['id_owner <>'] = $this->_user_id;
				break;
		}

		$media_ids = array();
		if ($album_id){
			$this->CI->load->model('media/models/Media_album_model');
			$media_ids = $this->CI->Media_album_model->get_media_ids_in_album($album_id);
		}

		$params['limit']['count'] = $count+1;
		$params['limit']['from'] = $loaded_count;
		if(!$album_id || $media_ids) {
			$media_count = $this->get_media_count($params, $media_ids);
			if($media_count > $count){
				$return['have_more'] = 1;
				$media_count--;
			}
		} else {
			$media_count = 0;
		}
		$params['limit']['count'] = $count;

		$return['media'] = (!$album_id || $media_ids) ? $this->get_media(null, null, $order_by, $params, $media_ids, true, false, true, true) : array();

		if(!$album_id && !$loaded_count && !$media_count){
			$return['msg'] = l('no_media', 'media');
		}

		$return['media_count'] = $media_count;
		$return['requested_count'] = $count;

		return $return;
	}

	public function get_albums($id_user, $page = 1){
		$return = array('content' => '', 'have_more' => 0);
		$this->CI->load->model('media/models/Albums_model');
		$params = array();
		$params["where"]["id_user"] = $id_user;
		$is_user_album_owner = ($id_user && $id_user == $this->_user_id);
		$albums_count_field = 'media_count';
		if(!$is_user_album_owner){
			$perm = $this->get_user_permissions($id_user);
			$params['where']['permissions >='] = $perm;
			$albums_count_field = $this->_user_id ? 'media_count_user' : 'media_count_guest';
		}
		$this->CI->load->helper('sort_order');
		$items_on_page = $this->pg_module->get_module_config('media', 'items_per_page');
		$albums_count = $this->CI->Albums_model->get_albums_count($params);
		$exists_page = get_exists_page_number($page, $albums_count, $items_on_page);
		$next_page = get_exists_page_number($exists_page + 1, $albums_count, $items_on_page);
		if ($next_page > $exists_page){
			$return['have_more'] = 1;
		}
		$albums = $this->CI->Albums_model->get_albums_list($params, null, null, $exists_page, $items_on_page);
		$this->CI->template_lite->assign('albums', $albums);
		$this->CI->template_lite->assign('albums_page', $exists_page);
		$this->CI->template_lite->assign('is_user_album_owner', $is_user_album_owner);
		$this->CI->template_lite->assign('albums_count_field', $albums_count_field);

		$return['content'] = $this->CI->template_lite->fetch('albums_list', 'user', 'media');

		return $return;
	}

	function get_albums_select($id_user){
		$this->CI->load->model('media/models/Albums_model');
		$params['where']['id_user'] = intval($id_user);
		$is_user_album_owner = ($id_user && $id_user == $this->_user_id);
		$albums_count_field = 'media_count';
		if(!$is_user_album_owner){
			$perm = $this->get_user_permissions($id_user);
			$params['where']['permissions >='] = $perm;
			$albums_count_field = $this->_user_id ? 'media_count_user' : 'media_count_guest';
		}
		$albums_list = $this->CI->Albums_model->get_albums_list($params, null, null, null, null, false);
		$this->CI->template_lite->assign('is_user_album_owner', $is_user_album_owner);
		$this->CI->template_lite->assign('albums_list', $albums_list);
		$this->CI->template_lite->assign('albums_count_field', $albums_count_field);
		return $this->CI->template_lite->fetch('albums_select', 'user', 'media');
	}

	public function get_media_position_info($media_id, $param = 'all', $album_id = 0, $user_id = 0, $use_permissions = true, $order_by = array('date_add' => 'DESC'), $filter_duplicate = false){
		$return = array('position' => 0, 'count' => 0);
		$where = array();
		if($use_permissions){
			$perm = $this->get_user_permissions($user_id);
			$where['where']['permissions >='] = $perm;
		}
		$show_adult = ($this->_user_id && $user_id == $this->_user_id) ? true : $this->session->userdata('show_adult');
		if(!$show_adult){
			$where['where']['is_adult'] = 0;
		}
		switch($param){
			case 'photo' : $where['where']['upload_gid'] = $this->file_config_gid; break;
			case 'video' : $where['where']['upload_gid'] = $this->video_config_gid; break;
		}
		if($user_id){
			$where["where"]["id_user"] = $user_id;
		}
		if($filter_duplicate){
			$where['where']['id_parent'] = 0;
		}
		if ($where['where']['id_user'] != $this->_user_id){
			$where['where']['status'] = 1;
		}

		$media_ids = array();
		if ($album_id){
			$this->CI->load->model('media/models/Media_album_model');
			$media_ids = $this->CI->Media_album_model->get_media_ids_in_album($album_id);
		}
		if(!$album_id || $media_ids){
			$media_ids = $this->get_media_ids(null, null, $order_by, $where, $media_ids);
		}

		$return['position'] = array_search($media_id, $media_ids) + 1;
		$return['count'] = count($media_ids);
		$return['next'] = $return['position'] < $return['count'] ? $media_ids[$return['position']] : 0;
		$return['previous'] = $return['position'] > 1 ? $media_ids[$return['position']-2] : 0;

		$media_ids = array($return['next'], $return['previous']);
		$medias = $this->get_media_by_key(null, null, null, array(), $media_ids);
		$return['next_type'] = !empty($medias[$return['next']]) ? $medias[$return['next']]['upload_gid'] : null;
		$return['previous_type'] = !empty($medias[$return['previous']]) ? $medias[$return['previous']]['upload_gid'] : null;
		$return['next_image'] = (!empty($medias[$return['next']]) && $return['next_type'] == $this->file_config_gid) ? $medias[$return['next']]['media']['mediafile']['file_url'] : null;
		$return['previous_image'] = (!empty($medias[$return['previous']]) && $return['next_type'] == $this->file_config_gid) ? $medias[$return['previous']]['media']['mediafile']['file_url'] : null;

		return $return;
	}

	public function get_media_type_by_id($media_id){
		$media = $this->get_media_by_id($media_id);
		return $media['upload_gid'];
	}

	public function increment_media_views($media_id, $delta = '1') {
		$this->db->set('views', 'views + '.$delta, FALSE);
		$this->DB->where('id', $media_id);
		$this->DB->update(MEDIA_TABLE);
	}

	public function decrement_media_views($media_id, $delta = '1') {
		$this->db->set('views', 'views - '.$delta, FALSE);
		$this->DB->where('id', $media_id);
		$this->DB->update(MEDIA_TABLE);
	}

	public function is_user_media_owner($media_id){
		$this->DB->select("COUNT(*) AS cnt");
		$this->DB->from(MEDIA_TABLE);
		$this->DB->where('id', $media_id);
		$this->DB->where('id_owner', $this->_user_id);

		$results = $this->DB->get()->result();
		if(!empty($results) && is_array($results)){
			return intval($results[0]->cnt);
		}
		return false;
	}

	public function is_user_media_user($media_id){
		$this->DB->select("COUNT(*) AS cnt");
		$this->DB->from(MEDIA_TABLE);
		$this->DB->where('id', $media_id);
		$this->DB->where('id_user', $this->_user_id);

		$results = $this->DB->get()->result();
		if(!empty($results) && is_array($results)){
			return intval($results[0]->cnt);
		}
		return false;
	}

	public function is_access_permitted($media_id, $media = array()){
		if(!$media){
			$media = $this->get_media_by_id($media_id);
		}
		if(!$media){
			return false;
		}
		if($this->_user_id && $media['id_user'] === $this->_user_id){
			return true;
		}
		switch($media['permissions']){
			case 1: //only for me
				return false;
			case 2: // friends
				if($this->pg_module->is_module_installed('users_lists')){
					$this->CI->load->model('Users_lists_model');
					$is_friend = $this->CI->Users_lists_model->is_friend($this->_user_id, $media['id_owner']);
					return $is_friend;
				}
				break;
			case 3: //registered
				if($this->_user_id){
					return true;
				}
				break;
			case 4: //all
				return true;
			default:
				return false;
		}

		return false;
	}

	public function get_user_permissions($id_owner){
		if(!$this->_user_id){
			return 4;
		}
		$perm = 3;
		if(!$id_owner){
			return $perm;
		}
		if($id_owner == $this->_user_id){
			return 0;
		}
		if($this->pg_module->is_module_installed('users_lists')){
			$this->CI->load->model('Users_lists_model');
			$perm = $this->CI->Users_lists_model->is_friend($this->_user_id, $id_owner) ? 2 : 3;
		}
		return $perm;
	}

	/**
	 * Install reviews fields
	 * @param array $fields
	 */
	/*public function install_reviews_fields($fields=array()){
		if(empty($fields)) return;
		$this->CI->load->dbforge();
		$table_fields = $this->CI->db->get(MEDIA_TABLE)->list_fields();
		foreach((array)$fields as $field_name=>$field_data){
			if(!in_array($field_name, $table_fields)){
				$this->CI->dbforge->add_column(MEDIA_TABLE, array($field_name=>$field_data));
			}
		}
	}*/

	/**
	 * Uninstall reviews fields
	 * @param array $fields
	 */
	/*public function deinstall_reviews_fields($fields=array()){
		if(empty($fields)) return;
		$this->CI->load->dbforge();
		$table_fields = $this->db->get(MEDIA_TABLE)->list_fields();
		foreach($fields as $field_name){
			if(in_array($field_name, $table_fields)){
				$this->dbforge->drop_column(MEDIA_TABLE, $field_name);
			}
		}
	}*/

	/**
	 * Callback for reviews module
	 * @param string $action action name
	 * @param array $data review data
	 * @return string
	 */
	public function callback_reviews($action, $data){
		$id = $data['id_object'];
		switch($action){
			case 'update':
				$media_data['review_type'] = $data['type_gid'];
				$media_data['review_sorter'] = $data['review_sorter'];
				$media_data['review_count'] = $data['review_count'];
				$media_data['review_data'] = serialize($data['review_data']);
				$this->save_image($id ,$media_data);
			break;
			case 'get_object':
				if(empty($data)) return array();
				$where['where_in']['id'] = (array)$data;
				$listings = $this->get_media(null, null, null, $where);
				$return = array();
				foreach($listings as $listing){
					$return[$listing['id']] = $listing;
				}
				return $return;
			break;
		}
	}

	private function _dynamic_block_get_users_media($type, $params, $view, $width, $gallery_params = array()){
		$data['rand'] = rand(1, 999999);
		$data['type'] = $type;
		$data['params'] = $params;
		$data['view'] = $view;
		$data['width'] = $width;
		$data['title'] = $params['title_'.$this->pg_language->current_lang_id];
		$data['media'] = $this->get_gallery_list($params['count'], $type, 0, 0, array('date_add' => 'DESC'), $gallery_params);

		$this->CI->template_lite->assign('dynamic_block_users_media_data', $data);
		return $this->CI->template_lite->fetch('dynamic_block_users_media', 'user', 'media');
	}

	public function _dynamic_block_get_users_photos($params, $view, $width){
		return $this->_dynamic_block_get_users_media('photo', $params, $view, $width);
	}

	public function _dynamic_block_get_users_videos($params, $view, $width){
		$gallery_params['where']['media_video_image !='] = '';
		return $this->_dynamic_block_get_users_media('video', $params, $view, $width, $gallery_params);
	}

	public function _format_wall_events($events){
		$is_permissions_allowed = function($media, $friends_ids, $user_id, $show_adult){
			if(!$media['status']){
				return false;
			}
			if($media['is_adult'] && !$show_adult){
				return false;
			}
			$perm = $user_id ? 3 : 4;
			if(isset($friends_ids[$media['id_owner']])){
				$perm = 2;
			}
			if($media['id_owner'] == $user_id){
				$perm = 0;
			}
			if(intval($media['permissions']) < $perm){
				return false;
			}
			return true;
		};

		$formatted_events = array();
		$media_ids = array();
		foreach($events as $key => $e){
			foreach($e['data'] as $mkey => $media){
				if (($this->get_media_count(array('where_in'=>array('id'=>$media['id']))))!=0){
					$media_ids[$media['id']] = $media['id'];
				}
			}
		}
		if($media_ids){
			$medias = $this->get_media_by_key(null, null, null, array(), $media_ids);
			if($medias){
				$friends_ids = array();
				if($this->_user_id && $this->pg_module->is_module_installed('users_lists')){
					$this->CI->load->model('Users_lists_model');
					$friends_ids = array_flip($this->CI->Users_lists_model->get_friendlist_users_ids($this->_user_id));
				}
				foreach($events as $key => $e){
					foreach($e['data'] as $mkey => $media){
						if(!empty($medias[$media['id']]) && $is_permissions_allowed($medias[$media['id']], $friends_ids, $this->_user_id, $this->session->userdata('show_adult'))){
							$e['data'][$mkey] = $medias[$media['id']];
						}else{
							unset($e['data'][$mkey]);
						}
					}
					if($e['data']){
						$e['media_count_all'] = $e['media_count']= count($e['data']);
						if($e['media_count_all'] > $this->wall_event_media_limit+4){
							$e['media_count'] = $this->wall_event_media_limit;
							$e['media_count_more'] = $e['media_count_all'] - $e['media_count'];
							$e['data'] = array_slice($e['data'], 0, $this->wall_event_media_limit);
						}
						$this->CI->template_lite->assign('event', $e);
						$e['html'] = $this->CI->template_lite->fetch('wall_events_media', null, 'media');
						$formatted_events[$key] = $e;
					} else {
						$e['html_delete'] = "<span class='spam_object_delete'>".l("error_is_deleted_wall_events_object", "spam")."</span>";
						$formatted_events[$key] = $e;
					}
				}
			}
		} else {
			foreach($events as $key => $e){
				$e['html_delete'] = "<span class='spam_object_delete'>".l("error_is_deleted_wall_events_object", "spam")."</span>";
				$formatted_events[$key] = $e;
			}
		}
		return $formatted_events;
	}

	private function _create_wall_event($media_id){
		$media = $this->get_media_by_id($media_id);
		if($media && $media['id_owner'] == $media['id_user']){
			$this->CI->load->helper('wall_events_default');
			$event_data['id'] = $media['id'];
			$event_data['id_user'] = $media['id_user'];
			$event_data['id_owner'] = $media['id_owner'];
			$event_data['description'] = $media['description'];
			$event_data['permissions'] = $media['permissions'];
			$event_data['is_adult'] = !empty($media['is_adult']);
			$event_data['upload_gid'] = $media['upload_gid'];
			$event_data['date_add'] = $media['date_add'];
			$e_gid = '';
			switch($media['upload_gid']){
				case $this->video_config_gid: $event_data['upload'] = $media['video_content']; $e_gid = 'video_upload'; break;
				case $this->file_config_gid: $event_data['upload'] = $media['media']['mediafile']; $e_gid = 'image_upload'; break;
			}
			unset($event_data['upload']['thumbs_data']);
			$event_result = add_wall_event($e_gid, $media['id_user'], $media['id_user'], $event_data, $media['id']);
			return $event_result;
		}
		return false;
	}

	/* SEO */
	public function get_seo_settings($method = '', $lang_id = '') {
		if (!empty($method)) {
			return $this->_get_seo_settings($method, $lang_id);
		} else {
			$actions = array('index', 'all', 'photo', 'video', 'albums');
			$return = array();
			foreach ($actions as $action) {
				$return[$action] = $this->_get_seo_settings($action, $lang_id);
			}
			return $return;
		}
	}

	private function _get_seo_settings($method, $lang_id = '') {
		switch($method){
			case 'index':
			case 'all':
				return array(
					"templates" => array(),
					"url_vars" => array()
				); break;
			case 'photo':
				return array(
					"templates" => array(),
					"url_vars" => array()
				); break;
			case 'video':
				return array(
					"templates" => array(),
					"url_vars" => array()
				); break;
			case 'albums':
				return array(
					"templates" => array(),
					"url_vars" => array()
				); break;
		}
	}

	public function request_seo_rewrite($var_name_from, $var_name_to, $value) {
		if ($var_name_from == $var_name_to) {
			return $value;
		}
	}

	function get_sitemap_xml_urls(){
		$this->CI->load->helper('seo');
		$return = array(
			array(
				"url" => rewrite_link('media', 'all'),
				"priority" => 0.1
			),
			array(
				"url" => rewrite_link('media', 'photo'),
				"priority" => 0.1
			),
			array(
				"url" => rewrite_link('media', 'video'),
				"priority" => 0.1
			),
			array(
				"url" => rewrite_link('media', 'albums'),
				"priority" => 0.1
			),
		);
		return $return;
	}

	function get_sitemap_urls(){
		$this->CI->load->helper('seo');
		$auth = $this->CI->session->userdata("auth_type");

		$block[] = array(
			"name" => l('header_gallery', 'media'),
			"link" => rewrite_link('media', 'index'),
			"clickable" => true,
			"items" => array(
				array(
					"name" => l('header_gallery_photo', 'media'),
					"link" => rewrite_link('media', 'photo'),
					"clickable" => true,
				),
				array(
					"name" => l('header_gallery_video', 'media'),
					"link" => rewrite_link('media', 'video'),
					"clickable" => true,
				),
				array(
					"name" => l('header_gallery_albums', 'media'),
					"link" => rewrite_link('media', 'albums'),
					"clickable" => true,
				),
			)
		);
		return $block;
	}

	/**
	 * Callback for spam module
	 * @param string $action action name
	 * @param integer $user_ids user identifiers
	 * @return string
	 */
	public function spam_callback($action, $data){
		switch($action){
			case "delete":
				$this->delete_media((int)$data);
				return "removed";
			break;
			case 'get_content':
				if(empty($data)) return array();
				$new_data = array();
				$return = array();
				foreach ($data as $id){
					if (($this->get_media_count(array('where_in'=>array('id'=>$id))))==0){
						$return[$id]["content"]["view"] = $return[$id]["content"]["list"] = "<span class='spam_object_delete'>".l("error_is_deleted_media_object", "spam")."</span>";
						$return[$id]["user_content"] = l("author_unknown", "spam");
					} else {
						$new_data[] = $id;
					}
				}
				$medias = $this->get_media(null, null, null, null, (array)$new_data, true, false, true);
				foreach($medias as $media){
					if ($media["upload_gid"]=="gallery_image") {
						$return[$media['id']]["content"]["list"] = "<img src='".$media['media']['mediafile']['thumbs']['hgreat']."'/>";
						$return[$media['id']]["content"]["view"] = "<img src='".$media['media']['mediafile']['thumbs']['great']."'/>";
					} else {
						if (preg_match_all("/id=\"default_player_([0-9]+)\">/is", $event['html'], $match)){
							$return[$media['id']]["rand"] = $match[1];
							foreach ($match[1] as $key=>$rand){
								$media['video_content']['embed'] = str_replace($rand, $rand."_".$key, $media['video_content']['embed']);
							}
						}
						$return[$media['id']]["content"]["list"] = $media['video_content']['embed'];
						$return[$media['id']]["content"]["view"] = $media['video_content']['embed'];
					}
					$return[$media['id']]["user_content"] = $media['user_info']['output_name'];
				}
				return $return;
			break;
			case 'get_subpost':
				return array();
			break;
			case 'get_link':
				return array();
			break;
			case 'get_deletelink':
				if(empty($data)) return array();
				$medias = $this->get_media(null, null, null, null, (array)$data);
				$return = array();
				foreach($medias as $media){
					$return[$media['id']] = site_url().'admin/spam/delete_content/';
				}
				return $return;
			break;
			case 'get_object':
				if(empty($data)) return array();
				$medias = $this->get_media_by_key(null, null, null, null, (array)$data);
				return $medias;
			break;
		}
	}
	
	public function get_recent_media($count = '16', $param = 'all'){
        return $this->get_gallery_list($count, $param);
    }
}
