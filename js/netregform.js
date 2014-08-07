jQuery(document).ready(function($) {
        var regex_macaddr = /^(?:[a-fA-F0-9]{2}\W?){6}$/;
        var regex_incomplete_macaddr = /^(?:[a-fA-F0-9]{2}\W?){0,5}[a-fA-F0-9]?$/;
        function setStatus(element, state) {
            var desiredClass = "has-" + state;
            var classes = ["has-error", "has-warning", "has-success"];

            for (var i = 0; i <= classes.length; i++) {
                if (classes[i] != desiredClass) {
                    if (element.hasClass(classes[i])) { element.removeClass(classes[i]); }
                }
            }
            if (desiredClass != "has-") {
                if (!element.hasClass(desiredClass)) { element.addClass(desiredClass); }
            }
        }
        $("#inputConsoleType").change(function() {
                $("#groupMacAddress").fadeIn("slow");
                if ($("#inputConsoleType").val() == "other") {
                    $("p#machelp").html('Insert a message here for when they pick "Other"');
                    $("#groupOtherConsole").fadeIn("slow");
                }
                else {
                    $("#groupOtherConsole").fadeOut("slow");
                    var selectedOption = $("#inputConsoleType option:selected");
                    $("p#machelp").html('Click <a href="' + selectedOption.data("machelp") + '">here</a> for instructions on finding the MAC address for your ' + selectedOption.text() + '.');
                }
            });
        $("#inputMacAddress").keyup(function() {
                var macaddr = $("#inputMacAddress").val();
                if (macaddr == "") {
                    setStatus($("#inputMacAddress"), "");
                }
                else {
                    if (macaddr.match(regex_incomplete_macaddr)) {
                        // Valid but incomplete MAC address
                        setStatus($("#groupMacAddress"), "warning");
                        $("#groupSubmitButton").fadeOut("fast");
                    }
                    else if (macaddr.match(regex_macaddr)) {
                        // Valid MAC address
                        setStatus($("#groupMacAddress"), "success");
                        $("#groupSubmitButton").fadeIn("slow");
                    }
                    else {
                        // Invalid MAC address
                        setStatus($("#groupMacAddress"), "error");
                        $("#groupSubmitButton").fadeOut("fast");
                    }
                }
            });
});
