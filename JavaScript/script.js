$(() => {
let server_host = "192.168.0.8:3030/deblocari"; 

    function populateListaMagazine() {
        let msg = {
            status: "Get_Buttons_Lista_Magazine"
        };
	
        //populate lista baza de date
        $.ajax({
            url: "http://"+server_host +"/StoresOpenCloseDatabase/php/storemanager.php",
            type: "post",
            data: msg,
            success: function (response) {
                // you will get response from your php page (what you echo or print)           
                //console.log(response);
                if (response.indexOf("Error: connection failed") != -1) {
                    //$("#open-stores").html("<h2 class='inactive'>Erroare la baza de date SQL</h2>");
                    $("#log-container-data").append("<h2 class='inactive'>Erroare la baza de date SQL</h2>");
                } else
                    $("#open-stores").html(response);

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
                let err_msg = "<div class='storeListItem'><h2 class='inactive'>Problema cu pagina de php storemanager!!!</h2></div>";
                //$("#open-stores").html(err_msg);
                //<p class="log-container-data-item">test</p>
                textStatus = "<p class='log-container-data-item inactive'>populare lista baza de date - " + textStatus + ":" + errorThrown + "</p>";
                $("#log-container-data").append(textStatus);
            }
        });
    }

    function populateListaMagazineDeschise() {
        let msg = {
            status: "Get_Buttons_Lista_Dechisa",
            statusAjax: "Get_Buttons_Lista_Dechisa"
        };
        //populate lista magazine baza de date deschisa
        $.ajax({
            url: "http://"+server_host +"/StoresOpenCloseDatabase/php/storemanager.php",
            type: "post",
            data: msg,
            success: function (response) {
                // you will get response from your php page (what you echo or print)           
                //console.log(response);
                if (response.indexOf("Error: connection failed") != -1) {
                    //$("#close-stores").html("<h2 class='inactive'>Erroare la baza de date SQL</h2>");
                    $("#log-container-data").append("<h2 class='inactive'>Erroare la baza de date SQL</h2>");
                } else
                    $("#close-stores").html(response);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
                let err_msg = "<div class='storeListItem'><h2 class='inactive'>Problema cu pagina de php storemanager!!!</h2></div>";
                //$("#close-stores").append(err_msg);
                //<p class="log-container-data-item">test</p>
                textStatus = "<p class='log-container-data-item inactive'>populare lista baza de date - " + textStatus + ":" + errorThrown + "</p>";
                $("#log-container-data").append(textStatus);
            }
        });

    }

    function popup() {
        document.getElementById("wait-box").style.visibility = "hidden";
    }

    function refreshListsData() {
        document.getElementById("wait-box").style.visibility = "visible";
        setTimeout(popup, 1300);
        setTimeout(populateListaMagazine, 1000);
        setTimeout(populateListaMagazineDeschise, 1000);
    }

    function refreshListsDataWait() {
        document.getElementById("wait-box").style.visibility = "visible";
        setTimeout(popup, 1300);
        setTimeout(populateListaMagazine, 1000);
        setTimeout(populateListaMagazineDeschise, 1000);
    }

    refreshListsData();

    $('body').on('click', '.storeListItem-btn', function () {
        let status = $(this).attr('data-status');
        let id = $(this).attr('data-store-id');
        let store = $(this).attr('data-magazin');
        //$("#log-container-data").append("am intrat pe :" + status);
        if (status === "open") {

            $(this).attr("disabled", "disabled");
            document.getElementById("wait-box").style.visibility = "visible";

            let btn_id = {
                status: 'Update_Store_Status',
                data_status: status,
                store: store,
                data_store_id: id
            };
            $.ajax({
                url: "http://" + server_host + "/StoresOpenCloseDatabase/php/storemanager.php",
                type: "post",
                data: btn_id,
                success: function (response) {
                    //$("#log-container-data").append("am intrat pe :" + response);
                    // you will get response from your php page (what you echo or print) 
                    if (response.indexOf("Error: connection failed") != -1) {
                        if (store === "G10" || store === "G21" || store === "G28" || store === "G52")
                            $(this).attr("disabled", "disabled");
                        //$("#open-stores").html("<h2 class='inactive'>Erroare la baza de date SQL</h2>");
                        $("#log-container-data").append("<h2 class='inactive'>Erroare la baza de date SQL</h2>");
                        refreshListsDataWait();
                    } else if ((response.indexOf("Calculator la distanta Inchis sau problema la baza de date") != -1) || (response.indexOf("Unable to complete network request to host") != -1)) {
                        //$("#open-stores").html("<h2 class='inactive'>Calculator la Distanta nu rapunde!! Contactati IT</h2>");
                        $("#log-container-data").append("<h2 class='inactive'>Calculator magazin:" + store + " la Distanta sau DBA Firebase nu rapunde!! Contactati IT</h2>");
                        //$(this).removeAtr("disable");
                        //$("#log-container-data").append("test ended");
                        refreshListsDataWait();

                    } else {
                        $("#log-container-data").append("<p class='log-container-data-item succes-msg'>Succes: Baza de date deschisa pentru tranzactii magazin:" + store + "</p>");
                        refreshListsDataWait();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                    let err_msg = "<div class='storeListItem'><h2 class='inactive'>Problema cu pagina de php storemanager!!!</h2></div>";
                    //$("#close-stores").html(err_msg);
                    //<p class="log-container-data-item">test</p>
                    textStatus = "<p class='log-container-data-item inactive'>Erroare La Conectare pe baza de date Incercati dinou - " + textStatus + ":" + errorThrown + "</p>";
                    $("#log-container-data").append(textStatus);
                    refreshListsDataWait();
                }
            });
            //refreshListsData();
        }

        if (status === "close") {
            if (store === "G10" || store === "G21" || store === "G28" || store === "G52") {
                $(this).attr("disabled", "disabled");
            } else {
                $(this).attr("disabled", "disabled");
                document.getElementById("wait-box").style.visibility = "visible";

                let btn_id = {
                    status: 'Update_Store_Status',
                    data_status: status,
                    store: store,
                    data_store_id: id
                };

                $.ajax({
                    url: "http://" + server_host + "/StoresOpenCloseDatabase/php/storemanager.php",
                    type: "post",
                    data: btn_id,
                    success: function (response) {
                        // you will get response from your php page (what you echo or print)           
                        if (response.indexOf("Error: connection failed") != -1) {
                            //$("#open-stores").html("<h2 class='inactive'>Erroare la baza de date SQL</h2>");
                            $("#log-container-data").append("<h2 class='inactive'>Erroare la baza de date SQL</h2>");
                            refreshListsDataWait();
                        } else if ((response.indexOf("Calculator la distanta Inchis sau problema la baza de date") != -1) || (response.indexOf("Unable to complete network request to host") != -1)) {
                            //$("#open-stores").html("<h2 class='inactive'>Calculator la Distanta nu rapunde!! Contactati IT</h2>");
                            $("#log-container-data").append("<h2 class='inactive'>Calculator magazin:" + store + " la Distanta sau DBA Firebase nu rapunde!! Contactati IT</h2>");
                            //$(this).removeAtr("disable");
                            refreshListsDataWait();
                        } else {
                            $("#log-container-data").append("<p class='log-container-data-item succes-msg'>Succes: Baza de date deschisa pentru tranzactii magazin:" + store + "</p>");
                            refreshListsDataWait();
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                        let err_msg = "<div class='storeListItem'><h2 class='inactive'>Problema cu pagina de php storemanager!!!</h2></div>";
                        //$("#close-stores").append(err_msg);
                        //<p class="log-container-data-item">test</p>
                        textStatus = "<p class='log-container-data-item inactive'>Erroare La Conectare pe baza de date Incercati dinou - " + textStatus + ":" + errorThrown + "</p>";
                        $("#log-container-data").append(textStatus);
                        refreshListsDataWait();
                    }
                });
            }
            //refreshListsData();
        }
    });
})