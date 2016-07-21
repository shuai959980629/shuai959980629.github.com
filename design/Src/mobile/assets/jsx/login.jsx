var style  = {
	marginTop:'20px',
};
var h1style={
	paddingTop:'10px',
	fontSize:'30px',
	textAlign:'center'
};
var LoginForm = React.createClass({
	Yzm:function(){
        var mobile = this.state.mobile;
        if(mobile==''){
            $.dialog({content:'手机号码不能为空！'});
            return false;
        }
        var reg = /^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|17[0-9]{9}$|18[0-9]{9}$/;
        if(!reg.test(mobile)){
            $.dialog({content:'请先输入正确的手机号码！'});
            return false;
        }
        var _this = $("#sendCode"),
            _thisRel = parseInt(_this.attr('rel')),
            time = 60,
            timer = null;
        if(_thisRel) {
        	_this.html('发送(' + time + 's)');
            timer = setInterval(function() {
                time--;
                if(time === 0) {
                    clearInterval(timer);
                    _this.css({'opacity':'1','min-width':'none'}).html('发送').attr('rel','1');
                }else {
                    _this.html('发送(' + time + 's)');
                }
            },1000);
            _this.css({'opacity':'0.5','min-width':'80px'}).attr('rel','0');
            //发送请求
            $.post('/login/sms_login_verify',{"mobile":mobile}, function(data){
                if(data.status){
					$.dialog({content:"验证码是："+data.data.code});
                }else{
                     $.dialog({content:data.msg});
                }
            }, 'json');
        }
    },
	handleVerify:function(){
		var mobile = this.state.mobile;
        if(mobile==''){
            $.dialog({content:'手机号码不能为空！'});
            return false;
        }
        var reg = /^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|17[0-9]{9}$|18[0-9]{9}$/;
        if(!reg.test(mobile)){
            $.dialog({content:'请先输入正确的手机号码！'});
            return false;
        }
        var code = this.state.code;
        if(code=='' || code.length !=6){
            $.dialog({content:'请输入正确的短信验证码！'});
            return false;
        }
        return true;
	},
	handleSubmit:function(e){
		e.preventDefault();
		if(this.handleVerify()){
			$.post('/login/lgin',this.state,function(data){
				if(data.status){
					window.location.href=data.data.redirect_uri;
				}else{
					if(data.data && data.data.redirect_uri){
						window.location.href=data.data.redirect_uri;
					}else{
						$.dialog({content:data.msg});
					}
				}
			},'json');
		}
	},
	handleChange:function(name,event){
		 var data = {};
		 data[name] = event.target.value; 
		 this.setState(data);
	},
	getInitialState: function() {
   	   return {
   	   		mobile:'',
   	   		code:'',
   	   };
    },
	render:function(){
		return (
			<div>
				<h1 style={h1style}>௵~周の业务管理系统</h1>
				<div style={style} className="ui-form ui-border-t">
				    <form className="Form" onSubmit={this.handleSubmit}>
			       		<div className="ui-form-item ui-form-item-pure ui-border-b">
							<input name="mobile" type="text" onChange={this.handleChange.bind(this,'mobile')} placeholder="请输入手机号码"/>
						</div>
						<div className="ui-form-item ui-form-item-r ui-border-b">
							<input type="number" maxLength="6" name="code" onChange={this.handleChange.bind(this,'code')} placeholder="请输入手机验证码"/>
							<button type="button"  id="sendCode" rel='1' onClick={this.Yzm} className="ui-border-l sendCode">发送验证码</button>
						</div>
						<div className="ui-btn-wrap">
							<button type="submit" className="ui-btn-lg ui-btn-danger">
								登录
							</button>
						</div>
						<div className="ui-btn-wrap">
							<a href="/login/auto" type="submit" className="ui-btn-lg ui-btn-primary">
								快捷登陆
							</a>
						</div>
			        </form>
			    </div>
			    <div className="cop"><em></em>2016-05-15 Jhou shuai<em></em></div>
			</div>
		);
	}
});
ReactDOM.render(
  <LoginForm />,
  document.getElementById('container')
);
