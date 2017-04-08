<?php
include "DealExpection.php";
/**
 * Created by PhpStorm.
 * User: aa
 * Date: 2017/4/7
 * Time: 15:32
 */
class Base_Information{
    private $parameter;  //the parameters we need to post to wechat
    private $parameter_can_be_change=["send_name","re_openid","total_amount","total_num","wishing","act_name","remark","client_ip"];  //the pramter can be modify outside
    private $parameter_cannot_change=["mch_id","wxappid","key","nonce_str","sign","mch_billno"];

    //parameters
    private  $mch_id="123";  //the id of merchant
    private $wxappid="123";  //the id of Official Accounts
    private $key="123";  //this variable do not need to append into parameter,it be needed in generation of sign
    private $nonce_str;  //random string
    private $sign;   //signature
    private $mch_billno;  //the id of order


    function __construct(){
        //generate the nonce_str
        $this->nonce_str=$this->get_random_string();
        //generate the mch_billno
        $this->mch_billno=$this->mch_id.date('YmdHis').rand(1000000000, 9999999999);

        $this->parameter["mch_id"]=$this->mch_id;
        $this->parameter["wxappid"]=$this->wxappid;
        $this->parameter["nonce_str"]=$this->nonce_str;
        $this->parameter["key"]=$this->key;
        //we should do this at last because the generation of sign needs the data in paramter
        // $this->parameter["sign"]=$this->sign;
        $this->parameter["mch_billno"]=$this->mch_billno;
    }

    //change the value,but only can change the parameter in parameter_can_be_change,otherwise it will throw a exception
    public function set_parameter($key,$value){
        try{
            //the parameter is not exist

            if(!in_array($key,$this->parameter_can_be_change)&&!in_array($key,$this->parameter_cannot_change)){
                throw new DealExpection(DealExpection::ERROR_PARAMETER,$key);
            }
            //the parameter is allowed to change
            elseif(!in_array($key,$this->parameter_can_be_change)&&in_array($key,$this->parameter_cannot_change)){
                throw new DealExpection(DealExpection::VALID_PARAMETER);
            }
            //valid
            else if(in_array($key,$this->parameter_can_be_change)){
                $this->parameter[$key]=$value;
            }
            else{
                throw new DealExpection(DealExpection::UNKNOWN_ERROR);
            }
        }catch(DealExpection $e){
            $e->getMessage();
        }
    }

    public function get_parameter($key){
        try{
            //the parameter is not exist
            if(!array_key_exists($key,$this->parameter_can_be_change)&&!array_key_exists($key,$this->parameter_cannot_change)){
                throw new DealExpection(DealExpection::ERROR_PARAMETER);
            }
            //the parameter is allowed to get
            elseif(!array_key_exists($key,$this->parameter_can_be_change)&&array_key_exists($key,$this->parameter_cannot_change)){
                throw new DealExpection(DealExpection::PRIVATE_ERROR);
            }
            //valid
            else if(array_key_exists($key,$this->parameter_can_be_change)){
                return $this->parameter[$key];
            }
            else{
                throw new DealExpection(DealExpection::UNKNOWN_ERROR);
            }
        }catch(DealExpection $e){
            $e->getMessage();
        }
    }


    //generate the random string
    private function get_random_string(){
        $str = '1234567890abcdefghijklmnopqrstuvwxyz';
        $t1="";
        for($i=0;$i<30;$i++){
            $j=rand(0,35);
            $t1 .= $str[$j];
        }
        return $t1;
    }


    //generate the sign
    private function get_sign(){
       try{
           //check the paramter if whole paramter is filled
           foreach($this->parameter_can_be_change as $key=>$value)
               if(empty($this->parameter[$value]))
                   throw new DealExpection(DealExpection::UNFILLED_ALL_PARAMETER,$value);

           foreach($this->parameter_cannot_change as $key=>$value)
               if($value!="sign"&&empty($this->parameter[$value]))
                   throw new DealExpection(DealExpection::UNFILLED_ALL_PARAMETER,$value);

               ksort($this->parameter);

               $stringA=$this->get_montage_string($this->parameter);
               $stringSignTemp=$stringA."&key=".$this->key;

               return strtoupper(md5($stringSignTemp));
       }
       catch(DealExpection $e){
           $e->getMessage();
       }
    }

    //get the montage_string
    private function get_montage_string($parameter){
        $answer="";
        foreach($parameter as $key=>$value)
            if($value!="null"&&$key!="sign"&&$key!="key")
                $answer=$answer.$key."=".$value."&";

        $reqPar="";
        if (strlen($answer) > 0) {
            $reqPar = substr($answer, 0, strlen($answer)-1);
        }
        return $reqPar;
    }


    //return the list of two parameter
    public function Get_List_Parameter($type=0){
        if(0==$type)
            return $this->parameter_can_be_change;
        else
            return $this->parameter_cannot_change;
    }

    //fill the sign
    public function GenerateSign(){
        $this->sign=$this->get_sign();
        $this->parameter['sign']=$this->sign;
    }

    public function returnPara(){
        $this->GenerateSign();

        return $this->parameter;
    }
}