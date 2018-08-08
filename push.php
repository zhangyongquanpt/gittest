<?php
$redis = new Redis();
$redis->pconnect('127.0.0.1',6379);
$conn = mysqli_connect("localhost","root",""); 
 if(!$conn){    
    echo "connect failed";    
    exit;    
}   
@mysqli_select_db($conn,"big");   
@mysqli_query($conn,"set names utf8");

$price=10;  
$user_id=1;  
$goods_id=1;  
$sku_id=11;  
$number=1;  

 //生成唯一订单号  
function build_order_no(){  
    return date('ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);  
} 

//记录日志  
function insertLog($event,$type=0){  
    global $conn;  
    $sql="insert into ih_log(event,type) values('$event','$type')";    
    @mysqli_query($conn,$sql);    
}  

$count=$redis->lpop('goods_store');  
if(!$count){  
    insertLog('error:no store redis'); 
    echo "抢光"; 
    return;  
}  
//生成订单    
$order_sn = build_order_no();  

$sql = "insert into ih_order(order_sn,user_id,goods_id,sku_id,price) values ('$order_sn','$user_id','$goods_id','$sku_id','$price')"; 

$order_rs = mysqli_query($conn,$sql);    
  
//库存减少  
$sql="update ih_store set number=number-{$number} where sku_id='$sku_id'";  
$store_rs =   @mysqli_query($conn,$sql);    ;    
if(@mysqli_affected_rows()){    
    insertLog('库存减少成功');  
}else{    
    insertLog('库存减少失败');  
}   
?>
