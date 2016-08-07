jQuery(document).ready(function ($) {


    const
        saButton = document.getElementById("addServiceAreaButton"),
        zipCode = document.getElementById("zipCode"),
        zipRadius = document.getElementById("zipRadius"),
        removeButton = document.getElementsByClassName("removeButton");

    // Remove Buttons
    function removeButtonCheck() {
        for (var i = 0; i < removeButton.length; i++) {
            removeButton[i].addEventListener('click', function () {

                console.log("clicked!", this.value);
                var zipRow = document.getElementById(this.value);
                $.ajax({
                    url: ajaxurl,
                    type: "POST",
                    dataType: "json",
                    // async: false,
                    // crossDomain: true,
                    data: {
                        "action": "remove_zips",
                        "zip_code": this.value,
                        "security": SERVICE_AREA.security
                    },
                    beforeSend: function () {
                        console.log("before")
                    },
                    success: function (response) {
                        //console.log(SERVICE_AREA.success);
                        $("#serviceAreaMessage").remove();
                        zipRow.remove();
                        $("#wpbody h1").after("<div id='serviceAreaMessage' class='notice notice-success'><p>Success</p></div>");

                    },
                    error: function (request, status, error) {
                        console.log("Error");
                        $("#serviceAreaMessage").remove();
                        console.log(SERVICE_AREA.error);
                        console.log(request.responseText);
                        $("#wpbody h1").after("<div id='serviceAreaMessage' class='notice notice-error'><p>" + request.responseText + "</p></div>");
                    }
                })

            });
        }
    }
    removeButtonCheck();

    function getTheZipCodes(zipCode) {
        if (localStorage.getItem("theZipCodes") === null) {
            console.log("No localStorage yet, requesting");
        }
        else {
            console.log("Killing Old localStorage");
            localStorage.removeItem("theZipCodes");
        }

        console.log("Looking for Zip:", zipCode);
        var clientKeyJs = "js-WT0k29FD4av1VcIhRv0XvT9O2AhYU4xQXyb0HQDM19OWmwed2ebx7lmaVD8PIGGG";
        var apiURL = "https://www.zipcodeapi.com/rest/" + clientKeyJs + "/radius.json/" + zipCode + "/" + zipRadius.value + "/mile";
        var xhr = new XMLHttpRequest();
        xhr.open('GET', encodeURI(apiURL));
        xhr.onload = function (zipCode) {
            if (xhr.status === 200) {
                var info = JSON.parse(xhr.responseText);
                var theZipCodes = [];
                for (var key in info.zip_codes) {
                    theZipCodes.push(info.zip_codes[key].zip_code);
                }
                localStorage.setItem("theZipCodes", theZipCodes);
                var stringZipCodes = theZipCodes.toString();
                console.log("We've got the zip codes!", stringZipCodes);
                zipCode = document.getElementById("zipCode");

                $.ajax({
                    url: ajaxurl,
                    type: "POST",
                    dataType: "json",
                    // async: false,
                    // crossDomain: true,
                    data: {
                        "action": "save_zips",
                        "zip_code": zipCode.value,
                        "zip_matches": theZipCodes.toString(),
                        "zip_radius": zipRadius.value,
                        "security": SERVICE_AREA.security
                    },
                    beforeSend: function () {
                        console.log("before")
                    },
                    success: function (response) {
                        //console.log(SERVICE_AREA.success);
                        $("#serviceAreaMessage").remove();

                        $("tbody#serviceAreas tr:last-of-type").after("<tr id='" + zipCode.value + "'><td class='remove-service_area'><button class='dashicons dashicons-no-alt button removeButton' value='" + zipCode.value + "'></button></td><td class='row-title'><label for='tablecell'>" + zipCode.value + "</label></td><td>" + zipRadius.value + "</td><td style='word-break: break-word;'><p>" + theZipCodes.toString() + "</p></td></tr>");
                        removeButtonCheck();
                        $("#wpbody h1").after("<div id='serviceAreaMessage' class='notice notice-success'><p>Success</p></div>");
                        /*
                         if (true === response.success) {

                         console.log("Success");
                         $("#wpbody h1").after("<div id='serviceAreaMessage' class='notice notice-success'><p>Success</p></div>");
                         }
                         else {
                         console.log("Failure");
                         $("#wpbody h1").after("<div id='serviceAreaMessage' class='notice notice-error'><p>Error</p></div>");
                         }
                         */
                    },
                    error: function (request, status, error) {
                        console.log("Error");
                        $("#serviceAreaMessage").remove();
                        console.log(SERVICE_AREA.error);
                        console.log(request.responseText);
                        $("#wpbody h1").after("<div id='serviceAreaMessage' class='notice notice-error'><p>" + request.responseText + "</p></div>");
                    }
                })

            }
            else {
                var responseText = "Request failed.  Returned status of " + xhr.status + ". (Possibly an invalid zip code)";
                console.log(responseText);
                $("#wpbody h1").after("<div id='serviceAreaMessage' class='notice notice-error'><p>" + responseText + "</p></div>");
            }
        };
        xhr.send();
    }

    function getLocalStorage(name) {
        retrievedObject = localStorage.getItem(name);
        console.log("Get local storage function:", retrievedObject);
        return retrievedObject;
    }

    // On Click Event for Ajax Call
    saButton.addEventListener("click", function () {
        event.preventDefault();
        getTheZipCodes(zipCode.value);
    });




});

