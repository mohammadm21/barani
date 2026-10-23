$(document).ready(function() {
    $("input[type=submit]").click(function(e) {
        var username = $("#username").val();
        var password = $("#password").val();
        if (password == '' || password == '')
        {
            e.preventDefault();
            alert("لطفا تمامی گزینه ها را پر کنید");
        }
    });
});