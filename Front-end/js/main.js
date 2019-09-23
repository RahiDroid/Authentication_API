/**
 * binds all necessary functions on document load
 */

$(document).ready(function () {
    bind_form_transition();
    bind_login_form();
    bind_register_form();

    // do not unbind dimissible alert from the html. 
    // slide up instead
    $('.alert').on('click', '.close', function () {
        $(this).closest('.alert').slideUp();
    });
});

function bind_form_transition() {
    $('.message a').click(function () {
        $('form').animate({ height: "toggle", opacity: "toggle" }, "slow");
    });
}

/**
 * Following section takes care of login functionalities
 */
function bind_login_form() {
    $("#btn_login").click(function () {

        let url = "http://localhost/rest_demo/api/users/authenticate.php";

        var email = $('.login-form .email').val();
        var password = $('.login-form .password').val();

        if (email == '' || password == '') {
            showAlerts('#generic-empty');
            return;
        }
        email = $.trim(email);
        password = $.trim(password);

        let data = {
            "email": email,
            "password": password
        };

        postJSON(loginVerifyResponse, url, data);
    });
}

function loginVerifyResponse(response) {

    let login_response = JSON.parse(response);
    console.log(login_response);
    if (login_response['response code'] === '200') {
        // success
        showAlerts('#login-success');
    } else if (login_response['response code'] === '404') {
        // incorrect password or email
        showAlerts('#login-incorrect');
    } else {
        // server error
        showAlerts('#generic-error');
    }

}

/**
 * Following section takes care of login functionalities
 */
function bind_register_form() {
    $('#btn_register').click(function () {
        let url = "http://localhost/rest_demo/api/users/register.php";

        var email = $('.register-form .email').val();
        var password = $('.register-form .password').val();
        var confirmPassword = $('.register-form .confirm-password').val();

        if (email == '' || password == '' || confirmPassword == '') {
            showAlerts('#generic-empty');
            return;
        } else if (password !== confirmPassword) {
            showAlerts('#register-not-match');
            return;
        }
        email = $.trim(email);
        password = $.trim(password);
        confirmPassword = $.trim(confirmPassword);

        let data = {
            "email": email,
            "password": password
        };

        postJSON(registerVerifyResponse, url, data);

    });
}

function registerVerifyResponse(response) {

    let login_response = JSON.parse(response);
    console.log(login_response);
    if (login_response['response code'] === '201') {
        showAlerts('#register-success');
    } else if (login_response['response code'] === '403') {
        showAlerts('#register-preregistered');
    } else {
        showAlerts('#generic-error');
    }

}

/**
 * Following section takes care of CRUS operations
 * 
 * @param {function} callback 
 * @param {string} url 
 * @param {JSObject} data 
 */
function postJSON(callback, url, data) {
    // new HttpRequest instance
    var xmlHttp = new XMLHttpRequest();

    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            callback(xmlHttp.responseText);
        }
    }

    var theUrl = url;
    xmlHttp.open("POST", theUrl);
    xmlHttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    xmlHttp.send(
        JSON.stringify(data)
    );
}

function showAlerts(id) {
    $(id).slideDown();
}
