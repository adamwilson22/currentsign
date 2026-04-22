<!-- Header -->
			<header class="ec-main-header" id="header">
				<nav class="navbar navbar-static-top navbar-expand-lg justify-content-between">
					<!-- Sidebar toggle button -->
					<button id="sidebar-toggler" class="sidebar-toggle"></button>
					
					<!-- navbar right -->
					<div class="navbar-right">
						<ul class="nav navbar-nav">
							<!-- User Account -->
							<li class="dropdown user-menu">
    <button class="dropdown-toggle nav-link ec-drop" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="{{ asset('public/assets/img/user/user.png') }}" class="user-image" alt="Image de l'utilisateur" />
    </button>
    <ul class="dropdown-menu dropdown-menu-right ec-dropdown-menu">
        <!-- Image de l'utilisateur -->
        <li class="dropdown-header">
            <img src="{{ asset('public/assets/img/user/user.png') }}" class="img-circle" alt="Image de l'utilisateur" />
            <div class="d-inline-block">
                Admin <small class="pt-1">admin@gmail.com</small>
            </div>
        </li>
        <!--<li>-->
        <!--    <a href="user-profile.html">-->
        <!--        <i class="mdi mdi-account"></i> Mon profil-->
        <!--    </a>-->
        <!--</li>-->
        <!--<li>-->
        <!--    <a href="#">-->
        <!--        <i class="mdi mdi-email"></i> Message-->
        <!--    </a>-->
        <!--</li>-->

        <li class="dropdown-footer">
            <a href="{{ route('admin.logout') }}"> <i class="mdi mdi-logout"></i> LOGOUT </a>
        </li>
    </ul>
</li>

<!--							<li class="dropdown notifications-menu custom-dropdown">-->
<!--	<button class="dropdown-toggle notify-toggler custom-dropdown-toggler">-->
<!--		<i class="mdi mdi-bell-outline"></i>-->
<!--	</button>-->

<!--	<div class="card card-default dropdown-notify dropdown-menu-right mb-0">-->
<!--		<div class="card-header card-header-border-bottom px-3">-->
<!--			<h2>Notifications</h2>-->
<!--		</div>-->

<!--		<div class="card-body px-0 py-0">-->
<!--			<ul class="nav nav-tabs nav-style-border p-0 justify-content-between" id="myTab" role="tablist">-->
<!--				<li class="nav-item mx-3 my-0 py-0">-->
<!--					<a href="#" class="nav-link active pb-3" id="home2-tab" data-bs-toggle="tab"-->
<!--						data-bs-target="#home2" role="tab" aria-controls="home2" aria-selected="true">Tout (10)</a>-->
<!--				</li>-->

				
<!--			</ul>-->

<!--			<div class="tab-content" id="myNotifications">-->
<!--				<div class="tab-pane fade show active" id="home2" role="tabpanel">-->
<!--					<ul class="list-unstyled" data-simplebar style="height: 360px">-->
<!--						<li>-->
<!--							<a href="javscript:void(0)" class="media media-message media-notification">-->
<!--								<div class="position-relative mr-3">-->
<!--									<img class="rounded-circle" src="assets/img/user/u2.jpg" alt="Image">-->
<!--									<span class="status away"></span>-->
<!--								</div>-->
<!--								<div class="media-body d-flex justify-content-between">-->
<!--									<div class="message-contents">-->
<!--										<h4 class="title">Nitin</h4>-->
<!--										<p class="last-msg">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nam itaque doloremque odio, eligendi delectus vitae.</p>-->

<!--										<span class="font-size-12 font-weight-medium text-secondary">-->
<!--											<i class="mdi mdi-clock-outline"></i> Il y a 30 minutes...-->
<!--										</span>-->
<!--									</div>-->
<!--								</div>-->
<!--							</a>-->
<!--						</li>-->
						<!-- Reste du contenu omis pour la concision -->
<!--					</ul>-->
<!--				</div>-->
				<!-- Les deux autres onglets sont également omis pour la concision -->
<!--			</div>-->
<!--		</div>-->
<!--	</div>-->

<!--	<ul class="dropdown-menu dropdown-menu-right d-none">-->
<!--		<li class="dropdown-header">Vous avez 5 notifications</li>-->
<!--		<li>-->
<!--			<a href="#">-->
<!--				<i class="mdi mdi-account-plus"></i> Nouvel utilisateur enregistré-->
<!--				<span class=" font-size-12 d-inline-block float-right"><i class="mdi mdi-clock-outline"></i> 10 h</span>-->
<!--			</a>-->
<!--		</li>-->
		<!-- Les autres notifications sont omises pour la concision -->
<!--		<li class="dropdown-footer">-->
<!--			<a class="text-center" href="#"> Voir tout </a>-->
<!--		</li>-->
<!--	</ul>-->
<!--</li>-->

							
						</ul>
					</div>
				</nav>
			</header>