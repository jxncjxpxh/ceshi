<?php
/**
 * 常用6种设计模式
 * Created by PhpStorm.
 * User: Jackson.Pei
 * Date: 2020/11/23
 * Time: 14:23
 */

/**
 * 单例模式【如数据库连接方式】
 * Class Singleton
 */
class Singleton {
    private static $_instance;
    private function __construct()
    {
    }
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    public static function getInstance() {
        if(self::$_instance instanceof Singleton) {
            self::$_instance = new Singleton();
        }

        return self::$_instance;
    }
}


class A {

}

class B {

}

/**
 * 工厂模式，不需要new实例，只要调用工厂实例化方法即可 【解决藕合】
 * Class Factory
 */
class Factory {
    const A = 'A';
    const B = 'B';

    static function getInstance($newclass) {
        $class = $newclass;
        return new $class;
    }
}

//调用方法
//$s = Factory::getInstance(Factory::A);
//var_dump($s);

/**
 * 注册树模式 把对象都放到一个全局的静态数组属性里面，需要就拿出来
 * Class register
 */

class register {
    protected static $objs;
    public function set($alias, $obj) {
        self::$objs[$alias] = $obj;
    }

    public function get($alias) {
        return self::$objs[$alias];
    }

    public function _unset($alias) {
        unset(self::$objs[$alias]);
    }
}

/**
 * 策略模式，实现一种功能，可以用很多策略。
 * Class Strategy
 */
abstract class Strategy {
    abstract function goSchool();
}

class Run extends Strategy {
    function goSchool()
    {
        echo 'run';
        // TODO: Implement goSchool() method.
    }
}

class Bus extends Strategy {
    function goSchool()
    {
        echo 'bus';
        // TODO: Implement goSchool() method.
    }
}

class context {
    protected $_strategy;

    function getinstance($obj) {
        $this->_strategy = $obj;
        $this->_strategy->goSchool();
    }
}

//$con = new context();
//$con->getinstance(new Bus());

/**
 * 适配器模式，统一api 如数据库操作，缓存操作等
 * Class toy
 */

abstract class toy {
    public abstract function openMouth();
    public abstract function closeMouth();
}

class Doy extends toy {
    public function openMouth()
    {
        echo 'doy open';
        // TODO: Implement openMouth() method.
    }

    public function closeMouth()
    {
        echo 'doy close';
        // TODO: Implement closeMouth() method.
    }
}

class Cat extends toy {
    public function openMouth()
    {
        echo 'cat open';
        // TODO: Implement openMouth() method.
    }

    public function closeMouth()
    {
        echo 'cat close';
        // TODO: Implement closeMouth() method.
    }
}

interface Red {
    public function doMouthOpen();
    public function doMouthClose();
}

interface Green {
    public function operateMouth($type = 0) ;
}
//适配器角色1
class redadapter implements Red {
    private $adaptee;

    function __construct(toy $adaptee) {
        $this->adaptee = $adaptee;
    }
    function doMouthOpen()
    {
        $this->adaptee->openMouth();
        // TODO: Implement doMouthOpen() method.
    }

    function doMouthClose()
    {
        $this->adaptee->closeMouth();
        // TODO: Implement doMouthClose() method.
    }
}
//适配器角色2
class greenadapter implements Green {
    private $adaptee;

    function __construct(toy $adaptee)
    {
        $this->adaptee = $adaptee;
    }

    function operateMouth($type = 0)
    {
        if($type) {
            $this->adaptee->closeMouth();
        } else {
            $this->adaptee->openMouth();
        }
        // TODO: Implement operateMouth() method.
    }
}

class testDriver {
    public function run() {
        $doy = new Doy();
        $adap_red = new redadapter($doy);
        $adap_red->doMouthOpen();

        $adap_green = new greenadapter($doy);
        $adap_green->operateMouth(0);
    }
}

//$o = new testDriver();
//$o->run();

/**
 * 观察者模式当一个对象发生变化，依赖他的对象全部会收到通知
 * Interface Subject
 */

interface Subject {
    public function register(Observer $observer);

    public function notify();
}

//观察者接口
interface Observer {
    public function watch();
}

//主题
class Action implements Subject {
    public $_observers = [];

    public function register(Observer $observer)
    {
        $this->_observers[] = $observer;
        // TODO: Implement register() method.
    }

    function notify()
    {
        foreach ($this->_observers as $o) {
            $o->watch();
        }
        // TODO: Implement notify() method.
    }
}

class sb1 implements Observer {
    function watch()
    {
        echo 'sb1';
        // TODO: Implement watch() method.
    }
}

class sb2 implements Observer {
    function watch()
    {
        echo 'sb2';
        // TODO: Implement watch() method.
    }
}

$action = new Action();
$action->register(new sb1());
$action->register(new sb2());
$action->notify();