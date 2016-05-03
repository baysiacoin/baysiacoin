<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'roles';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['id', 'name'];
	
	
	
	/**
	 * roleByName
	 * 
	 */
	public static function roleByName($name)
	{
		$id = self::where('name', '=', $name)->pluck('id');
		return $id;
	}
	
	/**
	 * allowedRolesByNames
	 * 
	 */
	public static function allowedRolesByNames($names)
	{
		$allowNames = array();
		if (in_array(ROLE_ADMIN, $names)) {
			$allowNames = array(ROLE_ADMIN, ROLE_ADMIN2, ROLE_BRANCH1, ROLE_BRANCH2);
		} else if (in_array(ROLE_BRANCH1, $names)) {
			$allowNames = array(ROLE_BRANCH1, ROLE_BRANCH2);
		}
		return $allowNames;
	}
	
	/**
	 * rolesByNames
	 * 
	 */
	public static function rolesByNames($names)
	{
		if (empty($names)) {
			return array();
		}
		return self::whereIn('name', $names)->orderBy('name')->get();
	}
}
