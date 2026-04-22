 <div aria-orientation="vertical" class="nav flex-column nav-pills wow fadeInUp" data-wow-delay=".2s" data-wow-duration="1.5s" id="v-pills-tab" role="tablist" style="visibility: visible; animation-duration: 1.5s; animation-delay: 0.2s;">
                          <a href="{{ url('profile') }}" class="nav-link nav-btn-style mb-3 @if($menu == 'profile') active  @endif">Profile</a>
                          <a href="{{ url('user/project-list') }}" class="nav-link nav-btn-style mb-3  @if($menu == 'projects') active  @endif ">Projects</a>
                          
		                  
                         
		                   <a href="{{ url('user/contacts') }}" class="nav-link nav-btn-style mb-3 @if($menu == 'contacts') active  @endif ">Contacts</a>
		                    <!--<a href="{{ url('user/company-detail') }}" class="nav-link nav-btn-style mb-3  @if($menu == 'company-detail') active  @endif ">Company details</a>-->
                          <a href="{{ url('/logout') }}" class="nav-link nav-btn-style mb-3">Logout</a>
                    
                    </div>