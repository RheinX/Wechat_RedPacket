<?php
include "Base_Information.php";
/**
 * Created by PhpStorm.
 * User: aa
 * Date: 2017/4/7
 * Time: 14:51
 */
class RedPacket{
    private $red_packet;

    private $client_ip="127.0.0.1";  //the ip address of current machine
    private $send_name="好乐学";   //the user name of sender
    private $re_openid;   //the user who will receive the re packet,and this id is the openid id in wxappid
    private $total_amount;  //the nuber of money will send
    private $total_num=1;  //the numer of people who will receive the money
    private $wishing="恭喜发财";   //wishes
    private $act_name="反馈用户";  //name of activity
    private $remark="回馈用户";  //remarks

    function __construct($re_openid,$total_amount,$client){
        $this->re_openid=$re_openid;
        $this->total_amount=$total_amount;
        $this->client_ip=$client;

        $this->red_packet=new Base_Information();
        $this->red_packet->set_parameter("client_ip",$this->client_ip);
        $this->red_packet->set_parameter("send_name",$this->send_name);
        $this->red_packet->set_parameter("re_openid",$this->re_openid);
        $this->red_packet->set_parameter("total_amount",$this->total_amount);
        $this->red_packet->set_parameter("total_num",$this->total_num);
        $this->red_packet->set_parameter("wishing",$this->wishing);
        $this->red_packet->set_parameter("act_name",$this->act_name);
        $this->red_packet->set_parameter("remark",$this->remark);
    }

    //get the value of $key
    public function Get_Parameter($key){
        return $this->red_packet->get_parameter($key);
    }

    //set the value
    public function Set_Parameter($key,$value){
        $this->red_packet->set_parameter($key,$value);
    }

    //get the list of parameter we get change
    public function Get_Parameter_Write(){
        return $this->red_packet->Get_List_Parameter();
    }

    public function Get_Parameter_ReadOnly(){
        return $this->red_packet->Get_List_Parameter(1);
    }

    public function check_all_parameter(){
        return $this->red_packet->returnPara();
    }
}