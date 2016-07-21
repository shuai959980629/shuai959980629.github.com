<div class="left_page" style="padding-bottom: 65px;">
<div data-url="/user/assess_news?flag=4" class="topmsg click-list-link" style="background: #00a5e0;">
    <h1><?php echo $month;?></h1>
    <p>本月评估总量</p>
</div>
<div data-url="/user/assess_news?flag=2" class="ui-tooltips ui-tooltips-warn click-list-link">
    <div class="ui-tooltips-cnt ui-tooltips-cnt-link ui-border-b">
        <b>待处理业务（<?php echo $wait;?>）</b>
    </div>
</div>
<ul class="ui-list ui-list-text ui-list-active ui-list-link ui-border-tb" style="height: auto; margin-top: -1px;">
    <li data-url="/user/assess_news?flag=3" class="ui-border-t click-list-link">
        <h4 class="ui-nowrap"><a href="javascript:void(0);">今日评估车辆（<?php echo $today;?>）</a></h4>
        <span class="ui-panel-subtitle">查看详情</span>
    </li>
    <li data-url="/user/assess_news?flag=1" class="ui-border-t click-list-link">
        <h4 class="ui-nowrap"><a href="javascript:void(0);">已完成的评估（<?php echo $all;?>）</a></h4>
        <span class="ui-panel-subtitle">查看详情</span>
    </li>
</ul>
<?php $data = array('username'=>$realname,'roleName'=>$roleName,'mobile'=>$mobile);echo load_widget('user/personal_data.php',$data); ?>
<script src="/assets/js/libs/frozen.min.js"></script>
</div>