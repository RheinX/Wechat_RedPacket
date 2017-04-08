<?php

/**
 * Created by PhpStorm.
 * User: aa
 * Date: 2017/4/8
 * Time: 14:43
 */
class MyException extends Exception{
    // 重定义构造器使 message 变为必须被指定的属性
    public function __construct($message, $code = 0, Exception $previous = null) {
        // 自定义的代码

        // 确保所有变量都被正确赋值
        parent::__construct($message, $code, $previous);
    }

    // 自定义字符串输出的样式
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function customFunction() {
        echo "A custom function for this type of exception\n";
    }
}

class DealExpection extends Exception{
    const ERROR_PARAMETER=0;  //fill the wrong parameter
    const VALID_PARAMETER=1; //this parameter is not allowed to change
    const UNKNOWN_ERROR=2;  //Unknown error
    const UNFILLED_ALL_PARAMETER=3;  //not all the parameter are filled
    const PRIVATE_ERROR=4;  //this parameter you cannot get

    function __construct($value = self::ERROR_PARAMETER,$wrong=0){
        switch($value){
            case self::ERROR_PARAMETER:{
                throw new MyException("The parameter ".$wrong." isn't exist!",0);
                break;
            }

            case self::VALID_PARAMETER:{
                throw new MyException("You are allowed to change this parameter".$wrong,1);
                break;
            }

            case self::UNKNOWN_ERROR:{
                throw new MyException("Unknown Error",2);
                break;
            }
            case self::UNFILLED_ALL_PARAMETER:{
                throw new MyException("You must filled all the parameter!The parameter ".$wrong." is miss!",3);
                break;
            }

            case self::PRIVATE_ERROR:{
                throw new MyException("The data is private,you are allowed get it",4);
                break;
            }
        }
    }

}