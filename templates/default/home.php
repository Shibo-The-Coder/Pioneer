<!--home-->
<div id = "home">
    <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
    <div id="comingsoon">
        <h1>We're coming with new articles soon!</h1>
        <p>Meanwhile.. You can <span class="fb-like" data-href="https://www.facebook.com/gtpioneer" data-layout="button" data-action="like" data-show-faces="true" data-share="true" style="display: inline;"></span> us on
            <a href="https://www.facebook.com/gtpioneer" target="_blank" title="Click to visit our FB page! (Opens in a new tab)"><img src="https://cdn0.iconfinder.com/data/icons/yooicons_set01_socialbookmarks/512/social_facebook_box_blue.png"></a> 
            for more updates!</p>
        <img src="homepagestock.jpg">
    </div>
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