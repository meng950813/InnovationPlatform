(function(){
    var bodyHeight = document.body.clientHeight;
    // console.log(bodyHeight);
    var bottombox = document.getElementById('bottombox');
    var banner = document.getElementsByClassName('bannerbox')[0].clientHeight;
    // console.log(banner);
    var contentHeight = document.getElementsByClassName('infolist-main')[0].clientHeight;
    // console.log(contentHeight);
    if((bodyHeight-banner-contentHeight-130)>80){
        bottombox.className="buttom_fixed";
    }
})();