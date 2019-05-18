<?php


namespace app\api\validate;


use think\Validate;

class User extends Validate
{
    protected $rule=[
            'username'=>'require',
            'password'=>'require'
        ];
}