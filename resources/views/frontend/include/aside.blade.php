  <div class="overlay_bg"></div>
 <aside class="doc_left_sidebarlist">
                            <div class="open_icon" id="left">
                                <span style="font-size:12px; font-weight:bold">Menu <i class="arrow_carrot-right"></i>
                                <i class="arrow_carrot-left"></i>
                                </span>
                           
                            </div>
                            <div class="scroll">
                                <ul class="list-unstyled nav-sidebar">
                                    <li class="nav-item">
                                        <a href="{{ url('/user/dashboard') }}" class="nav-link"> <i class="fa fa-home"></i> Dashboard</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/user/documents') }}" class="nav-link"> <i class="fa fa-file"></i> Documents</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/user/notes') }}" class="nav-link"> <i class="fa fa-edit"></i> Notes</a>
                                    </li>
                                    <li class="nav-item  active">
                                        <a href="{{ url('/profile') }}" class="nav-link"> <i class="fa fa-user"></i> Profile</a>
                                    </li>
                                     <li class="nav-item">
                                        <a href="{{ url('/user/notifications') }}" class="nav-link"> <i class="fa fa-bell"></i> Notifications</a>
                                    </li>
                                   <li class="nav-item">
                                        <a href="{{ url('/logout') }}" class="nav-link"> <i class="fa fa-sign-out"></i> Log Out</a>
                                    </li>
                                </ul>
                              
                            </div>
                        </aside>