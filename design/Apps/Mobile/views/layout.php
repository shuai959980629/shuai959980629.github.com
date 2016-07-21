<?php $CI_MODEL_LAYOUT = $this->uri->segment(1, 0); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <title><?php if(!empty($title)){ echo $title;}else{ echo '௵~周の业务管理系统'; } ?></title>
    <link rel="stylesheet" href="/assets/css/com.min.css" />
</head>
<body ontouchstart="" class="detail">
<?php echo $ci_body; ?>
<div class="cop"><em></em>&copy;版权所有--௵~周<em></em></div>
<?php if($_SESSION['user']['office']=='admin'){ ?>
    <footer class="ui-footer ui-footer-stable ui-btn-group ui-border-t">
        <ul class="ui-tiled">
            <li>
                <a href="/"><i class="icon iconfont icon-home"></i><div>首页</div></a>
            </li>
            <li <?php if($CI_MODEL_LAYOUT==='user'||$CI_MODEL_LAYOUT=='datashow'){ echo 'class="current"';} ?>>
                <a href="/user"><i class="icon iconfont icon-weiguanzhu"></i><div>个人中心</div></a>
            </li>
        </ul>
    </footer>
<?php }else{ ?>
<footer class="ui-footer ui-footer-stable ui-btn-group ui-border-t">
<div class="main-menu">
    <a class="menu-link" href="/home">首页</a>
    <a class="menu-img" href="/start"><img src="/assets/images/icon1.png"></a>
    <a class="menu-link" href="/yw_list/yw_wait" style="right: 0; left: auto;">我的业务</a>
</div>
</footer>
<?php } ?>
</body>
</html>
