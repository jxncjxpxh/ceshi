<?php
/**
 * Created by PhpStorm.
 * User: Jackson.Pei
 * Date: 2020/11/23
 * Time: 15:49
 */

class db {
    function init() {
        echo '连接db成功';
    }
}

class validate {
    function check() {
        echo '验证通过';
    }
}

class view {
    function display() {
        echo '显示界面';
    }
}

class user1 {
    function login() {
        $db = new db;
        $db->init();
        $validate = new validate;
        $validate->check();
        $view = new view();
        $view->display();
    }
}
// 严重依赖，藕合粘度太强
//(new user1())->login();
//die;

//解决依赖藕合的过程叫解耦，最常用的 依赖注入DI
//依赖注入解耦
class user2 {
    private $db=null;
    private $validate = null;
    private $view = null;

    function __construct(db $db, validate $validate, view $view)
    {
        $this->db = $db;
        $this->validate = $validate;
        $this->view = $view;
    }

    function login() {

        $this->db->init();
        $this->validate->check();
        $this->view->display();
    }
}
//依赖注册解耦
//(new user2(new db, new validate(), new view()))->login();
//die;


//创建容器实现依赖容器解耦
class Container
{
    //创建一个数据保存类与类的实例化方法
    public $instance = [];

    public function bind($abstract, Closure $process)
    {
        //键名为类名，值为实例化的方法
        $this->instance[$abstract] = $process;
    }

    //创建类的实例
    public function make($abstract, $process = [])
    {
        return call_user_func_array($this->instance[$abstract],$process);
//        return $this->instance[$abstract];
    }
}

$container = new Container();
$container->bind('db', function (){
   return new db();
});
$container->bind('validate', function (){
    return new validate();
});
$container->bind('view', function (){
    return new view();
});

//var_dump($container->instance);

class User {
    public function login(Container $container) {
//        var_dump($container);
        $s = $container->make('db')->init();
        var_dump($s);
    }
}
//依赖容器实现解耦
//$u = new User();
//$u->login($container);

//门面实现静态化调用
class facade {
    public static function init(Container $container) {
        return $container->make('db')->init();
    }
}
//门面模式
facade::init($container);