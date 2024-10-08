<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_Javascript {

	var $_javascript_location = 'js';

	public function __construct($params = array())
	{
		$defaults = array('js_library_driver' => 'jquery', 'autoload' => TRUE);

		foreach ($defaults as $key => $val)
		{
			if (isset($params[$key]) && $params[$key] !== "")
			{
				$defaults[$key] = $params[$key];
			}
		}

		extract($defaults);

		$this->CI =& get_instance();

		
		$this->CI->load->library('javascript/'.$js_library_driver, array('autoload' => $autoload));
		
		$this->js =& $this->CI->$js_library_driver;

		log_message('debug', "Javascript Class Initialized and loaded.  Driver used: $js_library_driver");
	}

	
	function blur($element = 'this', $js = '')
	{
		return $this->js->_blur($element, $js);
	}

	
	function change($element = 'this', $js = '')
	{
		return $this->js->_change($element, $js);
	}

	
	function click($element = 'this', $js = '', $ret_false = TRUE)
	{
		return $this->js->_click($element, $js, $ret_false);
	}

	
	function dblclick($element = 'this', $js = '')
	{
		return $this->js->_dblclick($element, $js);
	}

	
	function error($element = 'this', $js = '')
	{
		return $this->js->_error($element, $js);
	}

	
	function focus($element = 'this', $js = '')
	{
		return $this->js->__add_event($focus, $js);
	}

	
	function hover($element = 'this', $over, $out)
	{
		return $this->js->__hover($element, $over, $out);
	}

	
	function keydown($element = 'this', $js = '')
	{
		return $this->js->_keydown($element, $js);
	}

	function keyup($element = 'this', $js = '')
	{
		return $this->js->_keyup($element, $js);
	}

	
	function load($element = 'this', $js = '')
	{
		return $this->js->_load($element, $js);
	}

	
	function mousedown($element = 'this', $js = '')
	{
		return $this->js->_mousedown($element, $js);
	}

	
	function mouseout($element = 'this', $js = '')
	{
		return $this->js->_mouseout($element, $js);
	}

	
	function mouseover($element = 'this', $js = '')
	{
		return $this->js->_mouseover($element, $js);
	}

	
	function mouseup($element = 'this', $js = '')
	{
		return $this->js->_mouseup($element, $js);
	}

	
	function output($js)
	{
		return $this->js->_output($js);
	}

	
	function ready($js)
	{
		return $this->js->_document_ready($js);
	}

	
	function resize($element = 'this', $js = '')
	{
		return $this->js->_resize($element, $js);
	}

	
	function scroll($element = 'this', $js = '')
	{
		return $this->js->_scroll($element, $js);
	}

	
	function unload($element = 'this', $js = '')
	{
		return $this->js->_unload($element, $js);
	}

	
	function addClass($element = 'this', $class = '')
	{
		return $this->js->_addClass($element, $class);
	}

	
	function animate($element = 'this', $params = array(), $speed = '', $extra = '')
	{
		return $this->js->_animate($element, $params, $speed, $extra);
	}

	
	function fadeIn($element = 'this', $speed = '', $callback = '')
	{
		return $this->js->_fadeIn($element, $speed, $callback);
	}

	
	function fadeOut($element = 'this', $speed = '', $callback = '')
	{
		return $this->js->_fadeOut($element, $speed, $callback);
	}
	
	function slideUp($element = 'this', $speed = '', $callback = '')
	{
		return $this->js->_slideUp($element, $speed, $callback);

	}

	
	function removeClass($element = 'this', $class = '')
	{
		return $this->js->_removeClass($element, $class);
	}

	
	function slideDown($element = 'this', $speed = '', $callback = '')
	{
		return $this->js->_slideDown($element, $speed, $callback);
	}

	
	function slideToggle($element = 'this', $speed = '', $callback = '')
	{
		return $this->js->_slideToggle($element, $speed, $callback);

	}

	
	function hide($element = 'this', $speed = '', $callback = '')
	{
		return $this->js->_hide($element, $speed, $callback);
	}

	
	function toggle($element = 'this')
	{
		return $this->js->_toggle($element);

	}

	
	function toggleClass($element = 'this', $class='')
	{
		return $this->js->_toggleClass($element, $class);
	}

	
	function show($element = 'this', $speed = '', $callback = '')
	{
		return $this->js->_show($element, $speed, $callback);
	}


	
	function compile($view_var = 'script_foot', $script_tags = TRUE)
	{
		$this->js->_compile($view_var, $script_tags);
	}

	
	function clear_compile()
	{
		$this->js->_clear_compile();
	}

	
	function external($external_file = '', $relative = FALSE)
	{
		if ($external_file !== '')
		{
			$this->_javascript_location = $external_file;
		}
		else
		{
			if ($this->CI->config->item('javascript_location') != '')
			{
				$this->_javascript_location = $this->CI->config->item('javascript_location');
			}
		}

		if ($relative === TRUE OR strncmp($external_file, 'http://', 7) == 0 OR strncmp($external_file, 'https://', 8) == 0)
		{
			$str = $this->_open_script($external_file);
		}
		elseif (strpos($this->_javascript_location, 'http://') !== FALSE)
		{
			$str = $this->_open_script($this->_javascript_location.$external_file);
		}
		else
		{
			$str = $this->_open_script($this->CI->config->slash_item('base_url').$this->_javascript_location.$external_file);
		}

		$str .= $this->_close_script();
		return $str;
	}

	
	function inline($script, $cdata = TRUE)
	{
		$str = $this->_open_script();
		$str .= ($cdata) ? "\n// <![CDATA[\n{$script}\n// ]]>\n" : "\n{$script}\n";
		$str .= $this->_close_script();

		return $str;
	}
	
	
	function _open_script($src = '')
	{
		$str = '<script type="text/javascript" charset="'.strtolower($this->CI->config->item('charset')).'"';
		$str .= ($src == '') ? '>' : ' src="'.$src.'">';
		return $str;
	}

	
	function _close_script($extra = "\n")
	{
		return "</script>$extra";
	}


	
	function update($element = 'this', $speed = '', $callback = '')
	{
		return $this->js->_updater($element, $speed, $callback);
	}

	
	function generate_json($result = NULL, $match_array_type = FALSE)
	{
		
		if ( ! is_null($result))
		{
			if (is_object($result))
			{
				$json_result = $result->result_array();
			}
			elseif (is_array($result))
			{
				$json_result = $result;
			}
			else
			{
				return $this->_prep_args($result);
			}
		}
		else
		{
			return 'null';
		}

		$json = array();
		$_is_assoc = TRUE;

		if ( ! is_array($json_result) AND empty($json_result))
		{
			show_error("Generate JSON Failed - Illegal key, value pair.");
		}
		elseif ($match_array_type)
		{
			$_is_assoc = $this->_is_associative_array($json_result);
		}

		foreach ($json_result as $k => $v)
		{
			if ($_is_assoc)
			{
				$json[] = $this->_prep_args($k, TRUE).':'.$this->generate_json($v, $match_array_type);
			}
			else
			{
				$json[] = $this->generate_json($v, $match_array_type);
			}
		}

		$json = implode(',', $json);

		return $_is_assoc ? "{".$json."}" : "[".$json."]";

	}

	
	function _is_associative_array($arr)
	{
		foreach (array_keys($arr) as $key => $val)
		{
			if ($key !== $val)
			{
				return TRUE;
			}
		}

		return FALSE;
	}

	
	function _prep_args($result, $is_key = FALSE)
	{
		if (is_null($result))
		{
			return 'null';
		}
		elseif (is_bool($result))
		{
			return ($result === TRUE) ? 'true' : 'false';
		}
		elseif (is_string($result) OR $is_key)
		{
			return '"'.str_replace(array('\\', "\t", "\n", "\r", '"', '/'), array('\\\\', '\\t', '\\n', "\\r", '\"', '\/'), $result).'"';			
		}
		elseif (is_scalar($result))
		{
			return $result;
		}
	}

	
}
