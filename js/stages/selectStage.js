//create a drop down stage
function showSelectStage($inputDiv, stage) {

    $helpText = $("<h4>Select an option: </h4>");
        
    //text area
    $selectInput = $("<select name='dropdown' class='form-control'>");   
    for(var i = 0; i < stage.options.length; i++){        
        var option = stage.options[i];
        $option = $('<option value="' + option + '"></option>');
        $option.append(option);
        $selectInput.append($option);
    }
    
    $selectInput.val(stage.value[stage.record]);

    $inputDiv.append($helpText).append($selectInput);

    return $selectInput;
};