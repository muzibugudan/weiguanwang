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
        // 添加默認搜索屬性
        $where[]=['acid','=',19];
        // 添加默認支付
        $where[]=['status','=',1];
        // 起始时间搜索
        if(isset($param['start_time']) && !empty($param['start_time'])){
            $times = strtotime($param['start_time']);
            // var_dump($times);
            $where[] =['create_time','>',$times];
            $this->assign('start_time',$times);
            $this->assign('start_times',$param['start_time']);
        }else{
            $param['start_time']= '';
            $this->assign('start_time',$param['start_time']);
        } 
        // 結束時間搜索
        if(isset($param['end_time']) && !empty($param['end_time'])){
            $times = strtotime($param['end_time']);
            // var_dump($times);
            $where[] =['create_time','<',$times];
            $this->assign('end_time',$times);
            $this->assign('end_times',$param['end_time']);
        }else{
            $param['end_time']= '';
            $this->assign('end_time',$param['end_time']);
        } 
        // 起始和結束時間搜索
        if(isset($param['start_time'])&&isset($param['end_time'])){
            $start_time = strtotime($param['start_time']);
            $end_time = strtotime($param['end_time']);
            $where[] =['create_time','>',$start_time AND 'create_time','<',$start_time];
        }else{

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
        // 微信訂單搜索
        if(isset($param['transaction_id']) && !empty($param['transaction_id'])){
             // var_dump($param['out_trade_no']);
            $where[] = ['transaction_id','like',"%".$param['transaction_id']."%"];
            $this->assign('transaction_id',$param['transaction_id']);
        }else{
            $param['transaction_id']= '';
            $this->assign('transaction_id',$param['transaction_id']);
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
        // 添加默認搜索屬性
        $where[]=['acid','=',19];
        // 添加默認支付
        $where[]=['status','=',1];
        // // 起始时间
        if(isset($param['start_time']) && !empty($param['start_time'])){
            // $times = strtotime($param['create_time']);
            $where[] =['create_time','>',$param['start_time']];
        }
        // 结束时间
        if(isset($param['end_time']) && !empty($param['end_time'])){
            // $times = strtotime($param['create_time']);
            $where[] =['create_time','<',$param['end_time']];
        }
        if(isset($param['start_time']) && isset($param['end_time'])){
            $start_time = strtotime($param['start_time']);
            $end_time = strtotime($param['end_time']);
            $where[] =['create_time','>',$start_time AND 'create_time','<',$end_time];
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
        // 微信訂單搜索
        if(isset($param['transaction_id']) && !empty($param['transaction_id'])){
             // var_dump($param['out_trade_no']);
            $where[] = ['transaction_id','like',"%".$param['transaction_id']."%"];
        }else{

        }
        // 获取停车信息
        $park = model('Querys')->where($where)->field('id,plate_number,create_time,update_time,total_fee,deductionAmount,status,out_trade_no')->select();
        // var_dump($park);
        return $this->fetch('query/daochu',['park'=>$park]);
       
    }

}
