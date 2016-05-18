var casper = require('casper').create(
    {
        verbose: true,
        logLevel: "debug",
        "sslProtocol": "any"
    }
);

var debug = {
    log: function (value) {
        if (typeof value === "object") {
            casper.echo(JSON.stringify(value));
        } else {
            casper.echo(value);
        }
    }
};

if (!casper.cli.has("user")) {
    errorExit("Required parameter `user` not set");
}

if (!casper.cli.has("password")) {
    errorExit("Required parameter `password` not set");
}

var user = casper.cli.get("user");
var password = casper.cli.get("password");

debug.log("=== DEBUGGING ===");
debug.log("Trying to load URL");
casper.start('https://vitodata100.viessmann.com/VD100/VD100/Login/Login.aspx', function () {
//casper.start('http://cheesecake.com/', function() {
    // Load up the page!)
    debug.log("Successfully loaded the page: " + this.getTitle());
    console.log(this.getHTML());

    this.sendKeys('.RTBUser', user);
    this.sendKeys('.RTBPassword', password);
    debug.log("Adding Login Details");
    this.mouseEvent('click', '.RBLogin');
    debug.log("Clicked Login, Waiting for RBOverview to appear");
});

casper.waitForSelector('.RROverview', function () {
    debug.log("Overview Page Successfully Loaded!");
    this.mouseEvent('dblclick', "#ctl00_Content_RROverview_i0_DeviceImage");
    debug.log("Clicked the DeviceImage! Waiting for Boiler Page to Load!");
}, function () {
}, 20000);

casper.waitForSelector('.deviceOverviewTable', function () {
    debug.log("DeviceOverviewTable visible");
    this.click("#ctl00_Content_ActualizeButton_UpdateButton");
    debug.log("Clicked Update!");

    // This should transform the src of the refresh to this:
    // https://vitodata100.viessmann.com/VD100/Images/Commands/Others/CmdReloadLoading.gif
    //debug.log("The following text should be CmdReload)
    //https://vitodata100.viessmann.com/VD100/Images/Commands/16x16/CmdReload.png
}, function () {
    errorExit("Overview Timeout");
}, 30000);

//casper.wait(2000);

casper.waitFor(function check() {
    return this.evaluate(function () {
        return (document.getElementById("ctl00_Content_ActualizeButton_UpdateButton").src == "https://vitodata100.viessmann.com/VD100/Images/Commands/16x16/CmdReload.png");
        //return document.querySelectorAll('ul.your-list li').length > 2;
    });
}, function then() {
    debug.log("Successfully updated (apparently)")
    var listItems = this.evaluate(function () {
        var nodes = document.querySelectorAll('.deviceOverviewTable tr');
        return [].map.call(nodes, function (node) {
            return {
                title: node.children[0].textContent.trim(),
                value: node.children[1].textContent.trim()
            };
        });
    });

    var data = {};
    for (var x = 0; x < listItems.length; x++) {
        data[listItems[x].title] = listItems[x].value;
    }

    successExit(data);
}, function onTimeout(data) {
    errorExit("Update Timeout");
}, 180000);


function successExit(results) {
    debug.log("=== RESULTS ===");
    debug.log({
        "data": results
    });
    casper.exit();
}

function errorExit(description) {
    debug.log("=== RESULTS ===");
    debug.log({
        "error": description
    });
    casper.exit(1);
}

casper.run();
