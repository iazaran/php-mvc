/**
 * Set cookie
 *
 * @param name
 * @param value
 * @param expiresDay
 */
function setCookie(name, value, expiresDay) {
    const d = new Date();
    d.setTime(d.getTime() + (expiresDay * 24 * 60 * 60 * 1000));
    let expires = "expires=" + d.toUTCString();

    document.cookie = name + "=" + value + ";" + expires + ";path=/";
}

/**
 * Get cookie
 *
 * @param name
 * @returns {string}
 */
function getCookie(name) {
    let cookieName = name + "=";
    let ca = document.cookie.split(";");
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === " ") {
            c = c.substring(1);
        }
        if (c.indexOf(cookieName) === 0) {
            return c.substring(cookieName.length, c.length);
        }
    }

    return "";
}

/**
 * Add message to existing ones on chat
 *
 * @param messageHTML
 */
function showMessage(messageHTML) {
    $("#output").append(messageHTML);
}

function handleChat() {
    let webSocket = new WebSocket("ws://localhost:9090");

    webSocket.onopen = function (event) {
        showMessage("<small class='text-success'>Successfully entered the room...</small>");
    };

    webSocket.onmessage = function (event) {
        let data = JSON.parse(event.data);
        showMessage("<p>" + data.message + "</p>");
        $("#message").val("");
    };

    webSocket.onerror = function (event) {
        showMessage("<small class='text-danger'>Problem due to some Error!</p>");
    };

    webSocket.onclose = function (event) {
        showMessage("<small class='text-success'>Connection Closed</small>");
    };

    $("#chat-submit").on("click", function (event) {
        event.preventDefault();
        let messageJSON = {
            name: $("#client-name").val(),
            message: $("#message").val()
        };
        webSocket.send(JSON.stringify(messageJSON));
    });
}

$(document).ready(function () {
    /**
     * Start summernote if needed
     */
    const summernoteBody = $("#body");
    if (summernoteBody.length) {
        summernoteBody.summernote({
            tabsize: 4,
            height: 100
        });
    }

    /**
     * Enable bootstrap toast with options
     */
    const toastElement = $(".toast");
    toastElement.toast({delay: 4000});

    /**
     * Check if message cookie exist to show it
     */
    const message = getCookie("message");
    if (message !== "") {
        toastElement.toast("show");
        $(".toast-body").text(decodeURI(message));
    }

    /**
     * Enable tooltips everywhere
     */
    $(function () {
        $("[data-toggle='tooltip']").tooltip();
    });

    /**
     * Select body to add event listeners
     */
    const body = $("body");

    /**
     * Send form data with Ajax for all forms
     *
     * Consider a route for your form like /blog/create; now use blog-create as an ID
     * for form and blog-create-submit for it"s button. Form"s buttons
     * need to have constant form-button class.
     */
    body.on("click", ".form-button", function (event) {
        let elementId = $(this).attr("id");
        elementId = elementId.replace("-submit", "");

        /*
        let method_type = "POST";
        if (elementId.indexOf("update") > -1) method_type = "PUT";
         */

        let formData = new FormData($("form").get(0));

        $.ajax({
            url: apiAddress + "/" + elementId.replace("-", "/"),
            data: formData,
            type: "POST", // method_type,
            dataType: "JSON",
            cache: false,
            processData: false,
            contentType: false,
            beforeSend() {
                $(".progress").css("top", "56px");
            },
            complete() {
                $(".progress").css("top", "51px");
            },
            success(result) {
                if (result["status"] === "OK") {
                    window.location.replace("/");
                } else {
                    toastElement.toast("show");
                    $(".toast-body").text(result["message"]);
                }
            },
            error(xhr, status, error) {
                // alert("responseText: " + xhr.responseText);
                toastElement.toast("show");
                let message = "Unknown Error!";
                if (typeof result === "undefined") {
                    message = "DB connection error! Please try again.";
                } else {
                    message = result["message"];
                }
                $(".toast-body").text(message);
            }
        });
    });

    /**
     * Enable using Enter key in forms to trigger click on button
     */
    body.on("keypress", "form", function (event) {
        if (event.key === "Enter") $(".form-button").click();
    });

    /**
     * Send form data with Ajax for all forms
     *
     * Consider a route for your form like /blog/delete/{slug}; now use
     * blog-delete-{slug} as an ID for this button. Buttons
     * need to has constant form-delete-button class.
     */
    body.on("click", ".form-delete-button", function (event) {
        let elementId = $(this).attr("id");

        $.ajax({
            url: apiAddress + "/blog/delete/" + elementId,
            type: "DELETE",
            dataType: "JSON",
            beforeSend() {
                $(".progress").css("top", "56px");
            },
            complete() {
                $(".progress").css("top", "51px");
            },
            success(result) {
                if (result["status"] === "OK") {
                    window.location.replace("/");
                } else {
                    toastElement.toast("show");
                    $(".toast-body").text(result["message"]);
                }
            },
            error(xhr, status, error) {
                // alert("responseText: " + xhr.responseText);
                toastElement.toast("show");
                let message = "Unknown Error!";
                if (typeof result === "undefined") {
                    message = "DB connection error! Please try again.";
                } else {
                    message = result["message"];
                }
                $(".toast-body").text(message);
            }
        });
    });

    if (window.location.pathname === "/websocket") {
        handleChat();
    }
});
