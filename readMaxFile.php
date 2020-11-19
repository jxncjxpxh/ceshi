<?php
/**
 * Created by PhpStorm.
 * User: Jackson.Pei
 * Date: 2020/11/19
 * Time: 16:14
 * php读取大文件和目录遍历的方法【高效不占多大内存】
 * 利用yield生成器解决
 *
 */

/**
 * @param string $path 文件目录最后没有杠
 * @param bool $include_dirs 是否线上目录
 * @return Generator 生成器对象
 * 遍历目录（特别是大目录）
 */
function readMaxDir($path, $include_dirs = false) {
    $path = rtrim($path, '/*');

    if(is_readable($path)) {
        $dh = opendir($path);
        while (($file = readdir($dh)) !== false) {
            if(substr($file, 0, 1) == '.') {
                continue;
            }

            $rfile = "{$path}/{$file}";

            if(is_dir($rfile)) {
                $sub = readMaxDir($rfile, $include_dirs);
                while ($sub->valid()) {
                    yield $sub->current();
                    $sub->next();
                }
                if($include_dirs) {
                    yield $rfile;
                }
            } else {
                yield $rfile;
            }
        }

        closedir($dh);
    }
}

//$glob = readMaxDir('D:\myenv\www\peixiuhua-dev\Api\pxhApi', true);
//while ($glob->valid()) {
//    $filename = $glob->current();
//    $glob->next();
//    echo $filename."\r\n";
//}
/**
 * @param string $path 路径信息
 * @return Generator yield 生成器对象
 * 遍历多有的文件内容
 */
function readMaxText($path) {
    if($handle = fopen($path, 'r')) {
        while(!feof($handle)) {
            yield trim(fgets($handle));
        }

        fclose($handle);
    }
}
//$glob = readMaxText('D:\myenv\www\qudong.cc\Api\time.log');

//while ($glob->valid()) {
//    $line = $glob->current();
//    $glob->next();
//    echo $line . "\r\n";
//}
/**
 * @param $path
 * @param int $count 每页多少数据
 * @param int $offset 从哪里开始
 * @return array
 * 分页读取大文件
 */
function readTextPg($path, $count, $offset = 0) {
    $arr = [];
    if(!is_readable($path)) {
        return $arr;
    }

    $fp = new SplFileObject($path, 'r');

    if($offset) {
        $fp->seek($offset);
    }

    $i = 0;

    while (!$fp->eof()) {
        $i++;

        if($i > $count) {
            break;
        }
        $line = $fp->current();
        $arr[] = trim($line);

        $fp->next();
    }

    return $arr;

}

//$r = readTextPg('D:\myenv\www\2020\ceshi\access.log', 6, 1);
//var_dump($r);

/**
 * 复制大文件用数据流方式解决内存不足问题，另外还可以调用系统命令exec
 * @param string $path 原文件路径
 * @param string $to_path 要复制过去的文件路径
 * @return bool
 */
function copyText($path, $to_path) {
    if(!is_readable($path)) {
        return false;
    }
    $new_dir = dirname($to_path) . '/';
    if(!is_dir($new_dir)) {
        @mkdir($new_dir, 0777, true);
    }

    if( ($hd1 = fopen($path, 'r')) && ($hd2 = fopen($to_path, 'w'))) {
        stream_copy_to_stream($hd1, $hd2);

        fclose($hd1);
        fclose($hd2);
    }
}

//copyText('D:\myenv\www\2020\ceshi\access.log', 'D:\myenv\www\2020\ceshi\sb\access.log');