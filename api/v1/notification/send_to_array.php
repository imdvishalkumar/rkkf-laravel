<?PHP
    function sendMessage($message, $type, $array){
        $content = array(
            "en" => $message
            );
        
        $fields = array(
            'app_id' => "239e5f9a-42ab-4b43-aa03-caefb7f9249b",
            
        'include_player_ids' => $array,
        'data' => array( "type" => $type ),
        'contents' => $content
        );
        
        $fields = json_encode($fields);
        print("\nJSON sent:\n");
        print($fields);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);
        
        return $response;
    }
    function sendNoti($message, $type, $array){        
        $response = sendMessage($message, $type, $array);
        //$playerIdArray = array();
        //array_push( $playerIdArray, "8da064a6-5c46-467a-b87b-479f4dd748e6" ); // oppo id
        //$response = sendMessage("hi", "custom", $playerIdArray);
        $return["allresponses"] = $response;
        $return = json_encode( $return);

        print("\n\nJSON received:\n");
        print($return);
        print("\n");
        return $return;
    }
?>