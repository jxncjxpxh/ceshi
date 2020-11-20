<?php
/**
 * Created by PhpStorm.
 * User: Jackson.Pei
 * Date: 2020/11/20
 * Time: 16:02
 */

//数组里面元素作用于回调函数，返回新数组
$s = array_map(function($v){
    return $v*$v;
},[2,4,5,6,7]);

//var_dump($s);
// 数组元素对换 value=>key
$s = array_flip([4]);
//var_dump($s);

//数据元素过滤
$s = array_filter([3,4,5],function($v){
    if($v == 4) {
        return false;
    }
    return true;
});
//var_dump($s);

//对数组每个元素应用自定义函数
$arr = ['sb'=>'ee','w'=>4];
array_walk($arr, function ($k,$v) {
    echo $k.'=='.$v."\r\n";
});

$s = function() {
    return 'sb';
};
//var_dump($s); test
