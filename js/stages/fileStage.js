//create a file stage
function showFileStage($inputDiv, stage, callback) {
    if (stage.value[stage.record] == "") {
        $helpText = $("<h4>Upload a file: </h4>");
        $inputDiv.append($helpText);
        $input = self.generateFileUploadHtml($inputDiv, callback);
        return $input;
    } else {
        $helpText = $("<h4>File uploaded: </h4>");
        $inputDiv.append($helpText);
        $input = self.generateFileRemoveHtml($inputDiv, stage.value[stage.record]);
        return $input;
    }
}
;

function generateFileUploadHtml($inputDiv, callback) {
    //ask for file upload
    $uploadDiv = $('<div id="file-upload"></div>');
    $uploadLink = $("<a class='btn btn-primary' href='javascript:;'></a>");

    $input = $("<input id='files' type='file'>");
    $input.attr('name', 'temp_source');
    $input.attr('size', '40');
    $input.on('change', function(event) {
        handleFileSelect(event, callback);
    });

    $inputContent = $("<span class='glyphicon glyphicon-plus'></span>");

    $uploadLink.append($input).append($inputContent).append(" Upload File...");
    $uploadDiv.append($uploadLink);

    //also create a div (initially hidden) for showing upload progress
    $uploadProgress = $('<div id="upload-progress"></div>');
    $progressBar = $('<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">');

    $progressBar.append("Uploading...");
    $uploadProgress.append($progressBar);

    $inputDiv.append($uploadDiv).append($uploadProgress);

    return $input;
}
;

function generateFileRemoveHtml($inputDiv, value) {
    //show the uploaded file
    $input = $("<input id='uploaded-file' type='text' readonly>");
    $input.val(value);

    $removeBtn = $("<button class='btn btn-primary'></button>");
    $removeBtn.append("Remove File...");

    $inputDiv.append($input).append($removeBtn);

    return $input;
}
;  