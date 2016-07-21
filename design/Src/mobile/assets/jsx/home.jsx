var Home = React.createClass({
	render:function(){
		return (
			<div>
				<img src="assets/images/icon-index.gif"/>
				<h1>௵~周の管理系统</h1>
				<div className="index-main">
					<a href="/user">个人中心</a>
				</div>	
			</div>
		);
	}
});
ReactDOM.render(
  <Home />,
  document.getElementById('container')
);