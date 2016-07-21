<div class="left_page" style="padding-bottom: 65px;">
<div class="ui-tab">
    <ul class="ui-tab-nav ui-border-b">
        <li class="current"><?php echo $type;?>（<?php echo sizeof($list); ?>）</li>
    </ul>
    <ul class="ui-tab-content" style="width:100%; padding: 0;">
        <!--待处理业务-->
        <li class="current">
            <div class="list">
                <ul class="ui-list ui-list-active">
                    <?php if(!empty($list)){ 
                            foreach ($list as $k =>$t) {
                        ?>
                        <?php $date = date("Y-m-d",$t['created']);if($k !=0){$s = $k-1;}else{$s=$k;}$date_up = date("Y-m-d",$list[$s]['created']);?>
                        <?php if($date != $date_up || $k == 0){ ?>
                            <li class="time-line"><?php echo date("Y-m-d",$t['created'])?></li>
                        <?php }?>
                        <!--<li data-url="/pact/index?id=<?php /*echo $t['id'];*/?>" class="ui-border-t click-list-link">
                            <div class="ui-list-img car-logo">
                                <img width="70px" src="<?php /*echo $t['car_logo'];*/?>">
                            </div>
                            <div class="ui-list-info">
                                <h5><b class="fcolor_red">风控价：¥<?php /*echo $t['sug_money'];*/?>~¥<?php /*echo $t['max_money'];*/?>万</b></h5>
                                <h4>【<?php /*echo $t['store'];*/?>-<?php /*echo $t['realname'];*/?>】<?php /*echo $t['model'];*/?>&nbsp;<?php /*echo $t['zone']*/?></h4>
                            </div>
                        </li>-->

                            <li data-url="/pact/index?id=<?php echo $t['id'];?>" class="ui-border-t click-list-link">
                                <div class="ui-list-img car-logo <?php echo $t['state'];?>">
                                    <img src="<?php echo $t['car_logo'];?>">
                                    <div class="state">
                                        <p>
                                            <span><?php echo $t['store']?>-<?php echo $t['realname']?></span>
                                            <?php echo $t['deal_flag']; ?>
                                        </p>
                                        <em><?php echo $t['status'];?></em>
                                    </div>
                                </div>
                                <div class="ui-list-info">
                                    <h4><?php echo $t['model'];?></h4>
                                    <?php if(!empty( $t['ui_list_info'])){ echo $t['ui_list_info'];} ?>
                                </div>
                            </li>
                        <?php }?>
                        <?php if(sizeof($list)>4){?>
                        <li id="moretodolst" data-total="" data-page="1"  class="ui-border-t more">
                            <a  href="javascript:void(0);">点击加载更多</a>
                        </li>
                        <?php }?>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </li>
        
    </ul>
</div>
<script src="/assets/js/libs/frozen.min.js"></script>
<script>
    (function() {
        $("#moretodolst").tap(function(){
            var total = $(this).attr('data-total');
            var page =$(this).attr('data-page');
            ++page;
            $(this).attr('data-page',page);
            var ssss = $('.ui-list-active').find('.time-line');
            var length = ssss.length - 1;
            var date = ssss.eq(length).text();
            $.get('/order/todolst',{'page':page,'date':date}, function(data){
                if(!$.trim(data).length){
                    $("#moretodolst").remove();
                }else{
                    $("#moretodolst").before(data);
                    if(page==total){$("#moretodolst").remove();}
                }
            }, 'text');
        });
    })();
</script>
</div>