<?php
/**
* News feeds model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/


if (!defined('BASEPATH')) exit('No direct script access allowed');

define('NEWS_FEEDS_TABLE', DB_PREFIX.'news_feeds');

class Feeds_model extends Model
{
	private $CI;
	private $DB;

	private $fields = array(
		'id',
		'link',
		'title',
		'description',
		'site_link',
		'encoding',
		'status',
		'id_lang',
		'max_news',
		'date_add'
	);
	private $feeds_cache = array();

	/**
	 * Constructor
	 *
	 * @return Install object
	 */
	function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
	}

	public function get_feed_by_id($id){
		if(!isset($this->feeds_cache[$id])){
			$result = $this->DB->select(implode(", ", $this->fields))->from(NEWS_FEEDS_TABLE)->where("id", $id)->get()->result_array();
			if(!empty($result)){
				$data = $result[0];
				$this->feeds_cache[$id] = $data;
			}else{
				$this->feeds_cache[$id] = array();
			}
		}
		return $this->feeds_cache[$id];
	}

	public function get_feeds_list($page=null, $items_on_page=null, $order_by=null, $params=array(), $filter_object_ids=null){
		$this->DB->select(implode(", ", $this->fields));
		$this->DB->from(NEWS_FEEDS_TABLE);

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
				if(in_array($field, $this->fields)){
					$this->DB->order_by($field." ".$dir);
				}
			}
		}

		if(!is_null($page)){
			$page = intval($page)?intval($page):1;
			$this->DB->limit($items_on_page, $items_on_page*($page-1));
		}
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			foreach($results as $r){
				$data[] = $r;
				$this->feeds_cache[$r["id"]] = $r;
			}
			return $data;
		}
		return array();
	}

	public function get_feeds_count($params=array(), $filter_object_ids=null){
		$this->DB->select("COUNT(*) AS cnt");
		$this->DB->from(NEWS_FEEDS_TABLE);

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

		$result = $this->DB->get()->result();
		if(!empty($result)){
			return intval($result[0]->cnt);
		}else{
			return 0;
		}
	}

	public function save_feed($id, $data){
		if (empty($id)){
			$data["date_add"] = date("Y-m-d H:i:s");
			$this->DB->insert(NEWS_FEEDS_TABLE, $data);
			$id = $this->DB->insert_id();
		}else{
			$this->DB->where('id', $id);
			$this->DB->update(NEWS_FEEDS_TABLE, $data);
			if(isset($this->feeds_cache[$id])) unset($this->feeds_cache[$id]);
		}
		return $id;
	}

	public function validate_feed($id, $data){
		$return = array("errors"=> array(), "data" => array(), "items" => array());

		if(isset($data["id_lang"])){
			$return["data"]["id_lang"] = intval($data["id_lang"]);
		}

		if(isset($data["max_news"])){
			$return["data"]["max_news"] = intval($data["max_news"]);
			if(empty($return["data"]["max_news"]) ){
				$return["errors"][] = l('error_feed_max_news_incorrect', 'news');
			}
		}

		if(isset($data["link"])){
			$return["data"]["link"] = trim(strip_tags($data["link"]));

			if(empty($return["data"]["link"]) ){
				$return["errors"][] = l('error_feed_link_incorrect', 'news');
			}else{
				$flag_update_feed = false;

				if(!empty($id)){
					$feed_data = $this->get_feed_by_id($id);
					if($feed_data["link"] != $return["data"]["link"]){
						$flag_update_feed = true;
					}
				}else{
					$flag_update_feed = true;
				}

				if($flag_update_feed){
					$feed_content = $this->get_feed_content($return["data"]["link"]);
					if(!empty($feed_content["errors"])){
						foreach($feed_content["errors"] as $error) $return["errors"][] = $error;
					}else{
						$return["items"] = $feed_content["items"];
						$return["data"]["title"] = !empty($feed_content["channel"]["title"])?$feed_content["channel"]["title"]:'';
						$return["data"]["description"] = !empty($feed_content["channel"]["description"])?$feed_content["channel"]["description"]:'';
						$return["data"]["site_link"] = !empty($feed_content["channel"]["permalink"])?$feed_content["channel"]["permalink"]:'';
						$return["data"]["encoding"] = !empty($feed_content["channel"]["encoding"])?$feed_content["channel"]["encoding"]:'';
					}
				}
			}

		}
		return $return;
	}

	public function delete_feed($id_feed){
		if(!empty($id_feed)){
			$this->DB->where('id', $id_feed);
			$this->DB->delete(NEWS_FEEDS_TABLE);
			if(isset($this->feeds_cache[$id_feed])) unset($this->feeds_cache[$id_feed]);
		}
		return;
	}

	public function get_feed_content($link, $max_item_count=5){
		$return = array("errors"=> array(), "channel" => array(), "items" => array());

		$this->CI->load->library('simplepie');

		$this->CI->simplepie->set_feed_url($link);
		// @todo don't forget disable cache before debuging
		$this->CI->simplepie->set_cache_location(SITE_PHYSICAL_PATH . 'temp/cache');
		$this->CI->simplepie->enable_cache(true);
		// force_fsockopen can help read feed in some cases, if false used CURL if exist
		$this->CI->simplepie->force_fsockopen(true);
		$this->CI->simplepie->handle_content_type();
		$this->CI->simplepie->init();

		// throw error if exist
		if ($this->simplepie->error()){
			$return["errors"][] = $this->CI->simplepie->error();
		}else{
			$return["channel"] = array(
				"title" => $this->CI->simplepie->get_title(),
				"author" => $this->CI->simplepie->get_author(),
				"description" => $this->CI->simplepie->get_description(),
				"permalink" => $this->CI->simplepie->get_permalink(),
				"encoding" => $this->CI->simplepie->get_encoding(),
				"favicon" => $this->CI->simplepie->get_favicon(),
				"items_cnt" => $this->CI->simplepie->get_item_quantity(),
				"language" => $this->CI->simplepie->get_language(),
				"type" => $this->CI->simplepie->get_type(),
				"image_url" => $this->CI->simplepie->get_image_url(),
				"image_link" => $this->CI->simplepie->get_image_link(),
				"image_title" => $this->CI->simplepie->get_image_title(),
				"image_width" => $this->CI->simplepie->get_image_width(),
				"image_height" => $this->CI->simplepie->get_image_height(),
			);

			$items = $this->CI->simplepie->get_items(0, $max_item_count);
			if(!empty($items)){
				foreach($items as $key => $rss_item){
					$return["items"][] = array(
						"title" => $rss_item->get_title(),
						"description" => $rss_item->get_description(),
						"content" => $rss_item->get_content(),
						"date" => $rss_item->get_date('Y-m-d H:i:s'),
						"unique_id" => $rss_item->get_id(true),
						"link" => $rss_item->get_link(),
						"permalink" => $rss_item->get_permalink()
					);
				}
			}
		}

		return $return;
	}

	public function save_feed_news($id_feed, $items){
		if(empty($items)){
			return false;
		}
		$feed_data = $this->get_feed_by_id($id_feed);

		$this->CI->load->model('News_model');
		$count = 0;
		foreach($items as $rss_item){
			$data = array(
				'gid' => $rss_item["title"],
				'name' => $rss_item["title"],
				'annotation' => $rss_item["description"],
				'content' => $rss_item["content"],
				'status' => 1,
				'id_lang' => $feed_data["id_lang"],
				'news_type'=>"feed",
				'feed_link' => $rss_item["link"],
				'feed_id' => $id_feed
			);

			$validate_data = $this->CI->News_model->validate_news(null, $data);
			if(empty($validate_data["errors"])){
				$this->CI->News_model->save_news(null, $validate_data["data"]);
				$count++;
			}
		}
		return $count;
	}

	public function cron_feed_parser(){
		$params["where"]["status"] = 1;
		$feeds = $this->get_feeds_list(null, null, null, $params);
		$return = array();

		foreach($feeds as $feed){
			$content = $this->get_feed_content($feed["link"], $feed["max_news"]);
			if(!empty($content["errors"])){
				$return[] = $feed["title"].": ".implode("; ", $content["errors"]);
			}else{
				$saved_news = $this->save_feed_news($feed["id"], $content["items"]);
				if($saved_news){
					$return[] =  $feed["title"].": ".str_replace("[count]", $saved_news, l('success_parse_feed', 'news'));
				}else{
					$return[] =  $feed["title"].": ".l('success_no_feed_news', 'news');
				}
			}
		}
		echo implode("<br>", $return);
	}
}