for(i=0;i<result.length;i++)
    {
            var time = document.getElementById("time");
            time.classList.add('time');

            var icon = document.createElement("div");
            time.appendChild(icon);
            icon.classList.add('time-icon');

            var line = document.createElement("div");
            time.appendChild(line);
            line.classList.add('time-line');

            var br = document.createElement("br");
            line.appendChild(br);

            //开始向表格里填内容
            var number = document.createElement("p");
            var node = document.createTextNode("Number:" + "   "+(i + 1));
            number.appendChild(node);
            // console.log(ti);
            // var element = document.getElementById("div1");
            // console.log(element);
            line.appendChild(number);
            number.classList.add('time-bookname');

            var bookname = document.createElement("p");
            var node = document.createTextNode("Bookname:" + "   "+ result[i].bookname);
            bookname.appendChild(node);
            line.appendChild(bookname);
            bookname.classList.add('time-bookname');

            var author = document.createElement("p");
            var node = document.createTextNode("Author:" + "   "+ result[i].author);
            author.appendChild(node);
            line.appendChild(author);
            author.classList.add('time-bookname');

            var borrowdate = document.createElement("p");
            var node = document.createTextNode("Borrowdate:" + "    "+ result[i].borrowdate);
            borrowdate.appendChild(node);
            line.appendChild(borrowdate);
            borrowdate.classList.add('time-bookname');
            console.log(typeof result[i].borrowdate);

            var returndate = document.createElement("p");
            var node = document.createTextNode("Returndate:" + "    "+ result[i].returndate);
            returndate.appendChild(node);
            line.appendChild(returndate);
            returndate.classList.add('time-bookname');

            var bookreturn = document.createElement("p");
            var node = document.createTextNode("Return:" + "   "+ result[i].bookreturn);
            bookreturn.appendChild(node);
            line.appendChild(bookreturn);
            bookreturn.classList.add('time-bookname');

            
            var br = document.createElement("br");
                line.appendChild(br);

                var br = document.createElement("br");
                line.appendChild(br);

                var br = document.createElement("br");
                time.appendChild(br);
            //日期比较，看是否到期
            if(result[i].bookreturn == 'No')
            {
                var date = new Date(result[i].returndate);
                var time = date.getTime();
                time = time/1000;
                console.log(time);
                //日期越晚，时间戳越大
                if(today>time)//图书过期
                {
                    alert("您的图书 "+result[i].bookname+"已过期，请尽快归还，否则借书受阻");
                    //JS修改样式
                    icon.style.backgroundColor = 'coral';
                    line.style.backgroundColor = 'coral';
                }
                else if(today == time)
                {
                    alert("您的图书 "+result[i].bookname+"今日到期，请及时归还");
                    //JS修改样式
                    icon.style.backgroundColor = '#87cefb';
                    line.style.backgroundColor = '#87ceeb';
                }
            }
            else {
                    icon.style.backgroundColor = '#32cd32';
                    line.style.backgroundColor = '#32cd32';
                    line.style.color = '#DDD';
                    bookname.style.borderColor = '#DDD';
                    number.style.borderColor = '#DDD';
                    author.style.borderColor = '#DDD';
                    borrowdate.style.borderColor = '#DDD';
                    bookreturn.style.borderColor = '#DDD';
                    line.removeChild(returndate);
            }
    }
