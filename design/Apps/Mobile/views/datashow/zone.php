<div class="left_page" style="padding-bottom: 65px;">
<h2><b>本月全国总业绩：<?php if($total){  echo FormatMoney($total).'万';}else{ echo '--';}?></b></h2>
<ul class="ui-list ui-list-text ui-list-link ui-border-tb ui-list-active" style="height: auto; margin-top: -1px;">
    <?php foreach($zone as $key=>$list){ ?>
    <li class="ui-border-t">
        <a href="javascript:void(0);">
            <b class="ui-nowrap">第<?php echo $key+1; ?>名：<?php echo $list['storesName'];?>（<?php echo FormatMoney($list['sum']);?>万）</b>
        </a>
    </li>
    <?php } ?>
</ul>
</div>