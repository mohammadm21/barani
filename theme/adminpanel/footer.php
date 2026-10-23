<?php
$admin = session('admin');
if ($admin != '0') {
    if (!isset($_REQUEST['ajaxload'])) {
        ?>
        </div>
        <footer class="container_12">
            <ul class="grid_6">
                <li>
                    <a href="http://www.baran0.ir">طراحی و پشتیبانی حرفه ای چت روم</a>
                </li>

            </ul>
            <span class="grid_6">
                Copyright &copy; <?php echo(date('Y')); ?> <a href="http://www.baran0.ir/">baran0.IR</a>
            </span>
        </footer>
        <script>

            $$.loaded();

        </script>
        <div style="display:none;">
            <h1><a href="http://www.baran0.ir/"></a>طراحي چت</h1><h1><a href="http://www.baran0.ir/"></a>طراحي چت روم</h1><h1><a href="http://www.baran0.ir/"></a>هاست چت روم</h1><h1><a href="http://www.baran0.ir/"></a>خريد چت روم</h1><h1><a href="http://www.baran0.ir/"></a>فروش چت روم</h1><h1><a href="http://www.baran0.ir/"></a>چتروم</h1>
        </div>
        </body>

        </html>
        <?php
    }
} else {
    include("pages/notfound.php");
}
?>
