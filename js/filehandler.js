/*
 * Javascript to handle file uploads
 */

function test(){
    console.log("test");
}

//trigger when a file is selected to upload
function handleFileSelect(evt) {
    console.log("handlefileselect");
    var files = evt.target.files; // FileList object
    for (var i = 0, f; f = files[i]; i++) {        
        var name = f.name;        
        var newName = name.split(' ').join('_');        
        sendRequest(f, newName);
    }
}

// Setup variables for uploading the file
BYTES_PER_CHUNK = 1024 * 1024; // 1MB chunk sizes.
var slices;
var slices2;

//does the uploading of the file
function sendRequest(blob, fname) {

    var format = fname.split('.')[1];
    var compatible = ['pptx', 'docx'];

    if ($.inArray(format, compatible) !== -1) {

        var start = 0;
        var end;
        var index = 0;

        // calculate the number of slices required
        slices = Math.ceil(blob.size / BYTES_PER_CHUNK);
        slices2 = slices;

        while (start < blob.size) {
            end = start + BYTES_PER_CHUNK;
            if (end > blob.size) {
                end = blob.size;
            }

            uploadFile(blob, index, start, end, fname);

            start = end;
            index++;
        }
    } else {
        //invalid format
    }
}

function uploadFile(blob, index, start, end, fname) {
    var xhr;
    var end;
    var fd;
    var chunk;
    var url;
    var id;

    xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            if (xhr.responseText) {
                console.log(xhr);
            }

            slices--;

            updateProgress(slices, slices2);

            // if we have finished all slices
            if (slices == 0) {
                mergeFile(fname);
            }
        }
    };

    chunk = blob.slice(start, end);

    fd = new FormData();
    fd.append("file", chunk);
    fd.append("name", fname);
    fd.append("index", index);

    xhr.open("POST", "./scripts/writer.php", true);
    xhr.send(fd);
}

//reconstruct slices into original file
function mergeFile(fname) {
    
    var xhr;

    xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            if (xhr.responseText) {
                console.log(xhr);
            }
            console.log("success");
            document.location.href = "index.php#documents";
            location.reload();
        }
    };
    fd = new FormData();
    fd.append("name", fname);
    fd.append("index", slices2);

    xhr.open("POST", "./scripts/merge.php", true);
    xhr.send(fd);            
}


//update progress bar as file uploads
function updateProgress(slices, totalSlices) {

    //var percentage = ((totalSlices - slices) / totalSlices) * 100;

    //var cssPercentage = percentage + "%";

    //$('#upload-progress .progress-bar').css('width', cssPercentage);
}