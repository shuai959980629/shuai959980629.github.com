<h2>个人资料</h2>
<ul class="ui-list ui-list-text ui-list-active ui-list-link ui-border-tb">
    <li class="ui-border-t">
        <div class="ui-list-info">
            <h4 class="ui-nowrap"><?php echo $username;?></h4>
        </div>
        <div class="ui-txt-info">真实姓名</div>
    </li>
    <li class="ui-border-t">
        <div class="ui-list-info">
            <h4 class="ui-nowrap"><?php echo $roleName;?></h4>
        </div>
        <div class="ui-txt-info">职务</div>
    </li>
    <li class="ui-border-t">
        <h4 class="ui-nowrap"><?php echo $mobile;?></h4>
        <div class="ui-txt-info">联系方式</div>
    </li>
</ul>
<div class="ui-btn-wrap">
    <a href="javascript:logout();" class="ui-btn-lg mt15 ui-btn-danger">重新登录</a>
</div>