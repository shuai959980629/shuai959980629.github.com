/**
 * Created by ZhouShuai on 2016/3/1.
 */
/*根据输入。得到学生等级*/
function getlevel(){
    var score = document.getElementById('score').value;
    //不能为空
    if(score==''){
        alert('请输入查询分数！');
        return false;
    }
    //分数只能是数字
    if(isNaN(score)){
        alert('请输入正确的查询分数！');
        return false;
    }
    switch(true){
        case score>=90:
            alert("1等生");
            break;
        case score>=80&&score<90:
            alert("2等生");
            break;
        case score>=70&&score<80:
            alert("3等生");
            break;
        case score>=60&&score<70:
            alert("4等生");
            break;
        case score>=50&&score<60:
            alert("5等生");
            break;
        case score>=40&&score<50:
            alert("6等生");
            break;
        case score>=30&&score<40:
            alert("7等生");
            break;
        case score>=20&&score<30:
            alert("8等生");
            break;
        case score>=10&&score<20:
            alert("9等生");
            break;
        case score<10:
            alert("10等生");
            break;
    }
}