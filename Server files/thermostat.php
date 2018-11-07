<?php
$vent1temp = file_get_contents('vent1.txt'); //get the vent1 temp from vent1.txt
$vent2temp = file_get_contents('vent2.txt'); //get the vent2 temp from vent2.txt

//get the settings from file
$settingsArray = json_decode(file_get_contents("settingsArray.json"));

//get the settings from the array and set them to variables to make it easier to read
$idealTemp = $settingsArray[0];
$refresh = $settingsArray[1];
$vent1switch = $settingsArray[2];
$vent2switch = $settingsArray[3];

$vent1diff = abs($vent1temp - $idealTemp); //determine difference between vent1 and ideal, absolute value
$vent2diff = abs($vent2temp - $idealTemp); //determine difference between vent2 and ideal, absolute value

//determine what mode its in for the "what are the vents doing" table on the web page
if ($vent1switch == "vent1auto"){
    $vent1mode="Auto";
}else{
    $vent1mode="Manual";
}

//determine what mode its in for the "what are the vents doing" table on the web page
if ($vent2switch == "vent2auto"){
    $vent2mode="Auto";
}else{
    $vent2mode="Manual";
}

//switch for first vent to set and determine position
switch ($vent1switch) {
    //automatic mode determine vent position and update file
    case "vent1auto":
        if ($vent2diff > ($vent1diff + 5) && $vent2diff < ($vent1diff + 10)) {
            $vent1pos = "half-closed";
            $writeTofile = file_put_contents('vent1pos.txt', 4);
        } else if ($vent2diff > ($vent1diff + 10)) {
            $vent1pos = "closed";
            $writeTofile = file_put_contents('vent1pos.txt', 9);
        } else {
            $vent1pos = "open";
            $writeTofile = file_put_contents('vent1pos.txt', 0);
        }
        break;
    //manual mode open
    case "vent1open":
        $vent1pos = "open";
        $writeTofile = file_put_contents('vent1pos.txt', 0);
        break;
    //manual mode half
    case "vent1half":
        $vent1pos = "half-closed";
        $writeTofile = file_put_contents('vent1pos.txt', 4);
        break;
    //manual mode clsed
    case "vent1closed":
        $vent1pos = "closed";
        $writeTofile = file_put_contents('vent1pos.txt', 9);
        break;
}//end vent 1 switch

//switch for secnd vent to set and determine position
switch ($vent2switch) {
    //auto mode
    case "vent2auto":
        //determine vent2 position and write to file
        if ($vent1diff > ($vent2diff + 5) && $vent1diff < ($vent2diff + 10)) {
            $vent2pos = "half-closed";
            $writeTofile = file_put_contents('vent2pos.txt', 4);
        } else if ($vent1diff > ($vent2diff + 10)) {
            $vent2pos = "closed";
            $writeTofile = file_put_contents('vent2pos.txt', 9);
        } else {
            $vent2pos = "open";
            $writeTofile = file_put_contents('vent2pos.txt', 0);
        }
        break;
    case "vent2open":
        $vent2pos = "open";
        $writeTofile = file_put_contents('vent2pos.txt', 0);
        break;
    case "vent2half":
        $vent2pos = "half-closed";
        $writeTofile = file_put_contents('vent2pos.txt', 4);
        break;
    case "vent2closed":
        $vent2pos = "closed";
        $writeTofile = file_put_contents('vent2pos.txt', 9);
        break;
}//end vent2switch

?>
<!DOCTYPE html>
<!--##########################################

credit given where credit due:

This page is based on the bootstrap dashboard example: http://getbootstrap.com/getting-started/#examples

this page uses the Just gauge plugin: http://justgage.com/

this page uses Bootstrap switch plugin: http://www.bootstrap-switch.org/


##############################################-->
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="refresh" content="<?php echo $refresh ?>">
        <title>Thermostat for Smart Vents</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/dashboard.css" rel="stylesheet">
        <link href="css/bootstrap-switch.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/raphael-2.1.4.min.js"></script>
        <script src="js/justgage.js"></script>
        <script src="js/bootstrap-switch.js"></script>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    <h2 class="page-header">Smart-Vent Dashboard</h2>

                    <div class="row placeholders">
                        <div class="col-xs-6 col-sm-3 placeholder">
                            <div id="vent1" class="200x160px">
                                <script>
                                    var g = new JustGage({
                                        id: "vent1",
                                        value: <?php echo $vent1temp ?>,
                                        min: 0,
                                        max: 120,
                                        title: "",
                                        label: "",
                                        levelColors: [
                                            "#33D8FF",
                                            "#92E395",
                                            "#FE1405"
                                        ]
                                    });
                                </script>
                            </div>
                            <h4>Vent 1</h4>
                            <span class="text-muted">Basement bedroom</span>
                        </div>
                        <div class="col-xs-6 col-sm-3 placeholder">
                            <div id="ideal" class="400x320px">
                                <script>
                                    var g1 = new JustGage({
                                        id: "ideal",
                                        value: <?php echo $idealTemp ?>,
                                        min: 0,
                                        max: 120,
                                        title: "",
                                        label: "",
                                        levelColors: [
                                            "#33D8FF",
                                            "#92E395",
                                            "#FE1405"
                                        ]
                                    });
                                </script>
                            </div>
                            <h4>Ideal</h4>
                            <span class="text-muted">Your thermostat setting</span>
                        </div>
                        <div class="col-xs-6 col-sm-3 placeholder">
                            <div id="vent2" class="200x160px">
                                <script>
                                    var g2 = new JustGage({
                                        id: "vent2",
                                        value: <?php echo $vent2temp ?>,
                                        min: 0,
                                        max: 120,
                                        title: "",
                                        label: "",
                                        levelColors: [
                                            "#33D8FF",
                                            "#92E395",
                                            "#FE1405"
                                        ]
                                    });
                                </script>
                            </div>
                            <h4>Vent 2</h4>
                            <span class="text-muted">Upstairs bedroom</span>
                        </div>
                    </div>

                    <h2 class="sub-header">What are the vents doing:</h2>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Vent #</th>
                                    <th>Difference from ideal</th>
                                    <th>Vent Position</th>
                                    <th>Mode</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Vent 1</td>
                                    <td><?php echo $vent1diff ?></td>
                                    <td><?php echo $vent1pos ?></td>
                                    <td><?php echo $vent1mode ?></td>

                                </tr>
                                <tr>
                                    <td>Vent 2</td>
                                    <td><?php echo $vent2diff ?></td>
                                    <td><?php echo $vent2pos ?></td>
                                    <td><?php echo $vent2mode ?></td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
    </body>
</html>
