<?php
/**
 * Created by PhpStorm.
 * User: Jackson.Pei
 * Date: 2020/12/2
 * Time: 14:14
 * 布隆过滤器
 * 布隆过滤器只能验证一个值 一定不存在或可能存在
 */
class BloomFilter
{
    //由于php的int为64位,如果要存储10亿,或者更大的数据,需要通过多个int数组实现
    protected $bitmap = [];
    //一个int,只能表示0-64的数字
    //10亿的数字
    protected $maxNum = 1 << 30;

    function add($key)
    {
        $hash = $this->hash($key);
        $bitmapKey = $this->getBitmapKey($hash);

        if (isset($this->bitmap[$bitmapKey])) {
            //如果已经添加了这个数组,则直接与运算将数据加上
            $this->bitmap[$bitmapKey] = $this->bitmap[$bitmapKey] | (1 << ($hash % 64));
        } else {
            $this->bitmap[$bitmapKey] = 1 << ($hash % 64);
        }
        return $hash;
    }

    function get($key)
    {
        $hash = $this->hash($key);
        $bitmapKey = $this->getBitmapKey($hash);

        if (isset($this->bitmap[$bitmapKey])) {
            $bitToNum = 1 << ($hash % 64);//算出这个数字的二进制位数
            $tempNum = $this->bitmap[$bitmapKey] & $bitToNum;
            if ($tempNum != $bitToNum) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    function hash($key)
    {
        $hash = crc32($key);
        //time33返回的数据是0-2^31,目前我们的设想是只存储2^30,所以取余
        $hash = $hash % $this->maxNum;
        return $hash;
    }

    function getBitmapKey($data)
    {
        $key = intval($data / 64);
        return $key;
    }
}


$bloomFilter = new BloomFilter();
$str = "sb";
for($i=1; $i <= 1000; $i++) {

    $hash = $bloomFilter->add($str . $i);
}

var_dump($bloomFilter->get($str . '100'));
var_dump($bloomFilter->get('213312'));