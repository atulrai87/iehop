<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter
 *
 * @author      Bekbulaov A
 * @link      http://vkurseweba.ru
 * @since      Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

class PG_Pagination extends CI_Pagination {

	public $url_suffix = ''; // URL suffix, see config.php
	public $query_string_segment = 'page'; 
	
	/**
	 * Constructor
	 */
	public function PG_Pagination(){
		parent::CI_Pagination();
		$this->first_link = l('nav_first', 'start');
		$this->last_link = l('nav_last', 'start');
		$this->next_link = '&rsaquo;';
		$this->prev_link = '&lsaquo;';
	}

	// --------------------------------------------------------------------

	/**
	 * Generate the pagination links
	 *
	 * @access   public
	 * @return   string
	 */
	public function create_links($output_format_type = ''){
		// If our item count or per-page total is zero there is no need to
		// continue.
		if($this->total_rows == 0 OR $this->per_page == 0){
			return '';
		}

		// Calculate the total number of pages
		$num_pages = ceil($this->total_rows / $this->per_page);

		// Is there only one page? Hm... nothing more to do here then.
		if($num_pages == 1){
			return '';
		}

		// Determine the current page number.
		$CI =& get_instance();

		// My fix
		$this->url_suffix = $CI->config->item('url_suffix');

		if($CI->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE){
			//if ($CI->input->get($this->query_string_segment) != 0)
			//PG fix
			if(empty($this->cur_page) && $CI->input->get($this->query_string_segment) != 0){
				$this->cur_page = $CI->input->get($this->query_string_segment);

				// Prep the current page - no funny business!
				$this->cur_page = (int) $this->cur_page;
			}
		}else{
			//if ($CI->uri->segment($this->uri_segment) != 0)
			//PG fix
			if(empty($this->cur_page) && $CI->uri->segment($this->uri_segment) != 0){
				$this->cur_page = $CI->uri->segment($this->uri_segment);

				// Prep the current page - no funny business!
				$this->cur_page = (int) $this->cur_page;
			}

			// My fix, remove url_suffix from baseurl
			$this->base_url = $this->remove_url_suffix($this->base_url, $this->url_suffix);
		}

		$this->num_links = (int)$this->num_links;

		if($this->num_links < 1){
			show_error('Your number of links must be a positive number.');
		}

		if(!is_numeric($this->cur_page)){
			$this->cur_page = 0;
		}

		// My fix
		if($this->cur_page < 1){
			$this->cur_page = 1;
		}

		// Is the page number beyond the result range?
		// If so we show the last page

		/*if($this->cur_page > $this->total_rows){
			$this->cur_page = ($num_pages - 1) * $this->per_page;
		}*/
     
		// My fix

		if($this->cur_page > $num_pages){
			$this->cur_page = $num_pages;
		}

		$uri_page_number = $this->cur_page;

		// $this->cur_page = floor(($this->cur_page/$this->per_page) + 1);
		// My fix
		$this->cur_page = floor($this->cur_page);

		// Calculate the start and end numbers. These determine
		// which number to start and end the digit links with
		$start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page -
			($this->num_links - 1) : 1;
		
		$end = (($this->cur_page + $this->num_links) < $num_pages) ?
			$this->cur_page + $this->num_links : $num_pages;

		// Is pagination being used over GET or POST?  If get, add a per_page
		// query string. If post, add a trailing slash to the base URL if needed
		if($CI->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE){
			$parse_url = parse_url($this->base_url);
			if(isset($parse_url['query'])){
				$this->base_url = preg_replace('/(&|\?)page=([0-9]*)/', '', $this->base_url);
				if(false === strpos($this->base_url, '?')){
					$this->base_url = rtrim($this->base_url).'?'.$this->query_string_segment.'=';
				}else{
					$this->base_url = rtrim($this->base_url).'&amp;'.$this->query_string_segment.'=';
				}
			}else{
				$this->base_url = rtrim($this->base_url).'?'.$this->query_string_segment.'=';
			}
		}else{
			$this->base_url = rtrim($this->base_url, '/') .'/';
		}

		// And here we go...
		$output = '';

		// Render the "First" link
		// if  ($this->cur_page > $this->num_links + 1)

		// My fix
		if($this->cur_page > $this->num_links){

		/*$output .= $this->first_tag_open.'<a href="'.
		$this->base_url.'">'.$this->first_link.'</a>'.
		$this->first_tag_close;*/
        
		// My fix
		if(strpos($this->base_url, '[page]')){
			$first_page_link = str_replace('[page]', 1, $this->base_url);
		}else{
			$first_page_link = $this->base_url.'1';
		}
		$output .= $this->first_tag_open.'<a href="'.
			$first_page_link.$this->url_suffix.'" data-page="1">'.
			$this->first_link.'</a>'.$this->first_tag_close;
		}

		// Render the "previous" link
		/*if($this->cur_page != 1){
			$i = $uri_page_number - $this->per_page;
			if ($i == 0) $i = '';
			$output .= $this->prev_tag_open.'<a href="'.
				$this->base_url.$i.'">'.$this->prev_link.
				'</a>'.$this->prev_tag_close;
		}*/

		// My fix
		if($this->cur_page > 1){
			$i = $this->cur_page - 1;
			if ($i == 0) $i = '';
			if(strpos($this->base_url, '[page]')){
				$prev_page_link = str_replace('[page]', $i, $this->base_url);
			}else{
				$prev_page_link = $this->base_url.$i;
			}
			$output .= $this->prev_tag_open.'<a href="'.$prev_page_link.
				$this->url_suffix.'" data-page="'.$i.'">'.$this->prev_link.'</a>'.
				$this->prev_tag_close;
		}

		// Write the digit links
		for($loop = $start - 1; $loop <= $end; $loop++){
			// $i = ($loop * $this->per_page) - $this->per_page;

			// My fix
			$i = $loop;

			// if ($i >= 0)
         
			// My fix
			if($i > 0){
				if($this->cur_page == $loop){
					$output .= $this->cur_tag_open.$loop.$this->cur_tag_close;
					// Current page
				}else{
					$n = ($i == 0) ? '' : $i;

					/*$output .= $this->num_tag_open.'<a href="'.$this->base_url.
					$n.'">'.$loop.'</a>'.$this->num_tag_close;*/
               
					// My fix
					if(strpos($this->base_url, '[page]')){
						$cur_page_link = str_replace('[page]', $n, $this->base_url);
					}else{
						$cur_page_link = $this->base_url.$n;
					}
					$output .= $this->num_tag_open.'<a href="'.
						$cur_page_link.$this->url_suffix.'" data-page="'.$n.'">'.$loop.
						'</a>'.$this->num_tag_close;
				}
			}
		}

		// Render the "next" link
		if($this->cur_page < $num_pages){
			/*$output .= $this->next_tag_open.'<a href="'.$this->base_url.
				($this->cur_page * $this->per_page).'">'.
				$this->next_link.'</a>'.$this->next_tag_close;*/
         
			// My fix
			if(strpos($this->base_url, '[page]')){
				$next_page_link = str_replace('[page]', ($this->cur_page + 1), $this->base_url);
			}else{
				$next_page_link = $this->base_url.($this->cur_page + 1);
			}
			$output .= $this->next_tag_open.'<a href="'.$next_page_link.
				$this->url_suffix.'" data-page="'.($this->cur_page + 1).'">'.$this->next_link.
				'</a>'.$this->next_tag_close;
		}

		// Render the "Last" link
		if(($this->cur_page + $this->num_links) < $num_pages){
			/*$i = (($num_pages * $this->per_page) - $this->per_page);
			$output .= $this->last_tag_open.'<a href="'.
				$this->base_url.$i.'">'.$this->last_link.'</a>'.
				$this->last_tag_close;*/
				
			// My fix
			$i = $num_pages;
			if(strpos($this->base_url, '[page]')){
				$last_page_link = str_replace('[page]', $i, $this->base_url);
			}else{
				$last_page_link = $this->base_url.$i;
			}
			$output .= $this->last_tag_open.'<a href="'.$last_page_link.
				$this->url_suffix.'" data-page="'.$i.'">'.$this->last_link.'</a>'.
				$this->last_tag_close;
		}

		// Kill double slashes.  Note: Sometimes we can end up with a double
		// slash in the penultimate link so we'll kill all double slashes.
		$output = preg_replace("#([^:])//+#", "\1/", $output);

		// Add the wrapper HTML if exists
		$output = $this->full_tag_open.$output.$this->full_tag_close;

		if (!empty($output_format_type)){
			$output = $this->_get_formatted_output($output_format_type, $output);
		}
		return $output;
	}

	public function _get_formatted_output($output_type, $output){
		$tmp = '';
		if($output_type == 'sortBy'){
			$tmp = str_replace(
				array('<a ', '</a>', 'href='), 
				array('<span ', '</span>', 'onclick='),
				$output
			);
			$tmp = preg_replace(
				'/\?page=([\d]+)"/', 
				'\'$1\');"',
				$tmp
			);
		}elseif ($output_type == 'saveDs'){
			$tmp = str_replace(
				array('<a ', '</a>', 'href='), 
				array('<span ', '</span>', 'onclick='),
				$output
			);
			$tmp = preg_replace(
				'/\?page=([\d]+)"/', 
				'\'$1\');"',
				$tmp
			);	      
		}elseif ($output_type == 'loadStatistics'){
			$tmp = str_replace(
				array('<a ', '</a>', 'href='), 
				array('<span class="action" ', '</span>', 'onclick='),
				$output
			);	
			$tmp = preg_replace(
				'/\?page=([\d]+)"/', 
				'\'$1\');"',
				$tmp
			);	      
		}elseif ($output_type == 'briefPage'){
			$tmp = preg_replace(
				'/\?page=([\d]+)/', 
				'$1',
				$output
			);
		}		
		return $tmp;   
	}

	// My fix
	// Remove url_string
	public function remove_url_suffix($base_url = '', $url_suffix = ''){
		if($url_suffix != ""){
			return preg_replace("|".preg_quote($url_suffix)."$|", "", $base_url);
		}
		return $this->base_url;
	}
}
