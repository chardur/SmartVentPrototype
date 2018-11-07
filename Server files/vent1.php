<?php

/*************************************************************
 * 
 * Credit given where credit due:
 * this is based on the tutorial by AllAboutEE https://www.youtube.com/watch?v=q02f4sPghSo&feature=youtu.be
 * 
 ***********************************************************/

//read the vent temp to variable from the GET request sent via wifi to the server
$vent1temp = $_GET['vent1'];

//write vent temp to file
$writeTofile= file_put_contents('vent1.txt',$vent1temp);

// if success writing temp to file
if ($writeTofile !=false)
{
    //read the vent position from file, this file is created by thermostat.php after the position is determined
    $vent1pos = file_get_contents('vent1pos.txt');
    //echo the vent position so that the vent can open/close, this is read by the wifi connected to the vent
    echo "vent1pos=".$vent1pos;
}else
{
    echo "failed to write";
}

?>