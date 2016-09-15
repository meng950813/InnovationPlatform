(function(){
    var bodyHeight = document.body.innerHeight;
    var bottombox = document.getElementById('bottombox');
    var banner = document.getElementsByClassName('bannerbox')[0].innerHeight;
    var contentHeight = document.getElementsByClassName('infolist-main')[0].innerHeight;
    if((bodyHeight-banner-contentHeight-130)>60){
        bottombox.className="buttom_fixed";
    }
})();