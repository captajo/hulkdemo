let selected_type = '';
let selected_id = '';
let selected_actors = [];
let selected_tags = [];
let selected_categories = [];
let real_total = 0;
let current_page = 1;

setTimeout(()=>{
    getSelected();
}, 1000);

function getSelected() {
    let selected = document.getElementById('previewFilter').value;
    selected_type = selected;
    let url = '';
    let display_table = '';

    clearContent();

    if(selected == 'videos') {
        url = '/api/library/videos';
        display_table = 'displayVideos';
    } else if(selected == 'tags') {
        url = '/api/library/tags';
        display_table = 'displayTags';
    } else if(selected == 'categories') {
        url = '/api/library/categories';
        display_table = 'displayCategories'
    } else if(selected == 'actors') {
        url = '/api/library/actors';
        display_table = 'displayActors';
    }

    $.ajax({
            type: "GET",
            url: url,
            // data: formdata,
            processData: false,
            contentType: false,
            beforeSend: function(xhr) {
                document.getElementById(display_table).innerHTML = '';
            },
            success: function(resp) {
                console.log(resp);
                let sn = 1;
                if(resp.status == 'success') {
                    if(resp.data.length > 0) {
                        resp.data.map((item)=>{
                            let tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td width="4%">`+sn+`</td>
                                <td>`+item.title+`</td>
                                <td width="4%">
                                    <a data-toggle="modal" data-target="#myModal" onClick="editRecord('`+item.id+`')" style="cursor: pointer;">Edit</a>
                                </td>
                                `;
                            document.getElementById(display_table).appendChild(tr);
                            sn++;
                        });
                    }   
                    real_total = resp.total;
                    current_page = 1;
                    if(resp.total > 10) {
                        document.getElementById('pagination').style.display = '';
                        document.getElementById('pagination_next').style.display = '';
                        document.getElementById('pagination_next_last').style.display = '';
                        document.getElementById('current_page').innerHTML = '1';
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

function clearContent() {
    document.getElementById('displayVideos').innerHTML = '';
    document.getElementById('displayTags').innerHTML = '';
    document.getElementById('displayCategories').innerHTML = '';
    document.getElementById('displayActors').innerHTML = '';
}

function editRecord(id) {
    selected_id = id;
    let selected = selected_type;

    let formdata = new FormData();
    if(selected == 'videos') {
        url = '/api/filter/videos';
        formdata.append('video_id', id);
    } else if(selected == 'tags') {
        url = '/api/filter/tags';
        formdata.append('tag_id', id);
    } else if(selected == 'categories') {
        url = '/api/filter/categories';
        formdata.append('category_id', id);
    } else if(selected == 'actors') {
        url = '/api/filter/actors';
        formdata.append('actor_id', id);
    }

    $.ajax({
            type: "POST",
            url: url,
            data: formdata,
            processData: false,
            contentType: false,
            beforeSend: function(xhr) {
                document.getElementById('video_section').style.display = 'none';
            },
            success: function(resp) {
                console.log(resp);
                if(resp.status == 'success') {
                    document.getElementById('editTitle').value = resp.data.title;
                    if(selected == 'videos') {
                        document.getElementById('video_section').style.display = '';
                        document.getElementById('editDescription').value = resp.data.description;

                        let actors = [];
                        let actor_actors = '';
                        resp.data.actors.map((item)=>{
                            actor_actors += '<div id="vactor_'+item.id+'" class="label label-default">'+item.title+' &nbsp; <a onClick="removeGrouping(\'actor\', \''+item.id+'\')" style="color: white; cursor: pointer">X</a></div> &nbsp;';
                            actors.push(item.id);
                        });

                        let tags = [];
                        let actor_tags = '';
                        resp.data.tags.map((item)=>{
                            actor_tags += '<div id="vtag_'+item.id+'" class="label label-default">'+item.title+' &nbsp; <a onClick="removeGrouping(\'tag\', \''+item.id+'\')" style="color: white; cursor: pointer">X</a></div> &nbsp;';
                            tags.push(item.id);
                        });

                        let categories = [];
                        let category_tags = '';
                        resp.data.categories.map((item)=>{
                            category_tags += '<div id="vcategory_'+item.id+'" class="label label-default">'+item.title+' &nbsp; <a onClick="removeGrouping(\'category\', \''+item.id+'\')" style="color: white; cursor: pointer">X</a></div> &nbsp;';
                            categories.push(item.id);
                        });

                        //assign to global variables
                        selected_actors = actors;
                        selected_tags = tags;
                        selected_categories = categories;

                        document.getElementById('available_actors').innerHTML = actor_actors;
                        document.getElementById('available_tags').innerHTML = actor_tags;
                        document.getElementById('available_categories').innerHTML = category_tags;
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

function searchNew(type) {
    let search_term = '';
    let url = '';

    //hide all suggestion boxes
    document.getElementById('actor_suggestions').style.display = 'none';
    document.getElementById('tag_suggestions').style.display = 'none';
    document.getElementById('category_suggestions').style.display = 'none';

    if(type == 'actor') {
        search_term = document.getElementById('actor_search_term').value;
        url = 'api/filter/actors?search_term='+search_term;
    } else if(type == 'tag') {
        search_term = document.getElementById('tag_search_term').value;
        url = 'api/filter/tags?search_term='+search_term;
    } else if(type == 'category') {
        search_term = document.getElementById('category_search_term').value;
        url = 'api/filter/categories?search_term='+search_term;
    }

    $.ajax({
            type: "GET",
            url: url,
            // data: formdata,
            processData: false,
            contentType: false,
            beforeSend: function(xhr) {

            },
            success: function(resp) {
                console.log(resp);
                if(resp.status == 'success') {
                    let drop = ''
                    if(type == 'actor') {
                        drop = 'actor_suggestions';
                        document.getElementById('actor_suggestions').style.display = '';
                    } else if(type == 'tag') {
                        drop = 'tag_suggestions';
                        document.getElementById('tag_suggestions').style.display = '';
                    } else if(type == 'category') {
                        drop = 'category_suggestions';
                        document.getElementById('category_suggestions').style.display = '';
                    }
                    document.getElementById(drop).innerHTML = '';

                    resp.data.info.map((item) => {
                        let div = document.createElement('div');
                        div.classList.add('row');
                        div.innerHTML = `
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <hr class="sug_bar">
                              <a onClick="addGrouping('`+type+`', '`+item.id+`', '`+item.title+`')" style="cursor: pointer;">
                                <div class="row">
                                  <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    `+item.title+`
                                  </div>
                                </div>
                              </a>
                            </div>
                        `;
                        document.getElementById(drop).appendChild(div);
                    });
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

function addGrouping(type, id, text) {
    if(type == 'actor') {
        let check = selected_actors.indexOf(id);
        if(check == -1) {
            let div = document.createElement('div');
            div.setAttribute('id', 'vactor_'+id);
            div.innerHTML = text+' &nbsp; <a onClick="removeGrouping(\'actor\', \''+id+'\')" style="color: white; cursor: pointer">X</a>';
            div.setAttribute('class', 'label label-default');
            document.getElementById('available_actors').appendChild(div);
            selected_actors.push(id);
            document.getElementById('actor_suggestions').innerHTML = '';
            document.getElementById('actor_suggestions').style.display = 'none';
            document.getElementById('actor_search_term').value = '';
        }
    } else if(type == 'tag') {
        let check = selected_tags.indexOf(id);
        if(check == -1) {
            let div = document.createElement('div');
            div.setAttribute('id', 'vtag_'+id);
            div.innerHTML = text+' &nbsp; <a onClick="removeGrouping(\'tag\', \''+id+'\')" style="color: white; cursor: pointer">X</a>';
            div.setAttribute('class', 'label label-default');
            document.getElementById('available_tags').appendChild(div);
            selected_tags.push(id);
            document.getElementById('tag_suggestions').innerHTML = '';
            document.getElementById('tag_suggestions').style.display = 'none';
            document.getElementById('tag_search_term').value = '';
        }

    } else if(type == 'category') {
        let check = selected_categories.indexOf(id);
        if(check == -1) {
            let div = document.createElement('div');
            div.setAttribute('id', 'vcategory_'+id);
            div.innerHTML = text+' &nbsp; <a onClick="removeGrouping(\'category\', \''+id+'\')" style="color: white; cursor: pointer">X</a>';
            div.setAttribute('class', 'label label-default');
            document.getElementById('available_categories').appendChild(div);
            selected_categories.push(id);
            document.getElementById('category_suggestions').innerHTML = '';
            document.getElementById('category_suggestions').style.display = 'none';
            document.getElementById('category_search_term').value = '';
        }
    }
}

function removeGrouping(type, id) {
    if(type == 'actor') {
        document.getElementById('vactor_'+id).parentNode.removeChild(document.getElementById('vactor_'+id));
        let check = selected_actors.indexOf(Number(id));
        if(check > -1)
            selected_actors.splice(check, 1);

    } else if(type == 'tag') {
        document.getElementById('vtag_'+id).parentNode.removeChild(document.getElementById('vtag_'+id));
        let check = selected_tags.indexOf(Number(id));
        if(check > -1)
            selected_tags.splice(check, 1);

    } else if(type == 'category') {
        document.getElementById('vcategory_'+id).parentNode.removeChild(document.getElementById('vcategory_'+id));
        let check = selected_categories.indexOf(Number(id));
        if(check > -1)
            selected_categories.splice(check, 1);
    }
}

function updateSelected() {
    let selected = selected_type;
    let title = document.getElementById('editTitle').value;
    let description = '';
    let actors = [];
    let tags = [];
    let category = [];

    let formdata = '';
    if(selected == 'videos') {
        description = document.getElementById('editDescription').value;
        actors = selected_actors;
        tags = selected_tags;
        category = selected_categories;

        formdata = {
            'title': title,
            'description': description,
            'actors': actors,
            'tags': tags,
            'categories': category,
        }
    } else {
        formdata = new FormData();
        formdata.append('title', title);
    }    

    
    if(selected == 'videos') {
        url = '/api/videos/update';
        formdata.video_id = selected_id;
    } else if(selected == 'tags') {
        url = '/api/tags/update/term';
        formdata.append('tag_id', selected_id);
    } else if(selected == 'categories') {
        url = '/api/categories/update';
        formdata.append('category_id', selected_id);
    } else if(selected == 'actors') {
        url = '/api/actors/update';
        formdata.append('actor_id', selected_id);
    }


    if(selected == 'videos') {
        $.ajax({
                type: "POST",
                url: url,
                data: JSON.stringify(formdata),
                // data: formdata,
                processData: false,
                contentType: false,
                beforeSend: function(request) {
                    document.getElementById('updateRecordButton').setAttribute('disabled', 'disabled');
                    document.getElementById('request_resp').innerHTML = '<div class="lds-circle"></div>';
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function(resp) {
                    console.log(resp);
                    if(resp.status == 'success') {
                        document.getElementById('updateRecordButton').removeAttribute('disabled');
                        $('#myModal').modal('hide');
                        getSelected();
                    }
                },
                error: function(xhr) { // if error occured
                    console.log('error');

                },
                complete: function() {
                    document.getElementById('request_resp').innerHTML = '';
                    document.getElementById('updateRecordButton').removeAttribute('disabled');
                },
                dataType: 'json'
        });
    } else {
        $.ajax({
                type: "POST",
                url: url,
                // data: JSON.stringify(formdata),
                data: formdata,
                processData: false,
                contentType: false,
                beforeSend: function(request) {
                    document.getElementById('updateRecordButton').setAttribute('disabled', 'disabled');
                    document.getElementById('request_resp').innerHTML = '<div class="lds-circle"></div>';
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function(resp) {
                    console.log(resp);
                    if(resp.status == 'success') {
                        document.getElementById('updateRecordButton').removeAttribute('disabled');
                        $('#myModal').modal('hide');
                        getSelected();
                    }
                },
                error: function(xhr) { // if error occured
                    console.log('error');

                },
                complete: function() {
                    document.getElementById('request_resp').innerHTML = '';
                    document.getElementById('updateRecordButton').removeAttribute('disabled');
                },
                dataType: 'json'
        });
    } 
}

function paginate(resp) {
    if(resp.data.back || resp.data.next) 
        document.getElementById('pagination').style.display = '';

    if(resp.data.back) {
        document.getElementById('pagination_back').style.display = '';
        document.getElementById('pagination_back_last').style.display = '';
    } else {
        document.getElementById('pagination_back').style.display = 'none';
        document.getElementById('pagination_back_last').style.display = 'none';
    }

    if(resp.data.next) {
        document.getElementById('pagination_next').style.display = '';
        document.getElementById('pagination_next_last').style.display = '';
    } else {
        document.getElementById('pagination_next').style.display = 'none';
        document.getElementById('pagination_next_last').style.display = 'none';
    }

    document.getElementById('current_page').innerHTML = resp.data.nextPage;
}

function filterList(goto = 1, first = false, last = false, total = real_total) {
    let size = 10;
    let url = '';

    if(goto == '2') {
        current_page++;
    } else if(goto == '0') {
        current_page--;
        if(current_page == 0)
            current_page = 1;
    } else {
        current_page = 1;
    }

    let selected = selected_type;
    let display_table = '';

    clearContent();

    if(selected == 'videos') {
        url = '/api/filter/videos?goto='+current_page+'&first='+first+'&last='+last+'&total='+total;
        display_table = 'displayVideos';
    } else if(selected == 'tags') {
        url = '/api/filter/tags?goto='+current_page+'&first='+first+'&last='+last+'&total='+total;
        display_table = 'displayTags';
    } else if(selected == 'categories') {
        url = '/api/filter/categories?goto='+current_page+'&first='+first+'&last='+last+'&total='+total;
        display_table = 'displayCategories';
    } else if(selected == 'actors') {
        url = '/api/filter/actors?goto='+current_page+'&first='+first+'&last='+last+'&total='+total;
        display_table = 'displayActors';
    }

    console.log(url);

    $.ajax({
            type: "GET",
            url: url,
            processData: false,
            contentType: false,
            beforeSend: function(xhr) {
                document.getElementById(display_table).innerHTML = '';
            },
            success: function(resp) {
                console.log(resp);
                let sn = ((current_page - 1) * 10) + 1;
                if(resp.status == 'success') {
                    if(resp.data.info.length > 0) {
                        resp.data.info.map((item)=>{
                            let tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td width="4%">`+sn+`</td>
                                <td>`+item.title+`</td>
                                <td width="4%">
                                    <a data-toggle="modal" data-target="#myModal" onClick="editRecord('`+item.id+`')" style="cursor: pointer;">Edit</a>
                                </td>
                                `;
                            document.getElementById(display_table).appendChild(tr);
                            sn++;
                        });
                    }  
                    paginate(resp); 
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