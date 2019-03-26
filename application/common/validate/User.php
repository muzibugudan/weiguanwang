<?php
// 用户表验证器

namespace app\common\validate;
use think\Validate;

class User extends Validate
{
	protected $rule = [
		'name|姓名'=>[
			'require'=>'require',
			'length'=>'5,20',
			'chsAlphaNum'=>'chsAlphaNum', //仅允许汉字数字和字母
		],
		'email|邮箱'=>[
			'require'=>'require',
			'email'=>'email',
			'unique'=>'shop_user', //该字段必须在shop_user中唯一
		],
		'mobile|手机号'=>[
			'require'=>'require',
			'mobile'=>'mobile',
			'number'=>'number',
			'unique'=>'shop_user', //该字段必须在shop_user中唯一
		],
		'password|密码'=>[
			'require'=>'require',
			'length'=>'6,20',
			'alphaNum'=>'alphaNum', //仅允许数字和字母
		]
	],
}