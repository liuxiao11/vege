<?php
namespace app\api\controller;

use think\Request;
use think\controller\Rest;


class System extends Rest
{
    public function rest()
    {
        switch ($this->method){
            case 'get':     //查询
                $this->Find($id);
                break;
            case 'post':    //新增
                $this->Edit();
                break;
        }
    }
    /*系统信息*/
    public function Find($id)
    {
        $data = Request::instance()->param();
        $find = findone('admin',[],'id,username,title,logo',['id'=>$data['id']]);
        if($find){
            echo json(200,$find);
        }else{
            echo json(202,'');
        }
    }
    /*修改系统信息*/
    public function Edit()
    {
        $data = Request::instance()->param();
        $file = upFile('logo');
        $data['logo'] = $file;
        $edit = edit('admin',['id'=>$data['id']],$data);
        if($edit){
            echo json(200,'');
        }else{
            echo json(202,'');
        }

    }


}
