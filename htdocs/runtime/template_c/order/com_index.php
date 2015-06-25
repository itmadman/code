<script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
<?php

set_time_limit(0);
 foreach($this->arr_var["a"] as $this->arr_var["key"]=>$this->arr_var["val"]){
    echo '<br/>',$this->arr_var["val"],'------',date('Y-m-d H:i:s',time()),'-----请输入密码：<input type="password" name="name1" id="Ygbjcg_'.$this->arr_var["key"].'">---密码：'.$this->arr_var["b"][$this->arr_var["key"]].'----<a href="javascript:void(0)" onclick="CkeckPwd('.$this->arr_var["key"].')">输完点我</a>','<hr/>';
    ob_flush();
    flush();
    sleep(3);
 }


 ?>

 <script type="text/javascript">
     function CkeckPwd(id){
         var d=$('#Ygbjcg_'+id).val()

         $.get("<?=URL::To(['order/yz']);?>",{pwd:d,id:id},function(data){
            if(data == ''){
                alert('密码错误');
            }else{
                location.href=data;
            }
         })
     }

 </script>