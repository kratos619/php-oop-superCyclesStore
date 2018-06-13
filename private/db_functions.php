<?php
/**
 * Created by PhpStorm.
 * User: Gaurav
 * Date: 13-06-2018
 * Time: 15:48
 */

function db_connect(){

    $connection = new mysqli(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
    confirm_db_connect($connection);
    return $connection;
}

function confirm_db_connect($connection){
if($connection->connect_errno){
    $msg = "db failed";
    $msg .= $connection->connect_errno;
    $msg .= " (" . $connection->connect_errno . ") ";
    exit($msg);
}

}

function db_disconnect($connection){
    if(isset($connection)){
        $connection->close();
    }


}

?>