<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_Pagination {

	var $base_url			= ''; 
	var $prefix				= ''; 
	var $suffix				= ''; 

	var $total_rows			=  0; 
	var $per_page			= 10; 
	var $num_links			=  2; 
	var $cur_page			=  0; 
	var $use_page_numbers	= FALSE; 
	var $first_link			= '&lsaquo; First';
	var $next_link			= '&gt;';
	var $prev_link			= '&lt;';
	var $last_link			= 'Last &rsaquo;';
	var $uri_segment		= 3;
	var $full_tag_open		= '';
	var $full_tag_close		= '';
	var $first_tag_open		= '';
	var $first_tag_close	= '&nbsp;';
	var $last_tag_open		= '&nbsp;';
	var $last_tag_close		= '';
	var $first_url			= ''; 
	var $cur_tag_open		= '&nbsp;<strong>';
	var $cur_tag_close		= '</strong>';
	var $next_tag_open		= '&nbsp;';
	var $next_tag_close		= '&nbsp;';
	var $prev_tag_open		= '&nbsp;';
	var $prev_tag_close		= '';
	var $num_tag_open		= '&nbsp;';
	var $num_tag_close		= '';
	var $page_query_string	= FALSE;
	var $query_string_segment = 'per_page';
	var $display_pages		= TRUE;
	var $anchor_class		= '';

	
	public function __construct($params = array())
	{
		if (count($params) > 0)
		{
			$this->initialize($params);
		}

		if ($this->anchor_class != '')
		{
			$this->anchor_class = 'class="'.$this->anchor_class.'" ';
		}

		log_message('debug', "Pagination Class Initialized");
	}

	
	function initialize($params = array())
	{
		if (count($params) > 0)
		{
			foreach ($params as $key => $val)
			{
				if (isset($this->$key))
				{
					$this->$key = $val;
				}
			}
		}
	}

	
	function create_links()
	{
		
		if ($this->total_rows == 0 OR $this->per_page == 0)
		{
			return '';
		}

		
		$num_pages = ceil($this->total_rows / $this->per_page);

		
		if ($num_pages == 1)
		{
			return '';
		}

		
		if ($this->use_page_numbers)
		{
			$base_page = 1;
		}
		else
		{
			$base_page = 0;
		}

		
		$CI =& get_instance();

		if ($CI->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE)
		{
			if ($CI->input->get($this->query_string_segment) != $base_page)
			{
				$this->cur_page = $CI->input->get($this->query_string_segment);

				
				$this->cur_page = (int) $this->cur_page;
			}
		}
		else
		{
			if ($CI->uri->segment($this->uri_segment) != $base_page)
			{
				$this->cur_page = $CI->uri->segment($this->uri_segment);

				
				$this->cur_page = (int) $this->cur_page;
			}
		}
		
		
		if ($this->use_page_numbers AND $this->cur_page == 0)
		{
			$this->cur_page = $base_page;
		}

		$this->num_links = (int)$this->num_links;

		if ($this->num_links < 1)
		{
			show_error('Your number of links must be a positive number.');
		}

		if ( ! is_numeric($this->cur_page))
		{
			$this->cur_page = $base_page;
		}

		
		if ($this->use_page_numbers)
		{
			if ($this->cur_page > $num_pages)
			{
				$this->cur_page = $num_pages;
			}
		}
		else
		{
			if ($this->cur_page > $this->total_rows)
			{
				$this->cur_page = ($num_pages - 1) * $this->per_page;
			}
		}

		$uri_page_number = $this->cur_page;
		
		if ( ! $this->use_page_numbers)
		{
			$this->cur_page = floor(($this->cur_page/$this->per_page) + 1);
		}

		
		$start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links - 1) : 1;
		$end   = (($this->cur_page + $this->num_links) < $num_pages) ? $this->cur_page + $this->num_links : $num_pages;

		
		if ($CI->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE)
		{
			$this->base_url = rtrim($this->base_url).'&amp;'.$this->query_string_segment.'=';
		}
		else
		{
			$this->base_url = rtrim($this->base_url, '/') .'/';
		}

		
		$output = '';

		
		if  ($this->first_link !== FALSE AND $this->cur_page > ($this->num_links + 1))
		{
			$first_url = ($this->first_url == '') ? $this->base_url : $this->first_url;
			$output .= $this->first_tag_open.'<a '.$this->anchor_class.'href="'.$first_url.'">'.$this->first_link.'</a>'.$this->first_tag_close;
		}

		
		if  ($this->prev_link !== FALSE AND $this->cur_page != 1)
		{
			if ($this->use_page_numbers)
			{
				$i = $uri_page_number - 1;
			}
			else
			{
				$i = $uri_page_number - $this->per_page;
			}

			if ($i == 0 && $this->first_url != '')
			{
				$output .= $this->prev_tag_open.'<a '.$this->anchor_class.'href="'.$this->first_url.'">'.$this->prev_link.'</a>'.$this->prev_tag_close;
			}
			else
			{
				$i = ($i == 0) ? '' : $this->prefix.$i.$this->suffix;
				$output .= $this->prev_tag_open.'<a '.$this->anchor_class.'href="'.$this->base_url.$i.'">'.$this->prev_link.'</a>'.$this->prev_tag_close;
			}

		}

		
		if ($this->display_pages !== FALSE)
		{
			
			for ($loop = $start -1; $loop <= $end; $loop++)
			{
				if ($this->use_page_numbers)
				{
					$i = $loop;
				}
				else
				{
					$i = ($loop * $this->per_page) - $this->per_page;
				}

				if ($i >= $base_page)
				{
					if ($this->cur_page == $loop)
					{
						$output .= $this->cur_tag_open.$loop.$this->cur_tag_close; 
					}
					else
					{
						$n = ($i == $base_page) ? '' : $i;

						if ($n == '' && $this->first_url != '')
						{
							$output .= $this->num_tag_open.'<a '.$this->anchor_class.'href="'.$this->first_url.'">'.$loop.'</a>'.$this->num_tag_close;
						}
						else
						{
							$n = ($n == '') ? '' : $this->prefix.$n.$this->suffix;

							$output .= $this->num_tag_open.'<a '.$this->anchor_class.'href="'.$this->base_url.$n.'">'.$loop.'</a>'.$this->num_tag_close;
						}
					}
				}
			}
		}

		
		if ($this->next_link !== FALSE AND $this->cur_page < $num_pages)
		{
			if ($this->use_page_numbers)
			{
				$i = $this->cur_page + 1;
			}
			else
			{
				$i = ($this->cur_page * $this->per_page);
			}

			$output .= $this->next_tag_open.'<a '.$this->anchor_class.'href="'.$this->base_url.$this->prefix.$i.$this->suffix.'">'.$this->next_link.'</a>'.$this->next_tag_close;
		}

		
		if ($this->last_link !== FALSE AND ($this->cur_page + $this->num_links) < $num_pages)
		{
			if ($this->use_page_numbers)
			{
				$i = $num_pages;
			}
			else
			{
				$i = (($num_pages * $this->per_page) - $this->per_page);
			}
			$output .= $this->last_tag_open.'<a '.$this->anchor_class.'href="'.$this->base_url.$this->prefix.$i.$this->suffix.'">'.$this->last_link.'</a>'.$this->last_tag_close;
		}

		
		$output = preg_replace("#([^:])//+#", "\\1/", $output);

		
		$output = $this->full_tag_open.$output.$this->full_tag_close;

		return $output;
	}
}
