<style>
    .ui-tips{
        padding: 5px 0;
        text-align: left;
        margin-left: -8px;
    }
    p{
        margin-top: 5px;
    }
    .ui-list{
        background:none;
    }
</style>
<div class="left_page" style="padding-bottom: 65px;">
    <ul class="ui-list ui-list-pure ui-border-tb">
        <li class="ui-border-t">
            <div class="ui-tips ui-tips-info">
                <i></i><span style="color: #888888;font-size: 14px;font-weight: bold;">&nbsp;&nbsp;业务进件流程</span>
            </div>
        </li>
        <?php foreach($flow as $Key=>$list){ ?>
            <li class="ui-border-t">
                <div class="ui-tips ui-tips-success">
                    <i></i><span style="color: #888888;font-size: 14px;font-weight: bold;"><?php echo $list['title']; ?></span>
                </div>
                <p style="color:#888888;font-size: 14px;">内&nbsp;&nbsp;&nbsp;容：【<?php echo $list['con']; ?>】</p>
                <p style="color:#888888;font-size: 14px;">下一步：【<?php echo $list['next']; ?>】</p>
                <p style="color:#888888;font-size: 14px;"><?php echo $list['manager']['role']; ?>-<?php echo $list['manager']['name']; ?>&nbsp;&nbsp;&nbsp;<?php echo $list['manager']['created']; ?></p>
            </li>
        <?php } ?>
    </ul>
</div>