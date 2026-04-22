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
        <h1> Support List</h1>
        <p class="breadcrumbs"><span><a href="dashboard.html">Welcome</a></span>
            <span><i class="mdi mdi-chevron-right"></i></span>Support
        </p>
    </div>
    
</div>

					<div class="row">
						<div class="col-12">
							<div class="ec-vendor-list card card-default">
								<div class="card-body">
									<div class="table-responsive">
										<table id="responsive-data-table" class="table">
											<thead>
												<tr>
    <th>User</th>
    <th>message</th>
  
    <th>Action</th>
</tr>

											</thead>

											<tbody>
											    @foreach($supports as $support)
											    @php 
											        $user = DB::table('users')->where('id', $support->user_id)->first();
											    @endphp
										<tr>
    <td>{{ $user->email ?? 'NA' }}</td>
    <td>{{ $support->message }}</td>
    <td>
        <div class="btn-group mb-1">
            
              <button type="button" class="btn btn-danger deleteUser" data-id="{{ $support->id }}">Delete</button>
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
					<div class="modal fade modal-add-contact" id="addUser" tabindex="-1" role="dialog"
						aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
							<div class="modal-content">
								<form>
    <div class="modal-header px-4">
        <h5 class="modal-title" id="exampleModalCenterTitle">Ajouter un nouveau client</h5>
    </div>

    <div class="modal-body px-4">
        <div class="form-group row mb-6">
            <label for="coverImage" class="col-sm-4 col-lg-2 col-form-label">Image du client</label>

            <div class="col-sm-8 col-lg-10">
                <div class="custom-file mb-1">
                    <input type="file" class="custom-file-input" id="coverImage" required>
                    <label class="custom-file-label" for="coverImage">Choisir un fichier...</label>
                    <div class="invalid-feedback">Message d'erreur personnalisé pour les fichiers</div>
                </div>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="firstName">Prénom</label>
                    <input type="text" class="form-control" id="firstName" value="John">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <label for="lastName">Nom de famille</label>
                    <input type="text" class="form-control" id="lastName" value="Deo">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label for="userName">Nom d'utilisateur</label>
                    <input type="text" class="form-control" id="userName" value="johndoe">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" value="johnexample@gmail.com">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label for="Birthday">Date de naissance</label>
                    <input type="text" class="form-control" id="Birthday" value="10-12-1991">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label for="event">Adresse</label>
                    <input type="text" class="form-control" id="event" value="Adresse ici">
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer px-4">
        <button type="button" class="btn btn-secondary btn-pill" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-primary btn-pill">Enregistrer le contact</button>
    </div>
</form>

							</div>
						</div>
					</div>
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
    
     $('.editUser').on('click', function() {
        var userId = $(this).data('id'); // Get user ID from data-id attribute
        console.log(userId);

        // Make an AJAX request to fetch user data
        $.ajax({
            url:  "{{ route('customers.get') }}/"+ userId, // Adjust the URL to your route
            method: 'GET',
            success: function(response) {
                // Populate modal fields with user data
                $('#firstName').val(response.first_name);
                $('#lastName').val(response.surname);
                $('#userName').val(response.username);
                $('#email').val(response.email);
                $('#Birthday').val(response.dob);
                $('#event').val(response.address);
                $('#userId').val(response.id);
            },
            error: function(xhr) {
                // Handle error
                alert('An error occurred while fetching user data.');
            }
        });
    });
    
    
     // Handle form submission (if needed)
      // Handle form submission
    $('#saveChanges').on('click', function() {
        var formData = new FormData($('#editUserForm')[0]); // Use FormData to include file data
        console.log(formData);

        $.ajax({
            url: "{{ route('customers.update') }}", // Adjust the URL to your update route
            method: 'POST',
            data: formData,
            processData: false, // Prevent jQuery from automatically processing the data
            contentType: false, // Prevent jQuery from automatically setting the content type
            success: function(response) {
                // Handle success
                toastr.success('User updated successfully!');
                
                //alert('User updated successfully!');
                $('#editUser').modal('hide'); // Close the modal
                location.reload();
            },
            error: function(xhr) {
                // Handle error
               // alert('An error occurred while updating user data.');
                 toastr.error('An error occurred while updating user data.');
            }
        });
    });
    
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
                    url: "{{ route('support.delete') }}/"+ userId, // Adjust the URL to your delete route
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