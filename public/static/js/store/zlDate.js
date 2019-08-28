/*本代码由素材家园原创，转载请保留网址：www.sucaijiayuan.com*/
// 本地存储
var dateStorage = [];

var date = new Array() ;
var price =new Array() ;
// 获取日期数组
var data = new Array();
var changeData;

// 获取修改后的数据
function setData() {
    $('#calendar_choose td.on').each(function(){
        date.push($(this).attr('date'));
        price.push($(this).attr('price'));
    })
    for (var i = 0;i<date.length;i++){
        for(var j = 0; j<dateStorage.length; j++){
            if(dateStorage[j].Date == date[i]){
                dateStorage.splice(j,1);
            }
        }
        dateStorage.push({"Date":date[i],"Price":price[i]})
    }
    dateStorage.sort(function(a,b){
        return b['Date'] < a['Date'] ? 1 : -1
    })
}

var obj = { date: new Date(), year: -1, month: -1, priceArr: [] };
var htmlObj = { header: "", left: "", right: "" };
var elemId = null;
var dateArr = new Array();
// function getAbsoluteLeft(objectId) {
//    var o = document.getElementById(objectId)
//    var oLeft = o.offsetLeft;
   
//     while (o.offsetParent != null) {
//         console.log(o.offsetParent)
//         oParent = o.offsetParent
//         oLeft += oParent.offsetLeft
//         o = oParent
//     }
//     console.log(oLeft)
//     return oLeft
    
// }
//获取控件上绝对位置
// function getAbsoluteTop(objectId) {
//    var o = document.getElementById(objectId);
//    var oTop = o.offsetTop + o.offsetHeight + 10;
//     while (o.offsetParent != null) {
//         oParent = o.offsetParent
//         oTop += oParent.offsetTop
//         o = oParent
//     }
//     return oTop
// }
//获取控件宽度
function getElementWidth(objectId) {
    x = document.getElementById(objectId);
    return x.clientHeight;
}
var pickerEvent = {
    Init: function (elemid) {
        if (obj.year == -1) {
            dateUtil.getCurrent();
        }
        for (var item in pickerHtml) {
            pickerHtml[item]();
        }
        var p = document.getElementById("calendar_choose");
        if (p != null) {
            document.body.removeChild(p);
        }
        var html = '<div id="calendar_choose" class="calendar" style="display: block; position: fixed;">'
        html += htmlObj.header;
        html += '<div class="basefix" id="bigCalendar" style="display: block;">';
        html += htmlObj.left;
        html += htmlObj.right;
        html += '<div style="clear: both;"></div>';
        html += "</div></div>";
        elemId=elemid;
        var elemObj = document.getElementById(elemid);
        $(document.body).append(html);
        document.getElementById("picker_last").onclick = pickerEvent.getLast;
        document.getElementById("picker_next").onclick = pickerEvent.getNext;
		document.getElementById("picker_today").onclick = pickerEvent.getToday;
        // document.getElementById("calendar_choose").style.left = getAbsoluteLeft(elemid)+"px";
        // document.getElementById("calendar_choose").style.top  = getAbsoluteTop(elemid)+"px";
        document.getElementById("calendar_choose").style.zIndex = 1000;
        var tds = document.getElementById("calendar_tab").getElementsByTagName("td");
        for (var i = 0; i < tds.length; i++) {
            if (tds[i].getAttribute("date") != null && tds[i].getAttribute("date") != "" && tds[i].getAttribute("price") != "-1") {
                tds[i].onclick = function () {
                    commonUtil.chooseClick(this)
                };
            }
        }

    },
    getLast: function () {
        dateUtil.getLastDate();
        pickerEvent.Init(elemId);
        setData()
    },
    getNext: function () {
        dateUtil.getNexDate();
        pickerEvent.Init(elemId);
        setData()
    },
	getToday:function(){
		dateUtil.getCurrent();
		pickerEvent.Init(elemId);
	},
    setPriceArr: function (arr) {
        obj.priceArr = arr;
    },
    remove: function () {
        var p = document.getElementById("calendar_choose");
        if (p != null) {
            document.body.removeChild(p);
        }
    },
    isShow: function () {
        var p = document.getElementById("calendar_choose");
        if (p != null) {
            return true;
        }
        else {
            return false;
        }
    }
}
var pickerHtml = {
    getHead: function () {
        var head = '<div class="header_title"><span style="font-size: 16px;">选择日期</span><div class="btns"><span class="cancel">取消</span><span class="confirm" >确定</span><table></table></div></div><ul class="calendar_num basefix"><li class="bold">六</li><li>五</li><li>四</li><li>三</li><li>二</li><li>一</li><li class="bold">日</li><li class="picker_today bold" id="picker_today">回到今天</li></ul>';
        htmlObj.header = head;
    },
    getLeft: function () {
        var left = '<div class="calendar_left pkg_double_month"><p class="date_text">' + obj.year + '年<br>' + obj.month + '月</p><a href="javascript:void(0)" title="上一月" id="picker_last" class="pkg_circle_top">上一月</a><a href="javascript:void(0)" title="下一月" id="picker_next" class="pkg_circle_bottom ">下一月</a></div>';
        htmlObj.left = left;
    },
    getRight: function () {
        var days = dateUtil.getLastDay();
        var week = dateUtil.getWeek();
        var html = '<table id="calendar_tab" class="calendar_right"><tbody>';
        var index = 0;
        for (var i = 1; i <= 42; i++) {
            if (index == 0) {
                html += "<tr>";
            }
            var c = week > 0 ? week : 0;
            if ((i - 1) >= week && (i - c) <= days) {
                var price = commonUtil.getPrice((i - c));
                var priceStr = "";
                var classStyle = "";
                if (price != -1) {
                    priceStr = "<em style='font-weight:100;'>¥</em>" + price;
                    classStyle = "class='on today'";
                }
				if (price != -1&&obj.year==new Date().getFullYear()&&obj.month==new Date().getMonth()+1&&i-c==new Date().getDate()) {
                    classStyle = "class='on today'";
                }
				//判断今天
                var date = formatDate(obj.year,obj.month,i-c);
				if(obj.year==new Date().getFullYear()&&obj.month==new Date().getMonth()+1&&i-c==new Date().getDate()){
					html += '<td  ' + classStyle + ' date="' + date + '" price="' + price + '"><a><span class="date basefix" style="text-align: center">' + (i - c) + '</span><span class="team basefix" style="display: none;">&nbsp;</span><span class="calendar_price01" style="text-align: center">' + priceStr + '</span></a></td>';
				}
				else{
                	html += '<td  ' + classStyle + ' date="' + date + '" price="' + price + '"><a><span class="date basefix" style="text-align: center">' + (i - c) + '</span><span class="team basefix" style="display: none;">&nbsp;</span><span class="calendar_price01" style="text-align: center">' + priceStr + '</span></a></td>';
				}
                if (index == 6) {

                    html += '</tr>';
                    index = -1;
                }
            }
            else {
                html += "<td></td>";
                if (index == 6) {
                    html += "</tr>";
                    index = -1;
                }
            }
            index++;
        }
        html += "</tbody></table>";
        htmlObj.right = html;
    }
}
var dateUtil = {
    //根据日期得到星期
    getWeek: function () {
        var d = new Date(obj.year, obj.month - 1, 1);
        return d.getDay();
    },
    //得到一个月的天数
    getLastDay: function () {
        var new_year = obj.year;//取当前的年份        
        var new_month = obj.month;//取下一个月的第一天，方便计算（最后一不固定）        
        var new_date = new Date(new_year, new_month, 1);                //取当年当月中的第一天        
        return (new Date(new_date.getTime() - 1000 * 60 * 60 * 24)).getDate();//获取当月最后一天日期        
    },
    getCurrent: function () {
        var dt = obj.date;
        obj.year = dt.getFullYear();
        obj.month = dt.getMonth() + 1;
		obj.day = dt.getDate();
    },
    getLastDate: function () {
        if (obj.year == -1) {
            var dt = new Date(obj.date);
            obj.year = dt.getFullYear();
            obj.month = dt.getMonth() + 1;
        }
        else {
            var newMonth = obj.month - 1;
            if (newMonth <= 0) {
                obj.year -= 1;
                obj.month = 12;
            }
            else {
                obj.month -= 1;
            }
        }
    },
    getNexDate: function () {
        if (obj.year == -1) {
            var dt = new Date(obj.date);
            obj.year = dt.getFullYear();
            obj.month = dt.getMonth() + 1;
        }
        else {
            var newMonth = obj.month + 1;
            if (newMonth > 12) {
                obj.year += 1;
                obj.month = 1;
            }
            else {
                obj.month += 1;
            }
        }
    }
}
var commonUtil = {
    getPrice: function (day) {
        var dt = formatDate(obj.year,obj.month,day);
        for(var i = 0; i< dateStorage.length; i++){
            if(dateStorage[i].Date == dt){
                return dateStorage[i].Price;
            }
        }
        return -1;
    },
    chooseClick: function (sender) {
        var date = sender.getAttribute("date");
        var price = sender.getAttribute("price");
        var el = document.getElementById(elemId);
        if (el != null) {
            el.value = date;
            layui.use('layer',function(){
                var layer = layui.layer;
                var text = $(sender).find(".calendar_price01").text();
                var oldPrice = text.substr(1,text.length-1);
                layer.open({
                    type: 1
                    ,title:"更改价格"
                    ,btn: ['确定', '取消']
                    ,content: $('#setPrice') //这里content是一个DOM，注意：最好该元素要存放在body最外层，否则可能被其它的相对元素所影响
                    ,success: function(layero){ //调整按钮位置
                        layero.find('.layui-layer-btn').css('text-align', 'center');
                        $('#xgPrice').val(oldPrice);
                    }
                    ,yes:function (index,layero) {
                        var xgPrice = $('#xgPrice').val();
                        if(xgPrice == oldPrice) {
                            return layer.msg('输入的价格不能与原价格相等');
                        } else {
                            sender.setAttribute("price", xgPrice);
                            $(sender).find(".calendar_price01").html('<em style="font-weight:100;">¥</em>'+xgPrice);
                            setData();
                            layer.close(index);
                        }
                    }
                })
            })
        }
    }
}

//获取日期
function dateList(){
    var date_span = $('.hotel_category .dateItem');
    var dateArr = new Array();
    var price = $('.shop_price').val();
    
    if (dateArr.length == 0) {
        date_span.each(function(){
            dateArr.push($(this).text());
        })
    }
    data = [];
    for(var i=0; i<dateArr.length; i++){
        var json = {'Date':dateArr[i],'Price':price};
        data.push(json);
    }
    // console.log(data)
}
var oldVal,newVal;
var oldDate,newDate;
var datas;
function AjaxTime(){
    dateList();
    if (changeData == undefined) {
        datas = data;
        oldVal = $('.shop_price').val();
        oldDate = $('.date_choose').text();
    } else {
        newVal = $('.shop_price').val();
        if (newVal != oldVal) {
            oldVal = newVal;
            datas = data;
        } else {
            newDate = $('.date_choose').text();
            if (oldDate != newDate) {
                oldDate = newDate;
                datas = data;
            }else {
                // console.log(changeData)
                datas = changeData;
            }
        }
    }
    dateStorage = [];
    for(var i = 0; i<datas.length; i++){
        var c = datas[i];
        dateStorage.push({"Date":c.Date,"Price":c.Price});
    }

    setTimeout(function () {
        pickerEvent.setPriceArr(datas);
        pickerEvent.Init("calendar");
    },100)
}

function formatDate(y,m,d) {
    var date = y + "-";
    if (m < 10) {
        date += "0" + m;
    } else {
        date += m;
    }
    if (d < 10) {
        date += "-0" + d;
    } else {
        date += "-" + d;
    }

    return date;
}

var isFlag = false;
$(document).on('click','.confirm',function () {
    var str = '';
    $('#calendar_choose').hide()
    data = dateStorage;
    var result = [];
    for(var i = 0;i<dateStorage.length;i++){
        result.push({"Date":dateStorage[i].Date,"Price":dateStorage[i].Price})
    }
    changeData = result;
    isFlag = true;
    $.each(result,function(index,element){
        // console.log(element)
        str += '<div class="item">\
                    <div class="item_time">'+ element.Date +'</div>\
                    <div class="item_price">'+ element.Price +'</div>\
                </div>'
    })
    $('.date_time_price').html(str);
    
})

$(document).on('click','.cancel',function () {
    $('#calendar_choose').hide()
    var res;
    var str = '';
    if (!isFlag) {
        res = data;
    } else {
        if (changeData == '' || changeData == undefined) {
            res = data;
        } else {
            res = changeData;
        }
        
    }
    // $.each(res,function(index,element){
    //     // console.log(element)
    //     str += '<div class="item">\
    //                 <div class="item_time">'+ element.Date +'</div>\
    //                 <div class="item_price">'+ element.Price +'</div>\
    //             </div>'
    // })
    // $('.date_time_price').html(str);
});



