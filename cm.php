<?php
/**
 * Created by PhpStorm.
 * User: Jackson.Pei
 * Date: 2020/12/1
 * Time: 10:32
 */

function pdo_connect() {
    $dns = "mysql:host=127.0.0.1;dbname=demo";
    $res = new PDO($dns, 'root', '123456');
//    var_dump($res);die;
    return $res;
}

function select($conn,$sql) {
    $data = [];
    $rows = $conn->query($sql);
    foreach ($rows as $o) {
        $data[] = $o;
    }
    return $data;
}



function insert($conn, $data , $tableName) {
    $keyArr = array_keys($data);
    $valArr = array_values($data);
    $keystring = implode(',' , $keyArr);
    $valstring = '\'' .implode('\',\'' , $valArr) . '\'';
    $sql = "insert into `{$tableName}` ({$keystring}) VALUES ({$valstring})";

    $conn->exec($sql);
}


class Test {
    private static $instance = null;


    public static function Redis() {
        if(self::$instance == null) {
            $redis = new Redis();
            $redis->connect('127.0.0.1', 6379);
            self::$instance = $redis;
        }

        return self::$instance;
    }

    /**
     * 将商品库存储到队列
     */
    public function doPageSaveNum() {

        $redis = self::Redis();
        $goods_id = 1;
        $sql = "select num from ims_hotmallstore_goods where id = ". $goods_id;
        $conn = pdo_connect();
        $data = select($conn, $sql);
//        var_dump($data);die;

        if(!empty($data[0])) {
            for($i=1; $i<= $data[0]['num']; $i++) {
                $redis->lpush('num', 1);
            }
            die('入库成功');
        } else {
            $this->echoMsg(0, '商品不存在');
        }
     }

    /**
     * 抢购下单
     */
     function doPageGoodsStore() {
        $goods_id = 1;
        $sql = "select id, num, money from ims_hotmallstore_goods where id = " . $goods_id;
        $goods = select(pdo_connect(), $sql);
        $redis = self::Redis();
        $count = $redis->rPop('num');
        if($count == 0) {
            $this->echoMsg(0, 'no num redis');
        }

        $this->doPageGoodsOrder($goods[0], 1);

     }

     function orderNo() {
         return date('ymd') . substr(implode(null , array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
     }

     function doPageGoodsOrder($goods, $goods_number) {
         $orderNo = $this->orderNo();
         $number = $goods['num'] - $goods_number;

         if($number < 0) {
            $this->echoMsg(0 , '已经没有库存了');
         }

         $user_id = mt_rand(1, 700);
         $data['user_id'] = $user_id;
         $data['goods_id'] = $goods['id'];
         $data['number'] = $goods_number;
         $data['price'] = $goods['money'];
         $data['status'] = 1;
         $data['sku_id'] = 2;
         $data['order_sn'] = $orderNo;
         $data['create_time'] = date('Y-m-d H:i:s');
         insert(pdo_connect(), $data, 'ims_order');

         $sql = "update ims_hotmallstore_goods set num=num -" . $goods_number . " where num > 0 and id = " . $goods['id'];
         if(pdo_connect()->exec($sql)) {
             $this->echoMsg(1, '减库成功');
         }
         $redis = self::Redis();
         $redis->lpush('num', $goods_number);
         $this->echoMsg(0, '减库失败' . $number);
     }

    /**
     * 保存日志
     * @param $status
     * @param $msg
     * @param string $_data
     */
     function echoMsg($status, $msg, $_data = '') {
        $data['status'] = $status;
        $data['msg'] = $msg;
        $data['create_time'] = date('Y-m-d H:i:s');
        insert(pdo_connect(), $data, 'ims_order_log');
        die;
     }
}

$o = new Test();
//$o->doPageSaveNum();
$o->doPageGoodsStore();