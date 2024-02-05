@extends('layouts.prstats')

@section('title')
    PRstats is shutting down!
@endsection

@section('content')
    <div class="col-lg-6">
        <h4>PRstats is shutting down!</h4>
        <section>
            <p>After 7 years (with a couple of breaks) of running this website, it's time to say goodbye.</p>
            <p>Since taking a new role on my job I really don't have time to play games nor to continue maintaining this website. I didn't have too much time to play before either (check <a href="/sgt-baker" target="_blank">my stats</a> and when was the last time I was playing), but I did play from time to time and I had enthusiasm to maintain this website, if nothing else, then to see my stats update after I finish playing. But now when I don't even play this awesome game anymore, it's not really fun to keep maintaining this website.</p>
            <p>The domain name will expire on <strong>2024-05-03</strong> and I don't plan to renew it. Once it expires, I will shut down the background services used to gather data and generate avatars/signatures.</p>
            <p>If anyone is willing to take over the hosting and maintenance of this website, hit me up on avramyu <em>at</em> gmail <em>dot</em> com before the date stated above. The stack used is PHP (Laravel) with MySQL database, and AWS is used for static file hosting (S3) and background task processing (SQS).</p>
            <p>Thank you all for being part of this, but it's time to focus on other things in life.</p>
            <p>&nbsp;</p>
            <p><em>Sgt. Baker is my name<br/>
            I'm gonna teach you how to play the game<br />
            of WARFAREEEEE!!!</em></p>
            <iframe width="560" height="315" src="https://www.youtube.com/embed/TUSxX3K3q1c?si=rSo9xXv082zCS10C" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
        </section>
        <!-- /content-panel -->


    </div>


@endsection
