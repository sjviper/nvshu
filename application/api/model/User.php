<?php


namespace app\api\model;


use think\Model;

class User extends Model
{
    public function login($data){
        $validate=new \app\api\validate\User();
        if($validate->check($data))
        {
            $result=$this->where($data)->find();
            if(empty($result))
                return 0;//账号密码错误
            else
            {
                return 1;//更新token 登录成功
            }
        }
        else{
            return -1;//账号密码不能为空
        }
    }
    public function makeToken()
    {

        $str = md5(uniqid(md5(microtime(true)), true)); //生成一个不会重复的字符串
        $str = sha1($str); //加密
        return $str;
    }

}