<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


 
class CI_Jquery extends CI_Javascript {

	var $_javascript_folder = 'js';
	var $jquery_code_for_load = array();
	var $jquery_code_for_compile = array();
	var $jquery_corner_active = FALSE;
	var $jquery_table_sorter_active = FALSE;
	var $jquery_table_sorter_pager_active = FALSE;
	var $jquery_ajax_img = '';

	public function __construct($params)
	{
		$this->CI =& get_instance();	
		extract($params);

		if ($autoload === TRUE)
		{
			$this->script();			
		}
		
		log_message('debug', "Jquery Class Initialized");
	}
	
	function _blur($element = 'this', $js = '')
	{
		return $this->_add_event($element, $js, 'blur');
	}
	
	
	function _change($element = 'this', $js = '')
	{
		return $this->_add_event($element, $js, 'change');
	}
	
	
	function _click($element = 'this', $js = '', $ret_false = TRUE)
	{
		if ( ! is_array($js))
		{
			$js = array($js);
		}

		if ($ret_false)
		{
			$js[] = "return false;";
		}

		return $this->_add_event($element, $js, 'click');
	}

	
	function _dblclick($element = 'this', $js = '')
	{
		return $this->_add_event($element, $js, 'dblclick');
	}

	
	function _error($element = 'this', $js = '')
	{
		return $this->_add_event($element, $js, 'error');
	}

	
	function _focus($element = 'this', $js = '')
	{
		return $this->_add_event($element, $js, 'focus');
	}

	
	function _hover($element = 'this', $over, $out)
	{
		$event = "\n\t$(" . $this->_prep_element($element) . ").hover(\n\t\tfunction()\n\t\t{\n\t\t\t{$over}\n\t\t}, \n\t\tfunction()\n\t\t{\n\t\t\t{$out}\n\t\t});\n";

		$this->jquery_code_for_compile[] = $event;

		return $event;
	}

	
	function _keydown($element = 'this', $js = '')
	{
		return $this->_add_event($element, $js, 'keydown');
	}

	function _keyup($element = 'this', $js = '')
	{
		return $this->_add_event($element, $js, 'keyup');
	}	

	
	function _load($element = 'this', $js = '')
	{
		return $this->_add_event($element, $js, 'load');
	}	
	
	
	function _mousedown($element = 'this', $js = '')
	{
		return $this->_add_event($element, $js, 'mousedown');
	}

	
	function _mouseout($element = 'this', $js = '')
	{
		return $this->_add_event($element, $js, 'mouseout');
	}

	
	function _mouseover($element = 'this', $js = '')
	{
		return $this->_add_event($element, $js, 'mouseover');
	}

	
	function _mouseup($element = 'this', $js = '')
	{
		return $this->_add_event($element, $js, 'mouseup');
	}

	
	function _output($array_js = '')
	{
		if ( ! is_array($array_js))
		{
			$array_js = array($array_js);
		}
		
		foreach ($array_js as $js)
		{
			$this->jquery_code_for_compile[] = "\t$js\n";
		}
	}

	
	function _resize($element = 'this', $js = '')
	{
		return $this->_add_event($element, $js, 'resize');
	}

	
	function _scroll($element = 'this', $js = '')
	{
		return $this->_add_event($element, $js, 'scroll');
	}
	
	
	function _unload($element = 'this', $js = '')
	{
		return $this->_add_event($element, $js, 'unload');
	}

	
	function _addClass($element = 'this', $class='')
	{
		$element = $this->_prep_element($element);
		$str  = "$({$element}).addClass(\"$class\");";
		return $str;
	}

	
	function _animate($element = 'this', $params = array(), $speed = '', $extra = '')
	{
		$element = $this->_prep_element($element);
		$speed = $this->_validate_speed($speed);
		
		$animations = "\t\t\t";
		
		foreach ($params as $param=>$value)
		{
			$animations .= $param.': \''.$value.'\', ';
		}

		$animations = substr($animations, 0, -2); 

		if ($speed != '')
		{
			$speed = ', '.$speed;
		}
		
		if ($extra != '')
		{
			$extra = ', '.$extra;
		}
		
		$str  = "$({$element}).animate({\n$animations\n\t\t}".$speed.$extra.");";
		
		return $str;
	}

	
	function _fadeIn($element = 'this', $speed = '', $callback = '')
	{
		$element = $this->_prep_element($element);	
		$speed = $this->_validate_speed($speed);
		
		if ($callback != '')
		{
			$callback = ", function(){\n{$callback}\n}";
		}
		
		$str  = "$({$element}).fadeIn({$speed}{$callback});";
		
		return $str;
	}

	function _fadeOut($element = 'this', $speed = '', $callback = '')
	{
		$element = $this->_prep_element($element);
		$speed = $this->_validate_speed($speed);
		
		if ($callback != '')
		{
			$callback = ", function(){\n{$callback}\n}";
		}
		
		$str  = "$({$element}).fadeOut({$speed}{$callback});";
		
		return $str;
	}

	
	function _hide($element = 'this', $speed = '', $callback = '')
	{
		$element = $this->_prep_element($element);	
		$speed = $this->_validate_speed($speed);
		
		if ($callback != '')
		{
			$callback = ", function(){\n{$callback}\n}";
		}
		
		$str  = "$({$element}).hide({$speed}{$callback});";

		return $str;
	}
	
	
	function _removeClass($element = 'this', $class='')
	{
		$element = $this->_prep_element($element);
		$str  = "$({$element}).removeClass(\"$class\");";
		return $str;
	}

	
	function _slideUp($element = 'this', $speed = '', $callback = '')
	{
		$element = $this->_prep_element($element);	
		$speed = $this->_validate_speed($speed);
		
		if ($callback != '')
		{
			$callback = ", function(){\n{$callback}\n}";
		}
		
		$str  = "$({$element}).slideUp({$speed}{$callback});";
		
		return $str;
	}
	
	function _slideDown($element = 'this', $speed = '', $callback = '')
	{
		$element = $this->_prep_element($element);
		$speed = $this->_validate_speed($speed);
		
		if ($callback != '')
		{
			$callback = ", function(){\n{$callback}\n}";
		}
		
		$str  = "$({$element}).slideDown({$speed}{$callback});";
		
		return $str;
	}

	
	function _slideToggle($element = 'this', $speed = '', $callback = '')
	{
		$element = $this->_prep_element($element);
		$speed = $this->_validate_speed($speed);
		
		if ($callback != '')
		{
			$callback = ", function(){\n{$callback}\n}";
		}
		
		$str  = "$({$element}).slideToggle({$speed}{$callback});";
		
		return $str;
	}
	
	function _toggle($element = 'this')
	{
		$element = $this->_prep_element($element);
		$str  = "$({$element}).toggle();";
		return $str;
	}
	
	
	function _toggleClass($element = 'this', $class='')
	{
		$element = $this->_prep_element($element);
		$str  = "$({$element}).toggleClass(\"$class\");";
		return $str;
	}
	
	
	function _show($element = 'this', $speed = '', $callback = '')
	{
		$element = $this->_prep_element($element);	
		$speed = $this->_validate_speed($speed);
		
		if ($callback != '')
		{
			$callback = ", function(){\n{$callback}\n}";
		}
		
		$str  = "$({$element}).show({$speed}{$callback});";
		
		return $str;
	}

	
	function _updater($container = 'this', $controller, $options = '')
	{	
		$container = $this->_prep_element($container);
		
		$controller = (strpos('://', $controller) === FALSE) ? $controller : $this->CI->config->site_url($controller);
		
		
		if ($this->CI->config->item('javascript_ajax_img') == '')
		{
			$loading_notifier = "Loading...";
		}
		else
		{
			$loading_notifier = '<img src=\'' . $this->CI->config->slash_item('base_url') . $this->CI->config->item('javascript_ajax_img') . '\' alt=\'Loading\' />';
		}
		
		$updater = "$($container).empty();\n"; 
		$updater .= "\t\t$($container).prepend(\"$loading_notifier\");\n"; 

		$request_options = '';
		if ($options != '')
		{
			$request_options .= ", {";
			$request_options .= (is_array($options)) ? "'".implode("', '", $options)."'" : "'".str_replace(":", "':'", $options)."'";
			$request_options .= "}";
		}

		$updater .= "\t\t$($container).load('$controller'$request_options);";
		return $updater;
	}


	function _zebraTables($class = '', $odd = 'odd', $hover = '')
	{
		$class = ($class != '') ? '.'.$class : '';
		
		$zebra  = "\t\$(\"table{$class} tbody tr:nth-child(even)\").addClass(\"{$odd}\");";

		$this->jquery_code_for_compile[] = $zebra;

		if ($hover != '')
		{
			$hover = $this->hover("table{$class} tbody tr", "$(this).addClass('hover');", "$(this).removeClass('hover');");
		}

		return $zebra;
	}


	function corner($element = '', $corner_style = '')
	{
		
		$corner_location = '/plugins/jquery.corner.js';

		if ($corner_style != '')
		{
			$corner_style = '"'.$corner_style.'"';
		}

		return "$(" . $this->_prep_element($element) . ").corner(".$corner_style.");";
	}
	
	
	function modal($src, $relative = FALSE)
	{	
		$this->jquery_code_for_load[] = $this->external($src, $relative);
	}

	
	function effect($src, $relative = FALSE)
	{
		$this->jquery_code_for_load[] = $this->external($src, $relative);
	}

	
	function plugin($src, $relative = FALSE)
	{
		$this->jquery_code_for_load[] = $this->external($src, $relative);
	}

	
	function ui($src, $relative = FALSE)
	{
		$this->jquery_code_for_load[] = $this->external($src, $relative);
	}
	
	function sortable($element, $options = array())
	{

		if (count($options) > 0)
		{
			$sort_options = array();
			foreach ($options as $k=>$v)
			{
				$sort_options[] = "\n\t\t".$k.': '.$v."";
			}
			$sort_options = implode(",", $sort_options);
		}
		else
		{
			$sort_options = '';
		}

		return "$(" . $this->_prep_element($element) . ").sortable({".$sort_options."\n\t});";
	}

	
	function tablesorter($table = '', $options = '')
	{
		$this->jquery_code_for_compile[] = "\t$(" . $this->_prep_element($table) . ").tablesorter($options);\n";
	}
	
	
	function _add_event($element, $js, $event)
	{
		if (is_array($js))
		{
			$js = implode("\n\t\t", $js);

		}

		$event = "\n\t$(" . $this->_prep_element($element) . ").{$event}(function(){\n\t\t{$js}\n\t});\n";
		$this->jquery_code_for_compile[] = $event;
		return $event;
	}

	
	function _compile($view_var = 'script_foot', $script_tags = TRUE)
	{
		
		$external_scripts = implode('', $this->jquery_code_for_load);
		$this->CI->load->vars(array('library_src' => $external_scripts));

		if (count($this->jquery_code_for_compile) == 0 )
		{
			
			return;
		}

		
		$script = '$(document).ready(function() {' . "\n";
		$script .= implode('', $this->jquery_code_for_compile);
		$script .= '});';
		
		$output = ($script_tags === FALSE) ? $script : $this->inline($script);

		$this->CI->load->vars(array($view_var => $output));

	}
	
	
	function _clear_compile()
	{
		$this->jquery_code_for_compile = array();
	}

	
	function _document_ready($js)
	{
		if ( ! is_array($js))
		{
			$js = array ($js);

		}
		
		foreach ($js as $script)
		{
			$this->jquery_code_for_compile[] = $script;
		}
	}

	function script($library_src = '', $relative = FALSE)
	{
		$library_src = $this->external($library_src, $relative);
		$this->jquery_code_for_load[] = $library_src;
		return $library_src;
	}
	
	
	function _prep_element($element)
	{
		if ($element != 'this')
		{
			$element = '"'.$element.'"';
		}
		
		return $element;
	}
	
	
	function _validate_speed($speed)
	{
		if (in_array($speed, array('slow', 'normal', 'fast')))
		{
			$speed = '"'.$speed.'"';
		}
		elseif (preg_match("/[^0-9]/", $speed))
		{
			$speed = '';
		}
	
		return $speed;
	}

}
