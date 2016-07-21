<div class="left_page" style="padding-bottom: 65px;">
<ul class="ui-list ui-list-text ui-list-link ui-border-tb" style="height: auto; margin-top: -1px;">
    <li class="ui-border-t">
        <h4 class="ui-nowrap"><b>今日全国总业绩</b></h4><span><b><?php if($todaySum){ echo '¥'.FormatMoney($todaySum).'万';}else{ echo '--';}?></b></span>
    </li>
</ul>
<div class="ui-form ui-border-t">
    <form action="#">
        <div class="ui-form-item ui-border-b">
            <label>分公司</label>
            <div class="ui-select">
                <select id="zone" name="zone">
                    <?php foreach($zone as $Key=>$list){ ?>
                        <option <?php if($stores==$list['stores']){ echo 'selected'; } ?>  value="<?php echo $list['stores'];?>"><?php echo $list['storesName'];?>（<?php echo FormatMoney($list['sum']);?>万）</option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </form>
</div>
<h2><?php echo date('Y-m-d');?><span id="stores"><?php echo $storesName; ?></span>业绩</h2>
<div class="ui-tab">
    <ul class="ui-tab-nav ui-border-b">
        <li class="current">进行中（<?php if(!empty($doing['cnt'])){echo $doing['cnt'];}else{echo 0;}?>）</li>
        <li>已完成（<?php if(!empty($done['cnt'])){echo $done['cnt'];}else{echo 0;}?>）</li>
        <li>拒贷（<?php if(!empty($deny['cnt'])){echo $deny['cnt'];}else{echo 0;}?>）</li>
    </ul>
    <ul class="ui-tab-content" style="width:100%; padding: 0;">
        <li class="current">
            <div class="list">
                <ul class="ui-list ui-list-link ui-list-active first">
                    <?php if(!empty($doing['message'])){ ?>
                        <?php foreach($doing['message'] as $key=>$value){ ?>
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
                        <?php if(!empty($doing['num']) && $doing['num']>1){  ?>
                            <li id="moretodolst" data-total="<?php echo $doing['num'];?>" data-page="1"  class="ui-border-t more" data-stores="<?php echo $stores;?>">
                                <a href="javascript:void(0);">点击加载更多</a>
                            </li>
                        <?php } ?>
                    <?php } ?>
                </ul>
            </div>
        </li>
        <!--已完成业务-->
        <li style="height: 0;">
            <ul class="ui-list ui-list-link ui-list-active two">
                <?php if(!empty($done['message'])){ ?>
                    <?php foreach($done['message'] as $key=>$value){ ?>
                        <li class="time-line"><?php echo $key;?></li>
                        <?php foreach($value as $k=>$v){?>
                            <li data-url="/order/detail?id=<?php echo $v['aid'];?>" class="ui-border-t click-list-link">
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
                    <?php if(!empty($done['num']) && $done['num']>1){  ?>
                        <li id="moredolist" data-total="<?php echo $done['num'];?>" data-page="1"  class="ui-border-t more" data-stores="<?php echo $stores;?>">
                            <a href="javascript:void(0);">点击加载更多</a>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>
        </li>
        <!--拒贷-->
        <li style="height: 0;">
            <div class="list">
                <ul class="ui-list ui-list-link ui-list-active three">
                    <?php if(!empty($deny['message'])){ ?>
                        <?php foreach($deny['message'] as $key=>$value){ ?>
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
                        <?php if(!empty($deny['num']) && $deny['num']>1){  ?>
                            <li id="morecancelist" data-total="<?php echo $deny['num'];?>" data-page="1"  class="ui-border-t more" data-stores="<?php echo $stores;?>">
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
        var tab = new fz.Scroll('.ui-tab', {
            role: 'tab',
            autoplay: true,
            interval: 70000
        });
        tab.on('beforeScrollStart', function(from, to) {
            console.log(from, to);
        });
        tab.on('scrollEnd', function(curPage) {
            console.log(curPage);
        });

        $('.ui-list').on("tap",".click-list-link",function(){
            var url = $(this).attr('data-url');
            window.location.href =url;
        });


        $("#zone").change(function(){
            var stores = $(this).val();
            var url = "/datashow/today?stores="+stores;
            location.href = url;
        });

        $("#moretodolst").tap(function(){
            var total = $(this).attr('data-total');
            var stores = $(this).attr('data-stores');
            var page =$(this).attr('data-page');
            ++page;
            $(this).attr('data-page',page);
            var ssss = $('.first').find('.time-line');
            var length = ssss.length - 1;
            var date = ssss.eq(length).text();
            $.get('/datashow/doing', {'page':page,'date':date,'stores':stores}, function(data){
                if(!$.trim(data).length){
                    $("#moretodolst").remove();
                }else{
                    $("#moretodolst").before(data);
                    if(page==total){$("#moretodolst").remove();}
                }
            }, 'text');
        });

        $("#moredolist").tap(function(){
            var total = $(this).attr('data-total');
            var stores = $(this).attr('data-stores');
            var page =$(this).attr('data-page');
            ++page;
            $(this).attr('data-page',page);
            var ssss = $('.two').find('.time-line');
            var length = ssss.length - 1;
            var date = ssss.eq(length).text();
            $.get('/datashow/done', {'page':page,'stores':stores,'date':date}, function(data){
                if(!$.trim(data).length){
                    $("#moredolist").remove();
                }else{
                    $("#moredolist").before(data);
                    if(page==total){$("#moredolist").remove();}
                }
            }, 'text');
        });

        $("#morecancelist").tap(function(){
            var total = $(this).attr('data-total');
            var stores = $(this).attr('data-stores');
            var page =$(this).attr('data-page');
            ++page;
            $(this).attr('data-page',page);
            var ssss = $('.three').find('.time-line');
            var length = ssss.length - 1;
            var date = ssss.eq(length).text();
            $.get('/datashow/deny', {'page':page,'stores':stores,'date':date}, function(data){
                if(!$.trim(data).length){
                    $("#morecancelist").remove();
                }else{
                    $("#morecancelist").before(data);
                    if(page==total){$("#morecancelist").remove();}
                }
            }, 'text');
        });


    })();
</script>
</div>