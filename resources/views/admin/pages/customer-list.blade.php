@include('admin.include.header')
	<!-- WRAPPER -->
	<div class="wrapper">

		<!-- LEFT MAIN SIDEBAR -->
	    @include('admin.include.sidebar')

		<!--  PAGE WRAPPER -->
		<div class="ec-page-wrapper">

				<!-- Header -->
			@include('admin.include.header-nav')

			<!-- CONTENT WRAPPER -->
			<div class="ec-content-wrapper">
				<div class="content">
					<div class="breadcrumb-wrapper breadcrumb-contacts">
    <div>
        <h1> Customer List</h1>
        <p class="breadcrumbs"><span><a href="dashboard.html">Welcome</a></span>
            <span><i class="mdi mdi-chevron-right"></i></span>Customer
        </p>
        
        @if(session('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
        
        
        
    </div>
    <div>
      <a href="{{ route('customers.add') }}">  <button type="button" class="btn btn-primary" > Customer Add
        </button></a>
    </div>
</div>

					<div class="row">
						<div class="col-12">
						    <div class="card mb-2">
   
</div>


						    
							<div class="ec-vendor-list card card-default">
								<div class="card-body">
									<div class="table-responsive">
										<table id="responsive-data-table" class="table">
											<thead>
												<tr>
                                            <th>Profil</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>DOB</th>
                                            <th>Created At</th>
                                            <th>Signatures Pdf</th>
                                            
                                            <th>Action</th>
                                            </tr>

								</thead>
                         	<tbody>
                                @foreach($users as $user)
                                <tr>
                                <td><img class="vendor-thumb" src="{{ url("/") . "/" . "public/uploads/users/" .$user->image }}" alt="user profile" /></td>
                                <td>{{ $user->full_name}}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->dob }}</td>
                                <td>{{ $user->created_at }}</td>
                                
               <td>  <a class="dropdown-item" href="{{ route('signatures', ['id' => $user->id]) }}">PDF</a></td>                 
                                
    <td>
        <div class="btn-group mb-1">
           
            <button type="button" class="btn btn-outline-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                <span class="sr-only">Info</span>
            </button>

            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('customers.edit', ['id' => $user->id]) }}">Edit</a>
                 <a class="dropdown-item deleteUser" data-id="{{ $user->id }}" href="#">Delete</a>
            </div>
        </div>
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
					<!-- Add User Modal  -->

				</div> <!-- End Content -->
			</div> <!-- End Content Wrapper -->

		@include('admin.include.footer')
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
	
	<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
			<script>
$(document).ready(function() {
    
      var csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });
    
     toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    
    
    
    
     $('.deleteUser').on('click', function() {
        var userId = $(this).data('id'); // Get user ID from data-id attribute

        // Show SweetAlert2 confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with the deletion if confirmed
                $.ajax({
                    url: "{{ route('customers.delete') }}/"+ userId, // Adjust the URL to your delete route
                    method: 'POST',
                    success: function(response) {
                        Swal.fire(
                            'Deleted!',
                            'The user has been deleted.',
                            'success'
                        );
                        // Optionally, reload the page or remove the deleted item from the UI
                        location.reload(); // Reload the page to reflect changes
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            'An error occurred while deleting the user.',
                            'error'
                        );
                    }
                });
            }
        });
    });
    
});
</script>
</body>


</html>