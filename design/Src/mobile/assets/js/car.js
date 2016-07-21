/**
 * Created by shuai on 2016/1/21.
 */
!function ($) {
    var _private = {};
    _private.cache = {};
    $.tpl = function (str, data, env) {
        // 判断str参数，如str为script标签的id，则取该标签的innerHTML，再递归调用自身
        // 如str为HTML文本，则分析文本并构造渲染函数
        var fn = !/[^\w\-\.:]/.test(str)
            ? _private.cache[str] = _private.cache[str] || this.get(document.getElementById(str).innerHTML)
            : function (data, env) {
            var i, variable = [], value = []; // variable数组存放变量名，对应data结构的成员变量；value数组存放各变量的值
            for (i in data) {
                variable.push(i);
                value.push(data[i]);
            }
            return (new Function(variable, fn.code))
                .apply(env || data, value); // 此处的new Function是由下面fn.code产生的渲染函数；执行后即返回渲染结果HTML
        };

        fn.code = fn.code || "var $parts=[]; $parts.push('"
            + str
            .replace(/\\/g, '\\\\') // 处理模板中的\转义
            .replace(/[\r\t\n]/g, " ") // 去掉换行符和tab符，将模板合并为一行
            .split("<%").join("\t") // 将模板左标签<%替换为tab，起到分割作用
            .replace(/(^|%>)[^\t]*/g, function(str) { return str.replace(/'/g, "\\'"); }) // 将模板中文本部分的单引号替换为\'
            .replace(/\t=(.*?)%>/g, "',$1,'") // 将模板中<%= %>的直接数据引用（无逻辑代码）与两侧的文本用'和,隔开，同时去掉了左标签产生的tab符
            .split("\t").join("');") // 将tab符（上面替换左标签产生）替换为'); 由于上一步已经把<%=产生的tab符去掉，因此这里实际替换的只有逻辑代码的左标签
            .split("%>").join("$parts.push('") // 把剩下的右标签%>（逻辑代码的）替换为"$parts.push('"
            + "'); return $parts.join('');"; // 最后得到的就是一段JS代码，保留模板中的逻辑，并依次把模板中的常量和变量压入$parts数组

        return data ? fn(data, env) : fn; // 如果传入了数据，则直接返回渲染结果HTML文本，否则返回一个渲染函数
    };
    $.adaptObject =  function (element, defaults, option,template,plugin,pluginName) {
    var $this= element;

    if (typeof option != 'string'){
    
    // 获得配置信息
    var context=$.extend({}, defaults,  typeof option == 'object' && option);

    var isFromTpl=false;
    // 如果传入script标签的选择器
    if($.isArray($this) && $this.length && $($this)[0].nodeName.toLowerCase()=="script"){
      // 根据模板获得对象并插入到body中
      $this=$($.tpl($this[0].innerHTML,context)).appendTo("body");
      isFromTpl=true;
    }
    // 如果传入模板字符串
    else if($.isArray($this) && $this.length && $this.selector== ""){
      // 根据模板获得对象并插入到body中
      $this=$($.tpl($this[0].outerHTML,context)).appendTo("body");
      isFromTpl=true;
    }
    // 如果通过$.dialog()的方式调用
    else if(!$.isArray($this)){
      // 根据模板获得对象并插入到body中
      $this=$($.tpl(template,context)).appendTo("body");
      isFromTpl=true;
    }

    }

    return $this.each(function () {

      var el = $(this);
      // 读取对象缓存
  
      var data  = el.data('fz.'+pluginName);
      


      if (!data) el.data('fz.'+pluginName, 
        (data = new plugin(this,$.extend({}, defaults,  typeof option == 'object' && option),isFromTpl)

      ));

      if (typeof option == 'string') data[option]();
    })
  }
}(window.jQuery);


!function($){
    // 默认模板
    var _loadingTpl='<div class="ui-loading-block show">'+
            '<div class="ui-loading-cnt">'+
              '<i class="ui-loading-bright"></i>'+
              '<p><%=content%></p>'+
           '</div>'+
         '</div>';
    
    // 默认参数
    var defaults={
        content:'加载中...'
    }
    // 构造函数
    var Loading   = function (el,option,isFromTpl) {
        var self=this;
        this.element=$(el);
        this._isFromTpl=isFromTpl;
        this.option=$.extend(defaults,option);
        this.show();
    }
    Loading.prototype={
        show:function(){
            var e=$.Event('loading:show');
            this.element.trigger(e);
            this.element.show();
            
        },
        hide :function () {
            var e=$.Event('loading:hide');
            this.element.trigger(e);
            this.element.remove();
        }
    }
    function Plugin(option) {

        return $.adaptObject(this, defaults, option,_loadingTpl,Loading,"loading");
    }
    $.fn.loading=$.loading= Plugin;
}(window.jQuery);


function __init(){
    $(".letter").bind("click", function(e) {
        var letter = this.textContent;
        var top = $("#"+letter).offset().top-92;
        $("html, body").animate({scrollTop: top})
    });
    $("#close").bind("click",function(){
        $("#errorMsg").hide(1000);
    });
    $(".close").bind('click',function(){
        $(".ui-actionsheet").removeClass("show");
    });
    $(".hide-series-model").bind('click',function(){
        var _obj=$(this).attr('data');
        $("."+_obj).hide();
    });
    $(".click-list-link").click(function(){
        var url = $(this).attr('data-url');
        window.location.href =url;
    });
    $(".back").bind('click',function(){
        $(".left_page").show();
        $(".select_city").hide();
        $(".time_page").hide();
        $(".car_page").hide();
        $(".city_page").hide();
    });
}

function initVin(){
    $(".cartype_btn").addClass("disabled");
    $('.VIN_text').bind('input propertychange', function() {
        if($(".VIN_text").val()=="" || $(".VIN_text").val().length<17){
            $(".cartype_btn").addClass("disabled");
        } else{
            $(".cartype_btn").removeClass("disabled");
        }
    });

    $(".cartype_btn").click(function(){
        var vin =$(".VIN_text").val();
        if(vin=="" || vin.length<17){
            return false;
        }
        var el = $.loading({content:'加载中...'});//el.loading("hide");
        $.getJSON("/home/vin",{"vin":vin} ,function (data) {
            if(data.status){
                el.loading("hide");
                $("#vin-car-list").html('');
                var e = data.data;
                for(var i in e){
                    var _button = document.createElement('button');
                    _button.className='car-list-li';
                    _button.setAttribute('data-bid', e[i].brand_id);
                    _button.setAttribute('data-sid', e[i].series_id);
                    _button.setAttribute('data-mid', e[i].model_id);
                    _button.setAttribute('data-year',e[i].model_year);
                    _button.setAttribute('data-max', e[i].max_reg_year);
                    _button.setAttribute('data-min', e[i].min_reg_year);
                    _button.setAttribute('data-name',e[i].model_name);
                    _button.setAttribute('data-bname',e[i].brand_name);
                    _button.setAttribute('data-sname',e[i].series_name);
                    _button.innerHTML =  e[i].model_name+'('+e[i].model_price+'万)';
                    _button.addEventListener('click',function(e){});
                    $("#vin-car-list").append(_button);
                }
                $(".ui-actionsheet").addClass("show");
            }else{
                showErrorTips(data.msg);
                return false;
            }
        });
    });


    $(".car-list-li").live('click',function(e){//点vin
        var that = this;
        window.scrollTo(0,0);
        if (!that.hasAttribute("data-name")) return;
        window.modelName = that.getAttribute('data-name');//车型名称
        window.brandName = that.getAttribute('data-bname');//品牌名称
        window.seriesName= that.getAttribute('data-sname');//车系名称
        window.brandId  = that.getAttribute('data-bid');
        window.seriesId = that.getAttribute('data-sid');
        window.modelId  = that.getAttribute('data-mid');
        window.minYear = Number(that.getAttribute('data-min'));
        window.maxYear = Number(that.getAttribute('data-max'));
        $("#chooseModel").text(window.modelName);
        $(".ui-actionsheet").removeClass("show");
        return false
    });
}

function initMonth(){
    $("#year").html('');
    for(var i=window.minYear; i<=window.maxYear; i++){
        var _li = document.createElement('li');
        _li.className='year-list-li';
        _li.setAttribute('data-year',i);
        _li.innerHTML = '<h4  class="year">'+i+'</h4>';
        _li.addEventListener('click',function(e){});
        $("#year").append(_li);
    }
}




function initDate(){
    initMonth();
    $(".time").click(function(){
        if (window.modelId==''){
            showErrorTips('请选择车型！');
            return false;
        }
        $(".left_page").hide();
        $(".time_page").show();
        $(".car_page").hide();
        $(".city_page").hide();
    });

    $(".year-list-li").live('click',function(e){//点击年份
        var that = this;
        $(".year-list-li").css({"backgroundColor":"#ffffff","color":"#333333"});
        $(that).css({"backgroundColor":"darkmagenta","color":"White"});
        window.scrollTo(0,0);
        window.yearValue = that.getAttribute('data-year');
        if($(".month_list").length){
            $(".month_list").remove();
        }
        var _div = document.createElement('div');
        _div.className='month_list';
        var _ul = document.createElement('ul');
        _ul.className='ui-list ui-list-text ui-list-active ui-list-cover ui-border-tb';
        for(var i = 1; i <=12; i++){
            var _li = document.createElement('li');
            _li.className='ui-border-t month-list-li';
            _li.setAttribute('data-month',i);
            _li.innerHTML = '<h4  class="ui-nowrap month">'+i+'</h4>';
            _li.addEventListener('click',function(e){});
            $(_ul).append(_li);
        }
        $(_div).append(_ul);
        $(this).after(_div);
        if($(".month_list").length){
            $(".month_list").slideDown();
        }
        return false
    });


    $(".month-list-li").live('click',function(e){
        var that = this;
        $(".month-list-li").css({"backgroundColor":"#ffffff","color":"#333333"});
        $(that).css({"backgroundColor":"darkmagenta","color":"White"});
        window.scrollTo(0,0);
        window.monthValue = that.getAttribute('data-month');
        $(".left_page").show();
        $(".time_page").hide();
        if($(".month_list").length){
            $(".month_list").remove();
        }
        $(".month_list").hide();
        $("#regDate").html(window.yearValue+"-"+window.monthValue);
        return false
    });
}


function initCar(){
    $(".car").click(function(){
        $(".left_page").hide();
        $(".car_page").show();
        $(".city_page").hide();
        $(".time_page").hide();
        $(".VIN_text").val('');
    });

    $(".li-brand-list").on('click',function(){
        var that = this;
        window.scrollTo(0,0);
        var el = $.loading({content:'加载中...'});
        window.brandName = that.getAttribute('data-name');
        window.brandId   = that.getAttribute('data-source');
        $("#brand-title").html(window.brandName);
        $.getJSON("/car/series",{"bid":that.getAttribute('data-source')}, function (e) {
            el.loading("hide");
            if(e.length>0){
                $("#series_list").html('');
                for(var i in e){
                    var data_name = e[i].series_name||e[i].value;
                    var data_source = e[i].series_id||e[i].key;
                    var _li = document.createElement('li');
                    _li.className='ui-border-t series-list-li';
                    _li.setAttribute('data-name', data_name);
                    _li.setAttribute('data-source', data_source);
                    _li.innerHTML = '<h4  class="ui-nowrap">'+data_name+'</h4>';
                    _li.addEventListener('click',function(e){});
                    $("#series_list").append(_li);
                }
                $(".cartype_page").show().transition({'x':'0','y':'0'});
                $(".cartype_info").hide().css({'x':'500px','y':'0'});
            }
        });
        return false;
    });

    $(".series-list-li").live('click',function(){
        var that = this;
        window.scrollTo(0,0);
        var el = $.loading({content:'加载中...'});
        window.seriesId   = that.getAttribute('data-source');
        window.seriesName = that.getAttribute('data-name');
        $("#seriesTitle").html(window.seriesName);
        $.getJSON("/car/model",{"sid":that.getAttribute('data-source')}, function (e) {
            el.loading("hide");
            if(e.length>0){
                $("#model_list").html('');
                $(".cartype_info").show().transition({'x':'0','y':'0'});//.addClass("demo1");
                var modelYear = "";
                for(var i in e){
                    var year        = e[i].model_year||e[i].year;
                    var data_source = e[i].model_id||e[i].key;
                    var data_max    = e[i].max_reg_year||e[i].max_year;
                    var data_min    = e[i].min_reg_year||e[i].min_year;
                    var data_name   = e[i].model_name||e[i].nameL;
                    var short_name  = e[i].short_name||e[i].value;
                    var model_price  = e[i].model_price||e[i].price;
                    if (year != modelYear) {
                        var yearLi = document.createElement('li');
                        yearLi.setAttribute("style", "background: #e1e5ed;border:none;color:#2a6496");
                        yearLi.innerHTML = "<h4 class=\"year\">"+year+"款</h4>";
                        $("#model_list").append(yearLi);
                        modelYear = year;
                    }
                    var _li = document.createElement('li');
                    _li.className='ui-border-t model-list-li';
                    _li.setAttribute('data-source',data_source);
                    _li.setAttribute('data-max', data_max);
                    _li.setAttribute('data-min', data_min);
                    _li.setAttribute('data-name',data_name);
                    _li.innerHTML='<h4 class="ui-nowrap month">'+short_name+'('+Number(model_price).toFixed(2)+'万)</h4>';
                    _li.addEventListener('click',function(e){});
                    $("#model_list").append(_li);
                }
            }
        });
        return false
    });


    $(".model-list-li").live('click',function(e){//点车型
        var that = this;
        window.scrollTo(0,0);
        if (!that.hasAttribute("data-name")) return;
        window.modelName = that.getAttribute('data-name');
        window.modelId = that.getAttribute('data-source');
        window.minYear = Number(that.getAttribute('data-min'));
        window.maxYear = Number(that.getAttribute('data-max'));
        $("#year").html('');
        $("#regDate").html('上牌日期');
        window.yearValue = "";
        window.monthValue = "";
        /*$("#cty").html("选择城市");
        window.city = "";
        window.prov = "";
        window.provName = "";
        window.cityName = "";
        window.cityData = "";*/
        for(var i = window.minYear; i <= window.maxYear; i++){
            var _li = document.createElement('li');
            _li.className='year-list-li ui-border-t';
            _li.setAttribute('data-year',i);
            _li.innerHTML = '<h4 class="year">'+i+'</h4>';
            _li.addEventListener('click',function(e){});
            $("#year").append(_li);
        }
        $(".left_page").show();
        $(".cartype_info").hide();
        $(".cartype_page").hide();
        $(".car_page").hide();
        $("#chooseModel").text(window.modelName);
        return false
    });
}

function initCity(){
    $(".city").click(function(){
        $(".left_page").hide();
        $(".city_page").show();
        $(".car_page").hide();
        $(".time_page").hide();
    });
    $(".area").on('click',function(){
        window.scrollTo(0,0);
        $(this).css("color","orange");
        var el = $.loading({content:'加载中...'});//el.loading("hide");
        //加载城市
        window.prov = $(this).attr('data-prov');
        window.provName = $(this).text();
        $.getJSON("/car/city",{"pid":window.prov}, function (data) {
            el.loading("hide");
            window.cityData = data;
            var cityStr = [];
            for(var i in window.cityData){
                var city_id = window.cityData[i].city_id||window.cityData[i].key;
                var city_name = window.cityData[i].city_name||window.cityData[i].value;
                cityStr[i] = '<li  class="ui-border-t city-list-li">'
                    +'<h4 data-city="'+city_id+'" class="ui-nowrap area_city">' +city_name+'</h4>'
                    +'</li>';
            }
            var cityli = cityStr.join('');
            if($(".city-list-li").length){
                $(".city-list-li").remove();
            }
            $(".city-list").append(cityli);
            var city_height=document.documentElement.clientHeight;
            $(".select_city").css({"height":city_height+"px","border":"1px solid #000000"}).show().addClass("demo2");
            $(".area_city").on('click',function(){
                window.city = $(this).attr('data-city');
                window.cityName = $(this).text();
                var zone = window.cityName;
                if(window.provName!=window.cityName){
                    zone = window.provName+"-"+window.cityName;
                }
                $(".cityH4").html(zone);
                $(".left_page").show();
                $(".select_city").hide();
                $(".city_page").hide();
            });
        });
    });
}

function disappearErrorMsg() {
    window.setTimeout(function() {
        $("#errorMsg").hide(1000);
    }, 2000);
}

function showErrorTips(tip) {
    $("#msg").html(tip);
    $("#errorMsg").show();
}