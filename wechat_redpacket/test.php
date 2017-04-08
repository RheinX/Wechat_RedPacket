<?php
include "RedPacket.php";
/**
 * Created by PhpStorm.
 * User: aa
 * Date: 2017/4/8
 * Time: 14:13
 */

$test=new RedPacket("oxTWIuGaIt6gTKsQRLau2M0yL16E",0.01,"127.0.0.1");
$answer=$test->check_all_parameter();
foreach($answer as $key=>$value){
    print $key."=>".$value."<br>";
}