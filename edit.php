<?php
    if (!isset($_GET['method'])){
        die("go away!!");
    }
    header('Content-Type:application/json');
    require_once ('lazy.php');

    $success_message = json_encode(array('status'=>"修改成功"));
    $fail_message_arr = json_encode(array('status'=>"修改失败"));

    if ($method == 'changeUrlData') {
        $json_data = file_get_contents('content.json');
        $json_arr = json_decode($json_data, true);
        $id = isThisArr('pages', $_GET);
        $new_arr = array(
            'name'=>$name,
            'url'=>$url,
            'sort'=>$sort,
            'description'=>$description
        );
        $json_arr['pages'][$id] = $new_arr;
        if (file_put_contents('content.json', json_encode($json_arr, JSON_UNESCAPED_UNICODE))){
            echo $success_message;
        }else{
            echo $fail_message_arr;
        }
}

    if ($method == 'deletePage') {
        $json_data = file_get_contents('content.json');
        $json_arr = json_decode($json_data, true);
        $id = isThisArr2('pages', $_GET);
        $json_arr['pages'] = array_diff_key($json_arr['pages'], [$id=>"a"]);
        file_put_contents('content.json', json_encode($json_arr, JSON_UNESCAPED_UNICODE));
        echo $success_message;
    }

    if ($method == 'getData') {
        $json_data = file_get_contents('content.json');
        $json_arr = json_decode($json_data, true);
//        print_r($json_arr);
        echo json_encode($json_arr);
        }

    if ($method == 'addPage'){
        if (isset($_GET['name']) && isset($_GET['url']) && isset($_GET['description']) && isset($_GET['sort']) && $name != ""&& $url != ""&& $description != ""&& $sort != ""){
            if (addPage($name, $url, $description, $sort)){
                echo $success_message;
            }else{
                echo $fail_message_arr;
            }
        }else{
            echo json_encode(array(
                'status'=>'fail!请补齐信息',
            ));
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
                    'status'=>'fail!repeated WebPage!',
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

    function isThisArr($where,$content){
        $json_data = file_get_contents('content.json');
        $json_arr = json_decode($json_data, true);
        foreach ($json_arr[$where] as $k => $v){
            foreach ($content as $kk => $vv){
                if (strpos(serialize($v), $vv)){
                    return $k;
                }
            }
        }
        return false;
    }

    function isThisArr2($where,$content){
        $json_data = file_get_contents('content.json');
        $json_arr = json_decode($json_data, true);
        $i = 0;
        foreach ($json_arr[$where] as $k => $v){
            foreach ($content as $kk => $vv){
                if (strpos(serialize($v), $vv)){
                    $i++;
                }
                if ($i == 4){return $k;}
            }
            $i = 0;
        }
        return false;
}