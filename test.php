<?php 

$pattern = '@\w+.\w+@si'; 
  
echo preg_replace($pattern, 'im_$0 $1','users.id = siparis.id');



?>