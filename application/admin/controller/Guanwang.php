<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\model\Guanwangs;
use app\admin\model\Moban;
use app\admin\model\Daohang;
use app\admin\model\Image;

class Guanwang extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {   
        $param = $this->request->param();
        // var_dump($param);
        // 判断是否存在搜索关键词
        if (isset($param['tag'])) {
           $moban = model('Guanwangs')
                    ->join('Moban','guanwang.style=moban.moban_id')
                    ->where('tag','like','%'.$param['tag'].'%')->paginate(3); 
           $page = $moban->render();
        }else{
            $moban = model('Guanwangs')
                    ->join('Moban','guanwang.style=moban.moban_id')
                    ->paginate(3);
            $page = $moban->render();
        }

        return $this->fetch('guanwang/list',['moban'=>$moban,'page'=>$page]);
    }

    // 编辑页面
    public function edit($id)
    {
        // var_dump($id);
        $moban = model('Guanwangs')->find($id);
        // 获取导航内容
        $daohang = model('Daohang')->select();
        // 将对象转换成数组
        $daohangs = (json_decode($daohang,true));

        // 获取幻灯片内容
        $image = model('Image')->select();
        $images = (json_decode($image,true));

        return $this->fetch('guanwang/edit',['moban'=>$moban,'daohangs'=>$daohangs,'images'=>$images]);
    }
    // 执行修改页面
    public function doedit(Request $request)
    {
        if($this->request->isPost()){
            $input = $this->request->param();
            $id=$input['id'];
            // var_dump($id);
            // var_dump($input);
            $res = model('Guanwangs')->where('id',$id)->update(['name'=>$input['name'],'tag'=>$input['tag'],'status'=>$input['status'],'thumb'=>$input['thumb'],'description'=>$input['description'],'yuming'=>$input['yuming'],'url'=>$input['url'],'footer'=>$input['footer']]);

            if($res){
                return redirect('/admin/Guanwang/index');
            }else{

            }
        }
       
    }
    // 模板添加页面
    public function add()
    {
        // 获取模板分类
        $fenlei = model('Moban')->select();

        // 获取导航内容
        $daohang = model('Daohang')->select();
        // 将对象转换成数组
        $daohangs = (json_decode($daohang,true));
        // var_dump($daohangs);

        // 获取幻灯片内容
        $image = model('Image')->select();
        $images = (json_decode($image,true));
        // var_dump($image);
        return $this->fetch('guanwang/add',['fenlei'=>$fenlei,'daohangs'=>$daohangs,'images'=>$images]);
    }
    // 执行模板添加
    public function doadd(Request $request)
    {
        if($this->request->isPost()){
            // 获取添加数据
            $input = $this->request->param();
            $copys['addtime'] = time();
            // var_dump($input);
            $res = model('Guanwangs')->save($input);

           if($res){
               return redirect('/admin/Guanwang/index');
            }else{
               
            }
        }
        
    }
    // 封面上传
    public function upload()
    {
        $file = request()->file('photo');
        // var_dump($file->getInfo('name'));
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
    // 站点复制
    public function copy($id)
    {
        $copy = model('Guanwangs')->find($id);
        // 将对象转换成数组
        $copys = json_decode($copy,true);
        $copys['addtime'] = time();
            // var_dump($copys);
        $copys = array_slice($copys,1);
        // var_dump($copys);
        // 复制站点信息
        $res = model('Guanwangs')->save(['name'=>$copys['name'],'style'=>$copys['style'],'status'=>$copys['status'],'tag'=>$copys['tag'],'thumb'=>$copys['thumb'],'description'=>$copys['description'],'yuming'=>$copys['yuming'],'url'=>$copys['url'],'footer'=>$copys['footer'],'addtime'=>$copys['addtime'],'menu'=>$copys['menu']]);

        if($res){
           return redirect('/admin/Guanwang/index');
        }else{
           return redirect('/admin/Guanwang/index');
        }
    }
    // 添加导航页面
    public function daohang()
    {
        return $this->fetch('guanwang/daohang');
    }
    // 执行添加导航
    public function dodaohang(Request $request)
    {
        if($this->request->isPost()){
            $input = $this->request->param();

            // var_dump($input);

            $res = model('Daohang')->save($input);
        
            if($res){
                return redirect('/admin/Guanwang/add');
            }else{

            }
        }
        
    }
    // 删除站点
    public function del($id)
    {
        // var_dump($id);
        $res = model('Guanwangs')->where('id',$id)->delete();

        if($res){
           return redirect('/admin/Guanwang/index');
        }else{
           return redirect('/admin/Guanwang/index');
        }
    }
    // 执行导航编辑页面
    public function dhedit($id)
    {
        // var_dump($id);
        $daohang = model('Daohang')->find($id);
        // var_dump($daohang);

        return $this->fetch('guanwang/dhedit',['daohang'=>$daohang]);
    }
    // 指向导航更改
    public function dodh($id)
    {
        if($this->request->isPost()){
            $input = $this->request->param();
            // var_dump($input);
            $res = model('Daohang')->where('dh_id',$id)->update(['dh_place'=>$input['dh_place'],'dh_name'=>$input['dh_name'],'dh_description'=>$input['dh_description'],'dh_url'=>$input['dh_url'],'dh_state'=>$input['dh_state'],'dh_sort'=>$input['dh_sort'],'dh_thumb'=>$input['dh_thumb']]);
            if($res){
               return redirect('/admin/Guanwang/add');
            }else{
              
            }
        }
    }
    // 导航删除
    public function dhdel($id)
    {
       $res = model('Daohang')->where('dh_id',$id)->delete($id);

       if($res){
           return redirect('/admin/Guanwang/add');
        }else{
          
        }
    }
    // 幻灯片-添加图片页面
    public function image()
    {
        return $this->fetch('guanwang/image');
    }
    // 执行图片添加
    public function doimage()
    {
        $input = $this->request->param();

        // var_dump($input);
        $res = model('Image')->save($input);

        if($res){
            return redirect('/admin/Guanwang/add');
        }else{

        }
    }
    // 换灯片-图片删除
    public function imgdel($id)
    {   
        // var_dump($id);
        $res = model('Image')->where('img_id',$id)->delete();

        if($res){
            return redirect('/admin/Guanwang/add');
        }else{

        }
    }
}
