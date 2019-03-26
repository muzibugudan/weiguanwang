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
        // var_dump($param);
        $where = [];
        // 起始时间
        if(isset($param['create_time']) && !empty($param['create_time'])){
            $times = strtotime($param['create_time']);
            // var_dump($times);
            $where[] =['create_time','>',$times];
        } 
       
        // 车牌搜索
        if(isset($param['plate_number']) && !empty($param['plate_number'])){
             // var_dump($param['plate_number']);
            $where[] = ['plate_number','like', "%".$param['plate_number']."%"];
        }
        // 订单号搜索
        if(isset($param['out_trade_no']) && !empty($param['out_trade_no'])){
             // var_dump($param['out_trade_no']);
            $where[] = ['out_trade_no','like',"%".$param['out_trade_no']."%"];
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
        // var_dump($parks);
        return $this->fetch('query/list',['park'=>$park,'page'=>$page,'num'=>$num]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
