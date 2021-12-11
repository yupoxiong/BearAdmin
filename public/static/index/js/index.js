var Toast = Swal.mixin({
    toast: true,
    position: 'top',
    showConfirmButton: false,
    timer: 2345
});


/** 表单验证 */
$.validator.setDefaults({
    errorElement: "span",
    errorClass: "help-block error",

    errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.input-group').append(error);
        element.closest('.formInputDiv').append(error);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
    },
    submitHandler: function (form) {
        indexSubmitForm(form);
        return false;
    }
});

function showIndexError(msg) {
    Toast.fire({
        icon: 'error',
        title: msg
    })
}

function showIndexSuccess(msg) {
    Toast.fire({
        icon: 'success',
        title: msg
    })
}

function showIndexLoading() {
    Swal.fire({
        title: '正在提交，请稍候…',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        heightAuto: false,
        willOpen: () => {
            Swal.showLoading()
        },
    });
}
function closeIndexLoading(){
    Swal.close();
}

/**
 * 表单提交
 * @param form 表单dom
 * @param successCallback 成功回调
 * @param failCallback 失败回调
 * @param errorCallback 错误回调
 * @param showMsg 是否显示弹出信息
 * @returns {boolean}
 */
function indexSubmitForm(form, successCallback, failCallback, errorCallback, showMsg = true) {

    showIndexLoading();
    let action = $(form).attr('action');
    let method = $(form).attr('method');
    let data = new FormData($(form)[0]);
    $.ajax({
            url: action,
            dataType: 'json',
            type: method,
            data: data,
            contentType: false,
            processData: false,
            complete: function () {
                console.log('表单ajax执行完毕');
            },
            success: function (result) {
                closeIndexLoading();

                if (result.code === 200) {
                    showIndexSuccess(result.msg);
                    if (successCallback) {
                        // 如果有成功回调函数就走回调函数
                        successCallback(result);
                    } else {
                        // 没有回调函数跳转url
                        indexGoUrl(result.url);
                    }
                } else {
                    showIndexError(result.msg);
                    if (failCallback) {
                        // 如果有失败回调函数就走回调函数
                        failCallback(result);
                    } else {
                        // 没有回调函数跳转url
                        indexGoUrl(result.url);
                    }
                }
            },

            error: function (xhr, type, errorThrown) {
               closeIndexLoading();
                // 调试信息
                if (errorCallback) {
                    errorCallback(xhr)
                }

            },
        }
    );
    return false;
}


/** 跳转到指定url */
function indexGoUrl(url) {
    console.log(url);
    //清除列表页选择的ID
    if (url === 'url://current' ) {
        console.log('Stay current page.');
    } else if (url === 'url://reload' ) {
        console.log('Reload current page.');
        location.reload();
    } else if (url === 'url://back' ) {
        console.log('Return to the last page.');
        history.back(1);
    } else {
        console.log('Go to ' + url);
        window.location.href = url;
    }
}

function indexLogout(){
    indexGoUrl(indexLogoutUrl);
}