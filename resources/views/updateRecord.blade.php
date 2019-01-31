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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="s003">
      <form>
        <h2>What do you want to update?</h2>
        <div class="inner-form">
          <div class="input-field first-wrap" style="width: 100%;">
            <div class="input-select">
              <select data-trigger="" onChange="getSelected()" id="previewFilter" name="choices-single-defaul">
                <option value="videos">Videos</option>
                <option value="actors">Actors</option>
                <option value="tags">Tags</option>
                <option value="categories">Categories</option>
              </select>
            </div>
          </div>
          <!-- <div class="input-field second-wrap">
            <input id="search" type="text" placeholder="Type here to search" />
          </div> -->
          <div class="input-field third-wrap">
            <button class="btn-search" type="button" onClick="getSelected()">
              <svg class="svg-inline--fa fa-search fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="search" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path fill="currentColor" d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path>
              </svg>
            </button>
          </div>
        </div>
        <br>
        <br>
        <table class="table">
          <tbody id="displayVideos"></tbody>
          <tbody id="displayTags"></tbody>
          <tbody id="displayCategories"></tbody>
          <tbody id="displayActors"></tbody>
        </table>
        <div class="pull-right" style="display: none;" id="pagination">
          <ul class="pagination">
            <li><a style="cursor: pointer;" id="pagination_back_last" onClick="filterList('0', true)" style="display: none;">&laquo;</a></li>
            <li><a style="cursor: pointer;" id="pagination_back" onClick="filterList('0')"  style="display: none;">Prev</a></li>
            <li class="active"><a id="current_page">1</a></li>
            <li><a style="cursor: pointer;" id="pagination_next" onClick="filterList('2')" style="display: none;">Next</a></li>
            <li><a style="cursor: pointer;" id="pagination_next_last" onClick="filterList('2', false, true)" style="display: none;">&raquo;</a></li>
          </ul>
        </div>
        <br>
        <br>
        <br>
        <br>
        <div class="text-center"><a class="text-warning" href="{{ url('/configuration') }}">Configuration Page</a> | <a class="text-warning" href="{{ url('/search') }}">Search Page</a></div>
      </form>
    </div>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title text-primary" id="myModalLabel">Update Record</h4>
          </div>
          <div class="modal-body" style="color: black;">
            <div class="alert alert-info">
              <em>Updating</em> records would update its value in the database and its index value
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Title</label>
                  <input type="text" id="editTitle" class="form-control" placeholder="Enter a title here" name="">
                </div>
                <div id="video_section">
                  <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" id="editDescription" rows="4" placeholder="Enter a description here" name=""></textarea>
                  </div>
                  <div class="form-group">
                    <label>Actors</label> <span id="available_actors"></span>
                    <input type="text" class="form-control" id="actor_search_term" onkeyup="searchNew('actor')" placeholder="Add new actors" name="">
                    <div class="sug_box" id="actor_suggestions" style="display: none;">

                    </div>
                  </div>
                  <div class="form-group">
                    <label>Tags</label> <span id="available_tags"></span>
                    <input type="text" class="form-control" id="tag_search_term" onkeyup="searchNew('tag')" placeholder="Add new tag" name="">
                    <div class="sug_box" id="tag_suggestions" style="display: none;">
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Category</label> <span id="available_categories"></span>
                    <input type="text" class="form-control" id="category_search_term" onkeyup="searchNew('category')" placeholder="Add new categories" name="">
                    <div class="sug_box" id="category_suggestions" style="display: none;">
                    </div>
                  </div>
                </div>
              </div>
            </div>            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onClick="updateSelected()" id="updateRecordButton">Save changes</button>
            <span id="request_resp"></span>
          </div>
        </div>
      </div>
    </div>

    <script src="{{asset('js/extention/choices.js')}}"></script>
    <script src="{{asset('js/jquery-2.1.3.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/app/record.js')}}"></script>
    <script>
      const choices = new Choices('[data-trigger]',
      {
        searchEnabled: false,
        itemSelectText: '',
      });

    </script>
  </body><!-- This templates was made by Colorlib (https://colorlib.com) -->
</html>
