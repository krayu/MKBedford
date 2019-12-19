<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header" style="width: 335px;">
                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <div id="logo">
                  <a href="{{ url('/') }}">
                    <img src="/img/logo.png" text="MKBedford logo">
                  </a>                
                </div>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <div id="search">
                            <form action="{{ url('search') }}" method="POST">
                              {{ csrf_field() }}
                              <input type="text" id="search_box" name="searched">
                              <input type="submit" id="submit" value="Search">
                            </form>                        
                        </div>
                    </li>
                    <li>
                        <div class="links">
                            <div id="links-left">
                                  <a href="/show/milton-keynes" title="Milton Keynes"><img class="opacited" src="/img/mk.png" alt="Milton Keynes"></a>  
                                  <a href="/show/bedford" title="Bedford"><img class="opacited" src="/img/bed.png" alt="Bedford"></a>                      
                                  <a href="/show/northampton" title="Northampton"><img class="opacited" src="/img/nor.png" alt="Northampton"></a>  
                                  <a href="/show/luton" title="Luton"><img class="opacited" src="/img/lut.png" alt="Luton"></a>       
                            </div>
                            <div id="icons-left">
                                <a href="/show/english" title="Show only English ads"><img class="opacited" style="margin-bottom: 5px;" src="/img/uk.png" alt="Show only English ads"></a> 
                                <a href="/show/images" title="Show only ads with images"><img class="opacited" src="/img/photo.png" alt="Show only ads with images"></a>       
                            </div>
                            <div id="icons-right">
                                <a href="/show/polish" title="Show only Polish ads"><img class="opacited" style="margin-bottom: 5px;" src="/img/pl.png" alt="Show only Polish ads"></a>
                                <a href="/show/all" title="Show all ads"><img class="opacited" src="/img/off.png" alt="Show all ads"></a>
                            </div>
                        </div>
                    </li>
                    @if (Auth::guest())

                    @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                        </ul>
                    </li>
                    @endif
                </ul>
            </div>
        </div>    
    </nav>
    <div class="container">
      <input type="hidden" value="51" id="counter" />
      @yield('content')
    </div>
    <div id="footer">
       <div class="container">
            <div class="col-sm-3">
                     <a href="/show/milton-keynes" title="Milton Keynes">Milton Keynes</a><br/>  
                    <a href="/show/bedford" title="Bedford">Bedford</a>
            </div>
            <div class="col-sm-3">
                     <a href="/show/northampton" title="Northampton">Northampton</a><br/>   
                    <a href="/show/luton" title="Luton">Luton</a>   
            </div>
            <div class="col-sm-3">
                     <a href="/show/english" title="Show only English ads">English ads</a><br/>   
                    <a href="/show/polish" title="Show only Polish ads">Polish ads</a>
            </div>
            <div class="col-sm-3">
                     <a href="/show/images" title="Show only ads with images">Ads with images</a><br/>    
                    <a href="/show/all" title="Show all ads">All ads</a>
            </div>  
        </div>                                                           
    </div>
    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
      var win = $(window);
      win.scroll(function() {
        if ($(document).height() - win.height() - 50 < win.scrollTop()) {
          var $counter = $('#counter').val();
          var $counter_number = parseInt($counter);
          $.ajax({
            url: '/show-more', dataType: 'html', data: { counter : $counter, '_token': '{!! csrf_token() !!}'}, method: 'post',
            success: function(html) {
              $('#posts').append(html);
              $('#counter').val($counter_number+9);
              $('#loading').hide();
            }
          });
        }
      });
    });
    </script>
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-92563238-3', 'auto');
	  ga('send', 'pageview');

	</script>
</body>
</html>
