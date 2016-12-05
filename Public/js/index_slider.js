$(function(){

    var timer;

    $('#nav_index').addClass('nav_active').siblings().removeClass('nav_active');
   
    function nextImg () {
        var container = $('#banner-container');
        var moveWidth = ($("#banner-container li").width())*-1;
        container.stop().animate({left:moveWidth},'slow',function(){
            var firstImg = $('#banner-container ul li').first();
            container.find("ul").append(firstImg);
            $(this).css('left',"0px");
        });
         btnCircle();
    }
    function btnCircle(){
        var btnNum = $('#banner-container ul li').first().attr("index");
        $(".control .control-btn").siblings().removeClass("focus");
        $(".control .control-btn").eq((btnNum+1)%3).addClass("focus");
    }
    timer = setInterval(nextImg,5000);

    var animateEnd = 1;
    $('.control-btn').click(function(){

        clearInterval(timer);
        timer = null;

        if(animateEnd == 1){ // 表示图片不在切换
            $(this).addClass('focus').siblings().removeClass('focus');
            var nextIndex = $(this).index();
            // console.log("nextIndex = "+nextIndex);
            // 获取当前显示图片序列
            var currentIndex = $('#banner-container li').first().attr('index');
            // 保存当前图片
            var curr = $("#banner-container li").first().clone();
            if(currentIndex < nextIndex){
                for(var i=0; i<nextIndex-currentIndex;i++){
                    var firstImg = $('#banner-container li').first();
                    // 将目标图片之前的图片加到后面
                    $('#banner-container ul').append(firstImg);
                }
                // 结束循环，当前效果
                // console.log("结束循环 ："+animateEnd);
                $("#banner-container ul").prepend(curr);
                var moveWidth = ($('#banner-container li').width())*-1;
                if(animateEnd == 1){    
                    animateEnd = 0;//表示开始切换，期间不允许再次切换
                    $('#banner-container').stop().animate({left:moveWidth},"slow",function(){
                        $("#banner-container ul li").first().remove();
                        $("#banner-container").css("left","0px");
                        animateEnd = 1;
                    });
                }
            }
            else{
                // 保存最后一张图片
                var curt = $("#banner-container li").last().clone();
                for(var i = 0; i < currentIndex - nextIndex;i++){
                    var lastItem = $("#banner-container li").last();
                    $("#banner-container ul").prepend(lastItem);
                }
                $("#banner-container ul").append(curt);
                var moveWidth = ($("#banner-container li").width())*-1;
                $("#banner-container").css("left",moveWidth);
                if(animateEnd = 1){
                    $("#banner-container ul li").last().remove();
                    animateEnd = 0;
                    $("#banner-container").stop().animate({left:"0px"},"slow",function(){
                        animateEnd = 1;
                    });
                }
            }
        }

        timer = setInterval(nextImg,5000);

    });
});
$(function(){
    $(".news_picture").hide().first().fadeIn();
    var y=0
    var z=setInterval(function(){
        if(++y==5){y=0;}
        $(".news_picture").eq(y).fadeIn().siblings().fadeOut();
    },5000);

});
(function(){
    var bodyHeight = document.body.clientHeight;
    var contentHeight = document.getElementById('index_main_container').clientHeight;
    // 已知：top:80px ,nav:50px,banner:300px;bottombox:60px
    if((bodyHeight-430-contentHeight)>60){
        bottombox.className="buttom_fixed";
    }
})();