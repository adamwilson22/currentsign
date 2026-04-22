<?php 
   $seo['short_title']       = "Add User"; 
   $seo['full_title']        = "Add User"; 
   $seo['short_description'] = "Add User"; 
   $seo['full_description']  = "Add User"; 
   $seo['keywords']          = "Add User"; 
   
   ?>
@include('admin.include.header', ['seo' => $seo])
<?php  //show_error_or_success_helper($errors); ?>


    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ route('users.index') }}"> Dos</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-description">
                        Add a user


                    </h5>
<form action="{{ route('users.store') }}" class="forms-sample" method="POST">
    @csrf
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{old('email')}}" required/>
                        </div>
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" value="{{old('first_name')}}" required/>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name"  value="{{old('last_name')}}" required/>
                        </div>
                        
                        
                        <?php /*
                        <?php $country = DB::table("countries")->get(); ?>
                        
                        <div class="form-group">
                            <label for="mobile_country_code">Indicatif de pays mobile</label>
                            <select class="form-control" id="mobile_country_code" name="mobile_country_code" placeholder="Indicatif de pays mobile" required>
                                @foreach($country as $country_in)
                                    <option value="{{ $country_in->id }}" @if(old('mobile_country_code') == $country_in->id) selected @endif>{{ $country_in->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        */ ?>
                        
                        <div class="form-group">
                            <label for="mobile_number">Mobile No</label>
                            <input type="text" class="form-control" id="mobile_number" name="mobile_number" placeholder="Mobile No" value="{{old('mobile_number')}}" required/>
                        </div>
                      
                        
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe" value="{{old('password')}}" required/>
                        </div>
                        
                        
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" value="{{old('confirm_password')}}" required/>
                        </div>
                    
                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
                        <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>
                    </form>

                </div>
            </div>
        </div>
    </div>
    
@include('admin.include.footer')
