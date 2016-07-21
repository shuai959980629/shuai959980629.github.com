<div class="left_page" style="padding-bottom: 65px;">
        <div data-url="/user/todaysum" class="topmsg click-list-link">
            <h1><?php if($curMonth){ echo '¥'.$curMonth;}else{ echo '-';}?></h1>
            <p>本月销售额</p>
        </div>
        <div data-url="/user/salescnt?flag=1" class="ui-tooltips ui-tooltips-warn click-list-link">
            <div class="ui-tooltips-cnt ui-tooltips-cnt-link ui-border-b">
                <b>待处理业务（<?php echo $wait?>）</b>
            </div>
        </div>
        <ul class="ui-list ui-list-text ui-list-active ui-list-link ui-border-tb" style="height: auto; margin-top: -1px;">
            <li data-url="/user/todaysum" class="ui-border-t click-list-link">
                <h4 class="ui-nowrap">
                    <?php if(!empty($done)){ ?>
                    <a href="javascript:void(0);">本月共：<?php echo $done?>/<?php echo $curMonth?>  单/万</a>
                    <?php }else{ ?>
                        暂无
                    <?php } ?>
                </h4>
                <span class="ui-panel-subtitle">本月业绩</span>
            </li>
        </ul>
<?php $data = array('username'=>$realname,'roleName'=>$roleName,'mobile'=>$mobile);echo load_widget('user/personal_data.php',$data); ?>
<script src="/assets/js/libs/frozen.min.js"></script>
</div>