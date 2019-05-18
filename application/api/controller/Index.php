<?php
/*
 * 女书API
 * 2019-5-18
 * **/

namespace app\api\controller;


use app\api\validate\User;
use think\Controller;
use think\Cookie;
use think\Request;

class Index extends Controller
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[  版本由 <a href="http://nvshu.d80.top" target="qiniu">xcg</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="ad_bd568ce7058a1091"></think>';
    }
    public function Login(){//用户登录

        $req=Request::instance();
        if($req->isPost())
        {
            $token=$req->param('token');
            if(!empty($token))//如果有token直接使用token登录
            {
                $user=new \app\api\model\User();
                $result=$user->get(['token'=>$token]);
                if(empty($result))
                    return json(['msg'=>'无效token!']);
                $time=$result['lifetime'];
                if( $time-time()>0)//token没有过期
                {
                    $result['lifetime']=strtotime("+7 days");//更新token生命周期+7天
                    $result['token']=$user->makeToken();
                    if($result->save())
                        return json(['token'=>$result['token'],'status'=>1,'msg'=>'登陆成功']);
                    else
                        return json(['msg'=>'操作异常','status'=>-2]);
                }
                else
                    return json(['msg'=>'token已过期请重新登录!']);

            }
            else
            {
                $data=[

                    'username'=>$req->param('username'),
                    'password'=>$req->param('password')
                ];
                $user=new \app\api\model\User();
                $result=$user->login($data);
                if($result==1)
                {
                    $result1=$user->get($data);
                    $result1['token']=$user->makeToken();
                    $result1['lifetime']=strtotime("+7 days");
                    if($result1->save())
                    {
                        Cookie::set('token',$result1['token']);
                        return json(['token'=>$result1['token'],'status'=>1,'msg'=>'登陆成功']);
                    }
                    else
                        return json(['msg'=>'操作异常','status'=>-2]);

                }
                else
                    if ($result==0)
                        return json(['msg'=>'账号密码错误!','status'=>0]);
                    else
                        if($result==-1)
                            return json(['msg'=>'账号密码不能为空!','status'=>-1]);
            }
        }
        else
        {
            return json_encode(['msg'=>'非正常模式访问','status'=>-1]);
        }

    }
    public function Reg(){
        $req=Request::instance();
        $user=new \app\api\model\User();
        $data=[
            'username'=>'qqq',
            'password'=>'qqq',
            'lifetime'=>strtotime("+7 days"),
            'token'=>$user->makeToken()
        ];
        try{
            if($user->save($data))
                return json(['token'=>$data['token']]);
            else
                return json(['msg'=>'注册失败！','status'=>0]);
        }catch (\Exception $e)
        {
            return json(['msg'=>'账号重复注册！','status'=>0]);
        }


    }
    public function Check(){
        $user=new \app\api\model\User();
        $result=$user->get(['token'=>'5fac3d5a81b34676503281a6d43be4ccaf2c74aa']);
        $result['token']=$user->makeToken();
        if($result->save())
            return dump($result);

    }

}