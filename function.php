<?php

function nomer($a,$b){
    global $db;
   mysqli_query($db,"UPDATE `users` SET `nomer` = '$b' WHERE `chat_id` = '$a'");   
}

function qadam($a,$b){
    global $db;
   mysqli_query($db,"UPDATE `users` SET `qadam` = '$b' WHERE `chat_id` = '$a'");   
}
function til($a,$b){
    global $db;
   mysqli_query($db,"UPDATE `users` SET `til` = '$b' WHERE `chat_id` = '$a'");   
}

function del($chat_id,$message_id){
	bot('deleteMessage',[ 
       'chat_id'=>$chat_id,
         'message_id'=>$message_id,
        ]);
}
function joy($a,$b){
 global $db;
   mysqli_query($db,"UPDATE `users` SET `joy` = '$b' WHERE `chat_id` = '$a'");   
}


?>