<?php

/*************************************************************
 * 
 * Credit given where credit due:
 * this is based on the tutorial by AllAboutEE https://www.youtube.com/watch?v=q02f4sPghSo&feature=youtu.be
 * 
 ***********************************************************/

//get the settings posted from dashboard.php, when user clicks update settings
$idealTemp = $_POST['ideal'];
$refresh = $_POST['refresh'];
$vent1switch = $_POST['vent1switch'];
$vent2switch = $_POST['vent2switch'];

//put settings in array
$settingsArray = Array($idealTemp, $refresh, $vent1switch, $vent2switch);
//write settings to json object to be read by thermostat.php
$writeTofile = file_put_contents("settingsArray.json", json_encode($settingsArray));

//if success
if ($writeTofile != false) {
    //you wont see this unless you have a really slow internet connection- you will be redirected next line
    echo "updated";
    //load thermostat.php in the browser window- we dont want to stay on currnet page as it has nothing but "updated"
    header("Location: thermostat.php");
} else {
    echo "failed to write";
}