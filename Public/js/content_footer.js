(function(){
    var bodyHeight = document.body.clientHeight;
    var bottombox = document.getElementById('bottombox');
    var contentHeight = document.getElementById('body').clientHeight;
    if((bodyHeight-contentHeight-130)>80){
        bottombox.className="buttom_fixed";
    }
})();