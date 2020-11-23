<?php
/**
 * Created by PhpStorm.
 * User: Jackson.Pei
 * Date: 2020/11/23
 * Time: 10:28
 */

/**
 * 冒泡排序
 * 外层循环控制次数，内层循环比较大小调换位置，大的往后，类似小的网上浮大的往下沉，时间复杂度O(n²)
 * @param array $arr
 * @return array
 */

function mSort(array $arr) {
    $len = count($arr);
    if($len < 2) {
        return $arr;
    }

    for($i=0; $i<$len - 1; $i++) {
        for($j=0; $j<$len -$i -1; $j++) {
            if($arr[$j] > $arr[$j+1]) {
               $temp = $arr[$j];
               $arr[$j] = $arr[$j+1];
               $arr[$j+1] = $temp;
            }
        }
    }

    return $arr;
}

//var_dump(mSort([3,4,5,2,-2,4,0,5,9]));

/**
 * 快速排序
 * 选一个基准元素，通常选第一个，用一个循环比较基准元素，比基准元素大的在右边，小的在左边，递归。时间复杂度O (nlogn)
 * @param array $arr
 * @return array
 */

function qSort(array $arr) {
    $len = count($arr);
    if($len < 2) {
        return $arr;
    }

    $larr = [];
    $rarr = [];
    $un = $arr[0]; // 把第一个元素作为基准
    for($i = 1; $i < $len; $i++) {
        if($arr[$i] > $un) {
            $rarr[] = $arr[$i];
        } else {
            $larr[] = $arr[$i];
        }
    }

    $larr = qSort($larr);
    $rarr = qSort($rarr);

    return array_merge($larr,[$un],$rarr);
}

var_dump(qSort([34,5,1,233,0,-1]));