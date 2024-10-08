<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_Cart {

	
	var $product_id_rules	= '\.a-z0-9_-'; 
	var $product_name_rules	= '\.\:\-_ a-z0-9'; 

	
	var $CI;
	var $_cart_contents	= array();


	
	public function __construct($params = array())
	{
		
		$this->CI =& get_instance();

		
		$config = array();
		if (count($params) > 0)
		{
			foreach ($params as $key => $val)
			{
				$config[$key] = $val;
			}
		}

		
		$this->CI->load->library('session', $config);

		
		if ($this->CI->session->userdata('cart_contents') !== FALSE)
		{
			$this->_cart_contents = $this->CI->session->userdata('cart_contents');
		}
		else
		{
			
			$this->_cart_contents['cart_total'] = 0;
			$this->_cart_contents['total_items'] = 0;
		}

		log_message('debug', "Cart Class Initialized");
	}

	
	function insert($items = array())
	{
		
		if ( ! is_array($items) OR count($items) == 0)
		{
			log_message('error', 'The insert method must be passed an array containing data.');
			return FALSE;
		}

		
		$save_cart = FALSE;
		if (isset($items['id']))
		{
			if (($rowid = $this->_insert($items)))
			{
				$save_cart = TRUE;
			}
		}
		else
		{
			foreach ($items as $val)
			{
				if (is_array($val) AND isset($val['id']))
				{
					if ($this->_insert($val))
					{
						$save_cart = TRUE;
					}
				}
			}
		}

		
		if ($save_cart == TRUE)
		{
			$this->_save_cart();
			return isset($rowid) ? $rowid : TRUE;
		}

		return FALSE;
	}

	
	function _insert($items = array())
	{
		
		if ( ! is_array($items) OR count($items) == 0)
		{
			log_message('error', 'The insert method must be passed an array containing data.');
			return FALSE;
		}

		
		if ( ! isset($items['id']) OR ! isset($items['qty']) OR ! isset($items['price']) OR ! isset($items['name']))
		{
			log_message('error', 'The cart array must contain a product ID, quantity, price, and name.');
			return FALSE;
		}

		
		$items['qty'] = trim(preg_replace('/([^0-9])/i', '', $items['qty']));
		
		$items['qty'] = trim(preg_replace('/(^[0]+)/i', '', $items['qty']));

		
		if ( ! is_numeric($items['qty']) OR $items['qty'] == 0)
		{
			return FALSE;
		}

		
		if ( ! preg_match("/^[".$this->product_id_rules."]+$/i", $items['id']))
		{
			log_message('error', 'Invalid product ID.  The product ID can only contain alpha-numeric characters, dashes, and underscores');
			return FALSE;
		}

		
		if ( ! preg_match("/^[".$this->product_name_rules."]+$/i", $items['name']))
		{
			log_message('error', 'An invalid name was submitted as the product name: '.$items['name'].' The name can only contain alpha-numeric characters, dashes, underscores, colons, and spaces');
			return FALSE;
		}

		
		$items['price'] = trim(preg_replace('/([^0-9\.])/i', '', $items['price']));
		
		$items['price'] = trim(preg_replace('/(^[0]+)/i', '', $items['price']));

		
		if ( ! is_numeric($items['price']))
		{
			log_message('error', 'An invalid price was submitted for product ID: '.$items['id']);
			return FALSE;
		}

		
		if (isset($items['options']) AND count($items['options']) > 0)
		{
			$rowid = md5($items['id'].implode('', $items['options']));
		}
		else
		{
			
			$rowid = md5($items['id']);
		}

		
		unset($this->_cart_contents[$rowid]);

		$this->_cart_contents[$rowid]['rowid'] = $rowid;

		
		foreach ($items as $key => $val)
		{
			$this->_cart_contents[$rowid][$key] = $val;
		}

		
		return $rowid;
	}

	function update($items = array())
	{
		
		if ( ! is_array($items) OR count($items) == 0)
		{
			return FALSE;
		}

		
		$save_cart = FALSE;
		if (isset($items['rowid']) AND isset($items['qty']))
		{
			if ($this->_update($items) == TRUE)
			{
				$save_cart = TRUE;
			}
		}
		else
		{
			foreach ($items as $val)
			{
				if (is_array($val) AND isset($val['rowid']) AND isset($val['qty']))
				{
					if ($this->_update($val) == TRUE)
					{
						$save_cart = TRUE;
					}
				}
			}
		}

		
		if ($save_cart == TRUE)
		{
			$this->_save_cart();
			return TRUE;
		}

		return FALSE;
	}

	
	function _update($items = array())
	{
		
		if ( ! isset($items['qty']) OR ! isset($items['rowid']) OR ! isset($this->_cart_contents[$items['rowid']]))
		{
			return FALSE;
		}

		
		$items['qty'] = preg_replace('/([^0-9])/i', '', $items['qty']);

		
		if ( ! is_numeric($items['qty']))
		{
			return FALSE;
		}

		
		if ($this->_cart_contents[$items['rowid']]['qty'] == $items['qty'])
		{
			return FALSE;
		}

		
		if ($items['qty'] == 0)
		{
			unset($this->_cart_contents[$items['rowid']]);
		}
		else
		{
			$this->_cart_contents[$items['rowid']]['qty'] = $items['qty'];
		}

		return TRUE;
	}

	
	function _save_cart()
	{
		
		unset($this->_cart_contents['total_items']);
		unset($this->_cart_contents['cart_total']);

		
		$total = 0;
		$items = 0;
		foreach ($this->_cart_contents as $key => $val)
		{
			
			if ( ! is_array($val) OR ! isset($val['price']) OR ! isset($val['qty']))
			{
				continue;
			}

			$total += ($val['price'] * $val['qty']);
			$items += $val['qty'];

			
			$this->_cart_contents[$key]['subtotal'] = ($this->_cart_contents[$key]['price'] * $this->_cart_contents[$key]['qty']);
		}

		
		$this->_cart_contents['total_items'] = $items;
		$this->_cart_contents['cart_total'] = $total;

		
		if (count($this->_cart_contents) <= 2)
		{
			$this->CI->session->unset_userdata('cart_contents');

			
			return FALSE;
		}

		
		$this->CI->session->set_userdata(array('cart_contents' => $this->_cart_contents));

		
		return TRUE;
	}

	
	function total()
	{
		return $this->_cart_contents['cart_total'];
	}

	
	function total_items()
	{
		return $this->_cart_contents['total_items'];
	}

	
	function contents()
	{
		$cart = $this->_cart_contents;

		
		unset($cart['total_items']);
		unset($cart['cart_total']);

		return $cart;
	}

	
	function has_options($rowid = '')
	{
		if ( ! isset($this->_cart_contents[$rowid]['options']) OR count($this->_cart_contents[$rowid]['options']) === 0)
		{
			return FALSE;
		}

		return TRUE;
	}

	
	function product_options($rowid = '')
	{
		if ( ! isset($this->_cart_contents[$rowid]['options']))
		{
			return array();
		}

		return $this->_cart_contents[$rowid]['options'];
	}

	
	function format_number($n = '')
	{
		if ($n == '')
		{
			return '';
		}

		
		$n = trim(preg_replace('/([^0-9\.])/i', '', $n));

		return number_format($n, 2, '.', ',');
	}

	
	function destroy()
	{
		unset($this->_cart_contents);

		$this->_cart_contents['cart_total'] = 0;
		$this->_cart_contents['total_items'] = 0;

		$this->CI->session->unset_userdata('cart_contents');
	}


}
