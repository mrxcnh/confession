function isUnsignedNumber(str) {
    return /^\d+$/.test(str);
}

var isFirstLoadMoreComment = true;
const endPointApi = "/confession/api";
var lastCommentElement = document.querySelector(".all-comment > .UFIComment:last-child");
var lastCommentId = (lastCommentElement !== null && lastCommentElement.hasAttribute("comment_id")) ? lastCommentElement.getAttribute("comment_id") : null;
var confessionId = (document.getElementById("feed") != null && document.getElementById("feed").hasAttribute("confession_id")) ? document.getElementById("feed").getAttribute("confession_id") : -1;
var lastReaction = null;//true or false or null ~ like or dislike or nothing
var lastTimeReaction = null;
var lastTimeCreateConfession = null;
const limitCommentPerPost = document.querySelectorAll(".all-comment > .UFIComment").length;

/*
* selector: node or dom object
* */
function inDecreaseReaction(selector, isIncrease) {
    let node = (typeof selector === "string") ? document.querySelector(selector) : selector;
    let count = node.innerText.trim();
    if (!isUnsignedNumber(count)) {
        count = 0;
    }
    count = parseInt(count);
    if (isIncrease) {
        count++;
    }
    else {
        count--;
    }
    node.innerText = count;
}


function increaseReaction(selector) {
    inDecreaseReaction(selector, true);
}

function decreaseReaction(selector) {
    inDecreaseReaction(selector, false);
}


function sendAjax(edge, action, data, onSuccess) {
    let xhttp = new XMLHttpRequest();
    xhttp.open("POST", endPointApi, true);
    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");

    //Chuyển đỗi dữ liệu data từ object sang string
    let stringData = "";
    for (var key in data) {
        if (data.hasOwnProperty(key)) {
            stringData += "&" + key + "=" + encodeURIComponent(data[key]);
        }
    }
    xhttp.onload = function () {
        if (xhttp.status >= 200 && xhttp.status < 400) {
            // window.alert(xhttp.responseText);//debug
            var jsonData = {};
            try {
                if (xhttp.responseText === "") {
                    jsonData.error = false;
                }
                else {
                    jsonData = JSON.parse(xhttp.responseText);
                }
            } catch (e) {
                console.log("Error: " + e.toString());
            }
            onSuccess(jsonData);

        } else {
            // We reached our target server, but it returned an error
            console.log("Ajax comment error: We reached our target server, but it returned an error");
        }
    };
    xhttp.onerror = function () {
        // There was a connection error of some sort
        console.log("Ajax comment error: There was a connection error of some sort");
    };

    xhttp.send("post_id=" + confessionId + "&edge=" + edge + "&action=" + action + stringData);
}

function opposive(reaction) {
    if (reaction === "like") {
        return "dislike";
    }
    else if (reaction === "dislike") {
        return "like";
    }
    else {
        return null;
    }
}


//Create confession
function createConfession() {
    if (lastTimeCreateConfession !== null) {
        var nextTimeCanCreate = new Date().getTime() - lastTimeCreateConfession.getTime();
        if (nextTimeCanCreate < 3000) {//3 sec
            alert("Bạn vừa viết confession rồi.");
            return;
        }
    }
    let content = document.getElementById('content').value;
    if (content === null || content === "") {
        alert("Bạn phải nhập nội dung");
        return;
    }
    if (content.length < 10) {
        alert("Nội dung quá ngắn.");
        return;
    }

    sendAjax("confession", "create", {"content": content}, function (data) {
        if (data.error === false) {
            // Success!
            document.getElementById('content').value = '';
            lastTimeCreateConfession = new Date();
            alert("Thêm thành công. Đang chờ phê duyệt");
        } else alert("Thêm thất bại");
    });
}

function updateLocalCommentCount() {
    let totalComment = document.getElementById("total-comment").innerText;
    if (!isUnsignedNumber(totalComment)) {
        document.getElementById("total-comment").innerText = document.querySelectorAll(".all-comment > .UFIComment").length;
    }
    else {
        document.getElementById("total-comment").innerText = parseInt(totalComment) + 1;
    }
}

//comment_id: confession_id + "_" + comment_id
function addLocalComment(content, dateCreated, commentId = null, like = 0, dislike = 0) {
    if (commentId === null) {//comment create local
        updateLocalCommentCount();
        commentId = confessionId + "_" + Math.random();
    }//else comment load from server
    content = content.replace(/(?:\r\n|\r|\n)/g, "<br>");
    let wrapper = document.createElement("div");
    wrapper.setAttribute("class", "UFIComment LocalComment");
    wrapper.setAttribute("comment_id", commentId);
    wrapper.innerHTML = " <div class=\"lfloat\"> <a class=\"UFICommentAuthorWithPresence img _8o _8s UFIImageBlockImage\"> <img class=\"avatar\" style=\"width:32px;height:32px;\" src=\"data:image/png;base64,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\"> <div class=\"UFICommentAuthorPresence\"></div></a> </div><div class=\"UFIImageBlockContent _42ef\"> <div class=\"\"> <div class=\"UFICommentContentBlock\"> <div class=\"UFICommentContent\"> <div class=\"_26f8\"> <span class=\" UFICommentActorAndBody\"> <div class=\"UFICommentActorAndBodySpacing\"> <span><a class=\" UFICommentActorName\">" + dateCreated + "</a></span> <span>:</span> " + content + " </div></span> <div class=\"_10lo\"></div></div><span></span> <div></div></div><div class=\"fsm fwn fcg UFICommentActions\"> <a class=\"like-button\" onclick=\"doCommentReaction(this, 'like');\">Like</a> <span class=\"reaction-total like-reaction-total\">" + like + "</span> <span> · </span> <a class=\"dislike-button\" onclick=\"doCommentReaction(this, 'dislike');\">Dislike</a> <span class=\"reaction-total dislike-reaction-total\">" + dislike + "</span> <span> · </span> <span> <a class=\"uiLinkSubtle\">28m</a> </span> </div></div></div></div>";

    let thisElementSelector = ".all-comment > .UFIComment[comment_id='" + commentId + "']";
    //load bỏ những phần tử cũ đi, những phần tử vừa được thêm vào có trùng comment_id với phần tử mới thêm vào
    let oldComments = document.querySelectorAll(thisElementSelector);
    if (oldComments !== null && oldComments.length > 0) {
        oldComments.forEach(function (oldComment) {
            oldComment.remove();
        });
    }

    //thêm comment mới vào
    document.querySelector(".all-comment").appendChild(wrapper);
    let newCommentElement = document.querySelector(thisElementSelector);
    // console.log(newCommentElement);
    return newCommentElement;
}


function loadMoreComment() {
    if (isFirstLoadMoreComment) {
        isFirstLoadMoreComment = false;
        document.querySelectorAll(".UFIComment").forEach(function (element) {
            element.style.display = "";
        });
        return null;
    }
    else {
        sendAjax("confession", "loadMoreComment", {"lastCommentId": lastCommentId}, function (data) {
            if (data.error === false) {
                // Success!
                if (data.message === "no more") {
                    document.getElementById("load-more-comments").style.display = "none";
                    return;
                }
                let comments = data.message.comments;
                if (typeof comments === "undefined") return;
                if (comments.length < limitCommentPerPost) {
                    document.getElementById("load-more-comments").style.display = "none";
                }
                comments.forEach(function (comment) {
                    let tempCommentId = confessionId + "_" + comment.comment_id;
                    addLocalComment(comment.comment, comment.time_post, tempCommentId, comment.like_count, comment.dislike_count);
                    lastCommentId = tempCommentId;
                });
            }
        });
    }
}

function doPostReaction(action) {
    if (lastReaction === action) {
        if (lastTimeReaction !== null && new Date().getMilliseconds() - lastTimeReaction.getMilliseconds() < 300000) {
            console.log("Bạn vừa " + (action) + " rồi");
            return;
        }
    } else if (lastReaction === opposive(action)) {//lastReaction !== action
        decreaseReaction(".count-reaction #total-" + opposive(action));
    }

    lastReaction = action;
    lastTimeReaction = new Date();
    increaseReaction(".count-reaction #total-" + action);
    sendAjax("confession", (action), null, function () {
        console.log((action) + "d");
    });
}


function comment() {
    let content = document.querySelector("#comment-content").innerText.trim();
    if (content === null || content.length < 5) {
        return;
    }
    content = content.replace("\n\n", "\n");
    let now = new Date();
    let currentMonth = now.getMonth() + 1;
    let currentDate = now.getDate();
    if (currentDate < 10) {
        currentDate = "0" + currentDate;
    }
    if (currentMonth < 10) {
        currentMonth = "0" + currentMonth;
    }
    let dateCreated = currentDate + "/" + currentMonth + "/" + now.getFullYear();
    let localComment = addLocalComment(content, dateCreated);
    document.querySelector("#comment-content").innerHTML = " ";

    sendAjax("confession", "comment", {"content": content}, function (data) {
        if (!data.error) {
            localComment.setAttribute("comment_id", data.message.comment_id);
        }
    });

}

if (document.getElementById("comment-content") != null) {
    document.getElementById("comment-content").addEventListener("keyup", function (event) {
        event.preventDefault();
        if (event.keyCode === 13) {
            if (event.shiftKey) {
                comment();
            }
        }
    });
    document.getElementById("comment-content").addEventListener("click", function () {
        var content = document.getElementById("comment-content").innerText;
        if (content !== null && content.startsWith("Viết bình luận")) {
            document.getElementById("comment-content").innerText = " ";
        }
    });

}


function getParentHasClass(e1, className) {
    while (e1) {
        if (typeof e1 === "object") {
            let classes = e1.getAttribute("class");
            if (classes !== null && classes !== "") {
                if (classes.split(" ").includes(className)) {
                    return e1;
                }
            }
        }
        e1 = e1.parentNode;
    }
    return null;
}

function doCommentReaction(e1, reaction) {
    if (reaction !== "like" && reaction !== "dislike") {
        return;
    }
    let parentNode = getParentHasClass(e1, "UFIComment");
    if (parentNode === null || !parentNode.hasAttribute("comment_id")) {
        return;
    }

    let lastReaction = parentNode.getAttribute("reaction");
    if (lastReaction !== null) {
        if (reaction === lastReaction) {
            console.log("Bạn vừa " + reaction + " comment này rồi");
            return;
        } else if (reaction === opposive(lastReaction)) {
            decreaseReaction(parentNode.querySelector(".reaction-total." + opposive(reaction) + "-reaction-total"));
            increaseReaction(parentNode.querySelector(".reaction-total." + reaction + "-reaction-total"));
        }
    } else {
        increaseReaction(parentNode.querySelector(".reaction-total." + reaction + "-reaction-total"));
    }
    parentNode.setAttribute("reaction", reaction);
    let commentId = parentNode.getAttribute("comment_id");
    if (commentId === null) {
        return;
    }

    sendAjax("comment", reaction, {"comment_id": commentId}, function (data) {
        console.log(data);
    });
}






