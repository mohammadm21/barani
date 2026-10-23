<?php

class mahdilotfi {


   static function install(){
   //  db::insert('options', "`name`,`value`", "'allow_formatso','{\"mp3\":1,\"mp4\":1,\"3gp\":1,\"jpg\":1,\"txt\":1,\"gif\":1,\"png\":1,\"wmv\":1,\"pdf\":1,\"jpeg\":1,\"wma\":1,\"mov\":1}'");
	// db::insert('options', "`name`,`value`", "'bwoptions','{\"chat_report_msg\":[],\"UsersForBann\":[]}'");
	// db::insert('options', "`name`,`value`", "'texttouserpv','سلام به چت خوش آمدید'");
     chatroom::reloadconfig();

}


}

?>
