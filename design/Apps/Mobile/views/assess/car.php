<div class="left_page" style="padding-bottom: 65px;">
    <a href="javascript:void(0);"><ul class="ui-list ui-border-tb mb15 ui-list-active ui-list-link">
            <li class="ui-border-t">
                <div class="ui-avatar">
                    <img src="/assets/images/p1.jpg">
                </div>
                <div class="ui-list-info">
                    <h4 class="ui-nowrap"><?php echo $realname;?>（<?php echo $stores;?>）</h4>
                    <p class="ui-nowrap">职位：<?php echo $office;?></p>
                </div>
            </li>
        </ul>
    </a>
    <ul class="ui-list ui-list-text ui-list-active ui-list-link ui-border-tb mb15">
        <li class="ui-border-t car">
            <h4 id="chooseModel" class="ui-nowrap"><?php if(!empty($car_info['modelName'])){ echo $car_info['modelName'];}else{ echo '选择车型';}  ?></h4>
            <span class="ui-panel-subtitle">选择车型</span>
        </li>
        <li class="ui-border-t city">
            <h4 id="cty" class="ui-nowrap cityH4">
                <?php
                if(!empty($car_info['provName']) && !empty($car_info['city_name'])){
                    echo $car_info['provName'].'-'.$car_info['cityName'];
                }else{
                    if($current_city['prov_name']==$current_city['city_name']){
                        echo $current_city['prov_name'];
                    }else{
                        echo $current_city['prov_name'].'-'.$current_city['city_name'];
                    }
                }
                ?>
            </h4>
            <span class="ui-panel-subtitle">所在城市</span>
        </li>
        <div class="ui-form ui-border-t">
            <div class="ui-form-item ui-form-item-r ui-border-b">
                <input class="ui-nowrap" name="regDate" id="regDate" value="<?php if(isset($car_api['regDate'])){ echo date('Y-m',strtotime($car_api['regDate'])); }else{  echo date('Y-m'); } ?>" type="month" />
                <span class="right-text">上牌日期</span>
            </div>
        </div>
        <div class="ui-form ui-border-t">
            <form action="#">
                <div class="ui-form-item ui-form-item-r ui-border-b">
                    <input id="mile" name="mile_age" unit="1" pattern="^[0-9]\d*(\.\d{0,4})?$"  value="<?php if(!empty($car_api['mile'])){ echo $car_api['mile']; } ?>"  class="drive_distance" placeholder="请输入行驶里程">
                    <span class="right-text">万公里</span>
                    <a href="javascript:void(0);" class="ui-icon-close"></a>
                </div>
            </form>
        </div>
        <div class="ui-form ui-border-t">
            <div class="ui-form-item ui-form-item-r ui-border-b">
                <input  name="plate_no" id="plate_no" maxlength="7" style="text-transform: uppercase; ime-mode: inactive;" value="<?php if(!empty($plate_no)){ echo $plate_no;} ;?>" type="text" placeholder="请输入车牌号">
                <span class="right-text">车牌号码</span>
                <a href="javascript:void(0);" class="ui-icon-close"></a>
            </div>
        </div>
    </ul>

    <div id="errorMsg" class="ui-tooltips ui-tooltips-warn alert">
        <div class="ui-tooltips-cnt ui-border-b">
            <i></i><span id="msg">&nbsp;</span>
            <a id="close" class="ui-icon-close"></a>
        </div>
    </div>
    <div class="ui-btn-wrap">
        <button type="submit"  id="submit" class="ui-btn-lg ui-btn-danger start_Valuation">
            保存
        </button>
    </div>
    <div class="ui-actionsheet">
        <div  class="ui-actionsheet-cnt">
            <h4>根据VIN码识别出来的车型</h4>
            <div id="vin-car-list"></div>
            <button class="close">取消</button>
        </div>
    </div>
</div>
<div class="city_page">
    <div class="back" style="width: 100%;height:40px;padding-left:2px;line-height:40px;background-color:#414956;color:white;">
        <h4>&nbsp;返&nbsp;回(←)</h4>
    </div>
    <ul class="ui-list ui-list-text ui-list-active ui-list-cover ui-border-tb">
        <?php foreach($province as $key=>$value){ ?>
            <li class="ui-border-t">
                <h4 data-prov="<?php echo $key; ?>" class="area"><?php echo $value; ?></h4>
            </li>
        <?php } ?>
    </ul>
    <div class="select_city">
        <div class="back" style="width: 100%;height:40px;padding-left:2px;line-height:40px;background-color:#414956;color:white;">
            <h4>&nbsp;关&nbsp;闭</h4>
        </div>
        <ul style="border-left:1px solid #ddd;" class="ui-list city-list ui-list-text ui-list-active ui-list-cover ui-border-tb"></ul>
    </div>
</div>
<div class="car_page brand-list-div">
    <div class="ui-header ui-header-stable ui-border-b head">
        <div class="car-brand-name" >
            <h4  class="ui-nowrap">选择品牌</h4>
            <a class="back" style="color:white;" href="javascript:void(0);">&nbsp;返&nbsp;回(←)</a>
        </div>
        <dt>
            <?php foreach($brand_list as $key=>$list){ ?>
        <dl><a class="letter" href="javascript:void(0);"><?php echo $key; ?></a></dl>
        <?php } ?>
        </dt>
    </div>
    <div class="hei92"></div>
    <div class="logolist">
        <ul class="ui-list ui-list-text ui-list-active ui-list-cover ui-border-tb">
            <?php foreach($brand_list as $key=>$list){ ?>
                <a id="<?php echo $key; ?>">
                    <li class="ui-border-t car_N" >
                        <h4><?php echo $key; ?></h4>
                    </li>
                </a>
                <?php foreach($list as $item=>$value){ ?>
                    <li data-name="<?php echo $value['brand_name']; ?>" data-initial="<?php echo $value['initial']; ?>" data-source="<?php echo $value['brand_id']; ?>" class="ui-border-t li-brand-list car_type">
                        <h4><?php echo $value['brand_name']; ?></h4>
                    </li>
                <?php } ?>
            <?php } ?>
            <li style="border:0;height:20px;" class="ui-border-t"></li>
        </ul>

    </div>
    <div class="cartype_page series-list-div" style="border-left:1px solid;position: fixed; right: 0px; height: 100%; overflow-x: hidden; background-color: rgba(255,255,255,1);">
        <div class="car-brand-name">
            <h4 id="brand-title"  class="ui-nowrap"></h4>
            <a class="hide-series-model" href="javascript:void(0);" data="series-list-div">关闭</a>
        </div>
        <div class="carlist">
            <ul id="series_list" class="ui-list ui-list-text ui-list-active ui-list-cover ui-border-tb"></ul>
        </div>
    </div>
    <div class="cartype_info model-list-div" style="border-left:1px solid;position: fixed; right: -105px; height: 100%; overflow-x: hidden; background-color: rgba(255,255,255,1);">
        <div class="car-series-name">
            <h4 id="seriesTitle" class="ui-nowrap"></h4>
            <a class="hide-series-model"  href="javascript:void(0);" data="model-list-div">关闭</a>
        </div>
        <ul id="model_list" class="ui-list ui-list-text ui-list-active ui-list-cover ui-border-tb"></ul>
    </div>
</div>
<div class="time_page">
    <div class="back" style="width: 100%;height:40px;padding-left:2px;line-height:40px;background-color:#414956;color:white;">
        <h4>&nbsp;关&nbsp;闭</h4>
    </div>
    <ul id="year" class="ui-list ui-list-text ui-list-active ui-list-cover ui-border-tb onTime"></ul>
</div>
<div class="ui-loading-block">
    <div class="ui-loading-cnt">
        <i class="ui-loading-bright"></i>
        <p>正在加载中...</p>
    </div>
</div>
<script type="text/javascript" src="/assets/js/libs/jquery.min.js" charset="utf-8"></script>
<script src="/assets/js/car.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    $(function(){
        window.city = '<?php if(!empty($car_api['city'])){ echo $car_api['city'];}else{ echo $current_city['city_id'];} ?>';
        window.prov = '<?php if(!empty($car_api['prov'])){ echo $car_api['prov'];}else{ echo $current_city['prov_id'];} ?>';
        window.provName = "<?php if(!empty($car_info['provName'])){ echo $car_info['provName'];}else{ echo $current_city['prov_name'];} ?>";
        window.cityName = "<?php if(!empty($car_info['cityName'])){ echo $car_info['cityName'];}else{ echo $current_city['city_name'];} ?>";

        window.modelId = "<?php if(!empty($car_api['modelId'])){ echo $car_api['modelId'];}  ?>";//车型ID
        window.modelName="<?php if(!empty($car_info['modelName'])){ echo $car_info['modelName'];}  ?>";//车型名称

        window.brandId = "<?php if(!empty($car_api['brandId'])){ echo $car_api['brandId'];} ?>";//品牌ID
        window.brandName = "<?php if(!empty($car_info['brandName'])){ echo $car_info['brandName'];} ?>";//品牌名称

        window.seriesId="<?php if(!empty($car_api['seriesId'])){ echo $car_api['seriesId'];}  ?>";//车系ID
        window.seriesName="<?php if(!empty($car_info['seriesName'])){ echo $car_info['seriesName'];}  ?>";//车系名称

        window.cityData = '';
        window.minYear  = '<?php if(!empty($car_info['minYear'])){ echo $car_info['minYear']; } ?>';
        window.maxYear  = '<?php if(!empty($car_info['maxYear'])){ echo $car_info['maxYear']; } ?>';
        __init();
        initVin();
        initCar();
        initCity();
        initSbmit();
        disappearErrorMsg();
    });

    function initSbmit(){
        $("#submit").click(function(e){
            var _me = this;
            var prov = window.prov;
            var city = window.city;
            var provName = window.provName;
            var cityName = window.cityName;
            var mile = $("#mile");
            var mileAge = mile.val() / mile.attr('unit');
            var modelId = window.modelId;
            var brandId = window.brandId;
            var seriesId = window.seriesId;
            var modelName = window.modelName;//车型名称
            var brandName = window.brandName;//品牌名称
            var seriesName = window.seriesName;//车系名称
            var minYear = window.minYear;
            var maxYear = window.maxYear;
            var plate_no = $("#plate_no").val();
            var regDate  = $("#regDate").val();
            if (prov == undefined || prov == "") {
                showErrorTips('请选择省份！');
                return false;
            }
            if (city == undefined || city == "") {
                showErrorTips('请选择城市！');
                return false;
            }

            if (modelId == undefined || modelId == "" || modelName=='') {
                showErrorTips('请选择车型！');
                return false;
            }
            if (regDate == undefined || regDate == "") {
                showErrorTips('请选择上牌日期！');
                return false;
            }
            if (mileAge == "") {
                showErrorTips('行驶里程不可为空！');
                return false;
            } else if (parseFloat(mileAge) <= 0) {
                showErrorTips('请正确填写行驶里程！');
                return false;
            } else if (isNaN(parseFloat(mileAge))) {
                showErrorTips('请正确填写行驶里程！');
                return false;
            } else if (parseFloat(mileAge) >= 100) {
                if (parseInt(mile.attr('unit')) == 1) {
                    showErrorTips('行驶里程单位是万公里！');
                    return false;
                } else {
                    showErrorTips('行驶里程过大不合法！');
                    return false;
                }
            }
            if(plate_no==''){
                showErrorTips('请输入车牌号！');
                return false;
            }
            $.post('/assess/savecar',
                {
                    "id":<?php echo $id;?>,
                    "prov":prov,
                    "provName":provName,
                    "city":city,
                    "cityName":cityName,
                    "zone":city,
                    "brandId":brandId,
                    "brandName":brandName,
                    "seriesId":seriesId,
                    "seriesName":seriesName,
                    "modelId":modelId,
                    "modelName":modelName,
                    "regDate":regDate,
                    "minYear":minYear,
                    "maxYear":maxYear,
                    "plate_no":plate_no,
                    "mile":parseFloat(mileAge)
                },
                function(data){
                    var loading= $(".ui-loading-block");
                    loading.addClass('show');
                    if(data.status){
                        setTimeout(function(){
                            loading.removeClass('show');
                        },1000);
                        if(data.data && data.data.redirect_uri){
                            window.location.href=data.data.redirect_uri;
                        }
                    }else{
                        setTimeout(function(){
                            loading.removeClass('show');
                        },1000);
                        showErrorTips(data.msg);
                    }
                },'json');


        });
    }
</script>
