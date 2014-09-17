<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * PG date Model
 *
 * @package PG_Core
 * @subpackage Libraries
 * @category	libraries
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Alexander Batukhtin <abatukhtin@pilotgroup.net>
 * @version $Revision: 1 $ $Date: 2013-06-07 15:13:07 +0400 (пт, 07 июн 2013) $ $Author: abatukhtin $
 */
class CI_Pg_date {

	/**
	 * link to CodeIgniter object
	 * @var object
	 */
	private $CI;

	public $formats = array();

	public $templates = array(
		'js' => array(
			'year_2'				=> 'yy',
			'year_4'				=> 'yyyy',
			'month_without_zero'	=> 'M',
			'month_with_zero'		=> 'MM',
			'month_short'			=> 'MMM',
			'month_full'			=> 'MMMM',
			'day_without_zero'		=> 'd',
			'day_with_zero'			=> 'dd',
			'week_day_short'		=> 'ddd',
			'week_day_full'			=> 'dddd',
			'hours_12_without_zero' => 'h',
			'hours_12_with_zero'	=> 'hh',
			'hours_24_without_zero' => 'H',
			'hours_24_with_zero'	=> 'HH',
			'minutes_without_zero'	=> 'm',
			'minutes_with_zero'		=> 'mm',
			'seconds_without_zero'	=> 's',
			'seconds_with_zero'		=> 'ss'
		),
		'date' => array(
			'year_2'				=> 'y',
			'year_4'				=> 'Y',
			'month_without_zero'	=> 'n',
			'month_with_zero'		=> 'm',
			'month_short'			=> 'M',
			'month_full'			=> 'F',
			'day_without_zero'		=> 'j',
			'day_with_zero'			=> 'd',
			'week_day_short'		=> 'D',
			'week_day_full'			=> 'l',
			'hours_12_without_zero' => 'g',
			'hours_12_with_zero'	=> 'h',
			'hours_24_without_zero' => 'G',
			'hours_24_with_zero'	=> 'H',
			'minutes_without_zero'	=> 'i',
			'minutes_with_zero'		=> 'i',
			'seconds_without_zero'	=> 's',
			'seconds_with_zero'		=> 's'
		),
		'mysql' => array(
			'year_2'				=> '%y',
			'year_4'				=> '%Y',
			'month_without_zero'	=> '%c',
			'month_with_zero'		=> '%m',
			'month_short'			=> '%b',
			'month_full'			=> '%M',
			'day_without_zero'		=> '%e',
			'day_with_zero'			=> '%d',
			'week_day_short'		=> '%a',
			'week_day_full'			=> '%W',
			'hours_12_without_zero' => '%l',
			'hours_12_with_zero'	=> '%I',
			'hours_24_without_zero' => '%k',
			'hours_24_with_zero'	=> '%H',
			'minutes_without_zero'	=> '%i',
			'minutes_with_zero'		=> '%i',
			'seconds_without_zero'	=> '%s',
			'seconds_with_zero'		=> '%s'
		),
		'st' => array(	// strftime()
			'year_2'				=> '%y',
			'year_4'				=> '%Y',
			'month_without_zero'	=> '%m',
			'month_with_zero'		=> '%m',
			'month_short'			=> '%b',
			'month_full'			=> '%B',
			'day_without_zero'		=> '%e',
			'day_with_zero'			=> '%d',
			'week_day_short'		=> '%a',
			'week_day_full'			=> '%A',
			'hours_12_without_zero' => '%l',
			'hours_12_with_zero'	=> '%I',
			'hours_24_without_zero' => '%k',
			'hours_24_with_zero'	=> '%H',
			'minutes_without_zero'	=> '%M',
			'minutes_with_zero'		=> '%M',
			'seconds_without_zero'	=> '%S',
			'seconds_with_zero'		=> '%S',
		),
		'ui' => array(
			'year_2'				=> 'y',
			'year_4'				=> 'yy',
			'month_without_zero'	=> 'm',
			'month_with_zero'		=> 'mm',
			'month_short'			=> 'M',
			'month_full'			=> 'MM',
			'day_without_zero'		=> 'd',
			'day_with_zero'			=> 'dd',
			'week_day_short'		=> 'D',
			'week_day_full'			=> 'DD',
			'hours_12_without_zero' => '',
			'hours_12_with_zero'	=> '',
			'hours_24_without_zero' => '',
			'hours_24_with_zero'	=> '',
			'minutes_without_zero'	=> '',
			'minutes_with_zero'		=> '',
			'seconds_without_zero'	=> '',
			'seconds_with_zero'		=> ''
		),
	);

	public $available_formats = array(
		"date_numeric" => array(
			"year" => array("year_2", "year_4"),
			"month" => array("month_with_zero", "month_short", "month_full"),
			"day" => array("day_without_zero", "day_with_zero")),
		"date_literal" => array(
			"year" => array("year_2", "year_4"),
			"month" => array("month_with_zero", "month_short", "month_full"),
			"day" => array("day_without_zero", "day_with_zero"),
			"week_day" => array("week_day_short", "week_day_full")),
		"date_time_numeric" => array(
			"year" => array("year_2", "year_4"),
			"month" => array("month_with_zero", "month_short", "month_full"),
			"day" => array("day_without_zero", "day_with_zero"),
			"hours" => array("hours_24_without_zero", "hours_24_with_zero", "hours_12_without_zero", "hours_12_with_zero"),
			"minutes" => array("minutes_with_zero"),
			"seconds" => array("seconds_with_zero")),
		"date_time_literal" => array(
			"year" => array("year_2", "year_4"),
			"month" => array("month_with_zero", "month_short", "month_full"),
			"day" => array("day_without_zero", "day_with_zero"),
			"week_day" => array("week_day_short", "week_day_full"),
			"hours" => array("hours_24_without_zero", "hours_24_with_zero", "hours_12_without_zero", "hours_12_with_zero"),
			"minutes" => array("minutes_with_zero"),
			"seconds" => array("seconds_with_zero")),
		"time_numeric" => array(
			"hours" => array("hours_24_without_zero", "hours_24_with_zero", "hours_12_without_zero", "hours_12_with_zero"),
			"minutes" => array("minutes_with_zero"),
			"seconds" => array("seconds_with_zero")),
		"time_literal" => array(
			"hours" => array("hours_24_without_zero", "hours_24_with_zero", "hours_12_without_zero", "hours_12_with_zero"),
			"minutes" => array("minutes_with_zero"),
			"seconds" => array("seconds_with_zero")));

	public $_regexps = array(
		/*'js' => array(
			'year'		=> '/(?<!a)yy?y?y?/i',
			'week_day'	=> '/dddd?(?!a)/',
			'day'		=> '/dd?(?!a)/',
			'month'		=> '/MM?M?M?/',
			'hours'		=> '/(?<!t)[hH]+[hH]?/',
			'minutes'	=> '/mm?(?![io])/',
			'seconds'	=> '/(?<![edr])(ss?)(?!e)/'),*/
		'generic' => array(
			'year'		=> '/year(_[24]{1})?/i',
			'month'		=> '/month(_[\w\d_-]+)?/i',
			'day'		=> '/(?<!_)day(_[\w\d_-]+)?/i',
			'week_day'	=> '/week_day(_[\w\d_-]+)?/i',
			'hours'		=> '/hours(_[\w\d_-]+)?/i',
			'minutes'	=> '/minutes(_[\w\d_-]+)?/i',
			'seconds'	=> '/seconds(_[\w\d_-]+)?/i'));

	public function __construct() {
		$this->CI =& get_instance();

		// The modifier that is not supported in the Windows implementation of strftime().
		if (strtolower(substr(PHP_OS, 0, 3)) == 'win') {
			$this->templates['st']['day_without_zero'] = '%#d';
			$this->templates['st']['hours_12_without_zero'] = '%#I';
			$this->templates['st']['hours_24_without_zero'] = '%#H';
		}
	}

	/**
	 * Returns date format
	 *
	 * @param string $format_id
	 * @param string $type (js | date | mysql | st)
	 * @return string
	 */
	public function get_format($format_id, $type) {
		if(empty($this->formats[$type][$format_id])) {
			$gen_tpl = $this->CI->pg_module->get_module_config('start', 'date_format_' . $format_id);
			$gen_tpl = str_replace(array('[', ']'), '', $gen_tpl);
			$this->formats[$type][$format_id] = str_replace(
					array_keys($this->templates[$type]),
					array_values($this->templates[$type]),
					$gen_tpl);
		}
		return $this->formats[$type][$format_id];
	}

	/**
	 * Parses generic template<br/>
	 * Available patterns:
	 * <ul><li>[year_2]</li>
	 * <li>[year_4]</li>
	 * <li>[month_without_zero]</li>
	 * <li>[month_with_zero]</li>
	 * <li>[month_short]</li>
	 * <li>[month_full]</li>
	 * <li>[day_without_zero]</li>
	 * <li>[day_with_zero]</li>
	 * <li>[week_day_short]</li>
	 * <li>[week_day_full]</li>
	 * <li>[hours_12_without_zero]</li>
	 * <li>[hours_12_with_zero]</li>
	 * <li>[hours_24_without_zero]</li>
	 * <li>[hours_24_with_zero]</li>
	 * <li>[minutes_without_zero]</li>
	 * <li>[minutes_with_zero]</li>
	 * <li>[seconds_without_zero]</li>
	 * <li>[seconds_with_zero]</li></ul>
	 * @param string $gen_tpl
	 * @param string $type
	 * @return array
	 */
	public function parse_generic_template($gen_tpl, $type) {
		$matches	= array();
		$parts		= array('year', 'week_day', 'day', 'month', 'hours', 'minutes', 'seconds');
		$result['tpl'] = $gen_tpl;
		foreach($parts as $part) {
			preg_match('/(?<=\[)' . $part . '[\w\d]+(?=])/i', $gen_tpl, $matches);
			$format = ($this->templates[$type]);
			if(!empty($format[$matches[0]])) {
				$result[$part] = $matches[0];
				$result['tpl'] = str_replace($matches[0], $part, $result['tpl']);
			}
		}
		return $result;
	}

	/**
	 * Creates generic template
	 *
	 * @param type $data
	 * @param type $tpl
	 * @return type
	 */
	/*public function create_templates($data, $tpl) {
		$templates['generic'] = str_replace(array_keys($data), array_values($data), $tpl);
		foreach($this->templates as $type => $format) {
			if(!count($format)) {
				continue;
			}
			$templates[$type] = $tpl;
			foreach($data as $part => $value) {
				$templates[$type] = str_replace($part, $format[$value], $templates[$type]);
			}
		}
		return $templates;
	}*/

	/**
	 * Creates generic template
	 *
	 * @param string $uf_tpl
	 * @param array $data
	 * @return string
	 */
	public function create_generic_tpl($uf_tpl, $data) {
		$generic_tpl = $uf_tpl;
		// Replace user friendly patterns with generic patterns
		foreach($this->_regexps['generic'] as $name => $pattern) {
			$generic_tpl = preg_replace($pattern, $data[$name], $generic_tpl);
		}
		return $generic_tpl;
	}

	/**
	 * Saves the data format
	 *
	 * @param string $generic_template
	 * @param string $type
	 */
	public function save_format($generic_template, $type) {
		$this->CI->pg_module->set_module_config('start', 'date_format_' . $type, $generic_template);
	}

	/**
	 * Does the same thing as the strftime but with multiple formats
	 * and also translates month names and weekdays.
	 *
	 * @param string $template
	 * @param int $timestamp
	 * @param string $type (js | date | mysql | st | generic)
	 * @return string
	 */
	public function strftime($template, $timestamp = null, $type = 'st') {

		$time = getdate($timestamp);
		$translatable = array(
			'week_day_full'		=> 'wday',
			'week_day_short'	=> 'wday',
			'month_full'		=> 'mon',
			'month_short'		=> 'mon');
		$search = array();
		$replace = array();

		if('generic' === $type) {
			$type = 'st';
			$template = str_replace(array('[', ']'), '', $template);
			$template = str_replace(array_keys($this->templates[$type]), array_values($this->templates[$type]), $template);
		};

		foreach($translatable as $tpl_name => $time_name) {
			if(false !== (strpos($template, $this->templates[$type][$tpl_name]))) {
				$ds = ld($tpl_name, 'start');
				if(!empty($ds['option'][$time[$time_name]])) {
					$search[]	= $this->templates[$type][$tpl_name];
					$replace[]	= $ds['option'][$time[$time_name]];
				};
			};
		};

		// Translate
		if(count($replace)) {
			$template = str_replace($search, $replace, $template);
		};

		// Convert to st
		if('st' !== $type && 'generic' !== $type) {
			$template = $this->convert_tpl($type, 'st', $template);
		}

		return strftime($template, $timestamp);
	}

	public function convert_tpl($from, $to, $tpl) {
		return str_replace($this->templates[$from], $this->templates[$to], $tpl);
	}

}
