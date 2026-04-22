<?php 
   $seo['short_title']       = "User"; 
   $seo['full_title']        = "User"; 
   $seo['short_description'] = "User"; 
   $seo['full_description']  = "User"; 
   $seo['keywords']          = "User"; 
   
   ?>
@include('admin.include.header', ['seo' => $seo])
<?php  //show_error_or_success_helper($errors); ?>
<div class="row">
   <div class="col-12 grid-margin">
      <div class="card">
         <div class="card-body">
            <div class="pull-right" style="display: inline;">
               <a class="btn btn-success" href="{{ route('users.create') }}"> Add User</a>
            </div>
            <div class="pull-right" style="float: right;">
               <div class="input-group">
                  <form style="display: inline;">
                     <div class="btn-group" role="group" aria-label="Basic example">
                        <input type="text" class="form-control" placeholder="Search" name="search" value="<?php echo @$_REQUEST['search']; ?>">
                        <button class="btn btn-primary" type="submit">Search</button>
                        <!--<input type="hidden" class="form-control" name="page" value="<?php echo @$_REQUEST['page']; ?>">-->
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<style>
   svg.w-5.h-5 {
   width: 10px;
   }
</style>
<div class="row">
   <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
         <div class="card-body">
            <div class="table-responsive">
               <table class="table table-striped">
                  <thead>
                     <tr>
                        <th>
                           User ID

                        </th>
                        <th>
                          First and last name

                        </th>
                        <th>
                           Email
                        </th>
                        <th>
                           Mobile
                        </th>
                        <th>
                           Action
                        </th>

                     </tr>
                  </thead>
                  <tbody>
                     @foreach ($users as $user)
                     <tr>
                        <td class="py-1">{{ $user->id }}</td>
                        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td></td>
                        
                        <td>
                           <form action="{{ route('users.destroy',$user->id) }}" method="POST">
                              <div class="btn-group" role="group" aria-label="Basic example">
                                 <a class="btn btn-primary" href="{{ route('users.edit',$user->id) }}">Edit</a>
                                 @csrf @method('DELETE')
                                 <button type="submit" class="btn btn-danger">Delete</button>
                              </div>
                           </form>
                        </td>

                     </tr>
                     @endforeach
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.all.min.js"></script>
<script>
   function confirmAccept(id) {
       // Use SweetAlert for the confirmation dialog
       Swal.fire({
           title: 'Are you sure send reminder?',
           text: 'Do you want send this',
           icon: 'warning',
           showCancelButton: true,
           confirmButtonText: 'Yes, send it!',
           cancelButtonText: 'No, cancel!',
           reverseButtons: true
       }).then((result) => {
           if (result.isConfirmed) {
               
               AcceptItem(id);
               
           } else if (result.dismiss === Swal.DismissReason.cancel) {
           }
       });
   }
   
   function AcceptItem(id) {
       // Replace this with your actual delete logic
       //alert(id);
   }
</script>
{!! $users->links() !!} @include('admin.include.footer')