<?php
/**
* News user side controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/


Class News extends Controller
{
	/**
	 * link to CodeIgniter object
	 * @var object
	 */

	/**
	 * Constructor
	 */
	function __construct(){
		parent::Controller();
		$this->load->model("News_model");

		$this->load->model('Menu_model');
		$this->Menu_model->set_menu_active_item('user_main_menu', 'main-menu-news-item');
		$this->Menu_model->set_menu_active_item('guest_main_menu', 'main-menu-news-item');
		$this->Menu_model->breadcrumbs_set_parent('footer-menu-news-item');
	}

	public function index($page=1){
		$attrs = array();
		$attrs["where"]["id_lang"] = $this->pg_language->current_lang_id;
		$attrs["where"]["status"] = "1";
		$news_count = $this->News_model->get_news_count($attrs);

		$items_on_page = $this->pg_module->get_module_config('news', 'userside_items_per_page');
		$this->load->helper('sort_order');
		$page = get_exists_page_number($page, $news_count, $items_on_page);

		if ($news_count > 0){
			$news = $this->News_model->get_news_list( $page, $items_on_page, array('date_add' => "DESC"), $attrs);
			$this->template_lite->assign('news', $news);
		}
		$this->load->helper("navigation");
		$url = rewrite_link('news', 'index')."/";
		$page_data = get_user_pages_data($url, $news_count, $items_on_page, $page, 'briefPage');
		$page_data["date_format"] = $this->pg_date->get_format('date_time_literal', 'st');
		$this->template_lite->assign('page_data', $page_data);

		$this->template_lite->view('list');
	}

	public function view($id){
		$news = $this->News_model->get_news_by_id($id);
		if(empty($news)) {
			show_404();
		};
		$news = $this->News_model->format_single_news($news);
		$this->template_lite->assign('data', $news);

		$page_data["date_format"] = $this->pg_date->get_format('date_time_literal', 'st');
		$this->template_lite->assign('page_data', $page_data);

		if($news['id_seo_settings']){
			$this->load->model('Seo_model');
			$seo_settings = $this->Seo_model->parse_seo_tags($news['id_seo_settings']);
			$this->pg_seo->set_seo_tags($seo_settings);
		}else{
			$this->pg_seo->set_seo_data($news);
		}

		$this->Menu_model->breadcrumbs_set_active($news['name']);
		$this->Menu_model->get_breadcrumbs();
		$this->template_lite->view('view');
	}

	public function rss(){
		$rss_settings = $this->News_model->get_rss_settings();
		$this->load->library('rssfeed');
		$current_lang = $this->pg_language->languages[$this->pg_language->current_lang_id];

		$this->rssfeed->set_channel(
			site_url(),
			$rss_settings["rss_feed_channel_title"],
			$rss_settings["rss_feed_channel_description"],
			$current_lang["code"]
		);

		if($rss_settings["rss_feed_image_url"]){
			$this->rssfeed->set_image(
				$rss_settings["rss_feed_image_media"]["thumbs"]["rss"],
				$rss_settings["rss_feed_image_title"],
				site_url()
			);
		}

		$attrs["where"]["id_lang"] = $this->pg_language->current_lang_id;
		$attrs["where"]["status"] = "1";
		if(!$rss_settings["rss_use_feeds_news"]){
			$attrs["where"]["feed_id"] = "";
		}

		$news = $this->News_model->get_news_list(1, $rss_settings["rss_news_max_count"], array('date_add' => "DESC"), $attrs);
		if(!empty($news)){
			$this->load->helper('seo');
			foreach($news as $item){
				$url = rewrite_link("news", "view", $item);
				$this->rssfeed->set_item($url, $item["name"], $item["annotation"], $item["date_add"]);
			}
		}
		$this->rssfeed->send();
		return;
	}

}
