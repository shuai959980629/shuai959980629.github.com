/**
 * Created by ZhouShuai on 2016/3/6.
 */
var seletEl   = document.getElementById('select-skin-item');//皮肤项
var skinConEl = document.getElementById('skin-list-container');//皮肤列表
var skinBtn   = document.getElementById('skin-btn');//换肤按钮
//事件监听
var eventUtil = {
    addHandler: function(element, type, handler) {
        if (element.addEventListener) {
            element.addEventListener(type, handler, false);
        } else if (element.attachEvent) {
            element.attachEvent("on" + type, handler);
        } else {
            element["on" + type] = handler;
        }
    },
    removeHandler: function(element, type, handler) {
        if (element.removeEventListener) {
            element.removeEventListener(type, handler, false);
        } else if (element.detachEvent) {
            element.detachEvent("on" + type, handler);
        } else {
            element["on" + type] = null;
        }
    }
};
/*=============================页面加载即可，执行==============*/
init();
//事件监听，绑定
function init() {
    var skin = getCookie('skin') || localStorage.getItem('skin');
    skin = skin || 'skin-theme-mosi';
    document.body.setAttribute('class',skin);
    eventUtil.addHandler(seletEl, 'click', selectSkin);
    eventUtil.addHandler(skinBtn,'mouseover',showSkinlist);
    eventUtil.addHandler(skinConEl,'mouseover',showSkinlist);
    eventUtil.addHandler(skinConEl,'mouseout',hiddenSkinlist);
}
//显示
function showSkinlist(){
    skinConEl.style.display = 'block';
}
//隐藏
function  hiddenSkinlist(){
    skinConEl.style.display = 'none';
}
/*Window.localStorage.setItem(key,value);//存储数据
 Window.localStorage.getItem(key);//读取数据*/
//选择皮肤
function selectSkin(e){
    var ele = e.target || e.srcElement;
    var data = ele.getAttribute('data-type');
    if (data) {
       setCookie('skin',data);
       localStorage.setItem('skin',data);
       document.body.setAttribute('class',data);
       skinConEl.style.display = 'none';
    } else {
        return false;
    }
}

/**
 *@设置cookie的值
 */
function setCookie(name,value){
    var Days = 30;//一个月
    var exp = new Date();
    exp.setTime(exp.getTime() + Days*24*60*60*1000);
    document.cookie = name + "="+value+ ";expires=" + exp.toGMTString();
}

/**
 *@获取cookie的值
 */
function getCookie(name) {
    var cookies = document.cookie;
    var cookie;
    cookies = cookies.split(';');
    if(cookies.length) {
        for(var i=0; i<cookies.length; i++) {
            cookie = cookies[i].split('=');
            if(cookie[0].indexOf(name) != -1) {
                return cookie[1];
            }
        }
    }
    return false;
}
