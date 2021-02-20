<?php
    if (!isset($_GET['method'])){
        die("go away!!");
    }
    header('Content-Type:application/json');
    require_once ('lazy.php');

    $success_message = json_encode(array('status'=>"success"));
    $fail_message_arr = array('status'=>"fail");

    if ($method == 'getData') {
        $json_data = file_get_contents('content.json');
        $json_arr = json_decode($json_data, true);
        echo json_encode($json_arr);
        }

    if ($method == 'addPage'){
        if (isset($_GET['name']) & isset($_GET['url']) & isset($_GET['description']) & isset($_GET['sort'])){
            if (addPage($name, $url, $description, $sort)){
                echo $success_message;
            }else{
                echo json_encode($fail_message_arr);
            }
        }else{
            $message = array(
                'status'=>'fail',
                'reason'=>'something unset!',
                'name'=>isset($_GET['name']),
                'url'=>isset($_GET['url']),
                'description'=>isset($_GET['description']),
                'sort'=>isset($_GET['sort']),
            );
            echo json_encode($message);
        }

    }

    if ($method == 'addSilde'){
        if (isset($_GET['url'])){
            if (addSlide($url)){
                echo $success_message;
            }else{
                echo json_encode($fail_message_arr);
            }
        }
    }

    function addPage($name, $url, $description, $sort){
        $json_data = file_get_contents('content.json');
        $json_arr = json_decode($json_data, true);
        foreach ($json_arr['pages'] as $page){
            if ($page['name'] == $name){
                die(json_encode(array(
                    'status'=>'fail',
                    'reason'=>'repeated WebPage!'
                )));
            }
        }
        $new_arr = array(
            'name'=>$name,
            'url'=>$url,
            'sort'=>$sort,
            'description'=>$description
        );
        array_push($json_arr['pages'], $new_arr);
        if (file_put_contents('content.json', json_encode($json_arr, JSON_UNESCAPED_UNICODE))){
            return true;
        }else{
            return false;
        }
    }

    function addSlide($url){
        $json_data = file_get_contents('content.json');
        $json_arr = json_decode($json_data, true);
        $new_arr = array(
            'url'=>$url,
        );
        array_push($json_arr['slides'], $new_arr);
        if (file_put_contents('content.json', json_encode($json_arr, JSON_UNESCAPED_UNICODE))){
            return true;
        }else{
            return false;
        }
    }