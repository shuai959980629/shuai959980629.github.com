/*获取两个数和运算符*/
function operation(){
    var num1  = document.getElementById('num1').value;//数字1
    var num2  = document.getElementById('num2').value;//数字2
    var oper  = document.getElementById('oper').value;//运算符
    var resultEl = document.getElementById('result');//显示结果的元素
    if(num1==''){
        alert("请输入第一个数！");
        return false;
    }
    if(num2==''){
        alert("请输入第二个数！");
        return false;
    }
    if(oper=='/'&&num2==0){
        alert("除数不能为零！");
        return false;
    }
    var result = calculator(num1,num2,oper);
    resultEl.value = result;
}




/*计算*/
function calculator(num1,num2,oper){
    var res=0;
    switch(oper){
        case "+":
            num1 = parseFloat(num1);
            num2 = parseFloat(num2);
            res=num1+num2;
            break;
        case "-":
            num1 = parseFloat(num1);
            num2 = parseFloat(num2);
            res=num1-num2;
            break;
        case "*":
            res=num1*num2;
            break;
        case "/":
            res=num1/num2;
            break;
    }
    return res;
}