<?php
define(ROOT_PATH, dirname(__FILE__));
function endsWith($haystack, $needle)
{
        return $needle === "" || substr($haystack, -strlen($needle)) == $needle;
}
$DEBUGGING = endsWith(ROOT_PATH,"-dev");

$consoles = array(
    array("ps3", "PlayStation 3", "https://www.uvm.edu/techteam/playstation-3/"),
    array("ps4", "PlayStation 4", "https://www.uvm.edu/techteam/playstation-4/"),
    array("xbox360", "Xbox 360", "https://www.uvm.edu/techteam/xbox-360/"),
    array("xboxone", "Xbox One", "https://www.uvm.edu/techteam/xbox-one-locate-your-wired-mac-address/"),
    array("appletv", "Apple TV", "https://www.uvm.edu/techteam/apple-tv/"),
    array("wii", "Wii", "https://www.uvm.edu/techteam/wii/"),
    array("wiiu", "Wii U", "https://www.uvm.edu/techteam/wii-u/"),
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
    <script src="js/jquery.scrollTo.min.js"></script>

    <!--[if lt IE 9]>
        <script src="js/html5shiv.min.js"></script>
        <script src="js/respond.min.js"></script>
    <![endif]-->
    </head>
    <body>
        <div class="jumbotron">
            <img src="img/techteam_logo3.png">
            <h1>Network Registration</h1>
        </div>
        <div class="container">
            <form role="form" action="index.php" method="post" class="form-horizontal netregform container">
                <div class="form-group">
                    <div class="col-sm-1">
                        <h1>1.</h1>
                    </div>
                    <div class="col-sm-11">
                        <h3>Notice: This form will <em>ONLY</em> allow you to connect your game or media console through a <em>WIRED</em> connection.</h3>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-5"> </div>
                    <div class="col-sm-7">
                        <a class="btn btn-default btn-lg" id="btnWiredAgree">I understand</a>
                    </div>
                </div>
                <div class="form-group" id="groupSelectConsole">
                    <div class="col-sm-1">
                        <h1>2.</h1>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" for="inputConsoleType">Pick your console from the list to the right.</label>
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
                        <h1>2.5.</h1>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label" for="inputOtherConsole">Please specify.</label>
                    </div>
                    <div class="col-sm-7">
                        <input type="text" class="form-control input-lg" id="inputOtherconsole" name="otherconsole">
                    </div>
                </div>
                <div class="form-group" id="groupMacAddress">
                    <div class="alert alert-info col-sm-12" id="machelp"></div>
                    <div class="col-sm-1">
                        <h1>3.</h1>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" for="inputMacAddress">Enter the <em>wired</em> MAC address of the console.</label>
                    </div>
                    <div class="col-sm-7">
                        <input type="text" class="form-control input-lg" id="inputMacAddress" name="macaddress">
                    </div>
                    <div class="alert alert-success col-sm-12" role="alert">Looks like a valid MAC address!</div>
                    <div class="alert alert-warning col-sm-12" role="alert">The MAC address looks valid, but isn't long enough.</div>
                    <div class="alert alert-danger col-sm-12" role="alert">
                        <p>The MAC address you entered is invalid. MAC addresses consist of six pairs of numbers 0-9 and letters a-f. Call 802-656-2604 for help with this form.
                    </div>
                </div>
                <div class="form-group" id="groupSubmitButton">
                    <div class="col-sm-1">
                        <h1>4.</h1>
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
$macaddress = str_replace(" ", "", $macaddress);
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
$fromaddress = $_SERVER['WEBAUTH_LDAP_MAIL']; // This field doesn't exist on silk yet
if (empty($fromaddress)) { // Fallback in case that field did not get populated (it will not on Silk)
    $fromaddress = $_SERVER['WEBAUTH_USER'] . "@uvm.edu"; // Use netid@uvm.edu
}
$headers = <<<EOT
MIME-Version: 1.0
Content-type: text/html; charset=iso-8859-1
From: {$fromaddress}
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

mail($to, $subject, $message, $headers);
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
    <script src="js/jquery.scrollTo.min.js"></script>

    <!--[if lt IE 9]>
        <script src="js/html5shiv.min.js"></script>
        <script src="js/respond.min.js"></script>
    <![endif]-->
    </head>
    <body>
        <div class="jumbotron">
            <img src="img/techteam_logo3.png">
            <h1>Network Registration</h1>
        </div>
        <div class="container">
            <div class="page-header">
                <h1>Request submitted.</h1>
            </div>
            <h5>You will receive a confirmation email shortly stating that we have received your request.</h5>
            <h5>Please allow up to one business day for the process to complete. You will receive a second email when this has occurred.</h5>
            <h5>Direct all questions to the UVM Tech Team at (802)656-2604.</h5>
        </div>
    </body>
</html>

<?php
}
?>
