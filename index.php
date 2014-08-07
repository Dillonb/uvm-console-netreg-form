<?php
define(ROOT_PATH, dirname(__FILE__));
function endsWith($haystack, $needle)
{
        return $needle === "" || substr($haystack, -strlen($needle)) == $needle;
}
$DEBUGGING = endsWith(ROOT_PATH,"-dev");

$consoles = array(
    array("ps3", "PlayStation 3", "http://uvm.edu/address/to/ps3/mac/address/help"),
    array("ps4", "PlayStation 4", "http://"),
    array("xbox360", "Xbox 360", "http://"),
    array("xboxone", "Xbox One", "http://"),
    array("appletv", "Apple TV", "http://"),
    array("other", "Other", ""),
);
?>
<?php
if ($_SERVER['REQUEST_METHOD'] == "GET") {
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UVM Game Console Registration</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/netregform.js"></script>

    <!--[if lt IE 9]>
        <script src="js/html5shiv.min.js"></script>
        <script src="js/respond.min.js"></script>
    <![endif]-->
    </head>
    <body>
        <div class="jumbotron">
            <img src="img/techteam_logo3.png">
            <h1>Netreg Form</h1>
        </div>
        <div class="container">
            <form role="form" action="index.php" method="post" class="form-horizontal netregform container">
                <div class="form-group">
                    <div class="col-sm-1">
                        <h1>1.</h1>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" for="inputConsoleType">Type of Console (Xbox One, Apple TV, PS4, etc)</label>
                    </div>
                    <div class="col-sm-7">
                        <select size="<?php print count($consoles); ?>" class="form-control select-lg" id="inputConsoleType" name="consoletype">
                            <?php
                            foreach ($consoles as $console)
                            {
                                print <<<EOT
<option value="{$console[0]}" data-machelp="{$console[2]}">{$console[1]}</option>
EOT;
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group" id="groupOtherConsole">
                    <div class="col-sm-2">
                        <h1>1.5.</h1>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label" for="inputOtherConsole">Please specify.</label>
                    </div>
                    <div class="col-sm-7">
                        <input type="text" class="form-control input-lg" id="inputOtherconsole" name="otherconsole">
                    </div>
                </div>
                <div class="form-group" id="groupMacAddress">
                    <div class="col-sm-1">
                        <h1>2.</h1>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" for="inputMacAddress"><em>Wired</em> MAC address of the console (see the help below)</label>
                    </div>
                    <div class="col-sm-7">
                        <input type="text" class="form-control input-lg" id="inputMacAddress" name="macaddress">
                    </div>
                    <div class="alert alert-success col-sm-12" role="alert">Looks like a valid MAC address!</div>
                    <div class="alert alert-warning col-sm-12" role="alert">The MAC address looks valid, but isn't long enough.</div>
                    <div class="alert alert-danger col-sm-12" role="alert">
                        <p>The MAC address you entered is invalid. MAC addresses consist of six pairs of numbers 0-9 and letters a-f. Call 802-656-2604 for help with this form.
                        <p id="machelp"></p>
                    </div>
                </div>
                <div class="form-group" id="groupSubmitButton">
                    <div class="col-sm-1">
                        <h1>3</h1>
                    </div>
                    <div class="col-sm-4"></div>
                    <div class="col-sm-7">
                        <input type="submit" class="btn btn-default btn-lg">
                    </div>
                </div>
            </form>
        </div>

    </body>
</html>
<?php
}
elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
$consoletype = $_REQUEST['consoletype'];

$otherconsole = $_REQUEST['otherconsole'];

$macaddress = $_REQUEST['macaddress'];
$macaddress = preg_replace("/[^\w\d ]/ui", '', $macaddress);
$macaddress = implode(str_split($macaddress,2),"-");
$macaddress = strtoupper($macaddress);

$oui_file = file("oui.txt");
$oui = array();

foreach ($oui_file as $line) {
    $line = explode(" ",$line,2);
    $oui[$line[0]] = $line[1];
}
$macaddress_oui = substr($macaddress, 0, 8);
if (array_key_exists($macaddress_oui, $oui)) {
    $macaddress_oui = $oui[$macaddress_oui];
}
else {
    $macaddress_oui = "UNKNOWN";
}

$date = date("r");

$headers = <<<EOT
MIME-Version: 1.0
Content-type: text/html; charset=iso-8859-1
From: {$_SERVER['WEBAUTH_LDAP_MAIL']}
EOT;
$subject = "Netreg request from " . $_SERVER['WEBAUTH_USER'];

$message = <<<EOT
<p>Request submitted at {$date} by user {$_SERVER['WEBAUTH_LDAP_CN']}</p>
<p>NetID: {$_SERVER['WEBAUTH_USER']}</p>
<p>Console Type: {$consoletype}</p>
<p>Specify: {$otherconsole}</p>
<p>MAC address: {$macaddress}</p>
<p>Manufacturer: {$macaddress_oui}</p>
EOT;

if ($DEBUGGING) {
    $to = "netregformdebug@dillonbeliveau.com";
}
else {
    $to = "helpline@uvm.edu";
}

mail("netregformdebug@dillonbeliveau.com", $subject, $message, $headers);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UVM Game Console Registration</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/netregform.js"></script>

    <!--[if lt IE 9]>
        <script src="js/html5shiv.min.js"></script>
        <script src="js/respond.min.js"></script>
    <![endif]-->
    </head>
    <body>
        <pre>
<?php
print $message;
?>
        </pre>
    </body>
</html>

<?php
}
?>
