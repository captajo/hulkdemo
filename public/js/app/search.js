//jquery functions
var real_total = 0;
var current_page = 1;
var current_suggestions = [];
document.getElementById('search').focus();

$('html').bind('keypress', function(e)
{
   if(e.keyCode == 13)
   {
      $("#searchButton").click();
      return false;
   }
});

//get query by url
setTimeout(()=>{
    var urlParams = new URLSearchParams(window.location.search);
    var myParam = urlParams.get('search_term');
    if(myParam) {
        document.getElementById('search').value = myParam;
        document.getElementById('searchButton').click();
    }
},500);

function performSearch(goto = 1, first = false, last = false, total = real_total) {
    let search = document.getElementById('search').value;
    document.getElementById('suggestion_area').style.display = 'none';

    let size = 10;
    if(goto == '2') {
        current_page++;
    } else if(goto == '0') {
        current_page--;
    } else {
        current_page = 1;
    }

    var formdata = new FormData();
    formdata.append('search_term', search);
    formdata.append('goto', current_page);
    formdata.append('first', first);
    formdata.append('last', last);
    formdata.append('total', total);

    $.ajax({
        type: "POST",
        url: '/api/search',
        data: formdata,
        processData: false,
        contentType: false,
        beforeSend: function(xhr) {

        },
        success: function(resp) {
            console.log(resp);
            if(resp.status == 'success') {

                if(resp.data.result.length > 0) {
                    document.getElementById('search_result_area').innerHTML = '';
                    document.getElementById('search_summary').innerHTML = '<i>Search Term:</i> '+search+' <i class="pull-right">About '+resp.data.page_data.total_result.toLocaleString() +' results found</i>';
                    real_total = resp.data.page_data.total_result;

                    let sn = resp.data.page_data.current;
                    resp.data.result.map((item) => {
                        let file = item._source;

                        //initialization
                        let all_actors = '';
                        let all_tags = '';
                        let all_categories = '';

                        // actors
                        if(file.actors.length > 0) {
                            file.actors.map((item2) => {
                                all_actors += '<div class="label label-primary" style="margin: 5px;">'+item2+'</div> &nbsp;';
                            });                            
                        }

                        //tags
                        if(file.tags.length > 0) {
                            file.tags.map((item2) => {
                                all_tags += '<div class="label label-default" style="margin: 5px;">'+item2+'</div> &nbsp;';
                            });                            
                        }

                        // categories
                        if(file.categories.length > 0) {
                            file.categories.map((item2) => {
                                all_categories += '<div class="label label-warning" style="margin: 5px;">'+item2+'</div> &nbsp;';
                            });                            
                        }

                        let tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td width="5%" style="color: white; font-size: 20px;">`+(sn + 1)+`.</td>
                            <td style="color: white">
                                <span style="color: brown"><i>`+file.title+`</i></span> <br/>
                                `+file.description+` <br/> <br/>
                                <div class="row">
                                    <div class="col-sm-1">Actors:</div>
                                    <div class="col-sm-11">`+all_actors+`</div>
                                </div>
                                <br/>
                                <div class="row">
                                    <div class="col-sm-1">Tags:</div>
                                    <div class="col-sm-11">`+all_tags+`</div>
                                </div>
                                <br/>
                                <div class="row">
                                    <div class="col-sm-1">Category: </div>
                                    <div class="col-sm-11">`+all_categories+`</div>
                                </div>
                                <br/>
                            </td>
                        `;
                        tr.setAttribute('style', 'padding: 10px 0px;');
                        document.getElementById('search_result_area').appendChild(tr);
                        sn++;
                    });
                } else {
                    document.getElementById('search_summary').innerHTML = '<i>Search Term:</i> '+search+' <small class="pull-right">About 0 found</small>';
                    document.getElementById('search_result_area').innerHTML = '';
                    real_total = 0;
                }

                //get suggestions
                if(resp.data.suggest) {
                    current_suggestions = [];
                    resp.data.suggest.simple_actor_phrase.map((item) => {
                        item.options.map((item2)=>{
                            //check if suggestion exist
                            let check = current_suggestions.indexOf(item2.text);
                            if(check == -1)
                                current_suggestions.push(item2.text);
                        });
                    });

                    resp.data.suggest.simple_tags_phrase.map((item) => {
                        item.options.map((item2)=>{
                            //check if suggestion exist
                            let check = current_suggestions.indexOf(item2.text);
                            if(check == -1)
                                current_suggestions.push(item2.text);
                        });
                    });

                    resp.data.suggest.simple_title_phrase.map((item) => {
                        item.options.map((item2)=>{
                            //check if suggestion exist
                            let check = current_suggestions.indexOf(item2.text);
                            if(check == -1)
                                current_suggestions.push(item2.text);
                        });
                    });

                    if(current_suggestions.length > 0) {
                        document.getElementById('suggestion_area').style.display = '';
                        let suggestions = '';
                        current_suggestions.map((item) => {
                            console.log(item);
                            suggestions += '<a href="/search?search_term='+item+'"><span class="label label-default">'+item+'</span></a> &nbsp';
                        });
                        document.getElementById('suggestion_area').innerHTML = '<br/>Did you mean: '+suggestions;
                    }
                }
                

                document.getElementById('pagination').style.display = '';

                if(resp.pager) {
                    document.getElementById('pagination').style.display = '';
                    //pagination configuration
                    if(resp.pager.next) {
                        document.getElementById('pagination_next').style.display = '';
                        document.getElementById('pagination_next_last').style.display = '';
                    } else {
                        document.getElementById('pagination_next').style.display = 'none';
                        document.getElementById('pagination_next_last').style.display = 'none';
                    }

                    if(resp.pager.back) {
                        document.getElementById('pagination_back').style.display = '';
                        document.getElementById('pagination_back_last').style.display = '';
                    } else {
                        document.getElementById('pagination_back').style.display = 'none';
                        document.getElementById('pagination_back_last').style.display = 'none';
                    }

                    current_page = resp.pager.nextPage;
                    document.getElementById('current_page').innerHTML = current_page;
                    document.getElementsByTagName('body')[0].scrollTop = '100%';
                } else {
                    document.getElementById('pagination').style.display = 'none';
                } 
            }
        },
        error: function(xhr) { // if error occured
            console.log('error');
        },
        complete: function() {

        },
        dataType: 'json'
    });
}
