<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\model\Articles;
use app\admin\model\Categorys;

class Article extends Controller
{
    
    // 文章管理
    public function article()
    {
        // 获取文章
        $param = $this->request->param();

        $where=[];

        if (isset($param['cate_id'])) {

            $where['cate_id'] = $param['cate_id'];
        }

        $art = model('Articles')->where($where)->order('art_id asc')->paginate(10);

        // 实现分页
        $page = $art->render();

        // 获取文章总数
        $num = model('Articles')->where($where)->count();
        // 获取一级分类
        $cate_one = model('Categorys')->where('cate_pid','0')->select();
        
       return $this->fetch('article/list',['art'=>$art,'num'=>$num,'cate_one'=>$cate_one,'page'=>$page]);
    }
    

    /**
     * 文章添加页面
     *
     * @return \think\Response
     */
    public function create()
    {
         // 获取一级分类
        $cate_one = model('Categorys')->where('cate_pid','0')->select();

        return $this->fetch('article/add',['cate_one'=>$cate_one]);
    }

    /**
     * 执行文章添加
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {

        if($this->request->isPost()){

            $input = $this->request->param();
            // var_dump($input);
            //添加操作时间
            $input['art_time'] = time();
            // 执行文章起始浏览量
            $input['art_view'] = 0;
            $input['art_status'] = 0;

            // 数据保存
            $res = model('Articles')->save($input);

            if($res){
                return redirect('/admin/Article/article');
            }else{
                
            }
        }
        
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
     * 修改文章页面
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
         // 获取一级分类
        $cate_one = model('Categorys')->where('cate_pid','0')->select();

        //获取对应id文章内容
        $arts=model('Articles')->find($id);

        return $this->fetch('article/edit',['arts'=>$arts,'cate_one'=>$cate_one]);
        // return view('article/edit');
    }

    /**
     * 执行文章更新
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request)
    {
        if($this->request->isPost()){
            $input = $this->request->param();
            // var_dump($input);
            $id = $input['art_id'];
            $res = model('Articles')->where('art_id',$id)->update(['art_url'=>$input['art_url'],'art_sort'=>$input['art_sort'],'art_title'=>$input['art_title'],'shop'=>$input['shop'],'art_tag'=>$input['art_tag'],'art_source'=>$input['art_source'],'art_editor'=>$input['art_editor'],'art_thumb'=>$input['art_thumb'],'art_description'=>$input['art_description'],'art_content'=>$input['art_content']]);
            
            if($res){
                $data=[
                    'status'=>0,
                    'msg'=>'成功'
                ];
            }else{
                $data=[
                    'status'=>1,
                    'msg'=>'失败'
                ];
            }
            return json($data);
        }
        
    }

    // 删除文章
    public function del($id)
    {
        // 删除指定id
        $res=model('Articles')->where('art_id',$id)->delete();

        // if($res){
        //      $this.parents("tr").remove();
        // }else{
        //     return back();
        // }
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
    // 批量删除文章
    public function delAll($ids)
    {
        // var_dump($ids);

        foreach($ids as $k=>$v){
            $res = model('Articles')->where('art_id',$v)->delete();
        }

        if($res){
            $data=[
                'status'=>0,
                'message'=>'文章删除成功',
            ];
        }else{
            $data=[
                'status'=>1,
                'message'=>'文章删除失败',
            ];
        }
        return $data;
    }
    // 图片上传
    public function upload()
    {
        // 获取上传文件
        $file = request()->file('photo');
        // 文件移动
        $info=$file->move('./static/uploads','');
        // 判断文件是否上传成功
        if(!$info)
        {
        return json(['ServerNo'=>'400','ResultData'=>'文件保存失败']);
        }
        // 成功给前台返回上传结果
        return json(['ServerNo'=>'200','ResultData'=>$file->getInfo('name')]);
    }
}
