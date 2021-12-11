$(function () {
    // 手机号
    $.validator.addMethod("mobile", function (value, element, params) {
        if (adminDebug) {
            console.log('验证手机号', value, element, params);
        }
        return params === true ? (/^1[3-9]\d{9}$/.test(value)) : true;

    }, "手机号格式不正确");

    // 身份证号
    $.validator.addMethod("idCard", function (value, element, params) {
        if (adminDebug) {
            console.log('验证身份证号', value, element, params);
        }
        return params === true ? (/^1[3456789]\d{9}$/.test(value)) : true;

    }, "身份证号格式不正确");

    // 邮箱
    $.validator.addMethod("email", function (value, element, params) {
        if (adminDebug) {
            console.log('验证邮箱', value, element, params);
        }
        return params === true ? (/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(value)) : true;

    }, "邮箱格式不正确");

    // 纯数字
    $.validator.addMethod("number", function (value, element, params) {
        if (adminDebug) {
            console.log('验证纯数字', value, element, params);
        }
        return params === true ? (/^\d+$/.test(value)) : true;

    }, "内容必须为纯数字");

    // 汉字
    $.validator.addMethod("chs", function (value, element, params) {
        if (adminDebug) {
            console.log('验证汉字', value, element, params);
        }
        return params === true ? (/^(?:[\u3400-\u4DB5\u4E00-\u9FEA\uFA0E\uFA0F\uFA11\uFA13\uFA14\uFA1F\uFA21\uFA23\uFA24\uFA27-\uFA29]|[\uD840-\uD868\uD86A-\uD86C\uD86F-\uD872\uD874-\uD879][\uDC00-\uDFFF]|\uD869[\uDC00-\uDED6\uDF00-\uDFFF]|\uD86D[\uDC00-\uDF34\uDF40-\uDFFF]|\uD86E[\uDC00-\uDC1D\uDC20-\uDFFF]|\uD873[\uDC00-\uDEA1\uDEB0-\uDFFF]|\uD87A[\uDC00-\uDFE0])+$/.test(value)) : true;

    }, "内容必须为汉字");

    // 验证IPV4
    $.validator.addMethod("ipv4", function (value, element, params) {
        if (adminDebug) {
            console.log('验证IPV4', value, element, params);
        }
        return params === true ? (/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(value)) : true;

    }, "IPV4格式不正确");

    // 验证IPV6
    $.validator.addMethod("ipv6", function (value, element, params) {
        if (adminDebug) {
            console.log('验证IPV6', value, element, params);
        }
        return params === true ? (/^((([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(([0-9A-Fa-f]{1,4}:){0,5}:((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(::([0-9A-Fa-f]{1,4}:){0,5}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})|(::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){1,7}:))$/i.test(value)) : true;

    }, "IPV6格式不正确");

    // 验证IP
    $.validator.addMethod("ip", function (value, element, params) {
        if (adminDebug) {
            console.log('验证IP', value, element, params);
        }
        if (params === true) {
            let ipv4 = (/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(value));
            let ipv6 = (/^((([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(([0-9A-Fa-f]{1,4}:){0,5}:((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(::([0-9A-Fa-f]{1,4}:){0,5}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})|(::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){1,7}:))$/i.test(value));

            if (!ipv4 && !ipv6) {
                return false;
            }
        }
        return true;
    }, "IP格式不正确");

    // 验证16进制颜色
    $.validator.addMethod("color16", function (value, element, params) {
        if (adminDebug) {
            console.log('验证颜色', value, element, params);
        }
        return params === true ? (/^#?([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/.test(value)) : true;

    }, "颜色格式不正确");

    // 验证6位数字密码
    $.validator.addMethod("number6", function (value, element, params) {
        if (adminDebug) {
            console.log('验证6位数字密码', value, element, params);
        }
        return params === true ? (/^\d{6}$/.test(value)) : true;

    }, "必须为6位数字");

    // 验证简单密码
    $.validator.addMethod("simplePassword", function (value, element, params) {
        if (adminDebug) {
            console.log('验证简单密码', value, element, params);
        }
        return params === true ? (/^(?=.*[a-zA-Z])(?=.*\d).{6,16}$/.test(value)) : true;

    }, "至少1个字母和1个数字，6-16位");

    // 验证中等密码
    $.validator.addMethod("middlePassword", function (value, element, params) {
        if (adminDebug) {
            console.log('验证中等密码', value, element, params);
        }
        return params === true ? (/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,16}$/.test(value)) : true;

    }, "至少1个大写字母和1个小写字母和1个数字，8-16位");

    // 验证复杂密码
    $.validator.addMethod("complexPassword", function (value, element, params) {
        if (adminDebug) {
            console.log('验证复杂密码', value, element, params);
        }
        return params === true ? (/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.[$@!%#?&]).{8,16}$/.test(value)) : true;

    }, "至少1个大写字母和1个小写字母和1个数字和1个特殊字符，8-16位");

    // 验证URL
    $.validator.addMethod("url", function (value, element, params) {
        if (adminDebug) {
            console.log('验证URL', value, element, params);
        }
        return params === true ? (/^\w+[^\s]+(\.[^\s]+)+$/.test(value)) : true;

    }, "必须为正确的网址");

});