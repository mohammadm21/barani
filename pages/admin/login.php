<?php

if (!session('id'))
    header::Go(url('login'));
include(theme::file('login.php'));
?>