<?php
/**
 * Created by PhpStorm.
 * User: Jackson.Pei
 * Date: 2020/11/26
 * Time: 11:53
 * 工具类使用该类实现类的自动依赖注入
 */

class Ioc {
    /**
     * 获得类的对象实例
     * @param $className
     */
    public static function getInstance($className) {
        $paramArr = self::getMethodParams($className);
//        die;
//        var_dump($paramArr);
        return (new ReflectionClass($className))->newInstanceArgs($paramArr);
    }

    /**
     * 获得类的方法参数
     * @param $className
     */
    public static function getMethodParams($className, $methodName = '__construct') {
        $class = new ReflectionClass($className);
        $paramArr = [];
//        var_dump($class->getMethod($methodName));die;
        if($class->hasMethod($methodName)) {
            $construct = $class->getMethod($methodName);
            $params = $construct->getParameters();
//            var_dump($params);die;
//                var_dump(count($params));die;
            if(count($params) > 0) {
                foreach ($params as $k=>$v) {
//                    var_dump($paramClass = $v->getClass());die;
                    if($paramClass = $v->getClass()) {
                        $paramClassName = $paramClass->getName();
//                        var_dump($paramClassName);die;
//                        echo ($paramClassName);
                        $args = self::getMethodParams($paramClassName);
//                        var_dump($paramClass->getName());
                        $paramArr[] = (new ReflectionClass($paramClass->getName()))->newInstanceArgs($args);
//                        var_dump($paramArr);
                    }
                }
            }
        }
//        die;
//        var_dump($paramArr);
        return $paramArr;
    }

    /**
     * 执行类的方法
     * @param $className
     * @param $methodName
     * @param array $params
     * @return mixed
     */
    public static function make($className, $methodName, $params = []) {
        $instance = self::getInstance($className);

        $paramArr = self::getMethodParams($className, $methodName);
        return $instance->{$methodName}(...array_merge($paramArr, $params));
    }

}

class A {
    protected $cObj;
    function __construct(C $c)
    {
        $this->cObj = $c;
    }
    function aa() {
        echo 'this is A test';
    }
    function aac() {
        $this->cObj->cc();
    }
}

class B {
    protected $aObj;
    function __construct(A $a)
    {
        $this->aObj = $a;
    }
    function bb(C $c, $b) {
        $c->cc()."\r\n";
        echo 'params' . $b;
    }
    function bbb() {
        $this->aObj->aac();
    }
}

class C {
    function cc() {
        echo 'this is c test';
    }

}

$bObj = Ioc::getInstance('B');
$bObj->bbb();

var_dump($bObj);

//Ioc::make('B','bb',['this is sb']);

/**
 * 递归
 * @param $i
 * @return float|int
 */
function recursive($i){
    $sum = 1;
    echo '$i当前的值为'.$i."\r\n";
    if ($i == 1){
        echo '$i='.$i.'----$sum='.$sum."-------入栈结束,开始计算---\r\n";
        return 1;
    }else{
        $sum = $i * recursive($i - 1);
    }
    echo '$i='.$i.'----$sum='.$sum."\r\n";
    return $sum;
}
//recursive(4);