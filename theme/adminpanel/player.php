<?php
include('header.php');
include('sidebar.php');
?>
<script type="text/javascript">
    function deletemusic(id) {
        $.ajax({
            type: "POST",
            url: '<?php echo(url('ajax/admin.player.delete')); ?>',
            data: {id: id},
            success: function (data) {
                switch (data) {
                    case('ok'):
                        $.msgGrowl({type: 'success', title: 'موفقیت آمیز', text: 'با موفقیت موزیک حذف شد'});
                        $('#music-' + id).slideUp(1000);
                        break;
                    default:
                        alert('پیام سرور:' + data);
                        break;
                }
            }
        });
    }
</script>
<section id="content" class="container_12 clearfix" data-sort=true>
    <h1 class="grid_12 margin-top no-margin-top-phone">موزیک پلیر</h1>
    <div class="grid_12">
        <p style="text-align: center;"><a href="<?php echo(url('admin/player/add')); ?>"><button class="button grey block">موزیک جدید</button></a></p>

        <div class="box">
            <div class="header">
                <h2>
                    <img class="icon" src="<?php echo(theme::url('img/icons/packs/fugue/16x16/blue-document-music.png')); ?>">
                    موزیک پلیر
                </h2>

            </div>
            <div class="content">
                <div class="tabletools">
                </div>
                <table class="styled" id="daynmic_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th style="width: 25%;">عنوان</th>
                            <th>پخش</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = db::query("SELECT * FROM {prefix}player");
                        while ($row = mysqli_fetch_array($query)) {
                            ?>
                            <tr id="music-<?php echo($row['id']); ?>">
                                <td><?php echo($row['id']); ?></td>
                                <td><p><?php echo($row['title']); ?></p><a href="javascript:deletemusic('<?php echo($row['id']); ?>');">[حذف]</a></td>					
                                <td><object type="application/x-shockwave-flash" data="<?php echo(url('theme/music/player_mp3_multi.swf')); ?>" width="200" height="50"><param name="movie" value="<?php echo(url('theme/music/player_mp3_multi.swf')); ?>" /><param name="FlashVars" value="mp3=<?php echo(url($row['src'])); ?>&amp;title=<?php echo($row['title']); ?>&amp;showplaylistnumbers=0&amp;showinfo=1" /></object></td>
                            </tr>
                            <?php
                        }
                        mysqli_free_result($query);
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>