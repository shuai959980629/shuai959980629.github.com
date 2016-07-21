<div class="left_page" style="padding-bottom: 65px;">
<?php foreach($month as $key=>$items){ ?>
    <h2><b><?php echo $key;?>销售总额：<?php echo FormatMoney($items['sum']);?>万</b></h2>
    <ul class="ui-list ui-list-text ui-list-link ui-border-tb ui-list-active" style="height: auto; margin-top: -1px;">
        <?php foreach($items['list'] as $k=>$list){ ?>
        <li class="ui-border-t">
            <a href="javascript:void(0);">
                <b class="ui-nowrap"><?php echo $list['sdate'];?>(<?php echo FormatMoney($list['total']);?>万)</b>
            </a>
        </li>
        <?php } ?>
    </ul>
<?php } ?>
</div>
