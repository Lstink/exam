<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\models\Category;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * @content 商品分类的接口
     */
    public function getCategory(Request $request)
    {
        $tt = $request -> tt;
        $account_id = $request -> account_id;
        $category_type = $request -> category_type??0;
        $is_show_level = $request -> is_show_level??0;
        $vc = $request -> vc??null;
        //验证必填项
        if (empty($tt) || empty($account_id)) {
            return response() -> json(['status'=>407,'msg'=>'缺少参数']);
        }
        //查询所有分类
        $res = Category::select('cate_id','cate_name','parent_id')->get()->toArray();
        $data = $this -> getCategoryByType($category_type,$res,$is_show_level);
        return response() -> json(['status'=>0,'msg'=>'获取分类数据成功','data'=>$data]);

    }
    /**
     * @content 根据category_type获取不同的层级
     */
    private function getCategoryByType($type,$data,$is_show_level=0)
    {
        $res = [];
        switch ($type) {
            case '0':
                return $data;
                break;
            case '1':
                foreach ($data as $k => $v) {
                    if ($v['parent_id'] == 0) {
                       $res[] = $v;
                    }
                }
                return $res;
                break;
            case '2':
                if ($is_show_level==1) {
                    //查询所有的一级分类的id
                    $res = $this -> getNextCategoryById(0,$data);
                    //查询二级分类的商品
                    foreach ($res as $k => $v) {
                        foreach ($data as $key => $val) {
                            if ($v['cate_id'] == $val['parent_id']) {
                                $res[$k]['data'][] = $val;
                            }
                        }
                    }
                }else{
                    //查询所有的一级分类的id
                    $ids = $this -> getNextCategoryById(0,$data,true);
                    //查询二级分类的商品
                    foreach ($ids as $k => $v) {
                        $res = array_merge($res,$this -> getNextCategoryById($v,$data));
                    }
                }
                return $res;
                break;
            case '3':
                if ($is_show_level == 1) {
                    
                    return $this -> getTree($data);
                }else{
                    //查询所有的一级分类的id
                    $ids = $this -> getNextCategoryById(0,$data,true);
                    //根据一级分类的id查询所有的二级分类id
                    $arr = [];
                    foreach ($ids as $k => $v) {
                        $arr = array_merge($arr,$this -> getNextCategoryById($v,$data,true));
                    }
                    //根据二级分类查询所有的三级分类
                    foreach ($arr as $val) {
                        $res = array_merge($res,$this -> getNextCategoryById($val,$data));
                    }
                }
                return $res;
                break;
            
            default:
                //查询所有的一级分类的id
                $ids = $this -> getNextCategoryById(0,$data,true);
                //查询二级分类的商品
                foreach ($ids as $k => $v) {
                    $res[] = $this -> getNextCategoryById($v,$data);
                }
                return $res;
                break;
        }
    }
    /**
     * @content 根据id查询下一级分类
     * @param $id 上一级的id
     * @param $data 要查询的数据
     * @flag true 查询出下一级分类的所有id false 查询下一级分类的所有数据
     */
    private function getNextCategoryById($id,$data,$flag=false)
    {
        $arr = [];
        foreach ($data as $k => $v) {
            if ($v['parent_id'] == $id) {
                if ($flag) {
                    $arr[] = $v['cate_id'];
                }else{
                    $arr[] = $v;
                }
            }
        }
        return $arr;
    }
    /**
     * 无限极分类树 getTree($categories)
     * @param array $data
     * @param int $parent_id
     * @param int $level
     * @return array
     */
    public function getTree($data = [], $parent_id = 0, $level = 1)
    {
        $tree = [];
        if ($data && is_array($data)) {
            foreach ($data as $v) {
                if ($v['parent_id'] == $parent_id) {
                    $tree[] = [
                        'category_level' => $level,
                        'cate_name' => $v['cate_name'],
                        'category_id' => $v['cate_id'],
                        'data' => $this -> getTree($data, $v['cate_id'], $level + 1),
                    ];
                }
            }
        }
        return $tree;
    }
}
