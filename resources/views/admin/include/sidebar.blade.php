<!-- LEFT MAIN SIDEBAR -->
		<div class="ec-left-sidebar ec-bg-sidebar">
			<div id="sidebar" class="sidebar ec-sidebar-footer">

				<div class="ec-brand">
					<a href="{{ url('admin/dashboard') }}">
						<img class="ec-brand-icon" src="" alt="" />
						
					</a>
				</div>

				<!-- begin sidebar scrollbar -->
				<div class="ec-navigation" data-simplebar>
    <!-- menu latéral -->
    <ul class="nav sidebar-inner" id="sidebar-menu">
        <!-- Tableau de bord -->
        <li class="{{ $data['menu'] === 'dashboard' ? 'active' : '' }}">
            <a class="sidenav-item-link" href="{{ url('admin/dashboard') }}">
                <i class="mdi mdi-view-dashboard-outline"></i>
                <span class="nav-text">Dashboard</span>
            </a>
        </li>

        <li class="{{ $data['menu'] === 'customers' ? 'active' : '' }}">
            <a class="sidenav-item-link" href="{{ url('admin/customer-list') }}">
                <i class="mdi mdi-account-group"></i>
                <span class="nav-text">Users</span>
            </a>
        </li>
        
        
        <?php /*
        
        <!--<li class="{{ $data['menu'] === 'hotels' ? 'active' : '' }}">-->
        <!--    <a class="sidenav-item-link" href="{{ route('hotel') }}">-->
        <!--        <i class="mdi mdi-format-list-bulleted"></i>-->
        <!--        <span class="nav-text">Hotels</span>-->
        <!--    </a>-->
        <!--</li>-->
        <!--<li class="{{ $data['menu'] === 'promo_codes' ? 'active' : '' }}">-->
        <!--    <a class="sidenav-item-link" href="{{ route('promocodes') }}">-->
        <!--        <i class="mdi mdi-format-list-bulleted"></i>-->
        <!--        <span class="nav-text">Promo codes</span>-->
        <!--    </a>-->
        <!--</li>-->
        <!--   <li class="{{ $data['menu'] === 'bookings' ? 'active' : '' }}">-->
        <!--    <a class="sidenav-item-link" href="{{ route('booking') }}">-->
        <!--        <i class="mdi mdi-account-group"></i>-->
        <!--        <span class="nav-text">Bookings</span>-->
        <!--    </a>-->
        <!--</li>-->
        
        */ ?>
        
        
        <?php /*
        <li class="{{ $data['menu'] === 'supports' ? 'active' : '' }}">
            <a class="sidenav-item-link" href="{{ route('support') }}">
                <i class="mdi mdi-account-group"></i>
                <span class="nav-text">Supports</span>
            </a>
        </li>
        <li class="{{ $data['menu'] === 'terms_and_conditions' ? 'active' : '' }}">
            <a class="sidenav-item-link" href="{{ route('terms_and_conditions.edit') }}">
                <i class="mdi mdi-format-list-bulleted"></i>
                <span class="nav-text">Terms and Conditions</span>
            </a>
        </li>
         <li class="{{ $data['menu'] === 'privacy_policies' ? 'active' : '' }}">
            <a class="sidenav-item-link" href="{{ route('privacy_policies.edit') }}">
                <i class="mdi mdi-format-list-bulleted"></i>
                <span class="nav-text">Privacy Policies</span>
            </a>
        </li>
        
        
        <li class="{{ $data['menu'] === 'about_us' ? 'active' : '' }}">
            <a class="sidenav-item-link" href="{{ route('about_us.edit') }}">
                <i class="mdi mdi-format-list-bulleted"></i>
                <span class="nav-text">About US</span>
            </a>
        </li>
       

        <!-- <li class="{{ $data['menu'] === 'orders' ? 'active' : '' }}" >-->
        <!--    <a class="sidenav-item-link" href="{{ url('admin/order-listt') }}">-->
        <!--        <i class="mdi mdi-format-list-bulleted"></i>-->
        <!--        <span class="nav-text">Commandes</span>-->
        <!--    </a>-->
        <!--</li>-->

        */ ?>

        
        <!--  <li>-->
        <!--    <a class="sidenav-item-link" href="#">-->
        <!--        <i class="mdi mdi-file"></i>-->
        <!--        <span class="nav-text">Citation</span>-->
        <!--    </a>-->
        <!--</li>-->


        <!--<li>-->
        <!--    <a class="sidenav-item-link" href="#">-->
        <!--        <i class="mdi mdi-bell"></i>-->
        <!--        <span class="nav-text">Notifications</span>-->
        <!--    </a>-->
        <!--</li>-->
        <!-- Avis -->
        <!--<li>-->
        <!--    <a class="sidenav-item-link" href="#">-->
        <!--        <i class="mdi mdi-star-half"></i>-->
        <!--        <span class="nav-text">Avis</span>-->
        <!--    </a>-->
        <!--</li>-->
    </ul>
</div>

			</div>
		</div>