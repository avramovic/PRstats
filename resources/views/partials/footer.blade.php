<footer class="site-footer">
    <div class="text-center">
        <p>
            Copyright &copy; 2017-{{ date('Y') }}, <a href="https://avramovic.info" target="_blank">Nemanja Avramovic</a> a.k.a. <a href="/sgt-baker">Sgt_Baker</a>
        </p>
        <div class="credits">
            Tracking {{ \PRStats\Models\Player::count() }} players and {{ \PRStats\Models\Clan::count() }} clans in {{ \PRStats\Models\Match::count() }} matches across {{ \PRStats\Models\Server::count() }} servers.
        </div>
{{--        <div class="credits">--}}
{{--            Created with Dashio template by <a href="https://templatemag.com/">TemplateMag</a>--}}
{{--        </div>--}}
        <a href="#" class="go-top">
            <i class="fa fa-angle-up"></i>
        </a>
    </div>
</footer>