var bodyHeight = document.body.clientHeight;
var bottombox = document.getElementById('bottombox');
var contentHeight = document.getElementById('main').clientHeight;
if((bodyHeight-contentHeight-130)>80){
    var next = document.getElementsByClassName('next')[0];
    next.style.position="fixed";
    next.style.bottom = "80px";
    bottombox.className="buttom_fixed";
}