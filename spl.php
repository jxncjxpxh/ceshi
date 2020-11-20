<?php
/**
 * 数据结构spl的使用
 * Created by PhpStorm.
 * User: Jackson.Pei
 * Date: 2020/11/20
 * Time: 15:05
 */

//栈 后进先出
$stack = new SplStack();
$stack->push(3);
$stack->push(5);
$stack->push(1);

$stack->add(1,4);

//echo $stack->pop();
//echo $stack->pop();
//echo $stack->pop();
//echo $stack->pop();

//队列 先进先出

$queue = new SplQueue();
$queue->enqueue(3);
$queue->enqueue(4);
$queue->enqueue(5);

//echo $queue->dequeue();
//echo $queue->dequeue();
//echo $queue->dequeue();

//堆的使用
$heap = new SplMaxHeap(); //最大堆 升序输出
$heap->insert('A');
$heap->insert('E');
$heap->insert('D');

//echo $heap->extract();
//echo $heap->extract();

$arr = new SplFixedArray(10);
$arr[2] = 4;
$arr[4] = 'sbv';
$arr[6] = 'abc';
$arr->setSize(15);
var_dump($arr);