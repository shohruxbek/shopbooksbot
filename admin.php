<?php

if($text=='add'){
	bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"Kitob faylini yuboring"
	]);
	qadam($chat_id,"kfile");
}


if($message->document and $qadam=='kfile'){
	$doc = $message->document->file_id;
	  $res =   mysqli_query($db, "INSERT INTO `upload` (`id`, `file`, `photo`, `narx`) VALUES (1, '$doc', '0', 0)");
    if($res){
    	$re= json_encode($res);
        bot('sendMessage', ['text' => "Kitob rasmi?", 'chat_id' => $chat_id,  ]);
      qadam($chat_id,"krasm");
      exit(); 
  }else{
     bot('sendMessage', ['text' => "Xatolik, faylni qayta yuboring", 'chat_id' => $chat_id ]);
      qadam($chat_id,"kfile");
      exit();
  }
}


if($message->photo and $qadam=='krasm'){
	$pho = $message->photo[0]->file_id;
	  $res =   mysqli_query($db, "UPDATE `upload` SET `photo` = '$pho' WHERE `id` = 1");
    if($res){
    	$re= json_encode($res);
        bot('sendMessage', ['text' => "Kitob narxi?", 'chat_id' => $chat_id,  ]);
      qadam($chat_id,"knarx");
      exit(); 
  }else{
     bot('sendMessage', ['text' => "Xatolik, rasmni qayta yuboring", 'chat_id' => $chat_id ]);
      qadam($chat_id,"krasm");
      exit();
  }
}

if(is_numeric($text) and $qadam=='knarx'){
      $na = trim($text);
    $res =   mysqli_query($db, "UPDATE `upload` SET `narx` = '$na' WHERE `id` = 1");
    if($res){
        bot('sendMessage', ['text' => "Kitob nomi?", 'chat_id' => $chat_id,  ]);
      qadam($chat_id,"knom");
      exit(); 
  }else{
     bot('sendMessage', ['text' => "Xatolik, raqamda yozing...", 'chat_id' => $chat_id ]);
      qadam($chat_id,"knarx");
      exit();
  }

}


if($text and $qadam=='knom'){
$sql = "SELECT * FROM `upload` WHERE `id` = 1"; 
  $res= mysqli_query($db,$sql);
  $row = mysqli_fetch_assoc($res);

  $file=$row['file'];
  $photo=$row['photo'];
  $narx=$row['narx'];


      $text = filter_var ( trim($text), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    $res =   mysqli_query($db, "INSERT INTO `books` (`id`, `nom`, `narx`, `status`, `rasm`, `file`) VALUES (NULL, '$text', '$narx', 1, '$photo', '$file')");
    if($res){
    	$re= json_encode($res);
        bot('sendMessage', ['text' => "Yaxshi yuklandi", 'chat_id' => $chat_id,'reply_markup'=>$key2  ]);
      qadam($chat_id,"menu");
      mysqli_query($db,"DELETE FROM `upload` WHERE `upload`.`id` = 1");
      exit(); 
  }else{
     bot('sendMessage', ['text' => "Xatolik, lotinchada yozing...", 'chat_id' => $chat_id ]);
      qadam($chat_id,"knom");
      exit();
  }

}




?>