function logout() {
    console.log("Log out button pressed");
    sessionStorage.clear();
}
// call the Alpha Vantage API, reused by search_results
async function ajax( endpoint) {
    let xhr = new XMLHttpRequest();
    xhr.open("GET", endpoint);
    xhr.send();
    xhr.onreadystatechange = function() {
        if( xhr.readyState == this.DONE ) {
            if( xhr.status == 200 ) {
                let data = JSON.parse(xhr.responseText);
                let currPrice = parseFloat(data["Global Quote"]["05. price"]).toFixed(2);
                console.log("Current Price of that looked up index: " + currPrice);
                // return the price
                return (currPrice);
            } else {
                // There was some error calling the api
                return "Price Unavailable";
            }
        }
    }
}

async function getPrice(index) {
    console.log("Index being looked up: " + index);
    var apiCall = "https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol=" + index + "&apikey=PY60RVS8O6E0ZHLK";
    return await ajax(apiCall);
}