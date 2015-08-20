<!--home-->
<div id = "home">
    <div id="featured">
        <ul>
            <li><a href="#staffpicks" class="featured_tab">Staff Picks</a></li>
            <li><a href="#newarticles" class="featured_tab">New</a></li>
        </ul>
        <div id="staffpicks">
            {staffpicks_posts}
        </div>
        <div id="newarticles">
            {newarticles_posts}
        </div>
    </div>
    <script>
        $lasttab = "#staffpicks";
        $('#staffpicks').show();
        $('.featured_tab').click(function() {
            $($lasttab).hide();
            $lasttab = $(this).attr('href');
            $($(this).attr('href')).fadeIn();
        });
    </script>
</div>
<!--end-->