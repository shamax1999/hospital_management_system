<?php defined('BASEPATH') OR exit('No direct script access allowed');

class CI_Migration {

	protected $_migration_enabled = FALSE;
	protected $_migration_path = NULL;
	protected $_migration_version = 0;

	protected $_error_string = '';

	public function __construct($config = array())
	{
		
		if (get_parent_class($this) !== FALSE)
		{
			return;
		}

		foreach ($config as $key => $val)
		{
			$this->{'_' . $key} = $val;
		}

		log_message('debug', 'Migrations class initialized');

		
		if ($this->_migration_enabled !== TRUE)
		{
			show_error('Migrations has been loaded but is disabled or set up incorrectly.');
		}

		
		$this->_migration_path == '' AND $this->_migration_path = APPPATH . 'migrations/';

		
		$this->_migration_path = rtrim($this->_migration_path, '/').'/';

		
		$this->lang->load('migration');

		
		$this->load->dbforge();

		
		if ( ! $this->db->table_exists('migrations'))
		{
			$this->dbforge->add_field(array(
				'version' => array('type' => 'INT', 'constraint' => 3),
			));

			$this->dbforge->create_table('migrations', TRUE);

			$this->db->insert('migrations', array('version' => 0));
		}
	}

	
	public function version($target_version)
	{
		$start = $current_version = $this->_get_version();
		$stop = $target_version;

		if ($target_version > $current_version)
		{
			
			++$start;
			++$stop;
			$step = 1;
		}
		else
		{
			
			$step = -1;
		}

		$method = ($step === 1) ? 'up' : 'down';
		$migrations = array();

		
		for ($i = $start; $i != $stop; $i += $step)
		{
			$f = glob(sprintf($this->_migration_path . '%03d_*.php', $i));

			
			if (count($f) > 1)
			{
				$this->_error_string = sprintf($this->lang->line('migration_multiple_version'), $i);
				return FALSE;
			}

			
			if (count($f) == 0)
			{
				
				if ($step == 1)
				{
					break;
				}

				
				$this->_error_string = sprintf($this->lang->line('migration_not_found'), $i);
				return FALSE;
			}

			$file = basename($f[0]);
			$name = basename($f[0], '.php');

			
			if (preg_match('/^\d{3}_(\w+)$/', $name, $match))
			{
				$match[1] = strtolower($match[1]);

				
				if (in_array($match[1], $migrations))
				{
					$this->_error_string = sprintf($this->lang->line('migration_multiple_version'), $match[1]);
					return FALSE;
				}

				include $f[0];
				$class = 'Migration_' . ucfirst($match[1]);

				if ( ! class_exists($class))
				{
					$this->_error_string = sprintf($this->lang->line('migration_class_doesnt_exist'), $class);
					return FALSE;
				}

				if ( ! is_callable(array($class, $method)))
				{
					$this->_error_string = sprintf($this->lang->line('migration_missing_'.$method.'_method'), $class);
					return FALSE;
				}

				$migrations[] = $match[1];
			}
			else
			{
				$this->_error_string = sprintf($this->lang->line('migration_invalid_filename'), $file);
				return FALSE;
			}
		}

		log_message('debug', 'Current migration: ' . $current_version);

		$version = $i + ($step == 1 ? -1 : 0);

		
		if ($migrations === array())
		{
			return TRUE;
		}

		log_message('debug', 'Migrating from ' . $method . ' to version ' . $version);

		
		foreach ($migrations AS $migration)
		{
			
			$class = 'Migration_' . ucfirst(strtolower($migration));
			call_user_func(array(new $class, $method));

			$current_version += $step;
			$this->_update_version($current_version);
		}

		log_message('debug', 'Finished migrating to '.$current_version);

		return $current_version;
	}

	
	public function latest()
	{
		if ( ! $migrations = $this->find_migrations())
		{
			$this->_error_string = $this->line->lang('migration_none_found');
			return false;
		}

		$last_migration = basename(end($migrations));

		return $this->version((int) substr($last_migration, 0, 3));
	}

	
	public function current()
	{
		return $this->version($this->_migration_version);
	}

	
	public function error_string()
	{
		return $this->_error_string;
	}

	
	protected function find_migrations()
	{
		
		$files = glob($this->_migration_path . '*_*.php');
		$file_count = count($files);

		for ($i = 0; $i < $file_count; $i++)
		{
			
			$name = basename($files[$i], '.php');
			if ( ! preg_match('/^\d{3}_(\w+)$/', $name))
			{
				$files[$i] = FALSE;
			}
		}

		sort($files);
		return $files;
	}

	
	protected function _get_version()
	{
		$row = $this->db->get('migrations')->row();
		return $row ? $row->version : 0;
	}

	
	protected function _update_version($migrations)
	{
		return $this->db->update('migrations', array(
			'version' => $migrations
		));
	}

	public function __get($var)
	{
		return get_instance()->$var;
	}
}
