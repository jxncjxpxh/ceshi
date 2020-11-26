<?php
/**
 * Created by PhpStorm.
 * User: Jackson.Pei
 * Date: 2020/11/24
 * Time: 14:41
 */

//echo (int)'ef';

//function test(...$args) {
//    var_dump(func_num_args());
//    var_dump($args);
//}
//test(2,3,4);
//redis 利用setnx 实现分布式锁
//$redis = new Redis();
//$redis->pconnect('127.0.0.1', 6379);
//$redis->auth('');
//
//$res = $redis->setnx('sbkey', '1');
//if($res) {
//    //拿到了锁 业务操作
//    sleep(30);
//    $redis->del('sbkey');
//} else {
//    die('waiting');
//}

//$fp = fopen('access.log', 'a+');
//$re = flock($fp, LOCK_EX);
//var_dump($re);
//
//if($re) {
//    //拿到了独占锁 可以写入数据
//    fwrite($fp, time()."\r\n");
//    sleep(10);
//    flock($fp, LOCK_UN);
//    die('sucess');
//
//} else {
//    die('waiting...');
//}
//fclose($fp);
class test{
    public function testTry(){
        $i = 0;
        try {
            $i= $i+1;
            throw new Exception('sb');
            return $i;
        } catch (Exception $e) {
            echo "wc";
        } finally {
            $i= $i+2;
            echo $i;
//             print_r($i);
//            return "1111";//当finally有return的时候 返回这个，当注销后，返回try 或者是 catch的内容。

        }

    }
}

$b = new test();
echo $b->testTry();
