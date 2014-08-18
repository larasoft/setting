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

use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('larasoft/setting');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['setting'] = $this->app->share(function($app)
		{
			if($app['config']->get('setting::cache'))
			{
				$cache = $app['cache'];
			}
			else
			{
				$cache = false;
			}

			return new Setting(new SettingModel, $cache);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}