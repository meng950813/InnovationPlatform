 (function(){
    var bodyHeight = document.body.innerHeight;
    var bottombox = document.getElementById('bottombox');
    var contentHeight = document.getElementsByClassName('main')[0].innerHeight;
    if((bodyHeight-contentHeight-130)>60){
        bottombox.className="buttom_fixed";
    }
})();