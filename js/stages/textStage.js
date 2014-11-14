
//create a text stage
function showTextStage($inputDiv, stage) {   

    if (stage.type == "tags") {
        $helpText = $("<h4>Enter Tags (comma separated): </h4>");
    } else {
        $helpText = $("<h4>Select/Enter Text: </h4>");
    }

    //text area
    $textInput = $("<input type='text' class='form-control'>");
    $textInput.val(stage.value[stage.record]);

    $inputDiv.append($helpText).append($textInput);

    return $textInput;
}
;
