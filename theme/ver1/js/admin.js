<?php if(session('admin') == '1' or session('admin') == '2'){ ?>
function hide(user_id){
    if(user_id == user['id']){
        $.ajax({
            type: "POST",
            url: "<?php echo(url('ajax/hide')); ?>",
        });
        CloseUserWindow();
    }else{
        alert('شما فقط میتوانید فقط خودتان مخفی شوید');
    }
}
function show(user_id){
    if(user_id == user['id']){
        $.ajax({
            type: "POST",
            url: "<?php echo(url('ajax/show')); ?>",
        });
        CloseUserWindow();
    }else{
        alert('شما فقط میتوانید فقط خودتان را آشکار کنید');
    }
}
function benn(user_id){
    if(user_id != user['id']){
        var date= window.prompt('تا تاریخ(شمسی):');
        if(date == null || date == ''){return false;}
        $.ajax({
            type: "POST",
            url: "<?php echo(url('ajax/benn')); ?>",
            data: {  user: user_id, date:date},
            success: function(data){
                switch(data){
                    case('ok'):alert('با موفقیت به صفحه اخراجی ها منتقل شد');break;
                    case('admin'):alert('این شخص از مدیران و ناظران چت روم است');break;
                    default:alert(date);break;
                }
            }
        });
        CloseUserWindow();
    }else{
        alert('شما نمیتوانید خودتان را اخراج کنید');
    }
}
function refresh(user_id){
    if(user_id != user['id']){
        $.ajax({
            type: "POST",
            url: "<?php echo(url('ajax/refresh')); ?>",
            data: { user: user_id},
            success: function(data){
                switch(data){
                    case('ok'):alert('با موفقیت صفحه کاربر رفرش شد.');break;
                    default:alert(data);break;
                }
            }
        });
        CloseUserWindow();
    }
}
function exit(user_id){
    if(user_id != user['id']){
        $.ajax({
            type: "POST",
            url: "<?php echo(url('ajax/exit')); ?>",
            data: { user: user_id},
            success: function(data){
                switch(data){
                    case('ok'):alert('با موفقیت کاربر به بیرون هدایت شد.');break;
                    default:alert(data);break;
                }
            }
        });
        CloseUserWindow();
    }
}
function warning(user_id){
    var txt = window.prompt('متن اخطار:');
    if(txt == null || txt == ''){return false;}
    $.ajax({
        type: "POST",
        url: "<?php echo(url('ajax/warning')); ?>",
        data: {  user: user_id, txt:txt},
        success: function(data){
            switch(data){
                case('ok'):alert('با موفقیت اخطار به او نشان داده شد.');break;
                default:alert(data);break;
            }
        }
    });
    CloseUserWindow();
}
<?php if(session('admin') == '1'){ ?>

function mod(user_id){
    if(user_id != user['id']){
        $.ajax({
            type: "POST",
            url: "<?php echo(url('ajax/mod')); ?>",
            data: { action: 'add', user: user_id},
            success: function(data){
                switch(data){
                    case('ok'):alert('با موفقیت تبدیل به ناظر شد');break;
                    case('lastmod'):alert('این شخص در حال حاضر ناظر است');break;
                    case('lastadmin'):alert('این شخص در حال حاضر مدیر چت روم است');break;
                    case('notrigis'):alert('این شخص عضو سیستم نیست');break;
                    default:alert(data);break;
                }
            }
        });
        CloseUserWindow();
    }else{
        alert('شما خودتان مدیر هستید');
    }
}
function admin(user_id){
    if(user_id != user['id']){
        $.ajax({
            type: "POST",
            url: "<?php echo(url('ajax/admin')); ?>",
            data: { action: 'add', user: user_id},
            success: function(data){
                switch(data){
                    case('ok'):alert('با موفقیت تبدیل به مدیران پیوست.');break;
                    case('lastadmin'):alert('این شخص در حال حاضر مدیر چت روم است');break;
                    case('notrigis'):alert('این شخص عضو سیستم نیست');break;
                    default:alert(data);break;
                }
            }
        });
        CloseUserWindow();
    }else{
        alert('شما خودتان مدیر هستید');
    }
}
<?php }} ?>