@include('frontend.include.header')
       
       
        
       
        <section class="doc_documentation_area ptb-100">
            <div class="overlay_bg"></div>
            <div class="container custom_container">
                <div class="row">
                    <div class="col-lg-3 doc_mobile_menu display_none">
                        @include('frontend.include.aside')
                    </div>
                    <div class="col-lg-9 col-md-9">
                        <div class="documentation_info">
                            <div class="d-flex justify-content-between">
                                <h4>Notifications</h4>
                                
                            </div>
                            
                            
                               <hr/>
                           
        <style>
            .notification-card {
      background-color: #fff;
      border-radius: 8px;
      padding: 15px 20px;
      margin-bottom: 15px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
      display: flex;
      justify-content: space-between;
      align-items: center;
      transition: 0.3s ease;
    }

    

    .notification-icon {
      font-size: 30px;
      margin-right: 15px;
      color: #1c78e6;
    }

    .notification-text {
      flex: 1;
    }

    .notification-title {
      font-weight: bold;
      font-size: 16px;
      margin-bottom: 4px;
    }

    .notification-time {
      font-size: 12px;
      color: #888;
    }

    .mark-read {
      font-size: 13px;
      color: #007bff;
      cursor: pointer;
    }

    .mark-read:hover {
      text-decoration: underline;
    }
        </style>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">           
       <!-- Notification 1 (Unread) -->
  
    
      
      @foreach($notifications as $notify)
      <div class="notification-card">
      <div class="d-flex align-items-start">
      <i class="material-icons notification-icon">notifications</i>
      <div class="notification-text">
        <div class="notification-title">{{ $notify->notification_text }}</div>
        <div class="notification-desc">{{ $notify->notification_message }}</div>
      </div>
      </div>
      </div>
      @endforeach
    
    
  




        
        
                        </div>
                    </div>
                  
                </div>
            </div>
        </section>
        <style>
            .modal-open .modal {
    overflow-x: hidden;
    overflow-y: auto;
    background: #0000007d;
}
        </style>
        <!-- The Modal -->
  <div class="modal" id="adddoc">
    <div class="modal-dialog" style="max-width: 600px;">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Add document</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
           <form>
      <div class="form-group">
        <label for="docName">Document Name</label>
        <input type="text" class="form-control" id="docName" placeholder="e.g., Work Report March" required>
      </div>

      <div class="form-group">
        <label for="docCategory">Category</label>
        <select class="form-control" id="docCategory" required>
          <option value="">Select Category</option>
          <option>Work</option>
          <option>Personal</option>
          <option>Finance</option>
          <option>Legal</option>
        </select>
      </div>

      <div class="form-group">
        <label for="docFile">Upload File</label>
        <input type="file" class="form-control-file" id="docFile" required>
      </div>

      <button type="submit" class="btn btn-primary btn-block">Submit Document</button>
    </form>
        </div>
        
        
        
      </div>
    </div>
  </div>
        
        
        
        
        
        <footer class="footer_area f_bg_color">
        <img class="p_absolute leaf" src="img/v.svg" alt="">
        <img class="p_absolute f_man wow fadeInLeft" data-wow-delay="0.4s" src="img/home_two/f_man.png" alt="">
        <img class="p_absolute f_cloud" src="img/home_two/cloud.png" alt="">
        <img class="p_absolute f_email" src="img/home_two/email-icon.png" alt="">
        <img class="p_absolute f_email_two" src="img/home_two/email-icon_two.png" alt="">
        <img class="p_absolute f_man_two wow fadeInLeft" data-wow-delay="0.2s" src="img/home_two/man.png" alt="">
        <div class="footer_top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-sm-6">
                        <div class="f_widget subscribe_widget wow fadeInUp">
                         
                            <h4 class="c_head">Subscribe to our newsletter</h4>
                            <form action="#" class="footer_subscribe_form">
                                <input type="email" placeholder="Email" class="form-control">
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
                                <li><a href="#">Home</a></li>
                                <li><a href="#">About Us</a></li>
                                <li><a href="#">Pricing</a></li>
                                <li><a href="#">Blog</a></li>
                               
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
                                <li><a href="#">Privacy Policy</a></li>
                                <li><a href="#">Terms & Conditions</a></li>
                               
                              
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="border_bottom"></div>
            </div>
        </div>
        @include('frontend.include.footer')
</body>



</html>