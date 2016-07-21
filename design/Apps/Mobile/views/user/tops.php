<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
		<meta name="format-detection" content="telephone=no">
		<title>个人中心-销售排行榜</title>
		<link rel="stylesheet" href="/assets/css/com.min.css" />
	</head>

	<body ontouchstart="" class="detail">
		<h2><b>成都分公司销售排行榜</b></h2>
		<ul class="ui-list ui-list-text ui-list-link ui-border-tb" style="height: auto; margin-top: -1px;">
			<?php foreach ($list as $k=>$v) {?>
			<li class="ui-border-t">
				<h4 class="ui-nowrap"><div class="ui-badge"><?php echo $k+1?></div>&nbsp;<b><?php echo $v['employee']?></b></h4><span>￥<?php echo $v['loan_amount']?></span>
			</li>
			<?php }?>
		</ul>
	</body>

</html>