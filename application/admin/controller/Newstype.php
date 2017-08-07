<?php
/**
 * 网站新闻类型
 * @author yupoxiong <i@yufuping.com>
 * @version 1.0
 */

namespace app\admin\controller;

use think\Db;
use app\common\model\NewsTypes;

class Newstype extends Base
{
    
    /**
     * 列表
     */
    public function index()
    {
        $list = NewsTypes::paginate(10);

        $this->assign([
            'list'     => $list,
            'page'     => $list->render(),
            'url'      => $this->do_url
        ]);

        return $this->fetch();
    }

    /**
     * 增加
     */
    public function add()
    {
        if($this->request->isPost()){

            $post           = $this->post;
            $result = $this->validate($post,[
                ['parent_id|上级分类','require|token'],
                ['title|分类标题','require|unique:news_types,title,'.$post['title']],
                ['description|分类描述','require'],
                ['sort_id|排序','require'],
            ]);

            if (true !== $result) {
                return $this->error($result);
            }

            $file = request()->file('img');
            if($file!=null){
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'news' . DS . 'newstype');
                if($info){
                    $save_name = $info->getSaveName();
                }else{
                    return $file->getError();
                }
            }else{
                $save_name = '';
            }


            $type_data = [
                'parent_id'   => $post['parent_id'],
                'title'       => $post['title'],
                'description' => $post['description'],
                'sort_id'     => $post['sort_id'],
            ];

            if($save_name!=''){
                $type_data['img'] = $save_name;
            }

            $insert = NewsTypes::create($type_data);

            if($insert){

                return $this->success('增加成功');
            }else{
                return $this->error('增加失败');
            }
        }

        $this->assign('parents', NewsTypes::select());
        return $this->fetch();
    }

    /**
     * 修改
     */
    public function edit()
    {
        $id = $this->id;
        $info = AdminUsers::get($id);
        if($this->request->isPost()){
            $post           = $this->post;

            //验证
            $result = $this->validate($post,[
                ['parent_id|角色','require'],
                ['user_name|用户名','require|token'],
                ['email|邮箱','require|email'],
                ['mobile|手机号','require'],
                ['status|状态','require'],
            ]);
            if (true !== $result) {
                return $this->error($result);
            }


            $file = request()->file('avatar');
            if($file!=null){
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'admin' . DS . 'avatar');
                if($info){
                    $save_name = $info->getSaveName();
                }else{
                    return $file->getError();
                }
            }else{
                $save_name = '';
            }
            if($save_name!=''){
                $post['avatar'] = $save_name;
            }

            $roles = $post['parent_id'];
            unset($post['parent_id']);
            unset($post['__token__']);
            unset($post['save']);


            $data = array(
                'user_name'    => $post['user_name'],
                'email'        => $post['email'],
                'mobile'       => $post['mobile'],
                'nick_name'    => $post['nick_name'],
                'status'       => $post['status'],
            );

            $password = $post['password'];
            if(!empty($password)){
                $data['password'] = md5($password);
            }

            if($info->save($data)){//修改

                $info->adminroles()->delete();
                $group = [];
                foreach ($roles as $key=>$value){
                    array_push($group,['uid'=>$this->web_data['user_info']['user_id'],'group_id'=>$value]);
                }

                $info->adminroles()->saveAll($group);
                return $this->success('修改成功');

            }else{
                return $this->error('修改失败');
            }
        }


        $roles = AuthGroup::where('status=1')->select();
        $user_roles = $info->adminroles()->column('group_id');

        foreach ($roles as $key=>$value){
            if(in_array($value['id'],$user_roles)){
                $value['checked'] = 'checked';
            }else{
                $value['checked'] = '';
            }
        }

        $this->assign('roles',$roles);
        $this->assign('info',$info);
        return $this->fetch();
    }

    /**
     * 删除
     */
    public function del()
    {

        $user = AdminUsers::get($this->id);
        $user_id = $this->id;
        if($user_id==1){
            return $this->error('超级管理员不能删除');
        }

        if($user->delete()){
            return $this->success('删除成功');
        }else{
            return $this->error('删除失败');
        }

    }
}
