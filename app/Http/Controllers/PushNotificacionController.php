<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use \App\ADMPARAMETROBO;

class PushNotificacionController extends Controller
{
    
    public function NewItem(string $item){
        $parametros = ADMPARAMETROBO::first();
        $servek = "AAAA5ytqw8A:APA91bHrZeoeaAUZ342am5YwqEBz5SfWkW5Cvl6tMMPxu1KTgt85Et1ICPyI4G6f1IkMFqo6vu7Mss7d0h0djZ3-7FDMQ3OWflhkw3vavSgAF5aT-mJKyh-Bq8bLbRAdz5Uh9Po2fYK7";
        $topic = $parametros->ruc;
        $menss =  "Se Agregó: ".$item." al Inventario.";
        $this->sendNotification("ADMGO - Nuevo Producto", $menss , ["NombreITEM" => "Item CAfe"], trim($topic), $servek);
        Log::info("Notificacion Push: ",['ITEM'=>$item]);
        return response()->json('Se agrego ' .$item);
    }

    public function NewItem2(){
        //Testing Items Notifications Google FMS.
        $parametros = ADMPARAMETROBO::first();
        $item = "Iphone 12";
        $servek = "AAAA5ytqw8A:APA91bHrZeoeaAUZ342am5YwqEBz5SfWkW5Cvl6tMMPxu1KTgt85Et1ICPyI4G6f1IkMFqo6vu7Mss7d0h0djZ3-7FDMQ3OWflhkw3vavSgAF5aT-mJKyh-Bq8bLbRAdz5Uh9Po2fYK7";
        $topic = $parametros->ruc;
        $menss =  "Esta es una Notificación de prueba.";
        $this->sendNotification("ADMGO - Notificacin Test", $menss , ["NombreITEM" => "Item CAfe"], trim($topic), $servek);
        Log::info("Notificacion Push: ",['ITEM'=>$item]);
        return response()->json('Se agrego ' .$item);
    }


    public function sendNotification($title = "", $body = "", $customData = [], $topic = "", $serverKey = ""){
        if($serverKey != ""){
            ini_set("allow_url_fopen", "On");
            $data = 
            [
                "to" => '/topics/'.$topic,
                "notification" => [
                    "body" => $body,
                    "title" => $title,
                ],
                "data" => $customData
            ];

            $options = array(
                'http' => array(
                    'method'  => 'POST',
                    'content' => json_encode( $data ),
                    'header'=>  "Content-Type: application/json\r\n" .
                                "Accept: application/json\r\n" . 
                                "Authorization:key=".$serverKey
                )
            );

            $context  = stream_context_create( $options );
            $result = file_get_contents( "https://fcm.googleapis.com/fcm/send", false, $context );
            return json_decode( $result );
        }
        return false;
    }
}
