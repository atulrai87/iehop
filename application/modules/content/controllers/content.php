<?php
/**
* Content user side controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

Class Content extends Controller
{
	/**
	 * link to CodeIgniter object
	 * @var object
	 */

	/**
	 * Constructor
	 */
	function Content()
	{
		parent::Controller();
		$this->load->model("Content_model");
	}

	function index($gid = null){
		if(!is_null($gid)) {
			$this->load->helper('seo');
			redirect(rewrite_link('content', 'view', $gid));
		};
		$this->load->model('Menu_model');
		$this->Menu_model->breadcrumbs_set_active(l('seo_tags_index_title', 'content'), site_url().'content/');

		$params = array();
		$params['where']['parent_id'] = '0';
		$params['where']['status'] = '1';
		$pages_list = $this->Content_model->get_pages_list($this->pg_language->current_lang_id, 0, $params);

		$this->template_lite->assign('pages', $pages_list);
		$this->template_lite->assign('date_format', $this->pg_date->get_format('date_time_literal', 'st'));
		$this->template_lite->view('list');
	}

	function view($gid){
	 	$gid = strip_tags($gid);
		$lang_id = $this->pg_language->current_lang_id;
		$page_data = $this->Content_model->get_page_by_gid($lang_id, $gid);
		if(empty($page_data)) {
			show_404();
		};
		$this->template_lite->assign("page", $page_data);

		$this->load->model('Menu_model');
		$this->Menu_model->breadcrumbs_set_active($page_data["title"]);

		if($page_data['id_seo_settings']){
			$this->load->model('Seo_model');
			$seo_settings = $this->Seo_model->parse_seo_tags($page_data['id_seo_settings']);
			$this->pg_seo->set_seo_tags($seo_settings);
		}else{
			$this->pg_seo->set_seo_data($page_data);
		}
		
		$this->template_lite->view('view');
	}

}
