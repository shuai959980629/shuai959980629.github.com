<div class="left_page" style="padding-bottom: 65px;">
<h2><b>销售排行榜</b></h2>
<ul class="ui-list ui-list-text ui-list-link ui-border-tb ui-list-active" style="height: auto; margin-top: -1px;">
    <?php $index = 1; foreach($rank as $key=>$list){ ?>
        <li class="ui-border-t">
            <a href="javascript:void(0);">
                <b class="ui-nowrap">第<?php echo $index; ?>名：<?php echo $list['storesName'];?>-<?php echo $list['employee'];?>（<?php echo FormatMoney($list['sum']);?>万）</b>
            </a>
        </li>
    <?php $index++; } ?>
</ul>
</div>