<?php

foreach(array('_GET','_POST') as $_request){
    foreach ($$_request as $_k => $_v){
        ${$_k} = $_v;
    }
}