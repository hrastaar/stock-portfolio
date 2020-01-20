// function I found (using stack overflow) to get parameters in JS
function getQueryVariable(variable)
{
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i=0;i<vars.length;i++) {
            var pair = vars[i].split("=");
            if(pair[0] == variable){return pair[1];}
    }
    return(false);
}

// function that clears session variables.
function logout() {
    console.log("Log out button pressed");
    sessionStorage.clear();
}

// call the Alpha Vantage API
function ajax( endpoint, returnFunction ) {
    let xhr = new XMLHttpRequest();
    //.open(method - get or post?, endpoint)
    xhr.open("GET", endpoint);
    xhr.send();
    // Wait until we get some kind of response comes back from iTunes
    xhr.onreadystatechange = function() {
        // console.log(this);
        // When iTunes gives us some kind of response, this code will get run

        if( xhr.readyState == this.DONE ) {
            if( xhr.status == 200 ) {
                // Succesfully received a response
                // console.log( xhr.responseText );
                // console.log( JSON.parse(xhr.responseText) );

                returnFunction( xhr.responseText );
            }
            else {
                // There was some error
                alert("AJAX error!");
                console.log(xhr.status);
            }
        }
    }
}

// show the search results
function displayResults(resultObject) {
    // Convert JSON into JS objects
    resultObject = JSON.parse(resultObject);
    console.log(resultObject);
    // make the current price fixed to two decimal places
    var decimalPrice;
    var changePercent;
    var priceString;
    var changePercentString;
    if(resultObject["Global Quote"] != null) {
        var decimalPrice = parseFloat(resultObject["Global Quote"]["05. price"]).toFixed(2);
        var changePercent = parseFloat(resultObject["Global Quote"]["10. change percent"]).toFixed(2);
        priceString = "$" + decimalPrice.toString();
        changePercentString = "Daily Change: " + changePercent.toString() + "%";
        let changePercentElement = document.querySelector("#daily-change");
        changePercentElement.innerHTML = changePercentString;
    } else {
        priceString = "Invalid Stock Index.";
    }

    // Create a bunch of HTML elements so we can show the results on the browser in a nicely formatted way
    let indexElement = document.querySelector("#stock-index");
    indexElement.innerHTML = (getQueryVariable("index"));
    let priceElement = document.querySelector("#stock-price");
    priceElement.innerHTML = "Current Price: " + priceString;
    // by default, set buying price to the current price
    let priceInput = document.querySelector("#order_price");
    priceInput.setAttribute("value", parseFloat(resultObject["Global Quote"]["05. price"]).toFixed(2));
}
// URL for the GET request: https://www.alphavantage.co/query?function=TIME_SERIES_INTRADAY&symbol={INDEX_NAME}&interval=30min&outputsize=full&apikey=demo
var apiCall = "https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol=" + getQueryVariable("index") + "&apikey=PY60RVS8O6E0ZHLK";
ajax(apiCall, displayResults);
