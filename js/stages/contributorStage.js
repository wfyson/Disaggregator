//create a contributor stage
function showContributorStage($inputDiv, stage, builder)
{    
    var role;
        
    //help text
    $helpText = $("<h4>Contributor: </h4>");
    
    //input
    $textInput = $("<input type='text' readonly class='form-control'>");    
    
    $contributorInput = $("<input type='hidden'>");
    
    //roles
    var roles = ["Doer", "Conceiver", "Supervisor"];
    $roleInput = $("<select name='dropdown' class='form-control'>");
    for(var i = 0; i < roles.length; i++){
        $option = $('<option value="' + roles[i] + '"></option>');
        $option.append(roles[i]);
        $roleInput.append($option);
    }
    if (stage.value[stage.record] !== ""){
        var contributorInfo = $.parseJSON(stage.value[stage.record]);    
        $roleInput.val(contributorInfo.role);
    }else{
        $roleInput.val(roles[0]);
    }    
    
    $inputDiv.append($helpText).append($textInput).append($roleInput);
    
    $contributorList = $("<div class='custom-list contributor-list'></div>");    
    var url = "././scripts/getContributorList.php";
    if (stage.value[stage.record] !== ""){
        var contributorInfo = $.parseJSON(stage.value[stage.record]);        
        url = url + "?contributorid=" + contributorInfo.id;
    }
    
    //get a list of available contributors and details on the current contributor
    $.getJSON(url, function(data){    
        //display current contributor  
        if (data.current){
            $textInput.val(data.current);
        }
        
        var contributors = data.contributors;
        for(var i = 0; i < contributors.length; i++){ 
            var contributor = contributors[i];
            var contributorName = contributor.firstName + " " + contributor.familyName;            
            $contributorItem = $("<div class='list-item contributor-item'></div>");            
                       
            $contributorItem.append(contributorName);                
            //select an item in the list
            $contributorItem.click({id: contributor.id, name: contributorName}, function(event){                                      
                                
                var role = $roleInput.val();
                
                $contributorInput.val('{"id": ' + event.data.id + ', "name": "' + event.data.name + '", "role": "' + role + '"}');                
                builder.setCustom('{"id": ' + event.data.id + ', "name": "' + event.data.name + '", "role": "' + role + '"}');
                
                $contributorList.hide();
                
            });            
            $contributorList.append($contributorItem);
        }
        
        $inputDiv.append($contributorList);
    });   
    
    $textInput.click(function(){    
        $contributorList.show();
    });
    
    $roleInput.change(function(){
        if (stage.value[stage.record] !== ""){
            var contributorInfo = $.parseJSON(stage.value[stage.record]);    
            builder.setCustom('{"id": ' + contributorInfo.id + ', "name": "' + contributorInfo.name + '", "role": "' + $roleInput.val() + '"}');
        }
    });
    
    return $contributorInput;
}