/**
 * yupoxiong
 * 2017.05.15
 */


var confirm_open = 0;
/**
 * 软删除一条数据
 * @param obj
 * @param id
 * @param url
 * @param fn
 */
function del(obj, id, url, fn) {
    soft_delete(obj, id, url, '确定要删除本条数据吗？', "已删除！", fn);
}

/**
 * 软删除后台调用
 * @param obj
 * @param id
 * @param url
 * @param tips
 * @param resultTips
 * @param fn
 */
function soft_delete(obj, id, url, tips, resultTips, fn) {
    layer.confirm(tips, {
        btn: ['确定', '取消'],
        title: '提示',
        icon: 3
    }, function () {
        if(confirm_open==0){
            confirm_open=1;
            $.post(url, {id: id}, function (data) {
                console.log(obj);
                console.log(data);
                if(data.status == 200) {
                    $(obj).parents("tr").fadeOut();
                    layer.msg(resultTips, {icon: 1});
                }else if(data.status == 401){
                    layer.msg('未登录或登录信息已失效',{icon: 2});
                }else if(data.status == 403){
                    layer.msg('无权限',{icon: 2});
                }else{
                    layer.msg(data.message,{icon: 2});
                }
                fn && fn(data);
                confirm_open=0;
            }, 'json').error(function() { layer.msg('请求错误',{icon: 2});});

        }
    }, function (index) {
        layer.close(index);
    });
}

function add_open() {
    layer_open('添加','add.html');
}


/**
 * 弹出层
 * @param title 层标题
 * @param url 层链接(opt.type=2|默认)或者HTML内容(opt.type=1)
 * @param opt 选项 {w:WIDTH('800px|80%'),h:HEIGHT('600px|80%'),type:1|2,fn:CALLBACK(回调函数),confirm:BOOL(关闭弹层警告)}
 */
function layer_open(title, url, opt) {
    if (typeof opt === "undefined") opt = {nav: true};
    var w = opt.w || "80vw";
    var h = opt.h || "80vh";
    // 不支持vh,vw单位时采取js动态获取
    if (!attr_support('height', '10vh')) {
        w = w.replace(/([\d\.]+)(vh|vw)/, function (source, num, unit) {
            return $(window).width() * num / 100 + 'px';
        });
        h = h.replace(/([\d\.]+)(vh|vw)/, function (source, num, unit) {
            return $(window).height() * num / 100 + 'px';
        });
    }
    return layer.open({
        type: opt.type || 2,
        area: [w, h],
        fix: false, // 不固定
        maxmin: true,
        shade: 0.4,
        title: title,
        content: url,
        success: function (layero, index) {
            if (typeof opt.confirm !== "undefined" && opt.confirm === true) {
                layero.find(".layui-layer-close").off("click").on("click", function () {
                    layer.alert('您确定要关闭当前窗口吗？', {
                        btn: ['确定', '取消'] //按钮
                    }, function (i) {
                        layer.close(i);
                        layer.close(index);
                    });
                });
            }
            // 自动添加面包屑导航
            if (true === opt.nav) {
                layer.getChildFrame('#nav-title', index).html($('#nav-title').html() + ' <span class="c-gray en">&gt;</span> ' + $('.layui-layer-title').html());
            }
            if (typeof opt.fn === "function") {
                opt.fn(layero, index);
            }
        }
    });
}

/**
 * 检查浏览器是否支持某属性
 * @param attrName
 * @param attrValue
 * @returns {boolean}
 */
function attr_support(attrName, attrValue) {
    try {
        var element = document.createElement('div');
        if (attrName in element.style) {
            element.style[attrName] = attrValue;
            return element.style[attrName] === attrValue;
        } else {
            return false;
        }
    } catch (e) {
        return false;
    }
}
