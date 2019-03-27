<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\model\Querys;


class Query extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {  
        $param = $this->request->param();

        $where = [];
        // 起始时间
        if(isset($param['create_time']) && !empty($param['create_time'])){
            $times = strtotime($param['create_time']);
            // var_dump($times);
            $where[] =['create_time','>',$times];
            $this->assign('create_time',$times);
        }else{
            $param['create_time']= '';
            $this->assign('create_time',$param['create_time']);
        } 

        
        // 车牌搜索
        if(isset($param['plate_number']) && !empty($param['plate_number'])){
             // var_dump($param['plate_number']);
            $where[] = ['plate_number','like', "%".$param['plate_number']."%"];
            $this->assign('plate_number',$param['plate_number']);
        }else{
            $param['plate_number']= '';
            $this->assign('plate_number',$param['plate_number']);
        } 
        // 订单号搜索
        if(isset($param['out_trade_no']) && !empty($param['out_trade_no'])){
             // var_dump($param['out_trade_no']);
            $where[] = ['out_trade_no','like',"%".$param['out_trade_no']."%"];
            $this->assign('out_trade_no',$param['out_trade_no']);
        }else{
            $param['out_trade_no']= '';
            $this->assign('out_trade_no',$param['out_trade_no']);
        } 
        // 获取停车信息
        $park = model('Querys')
                ->where($where) 
                ->paginate(10, false, ['query' => request()->param()]);

        // var_dump($park);
        // 实现分页
        $page = $park->render();
        // 统计数量
        $num = model('Querys')->where($where)->count();


        return $this->fetch('query/list',['park'=>$park,'page'=>$page,'num'=>$num]);
    }
    // 实现搜索资源Excel下载
    public function daochu()
    {
        Header( "Content-type: application/octet-stream "); 
        Header( "Accept-Ranges: bytes "); 
        Header( "Content-type:application/vnd.ms-excel ;charset=utf-8");//自己写编码 
        Header( "Content-Disposition:attachment;filename=车牌查询日志.xls "); //名字
        $param = $this->request->param();
        $where = [];
        // // 起始时间
        if(isset($param['create_time']) && !empty($param['create_time'])){
            // $times = strtotime($param['create_time']);
            $where[] =['create_time','>',$param['create_time']];
        }
        // 车牌搜索
        if(isset($param['plate_number']) && !empty($param['plate_number'])){
            $where[] = ['plate_number','like', "%".$param['plate_number']."%"];
        }else{

        }
        // 订单号搜索
        if(isset($param['out_trade_no']) && !empty($param['out_trade_no'])){
            $where[] = ['out_trade_no','like',"%".$param['out_trade_no']."%"];
        }else{

        }
        // 获取停车信息
        $park = model('Querys')->where($where)->field('id,plate_number,create_time,out_trade_no')->select();
        return $this->fetch('query/daochu',['park'=>$park]);
       
    }

}
