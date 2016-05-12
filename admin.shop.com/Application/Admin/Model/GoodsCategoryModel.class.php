<?php
namespace Admin\Model;

class GoodsCategoryModel extends \Think\Model{
    /**
     * TODO:自动验证
     * name不能为空
     */
    
    /**
     * 获取分类列表
     */
    public function getList(){
        return $this->where(['status'=>1])->order('lft')->select();
    }
    
    
    public function getPageResult(){
        $cond = ['status'=>1];
        $count = $this->where($cond)->count();
        $size = C('PAGE_SIZE');
        $page = new \Think\Page($count,$size);
        $page->setConfig('theme', C('PAGE_THEME'));
        $page_html = $page->show();
        $rows = $this->page(I('get.p'),$size)->where($cond)->order('lft')->select();
        return ['rows'=>$rows,'page_html'=>$page_html];
    }
    
    public function addCategory(){
        //实例化一个nestedsets所需要的数据库操作类的对象
//        $orm = new \Admin\Model\NestedSetsMysql();
        //使用D函数创建对象,第一个实参是模型类名,第二个是模型层标示(也就是模型文件夹),注意,模型文件根据规范都应当以层的标识为结束字符串
        $orm = D('NestedSetsMysql','Logic');
        //创建一个nestedsets对象
        $nestedsets = new \Admin\Service\NestedSets($orm, $this->trueTableName, 'lft', 'rght', 'parent_id', 'id', 'level');
        if(($cat_id = $nestedsets->insert($this->data['parent_id'], $this->data, 'bottom'))===false){
            $this->error = M()->getError();
            return false;
        }else{
            return $cat_id;
        }
    }
}
