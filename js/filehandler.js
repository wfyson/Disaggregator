/*
 * Javascript to handle file uploads
 */

//trigger when a file is selected to upload
function handleFileSelect(evt, callback) {
    var files = evt.target.files; // FileList object
    var source = evt.currentTarget.name;
    for (var i = 0, f; f = files[i]; i++) {
        var name = f.name;
        var newName = name.split(' ').join('_');
        sendRequest(f, newName, source, callback);
    }
}

// Setup variables for uploading the file
BYTES_PER_CHUNK = 512 * 512; // 1MB chunk sizes.
var slices;
var slices2;

//does the uploading of the file
function sendRequest(blob, fname, source, callback) {


    var format = fname.split('.')[1];
    //var compatible = ['pptx', 'docx'];

    // if ($.inArray(format, compatible) !== -1) {

    //display the progress bar and remove the upload interface
    console.log("sending the file!!");
    $('#file-upload').fadeOut("slow", function() {
        $('#upload-progress').fadeIn("slow", function() {
            var dir = "";
            switch (source) {
                case "doc_source":
                    dir = "uploads";
                    break;
                case "temp_source":
                    dir = "temp";
            }

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

                uploadFile(blob, index, start, end, fname, dir, callback);

                start = end;
                index++;
            }
        });
    });


    //} else {
    //invalid format
    //    console.log("invalid format");
    //}
}

function uploadFile(blob, index, start, end, fname, dir, callback) {
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
                mergeFile(fname, dir, callback);
            }
        }
    };

    chunk = blob.slice(start, end);

    fd = new FormData();
    fd.append("file", chunk);
    fd.append("dir", dir);
    fd.append("name", fname);
    fd.append("index", index);

    xhr.open("POST", "./scripts/writer.php", true);
    xhr.send(fd);
}

//reconstruct slices into original file
function mergeFile(fname, dir, callback) {

    var xhr;

    xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            if (xhr.responseText) {
                console.log(xhr);
            }
            console.log("success");
            callback(fname);
            //if (dir === "uploads"){
            //show the new document            
            //document.location.href = "index.php#documents";
            //location.reload();
            //}
        }
    };
    fd = new FormData();
    fd.append("name", fname);
    fd.append("dir", dir);
    fd.append("index", slices2);

    xhr.open("POST", "./scripts/merge.php", true);
    xhr.send(fd);
}


//update progress bar as file uploads
function updateProgress(slices, totalSlices) {

    

    var percentage = ((totalSlices - slices) / totalSlices) * 100;

    var cssPercentage = percentage + "%";
console.log("updating progress..." + cssPercentage);
    $('#upload-progress .progress-bar').css('width', cssPercentage);
}