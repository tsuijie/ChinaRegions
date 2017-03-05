<?php

/**
 * 解析行政区文本
 * @category
 * @author cuijie <jndion2014@gmail.com>
 * 2017/3/6 0:50
 */

$raw = file_get_contents('../txt/latest.txt');
$data = explode(PHP_EOL, $raw);

$array = array();
$php = '<?php ' . PHP_EOL . '/** 程序生成的脚本，请勿手动更改 */' . PHP_EOL . 'return ';

$mysql = 'DROP TABLE IF EXISTS china_regions;';
$mysql .= 'CREATE TABLE china_regions (`code` int(6) unsigned NOT NULL COMMENT \'行政区编号\', `name` varchar(60) NOT NULL DEFAULT \'\' COMMENT \'行政区名称\', PRIMARY KEY (`code`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
$mysql .= 'INSERT INTO china_regions (`code`, `name`) VALUES ';

$javascript = "var regions={";

foreach ($data as $item) {
    $item = explode(' ', preg_replace('/\s+/', ' ', $item));
    $id = $item[0];
    $name = trim($item[1], '　'); // 清除全角空格
    $javascript .= "'$id':'$name',";
    $mysql .= "('$id', '$name'),";
    $array[$id] = $name;
}

$javascript = rtrim($javascript, ',').'};';
file_put_contents('../dist/regions.js', $javascript);

$mysql = rtrim($mysql, ',').';';
file_put_contents('../mysql/china_regions.sql', $mysql);

$php .= var_export($array, true).';';
file_put_contents('../php/regions.php', $php);