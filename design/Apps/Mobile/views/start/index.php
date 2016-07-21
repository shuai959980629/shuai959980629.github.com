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

    <div class="ui-form ui-border-t" style="margin-bottom: -1px;">
        <div class="ui-form-item ui-form-item-r ui-border-b">
            <input value="<?php if(!empty($_GET['vin'])){ echo $_GET['vin']; } ?>" type="text" placeholder="VIN码（请输入17位VIN码）" style="text-transform: uppercase; ime-mode: inactive;" maxlength="17" class="VIN_text">
            <!-- 若按钮不可点击则添加 class="disabled" -->
            <button type="button" class="ui-border-l cartype_btn ">识别车型</button>
        </div>
    </div>
    <ul class="ui-list ui-list-text ui-list-active ui-list-link ui-border-tb mb15">
        <li class="ui-border-t car">
            <h4 id="chooseModel" class="ui-nowrap"><?php if(!empty($_GET['modelName'])){ echo $_GET['modelName']; }else{ echo '选择车型';}  ?></h4>
            <span class="ui-panel-subtitle">选择车型</span>
        </li>
        <li class="ui-border-t city">
            <h4 id="cty" class="ui-nowrap cityH4">
                <?php
                if(!empty($_GET['provName']) && !empty($_GET['cityName']) ){
                    if($_GET['provName']==$_GET['cityName']){
                        echo $_GET['cityName'];
                    }else{
                        echo $_GET['provName'].'-'.$_GET['cityName'];
                    }
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
                <input class="ui-nowrap" name="regDate" id="regDate" value="<?php if(isset($_GET['regDate'])){ echo $_GET['regDate']; }else{  echo date('Y-m'); } ?>" type="month" />
                <span class="right-text">上牌日期</span>
            </div>
        </div>
        <!-- <li class="ui-border-t time">
            <h4 id="regDate" class="ui-nowrap"><?php /*if(!empty($_GET['regDate'])){ echo $_GET['regDate']; }else{ echo '上牌日期';}  */?></h4>
            <span  class="ui-panel-subtitle">上牌日期</span>
        </li>-->
        <div class="ui-form ui-border-t">
            <form action="#">
                <div class="ui-form-item ui-form-item-r ui-border-b">
                    <input id="mile" name="mile_age" unit="1" pattern="^[0-9]\d*(\.\d{0,4})?$"  value="<?php if(!empty($_GET['mile'])){ echo $_GET['mile']; } ?>" style="text-transform: uppercase; ime-mode: inactive;" class="drive_distance" placeholder="请输入行驶里程">
                    <span class="right-text">万公里</span>
                </div>
            </form>
        </div>
    </ul>
    <div id="errorMsg" class="ui-tooltips ui-tooltips-warn alert">
        <div class="ui-tooltips-cnt ui-border-b">
            <i></i><span id="msg">&nbsp;</span>
            <a id="close" class="ui-icon-close"></a>
        </div>
    </div>
    <div class="ui-btn-wrap">
        <div class="ui-progress mb15">
            <span class="on">1.估值查询</span>
            <span>2.评估定价</span>
            <span>3.风控审核</span>
            <span>4.签订合同</span>
            <span>5.完成</span>
        </div>
        <button type="submit"  id="submit" class="ui-btn-lg ui-btn-danger start_Valuation">
            开始估值
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
    <div class="cartype_page series-list-div" style="border-left:1px solid;position: fixed; right: 0; height: 100%; overflow-x: hidden; background-color: rgba(255,255,255,1); ">
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
<script type="text/javascript" src="/assets/js/libs/jquery.min.js" charset="utf-8"></script>
<script src="/assets/js/car.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    $(function(){
        window.city = '<?php if(!empty($_GET['city'])){ echo $_GET['city'];}else{ echo $current_city['city_id'];} ?>';
        window.prov = '<?php if(!empty($_GET['prov'])){ echo $_GET['prov'];}else{ echo $current_city['prov_id'];} ?>';
        window.provName = "<?php if(!empty($_GET['provName'])){ echo $_GET['provName'];}else{ echo $current_city['prov_name'];} ?>";
        window.cityName = "<?php if(!empty($_GET['cityName'])){ echo $_GET['cityName'];}else{ echo $current_city['city_name'];} ?>";

        window.modelId ="<?php if(!empty($_GET['modelId'])){ echo $_GET['modelId']; } ?>";//车型ID
        window.modelName="<?php if(!empty($_GET['modelName'])){ echo $_GET['modelName']; } ?>";//车型名称

        window.brandId ="<?php if(!empty($_GET['brandId'])){ echo $_GET['brandId']; }?>";//品牌ID
        window.brandName = "<?php if(!empty($_GET['brandName'])){ echo $_GET['brandName']; } ?>";//品牌名称

        window.seriesId="<?php if(!empty($_GET['seriesId'])){ echo $_GET['seriesId']; } ?>";//车系ID
        window.seriesName="<?php if(!empty($_GET['seriesName'])){ echo $_GET['seriesName']; } ?>";//车系名称

        window.cityData = '';
        window.minYear  = '<?php if(!empty($_GET['minYear'])){ echo $_GET['minYear']; } ?>';
        window.maxYear  = '<?php if(!empty($_GET['maxYear'])){ echo $_GET['maxYear']; } ?>';

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
            var vin =$(".VIN_text").val();//车架号
            var regDate = $("#regDate").val();
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
            if(mileAge == "") {
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
            var url = "/car/index?"+
                "vin=" + vin +
                "&prov=" + prov +
                "&provName=" + provName +
                "&city=" + city +
                "&cityName=" + cityName +
                "&zone=" + city +
                "&brandId=" + brandId +
                "&brandName=" + brandName +
                "&seriesId=" + seriesId +
                "&seriesName=" + seriesName +
                "&modelId=" + modelId +
                "&modelName=" + modelName +
                "&regDate=" + regDate +
                "&minYear=" + minYear +
                "&maxYear=" + maxYear +
                "&mile=" + parseFloat(mileAge);
            location.href = url;
        });
    }
</script>
