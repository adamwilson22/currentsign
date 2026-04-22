<?php
$seo['short_title'] = "Edit User";
$seo['full_title'] = "Edit User";
$seo['short_description'] = "Edit User";
$seo['full_description'] = "Edit User";
$seo['keywords'] = "Edit User";
?>
@include('admin.include.header', ['seo' => $seo])
<?php //show_error_or_success_helper($errors); ?>

<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>
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
                    Update a user
                </h5>

                <form class="forms-sample" action="{{ route('users.update',$user->id) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{$user->email}}" required />
                    </div>
                    <div class="form-group">
                        <label for="first_name">First name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" value="{{$user->first_name}}" required />
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" value="{{$user->last_name}}" required />
                    </div>



                    <div class="form-group">
                        <label for="password">Mobile No</label>
                        <input type="text" class="form-control" id="mobile_number" name="mobile_number" placeholder="Mobile No" value="{{$user->mobile_number}}" required />
                    </div>

                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h5 class="card-description">
                    Update password

                </h5>

                <form class="forms-sample" action="{{ route('admin.users.update.password', $user->id) }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="{{old('password')}}" required />
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm password" value="{{old('confirm_password')}}" required />
                    </div>

                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>
                </form>
            </div>
        </div>
    </div>
</div>

@include('admin.include.footer')
