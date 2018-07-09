<?php
namespace app\api\controller;

use think\Request;
use think\controller\Rest;


class Order extends Rest
{
    public function rest()
    {
        switch ($this->method){
            case 'get':     //查询
                $this->OrderSelect($id);
                break;
            case 'post':    //新增
                $this->OrderAdd();
                break;
            case 'put':  //修改
                $this->OrderEdit($id);
                break;
            case 'delete':  //修改
                $this->OrderDelete($id);
                break;
        }
    }
    /*所有蔬菜名称*/
    public function VegeName(){
        $select = findMore('vegetable',[],' vege_name,vege_id,vege_price ','','','');
        if($select){
            echo json(200,$select);
        }else{
            echo json(202,'');
        }
    }
    /*所有商户名称*/
    public function UserName()
    {
        $select = findMore('user',[],'user_name,user_id ','','','');
        if($select){
            echo json(200,$select);
        }else{
            echo json(202,'');
        }
    }
    /*订单明细*/
    public function Desc($id){
        $id = json_decode(Request::instance()->param()['id'],true);
        $join = [
            ['vegetable v','order.v_id = v.vege_id'],
        ];
        $select = findMore('order',$join,'v.vege_id, v.vege_name,v.vege_spec,v.vege_unit,a.vege_price,a.sum_price',['order_adminid'=>$id['admin_id'],'a.order_id'=>$id['order_id']],'a.order_time desc');
        if($select){
            echo json(200,$select);
        }else{
            echo json(202,'');
        }

    }
    /*订单查询*/
    public function OrderSelect($id)
    {
        $id = Request::instance()->param();
        $join = [
            ['user u','order.s_id = u.user_id'],
        ];
        $select = findMore('order',$join,'a.order_id, u.user_name,u.user_contacts,u.user_phone,a.order_number,a.sum_price,a.is_pay,a.order_time',['order_adminid'=>$id['id']],'a.order_time desc');
        if($select){
            echo json(200,$select);
        }else{
            echo json(202,'');
        }
    }
    /*订单增加*/
    public function OrderAdd()
    {
        $data = Request::instance()->param();
        $data['order_year'] = substr($data['order_time'],0,4);
        $data['order_month'] = substr($data['order_time'],5,2);
        $data['order_date'] = substr($data['order_time'],8,2);
        $data['order_insert_time'] = date('Y-m-d:H:i:s');
        $data['order_number'] = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        $insert = addData('order',$data);
        if($insert){
            echo json(200,'');
        }else{
            echo json(202,'');
        }
    }
    /*订单修改*/
    public function OrderEdit($id)
    {
        $data = json_decode(Request::instance()->param()['id'],true);
        $edit = edit('order',['order_id'=>$data['order_id']],$data);
        if($edit){
            echo json(200,'');
        }else{
            echo json(202,'');
        }
    }
    /*订单删除*/
    public function OrderDelete($id){
        $id = Request::instance()->param();
        $delete = del('order',['order_id'=>$id['id']]);
        if($delete){
            echo json(200,'');
        }else{
            echo json(202,'');
        }
    }

}
