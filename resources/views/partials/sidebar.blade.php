<aside>
    <div id="sidebar" class="nav-collapse ">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">
{{--            <p class="centered"><a href="profile.html"><img src="img/ui-sam.jpg" class="img-circle" width="80"></a></p>--}}
{{--            <h5 class="centered">Sam Soffes</h5>--}}
            <li class="mt">
                <a href="/" class="{{ (request()->is('/')) ? 'active' : '' }}">
                    <i class="fa fa-home"></i>
                    <span>Home</span>
                </a>
            </li>
            <li class="">
                <a href="/players" class="{{ (request()->is('player*')) ? 'active' : '' }}">
                    <i class="fa fa-user"></i>
                    <span>Players</span>
                </a>
            </li>
            <li class="">
                <a href="/clans" class="{{ (request()->is('clan*')) ? 'active' : '' }}">
                    <i class="fa fa-users"></i>
                    <span>Clans</span>
                </a>
            </li>
            <li class="">
                <a href="/servers" class="{{ (request()->is('server*') || request()->is('match*')) ? 'active' : '' }}">
                    <i class="fa fa-server"></i>
                    <span>Servers</span>
                </a>
            </li>
            <li class="">
                <a href="/maps" class="{{ (request()->is('map*')) ? 'active' : '' }}">
                    <i class="fa fa-map"></i>
                    <span>Maps</span>
                </a>
            </li>
            <li class="">
                <a href="/notifications" class="{{ (request()->is('notifications*')) ? 'active' : '' }}">
                    <i class="fa fa-bell"></i>
                    <span>Notifications</span>
                </a>
            </li>

        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>
