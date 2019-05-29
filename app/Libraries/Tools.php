<?php

namespace App\Libraries;

trait Tools
{
    // 无限级数组排序
    public function sorting($data, $parent_id = 0, $level = 0)
    {
        static $result = [];
        foreach ($data as $key=>$value) {
            if ($value['parent_id'] == $parent_id) {
                $value['level'] = $level;
                $result[] = $value;
                $this->sorting($data, $value['id'], $level+1);
            }
        }
        return $result;
    }

    // 无限级数组排序成多维数组
    public function sortArray($data, $parent_id = 0)
    {
        $result = [];
        foreach ($data as $key=>$value) {
            if ($value['parent_id'] == $parent_id) {
                $value['child'] = $this->sortArray($data, $value['id']);
                $result[] = $value;
            }
        }
        return $result;
    }

    // 多维数组转化成菜单
    public function sortString($data)
    {
        $result = '';
        foreach ($data as $key=>$value) {
            if (empty($value['child'])) {
                $className = '';
                $aTag  = '<a href="/'. $value['uri'] .'" target="menuFrame"><i class="fa '. $value['icon'] .'"></i> <span>'. $value['title'] .'</span></a>';
            } else {
                $className = 'treeview';
                $aTag  = '<a href="'. $value['uri'] .'"><i class="fa '. $value['icon'] .'"></i> <span>'. $value['title'] .'</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
            }
            $result .= '<li class="'. $className .'">'.$aTag;
            if (!empty($value['child'])) {
                $result .= '<ul class="treeview-menu" style="display: none;">'. $this->sortString($value['child']) .'</ul>';
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
