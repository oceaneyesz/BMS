for(i=0;i<books.length;i++)
    {


    var tou_code=document.createElement("code");       
    var tou_lent=document.createElement("lent_time");
        tou_code.appendChild(lent_time);
    var tou_return=document.createElement("return_time");
        tou_code.appendChild(return_time);

        tou_code.innerHTML=books[i].book_code;
        tou_lent.innerHTML=books[i].lent_time;
        tou_return.innerHTML=books[i].return_time;

    }
function autocreate(){
//创建table表格
var table=document.createElement("table");
//获取行数值
var line=document.getElementById("line").value;
//获取列数值
var list=document.getElementById("list").value;
for(var i=1;i<=line;i++){
//alert(line);
//创建tr
var tr=document.createElement("tr");
for(var j=1;j<=list;j++){
//alert(list);
//创建td
var td=document.createElement("td");
td.innerHTML=i*j;
tr.appendChild(td);
}
table.appendChild(tr); 
}
document.getElementById("d1").appendChild(table);