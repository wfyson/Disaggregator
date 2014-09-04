
//create a compound stage
function showCompoundStage($inputDiv, stage, docid)
{
    //help text
     $helpText = $("<h4>Select a Compound: </h4>");
    
    //text area
    $textInput = $("<input type='text' readonly>");    
    
    $compoundInput = $("<input type='hidden'>")

    $inputDiv.append($helpText).append($textInput);
    $compoundList = $("<div class='compound-list'></div>");

    var url = "././scripts/getCompounds.php?docid=" + docid;
    if (stage.value[stage.record] !== ""){
        var compoundInfo = $.parseJSON(stage.value[stage.record]);
        url = url + "&compoundid=" + compoundInfo.id;
    }
    
    //get a list of available compounds and details on the current compound

    $.getJSON(url, function(data){    
        //display current compound
        if (data.current){
            $textInput.val(data.current);
        }
        
        //reference compounds        
        $subtitle = $("<div class='subtitle'><b>Document Compounds</b></div>");
        $compoundList.append($subtitle);
        var refCompounds = data.refCompounds.results;
        for(var i = 0; i < refCompounds.length; i++){            
            var compound = refCompounds[i];
            $compoundItem = $("<div class='compound-item'></div>");            
            $compoundItem.append(compound.name);            
            $compoundItem.click({id: compound.id, name: compound.name}, function(event){                             
                $compoundInput.val('{"id": ' + event.data.id + ', "name": "' + event.data.name + '"}');
                $textInput.val(event.data.name);
                $compoundList.hide();
            });            
            $compoundList.append($compoundItem);
        }                      
        $inputDiv.append($compoundList);
    });   
    
    $textInput.click(function(){     
        console.log("clicked!!!");
        $compoundList.show();
    });
    
    return $compoundInput;
}
