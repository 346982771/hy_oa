<?php


function getchildrenid($data,$id){
    //进行数组排序
    static $arr = [];

    foreach ($data as $k => $v){
        if($v['pid'] == $id){
            if($id != 0){
                $v['title'] = '|—'.$v['title'];
            }
            $arr[] = $v;

            getchildrenid($data,$v['id']);
        }
    }
    return $arr;
}
//获取最近N天日期
function get_days($num = 7, $time = '', $format='Y-m-d'){
    $time = $time != '' ? $time : time();
    //组合数据
    $date = [];
    for ($i=1; $i<=$num; $i++){
        $date[$i] = date($format ,strtotime( '+' . $i-$num .' days', $time));
    }
    return $date;
}
//多维数组是否含有某个值
function deep_in_array($value, $array) {

        foreach($array as $item) {
            if(!is_array($item)) {
                if ($item == $value) {
                    return true;
                } else {
                    continue;
                }
            }
            if(in_array($value, $item)) {
                return true;
            } else if(deep_in_array($value, $item)) {
                return true;
            }
        }


    return false;
}
//获取随机6位字符串
function get_random($length = 6, $type = 'string', $convert = 0){
    $config = [
        'number' => '1234567890',
        'letter' => 'abcdefghijklmnopqrstuvwxyz',
        'string' => 'abcdefghjkmnpqrstuvwxyz23456789',
        'all' => 'abcdefghijklmnopqrstuvwxyz1234567890'
    ];
    if (!isset($config[$type]))
        $type = 'string';
    $string = $config[$type];

    $code = '';
    $strlen = strlen($string) - 1;
    for ($i = 0; $i < $length; $i++) {
        $code .= $string{mt_rand(0, $strlen)};
    }
    if (!empty($convert)) {
        $code = ($convert > 0) ? strtoupper($code) : strtolower($code);
    }
    return $code;
}

