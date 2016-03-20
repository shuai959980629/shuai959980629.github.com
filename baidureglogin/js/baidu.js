var baiDu = {
    init:function(){
        this.defaultFocus();//搜索框获取焦点
        this.settingMenu();//设置列表
        this.baiduMoreMenu();//更多产品
        this.initEvent();//初始化事件
        this.login();//登录
    },
    defaultFocus:function(){
        $("#search-input").focus();
    },
    settingMenu:function(){
        $("#list-set,#set,#ul-set").mouseover(function(){
            $("#ul-set").show();
        }).mouseout(function(){
            $("#ul-set").hide();
        });
    },
    baiduMoreMenu:function(){
        $("#more,#more-product").mouseover(function(){
            $("#more-product").show();
        }).mouseout(function(){
           $("#more-product").hide();
        });
    },
    initEvent:function(){
        //打开登录框
        $("#login-btn").click(function(){
            $("#pop-wrapper").show(300,'linear');
        });
        //关闭登录框
        $("#closeBtn").click(function(){
            $("#pop-wrapper").hide(300,'linear');
        });
        //短信登陆
        $("#sms-btn").on('click',function(){
            $("#normalogin").hide();
            $("#otherLogin").hide();
            $("#sms-login").show();
        });
        //账号密码登录
        $("#normal-login-btn").on('click',function(){
            $("#normalogin").show();
            $("#otherLogin").show();
            $("#sms-login").hide();
        });
        //账户登录。二维码登录
        $("#change-method-btn").click("on",function(){
            var type = $(this).attr('data-type');
            if(type=='qrcode'){
                $("#normalogin").hide();
                $("#otherLogin").hide();
                $("#sms-login").hide();
                $("#qrcode-login").show();
                $(this).removeClass('pop-qrcodeLogin');
                $(this).addClass('pop-normalLogin');
                $(this).attr('data-type','normalogin');
            }else if(type=='normalogin'){
                $("#normalogin").show();
                $("#otherLogin").show();
                $("#sms-login").hide();
                $("#qrcode-login").hide();
                $(this).addClass('pop-qrcodeLogin');
                $(this).removeClass('pop-normalLogin');
                $(this).attr('data-type','qrcode');
            }
        });
        //输入框获得焦点的事件
        $(".pass-text-input").on('focus',function(){
            var loginItem = $(this).parent('.login-item');
            var passLabel = $(this).prev('.pass-label');
            if(loginItem.length){
                if(loginItem.attr('error')){
                    $(".error-tip").html('');
                    loginItem.removeClass('error');
                }
                loginItem.addClass('active');
                if(passLabel.length){
                    passLabel.addClass('active-label');
                }
            }else{
                if($(this).attr('error')){
                    $(".error-tip").html('');
                    $(this).removeClass('error');
                }
                $(this).addClass('active');
                $(this).removeClass('error');
            }
        });
        //输入框失去焦点的事件
        $(".pass-text-input").on('blur',function(){
            var loginItem = $(this).parent('.login-item');
            var passLabel = $(this).prev('.pass-label');
            if(loginItem.length){
                loginItem.removeClass('active');
                if(passLabel.length){
                    passLabel.removeClass('active-label');
                }
            }else{
                $(this).removeClass('active');
            }
        });
        //键盘按下的事件
        $(".pass-text-input").keyup(function(){
            var val = $(this).val();
            var cleanBtn = $(this).next('.clean-btn');
            if(val!=''){
                cleanBtn.show();
            }else{
                cleanBtn.hide();
            }
        });
        //点击清空按钮
        $(".clean-btn").click(function(){
            var input = $(this).prev('.pass-text-input');
            input.val('');
            $(this).hide();
        });

    },
    login:function(){/*submit*/
        var _that = this;
        $(".pass-button-submit").on("click",function(event){
            var e = event || window.event;
            var type = $(this).attr('data-type');
            var result = _that.validation(type);
            if(result){
                if(type=='normal'){
                    var username = $("#username").val();
                    var password = $("#password").val();
                    window.location.href = 'mybaidu.html';
                }else if(type=='sms'){
                    var phone       = $("#phone").val();
                    var smsVerifyCode = $("#smsVerifyCode").val();
                    window.location.href = 'mybaidu.html';
                }
            }
            e.preventDefault();
        });
    },
    validation:function(type){/*表单验证*/
        if(type=='normal'){
            var username = $("#username").val();
            if(username==''){
                var loginItem = $(".login-item-username");
                this.errortip('请输入手机/邮箱/用户名！',type);
                loginItem.addClass('error');
                loginItem.attr('error','1') ;
                return false;
            }
            var password = $("#password").val();
            if(password==''){
                var loginItem = $(".login-item-password");
                loginItem.addClass('error');
                loginItem.attr('error','1') ;
                this.errortip('请输入密码！',type);
                return false;
            }
            return true;
        }else{
            var phone  = $("#phone").val();
            if(phone==''){
                var loginItem = $(".login-phone-item");
                loginItem.addClass('error');
                loginItem.attr('error','1') ;
                this.errortip('请输入手机号！',type);
                return false;
            }
            var smsVerifyCode = $("#smsVerifyCode").val();
            if(smsVerifyCode==''){
                $("#smsVerifyCode").addClass('error');
                $("#smsVerifyCode").attr('error','1') ;
                this.errortip('请输入动态密码！',type);
                return false;
            }
            return true;
        }
    },
    errortip:function(msg,type){/*错误提示*/
        $("#error-tip-"+type).html(msg);
    }
};
$(function(){
   baiDu.init();
});