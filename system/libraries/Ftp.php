<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_FTP {

	var $hostname	= '';
	var $username	= '';
	var $password	= '';
	var $port		= 21;
	var $passive	= TRUE;
	var $debug		= FALSE;
	var $conn_id	= FALSE;


	
	public function __construct($config = array())
	{
		if (count($config) > 0)
		{
			$this->initialize($config);
		}

		log_message('debug', "FTP Class Initialized");
	}

	
	function initialize($config = array())
	{
		foreach ($config as $key => $val)
		{
			if (isset($this->$key))
			{
				$this->$key = $val;
			}
		}

		
		$this->hostname = preg_replace('|.+?://|', '', $this->hostname);
	}

	
	function connect($config = array())
	{
		if (count($config) > 0)
		{
			$this->initialize($config);
		}

		if (FALSE === ($this->conn_id = @ftp_connect($this->hostname, $this->port)))
		{
			if ($this->debug == TRUE)
			{
				$this->_error('ftp_unable_to_connect');
			}
			return FALSE;
		}

		if ( ! $this->_login())
		{
			if ($this->debug == TRUE)
			{
				$this->_error('ftp_unable_to_login');
			}
			return FALSE;
		}

		
		if ($this->passive == TRUE)
		{
			ftp_pasv($this->conn_id, TRUE);
		}

		return TRUE;
	}

	
	function _login()
	{
		return @ftp_login($this->conn_id, $this->username, $this->password);
	}

	
	function _is_conn()
	{
		if ( ! is_resource($this->conn_id))
		{
			if ($this->debug == TRUE)
			{
				$this->_error('ftp_no_connection');
			}
			return FALSE;
		}
		return TRUE;
	}

	
	function changedir($path = '', $supress_debug = FALSE)
	{
		if ($path == '' OR ! $this->_is_conn())
		{
			return FALSE;
		}

		$result = @ftp_chdir($this->conn_id, $path);

		if ($result === FALSE)
		{
			if ($this->debug == TRUE AND $supress_debug == FALSE)
			{
				$this->_error('ftp_unable_to_changedir');
			}
			return FALSE;
		}

		return TRUE;
	}

	
	function mkdir($path = '', $permissions = NULL)
	{
		if ($path == '' OR ! $this->_is_conn())
		{
			return FALSE;
		}

		$result = @ftp_mkdir($this->conn_id, $path);

		if ($result === FALSE)
		{
			if ($this->debug == TRUE)
			{
				$this->_error('ftp_unable_to_makdir');
			}
			return FALSE;
		}

		
		if ( ! is_null($permissions))
		{
			$this->chmod($path, (int)$permissions);
		}

		return TRUE;
	}

	
	function upload($locpath, $rempath, $mode = 'auto', $permissions = NULL)
	{
		if ( ! $this->_is_conn())
		{
			return FALSE;
		}

		if ( ! file_exists($locpath))
		{
			$this->_error('ftp_no_source_file');
			return FALSE;
		}

		
		if ($mode == 'auto')
		{
			
			$ext = $this->_getext($locpath);
			$mode = $this->_settype($ext);
		}

		$mode = ($mode == 'ascii') ? FTP_ASCII : FTP_BINARY;

		$result = @ftp_put($this->conn_id, $rempath, $locpath, $mode);

		if ($result === FALSE)
		{
			if ($this->debug == TRUE)
			{
				$this->_error('ftp_unable_to_upload');
			}
			return FALSE;
		}

		
		if ( ! is_null($permissions))
		{
			$this->chmod($rempath, (int)$permissions);
		}

		return TRUE;
	}

	
	function download($rempath, $locpath, $mode = 'auto')
	{
		if ( ! $this->_is_conn())
		{
			return FALSE;
		}

		
		if ($mode == 'auto')
		{
			
			$ext = $this->_getext($rempath);
			$mode = $this->_settype($ext);
		}

		$mode = ($mode == 'ascii') ? FTP_ASCII : FTP_BINARY;

		$result = @ftp_get($this->conn_id, $locpath, $rempath, $mode);

		if ($result === FALSE)
		{
			if ($this->debug == TRUE)
			{
				$this->_error('ftp_unable_to_download');
			}
			return FALSE;
		}

		return TRUE;
	}

	
	function rename($old_file, $new_file, $move = FALSE)
	{
		if ( ! $this->_is_conn())
		{
			return FALSE;
		}

		$result = @ftp_rename($this->conn_id, $old_file, $new_file);

		if ($result === FALSE)
		{
			if ($this->debug == TRUE)
			{
				$msg = ($move == FALSE) ? 'ftp_unable_to_rename' : 'ftp_unable_to_move';

				$this->_error($msg);
			}
			return FALSE;
		}

		return TRUE;
	}

	
	function move($old_file, $new_file)
	{
		return $this->rename($old_file, $new_file, TRUE);
	}

	
	function delete_file($filepath)
	{
		if ( ! $this->_is_conn())
		{
			return FALSE;
		}

		$result = @ftp_delete($this->conn_id, $filepath);

		if ($result === FALSE)
		{
			if ($this->debug == TRUE)
			{
				$this->_error('ftp_unable_to_delete');
			}
			return FALSE;
		}

		return TRUE;
	}

	
	function delete_dir($filepath)
	{
		if ( ! $this->_is_conn())
		{
			return FALSE;
		}

		
		$filepath = preg_replace("/(.+?)\/*$/", "\\1/",  $filepath);

		$list = $this->list_files($filepath);

		if ($list !== FALSE AND count($list) > 0)
		{
			foreach ($list as $item)
			{
				
				if ( ! @ftp_delete($this->conn_id, $item))
				{
					$this->delete_dir($item);
				}
			}
		}

		$result = @ftp_rmdir($this->conn_id, $filepath);

		if ($result === FALSE)
		{
			if ($this->debug == TRUE)
			{
				$this->_error('ftp_unable_to_delete');
			}
			return FALSE;
		}

		return TRUE;
	}

	
	function chmod($path, $perm)
	{
		if ( ! $this->_is_conn())
		{
			return FALSE;
		}

		
		if ( ! function_exists('ftp_chmod'))
		{
			if ($this->debug == TRUE)
			{
				$this->_error('ftp_unable_to_chmod');
			}
			return FALSE;
		}

		$result = @ftp_chmod($this->conn_id, $perm, $path);

		if ($result === FALSE)
		{
			if ($this->debug == TRUE)
			{
				$this->_error('ftp_unable_to_chmod');
			}
			return FALSE;
		}

		return TRUE;
	}

	function list_files($path = '.')
	{
		if ( ! $this->_is_conn())
		{
			return FALSE;
		}

		return ftp_nlist($this->conn_id, $path);
	}

	
	function mirror($locpath, $rempath)
	{
		if ( ! $this->_is_conn())
		{
			return FALSE;
		}

		
		if ($fp = @opendir($locpath))
		{
			
			if ( ! $this->changedir($rempath, TRUE))
			{
				
				if ( ! $this->mkdir($rempath) OR ! $this->changedir($rempath))
				{
					return FALSE;
				}
			}

			
			while (FALSE !== ($file = readdir($fp)))
			{
				if (@is_dir($locpath.$file) && substr($file, 0, 1) != '.')
				{
					$this->mirror($locpath.$file."/", $rempath.$file."/");
				}
				elseif (substr($file, 0, 1) != ".")
				{
					
					$ext = $this->_getext($file);
					$mode = $this->_settype($ext);

					$this->upload($locpath.$file, $rempath.$file, $mode);
				}
			}
			return TRUE;
		}

		return FALSE;
	}


	
	function _getext($filename)
	{
		if (FALSE === strpos($filename, '.'))
		{
			return 'txt';
		}

		$x = explode('.', $filename);
		return end($x);
	}


	
	function _settype($ext)
	{
		$text_types = array(
							'txt',
							'text',
							'php',
							'phps',
							'php4',
							'js',
							'css',
							'htm',
							'html',
							'phtml',
							'shtml',
							'log',
							'xml'
							);


		return (in_array($ext, $text_types)) ? 'ascii' : 'binary';
	}

	
	function close()
	{
		if ( ! $this->_is_conn())
		{
			return FALSE;
		}

		@ftp_close($this->conn_id);
	}

	
	function _error($line)
	{
		$CI =& get_instance();
		$CI->lang->load('ftp');
		show_error($CI->lang->line($line));
	}


}
