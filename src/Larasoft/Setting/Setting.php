<?php 

/*
 * This file is part of the Larasoft package.
 *
 * (c) Rok Grabnar <rokgrabnar@hotmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Larasoft\Setting;

class Setting {
    
    /**
	 * The database table used by the model.
	 *
	 * @var Larasoft\Setting\SettingModel
	 */
	protected $model;

	protected $cache;

	/**
	 * Storage for setting values that are
	 * retrieved from cache or database
	 *
	 * @var array
	 */
	protected $values = array();

	public function __construct($model, $cache)
	{
		$this->model = $model;

		$this->cache = $cache;
	}

	/**
     * Set a single value or multiple values through array
     *
     * @param  mixed  $key
     * @param  mixed  $value
     * @return Larasoft\Setting\Setting
     */
	public function set($key, $value = null)
	{
		if(is_array($key))
		{
			foreach ($key as $keySingle => $value) 
			{
				$this->setValue($keySingle, $value);
			}
		}
		else
		{
			$this->setValue($key, $value);
		}

		// clear cached setting values so cache can
		// be rebuilded with new values
		$this->flushCache();

		return $this;
	}

	/**
     * Clear\Remove cached values
     *
     * @return Larasoft\Setting\Setting
     */
	public function flushCache()
	{
		$this->cache->forget('setting');

		return $this;
	}

	public function clear($key)
	{
		$query = $this->model->query()->where('key', $key)->first();

		if($query)
		{
				$query->delete();
		}

		$this->flushCache();

		return $this;
	}

	protected function setValue($key, $value)
	{
		$query = $this->model->query()->where('key', $key)->first();

		$serialized = false;

		// If value is array then serialize it so we can save it to database
		if(is_array($value))
		{
			$value = serialize($value);

			$serialized = true;
		}

		// If key exists in database then just update it's value and if don't
		// exists then just insert a new record into a database with new key and value
		if($query)
		{
			if($serialized)
			{
				$query->serialized = 1;
			}

			$query->value = $value;

			$query->save();
		}
		else
		{
			$model = $this->model;

			if($serialized)
			{
				$model->serialized = 1;
			}

			$model->key = $key;

			$model->value = $value;

			$model->save();
		}
	}

	/**
     * Get setting value
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
	public function get($key, $default = null)
	{
		if(!$this->values)
		{
			$this->loadValuesFromDatabase();
		}

		if(isset($this->values[$key]))
		{
			if($this->values[$key]['serialized'])
			{
				return unserialize($this->values[$key]['value']);
			}
			else
			{
				return $this->values[$key]['value'];
			}
		}
		else
		{
			return $default;
		}
	}

	protected function loadValues()
	{
		
	}

	protected function loadValuesFromCache()
	{

	}

	/**
     * Load setting values from database
     *
     */
	protected function loadValuesFromDatabase()
	{
		// if setting values are in cache then retrieve them,
		// otherwise retrieve them from database table
		if($this->cache->has('setting'))
		{
			$settingValues = $this->cache->get('setting');
			
			$this->values = array_merge($this->values, $settingValues);
		}
		else
		{
			$query = $this->model->query();
			
			foreach($query->get() as $resultRow)
			{
				$this->values[$resultRow->key] = array('value' => $resultRow->value, 'serialized' => $resultRow->serialized);
			}
			
			$this->cache->forever('setting', $this->values);
		}
	}
}
