$(() => {


    function populateListaMagazine() {
        let msg = {
            status: "Get_Buttons_Lista_Magazine"
        };
        //populate lista baza de date
        $.ajax({
            url: "http://localhost/StoresOpenCloseDatabase/php/storemanager.php",
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
            url: "http://localhost/StoresOpenCloseDatabase/php/storemanager.php",
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

    refreshListsData();

    $('body').on('click', '.storeListItem-btn', function () {

        let status = $(this).attr('data-status');
        let id = $(this).attr('data-store-id');
        let store = $(this).attr('data-magazin');
        if (status === "open") {
            $(this).attr("disabled", "disabled");
            let btn_id = {
                status: 'Update_Store_Status',
                data_status: status,
                store: store,
                data_store_id: id
            };
            $.ajax({
                url: "http://localhost/StoresOpenCloseDatabase/php/storemanager.php",
                type: "post",
                data: btn_id,
                success: function (response) {
                    // you will get response from your php page (what you echo or print) 
                    if (response.indexOf("Error: connection failed") != -1) {
                        //$("#open-stores").html("<h2 class='inactive'>Erroare la baza de date SQL</h2>");
                        $("#log-container-data").append("<h2 class='inactive'>Erroare la baza de date SQL</h2>");
                    } else if (response.indexOf("Calculator la distanta Inchis sau problema la baza de date") != -1) {
                        //$("#open-stores").html("<h2 class='inactive'>Calculator la Distanta nu rapunde!! Contactati IT</h2>");
                        $("#log-container-data").append("<h2 class='inactive'>Calculator la Distanta sau DBA Firebase nu rapunde!! Contactati IT</h2>");
                        $(this).removeAttr("disabled");
                    } else
                        $("#log-container-data").append("<p class='log-container-data-item succes-msg'>Succes: Baza de date deschisa pentru tranzactii</p>");
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                    let err_msg = "<div class='storeListItem'><h2 class='inactive'>Problema cu pagina de php storemanager!!!</h2></div>";
                    //$("#close-stores").html(err_msg);
                    //<p class="log-container-data-item">test</p>
                    textStatus = "<p class='log-container-data-item inactive'>Erroare La Conectare pe baza de date Incercati dinou - " + textStatus + ":" + errorThrown + "</p>";
                    $("#log-container-data").append(textStatus);
                }
            });
            refreshListsData();
        }

        if (status === "close") {
            $(this).attr("disabled", "disabled");
            let btn_id = {
                status: 'Update_Store_Status',
                data_status: status,
                store: store,
                data_store_id: id
            };
            $.ajax({
                url: "http://localhost/StoresOpenCloseDatabase/php/storemanager.php",
                type: "post",
                data: btn_id,
                success: function (response) {
                    // you will get response from your php page (what you echo or print)           
                    if (response.indexOf("Error: connection failed") != -1) {
                        //$("#open-stores").html("<h2 class='inactive'>Erroare la baza de date SQL</h2>");
                        $("#log-container-data").append("<h2 class='inactive'>Erroare la baza de date SQL</h2>");
                    } else if (response.indexOf("Calculator la distanta Inchis sau problema la baza de date") != -1) {
                        //$("#open-stores").html("<h2 class='inactive'>Calculator la Distanta nu rapunde!! Contactati IT</h2>");
                        $("#log-container-data").append("<h2 class='inactive'>Calculator la Distanta sau DBA Firebase nu rapunde!! Contactati IT</h2>");
                        $(this).removeAttr("disabled");
                    } else
                        $("#log-container-data").append("<p class='log-container-data-item succes-msg'>Succes: Baza de date inchisa pentru tranzactii</p>");
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                    let err_msg = "<div class='storeListItem'><h2 class='inactive'>Problema cu pagina de php storemanager!!!</h2></div>";
                    //$("#close-stores").append(err_msg);
                    //<p class="log-container-data-item">test</p>
                    textStatus = "<p class='log-container-data-item inactive'>Erroare La Conectare pe baza de date Incercati dinou - " + textStatus + ":" + errorThrown + "</p>";
                    $("#log-container-data").append(textStatus);
                }
            });
            refreshListsData();
        }
    });
})
