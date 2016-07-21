<ul class="ui-list ui-list-link ui-border-tb" style="height:160px;margin-top: -1px;">
    <li class="ui-border-t">
        <div class="ui-list-img car-logo">
            <img src="<?php echo str_replace('static','assets',$car['car_logo_url']); ?>">
        </div>
        <div class="ui-list-info">
            <h4><?php echo $car['modelName'];?>(<?php echo $car['price'];?>万)</h4>
        </div>
    </li>
    <dl>
        <dt>
            <p>所在城市</p>
            <h4><?php echo $car['zone'];?></h4>
        </dt>
        <dt class="linelr">
            <p>上牌日期</p>
            <h4><?php echo $car['registerDate'];?></h4>
        </dt>
        <dt>
            <p>行驶里程</p>
            <h4><?php echo $car['mile'];?>万公里</h4>
        </dt>
    </dl>
</ul>