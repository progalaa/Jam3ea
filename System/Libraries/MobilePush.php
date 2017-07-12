<?php

define("FIREBASE_API_KEY", "AAAATqdKNRU:APA91bGCSe4BwiT5yJShVU8qXIRLR93R4q4cutvvQtLwpq-PceDMN-0hY96YxVVGP512TWy4dwrWkUzcYTMPCRyDLXohbzndhOZtTMlQ_aWjcS15VFMkQRBNYjFEXP5zghT_A5e8IH5N");

class MobilePush {

    function __construct() {

    }

    function Android($DevicesTokens, $ID, $Title, $Body) {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $fields = array(
            'to' => $DevicesTokens,
            // in message case send data only
            'data' => array(
                'id' => $ID,
                'title' => $Title,
                'body' => $Body
            )
        );

        $headers = array(
            'Authorization: key=' . FIREBASE_API_KEY,
            'Content-Type: application/json'
        );
// Open connection
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
//        if ($result === FALSE) {
//            die('Curl failed: ' . curl_error($ch));
//        }
// Close connection
        curl_close($ch);
        \var_dump($result);exit;
        return $result;
    }

    function IOS($DeviceToken, $ID, $Title, $Body) {
        header('Content-type: application/json');
////////////////////////////////////////////////////////////////////////////////
        $PassPhrase = '123456';
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', BASE_DIR . '__Mobile/dev.pem');
        stream_context_set_option($ctx, 'ssl', 'passphrase', $PassPhrase);
//        stream_context_set_option($ctx, 'ssl', 'cafile', BASE_DIR . '__Mobile/Certification_Authority.cer');
// Open a connection to the APNS server
        // Production
//        $fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
        // Testing
        $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

//        if (!$fp)
//            exit("Failed to connect: $err $errstr" . PHP_EOL);
//        echo 'Connected to APNS' . PHP_EOL;
// Create the payload body
        $body['aps'] = array(
            'id' => $ID,
            'alert' => $Body,
            'sound' => 'default'
        );

// Encode the payload as JSON
        $payload = json_encode($body);
// Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $DeviceToken) . pack('n', strlen($payload)) . $payload;

// Send it to the server
//        $result =
        fwrite($fp, $msg, strlen($msg));

//        if (!$result)
//            echo 'Message not delivered' . PHP_EOL;
//        else
//            echo 'Message successfully delivered' . PHP_EOL;
// Close the connection to the server
        fclose($fp);
    }

}
