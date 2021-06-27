<?php
define('API_KEY','MTU2MjE2NjQyMDpBQUZmMHMtQXZCZnJnNHFxb1BWVF9tWVJ2YmpWNEJtYWZ0TQ');
/*echo "https://api.telegram.org/bot" . API_KEY . "/setwebhook?url=" . $_SERVER['SERVER_NAME'] . "" . $_SERVER['SCRIPT_NAME'];*/

include ('db.php');


function bot($method,$datas=[]){
 $url = "https://api.telegram.org/bot".API_KEY."/".$method;
 $ch = curl_init();
 curl_setopt($ch,CURLOPT_URL,$url);
 curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
 curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
 $res = curl_exec($ch);
 if(curl_error($ch)){
  var_dump(curl_error($ch));
}else{
  return json_decode($res);
}
}


$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
$message_id = $message->message_id;
$chat_id = $message->chat->id;
$text = $message->text;

$username = $message->from->username;
$first_name = $message->from->first_name;

$callback = $update->callback_query;
$username2 = $callback->message->from->username;
$first_name2 = $callback->message->from->first_name;
$chat_id2 = $update->callback_query->message->chat->id;
$message_id2 = $callback->message->message_id;
$data = $update->callback_query->data;
$cqid = $update->callback_query->id;


if(!$chat_id){
  $chat_id=$chat_id2;
  $message_id=$message_id2;
  $username=$username2;
  $first_name=$first_name2;
}

if($chat_id){
  $sql = "SELECT * FROM `users` WHERE `chat_id` = $chat_id"; 
  $res= mysqli_query($db,$sql);
  $row = mysqli_fetch_assoc($res);

  $nomer=$row['nomer'];
  $qadam=$row['qadam'];
  $joy=$row['joy'];
}




$key0 = json_encode([
  'inline_keyboard'=>[
    [['text'=>"Uz", 'callback_data'=>"uz"]],
    [['text'=>"Ru", 'callback_data'=>"ru"]],
  ]]);

$key = json_encode([
  'resize_keyboard'=>true,
  'keyboard'=>[
    [['text'=>"Telefon raqamingizni yuboring",'request_contact'=>true]],
  ]]);

$key2 = json_encode([
  'one_time_keyboard'=>true,
  'resize_keyboard'=>true,
  'keyboard'=>[
    [['text'=>"Search"],['text'=>"Books"]],
    [['text'=>"Info"]],
  ]]);

$key3 = json_encode([
  'inline_keyboard'=>[
    [['text'=>"Search", 'callback_data'=>"1"]],
    [['text'=>"Books", 'callback_data'=>"2"]],
    [['text'=>"Info", 'callback_data'=>"$kurinishsoni"]],
  ]]);


include ('function.php');
include ('admin.php');

if($text=="/start"){

  if(!mysqli_fetch_assoc(mysqli_query($db,"SELECT * FROM `users` WHERE `chat_id` = $chat_id"))){
    bot('sendMessage',[
      'chat_id'=>$chat_id,
      'text'=>'🇺🇿 Xush kelibsiz ❕
      🇷🇺 Добро пожаловать ❕


      ⏹ 🇺🇿 Tilingizni tanlang ❕
      ⏹ 🇷🇺 Выберите язык общения ❕',
      'reply_markup'=>$key0
    ]);
    mysqli_query($db, "INSERT INTO `users` (`id`, `chat_id`, `nomer`, `qadam`, `til`, `joy`) VALUES (NULL, '$chat_id', '0', 'start','0','0')");
    qadam($chat_id,"start");
    exit();

  }else{
   bot('sendMessage',[
    'chat_id'=>$chat_id,
    'text'=>"Xush kelibsiz",
    'reply_markup'=>$key2
  ]);
 }
}


if($message->contact and $qadam=="nomer"){
  $contact = $message->contact->phone_number;
  $sid = $message->contact->user_id;
  if($chat_id==$sid){
    bot('sendMessage',[
      'chat_id'=>$chat_id,
      'text'=>"Xush kelibsiz",
      'reply_markup'=>$key2
    ]);
    bot('deleteMessage',[
      'chat_id'=>$chat_id,
      'message_id'=>$message_id
    ]);
    bot('deleteMessage',[
      'chat_id'=>$chat_id,
      'message_id'=>$message_id-1
    ]);
    nomer($chat_id,$contact);
    qadam($chat_id,"menu");
  }else{
   bot('sendMessage',[
    'chat_id'=>$chat_id,
    'text'=>"Faqat o'zingizning raqamingizni yuboring"
  ]);
   qadam($chat_id,"nomer");
 }

 exit();
}


if($text=="Books" or $data=="keyin" or $data=="oldin" or $data=="Ortga"){

$kurinishsoni = 9;
if($text=="Books"){
  $ytr=$kurinishsoni;
  joy($chat_id,$kurinishsoni);
}
else if($data=="keyin"){
  $ytr=$joy+$kurinishsoni;
  joy($chat_id,$joy+$kurinishsoni);
}
else if($data=="oldin"){
  $ytr=$joy-$kurinishsoni;
  joy($chat_id,$joy-$kurinishsoni);
}
if($data=="Ortga"){
   del($chat_id,$message_id);
   $ytr=$kurinishsoni;
  joy($chat_id,$kurinishsoni);
}


  $sqlqa = "SELECT * FROM `books` WHERE `status` = 1";
  $resqa= mysqli_query($db,$sqlqa);
  $uwe= [];
  while($rowqa = mysqli_fetch_assoc($resqa)){
    $uwe[] = $rowqa;
  }  
  $pav="";
  $uw="";

  $k=0;
  foreach($uwe AS $uw){

    if($k>=$ytr-$kurinishsoni and $k<$ytr){

      $id=$uw['id'];
      $nom=$uw['nom'];
      $narx=$uw['narx'];

      if($pav==""){
       $pav="[{\"callback_data\":\"@$id\",\"text\":\"📘 $nom - $narx so'm\"}]";   

     }
     else{
      $pav.=",[{\"callback_data\":\"@$id\",\"text\":\"📘 $nom - $narx so'm\"}]";  
    }   
  }
  $k+=1;
}


if($ytr-$kurinishsoni==0 and count($uwe)>$kurinishsoni){
 $pav.=",[{\"callback_data\":\"keyin\",\"text\":\"Keyingi\"}]";   
}

if($ytr>$kurinishsoni and $ytr<count($uwe)){
  $pav.=",[{\"callback_data\":\"oldin\",\"text\":\"Oldingi\"},{\"callback_data\":\"keyin\",\"text\":\"Keyingi\"}]";  
}

if($ytr>=count($uwe) and count($uwe)>$kurinishsoni){
 $pav.=",[{\"callback_data\":\"oldin\",\"text\":\"Oldingi\"}]";  
}


$fg="";
if($data=="oldin" or $data=="keyin"){
  $fg="EditMessageText";
}else{
  $fg="sendMessage";
}

$men='{"inline_keyboard":['."$pav".']}';  
bot($fg,[ 
 'chat_id'=>$chat_id,
 'message_id'=>$message_id,
 'text'=>"Tanlang...",
 'reply_markup'=>$men]);

qadam($chat_id,"product");

}










if($data=="uz" and $qadam=="start"){
 bot('sendMessage',[
  'chat_id'=>$chat_id,
  'text'=>'Telefon raqamingizni yuboring...',
  'reply_markup'=>$key
]);
 qadam($chat_id,"nomer");
 til($chat_id,"uz");
 del($chat_id,$message_id);
 exit();
}

if($data=="ru" and $qadam=="start"){
  bot('sendMessage',[
   'chat_id'=>$chat_id,
   'text'=>"Отправьте свой номер телефона...",
   'reply_markup'=>$key,
 ]);
  qadam($chat_id,"nomer");
  til($chat_id,"ru");
  del($chat_id,$message_id);
  exit();
}

if($message->photo){
 $po =  $message->photo[0]->file_id;
  bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"$po"
  ]);
}

if($data[0]=="@" and $qadam=="product"){

$data = str_replace("@", '', $data);
 $sql1 = "SELECT * FROM `books` WHERE `id`=$data"; 
  $res1= mysqli_query($db,$sql1);
  $row1 = mysqli_fetch_assoc($res1);

  $id=$row1['id'];
  $nom=$row1['nom'];
  $narx=$row1['narx'];
  $rasm=$row1['rasm'];

   bot('sendPhoto',[
   'chat_id'=>$chat_id,
   'photo'=>$rasm,
   'caption'=>"Nomi: $nom\nNarx: $narx\n",
   'reply_markup'=>json_encode([
  'inline_keyboard'=>[
    [['text'=>"Sotib olish", 'callback_data'=>"#{$id}"]],
    [['text'=>"Ortga", 'callback_data'=>"Ortga"]],
  ]])
 ]);

   del($chat_id,$message_id);
   qadam($chat_id,"us");
}

if($data[0]=="#" and $qadam=="us"){
bot('answerCallbackQuery',[
     'callback_query_id'=>$cqid,
     'text'=>"Yuklanmoqda...",
     'show_alert'=>false
    ]);

$data = str_replace("#", '', $data);
 $sql1 = "SELECT * FROM `books` WHERE `id`=$data"; 
  $res1= mysqli_query($db,$sql1);
  $row1 = mysqli_fetch_assoc($res1);

  $nom=$row1['nom'];
  $narx=$row1['narx'];
  $file=$row1['file'];

   bot('sendDocument',[
   'chat_id'=>$chat_id,
   'document'=>$file,
   'caption'=>"Nomi: $nom\nNarx: $narx\n",
 ]);

}