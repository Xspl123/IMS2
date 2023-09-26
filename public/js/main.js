var page_name;
var action;
var targets;
var GSM_Gateway;

var baseurl =window.location.href;
 
//  setTimeout(function() { getbarcode(); }, 3000);

 const interval = setInterval(function() {
    getbarcode();
 }, 2000);

 
 function getbarcode(sel) {
    var data_html = {'action': 'fetch_email_listing'};
    var url = "http://192.168.1.109/Abhishek/VertAgeCrm/public/api/getBarcodeValue";
    var token = "YOUR_TOKEN_HERE"; // Replace with your actual token

    call_ajax(data_html, url, 'barcode', 'json', 'GET', token);
}

function call_ajax(data_html, url, roll, dataType, Method, token) {
    $.ajax({
        type: Method,
        url: url,
        data: data_html,
        dataType: dataType,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('Authorization', 'Bearer ' + token);
        },
        success: function (data) {
            record_response(data, roll); // Assuming you meant to call record_response instead of record_responce
        },
        error: function (xhr, status, error) {
            if (xhr.status === 401) {
                window.location.href = "/login"; // Redirect to login page
            } else {
                console.log("Error: " + error);
            }
        }
    });
}

function record_response(data, roll) {
    // Your implementation for handling the response data
    console.log(data);
        if (roll === 'barcode') {
            $('#barcode').val(data.live_agent.barcode);
        }
}
