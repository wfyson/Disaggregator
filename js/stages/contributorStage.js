//create a contributor stage
function showContributorStage($inputDiv, stage, builder)
{
    var role;

    //help text
    $helpText = $("<h4>Contributor: </h4>");

    //input
    $textInput = $("<input id='contributor-text' type='text' readonly class='form-control'>");

    $contributorInput = $("<input id='contributor-input' type='hidden'>");

    //roles
    var roles = ["Doer", "Conceiver", "Supervisor"];
    $roleInput = $("<select id='contributor-role' name='dropdown' class='form-control'>");
    for (var i = 0; i < roles.length; i++) {
        $option = $('<option value="' + roles[i] + '"></option>');
        $option.append(roles[i]);
        $roleInput.append($option);
    }
    if (stage.value[stage.record] !== "") {
        var contributorInfo = $.parseJSON(stage.value[stage.record]);
        $roleInput.val(contributorInfo.role);
    } else {
        $roleInput.val(roles[0]);
    }

    //new contributor button
    $new = $("<button class='btn btn-success'>");
    $new.append("Add New");

    $inputDiv.append($helpText).append($textInput).append($roleInput).append($new);

    $contributorList = $("<div id='contributor-list' class='custom-list contributor-list'></div>");
    $inputDiv.append($contributorList);

    var id = false;
    if (stage.value[stage.record] !== "") {
        var contributorInfo = $.parseJSON(stage.value[stage.record]);
        id = contributorInfo.id;
    }

    updateContributorList(id, $contributorInput, $contributorList, $textInput, $roleInput, builder, false);

    $textInput.click(function() {
        $contributorList.show();
    });

    //add a new contributor button functionality
    $new.click(function() {
        $('#contributor-modal').modal({
            keyboard: false,
            backdrop: 'static'
        });
    });

    $roleInput.change(function() {
        if (stage.value[stage.record] !== "") {
            var contributorInfo = $.parseJSON(stage.value[stage.record]);
            builder.setCustom('{"id": ' + contributorInfo.id + ', "name": "' + contributorInfo.name + '", "role": "' + $roleInput.val() + '"}');
        }
    });

    return $contributorInput;
}

function newContributor(builder) {
    $contributorError = $('#contributor-error');
    $contributorErrorMessage = $('#contributor-error-message');

    $contributorError.hide();
    $contributorErrorMessage.empty();

    var data = {};
    //get first name                        
    data["firstname"] = $('#firstname').val();
    if (data["firstname"] === "") {
        $('#contributor-error').show();
        $contributorErrorMessage.append("Missing first name!");
        return;
    }

    //get family name
    data["familyname"] = $('#familyname').val();
    if (data["familyname"] === "") {
        $('#contributor-error').show();
        $contributorErrorMessage.append("Missing family name!");
        return;
    }

    //get orcid
    data["orcid"] = $('#orcid').val();

    //post the data
    var script = "././scripts/addContributor.php";
    $.post(script, data, function(data) {
        $('#contributor-modal').modal('hide');
        $contributorInput = $('#contributor-input');
        $contributorList = $('#contributor-list');
        $textInput = $('#contributor-text');
        $roleInput = $('#contributor-role');
        updateContributorList(data, $contributorInput, $contributorList, $textInput, $roleInput, builder, true);
    });
}

function updateContributorList(id, $contributorInput, $contributorList, $textInput, $roleInput, builder, newContributor) {

    $contributorList.empty();

    var url = "././scripts/getContributorList.php";

    if (id) {
        url = url + "?contributorid=" + id;
    }

    //get a list of available contributors and details on the current contributor
    $.getJSON(url, function(data) {
        //display current contributor  
        if (data.current) {
            $textInput.val(data.current);
        }
                                   
        displayContributorSection("Previous Collaborators", data.relatedContributors);
        displayContributorSection("Other Contributors", data.otherContributors);    
        
        function displayContributorSection(title, contributors) {
            $subtitle = $("<div class='subtitle'><b>" + title + "</b></div>");
            $contributorList.append($subtitle); 
            
            for (var i = 0; i < contributors.length; i++) {
                var contributor = contributors[i];
                var contributorName = contributor.firstName + " " + contributor.familyName;
                $contributorItem = $("<div class='list-item contributor-item'></div>");

                $contributorItem.append(contributorName);
                //select an item in the list
                $contributorItem.click({id: contributor.id, name: contributorName}, function(event) {

                    var role = $roleInput.val();

                    $contributorInput.val('{"id": ' + event.data.id + ', "name": "' + event.data.name + '", "role": "' + role + '"}');
                    builder.setCustom('{"id": ' + event.data.id + ', "name": "' + event.data.name + '", "role": "' + role + '"}');

                    $contributorList.hide();

                });
                $contributorList.append($contributorItem);

                if ((contributor.id == id) && (newContributor)) {
                    $contributorItem.click();
                }
            }
        }
        
    });
}