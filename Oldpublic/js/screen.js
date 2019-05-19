var params={};

request(updateData,'screen','',params);
function updateData(data) {
    for(i=0;i<data.length;i++) {
        console.log(data[i]);
        if(data[i].is_queue != "0") {
            console.log(data[i].is_queue);
            pushData(data[i]);
        }
    }

}
//request(pushData,'screen.php','',params);
//function updateData(data) {
   // for (i = 0; i < Object.keys(data).length; i++) {
      //  if (currentQueue != data[i].queue || currentRoom !=data[i].room) {
          //  pushData(data[0]);
      //  }
  //  }
//}
function pushData(data) {
    const bigQueue=document.getElementById('big-queue');
    var queue=bigQueue.getElementsByClassName('queue')[0].textContent;
    var drName=bigQueue.getElementsByClassName('dr-name')[0].textContent;
    var room=bigQueue.getElementsByClassName('room')[0].textContent;
    var text="<div class='queue-box nobat-box'>" +
        "<div class='info'>" +
        "<span class='doctor'> <i class='flaticon-doctor'></i>"+drName+"</span>" +
        "<small>اتاق شماره  <span class='number'>"+room+"</span></small>" +
        "</div>" +
        "<div class='nobat'>"+
        "<i class='flaticon-user'></i>" +
        "<div class='number'>" +
        "<span>نوبت</span>" +
        "<h2 class='queue'>"+queue+"</h2>" +
        "</div>" +
        "</div>" +
        "</div>";
    $(".rightBox").prepend($(text).fadeIn('slow'));
    //changeQ=document.getElementsByClassName('queue-box')[2].innerHTML;
    //console.log(changeQ)
    //node=document.createElement()
    $(".queue-box:nth-child(4)").remove()
    bigQueue.getElementsByClassName('queue')[0].textContent=data.queue;
    bigQueue.getElementsByClassName('dr-name')[0].textContent=data.dr_name;
    bigQueue.getElementsByClassName('room')[0].textContent=data.room;
    params.room=data.room;
    request(updateData,'screen','calledQueue',params)
}
function updateInterval(data) {
    const bigQueue=document.getElementById('big-queue');
    var currentQueue=bigQueue.getElementsByClassName('queue')[0].textContent;
    if(currentQueue==data.queue){
    }
    console.log(currentQueue);
}
setInterval(function(){ request(updateData,'screen','',params); }, 3000);
