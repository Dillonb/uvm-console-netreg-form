function stopRKey(evt) {
    var evt = (evt) ? evt : ((event) ? event : null);
    var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
    if (evt.keyCode == 13) {
        alert(node.type);
    }
    if ((evt.keyCode == 13) && (node.type=="text" || node.type=="select-one"))  {return false;}
}

document.onkeypress = stopRKey;

jQuery(document).ready(function($) {
        var regex_macaddr = /^(?:[a-fA-F0-9]{2}\W?){6}$/;
        var regex_incomplete_macaddr = /^(?:[a-fA-F0-9]{2}\W?){0,5}[a-fA-F0-9]?$/;
        function setStatus(element, state) {
            var desiredClass = "has-" + state;
            var classes = ["has-error", "has-warning", "has-success"];
            var changed = false;

            for (var i = 0; i <= classes.length; i++) {
                if (classes[i] != desiredClass) {
                    if (element.hasClass(classes[i])) {
                        element.removeClass(classes[i]);
                        changed = true;
                    }
                }
            }
            if (desiredClass != "has-") {
                if (!element.hasClass(desiredClass)) {
                    element.addClass(desiredClass);
                    changed = true;
                }
            }
            return changed;
        }
        $("#inputConsoleType").change(function() {
                $("#groupMacAddress").fadeIn("slow");
                if ($("#inputConsoleType").val() == "other") {
                    $("#machelp").html('We suggest searching the internet for help with finding the MAC address for your device.');
                    $("#groupOtherConsole").fadeIn("slow");
                }
                else {
                    $("#groupOtherConsole").fadeOut("slow");
                    var selectedOption = $("#inputConsoleType option:selected");
                    $("#machelp").html('Click <a href="' + selectedOption.data("machelp") + '" target="_blank">here</a> for instructions on finding the MAC address for your ' + selectedOption.text() + '.');
                }
                $.scrollTo($("#groupMacAddress"), 800);
            });
        $("#inputMacAddress").keyup(function() {
                var macaddr = $("#inputMacAddress").val();
                if (macaddr == "") {
                    setStatus($("#inputMacAddress"), "");
                }
                else {
                    if (macaddr.match(regex_incomplete_macaddr)) {
                        // Valid but incomplete MAC address
                        if (setStatus($("#groupMacAddress"), "warning")) {
                            $("#groupSubmitButton").fadeOut("fast");
                            $.scrollTo($("#groupMacAddress .alert-warning"), 800);
                        }
                    }
                    else if (macaddr.match(regex_macaddr)) {
                        // Valid MAC address
                        if (setStatus($("#groupMacAddress"), "success")) {
                            $("#groupSubmitButton").fadeIn("slow");
                            $.scrollTo($("#groupSubmitButton"), 800);
                        }
                    }
                    else {
                        // Invalid MAC address
                        if (setStatus($("#groupMacAddress"), "error")) {
                            $("#groupSubmitButton").fadeOut("fast");
                            $.scrollTo($("#groupMacAddress .alert-danger"), 800);
                        }
                    }
                }
            });

        $("#btnWiredAgree").click(function() {
                $("#groupSelectConsole").fadeIn("slow");
                $.scrollTo($('#groupSelectConsole'), 800);
            });
});
