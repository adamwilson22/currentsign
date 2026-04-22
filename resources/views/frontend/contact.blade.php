@include('frontend.include.header')
<section class="contact_area sec_pad">
            <div class="container">
                <div class="section_title text-left">
                    <h2 class="h_title wow fadeInUp">Need to Contact us?</h2>
                </div>
                <div class="get_info_inner">
                    <div class="row get_info_item align-items-center justify-content-between">
                        <div class="col-lg-4 col-sm-5 mb-3">
                            <div class="media">
                                <i class="fa fa-envelope" style="font-size:40px; margin-right:15px"></i>
                                <div class="media-body">
                                    <h5 class="h5 bold">Email</h5>
                                    <p>support@currentsign.com</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 col-sm-5 mb-3">
                            <div class="media">
                                <i class="fa fa-phone" style="font-size:40px; margin-right:15px"></i>
                                <div class="media-body">
                                    <h5 class="h5 bold">Phone</h5>
                                    <p>1-800-123-CURRENT (1-800-123-287738)</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 col-sm-5 mb-3">
                            <div class="media">
                                <i class="fa fa-comment-o" style="font-size:40px; margin-right:15px"></i>
                                <div class="media-body">
                                    <h5 class="h5 bold">Live Chat</h5>
                                    <p>Accessible on the website during business hours (9 AM–5 PM ET) </p>
                                </div>
                            </div>
                        </div>
                      
                    </div>
                   
                </div>
                <div class="contact_info">
                    <div class="section_title text-left">
                        <h2 class="h_title wow fadeInUp">Let’s start the conversation</h2>
                        <p>Please email us, we’ll happy to assist you.</p>
                        @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

                    </div>
                    <form action="{{ url('contact-submit') }}" method="POST" class="contact_form">
                        @csrf
                        <div class="row contact_fill">
                            <div class="col-lg-4 form-group">
                                <h6>Full Name</h6>
                                <input type="text" class="form-control" name="name" id="name" placeholder="">
                            </div>
                            <div class="col-lg-4 form-group">
                                <h6>Email</h6>
                                <input type="email" class="form-control" name="email" id="email" placeholder="">
                            </div>
                            <div class="col-lg-4 form-group">
                                <h6>Phone no</h6>
                                <input type="tel" class="form-control" name="phone" id="phone" placeholder="">
                            </div>
                            <div class="col-lg-12 form-group">
                                <h6>Message</h6>
                                <textarea class="form-control message" name="message" id="message" placeholder="Enter Your Text ..."></textarea>
                            </div>
                            <div class="col-lg-12 form-group">
                                <button type="submit" class="btn action_btn thm_btn">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        
@include('frontend.include.footer')
</body>


</html>