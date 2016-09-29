(function(){
    var bodyHeight = document.body.clientHeight;
    var bottombox = document.getElementById('bottombox');
    var contentHeight = document.getElementById('main').clientHeight;
    if((bodyHeight-contentHeight-130)>60){
        bottombox.className="buttom_fixed";
    }
})();