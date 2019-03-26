<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\model\Categorys;


class Cate extends Controller
{
    // 文章分类管理
    public function cate(Request $request)
    {
        // 获取分类   
        $cate = model('Categorys')->where('cate_pid','0')->order('cate_sort desc')->paginate(5);
        // 获取分类数量
        $num = model('Categorys')->where('cate_pid','0')->count();
        $page = $cate->render();
        return $this->fetch('cate/list',['cate'=>$cate,'page'=>$page,'num'=>$num]);
    }

    // 添加分类页面
    public function add()
    {
        // 获取分类   
        $cate = model('Categorys')->where('cate_pid','0')->select();

        return $this->fetch('cate/add',['cate'=>$cate]);
    }
    // 执行添加操作
    public function doadd(Request $request)
    {
        if($this->request->isPost()){
            $input = $this->request->param();

            // var_dump($input);
            $res = model('Categorys')->save($input);

            if($res){
                $data=[
                    'status'=>0,
                    'msg'=>'分类添加成功'
                ];
            }else{
                $data=[
                    'status'=>1,
                    'msg'=>'分类添加失败'
                ];
            }
            return json($data);
        }
        
    }
    // 更改分类页面
    public function edit($id)
    {
        $cate = model('Categorys')->find($id);
        return $this->fetch('cate/edit',['cate'=>$cate]);
    }
    // 执行更改操作
    public function doedit(Request $request)
    {
        if($this->request->isPost()){
            // 获取更改信息
            $input = $this->request->param();
            $id=$input['uid'];
           
            $res = model('Categorys')->where('cate_id',$id)->update(['cate_name'=>$input['cate_name'],'cate_title'=>$input['cate_title']]);

            if($res){
                $data=[
                    'status'=>0,
                    'msg'=>'分类修改成功'
                ];
            }else{
                $data=[
                    'status'=>1,
                    'msg'=>'分类修改失败'
                ];
            }
            return json($data);
        }
        
    }
    // 删除
    public function del($id)
    {
        $res = model('Categorys')->where('cate_id',$id)->delete();

        if($res){
            $data=[
                'status'=>0,
                'message'=>'删除成功'
            ];
        }else{
            $data=[
                'status'=>1,
                'message'=>'删除成功'
            ];
        }

        return $data;
    }
}
