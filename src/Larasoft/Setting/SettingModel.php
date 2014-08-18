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

use Illuminate\Database\Eloquent\Model;

class SettingModel extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'settings';

}
?>