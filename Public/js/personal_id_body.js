(function(){
    var bodyHeight = document.body.clientHeight;
    var bottombox = document.getElementById('bottombox');
    var contentHeight = document.getElementById('body').clientHeight;
    if((bodyHeight-contentHeight-210)>60){
        bottombox.className="buttom_fixed";
    }
})();