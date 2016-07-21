<div class="left_page" style="padding-bottom: 65px;">
<div class="ui-tab">
    <ul class="ui-tab-nav ui-border-b">
        <li class="current"><?php echo $type;?>（<?php echo $list['number']; ?>）</li>
    </ul>
    <ul class="ui-tab-content" style="width:100%; padding: 0;">
        <!--待处理业务-->
        <li class="current">
            <div class="list">
                <ul class="ui-list ui-list-link ui-list-active">
                    <?php if(!empty($list['data']) && is_array($list['data'])):?>
                           <?php foreach($list['data'] as $k=>$t):?>
                            <?php $date = date("Y-m-d",$t['created']);if($k !=0){$s = $k-1;}else{$s=$k;}$date_up = date("Y-m-d",$list['data'][$s]['created']);?>
                            <?php if($date != $date_up || $k == 0){ ?>
                                <li class="time-line"><?php echo date("Y-m-d",$t['created'])?></li>
                            <?php }?>
                                <?php if($_GET['flag'] == 2){?>
                                  <?php if($t['type']=='subpact'){ ?>
                                    <li data-url="/pact/index?id=<?php echo $t['aid'];?>"class="ui-border-t click-list-link">
                                  <?php }else{ ?>
                                    <li data-url="/assess/index?id=<?php echo $t['aid'];?>"class="ui-border-t click-list-link">
                                  <?php } ?>
                                <?php }else{?>
                                    <li data-url="/order/detail?id=<?php echo $t['aid'];?>"class="ui-border-t click-list-link">
                                <?php }?>
                                <?php if(empty($t['car_info'])){?>
                                    <div class="ui-list-img car-logo">
                                        <img width="70px" src="/assets/images/pic/cbdefault.png">
                                    </div>
                                    <div class="ui-list-info">
                                        <h5>
                                            <a style="font-weight:bold;color:#0089db;" href="javascript:void(0);">补单[<?php echo $t['plate_no']; ?>]——续借</a>
                                        </h5>
                                    </div>
                                <?php }else{ ?>
                                    <div class="ui-list-img car-logo">
                                        <img width="70px" src="<?php echo $t['car_logo'];?>">
                                    </div>
                                    <div class="ui-list-info">
                                        <h4>【<?php echo $t['store']?>-<?php echo $t['realname']?>】<?php echo $t['title'];?>&nbsp;<?php echo $t['city_name'];?></h4>
                                    </div>
                                <?php } ?>
                                </li>
                            <?php endforeach;?>
                        <?php if(!empty($list['cnt'])){?>
                            <li id="moretodolst" data-total="<?php echo $list['cnt']?>" data-page="1"  class="ui-border-t more">
                                <a  href="javascript:void(0);">点击加载更多</a>
                            </li>
                        <?php }?>

                    <?php endif;?>
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
            var ssss = $('.ui-list-active').find('.time-line');
            var length = ssss.length - 1;
            var date = ssss.eq(length).text();
            $(this).attr('data-page',page);
            $.post('/user/assess_more', {'page':page,date:date,flag:<?php echo $_GET['flag']?>}, function(data){
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