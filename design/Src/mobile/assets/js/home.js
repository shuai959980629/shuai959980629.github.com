var Home = React.createClass({displayName: "Home",
	render:function(){
		return (
			React.createElement("div", null, 
				React.createElement("img", {src: "assets/images/icon-index.gif"}), 
				React.createElement("h1", null, "௵~周の管理系统"), 
				React.createElement("div", {className: "index-main"}, 
					React.createElement("a", {href: "/user"}, "个人中心")
				)	
			)
		);
	}
});
ReactDOM.render(
  React.createElement(Home, null),
  document.getElementById('container')
);