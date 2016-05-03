<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class RegisterPostRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$birthdayBefore = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d"), date("Y") - 18));
		$telnum_rule = '/^\d{3}-\d{4}-\d{4}|\d{2}-\d{4}-\d{4}|\d{4}-\d{2}-\d{4}|d{3}-\d{3}-\d{4}|d{5}-\d{1}-\d{4}$/';
		
		return [
			'firstname' => 'required',
			'lastname' => 'required',
			'firstname1' => 'required',
			'lastname1' => 'required',
			'birthday' => 'required|before:' . $birthdayBefore,
			'email' => 'required|email|confirmed|unique:users',
			'password' => 'required|confirmed|min:8',
			'telnum' => ['required', 'regex:' . REGEX_TELNUM],
			'zipcode' => 'required|regex:' . REGEX_ZIPCODE,
			'state' => 'required',
			'city' => 'required',
			'address' => 'required',
			'bankname' => 'required',
			'branchname' => 'required',
			'accounttype' => 'required',
			'accountnumber' => 'required|max:9999999|regex:' . REGEX_ACCOUNTNUMBER,
			'accountname' => 'required',
			'kiyaku' => 'accepted',
		];
	}

}
