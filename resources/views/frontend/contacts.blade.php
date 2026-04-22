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
     
     <div class="col-md-12 mb-2 d-flex" style="justify-content: space-between;">
                   <h4>Contacts</h4>
                                      @if ($errors->has('error'))
    <div class="alert alert-danger">
        {{ $errors->first('error') }}
    </div>
@endif
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
                   <div>
                       <form action="{{ route('contacts.import') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" accept=".csv,.txt,.xlsx,.xls">
    <button lass="theme-btn" tyle="background-color: #173f5f !important; border-color:#173f5f !important; cursor:pointer;" type="submit">Import Contacts</button>
</form>

                     <!--<label class="theme-btn" style="background-color: #173f5f !important; border-color:#173f5f !important; cursor:pointer;"><input type="file" name="file" style="display:none;" accept=".csv,.txt,.xlsx,.xls"> Import From Excel</label>  -->
                       <a href="{{ url('public/public/Book1.xlsx') }}" class="theme-btn"  style="background-color: #173f5f !important; border-color:#173f5f !important;">Simple Excel file </a>
                   <a href="{{ url('user/create-contact') }}" class="theme-btn">Create New</a></div>
                   
              </div>
     
           <div class="col-md-12 mb-2">  
              <div class="row">
                 <div class="table-responsive">
                 <table class="table" id="example">
                     <thead>
                         <tr>
                              <th>Company</th>
                             <th>Contacts Name</th>
                            
                             <th>Email</th>
                             <th>Phone Number</th>
                             <th>Location</th>
                             <th>Status</th>
                             <th>Action</th>
                         </tr>
                     </thead>
                     <tbody>
                         @foreach($contacts as $contact)
                          <tr>
                              <td>{{ $contact->company_name }}</td>
                             <td>{{ $contact->contect_name }}</td>
                             
                             <td>{{ $contact->email }}</td>
                             <td>{{ $contact->phone_number }}</td>
                             <td>{{ $contact->address }}</td>
                             <td>{{ $contact->contact_status }}</td>
                             <td><a href="{{ url('user/edit-contact/'.$contact->id) }}" class="theme-btn" >Edit</a><a href="{{ url('user/delete-contact/'.$contact->id) }}" onclick="return confirmDelete()" class="btn btn-danger" >Delete</a></td>
                         </tr>
                         @endforeach
                     </tbody>
                 </table>
                 </div> 
              </div>
          </div>
     
          </div>  
              
         </div>
         <!--end row -->
     </div>
    </div>
        <!-- end dashboard area -->


        

    </main>

  <!-- footer area -->
@include('frontend.include.footer')
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.bootstrap5.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.colVis.min.js"></script>
<script>
 $('#example').DataTable( {
    "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]]
} );
</script>
<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this contact?");
    }
</script>

<script>
function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
    // Specify file name
    filename = filename?filename+'.xls':'excel_data.xls';
    
    // Create download link element
    downloadLink = document.createElement("a");
    
    document.body.appendChild(downloadLink);
    
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob( blob, filename);
    }else{
        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
    
        // Setting the file name
        downloadLink.download = filename;
        
        //triggering the function
        downloadLink.click();
    }
}
</script>

</body>


</html>