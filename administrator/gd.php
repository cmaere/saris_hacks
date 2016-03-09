<?php 
$im  =  ImageCreate ( 10,  200 );   
$red  =  ImageColorAllocate ( $im,  255,  0,  0 );   
$white  =  ImageColorAllocate ( $im ,  255,  255,  255 );   
$blue  =  ImageColorAllocate ( $im ,  0 ,  0 ,  255 );   
$gray  =  ImageColorAllocate ( $im , 0xC0, 0xC0 , 0xC0 );   

ImageFill ( $im , 0 , 0 , $gray );   

ImageFilledRectangle ( $im , 0 ,$bluehg, 3, 200, $blue );   

ImageFilledRectangle ( $im , 6, $redhg, 10, 200, $red );   

ImageGIF ( $im ); 

?> 