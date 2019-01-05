$(document).ready(function () {

    $('#copy_to_clipboard').attr("data-clipboard-text", PageData.getInstance().currentPostLink);

    var clipboard = new ClipboardJS('#copy_to_clipboard');

    //Bắt sự kiện người dùng enter khi gõ xong bình luận
    $("#comment-content").keyup(function (e) {
        var code = e.keyCode ? e.keyCode : e.which;
        if (code === 13) {  // Enter keycode
            $('#send-comment')[0].click();
        }
    });

});


class PageData {
    constructor() {
        this.endPointApi = "/confession/api";
        this.limitCommentPerPost = 10;
        this.limitTimePerPost = 3 * 60 * 1000;//Thời gian tối thiểu giữa hai lần post bài liên tiếp (3 míns)
        this.isMoreComment = true;

        this.postId = (document.getElementById("feed") != null && document.getElementById("feed").hasAttribute("post_id"))
            ? document.getElementById("feed").getAttribute("post_id") : -1;

        this.currentPostLink = (this.postId === -1) ? window.location.href : (window.location.origin + '/confession/' + this.postId);

        this.lastReaction = Reaction.getInstance().None;//true or false or null ~ like or dislike or nothing
        this.lastTimeReaction = null;
        this.limitTimePerReaction = 3 * 1000;//Thời gian tối thiểu giữa hai lần reaction comment liên tiếp (3 sec)
        this.lastTimeCreateConfession = null;
    }

    static getInstance() {
        if (this.instance == null) {
            this.instance = new PageData();
        }
        return this.instance;
    }

}

class CommentManager {

    constructor() {
        this.isDoMouseEnter = false;
        this.loadMoreCommentsButtonDom = $("#load-more-comments");
        this.CommentBlock = $("#all-comment");
        this.totalCommentDom = $("#total-comment");
        this.commentContentInputDom = $("#comment-content");

        this.commentContentInputOrigin = "Viết bình luận ...";
    }

    static getInstance() {
        if (this.instance == null) {
            this.instance = new CommentManager();
        }
        return this.instance;
    }

    // isShowComment: quyet dinh xem co hien thi comment khong
    // isDoMouseEnter: co phai thuc hien load more comment bang onmouseenter khong
    static loadMoreComment(isShowComment = true, isDoMouseEnter = false) {
        //Vua moi load onmouseenter roi
        if (CommentManager.getInstance().isDoMouseEnter && isDoMouseEnter) {
            return;
        }

        CommentManager.getInstance().isDoMouseEnter = isDoMouseEnter;

        var unshowComments = CommentManager.getCommentUnShowDom();

        if (unshowComments != null) {
            if (isDoMouseEnter && unshowComments.length >= PageData.getInstance().limitCommentPerPost) {
                console.log("isDoMouseEnter: Has more than " + PageData.getInstance().limitCommentPerPost + " comment not show");
                return;
            } else if (isShowComment) {
                unshowComments.show();
                isShowComment = false;
            }
        }


        //Nếu không còn comment nào nữa thì return
        if (PageData.getInstance().isMoreComment === false) {
            if (!isDoMouseEnter) {//Nếu là click thì hide nút load mỏe đi
                CommentManager.getInstance().loadMoreCommentsButtonDom.hide();
            }
            return;
        }


        let formData = new FormData();
        formData.append("edge", "confession");
        formData.append("action", "load_more_comment");
        formData.append("post_id", PageData.getInstance().postId);
        let lastCommentSync = CommentManager.getLastCommentSync();
        if (lastCommentSync != null) {
            formData.append("last_comment_id", lastCommentSync.getCommentIdwithPostId());
        }

        $.ajax({
            url: PageData.getInstance().endPointApi,
            dataType: 'json',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            type: 'post',
            complete: function (response) {
                if (response.status === 200) {
                    if (response.responseJSON.success) {
                        // alert(response.responseJSON.hasOwnProperty("message") ? response.responseJSON.message : "Load hành công");

                        // Success!
                        if (response.responseJSON.hasOwnProperty("No_More") && response.responseJSON.No_More === true) {
                            PageData.getInstance().isMoreComment = false;
                            if (!isDoMouseEnter && isShowComment) {
                                CommentManager.getInstance().loadMoreCommentsButtonDom.hide();
                            }
                        } else {
                            let comments = new Comments(response.responseJSON.data);

                            if (comments == null || comments.comments == null || comments.comments.length === 0) {
                                console.log("Invalid comment");
                                return;
                            }

                            if (comments.comments.length < PageData.getInstance().limitCommentPerPost) {
                                PageData.getInstance().isMoreComment = false;
                                if (!isDoMouseEnter && isShowComment) {
                                    CommentManager.getInstance().loadMoreCommentsButtonDom.hide();
                                }
                            }

                            CommentManager.addLocalComments(comments, isShowComment, true, false);
                        }
                    } else {
                        alert(response.responseJSON.hasOwnProperty("message") ? response.responseJSON.message : "Load thất bại");
                    }
                } else {
                    alert((response.status === 0) ? "Không thể kết nối tới server" : "Đã có lỗi xảy ra");
                }
            }
        });
    }


    static getInputContentfromDom() {
        let content = CommentManager.getInstance().commentContentInputDom.text().trim();
        if (content === "" || content === CommentManager.getInstance().commentContentInputOrigin) {
            return "";
        }
        return content.replace("\n\n", "\n");
    }

    //Lấy tất cả các comment từ local DOM và trả về đối tượng Dom
    static getAllCommentsDom() {
        let allComments = $("#all-comment .UFIComment");
        return (allComments == null || allComments.length === 0) ? null : allComments;
    }

    //Lấy tất cả các comment từ local DOM và trả về đối tượng Comment
    static getAllComments() {
        var myComments = new Comments([]);
        var allComments = CommentManager.getAllCommentsDom().filter("[comment_id]");
        if (allComments != null) {
            allComments.each(function (index) {
                var myComment = Comment.getEmptyInstance();
                let commentId = $(this).attr("comment_id");
                commentId = commentId.split("_")[1];
                commentId = parseInt(commentId);
                myComment.id = commentId;

                myComment.setDateCreated(($(this)).find(".UFICommentActorName").text().trim());
                myComment.like = parseInt(($(this)).find(".like-reaction-total").text().trim());
                myComment.dislike = parseInt(($(this)).find(".dislike-reaction-total").text().trim());

                myComments.addAndRemoveOld(myComment);
            });
        }
        return myComments;
    }

    static getCommentUnShowDom() {
        let allComments = $("#all-comment .UFIComment:hidden");
        return (allComments == null || allComments.length === 0) ? null : allComments;
    }

    static getLocalCommentCount() {
        let totalComment = CommentManager.getInstance().totalCommentDom.text().trim();
        return $.isNumeric(totalComment) ? parseInt(totalComment) : 0;
    }

    static setLocalCommentCount(totalComment) {
        CommentManager.getInstance().totalCommentDom.text(parseInt(totalComment) + 1);
    }

    static minusLocalCommentCount() {
        CommentManager.setLocalCommentCount(CommentManager.getLocalCommentCount() - 1);
    }

    static plusLocalCommentCount() {
        CommentManager.setLocalCommentCount(CommentManager.getLocalCommentCount() + 1);
    }

    static getCommentDomhasComment(commentId) {
        let dom = $("#all-comment .UFIComment[comment_id=\"" + commentId + "\"]");
        if ((dom == null || dom.length === 0)) {
            dom = $("#all-comment .UFIComment[comment_id='" + commentId + "']");
        }
        return (dom == null || dom.length === 0) ? null : dom;
    }


    //Chú ý dấu " và ' rất khác nhau đó, sai 1 ly đi 1 dặm
    static getLastCommentDomhasComment(commentId) {
        let dom = $("#all-comment .UFIComment[comment_id=\"" + commentId + "\"]");
        if ((dom == null || dom.length === 0)) {
            dom = $("#all-comment .UFIComment[comment_id='" + commentId + "']");
        }
        return (dom == null || dom.length === 0) ? null : dom;
    }

    static getLastCommentDom() {
        let dom = $("#all-comment .UFIComment[comment_id]:last-child");
        return (dom == null || dom.length === 0) ? null : dom.last();
    }

    //Lấy ra comment cuối cùng mà vẫn hiển thị (display: block)
    static getLastCommentShowDom() {
        let dom = $("#all-comment .UFIComment[comment_id]:visible");
        return (dom == null || dom.length === 0) ? null : dom.last();
    }

    static getLastComment() {
        return CommentManager.convertDomtoComment(CommentManager.getLastCommentDom());
    }

    static getLastCommentDomSync() {
        let dom = $("#all-comment .UFIComment[comment_id][sync=true]:last-child");
        return (dom == null || dom.length === 0) ? null : dom;
    }

    static getLastCommentSync() {
        return CommentManager.convertDomtoComment(CommentManager.getLastCommentDomSync());
    }

    static convertDomtoComment(dom) {
        if (dom == null) return null;
        let comment = Comment.getEmptyInstance();
        comment.setFromCommentIdwithPostId(dom.attr("comment_id"));
        return comment;
    }

    // comment instance of Comment class
    static removeComment(comment) {
        CommentManager.removeCommentHasId(comment.getCommentIdwithPostId());
    }

    static removeCommentHasId(commentId) {
        $("#all-comment .UFIComment[comment_id=\"" + commentId + "\"]").remove();
        $("#all-comment .UFIComment[comment_id='" + commentId + "']").remove();
    }

    //thêm các comment mới vào
    //comments instance of Comments class
    //updateTotalCmt: có update lại tổng số comment không
    static addLocalComments(comments, isShowComment, sync, updateTotalCmt = true) {
        if (comments === null) {
            return;
        }

        for (var i = 0; i < comments.comments.length; i++) {
            CommentManager.addLocalCommentandRemoveOld(comments.comments[i], isShowComment, sync, updateTotalCmt);
        }
    }

    //thêm 1 comment mới vào va xoa comment cu di(co cung comment_id) và trả về comment dom vừa thêm
    // comment instance of Comment class
    static addLocalCommentandRemoveOld(comment, isShowComment, sync, updateTotalCmt = true) {
        if (comment === null) {
            return null;
        }

        CommentManager.removeComment(comment);

        return CommentManager.addLocalComment(comment, isShowComment, sync, updateTotalCmt);
    }

    //thêm 1 comment mới vào và trả về comment dom vừa thêm
    // comment instance of Comment class
    static addLocalComment(comment, isShowComment, sync, updateTotalCmt = true) {
        if (comment === null) {
            return null;
        }

        CommentManager.removeComment(comment);

        let wrapper = CommentManager.buildCommentDom(comment, isShowComment, sync);
        if (wrapper == null) {
            console.log("Can't build this comment");
            return;
        }

        //Thêm comment mói (local comment chưa sync) vào vị trí ngay trước những comment bị ẩn và cuối cùng những comment hiển thị
        if (sync === false) {
            let lastCommentShowDom = CommentManager.getLastCommentShowDom();
            if (lastCommentShowDom != null) {
                lastCommentShowDom.after(wrapper);
            } else {
                CommentManager.getInstance().CommentBlock.append(wrapper);
            }
        } else {
            CommentManager.getInstance().CommentBlock.append(wrapper);
        }

        /*console.log("----------------------------------");
        console.log(comment);
        console.log(comment.getCommentIdwithPostId());*/

        if (updateTotalCmt) {
            CommentManager.setTotalCmt(CommentManager.getTotalCmt() + 1);
        }

        return CommentManager.getLastCommentDomhasComment(comment.getCommentIdwithPostId());
    }

    static buildCommentDom(comment, isShowComment, sync) {
        if (comment == null || comment.constructor.name !== "Comment") {
            console.log("Invalid input comment class");
            return;
        }

        if ((typeof comment.content) !== "undefined") {
            comment.content = comment.content.replace(/(?:\r\n|\r|\n)/g, "<br>");
        }

        let wrapper = document.createElement("div");
        wrapper.setAttribute("class", "UFIComment");
        wrapper.setAttribute("sync", sync);
        wrapper.setAttribute("comment_id", comment.getCommentIdwithPostId());

        if (isShowComment === false) {
            wrapper.setAttribute("style", "display: none");
        }

        wrapper.innerHTML = " <div class=\"lfloat\"> <a class=\"UFICommentAuthorWithPresence img _8o _8s UFIImageBlockImage\">" +
            "<img class=\"avatar\" style=\"width:32px;height:32px;\" src=\"data:image/png;base64,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\">" +
            "<div class=\"UFICommentAuthorPresence\"></div></a> </div>" +
            "<div class=\"UFIImageBlockContent _42ef\"> <div class=\"\"> " +
            "<div class=\"UFICommentContentBlock\"> <div class=\"UFICommentContent\">" +
            "<div class=\"_26f8\"> <span class=\" UFICommentActorAndBody\">" +
            "<div class=\"UFICommentActorAndBodySpacing\">" +
            "<span> <a class=\" UFICommentActorName\" timestamp = '" + comment.getDateCreatedUnixTimestamp() + "'>" + comment.getDateCreatedTimeAgo() + "</a> </span> " +
            "<span>:</span>" +
            " " + comment.content + " </div></span> <div class=\"_10lo\"></div></div><span></span>" +
            "<div></div></div><div class=\"fsm fwn fcg UFICommentActions\">" +
            "<a onclick=\"CommentManager.doReactionLike($(this))\" class=\"like-button\">Like</a>" +
            "<span>&nbsp;</span>" +
            "<span class=\"reaction-total like-reaction-total\">" + comment.like + "&nbsp;</span> <span> · </span>" +
            "<a onclick=\"CommentManager.doReactionDislike($(this))\" class=\"dislike-button\">Dislike</a>" +
            "<span>&nbsp;</span>" +
            "<span class=\"reaction-total dislike-reaction-total\">" + comment.dislike + "&nbsp;</span> <span> · </span> <span>" +
            "<a class=\"uiLinkSubtle\">" + comment.getDateCreatedTimeAgo() + "</a> </span> </div></div></div></div>";

        return wrapper;
    }

    static getCommentfromDom() {
        let content = CommentManager.getInputContentfromDom();
        if (content === "") {
            return;
        }
        let comment = Comment.getRandomInstance(PageData.getInstance().postId);
        comment.content = content;
        comment.setDateCreated(moment());

        return comment;
    }

    static resetInputComment() {
        CommentManager.getInstance().commentContentInputDom
            .text(CommentManager.getInstance().commentContentInputOrigin);
    }

    static emptyInputComment() {
        let content = CommentManager.getInstance().commentContentInputDom.text().trim();
        if (content === "" || content === CommentManager.getInstance().commentContentInputOrigin) {
            CommentManager.getInstance().commentContentInputDom.text("");
        }
    }

    //comment instance of Comment class
    static doComment(comment) {
        if (comment == null) return;

        CommentManager.resetInputComment();

        //Add local comment
        var commentDom = CommentManager.addLocalCommentandRemoveOld(comment, true, false);
        if (commentDom == null) {
            console.log("What the hell. commentDom is null");
        }

        var formData = new FormData();
        formData.append("edge", "confession");
        formData.append("action", "comment");
        formData.append("post_id", comment.postId);
        formData.append("content", comment.getCotentEncode());

        //Send comment to server
        $.ajax({
            url: PageData.getInstance().endPointApi,
            dataType: 'json',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            type: 'post',
            complete: function (response) {
                if (response.status === 200) {
                    if (response.responseJSON.success) {
                        // alert(response.responseJSON.hasOwnProperty("message") ? response.responseJSON.message : "Thêm hành công");
                        if (commentDom != null) {
                            commentDom.attr("comment_id", response.responseJSON.comment_id);
                            commentDom.attr("sync", true);
                        }
                    } else {
                        alert(response.responseJSON.hasOwnProperty("message") ? response.responseJSON.message : "Thêm thất bại");
                        if (commentDom != null) {
                            commentDom.remove();
                        }
                    }
                } else {
                    alert((response.status === 0) ? "Không thể kết nối tới server" : "Đã có lỗi xảy ra");
                }
            }
        });
    }

    static getTotalCmt() {
        let totalCmt = CommentManager.getInstance().totalCommentDom.text();
        return ($.isNumeric(totalCmt)) ? parseInt(totalCmt) : 0;
    }

    static setTotalCmt(number) {
        CommentManager.getInstance().totalCommentDom.text(number);
    }


    static doReactionLike(commentId) {
        CommentManager.doReaction(commentId, Reaction.getInstance().Like);
    }

    static doReactionDislike(commentId) {
        CommentManager.doReaction(commentId, Reaction.getInstance().Dislike);
    }

    static doReaction(commentId, reaction) {
        if (reaction == null || reaction.constructor.name !== "String" || !Reaction.isValidAction(reaction)) {
            alert("Invalid reaction: " + reaction);
            return;
        }

        var commentDom;
        if ($.isNumeric(commentId)) {
            commentDom = CommentManager.getCommentDomhasComment(commentId);
        }
        else if (typeof commentId === "object" && commentId.constructor.name === "w") {//JQuery Dom
            commentDom = commentId.parents('.UFIComment');
        } else {
            console.log("Invalid commentId");
            return;
        }

        if (commentDom == null) {
            console.log("Comment Dom not found");
            return;
        }

        commentId = commentDom.attr('comment_id');//Include post id

        let lastReaction = commentDom.attr("reaction");
        if (Reaction.isValidAction(lastReaction)) {
            if (reaction === lastReaction) {
                console.log("Bạn vừa " + reaction + " comment " + commentId + " rồi");
                return;
            } else if (Reaction.isOpposive(reaction, lastReaction)) {
                CommentManager.decreaseReaction(commentDom, ".reaction-total." + Reaction.getOpposive(reaction) + "-reaction-total");
                CommentManager.increaseReaction(commentDom, ".reaction-total." + reaction + "-reaction-total");
            }
        } else {
            CommentManager.increaseReaction(commentDom, ".reaction-total." + reaction + "-reaction-total");
        }

        commentDom.attr("reaction", reaction);

        let formData = new FormData();
        formData.append("edge", "comment");
        formData.append("action", "reaction");
        formData.append("post_id", PageData.getInstance().postId);
        formData.append("comment_id", commentId);
        formData.append("type", reaction);

        $.ajax({
            url: PageData.getInstance().endPointApi,
            dataType: 'json',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            type: 'post',
            complete: function (response) {
                if (response.status === 200) {
                    if (response.responseJSON.success) {

                    } else {
                        alert(response.responseJSON.hasOwnProperty("message") ? response.responseJSON.message : "Reaction thất bại");
                    }
                } else {
                    alert((response.status === 0) ? "Không thể kết nối tới server" : "Đã có lỗi xảy ra");
                }
            }
        });
    }

    static decreaseReaction(commentDom, selector) {
        let find = commentDom.find(selector);
        let text = find.text();
        text = ($.isNumeric(text)) ? parseInt(text) : 0;
        find.text(text - 1);
    }

    static increaseReaction(commentDom, selector) {
        let find = commentDom.find(selector);
        let text = find.text();
        text = ($.isNumeric(text)) ? parseInt(text) : 0;
        find.text(text + 1);
    }
}


class Comments {
    constructor(comments) {
        if (comments == null) {
        }
        else if (comments.constructor.name === "Comments") {
            this.comments = comments.comments;
        } else if (comments.constructor === Array) {
            this.comments = [];
            for (var i = 0; i < comments.length; i++) {
                let comment = Comment.import(comments[i]);
                if (comment != null) {
                    this.comments.push(comment);
                }
            }
        } else {
            this.comments = [];
        }
    }

    getHasComment(comment) {//Lấy ra comment comment
        let i = this.indexOf(comment);
        if (i !== -1) {
            return this.comments[i];
        } else {
            return null;
        }
    }

    getHasCommentId(commentId) {//Lấy ra comment có id là commentId
        let comment = new Comment(commentId, null, null, null, null);
        return this.getHasComment(comment);
    }

    add(comment) {//Thêm comment vào list
        this.comments.push(comment);
    }

    addAndRemoveOld(comment) {//Thêm comment vào list, nếu comment đã có thì xóa cái cũ đi thêm cái mới vào
        let i = this.indexOf(comment);
        if (i !== -1) {
            this.comments[i] = comment;
        } else {
            this.comments.push(comment);
        }
    }

    remove(comment) {//Xóa 1 comment trong list
        let i = this.indexOf(comment);
        if (i !== -1) {
            this.comment.splice(i, 1);
        }
    }

    indexOf(comment) {//Tìm vị trí của 1 comment trong comment
        return this.indexOfCommentId(comment.id);
    }

    indexOfCommentId(commentId) {//Tìm vị trí của 1 comment trong comment theo comment id
        if (this.comments.length === 0 || commentId == null) return -1;
        for (var i = 0; i < this.comments.length; i++) {
            if (this.comments[i].id === commentId) return i;
        }
        return -1;
    }
}

class Comment {
    //dateCreated: moment instance  : format ["DD-MM-YYYY", "DD/MM/YYYY", "YYYY-MM-DD", "YYYY/MM/DD"]
    constructor(postId, id, content, dateCreated, like, dislike) {
        if (!$.isNumeric(id)) {
        }
        else if (parseInt(id) < 0) {
            this.id = "" + Date.now();
        } else {
            this.id = parseInt(id);
        }

        if (!$.isNumeric(postId)) {
            alert("Invalid postId in create new Comment");
        }
        else {
            this.postId = parseInt(postId);
        }

        this.content = (content != null && typeof content !== "undefined") ? content : "";
        this.like = (typeof like !== "undefined" && $.isNumeric(like)) ? parseInt(like) : 0;
        this.dislike = (typeof dislike !== "undefined" && $.isNumeric(dislike)) ? parseInt(dislike) : 0;

        this.setDateCreated(dateCreated);
    }

    getDateCreatedUnixTimestamp() {
        return this.dateCreated.valueOf();
    }

    getDateCreated() {
        return this.dateCreated.format("DD-MM-YYYY");
    }

    setDateCreated(dateCreated) {
        if (dateCreated == null) {
            this.dateCreated = moment();
        }
        else if (dateCreated.constructor.name === "Moment") {
            this.dateCreated = dateCreated;
        }
        else if (dateCreated.constructor.name === "String") {
            this.dateCreated = moment(dateCreated, ["DD-MM-YYYY", "DD/MM/YYYY", "YYYY-MM-DD", "YYYY/MM/DD"]);
            if (this.dateCreated == null || typeof this.dateCreated === "undefined") {
                this.dateCreated = moment();
            }
        } else {
            this.dateCreated = moment();
        }
    }

    getDateCreatedTimeAgo() {
        let dateCreatedTimeAgo = "";
        if (this.getDateCreated() != null) {
            dateCreatedTimeAgo = timeago().format(this.getDateCreatedUnixTimestamp(), 'vi');
            if (dateCreatedTimeAgo == null) dateCreatedTimeAgo = "";
        }
        return dateCreatedTimeAgo;
    }

    getCotentEncode() {
        return encodeURIComponent(this.content);
    }

    getCommentIdwithPostId() {
        return this.postId + "_" + this.id;
    }

    setFromCommentIdwithPostId(post_comment) {
        if (post_comment == null) return;
        let post_comment_array = post_comment.split("_");
        if (typeof post_comment_array === "undefined" || post_comment_array.length !== 2) return;
        this.postId = post_comment_array[0];
        this.id = post_comment_array[1];
    }


    static getRandomInstance(postId) {
        return new Comment(postId, -1, null, null, null, null);
    }

    static getEmptyInstance() {
        return new Comment(-1, null, null, null, null, null);
    }

    static import(importComment) {
        if (importComment == null) return null;

        if (importComment.constructor.name === "Comment") return importComment;

        let comment = Comment.getEmptyInstance();
        if (importComment.hasOwnProperty("confession_id")) {
            comment.postId = importComment.confession_id;
        }

        if (importComment.hasOwnProperty("time_post")) {
            comment.setDateCreated(importComment.time_post);
        }

        comment.content = (importComment.hasOwnProperty("comment")) ? importComment.comment : "";
        comment.id = (importComment.hasOwnProperty("comment_id")) ? parseInt(importComment.comment_id) : ("" + Date.now());
        comment.like = (importComment.hasOwnProperty("like")) ? parseInt(importComment.like) : 0;
        comment.dislike = (importComment.hasOwnProperty("dislike")) ? parseInt(importComment.dislike) : 0;
        return comment;
    }
}

class Post {
    constructor(id, content = "", dateCreated = null, like = 0, dislike = 0, comments = 0) {
        if (parseInt(id) < 0) {
            this.id = confessionId + "_" + Date.now();
        } else {
            this.id = id;
        }
        this.content = content;
        this.dateCreated = dateCreated;
        this.like = like;
        this.dislike = dislike;
        if (comments != null && comments.hasOwnProperty("comments") && comments.comments != null) {
            this.comments = comments;
        } else {
            this.comments = new Comments([]);
        }
    }

    static getRandomInstance() {
        return new Comment(-1, null, null, null, null);
    }

    getCotentEncode() {
        return encodeURIComponent(this.content);
    }
}

class Reaction {
    constructor() {
        this.Like = 'like';
        this.Dislike = 'dislike';
        this.None = 'none';
    }

    static getInstance() {
        if (this.instance == null) {
            this.instance = new Reaction();
        }
        return this.instance;
    }

    static isOpposive(reaction1, reaction2) {
        return (reaction1 === Reaction.getInstance().Like && reaction2 === Reaction.getInstance().Dislike)
            || (reaction1 === Reaction.getInstance().Dislike && reaction2 === Reaction.getInstance().Like);
    }

    static getOpposive(reaction) {
        if (reaction === Reaction.getInstance().Like) return Reaction.getInstance().Dislike;
        else if (reaction === Reaction.getInstance().Dislike) return Reaction.getInstance().Like;
        else return Reaction.getInstance().None;

    }

    static isValidAction(action) {
        return (action === Reaction.getInstance().Like || action === Reaction.getInstance().Dislike)
    }
}


class PostManager {
    constructor() {

    }

    static doReaction(reaction) {
        if (reaction == null || reaction.constructor.name !== "String" || !Reaction.isValidAction(reaction)) {
            alert("Invalid reaction: " + reaction);
            return;
        }

        if (PageData.getInstance().lastReaction === reaction) {
            if (PageData.getInstance().lastTimeReaction !== null
                && (new Date().getMilliseconds() - PageData.getInstance().lastTimeReaction.getMilliseconds() < PageData.getInstance().limitTimePerReaction)) {
                console.log("Bạn vừa " + (reaction) + " rồi");
                return;
            }
        } else if (Reaction.isOpposive(PageData.getInstance().lastReaction, reaction)) {
            PostManager.decreaseReaction(".count-reaction #total-" + Reaction.getOpposive(reaction));
        }

        PageData.getInstance().lastReaction = reaction;
        PageData.getInstance().lastTimeReaction = new Date();
        PostManager.increaseReaction(".count-reaction #total-" + reaction);

        let formData = new FormData();
        formData.append("edge", "confession");
        formData.append("action", "reaction");
        formData.append("post_id", PageData.getInstance().postId);
        formData.append("type", reaction);

        $.ajax({
            url: PageData.getInstance().endPointApi,
            dataType: 'json',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            type: 'post',
            complete: function (response) {
                if (response.status === 200) {
                    if (response.responseJSON.success) {

                    } else {
                        alert(response.responseJSON.hasOwnProperty("message") ? response.responseJSON.message : "Reaction thất bại");
                    }
                } else {
                    alert((response.status === 0) ? "Không thể kết nối tới server" : "Đã có lỗi xảy ra");
                }
            }
        });
    }

    static decreaseReaction(selector) {
        $(selector).text(parseInt($(selector).text()) - 1);
    }

    static increaseReaction(selector) {
        $(selector).text(parseInt($(selector).text()) + 1);
    }

    static create() {
        if (PageData.getInstance().lastTimeCreateConfession !== null) {
            var nextTimeCanCreate = new Date().getTime() - PageData.getInstance().lastTimeCreateConfession.getTime();
            if (nextTimeCanCreate < PageData.getInstance().limitTimePerPost) {
                alert("Bạn vừa viết confession rồi.");
                return;
            }
        }
        let content = document.getElementById('content').value;
        if (content === null || content.trim() === "") {
            alert("Bạn phải nhập nội dung");
            return;
        }

        content = content.trim();

        if (content.length < 10) {
            alert("Nội dung quá ngắn.");
            return;
        }

        let formData = new FormData();
        formData.append("edge", "confession");
        formData.append("action", "create");
        formData.append("content", encodeURIComponent(content));

        $.ajax({
            url: PageData.getInstance().endPointApi,
            dataType: 'json',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            type: 'post',
            complete: function (response) {
                if (response.status === 200) {
                    if (response.responseJSON.success) {
                        alert(response.responseJSON.hasOwnProperty("message") ? response.responseJSON.message : "Thêm thành công. Đang chờ phê duyệt");
                        document.getElementById('content').value = '';
                        PageData.getInstance().lastTimeCreateConfession = new Date();
                    } else {
                        alert(response.responseJSON.hasOwnProperty("message") ? response.responseJSON.message : "Thêm thất bại");
                    }
                } else {
                    alert((response.status === 0) ? "Không thể kết nối tới server" : "Đã có lỗi xảy ra");
                }
            }
        });
    }
}
