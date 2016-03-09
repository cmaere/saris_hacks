<?php

function LoadData($file)
{
    //Read file lines
    $lines=file($file);
    $data=array();
    foreach($lines as $line)
        $data[]=explode(';',chop($line));
    
    
    
    
    return $data;
}

print_r(LoadData("cha.txt"));



?>