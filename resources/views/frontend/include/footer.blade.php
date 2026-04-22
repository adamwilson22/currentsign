    <!--<footer class="footer_area footer_p_top f_bg_color">-->
         <footer class="footer_area  f_bg_color">
        <img class="p_absolute leaf" src="{{ asset('assets_web/img/v.svg') }}" alt="">
        <img class="p_absolute f_man wow fadeInLeft" data-wow-delay="0.4s" src="{{ asset('assets_web/img/home_two/f_man.png') }}" alt="">
        <img class="p_absolute f_cloud" src="{{ asset('assets_web/img/home_two/cloud.png') }}" alt="">
        <img class="p_absolute f_email" src="{{ asset('assets_web/img/home_two/email-icon.png') }}" alt="">
        <img class="p_absolute f_email_two" src="{{ asset('assets_web/img/home_two/email-icon_two.png') }}" alt="">
        <img class="p_absolute f_man_two wow fadeInLeft" data-wow-delay="0.2s" src="{{ asset('assets_web/img/home_two/man.png') }}" alt="">
        <div class="footer_top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-sm-6">
                        <div class="f_widget subscribe_widget wow fadeInUp">
                         
                            <h4 class="c_head">Subscribe to our newsletter</h4>
                            <form id="subscribe-form" class="footer_subscribe_form" method="GET" data-url="{{ url('/subscribe-endpoint') }}">
    @csrf
    <input type="email" placeholder="Email" name="email" id="emaill"  class="form-control">
    <button type="submit" class="s_btn">Send</button>
</form>
                            <ul class="list-unstyled f_social_icon">
                                <li><a href="#"><i class="social_facebook"></i></a></li>
                                <li><a href="#"><i class="social_twitter"></i></a></li>
                             
                                <li><a href="#"><i class="social_linkedin"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="f_widget link_widget pl_30 wow fadeInUp" data-wow-delay="0.2s">
                            <h3 class="f_title">Quick Link</h3>
                            <ul class="list-unstyled link_list">
                                <li><a href="{{ url('/') }}">Home</a></li>
                                <li><a href="#">About Us</a></li>
                                <li><a href="{{ url('/pricing') }}">Pricing</a></li>
                                <!--<li><a href="#">Blog</a></li>-->
                               
                                <li><a href="#">Contact Us</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-6">
                        <div class="f_widget link_widget wow fadeInUp" data-wow-delay="0.4s">
                            <h3 class="f_title">Importnant </h3>
                            <ul class="list-unstyled link_list">
                                <li><a href="#">Documents</a></li>
                                <li><a href="#">Uploads</a></li>
                                <li><a href="#">Notes</a></li>
                               
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="f_widget link_widget pl_70 wow fadeInUp" data-wow-delay="0.6s">
                            <h3 class="f_title">Legal Links</h3>
                            <ul class="list-unstyled link_list">
                                <li><a href="{{ url('privacy-policy') }}">Privacy Policy</a></li>
                                <li><a href="{{ url('termsofuse') }}">Terms & Conditions</a></li>
                                <li><a href="{{ url('legal-disclaimer') }}">legal disclaimer</a></li>
                               
                              
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="border_bottom"></div>
            </div>
        </div>
        <div class="footer_bottom text-center">
            <div class="container">
                <p>© 2025 All Rights Reserved by <a href="index.html">Current Sign</a></p>
            </div>
        </div>
    </footer>
</div>

<!-- Back to top button -->
<a id="back-to-top" title="Back to Top"></a>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
 <script src="{{ asset('assets_web/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('assets_web/js/pre-loader.js') }}"> </script>
    <script src="{{ asset('assets_web/js/pre-loader.js') }}"> </script>
    <script src="{{ asset('assets_web/assets/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets_web/assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets_web/assets/slick/slick.min.js') }}"></script>
    <script src="{{ asset('assets_web/js/parallaxie.js') }}"></script>
    <script src="{{ asset('assets_web/js/TweenMax.min.js') }}"></script>
    <script src="{{ asset('assets_web/js/jquery.wavify.js') }}"></script>
    <script src="{{ asset('assets_web/assets/wow/wow.min.js') }}"></script>
    <script src="{{ asset('assets_web/assets/mcustomscrollbar/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <script src="{{ asset('assets_web/js/main.js') }}"></script>
    <script>
$(document).ready(function(){
    $('#subscribe-form').on('submit', function(e){
      
        e.preventDefault();

        var email = $('#emaill').val();
        
        var csrfToken = $('meta[name="csrf-token"]').attr('content'); 
        var url = $('#subscribe-form').data('url');

        if(email !== ''){
              
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: csrfToken,
                    email: email
                },
                success: function(response){
                    $('input[name="email"]').val('');
                    alert(response.message);
                },
                error: function(xhr, status, error){
                    alert("Something went wrong, please try again.");
                }
            });
        }
    });
});
</script>