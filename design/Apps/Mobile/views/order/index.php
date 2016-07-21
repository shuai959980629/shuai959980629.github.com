<div class="left_page" style="padding-bottom: 65px;">
<div class="ui-tab">
    <ul class="ui-tab-content" style="width:100%;padding-top: 0;">
        <!--待处理业务-->
        <li class="current">
            <div class="list">
                <ul class="ui-list ui-list-link ui-list-active">
                    <?php if(!empty($array)){ ?>
                        <?php foreach($array['message'] as $key=>$value){ ?>
                            <li class="time-line"><?php echo $key;?></li>
                            <?php foreach($value as $k=>$v){?>
                                <li data-url="/order/detail?id=<?php echo $v['id'];?>" class="ui-border-t click-list-link">
                                    <div class="ui-list-img car-logo <?php echo $v['state'];?>">
                                        <img src="<?php echo $v['car_logo_url'];?>">
                                        <div class="state">
                                            <p>
                                                <span><?php echo $v['store']?>-<?php echo $v['realname']?></span>
                                                <?php echo $v['deal_flag']; ?>
                                            </p>
                                            <em><?php echo $v['status'];?></em>
                                        </div>
                                    </div>
                                    <div class="ui-list-info">
                                        <h4><?php echo $v['modelName'];?></h4>
                                        <?php if(!empty( $v['ui_list_info'])){ echo $v['ui_list_info'];} ?>
                                    </div>
                                </li>
                            <?php } ?>
                        <?php } ?>
                        <?php if(!empty($array['num']) && $array['num']>1){  ?>
                            <li id="moretodolst" data-total="<?php echo $array['num'];?>" data-page="1"  class="ui-border-t more">
                                <a href="javascript:void(0);">点击加载更多</a>
                            </li>
                        <?php } ?>
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
            $.get('/order/todolst', {'page':page,'date':date}, function(data){
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