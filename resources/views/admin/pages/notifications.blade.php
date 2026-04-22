<?php 
$seo['short_title']       = "Notifications"; 
$seo['full_title']        = "Notifications"; 
$seo['short_description'] = "Notifications"; 
$seo['full_description']  = "Notifications"; 
$seo['keywords']          = "Notifications"; 
?>

@include('admin.include.header', ['seo' => $seo])

<?php


        if(isset($_REQUEST['send_notification'])){

        $users = \App\Models\User::all();
        
        foreach($users as $users_in){
        }
          echo "<div class='alert alert-success'>Notification Send Successfully.</div>";
        }

?>

<div class="row grid-margin">
    <div class="col-12">
        <div class="card ">
            <div class="card-body">
                <!-- Notification form -->
                <form method="POST" action="">
                    @csrf

                    <!-- Notification title -->
                    <div class="form-group">
                        <label for="title">Notification Title:</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Enter notification title" required>
                    </div>

                    <!-- Notification message -->
                    <div class="form-group">
                        <label for="message">Notification Message:</label>
                        <textarea class="form-control" id="message" name="message" rows="3" placeholder="Enter notification message" required></textarea>
                    </div>

                    <!-- Submit button -->
                    
                    <input type="hidden" id="send_notification" name="send_notification" required>

                    
                    <button type="submit" class="btn btn-primary">Send Notification</button>
                </form>
                <!-- End of Notification form -->
            </div>
        </div>
    </div>
</div>

@include('admin.include.footer')
