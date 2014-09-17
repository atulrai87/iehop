<?php
/**
* Content us admin side controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/
Class Admin_Content extends Controller
{
	/**
	 * link to CodeIgniter object
	 * @var object
	 */

	/**
	 * Constructor
	 */
	function Admin_Content()
	{
		parent::Controller();
		$this->load->model("Content_model");
		$this->load->model('Menu_model');
		$this->Menu_model->set_menu_active_item('admin_menu', 'content_items');
	}

	function index($lang_id=null){
		if(!$lang_id) $lang_id = $this->pg_language->get_default_lang_id();

		$languages = $this->pg_language->languages;
		$this->template_lite->assign("languages", $languages);
		$this->template_lite->assign("current_lang", $lang_id);

		$pages = $this->Content_model->get_pages_list($lang_id);
		$this->template_lite->assign("pages", $pages);

		$this->pg_theme->add_js('admin-multilevel-sorter.js');
		$this->system_messages->set_data('header', l('admin_header_page_list', 'content'));
		$this->template_lite->view('list');
	}

	function edit($lang_id, $parent_id=0, $page_id=null, $section_gid='text'){
		if($page_id){
			$data = $this->Content_model->get_page_by_id($page_id);
		}else{
			$data = array();
		}

		if($this->input->post('btn_save')){
			switch($section_gid){
				case 'text':
					$post_data = array(
						"title" => $this->input->post('title', true),
						"content" => $this->input->post('content', true),
						"gid" => $this->input->post('gid', true),
						"lang_id" => $lang_id,
						"parent_id" => $parent_id
					);
					$validate_data = $this->Content_model->validate_page($page_id, $post_data);
					if(!empty($validate_data["errors"])){
						$this->system_messages->add_message('error', $validate_data["errors"]);
						$data = array_merge($data, $validate_data['data']);
					}else{
						$data = $validate_data["data"];
						$this->Content_model->save_page($page_id, $data);
						$this->system_messages->add_message('success', ($page_id)?l('success_update_page', 'content'):l('success_add_page', 'content'));
						//$url = site_url()."admin/content/index/".$lang_id;
						$url = site_url()."admin/content/edit/".$lang_id.'/'.$parent_id.'/'.$page_id.'/'.$section_gid;
						redirect($url);
					}
				break;
				case 'seo':
					$this->load->model('Seo_model');
					$seo_fields = $this->Seo_model->get_seo_fields();
					foreach($seo_fields as $key=>$section_data){
						if($this->input->post('btn_save_'.$section_data['gid'])){
							$post_data = array();
							$post_data[$section_data['gid']] = $this->input->post($section_data['gid'], true);
							$validate_data = $this->Seo_model->validate_seo_tags($page_id, $post_data);
							if(!empty($validate_data['errors'])){
								$this->system_messages->add_message('error', $validate_data["errors"]);
							}else{
								$page_data['id_seo_settings'] = $this->Seo_model->save_seo_tags($data['id_seo_settings'], $validate_data['data']);
								if(!$data['id_seo_settings']){
									$data['id_seo_settings'] = $page_data['id_seo_settings'];
									$this->Content_model->save_page($page_id, $page_data);
								}
								$this->system_messages->add_message('success', l('success_settings_updated', 'seo'));
								$url = site_url()."admin/content/edit/".$lang_id.'/'.$parent_id.'/'.$page_id.'/'.$section_gid;
								redirect($url);
							}
							$data = array_merge($data, $post_data);
							break;
						}
					}
				break;
			}
		}
		
		switch($section_gid){
			case 'text':
			$this->load->plugin('fckeditor');
			$data["content_fck"] = create_editor("content", isset($data["content"]) ? $data["content"] : "", 700, 400, 'Middle');
			break;
			case 'seo':
				$this->load->model('Seo_model');
				$seo_fields = $this->Seo_model->get_seo_fields();
				$this->template_lite->assign('seo_fields', $seo_fields);
			
				$languages = $this->pg_language->languages;
				$this->template_lite->assign('languages', $languages);
				
				$current_lang_id = $this->pg_language->current_lang_id;
				$this->template_lite->assign('current_lang_id', $current_lang_id);

				$seo_settings = $this->Seo_model->get_seo_tags($data['id_seo_settings']);
				$this->template_lite->assign('seo_settings', $seo_settings);
			break;
		}
		
		$this->template_lite->assign('data', $data);
		$this->template_lite->assign('section_gid', $section_gid);

		$this->template_lite->assign('languages', $this->pg_language->languages);
		$this->template_lite->assign("current_lang", $lang_id);
		$this->template_lite->assign('parent_id', $parent_id);
		$this->system_messages->set_data('header', l('admin_header_page_edit', 'content'));
		$this->template_lite->view('edit_form');
	}

	function ajax_save_sorter(){
		$sorter = $this->input->post("sorter");
		foreach($sorter as $parent_str => $items_data){
			$parent_id = intval(str_replace("parent_", "", $parent_str));
			foreach($items_data as $item_str =>$sort_index){
				$page_id = intval(str_replace("item_", "", $item_str));
				$data = array(
					"parent_id" => $parent_id,
					"sorter" => $sort_index
				);
				$this->Content_model->save_page($page_id, $data);
			}
		}
	}

	function ajax_activate($status, $page_id){
		$this->Content_model->activate_page($page_id, $status);
	}

	function ajax_delete($page_id){
		$this->Content_model->delete_page($page_id);
	}

	function promo($lang_id=0, $content_type=''){
		if(!$lang_id) $lang_id = $this->pg_language->get_default_lang_id();
		$this->load->model("content/models/Content_promo_model");

		$promo_data = $this->Content_promo_model->get_promo($lang_id);

		if($this->input->post('btn_save_settings')){
			$post_data = array(
				"content_type" => $this->input->post('content_type', true),
				"block_width" => $this->input->post('block_width', true),
				"block_width_unit" => $this->input->post('block_width_unit', true),
				"block_height" => $this->input->post('block_height', true),
				"block_height_unit" => $this->input->post('block_height_unit', true),
			);
			$validate_data = $this->Content_promo_model->validate_promo($post_data);

			if(!empty($validate_data["errors"])){
				$this->system_messages->add_message('error', $validate_data["errors"]);
				$promo_data = array_merge($promo_data, $validate_data["data"]);
			}else{
				$this->Content_promo_model->save_promo($lang_id, $validate_data["data"]);
				$this->system_messages->add_message('success', l('success_update_promo_block', 'content'));
				$url = site_url()."admin/content/promo/".$lang_id;
				redirect($url);
			}
		}

		if($this->input->post('btn_save_content')){
			if($content_type == 't'){
				$post_data = array(
					"promo_text" => $this->input->post('promo_text', true),
					"block_align_hor" => $this->input->post('block_align_hor', true),
					"block_align_ver" => $this->input->post('block_align_ver', true),
					"block_image_repeat" => $this->input->post('block_image_repeat', true),
				);
				$validate_data = $this->Content_promo_model->validate_promo($post_data, 'promo_image');
			}elseif($content_type == 'f'){
				$validate_data = $this->Content_promo_model->validate_promo(array(), '', 'promo_flash');
			}

			if($this->input->post('promo_image_delete')){
				$this->load->model("Uploads_model");
				$this->Uploads_model->delete_upload($this->Content_promo_model->upload_gid, $lang_id."/", $promo_data['promo_image']);
				$validate_data["data"]["promo_image"] = '';
			}

			if($this->input->post('promo_flash_delete')){
				$this->load->model("file_uploads/models/File_uploads_model");
				$this->File_uploads_model->delete_upload($this->Content_promo_model->file_upload_gid, $lang_id."/", $promo_data['promo_flash']);
				$validate_data["data"]["promo_flash"] = '';
			}

			if(!empty($validate_data["errors"])){
				$this->system_messages->add_message('error', $validate_data["errors"]);
				$promo_data = array_merge($promo_data, $validate_data["data"]);
			}else{
				$this->Content_promo_model->save_promo($lang_id, $validate_data["data"], 'promo_image', 'promo_flash');
				$this->system_messages->add_message('success', l('success_update_promo_block', 'content'));
				$url = site_url()."admin/content/promo/".$lang_id."/".$content_type;
				redirect($url);
			}

		}

		if(!$content_type) $content_type = $promo_data["content_type"];

		$this->load->plugin('fckeditor');
		$promo_data["promo_text_fck"] = create_editor("promo_text", isset($promo_data["promo_text"]) ? $promo_data["promo_text"] : "", 570, 300, 'Middle');

		$this->template_lite->assign('languages', $this->pg_language->languages);
		$this->template_lite->assign("current_lang", $lang_id);
		$this->template_lite->assign("content_type", $content_type);
		$this->template_lite->assign("promo_data", $promo_data);
		$this->system_messages->set_data('header', l('admin_header_promo_edit', 'content'));
		$this->template_lite->view('promo_form');
	}
}
