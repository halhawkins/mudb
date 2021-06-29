
function formatComment(parentElement,commentObject,url,userObject){
    d = new Date(commentObject.created_at);
    dt = d.getMonth() + "/" + d.getDate() + '/' + d.getFullYear() + " " +d.toLocaleTimeString();
    ellipsisButton = $('<div class="float-right fix"><i id="ellipsis-' + commentObject.id + '" class="comment-icons fas fa-ellipsis-h"></i></div>');
    avatarDiv = $('<div class="float-left"><img class="comment-avatar" width="20" src="' + commentObject.user.avatar + '" alt="user avatar"></div>');
    commentUser = $('<strong class="comment-name">&nbsp;' + commentObject.user.name + '</strong>');
    commentText = $('<div class="comment-wrapper"></div>');
    commentText.append(commentUser)
    commentText.append("&nbsp;" + commentObject.comment_body);
    commentText.append($('<br><i>' + dt + '</i>'));
    if(commentObject.likes > 0)
        likes = String(commentObject.likes)
    else
        likes = "";
    replyIcon = $('<i title="Reply to this comment" onclick="leaveComment(' + commentObject.id + ',\'' + url + '\',\'' + commentObject.user.avatar + '\')" class="comment-icons fas fa-reply"></i>');
    if(commentObject.liked)
        likeIcon =  $('<i title="Like to this comment" onclick="likeComment(\'' + userObject.id + '\',\'' + commentObject.id + '\',\'' + url + '\')" class="comment-icons fas fa-heart activated"></i>&nbsp;<span title="Number of likes" class="likesspan">' + likes + "</span>");
    else
        likeIcon =  $('<i title="Like to this comment" onclick="likeComment(\'' + userObject.id + '\',\'' + commentObject.id + '\',\'' + url + '\')" class="comment-icons fas fa-heart"></i>&nbsp;<span title="Number of likes" class="likesspan">' + likes + "</span>");
    commentText.append(replyIcon);
    commentText.append(likeIcon);
    if(userObject.user_id === commentObject.user.id){
        self = "self";
    }
    else{
        self = "nonself";
    }
    li = $('<li class="comment" id="comment-'+commentObject.id+'"></li>').append(ellipsisButton).append(avatarDiv).append(commentText);
    $(parentElement).append(li);
    ellipsisMenu(self,commentObject.id,url,userObject.avatar);
}

function likeComment(user,commentid,url){
    $.ajax({
        type: "post",
        url: url + "/likecomment",
        data: {
            id: user.id,
            item_id: commentid,
            award_type: 'like',
        },
        success: (response) => {
            if(response.userlikes > 0){
                $("#comment-"+commentid).find(".fa-heart").addClass('activated');
                if(response.likes > 0)
                    $("#comment-"+commentid).find(".likesspan").text(response.likes);
                else
                    $("#comment-"+commentid).find(".likesspan").text("");
            }
            else{
                $("#comment-"+commentid).find(".fa-heart").removeClass('activated');
                if(response.likes > 0)
                    $("#comment-"+commentid).find(".likesspan").text(response.likes);
                else
                    $("#comment-"+commentid).find(".likesspan").text("");
            }
        }
    });
}


function submitReportComment(commentid,reason,url){
    $.ajax({
        type: "post",
        url: url + "/reportcomment",
        data: {
            comment_id: commentid,
            reason_for_report: reason
        },
        success: function (response) {
        }
    });
}

function reportComment(commentid,url){
    $("#comment-id-input").val(commentid);
    $("#reason-for-report-input").val('');
    $("#submit-report-comment").unbind();
    $("#submit-report-comment").click(()=>{
        submitReportComment(commentid,$("#reason-for-report-input").val(),url);
    });
    $("#report-comment-modal").modal('show');
}

function ellipsisMenu(self,commentid,url,avatar){
    if(self === 'self'){
        items = {
            "edit":{
                name: "Edit",
                icon:null, 
                callback: (self,o,e)=>{
                    avatar = $("#comment-" + o.selector.substr(o.selector.search('-')+1)).find('img').attr('src');
                    commentid = o.selector.substr(o.selector.search('-')+1);
                    str = o.context.URL;
                    url = str.substr(0,str.substr(0,str.lastIndexOf('/')).lastIndexOf('/')); 
                    editComment(commentid,url,avatar)
                }
            },
            "delete":{
                name: "Delete",
                icon:null,
                callback: (self,o,e) => {
                    avatar = $("#comment-" + o.selector.substr(o.selector.search('-')+1)).find('img').attr('src');
                    commentid = o.selector.substr(o.selector.search('-')+1);
                    str = o.context.URL;
                    url = str.substr(0,str.substr(0,str.lastIndexOf('/')).lastIndexOf('/')); 

                    deleteComment(commentid,url,avatar)
                }
            }
        }
    }
    else{
        items = {
            "report":{
                name:"Report",
                callback: (self,o,e)=> {
                    avatar = $("#comment-" + o.selector.substr(o.selector.search('-')+1)).find('img').attr('src');
                    commentid = o.selector.substr(o.selector.search('-')+1);
                    str = o.context.URL;
                    url = str.substr(0,str.substr(0,str.lastIndexOf('/')).lastIndexOf('/'));   
                    reportComment(commentid,url);                 
                }
            },
            "reply":{name:"Reply"}
        }
    }
    $.contextMenu({
        selector: '#ellipsis-' + commentid, 
        trigger: 'left',
        appendTo: '#ellipsis-' + commentid,
        className: 'menuclass',
        callback: function(key, options) {
            var m = "clicked: " + key;
            alert(m); 
        },
        items: items
    });
}

function leaveComment(commentID,url,avatar){
    if(commentID === null){
        li = $("#commentdiv").prepend(" \
    <li class='comment'> \
        <div style='float:left;'> \
            <img width='20' class='comment-avatar' alt='user avatar' src='" + avatar + "'> \
        </div> \
        <div style='margin-left:1rem;' class='newcomment' title='tooltip'>&nbsp; \
            <textarea id='newcomment'></textarea><button class='btn btn-sm btn-primary btn-comment' id='submit'>Submit</button><button class='btn-comment btn btn-sm btn-dark' id='cancel'>Cancel</button> \
        </div> \
    </li> \
        ");
    }
    else{
        li = $("#comment-" + commentID).append(" \
<ul> \
    <li class='comment'> \
        <div style='float:left;'> \
            <img width='20' class='comment-avatar' alt='user avatar' src='" + avatar + "'> \
        </div> \
        <div style='margin-left:1rem;' class='newcomment' title='tooltip'>&nbsp; \
            <textarea id='newcomment" + commentID + "'></textarea><button class='btn btn-sm btn-primary btn-comment' id='submit'>Submit</button><button class='btn-comment btn btn-sm btn-dark' id='cancel'>Cancel</button> \
        </div> \
    </li> \
</ul>        ");
    }
    $('#submit').click(()=>{
        if(commentID === null)
            content_body = $("#newcomment").val();
        else
            content_body = $("#newcomment" + commentID).val()
        $.ajax({
            type: "post",
            url: url + "/comment",
            cache: false,
            data: {
                parent_comment: commentID,
                item_id: itemId,
                comment_body: content_body,
            },
            success: (response) => {
                d = new Date(response.comment.comment.created_at);
                dt = d.getMonth() + "/" + d.getDate() + '/' + d.getFullYear() + " " +d.toLocaleTimeString();
                if(commentID === null)
                    commentID = "";
                commentdiv = $("#newcomment" + commentID).parent();
                li = commentdiv.parent();
                $("#newcomment" + commentID).remove();
                $("#submit,#cancel").remove();
                //<div class="float-right fix"><i class="comment-icons fas fa-ellipsis-h"></i></div>
                commentdiv.append('<strong class="comment-name">' + response.comment.user.name + '</strong> ' + response.comment.comment.comment_body + '<br/><i>' + dt + '</i><i title="Reply to this comment" onclick="leaveComment(\'' + response.comment.comment.id + '\',\'' + url + '\',\'' + avatar + '\')" class="comment-icons fas fa-reply"></i>');
                li.attr('id',"comment-"+response.comment.comment.id);
                li.prepend('<div class="float-right fix"><i id="ellipsis-' + response.comment.comment.id + '" class="comment-icons fas fa-ellipsis-h"></i></div>');
                ellipsisMenu('self',response.comment.comment.id,url,avatar);
            }
        });
    });
    $('#cancel').click(()=>{
        if(commentID === null)
            commentID = "";
        $("#newcomment" + commentID).closest("li").remove();
        $("#submit,#cancel").remove();
    });
       
}

function deleteComment(commentid,url,avatar){
    if(confirm("Delete this comment?")){
        $.ajax({
            type: "post",
            url: url + "/deletecomment",
            data: {
                id: commentid
            },
            success:(response) => {
                parent = $('#comment-' + commentid).closest('ul');
                if(parent.children('li').length === 1 && parent.attr('id') !== "commentdiv")
                    parent.remove();
                else
                    $('#comment-' + commentid).remove();
            }
        });
    }
}

function editComment(commentID,url,avatar){
    if(commentID === null){
        return;
    }
    else{
        edit = $(' \
        <div class="newcomment"> \
            <textarea id="newcomment' + commentID + '"></textarea> \
            <button class="btn btn-sm btn-primary btn-comment" id="submit">Submit</button> \
            <button class="btn-comment btn btn-sm btn-dark" id="cancel">Cancel</button> \
        </div>');
        save = $("#comment-" + commentID).find(".comment-wrapper").html();
        $("#comment-" + commentID).find("strong").remove();
        text = $("#comment-" + commentID).find(".comment-wrapper").html();
        pos = text.indexOf("<br>");
        text = text.substr(0,pos).replace("&nbsp;"," ").trim();
        $("#comment-" + commentID).find(".comment-wrapper").remove();
        $("#comment-" + commentID).append(edit);
        $("#newcomment" + commentID).val(text);
    }

    $('#submit').click(()=>{
        if(commentID === null)
            content_body = $("#newcomment").val();
        else
            content_body = $("#newcomment" + commentID).val()
        $.ajax({
            type: "post",
            url: url + "/editcomment",
            cache: false,
            data: {
                id: commentID,
                item_id: itemId,
                comment_body: content_body,
            },
            success: (response) => {
                if(commentID === null)
                    commentID = "";
                commentdiv = $("#newcomment" + commentID).parent();
                li = commentdiv.parent();
                $("#newcomment" + commentID).remove();
                $("#submit,#cancel").remove();
                commentdiv.append('<strong class="comment-name">' + response.user.name + '</strong> ' + response.comment.comment_body + '<br/><i>' + dt + '</i><i title="Reply to this comment" onclick="leaveComment(\'' + response.comment.id + '\',\'' + url + '\',\'' + avatar + '\')" class="comment-icons fas fa-reply"></i>');
                li.attr('id',"comment-"+response.comment.comment.id);
                li.prepend('<div class="float-right fix"><i id="ellipsis-' + response.comment.id + '" class="comment-icons fas fa-ellipsis-h"></i></div>');
                ellipsisMenu('self',response.comment.id,url,avatar);
            }
        });
    });
    $('#cancel').click(()=>{
        if(commentID === null)
            commentID = "";
        cw = $('<div class="content-wrapper">' + save + '</div>');
        $("#newcomment" + commentID).closest(".newcomment").remove();
        $("#comment-" + commentID).append(cw);
        ellipsis = $("#ellipsis-" + commentID).parent();
        $("#ellipsis-" + commentID).unbind('click');
        $("#ellipsis-" + commentID).remove();
        ellipsis.append('<i style="z-index:1000;" id="ellipsis-' + commentID + '" class="comment-icons fas fa-ellipsis-h"></i>');
        $(".ellipsis-" + commentID).removeClass("comment-icons");
        $(".ellipsis-" + commentID).addClass("comment-icons");
        ellipsisMenu('self',commentID,url,avatar);
        // $("#submit,#cancel").remove();
    });
       
}

let count = 0;

function showComments(comments,list,userid,url,avatar){
   if(Array.isArray(comments)){
        count++;
        $.each(comments,function(i1,val1){
            if(Array.isArray(val1)){
                id = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
                $("#"+list).append('<li class="comment"><ul class="comment" id="' + id + '"></ul></li>');
                showComments(val1,id,userid,url);

            }
            else{
                formatComment($("#"+list),val1,url,{user_id:userid,avatar:avatar});
            }
        });
    }
    else{
        count++;
    }
    id = "wert";
    return 
    id;
}

