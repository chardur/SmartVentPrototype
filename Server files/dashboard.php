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
        <meta http-equiv="refresh" content="">
        <title>Thermostat for Smart Vents</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-switch.css" rel="stylesheet">
        <link href="css/dashboard.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/raphael-2.1.4.min.js"></script>
        <script src="js/justgage.js"></script>
        <script src="js/bootstrap-switch.js"></script>
        <script>
            //set the bootstrap switch defaults- the toggle switches size, colors
            $.fn.bootstrapSwitch.defaults.size = 'mini';
            $.fn.bootstrapSwitch.defaults.onColor = 'warning';
            $.fn.bootstrapSwitch.defaults.offColor = 'info';
        </script>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3 col-md-2 sidebar">
                    <ul class="nav nav-sidebar">

                        <li class="active"><a href="#">Settings: <span class="sr-only">(current)</span></a></li>
                        <li><form method="post" action="ventSettings.php" id="settings" target="thermostatframe">
                                <div class="form-group">
                                    <br>
                                    Set ideal temp: (0-120):<br>
                                    <input id="ideal" type="number" name="ideal" min="0" max="120">
                                    <br>
                                    <br>
                                    Refresh rate: (2-999)<br>
                                    <input type="number" name="refresh" min="2" max="999">
                                    <br>
                                    <br>

                                    Vent1 auto mode: <br>
                                    <input value="vent1auto" name="vent1switch" type="radio" class="switch" checked>
                                    <br>
                                    <br>
                                    Vent1 force position:<br>
                                    <input value="vent1open" type="radio" name="vent1switch"  class="switch">: Open<br>
                                    <input value="vent1half" type="radio" name="vent1switch"  class="switch">: Half<br>
                                    <input value="vent1closed" type="radio" name="vent1switch"  class="switch">: Closed<br>
                                    <script>$("[name='vent1switch']").bootstrapSwitch();</script>
                                    <br>
                                    <br>
                                    Vent2 auto mode: <br>
                                    <input value="vent2auto" name="vent2switch" type="radio" class="switch" checked>
                                    <br>
                                    <br>
                                    Vent2 force position:<br>
                                    <input value="vent2open" type="radio" name="vent2switch" class="switch">: Open<br>
                                    <input value="vent2half" type="radio" name="vent2switch" class="switch">: Half<br>
                                    <input value="vent2closed" type="radio" name="vent2switch" class="switch">: Closed<br>
                                    <script>$("[name='vent2switch']").bootstrapSwitch();</script>
                                    <br>
                                    <br>
                                    <input id="submit" type="submit" value="Update Settings" name="submit"/>
                                </div>
                            </form>
                        </li>
                    </ul>
                </div>
                <iframe style="width: 100vw;height: 100vh;position: relative;" src="thermostat.php" frameborder="0" allowfullscreen name="thermostatframe" id="thermostatframe"></iframe>
            </div>
    </body>
</html>