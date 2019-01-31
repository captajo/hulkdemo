//jquery functions

function reIndexLibrary() {
    if(confirm('Are you sure you want to refresh your library index?')) {
        $.ajax({
            type: "DELETE",
            url: '/api/library/re-index',
            // data: formdata,
            processData: false,
            contentType: false,
            beforeSend: function(xhr) {
                document.getElementById('reindexButton').setAttribute('class', 'text text-danger');
                document.getElementById('reindexButton').setAttribute('disabled', 'disabled');
                document.getElementById('reindex_resp').innerHTML = '<div class="lds-circle"></div> please wait.... might take a minute or two';
            },
            success: function(resp) {
                console.log(resp);
                if(resp.status == 'success') {
                    document.getElementById('reindex_resp').innerHTML = 'completed successful';
                } else {
                    document.getElementById('reindex_resp').innerHTML = '';
                }
            },
            error: function(xhr) { // if error occured
                console.log('error');
            },
            complete: function() {
                document.getElementById('reindexButton').setAttribute('class', 'text text-info');
                document.getElementById('reindexButton').removeAttribute('disabled');
                
            },
            dataType: 'json'
        });
    }        
}

function indexLatestChanges() {
    $.ajax({
            type: "PUT",
            url: '/api/library/index/latest',
            // data: formdata,
            processData: false,
            contentType: false,
            beforeSend: function(xhr) {
                document.getElementById('index_latest_resp').setAttribute('class', 'text text-danger');
                document.getElementById('indexLatestButton').setAttribute('disabled', 'disabled');
                document.getElementById('index_latest_resp').innerHTML = '<div class="lds-circle"></div> please wait.... checking for new videos';
            },
            success: function(resp) {
                console.log(resp);
                if(resp.status == 'success') {
                    document.getElementById('index_latest_resp').innerHTML = resp.data;
                } else {
                    document.getElementById('reindex_resp').innerHTML = '';
                }
            },
            error: function(xhr) { // if error occured
                document.getElementById('index_latest_resp').innerHTML = 'An error occured, please try again';
            },
            complete: function() {
                document.getElementById('index_latest_resp').setAttribute('class', 'text text-info');
                document.getElementById('indexLatestButton').removeAttribute('disabled');                
            },
            dataType: 'json'
        });
}
