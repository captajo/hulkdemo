<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="author" content="colorlib.com">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet" />
    <link href="{{asset('css/main.css')}}" rel="stylesheet" />
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" />
  </head>
  <body style="color: white;">
    <div class="s003">
      
      <br><br> 
      <div class="row">
        <div class="col-sm-12">
          <h2 style="color: white;">Library Configuration</h2>  
          <br>
          <h4>Application Testing</h4>
          <ul>
            <li>Test Database Connectivity (MySQL) - </li>
            <li>Test Elasticsearch Availability - </li>
            <li>Test Memcache Availability - </li>
          </ul>
          <br>
          <h4>Application Elasticsearch Indexing</h4>
          <br>
          <div class="row">
            <div class="col-sm-12">
              Do you want to start/re-index your library? <br> <br>
              <button class="btn btn-danger" onClick="reIndexLibrary()" id="reindexButton">Start/Re-Index Button</button> &nbsp;
              <i id="reindex_resp" class="text-danger"></i>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-sm-12">
              Do you want to add latest records to library index? <br> <br>
              <button class="btn btn-warning" onClick="indexLatestChanges()" id="indexLatestButton">Index Latest Button</button> &nbsp;
              <i id="index_latest_resp" class="text-danger"></i>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-sm-12">
              Do you want to update a record? <br> <br>
              <a href="{{ url('/update') }}"><button class="btn btn-info">Update Index Record</button></a>
            </div>
          </div>
          <br>
        <br>
        <br>
        <br>
        <div class="text-center"><a class="text-warning" href="{{ url('/update') }}">Records Update Page</a> | <a class="text-warning" href="{{ url('/search') }}">Search Page</a></div>
        </div>
      </div>
    </div>
    <script src="{{asset('js/extention/choices.js')}}"></script>
    <script src="{{asset('js/jquery-2.1.3.min.js')}}"></script>
    <script src="{{asset('js/app/config.js')}}"></script>
    <script>
      const choices = new Choices('[data-trigger]',
      {
        searchEnabled: false,
        itemSelectText: '',
      });

    </script>
  </body><!-- This templates was made by Colorlib (https://colorlib.com) -->
</html>
