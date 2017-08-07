/**
 * 后台js主文件
 */

/**
 * 重置查询条件
 * 利用replace实现，只能是页面参数为后缀？的情况下使用
 * var url = window.location.protocol + "//" + window.location.host + window.location.pathname;
 */
function clear_form(){
    var url_all =  window.location.href;
    var arr = url_all.split('?');
    var url = arr[0];
    location.replace(url);
}

function alert(message) {
    console.log(message);
}