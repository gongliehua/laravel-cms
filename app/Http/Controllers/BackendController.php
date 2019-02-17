<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BackendController extends Controller
{
    //无限极分类
    public function _sort($data, $pid = 0, $level = 0)
    {
        static $result = [];
        foreach ($data as $key=>$value) {
            if ($value['parent_id'] == $pid) {
                $value['level'] = $level;
                $result[] = $value;
                $this->_sort($data, $value['id'], $level+1);
            }
        }
        return $result;
    }

    //无限极菜单
    public function _sorting($data, $pid = 0)
    {
        $result = [];
        foreach ($data as $key=>$value) {
            if ($value['parent_id'] == $pid) {
                $value['child'] = $this->_sorting($data, $value['id']);
                $result[] = $value;
            }
        }
        return $result;
    }

    //数组转菜单
    public function _menu($data)
    {
        $result = '';
        foreach ($data as $key=>$value) {
            if (empty($value['child'])) {
                $className = '';
                $aTag  = '<a href="/'. $value['name'] .'" target="menuFrame"><i class="fa '. $value['icon'] .'"></i> <span>'. $value['title'] .'</span></a>';
            } else {
                $className = 'treeview';
                $aTag  = '<a href="'. $value['name'] .'"><i class="fa '. $value['icon'] .'"></i> <span>'. $value['title'] .'</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
            }
            $result .= '<li class="'. $className .'">'.$aTag;
            if (!empty($value['child'])) {
                $result .= '<ul class="treeview-menu" style="display: none;">'. $this->_menu($value['child']) .'</ul>';
            }
            $result .= '</li>';
        }
        return $result;
    }

    //文件上传
    public function fileUpload($file, $dir = '', $exceptExtends = [])
    {
        if ($file) {
            $fileName = sha1(uniqid('',true));
            $fileExtends = $file->getClientOriginalExtension();
            $uploadDir = (strlen($dir) > 0) ? public_path($dir) : public_path();
            $url = trim(str_replace('\\','/',trim(trim($dir,'/'),'\\').'\\'.$fileName.'.'.$fileExtends),'/');
            if (in_array($fileExtends,$exceptExtends)) return ['msg'=>'该文件类型禁止上传','data'=>[],'code'=>400];
            if ($file->move($uploadDir,$fileName.'.'.$fileExtends)) {
                return ['msg'=>'上传成功','data'=>$url,'code'=>200];
            } else {
                return ['msg'=>'上传失败','data'=>[],'code'=>400];
            }
        } else {
            return ['msg'=>'请上传文件','data'=>[],'code'=>400];
        }
    }
}
