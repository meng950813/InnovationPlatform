/*  create by cm  */
$(function(){
    $('#logo').change(function(event) {
        event.preventDefault();
        var $file = $(this);
        var fileObj = $file[0];
        var windowURL = window.URL || window.webkitURL;
        if(fileObj && fileObj.files && fileObj.files[0]){
            var path = windowURL.createObjectURL(fileObj.files[0]);
            $('#preview').attr('src',path);

            $("#bottombox").removeClass("buttom_fixed");
        }else{
            console.log($file.val());
            var dataURL = $file.val();
            var imgObj = document.getElementById("preview");
            // 两个坑:
            // 1、在设置filter属性时，元素必须已经存在在DOM树中，动态创建的Node，也需要在设置属性前加入到DOM中，先设置属性在加入，无效；
            // 2、src属性需要像下面的方式添加，上面的两种方式添加，无效；
            imgObj.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale);max-width:200px;";
            // imgObj.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = dataURL;
            // imgObj.setAttribute("max-width",'200px');
            console.log("ie");
            imgObj.setAttribute("src",dataURL);
    }
    });

    $('#sure_pwd').change(function(event){
        if($('#pwd').val() == $('#sure_pwd').val()){
            $('#pwdError').html("*");
        }else{
            $('#pwdError').html('密码不同');
        }
    });

    $('#identify').change(function(event){
        if(/^\d{18}|\d{17}[Xx]$/.test($('#identify').val())){
            $('#idError').html("*");
        }else{
            $('#idError').html('身份证格式错误');
        }
    });

    $('#com_phone').change(function(event){
        if(!(/^\d{4}-\d{8}$/.test($('#com_phone').val()))){
            //  document.getElementById("com_phoneError").innerHTML = "号码格式错误";
            $("#com_phoneError").html("电话号码格式错误");
        }else{
             $("#com_phoneError").html("*");
        }
    });
    
    $('#phone').change(function(event){
        if(/^1[3-9]\d{9}$/.test($('#phone').val())){
            $('#phoneError').html('*');
        }else{
            $('#phoneError').html('手机号码格式错误');
        }
    });
});