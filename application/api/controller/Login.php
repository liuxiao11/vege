<?php
namespace app\api\controller;

use think\Request;
use think\controller\Rest;


class Login extends Rest
{
    public function rest()
    {
        switch ($this->method){
//            case 'get':     //查询
//                $this->regiset($name);
//                break;
            case 'post':    //登录和注册
                $this->index();
                break;
            case 'put':  //修改
                $this->recove($id);
                break;
        }
    }
    /*登录和注册*/
    public function index()
    {
        $data = Request::instance()->param();
        if(!empty($data['phone'])){
            $data['password'] = md5($data['password']);
            $data['last_time'] = date('Y-m-d:H:i:s');
            $insert = addId('admin',$data);
            if($insert){
                echo json(200,array("username"=>$data['username'],"id"=>$insert));
            }else{
                echo json(202,'');
            }
        }else{
            $find = findone('admin',[],'username,password,id',['username'=>$data['username'],'password'=>md5($data['password'])]);
            if($find){
                $date = date('Y-m-d:H:i:s');
                $edit = edit('admin',['id'=>$find['id']],['last_time'=>$date]);
                if($edit){
                    echo json(200,array("username"=>$find['username'],"id"=>$find['id']));
                }else{
                    echo json(202,'');
                }
            }else{
                echo json(202,'');
            }
        }
    }
//    /*注册*/
//    public function regiset($name)
//    {
//        $data = json_decode(Request::instance()->param()['name'],true);
//
//    }
    /*忘记密码*/
    public function recove($id)
    {
        $data = json_decode(Request::instance()->param()['id'],true);
        $find = findone('admin',[],'username,password,id',['phone'=>$data['phone']]);
        if($find){
            $edit = edit('admin',['id'=>$find['id']],['password'=>md5($data['password'])]);
            if($edit){
                echo json(200,$find['username']);
            }else{
                echo json(202,'');
            }
        }else{
            echo json(404,'');
        }
    }
}
