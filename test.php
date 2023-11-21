<?php 
function track(){
    static $i = 0;
    $i++;
    echo $i;
}
track();
track();
track();
