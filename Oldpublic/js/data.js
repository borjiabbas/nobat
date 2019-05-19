function request(fn,page,method,params) {
    var url = "../app/view.php?page="+page;

    if(typeof method !== 'undefined' ){
        if( method !=='') {
            url = url + "&method=" + method;
        }
    }
    if(typeof params !== 'undefined'){
        pKeys=Object.keys(params);
        pVal=Object.values(params);
        len=pKeys.length;
        for(i=0;i<len;i++){
            url= url+"&params["+pKeys[i]+"]="+pVal[i]
        }
    }
    $.ajax({url: url, success: function(result){
        result=JSON.parse(result);
        fn(result)
    }});



    // var xmlhttp = new XMLHttpRequest();
    // xmlhttp.onreadystatechange = function () {
    //     if (this.readyState == 4 && this.status == 200) {
    //        var data = JSON.parse(this.responseText);
    //         fn(data);
    //         //console.log(this.responseText)
    //
    //     }
    // };
    // xmlhttp.open("GET", url, true);
    // xmlhttp.send();
    // return data
}
//setInterval(request('screen.php'),500);
