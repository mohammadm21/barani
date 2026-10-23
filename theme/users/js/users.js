// JavaScript Document
var $url_type = 'rewrite';
$rewrite_prefix = '';

function url(e) {
    if ($url_type == "rewrite") {
        return "../../" + e + $rewrite_prefix
    } else if ($url_type == "i") {
        return "?i=" + e.replace(/\//, "&")
    }
}

function search(){
    var username = $('#username').val();
    if(username == '' || username == ' '){return;}
    $.ajax({
        type: "GET",
        url: url("ajax/users.search"),
        data:{username:username},
        success: function(data) {
            $('#result').html('');
            data = $('user',data);
            for(x=0;x!=data.length;x++){
                addrow(data[x]);
            }
        },
        dataType:'xml'
    });
    return false;
}
function addrow(data){
    $('#result').append($(data).text());
}
function changepage(elem){
    if(window.location.href.match(/page:(\d)/)){
        window.location.href = window.location.href.replace(/page:(\d)/, 'page:'+elem.value);
    }else{
        if(window.location.href.match(/users\//)){
            window.location.href = window.location.href+',page:'+elem.value;
        }else{
            window.location.href = window.location.href+'/page:'+elem.value;
        }
    }
}