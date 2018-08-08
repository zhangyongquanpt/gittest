<?php
$redis = new Redis();
$redis->connect('127.0.0.1',6379);

/*while(true){

  print_r($redis->blpop('list',10));
}*/
$count = 10;
for($i=0;$i<$count;$i++){  
    $redis->lpush('goods_store',1);  
}
?>
