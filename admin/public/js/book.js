var AJAX_URL = "/admin/ajax/main.php";

//List and Search book
var start = 0;
const limit = 10;
var moreResult = true;
var type = "all post";
var pending = false;
var timeagoInstance = timeago();

var search_content;
var order_field;
var order_by;
getInputData();
var ajax_url;

function remove(id) {
    if (confirm('Are you sure?')) {
        $.ajax({
            url: API_ENDPOINT_BOOK + '/' + id,
            type: 'DELETE',
            crossDomain: true,
            crossOrigin: true,
            dataType: 'json',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Basic YWRtaW46YWRtaW4='
            },
            complete: function (response) {
                if (response.status === 200) {
                    $('#' + id).remove();
                    console.log(response.responseJSON.message);
                    alert("Xóa thành công");
                } else {
                    if (response.status === 0) {
                        alert("Không thể kết nối tới server");
                    } else if (response.hasOwnProperty("responseJSON")) {
                        alert(response.responseJSON.message);
                    } else {
                        alert("Đã có lỗi xảy ra");
                    }

                }
            }
        });
    }
}

function buildResult(data) {
    if ((typeof data).toLowerCase() !== "object" || data == null || data.length === 0) return;
    if (data.length < limit) moreResult = false;

    if (start === 0) {
        $('#search-result tr[id^="post_"]').remove();
    }

    $('#search-result').show();
    for (var i = 0; i < data.length; i++) {
        var id = data[i].id;
        var approval_status;
        if (data[i].approval === "yes") approval_status = "Phê duyệt bởi " + data[i].approval_by;
        else if (data[i].approval === "no") approval_status = "Không được phê duyệt";
        else if (data[i].approval === "not yet") approval_status = "Chờ phê duyệt";
        else approval_status = "Error";

        var content = data[i].content;
        // if (content.length > 500) content = content.substr(0, 500) + "...";

        var total_comment = data[i].cmt;
        var total_like = data[i].like;
        var total_dislike = data[i].dislike;
        var total_view = data[i].view;
        var date_created = data[i].date_created;

        var row = "<tr id=\"post_" + id + "\">";

        row += "<td>" + id + "</td>";
        row += "<td>" + content + "</td>";
        // row += "<td>" + HTMLEncode(content) + "</td>";
        row += "<td class='approval_status'>" + approval_status + "</td>";
        row += "<td>" + total_comment + "</td>";
        row += "<td>" + total_like + "</td>";
        row += "<td>" + total_dislike + "</td>";
        row += "<td>" + total_view + "</td>";
        row += "<td>" + timeagoInstance.format(date_created, 'vi') + "</td>";
        row += "<td>" + ((data[i].approval === "yes") ? getApprovalElementDisable(id) : getApprovalElement(id)) + "</td>";
        row += "<td>" + ((data[i].approval === "no") ? getUnapprovalElementDisable(id) : getUnapprovalElement(id)) + "</td>";
        row += "</tr>";
        $('#search-result tbody').append(row);
    }
}

function getApprovalElement(id) {
    return "<a class='approval' href=\"javascript:doApproval(" + id + ")\"><i class=\"fa fa-check\"></i></a>";
}

function getApprovalElementDisable(id) {
    return "<a class='approval' style='cursor: no-drop; color: black'><i class=\"fa fa-check\"></i></a>";
}

function getUnapprovalElement(id) {
    return "<a class='unapproval' href=\"javascript:doUnapproval(" + id + ")\"><i class=\"fa fa-remove\"></i></a>";
}

function getUnapprovalElementDisable(id) {
    return "<a class='unapproval' style='cursor: no-drop; color: black'><i class=\"fa fa-remove\"></i></a>";
}

function doSearch(startInput = 0) {
    start = startInput;
    var title = $('#book_title').val().trim();
    var content = $('#book_content').val().trim();
    var categories = $('#sel').selectpicker().val();

    if (title === "" && content === "" && categories == null) {
        alert('Bạn phải nhập thông tin tìm kiếm');
        return;
    }


    var query = "?start=" + startInput + "&limit=" + limit;
    if (title !== "") query += "&title=" + encodeURI(title);
    if (content !== "") query += "&content=" + encodeURI(content);
    if (categories != null) query += "&categories=" + encodeURI(categories.toString());

    $.ajax({
        url: API_ENDPOINT_BOOK_SEARCH + query,
        type: 'GET',
        crossDomain: true,
        crossOrigin: true,
        dataType: 'json',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Basic YWRtaW46YWRtaW4='
        },
        complete: function (response) {
            if (startInput === 0) $('#search-result tbody tr').remove();
            if (response.status === 200) {
                moreResult = true;

                console.log(response.responseJSON.result);
                buildResult(response.responseJSON.result);
                start = startInput + limit;

                if (startInput === 0) {//scroll to first result element
                    $.scrollTo($('#form_search_book > input[type="submit"]'), 800);
                }

            } else if (response.status === 0) {
                alert("Không thể kết nối tới server");
            } else {
                moreResult = false;
                if (startInput === 0) {
                    $('#search-result').hide();
                    if (response.hasOwnProperty("responseJSON")) {
                        alert(response.responseJSON.message);
                    } else {
                        alert("Đã có lỗi xảy ra");
                    }
                }
            }
        }
    });
}

function getInputData() {
    search_content = $("#search_content").val();
    if (search_content != null) search_content = search_content.trim();
    if (search_content === "") search_content = null;
    order_field = $('#order_field').selectpicker('val');
    order_by = $('#order_by').selectpicker('val');
}

function getPosts(startInput = 0) {
    if (pending) return;
    if (type == null) return;
    start = startInput;
    if (startInput === 0) {
        getInputData();
    }

    let new_ajax_url = AJAX_URL + "?type=" + type;
    if (search_content != null) new_ajax_url += "&content=" + encodeURI(search_content);
    new_ajax_url += "&start=" + startInput + "&limit=" + limit;

    if (order_field != null) new_ajax_url += "&order_field=" + order_field;
    if (order_by != null) new_ajax_url += "&order_by=" + order_by;

    if (ajax_url === new_ajax_url) {
        console.log("Chưa có thay đỗi gì");
        return;
    }
    else ajax_url = new_ajax_url;

    pending = true;
    $.ajax({
        url: ajax_url,
        type: 'GET',
        complete: function (response) {
            if (response.status === 200) {
                if (response.responseText === "null") {
                    moreResult = false;
                    if (startInput === 0) {
                        $('#search-result tr[id^="post_"]').remove();
                        alert("Không có dữ liệu");
                    }
                }
                else {
                    moreResult = true;
                    console.log(response.responseJSON);
                    buildResult(response.responseJSON);
                    start = startInput + limit;
                }
            } else if (response.status === 0) {
                alert("Không thể kết nối tới server");
            } else {
                moreResult = false;
                if (startInput === 0) {
                    alert("Đã có lỗi xảy ra");
                }
            }
            pending = false;
        }
    });
}


//Kiểm tra 2 mảng có bằng nhau không (các giá trị bằng nhau)
function arraysEqual(arr1, arr2) {
    if (arr1.length === 0 && arr2.length === 0) return true;
    if (arr1.length !== arr2.length) return false;
    for (var i = arr1.length; i--;) {
        if (arr1[i] !== arr2[i]) return false;
    }
    return true;
}

//Trả về 1 mảng có giá trị xuất hiện ở arr1 mà không xuất hiện ở arr2
function subArrays(arr1, arr2) {
    if (arr1.length === 0 || arr2.length === 0) return arr1;
    var sub_arr = [];
    for (var i = 0; i < arr1.length; i++) {
        if (!arr2.includes(arr1[i])) sub_arr.push(arr1[i]);
    }
    return sub_arr;
}


//Thay đỗi trạng thái icon thành disable (màu xám) và loại bỏ hàm javascript
function disableEmelemt(e) {
    e.css({"cursor": "no-drop", "color": "black"});
    e.removeAttr("href");
}

//Thay đỗi trạng thái icon thành enable (màu xanh) và thêm hàm javascript khi click vào
function enableEmelemt(e, id) {
    e.removeAttr("style");
    if (e.hasClass("approval")) e.attr("href", "javascript:doApproval(" + id + ")");
    else e.attr("href", "javascript:doUnapproval(" + id + ")");
}

function doApproval(post_id) {
    $.ajax({
        url: AJAX_URL + "?post_id=" + post_id,
        type: 'POST',
        data: {
            action: "approval",
        },
        complete: function (response) {
            if (response.status === 200) {
                if (true === response.responseJSON) {
                    //Get current admin
                    var admin = $("#admin-title").text();
                    alert("Phê duyệt thành công");
                    $("#post_" + post_id + " .approval_status").html("Phê duyệt bởi " + admin);
                    disableEmelemt($("#post_" + post_id + " .approval"));
                    enableEmelemt($("#post_" + post_id + " .unapproval"), post_id);
                }
                else alert("Không thể phê duyệt bài này được");
            } else if (response.status === 0) {
                alert("Không thể kết nối tới server");
            } else if (response.hasOwnProperty("responseJSON")) {
                alert(response.responseJSON.message);
            } else {
                alert("Đã có lỗi xảy ra");
            }
        }
    });
}

function doUnapproval(post_id) {
    $.ajax({
        url: AJAX_URL + "?post_id=" + post_id,
        type: 'POST',
        data: {
            action: "unapproval",
        },
        complete: function (response) {
            if (response.status === 200) {
                if (true === response.responseJSON) {
                    alert("Hủy phê duyệt thành công");
                    $("#post_" + post_id + " .approval_status").html("Không được phê duyệt");
                    enableEmelemt($("#post_" + post_id + " .approval"), post_id);
                    disableEmelemt($("#post_" + post_id + " .unapproval"));
                }
                else alert("Không thể hủy phê duyệt bài này được");
            } else if (response.status === 0) {
                alert("Không thể kết nối tới server");
            } else if (response.hasOwnProperty("responseJSON")) {
                alert(response.responseJSON.message);
            } else {
                alert("Đã có lỗi xảy ra");
            }
        }
    });
}

function HTMLEncode(str) {
    var i = str.length,
        aRet = [];

    while (i--) {
        var iC = str[i].charCodeAt();
        if (iC < 65 || iC > 127 || (iC > 90 && iC < 97)) {
            aRet[i] = '&#' + iC + ';';
        } else {
            aRet[i] = str[i];
        }
    }
    return aRet.join('');
}