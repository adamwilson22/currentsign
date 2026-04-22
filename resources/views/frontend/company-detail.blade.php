@include('frontend.include.header')
@include('frontend.include.nav-bar')

 <main class="main">

        <!-- breadcrumb -->
        <!--<div class="site-breadcrumb" style="background: url(assets/img/breadcrumb/01.jpg)">-->
        <!--    <div class="container">-->
        <!--        <h2 class="breadcrumb-title">Company Detail</h2>-->
        <!--        <ul class="breadcrumb-menu">-->
        <!--            <li><a href="index.html">Home</a></li>-->
        <!--            <li class="active">Company Detail</li>-->
        <!--        </ul>-->
        <!--    </div>-->
        <!--</div>-->
        <!-- breadcrumb end -->

        
        <!-- dashboard area -->
        <div class="dashboard-section pt-5 mb-110">
        <div class="container">
         <div class="row">
             
           <div class="profile-nav col-md-3">
         
      
         @include('frontend.include.user-sidebar')
         
  </div>
 
  <div class="profile-info col-md-9">
     
     <div class="col-md-12 mb-3">
                   <h4>Company Information</h4>
                   <hr/>
              </div>
  
          <div class="col-md-12">   
              <div class="row">
                   <form action="#">
                       <div id="fields-wrapper">
												<div class="row  mb-2" style="border-bottom:1px solid #ddd">
													<div class="col-md-12 field-group">
														<div class="form-group">
															<label class="editable-label" contenteditable="false">Company Name</label> <input class="form-control" placeholder="Company Name" type="text" value="ABC Pvt Ltd">
														</div>
													</div>
													<div class="col-md-12">
														<div class="form-group">
															<label>Address1</label> <input class="form-control" placeholder="Address1" type="text" value="25/AB AA Rbh Road, USA">
														</div>
													</div>
														<div class="col-md-12">
														<div class="form-group">
															<label>Address2</label> <input class="form-control" placeholder="Address2" type="text" value="Near Old Police station, USA">
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label>Suburb</label> 
														<input class="form-control" placeholder="Suburb" type="text" value="wdfwydfyw">
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label>State</label> 
														<input class="form-control" placeholder="State" type="text" value="tewwdwdt">
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label>Postcode</label> 
														<input class="form-control" placeholder="Postcode" type="text" value="46464464">
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label>Phone</label> <input class="form-control" placeholder="Phone" type="text" value="(XX) 7888XXXXXX">
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label>Email</label> <input class="form-control" placeholder="Email" type="email" value="cardeeal@mail.com">
														</div>
													</div>
													
													
													<div class="col-md-4">
														<div class="form-group">
															<label>ABN</label> <input class="form-control" placeholder="ABN" type="text" value="">
														</div>
													</div>
												</div>
										</div>
						<div class="button-container mb-3 gap-2 d-flex" style="float: right;">
        <button type="button" id="add-btn" class="btn btn-warning text-white">+ Add More</button>
        <button type="button" id="remove-btn" class="btn btn-danger">- Remove</button>
    </div>		
												
						 <div class="col-md-12 mt-5">   					
						 <button class="btn btn-success" type="button"> Update </button>
						 </div>
						</form>
             
              </div>
         </div>
     
          </div>  
              
         </div>
         <!--end row -->
     </div>
    </div>
        <!-- end dashboard area -->


        

    </main>


@include('frontend.include.footer')
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const wrapper = document.getElementById('fields-wrapper');
        const addBtn = document.getElementById('add-btn');
        const removeBtn = document.getElementById('remove-btn');

        // Function to add a new field group with editable labels
        addBtn.addEventListener('click', () => {
            const newFieldGroup = document.createElement('div');
            newFieldGroup.className = 'row mb-3 field-group';
            newFieldGroup.innerHTML = `
                <div class="form-group">
							<label class="editable-label" contenteditable="true"  spellcheck="false" style="padding: 2px 10px; margin-bottom:10px;background-color: #ddd;border: 2px solid #f9f9f9;">Company Name</label> <input class="form-control" placeholder="Company Name" type="text" value="ABC Pvt Ltd">
				</div>
                
            `;
            wrapper.appendChild(newFieldGroup);
        });

        // Function to remove the last field group
        removeBtn.addEventListener('click', () => {
            const fieldGroups = wrapper.querySelectorAll('.field-group');
            if (fieldGroups.length > 1) {
                wrapper.removeChild(fieldGroups[fieldGroups.length - 1]);
            }
        });
    });
</script>
</body>


</html>