//获取对象
var oper = ''; //运算符
var flag = 0; //标签 0:num1 num2初始化， 1：num1 字符链接。2:num2 赋值
var num1 = ''; //操作数1
var num2 = ''; //操作数2
var resultEl = document.getElementById('result'); //结果
var calculatorEl = document.getElementById('calculator'); //计算器容器
//事件监听
var eventUtil = {
    addHandler: function(element, type, handler) {
        if (element.addEventListener) {
            element.addEventListener(type, handler, false);
        } else if (element.attachEvent) {
            element.attachEvent("on" + type, handler);
        } else {
            element["on" + type] = handler;
        }
    },
    removeHandler: function(element, type, handler) {
        if (element.removeEventListener) {
            element.removeEventListener(type, handler, false);
        } else if (element.detachEvent) {
            element.detachEvent("on" + type, handler);
        } else {
            element["on" + type] = null;
        }
    }
};
//=============================执行操作==========================================
init();

//事件监听，绑定
function init() {
    eventUtil.addHandler(calculatorEl, 'click', calculat);
}

//绑定计算handler
function calculat(e) {
    var ele = e.target || e.srcElement;
    var data = ele.getAttribute('data-type');
    if (data) {
        if (isNaN(data)) {
            //计算操作
            operHandler(data);
        } else {
            //输出数字
            inputNum(data);
        }
    } else {
        return false;
    }
}

//删除
function delte() {
    if (!isNaN(resultEl.value)) {
        if (resultEl.value.length == 1) {
            resultEl.value = 0;
        } else {
            resultEl.value = resultEl.value.substring(0, resultEl.value.length - 1);
        }
        flag = 2;
        num2 = resultEl.value;
    } else {
        flag = 0;
        resultEl.value = '数字不合法！';
    }

}

//全清：
function clear() {
    oper = '';
    flag = 0;
    num1 = '';
    num2 = '';
    resultEl.value = 0;
}



//正数、负数
function abs() {
    flag = 0;
    if (!isNaN(resultEl.value)) {
        resultEl.value = -resultEl.value;
        num1 = resultEl.value;
        num2 = '';
    } else {
        resultEl.value = '数字不合法！';
    }
}

//倒数
function recip() {
    flag = 0;
    if (!isNaN(resultEl.value) && resultEl.value != 0) {
        resultEl.value = 1 / parseFloat(resultEl.value);
        num1 = resultEl.value;
        num2 = '';
    } else {
        resultEl.value = '数字不合法！';
    }
}

//%
function percent() {
    flag = 0;
    if (!isNaN(resultEl.value)) {
        resultEl.value = parseFloat(resultEl.value) / 100;
        num1 = resultEl.value;
        num2 = '';
    } else {
        resultEl.value = '数字不合法！';
    }
}

function sqrt() {
    flag = 0;
    if (!isNaN(resultEl.value)) {
        resultEl.value = Math.sqrt(parseFloat(resultEl.value));
        num1 = resultEl.value;
        num2 = '';
    } else {
        resultEl.value = '数字不合法！';
    }
}
//小数点
function point() {
    if (!isNaN(resultEl.value)) {
        if (resultEl.value.indexOf('.') < 0) {
            resultEl.value += '.';
            if (flag == 1) {
                num1 = resultEl.value;
            } else {
                num2 = resultEl.value;
            }
        }
    } else {
        flag = 0;
        resultEl.value = '数字不合法！';
    }
}

/**
 * @计算操作
 */
function operHandler(data) {
    switch (data) {
        case 'delte':
            delte();
            break;
        case 'clear':
            clear();
            break;
        case 'abs':
            abs();
            break;
        case 'recip':
            recip();
            break;
        case '%':
            percent();
            break;
        case '√':
            sqrt();
            break;
        case '.':
            point();
            break;
        case "＋":
            oper = oper || '+';
            change();
            oper = '+';
            break;
        case "－":
            oper = oper || '-';
            change();
            oper = '-';
            break;
        case "×":
            oper = oper || '*';
            change();
            oper = '*';
            break;
        case "÷":
            oper = oper || '/';
            change();
            oper = '/';
            break;
        case '＝':
            change();
            break;
        default:
            clear();
            break;
    }
}

function change(){
    flag = 2;
    if (num2 != '' && oper != '') {
        var res = calculator(num1, num2, oper);
        if(isNaN(res)){
            flag = 0;
            resultEl.value = res;
        }else{
            num1 = res;
            resultEl.value = num1;
            num2 = '';
        }
    }
}

/**
 *@输出数字,显示屏中展现。保存num1 num2 方便计算
 */
function inputNum(data) {
    switch (flag) {
        case 0:
            num1 = data;
            num2 = '';
            flag = 1;
            resultEl.value = parseFloat(num1);
            break;
        case 1:
            num1 += data;
            resultEl.value = parseFloat(num1);
            break;
        case 2:
            num2 += data;
            resultEl.value = parseFloat(num2);
            break;
    }
}


/**
 * @加减乘除运算
 */
function calculator(num1, num2, oper) {
    var res = 0;
    switch (oper) {
        case "+":
            num1 = parseFloat(num1);
            num2 = parseFloat(num2);
            res = num1 + num2;
            break;
        case "-":
            num1 = parseFloat(num1);
            num2 = parseFloat(num2);
            res = num1 - num2;
            break;
        case "*":
            res = parseFloat(num1 * num2);
            break;
        case "/":
            if (parseFloat(num2) == 0) {
                res = '除数不能为零！';
            }else{
                res = parseFloat(num1 / num2);
            }
            break;
    }
    return res;
}
