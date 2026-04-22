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
                              <h4>Profile Info</h4>
                               <hr/>
                               @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

                            <div class="user-profile-form">
                                            <form action="{{ url('/user/update-profile') }}"  method="POST" >
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Full name</label> <input class="form-control" name="full_name"  placeholder="Full name" type="text" value="{{ Auth::user()->full_name }}">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>E-mail</label> <input class="form-control"  name="email" placeholder="E-mail" type="text" value="{{ Auth::user()->email }}"   readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Phone</label> <input class="form-control" name="mobile_number"  placeholder="Phone" type="text" value="{{ Auth::user()->mobile_number }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Address</label> <input class="form-control" name="address" placeholder="Address" type="text" value="{{ Auth::user()->address }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="btn btn-primary my-3" type="submit">Save Changes</button>
                                            </form>
                                        </div>
                                     <hr/>
                                      <h4>Password Setting</h4>
                                       <hr/>
                                            <div class="user-profile-form">
                                                <form action="{{ url('/user/change-password') }}"  method="POST" >
                                                    @csrf
                                                    <div class="form-group">
                                                        <label>Old Password</label> <input class="form-control" name="old_password" type="password">
                                                    </div>
                                                    <div class="form-group">
    <label>New Password</label>
    <input class="form-control" name="new_password" type="password" required>
</div>
<div class="form-group">
    <label>Repeat New Password</label>
    <input class="form-control" name="confirm_password" type="password" required>
</div>

                                                    <button class="btn btn-primary my-3" type="submit">Change password</button>
                                                </form>
                                            </div>
                                        
                        </div>
                    </div>
                  
                </div>
            </div>
        </section>
       @include('frontend.include.footer')
</body>


</html>