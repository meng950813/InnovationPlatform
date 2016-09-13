(function(){
    var bodyHeight = document.body.clientHeight;
    var bottombox = document.getElementById('bottombox');
    var banner = document.getElementsByClassName('bannerbox')[0].clientHeight;
    var contentHeight = document.getElementsByClassName('infolist-main')[0].clientHeight;
    if((bodyHeight-banner-contentHeight-130)>60){
        bottombox.className="buttom_fixed";
    }
})();