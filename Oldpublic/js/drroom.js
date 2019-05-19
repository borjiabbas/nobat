function next() {
    room=document.getElementById('room').value;
    params={room:room};
    request(noticeShow,'drroom','nextQueue',params);
    changeNextStyle('نفر بعدی')
}
function noticeShow(data){
    console.log(data);
    document.getElementById("queue").innerHTML=data.queue;
    document.getElementById("shDate").innerHTML=data.day;
    document.getElementById("drName").innerHTML=data.dr_name;
    statusTranslate={
        nextQueue:'نوبت'+data.queue+'فراخون شد',
        finishQueue:'نوبت های در انتظار به اتمام رسیده',
        roomInit:" فراخوان برای "+data.dr_name+"<br>"+"اگر نام پزشک صحیح نیست پذیرشی برای شما انجام نشده است"+"<br>"
        +"پایان شیفت را زده و بعد از چند لحظه دوباره امتحان کنید",
        errorInHis:"شماره نوبت با his ست شد",
        nextDay:"فراخوان با توجه به پایان روز ریست شد",
        drLeft:"پایان شبفت "
    };

    document.getElementById('notice').innerHTML=statusTranslate[data.status]
}
function drLeft() {
    room=document.getElementById('room').value;
    params={room:room};
    request(noticeShow,'drroom','drLeft',params);
    changeNextStyle('شروع ویزیت')
}
function changeNextStyle(textValue) {
    document.getElementById('nextText').innerHTML=textValue
}
function keyPressRunFn(keyName,fn) {
    document.addEventListener('keypress',function () {
        if(event.key==keyName){
            window[fn]()
        }
    })

}



document.getElementById('nextButton').onclick=next;
document.getElementById('drLeft').onclick=drLeft;
document.onload=keyPressRunFn('z','next');


