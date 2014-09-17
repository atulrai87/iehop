<?php
/**
* Social networking linkdin widget model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Linkedin_widgets_model extends Model {

	public $CI;
	public $widget_types = array(
		'share',
	);
	public $head_loaded = false;

	function __construct() {
		parent::__construct();
		$this->CI = & get_instance();
	}

	public function get_header($service_data = array(), $locale = '', $types = array()) {
		$header = '';
		if (in_array('share', $types))
			$header .= '<script src="//platform.linkedin.com/in.js" type="text/javascript"></script>';
		$this->head_loaded = $header ? true : false;
		return $header;
	}

	public function get_share() {
		return $this->head_loaded ? '<script type="IN/Share" data-counter="right"></script>' : '';
	}

}
