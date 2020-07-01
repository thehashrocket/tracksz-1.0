<footer class="app-footer">
    <ul class="list-inline">
        <li class="list-inline-item">
            <a class="text-muted" href="#">Support</a>
        </li>
        <li class="list-inline-item">
            <a class="text-muted" href="#">Help Center</a>
        </li>
        <li class="list-inline-item">
            <a class="text-muted" href="#">Privacy</a>
        </li>
        <li class="list-inline-item">
            <a class="text-muted" href="#">Terms of Service</a>
        </li>
    </ul>
    <?php
        if(isset($_COOKIE['PHPSESSID']))
        {

        $cook_value = $_COOKIE['PHPSESSID'];
        $expire = date('D, d M Y H:i:s', time() + (86400 * 30)); // one month from now
        header("Set-cookie: PHPSESSID = $cook_value; expires=$expire; path=/; HttpOnly; SameSite=Strict");

       }
    ?>
    <div class="copyright"> Copyright © <?php echo date('Y'); ?>. All right reserved. </div>
</footer><!-- /.app-footer -->
