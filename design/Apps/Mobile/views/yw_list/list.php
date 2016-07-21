<?php
$type = $this->uri->segment(2,0);
$param = explode('_', $type);
?>
<link rel="stylesheet" href="/assets/css/data_list.css" />
<header class="ui-header ui-header-stable ui-border-b toplist">
    <a <?php if($type=='yw_wait'){ ?> class="on" <?php } ?> <?php if($param[0]!='yw'){ ?> style="display: none;"  <?php } ?> href="/yw_list/yw_wait">待处理</a>
    <a <?php if($type=='yw_done'){ ?> class="on" <?php } ?> <?php if($param[0]!='yw'){ ?> style="display: none;"  <?php } ?> href="/yw_list/yw_done">已处理</a>
    <a <?php if($type=='as_pending'){ ?> class="on" <?php } ?> <?php if($param[0]!='as'){ ?> style="display: none;"  <?php } ?> href="/yw_list/as_pending">进行中</a>
    <a <?php if($type=='as_done'){ ?> class="on" <?php } ?> <?php if($param[0]!='as'){ ?> style="display: none;"  <?php } ?> href="/yw_list/as_done">已完成</a>
    <a href="javascript:void(0);">
        <span>
            <?php 
                if($param[0]=='yw'){
                    echo '我的业务';
                }
                if($param[0]=='as'){
                    echo '我的进件';
                }
            ?>
        </span>
        <em>
            <i data-url="/yw_list/yw_wait"    class="click-list-link">我的业务</i>
            <i data-url="/yw_list/as_pending" class="click-list-link">我的进件</i>
        </em>
    </a>
</header>
<div class="ui-tab" style="padding-bottom: 60px;padding-top:50px;">
    <ul class="ui-list ui-list-link ui-border-tb ui-list-active" style="height: auto;">
        <?php if(!empty($data)){  ?>
            <?php foreach($data as $Key=>$list){ ?>
                <li data-url="<?php echo $list['url_detail'];?>" class="ui-border-t click-list-link">
                    <div class="ui-list-img car-logo <?php echo 'state-'.$list['color'];?>">
                        <img src="<?php echo $list['car_logo'];?>">
                        <div class="state">
                            <p>
                                <span><?php echo $list['store'];?>-<?php echo $list['realname'];?></span>
                                <?php echo $list['icon'];?>
                            </p>
                            <em><?php echo $list['status_show'];?></em>
                        </div>
                    </div>
                    <div class="ui-list-info">
                        <h4><?php echo $list['model'];?></h4>
                        <h5 class="ui-nowrap"><b class="<?php echo 'fcolor_'.$list['color'];?>"><?php echo $list['show_last_price'];?>万</b></h5>
                        <h6 class="fcolor_gray3">最后处理：<?php echo $list['modify_time']; ?></h6>
                    </div>
                </li>
            <?php } ?>
        <?php } ?> 
        <?php if(!empty($num) && $num>1){  ?>
            <li id="moretodolst" data-total="<?php echo $num;?>" data-page="1"  class="ui-border-t more" data-type="<?php echo $type; ?>">
                <a href="javascript:void(0);">点击加载更多</a>
            </li>
        <?php } ?>
    </ul>
</div>
<script src="/assets/js/libs/frozen.min.js"></script>
<script>
    (function(){
        $("#moretodolst").tap(function(){
            var total  = $(this).attr('data-total');
            var type   = $(this).attr('data-type');
            var page   = $(this).attr('data-page');
            ++page;
            $(this).attr('data-page',page);
            $.get('/yw_list/more', {'page':page,'type':type}, function(data){
                if(!$.trim(data).length){
                    $("#moretodolst").remove();
                }else{
                    $("#moretodolst").before(data);
                    if(page==total){
                        $("#moretodolst").remove();
                    }
                }
            }, 'text');
        });
    })();
</script>