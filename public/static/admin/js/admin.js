try {
    /** pjax相关 */
    //超时3秒(可选)
    $.pjax.defaults.timeout = 3000;
    $.pjax.defaults.type = 'GET';
    //存储容器id
    $.pjax.defaults.container = '#pjax-container';
    //目标id
    $.pjax.defaults.fragment = '#pjax-container';
    //最大缓存长度(可选)
    $.pjax.defaults.maxCacheLength = 0;
    $(document).pjax('a:not(a[target="_blank"])', {
        //存储容器id
        container: '#pjax-container',
        //目标id
        fragment: '#pjax-container'
    });
    //ajax请求开始时执行
    $(document).ajaxStart(function () {
        //启动进度条
        NProgress.start();
    }).ajaxStop(function () {
        //ajax请求结束后执行
        //关闭进度条
        NProgress.done();
    });
} catch (e) {
    if (adminDebug) {
        console.log(e.message);
    }
}

$(document).on('pjax:timeout', function (event) {
    event.preventDefault();
});
$(document).on('pjax:send', function (xhr) {
    NProgress.start();
});
$(document).on('pjax:complete', function (xhr) {
    $('[data-toggle="tooltip"]').tooltip();
    NProgress.done();
});
//列表页搜索pjax
$(document).on('submit', '.searchForm', function (event) {
    $.pjax.submit(event, '#pjax-container');
});


//菜单搜索
$(function () {
    $('#sidebar-form').on('submit', function (e) {
        e.preventDefault();
    });

    $('.sidebar-menu li.active').data('lte.pushmenu.active', true);

    $('#search-input').on('keyup', function () {
        let term = $('#search-input').val().trim();

        if (term.length === 0) {
            $('.sidebar-menu li').each(function () {
                $(this).show(0);
                $(this).removeClass('active');
                if ($(this).data('lte.pushmenu.active')) {
                    $(this).addClass('active');
                }
            });
            return;
        }

        $('.sidebar-menu li').each(function () {
            if ($(this).text().toLowerCase().indexOf(term.toLowerCase()) === -1) {
                $(this).hide(0);
                $(this).removeClass('pushmenu-search-found', false);

                if ($(this).is('.treeview')) {
                    $(this).removeClass('active');
                }
            } else {
                $(this).show(0);
                $(this).addClass('pushmenu-search-found');

                if ($(this).is('.treeview')) {
                    $(this).addClass('active');
                }

                var parent = $(this).parents('li').first();
                if (parent.is('.treeview')) {
                    parent.show(0);
                }
            }

            if ($(this).is('.header')) {
                $(this).show();
            }
        });

        $('.sidebar-menu li.pushmenu-search-found.treeview').each(function () {
            $(this).find('.pushmenu-search-found').show(0);
        });
    });
});

//点击菜单高亮
$(function () {
    $('.sidebar-menu li:not(.treeview) > a').on('click', function () {
        let $parent = $(this).parent().addClass('active');
        $parent.siblings('.treeview.active').find('> a').trigger('click');
        $parent.siblings().removeClass('active').find('li').removeClass('active');
    });

    $('[data-toggle="popover"]').popover();
});

// bootstrap提示
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});

/** 表单验证错误展示 */
$.validator.setDefaults({
    errorElement: "span",
    errorClass: "help-block error",
    submitHandler: function (form) {
        formSubmit(form);
        return false;
    }
});

/** 边栏菜单 */
try {
    $('.sidebar-menu').tree();
} catch (e) {
    if (adminDebug) {
        console.log(e.message);
    }
}


/* 清除搜索表单 */
function clearSearchForm() {
    let url_all = window.location.href;
    let arr = url_all.split('?');
    let url = arr[0];
    $.pjax({url: url, container: '#pjax-container'});
}


$(function () {
    let $body = $('body');
    /* 返回按钮 */
    $body.on('click', '.BackButton', function (event) {
        event.preventDefault();
        history.back(1);
    });

    /* 刷新按钮 */
    $body.on('click', '.ReloadButton', function (event) {
        event.preventDefault();
        $.pjax.reload();
    });

});


/*列表中单个选择和取消*/
function checkThis(obj) {
    let id = $(obj).attr('value');
    if ($(obj).is(':checked')) {
        if ($.inArray(id, dataSelectIds) < 0) {
            dataSelectIds.push(id);
        }
    } else {
        if ($.inArray(id, dataSelectIds) > -1) {
            dataSelectIds.splice($.inArray(id, dataSelectIds), 1);
        }
    }

    let all_length = $("input[name='data-checkbox']").length;
    let checked_length = $("input[name='data-checkbox']:checked").length;
    if (all_length === checked_length) {
        $("#dataCheckAll").prop("checked", true);
    } else {
        $("#dataCheckAll").prop("checked", false);
    }
    console.log(dataSelectIds);
}

/*全部选择/取消*/
function checkAll(obj) {
    dataSelectIds = [];
    let all_check = $("input[name='data-checkbox']");
    if ($(obj).is(':checked')) {
        all_check.prop("checked", true);
        $(all_check).each(function () {
            dataSelectIds.push(this.value);
        });
    } else {
        all_check.prop("checked", false);
    }
}


/**
 * 表单提交
 */
function formSubmit(form, successCallback, failCallback, errorCallback, showMsg = true) {
    let loadT = layer.msg('正在提交，请稍候…', {icon: 16, time: 0, shade: [0.3, "#000"], scrollbar: false,});
    let action = $(form).attr('action');
    let method = $(form).attr('method');
    let data = new FormData($(form)[0]);

    if (adminDebug) {
        console.log('%cajax submit start!', ';color:#333333');
        console.log('action:' + action);
        console.log('method:' + method);
        console.log('data:' + data);
    }

    $.ajax({
            url: action,
            dataType: 'json',
            type: method,
            data: data,
            contentType: false,
            processData: false,
            success: function (result) {
                layer.close(loadT);
                if (showMsg) {
                    layer.msg(result.msg, {
                        icon: result.code ? 1 : 2,
                        scrollbar: false,
                    });
                }
                // 调试信息
                if (adminDebug) {
                    console.log('submit success!');
                    result.code === 1 ? console.log('%cresult success', ';color:#00a65a') : console.log('%cresult fail', ';color:#f39c12');
                }
                if(result.code === 1){
                    if (successCallback) {
                        successCallback(result);
                    } else {
                        goUrl(result.url);
                    }
                }else{
                    if (failCallback) {
                        failCallback(result);
                    } else {
                        goUrl(result.url);
                    }
                }
            },
            error: function (xhr, type, errorThrown) {
                //异常处理；
                if (adminDebug) {
                    console.log('%csubmit fail!', ';color:#dd4b39');
                    console.log();
                    console.log("type:" + type + ",readyState:" + xhr.readyState + ",status:" + xhr.status);
                    console.log("url:" + action);
                    console.log("data:" + data);
                    layer.close(loadT);
                }
                if(showMsg){
                    layer.msg('访问错误,代码' + xhr.status, {icon: 2, scrollbar: false,});
                }

                if(errorCallback){
                    errorCallback(xhr)
                }
            }
        }
    );
    return false;
}


/** 跳转到指定url */
function goUrl(url = 1) {
    console.log(url);
    //清除列表页选择的ID
    if (url !== 'url://current' && url !== 1) {
        dataSelectIds = [];
    }
    if (url === 'url://current' || url === 1) {
        console.log('Stay current page.');
    } else if (url === 'url://reload' || url === 2) {
        console.log('Reload current page.');
        $.pjax.reload();
    } else if (url === 'url://back' || url === 3) {
        console.log('Return to the last page.');
        history.back(1);
    } else if (url === 4 || url === 'url://close-refresh') {
        console.log('Close this layer page and refresh parent page.');
        let indexWindow = parent.layer.getFrameIndex(window.name);
        //先刷新父级页面
        parent.goUrl(2);
        //再关闭当前layer弹窗
        parent.layer.close(indexWindow);
    } else if (url === 5 || url === 'url://close-layer') {
        console.log('Close this layer page.');
        let indexWindow = parent.layer.getFrameIndex(window.name);
        parent.layer.close(indexWindow);
    } else {
        console.log('Go to ' + url);
        try {
            $.pjax({
                url: url,
                container: '#pjax-container'
            });
        } catch (e) {
            window.location.href = url;
        }
    }
}

/**
 * ajax访问按钮
 * 例如元素为<a class="AjaxButton" data-confirm="1" data-type="1" data-url="disable" data-id="2" data-go="" ></a>
 * data-confirm为是否弹出提示，1为是，2为否。比如删除某条数据，data-confirm="1"就会弹出来提示
 * data-type为访问方式，1为直接ajax访问，例如删除操作。2是为打开layer窗口展示数据，例如查看操作日志详情
 * data-url为要访问的url
 * data-id为要操作的数据ID，可以填写正常的数据ID，例如data-id="2"，
 * 或者填写checked表示获取当前数据列表选择的ID，也就是取的变量dataSelectIds的值
 * data-go为操作完成后的跳转url，不设置此参数默认根据后台返回的url跳转
 * data-confirm-title为确认提示弹窗的标题 例如data-confirm-title="删除警告"
 * data-confirm-content为确认提示的内容 例如data-confirm-content="您确定要删除此数据吗？"
 * data-title 窗口显示的标题
 *
 */
$(function () {
    $('body').on('click', '.AjaxButton', function (event) {
        event.preventDefault();

        if (adminDebug) {
            console.log('AjaxButton clicked.');
        }
        //是否弹出提示
        let layerConfirm = $(this).data("confirm") || 1;
        //访问方式，1为直接访问，2为layer窗口显示
        let layerType = parseInt($(this).data("type") || 1);
        //访问的url
        let url = $(this).data("url");
        //访问方式，默认post
        let layerMethod = $(this).data("method") || 'post';
        //访问成功后跳转的页面，不设置此参数默认根据后台返回的url跳转
        let go = $(this).data("go") || 'url://reload';

        //当为窗口显示时可定义宽度和高度
        let layerWith = $(this).data("width") || '80%';
        let layerHeight = $(this).data("height") || '60%';

        //窗口的标题
        let layerTitle = $(this).data('title');

        //当前操作数据的ID
        let dataId = $(this).data("id");

        let dataData;
        //如果没有定义ID去查询data-data属性
        if (dataId === undefined) {
            dataData = $(this).data("data") || {};
        } else {
            if (dataId === 'checked') {
                if (dataSelectIds.length === 0) {
                    layer.msg('请选择要操作的数据', {icon: 2, scrollbar: false,});
                    return false;
                }
                dataId = dataSelectIds;
            }
            dataData = {"id": dataId};
        }

        if (typeof (dataData) != 'object') {
            dataData = JSON.parse(dataData);
        }

        /*需要确认操作*/
        if (parseInt(layerConfirm) === 1) {
            //提示窗口的标题
            let confirmTitle = $(this).data("confirmTitle") || '操作确认';
            //提示窗口的内容
            let confirmContent = $(this).data("confirmContent") || '您确定要执行此操作吗?';
            layer.confirm(confirmContent, {title: confirmTitle, closeBtn: 1, icon: 3}, function () {
                //如果为直接访问
                if (layerType === 1) {
                    ajaxRequest(url, layerMethod, dataData, go);
                } else if (layerType === 2) {
                    //如果为打开窗口
                    //先进行权限查询
                    if (checkAuth(url)) {
                        layer.open({
                            type: 1,
                            area: [layerWith, layerHeight],
                            title: layerTitle,
                            closeBtn: 1,
                            shift: 0,
                            content: url + "?request_type=layer_open&" + parseParam(dataData),
                            scrollbar: false,
                        });
                    }
                }
            });
        } else {
            //不需要操作确认
            if (layerType === 1) {
                //直接请求
                ajaxRequest(url, layerMethod, dataData, go);
            } else if (layerType === 2) {
                //弹出窗口
                //检查权限
                if (checkAuth(url)) {
                    //用窗口打开
                    layer.open({
                        type: 2,
                        area: [layerWith, layerHeight],
                        title: layerTitle,
                        closeBtn: 1,
                        shift: 0,
                        content: url + "?request_type=layer_open&" + parseParam(dataData),
                        scrollbar: false,
                    });
                }
            }
        }
    });
});

//ajax请求封装
/**
 *
 * @param url 访问的url
 * @param method  访问方式
 * @param data  data数据
 * @param go 要跳转的url
 */
function ajaxRequest(url, method, data, go) {
    let loadT = layer.msg('正在请求,请稍候…', {icon: 16, time: 0, shade: [0.3, '#000'], scrollbar: false,});
    $.ajax({
            url: url,
            dataType: 'json',
            type: method,
            data: data,
            success: function (result) {
                layer.close(loadT);
                layer.msg(result.msg, {
                    icon: result.code ? 1 : 2,
                    scrollbar: false,
                });

                if (adminDebug) {
                    console.log('request success!');
                    if (result.code === 1) {
                        console.log('%cresult success', ';color:#00a65a');
                    } else {
                        go = 'url://current';
                        console.log('%cresult fail', ';color:#f39c12');
                    }
                }

                goUrl(go);
            },
            error: function (xhr, type, errorThrown) {
                //异常处理；
                if (adminDebug) {
                    console.log('%crequest fail!', ';color:#dd4b39');
                    console.log();
                    console.log("type:" + type + ",readyState:" + xhr.readyState + ",status:" + xhr.status);
                    console.log("url:" + url);
                    console.log("data:");
                    console.log(data);
                    layer.close(loadT);
                }
                layer.msg('访问错误,代码' + xhr.status, {icon: 2, scrollbar: false,});
            }
        }
    );
}

//改变每页数量
function changePerPage(obj) {
    if (adminDebug) {
        console.log('当前每页数量' + Cookies.get(cookiePrefix + 'admin_per_page'));
    }
    Cookies.set(cookiePrefix + 'admin_per_page', obj.value, {expires: 30});
    $.pjax.reload();
}


/**
 * 检查授权
 */
function checkAuth(url) {
    let hasAuth = false;
    let loadT = layer.msg('正在请求,请稍候…', {icon: 16, time: 0, shade: [0.3, '#000'], scrollbar: false,});
    $.post({
        url: url,
        data: {"check_auth": 1},
        dataType: 'json',
        async: false,
        success: function (result) {
            layer.close(loadT);
            if (result.code === 1) {
                hasAuth = true;
            } else {
                layer.msg(result.msg, {
                    icon: 2,
                    scrollbar: false,
                });
            }
        },
        error: function (xhr, type, errorThrown) {
            layer.msg('访问错误,代码' + xhr.status, {icon: 2, scrollbar: false,});
        }
    });
    return hasAuth;
}

/** 处理url参数 **/
function parseParam(param, key) {
    let paramStr = "";
    if (param instanceof String || param instanceof Number || param instanceof Boolean) {
        paramStr += "&" + key + "=" + encodeURIComponent(param);
    } else {
        $.each(param, function (i) {
            let k = key == null ? i : key + (param instanceof Array ? "[" + i + "]" : "." + i);
            paramStr += '&' + parseParam(this, k);
        });
    }
    return paramStr.substr(1);
}

/** 导出excel **/
function exportData(url) {
    let exportUrl = url || 'index.html';
    let openUrl = exportUrl + '?export_data=1&' + $("#searchForm").serialize();
    window.open(openUrl);

}


