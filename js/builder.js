/*
 * JS related to building compounds and reactions and breaking down collecting
 * the parameters for them into stages.
 * 
 * A builder object handles both the view of the current stage and displays an 
 * ongoing overview - provided it is given two components of the page to display
 * these things in...
 */
function Builder(data, $stagingArea, $overviewArea){
    
    var self = this;    
    self.data = data;
    self.type = data.type;
    self.title = data.title;
    self.docid = data.docid;
    self.stages = data.stages;
    self.stage = 0;     
    
    
    self.$stagingArea = $stagingArea;
    self.$overviewArea = $overviewArea;
    self.invalidStages = [];       
    
    self.initialiseInvalidStages = function(){
        for (var i = 0; i < self.stages.length; i++) {
            var stage = self.stages[i];
            self.invalidStages[i] = [];
        }
    };
    self.initialiseInvalidStages();
    
    /*
     * overview area
     */
    self.updateOverviewArea = function(stageNo){  
        //first empty the overview area
        self.$overviewArea.empty();
                        
        self.$overviewArea.append('<h3>New ' + self.title + '</h3>');  
        //loop through all the stages
        for(var i = 0; i < self.stages.length; i++){
            var stage = self.stages[i];
            $stageOverview = $('<div class="stage-overview"></div>');
            if(i === stageNo){
                $stageOverview.addClass("current-stage");                
            }                        
            
            $stageName = $('<div class="name-overview"></div>');
            $stageName.append("<b>" + stage.name + ": </b>");
            
            if(stage.multi){
                $stageValue = $('<div class="value-overview"></div>');     
                for(var j = 0; j < stage.value.length; j++){       
                    self.displayOverviewValue($stageValue, stage, j);
                }
            }else{            
                $stageValue = $('<div class="value-overview"></div>');
                if (stage.value[stage.record] !== ""){
                    self.displayOverviewValue($stageValue, stage, 0);
                }                                
            }            
            
            if (self.invalidStages[i].length > 0) {
                $stageOverview.addClass("invalid-stage alert alert-danger");
            }
            
            $stageOverview.append($stageName).append($stageValue);    
            self.$overviewArea.append($stageOverview);
        }        
    };
    
    self.displayOverviewValue = function($div, stage, record){       
        
        switch (stage.type){
            case "tags":
                var tags = stage.value[record].split(", ");
                for(var i = 0; i < tags.length; i++){
                    $tagDiv = $('<div class="label label-default"></div>');
                    $tagDiv.append(tags[i]);
                    $div.append($tagDiv);
                }
                return $div;
                break;
            case "compound":
                if (stage.value[record] !== ""){
                    var compoundInfo = $.parseJSON(stage.value[record]);
                    $stageRecord = $('<div class="record-overview"></div>');
                    $stageRecord.append(compoundInfo.name);
                    $div.append($stageRecord);
                    return $div;   
                }
                break;
            case "contributor":
                if (stage.value[record] !== ""){
                    var contributorInfo = $.parseJSON(stage.value[record]);
                    $stageRecord = $('<div class="record-overview"></div>');
                    $stageRecord.append(contributorInfo.name + ' - ' + contributorInfo.role);
                    $div.append($stageRecord);
                    return $div;   
                }
                break;
            default:
                $stageRecord = $('<div class="record-overview"></div>');
                $stageRecord.append(stage.value[record]);
                $div.append($stageRecord);
                return $div;   
        }
    }; 
    
    /*
     * staging area
     */    
    self.clearStagingArea = function(){
        self.$stagingArea.empty();
    };
    
    //creates the interface and functionality for a stage
    self.showStage = function(stageNo){ 
        var $prevRecordBtn, $nextRecordBtn, $prevStageBtn, $nextStageBtn; 
        
        //first update the overview area to represent current status        
        self.updateOverviewArea(stageNo);
        
        var stage = self.stages[stageNo];
        //clear previous stage
        self.clearStagingArea();          
        
        //the title area
        $titleDiv = $("<div class='title'></div>");
                
        //first a previous button if necessary
        $leftDiv = $("<div class='title-left'></div>");
        if (stageNo > 0){
            //show the previous stage button
            $prevStageBtn = self.showPrevButton($leftDiv);
        }
        $titleDiv.append($leftDiv);
        
        //show the title
        var stageName = stage.name;
        if (stage.multi){
            stageName = stageName + ' (' + (stage.record + 1) + ')';
        }      
        $name = $("<h3>" + stageName + "</h3>");
        $titleDiv.append($name);
        
        //show a next/submit button
        $rightDiv = $("<div class='title-right'></div>");
        $nextStageBtn = self.showNextButton($rightDiv, stageNo);
        $titleDiv.append($rightDiv);
                
        //stage value area (for inputting and selecting within a stage)   
        $stageControlDiv = $("<div class ='stage-control'></div>");     
        //create an input area        
        $inputDiv = $("<div class='input form-inline'></div>");                
        //show the input area
        var input;
        switch (stage.type){
            case "text":                
                input = showTextStage($inputDiv, stage);
                break;
            case "tags":                
                input = showTextStage($inputDiv, stage);
                break;
            case "select":                
                input = showSelectStage($inputDiv, stage);
                break;
            case "file":
                //passed a callback for when the file has been uploaded
                input = showFileStage($inputDiv, stage, self.setFile); 
                break;
            case "image":
                //passed a callback should a file be uploaded instead of selected
                input = showImageStage($inputDiv, stage);
                break;
            case "compound":
                input = showCompoundStage($inputDiv, stage, self, self.docid);
                break;
            case "contributor":
                input = showContributorStage($inputDiv, stage, self);
                break;
        }
        $stageControlDiv.append($inputDiv);
        
        //multi stage?
        if (stage.multi){
            recordButtons = self.showMultiControls($stageControlDiv, stage);
            $prevRecordBtn = recordButtons.prevRecordBtn;
            $nextRecordBtn = recordButtons.nextRecordBtn;
        }
        
        self.$stagingArea.append($titleDiv).append($stageControlDiv);
        
        //add functionality to prev/next record buttons
        if (typeof $prevRecordBtn !== "undefined"){
            $prevRecordBtn.click(function(){      
                var record = self.stages[stageNo].record;                
                //set the value for this record
                if (input.val() !== "")
                    self.stages[stageNo].value[record] = input.val();
                
                //move on to the previous record
                self.stages[stageNo].record = record - 1;
                self.showStage(stageNo);
            });
        }
        
        if (typeof $nextRecordBtn !== "undefined"){
            $nextRecordBtn.click(function(){
                var record = self.stages[stageNo].record;                
                //set the value for this record
                if (input.val() !== ""){
                    self.stages[stageNo].value[record] = input.val();                                                    
                }
                //move on to the next record and initialise its value
                self.stages[stageNo].record = record + 1;
                if (self.stages[stageNo].value[record + 1] == null){
                    self.stages[stageNo].value[record + 1] = "";
                }
                self.showStage(stageNo);                
            });
        }
        
        //add functionality to prev/next stage buttons
        if (typeof $prevStageBtn !== "undefined"){                                    
            $prevStageBtn.click(function(){
                
                $('#progress-tab').tab('show');
                
                var record = self.stages[stageNo].record;
                if (input.val() !== ""){
                    self.stages[self.stage].value[record] = input.val();
                }
                self.stage--;
                self.showStage(self.stage);
            });
        }
        
        if (typeof $nextStageBtn !== "undefined"){
            $nextStageBtn.click(function(){

                $('#progress-tab').tab('show');
                
                if (stageNo === (self.stages.length - 1)){
                    
                    //first submit the value
                    var record = self.stages[stageNo].record;
                    if (input.val() !== ""){
                        self.stages[stageNo].value[record] = input.val();
                        self.updateOverviewArea(stageNo);
                    }
                    
                    //and now submit the record, but check to see if everything is in place                    
                    var data = {};
                    var invalidData = false;
                    self.invalidStages = [];                    
                    for(var i = 0; i < self.stages.length; i++){                        
                        var stage = self.stages[i];
                        self.invalidStages[i] = [];
                        //loop through each record for the stage
                        for(var j = 0; j < stage.value.length; j++){                            
                            //validate the stage's record (this may need to be more precise in future (e.g. check a picture is a picture, etc.)
                            var value = stage.value[j];
                            
                            if (value === ""){
                                invalidData = true;
                                self.invalidStages[i].push(j);
                            }
                        }                        
                        data[stage.name] = stage.value;                        
                    }
                    //either submit the data or ask for more information   
                    if (!invalidData){
                        //we successfully have everything
                        data["docid"] = self.docid;   
                        //get the relevant php script for adding the record
                        var script = "";
                        switch (self.type){
                            case "Compound":
                                script = "./scripts/addCompound.php";
                                break;
                            case "Reaction":
                                script = "./scripts/addReaction.php";
                                break; 
                            case "Spectra":
                                data["compoundid"] = self.data.compoundid;   
                                script = "./scripts/addSpectra.php";
                                break;  
                        }
                        $.post(script, data, function(data){
                            //now show a modal to indicate success and present options
                            $('#complete-modal-title').append(self.type + " added!");                                                                                  
                            if (self.type == "Compound"){
                                
                                $heading = $('<h4>Add a spectrum for this compound...</h4>');
                                
                                $spectrumLink = $('<a><span class="glyphicon glyphicon-plus"></span> New Spectrum</a>');
                                $spectrumLink.addClass("btn btn-info");
                                $spectrumLink.attr("href", "extract.php?type=spectra&docid=" + self.docid + "&compoundid=" + data); 
                                
                                $('#complete-modal-options').append($heading).append($spectrumLink);
                            }                            
                            $('#complete-modal').modal({
                                keyboard: false
                            });
                            
                        });
                    }else{
                        self.updateOverviewArea(stageNo);
                    }                    
                }else{
                    //set value and move on to the next stage
                    var record = self.stages[stageNo].record;
                    if (input.val() !== ""){
                        self.stages[stageNo].value[record] = input.val();
                    }
                    self.stage++;
                    self.showStage(self.stage);
                }
            });
        }                
    };
    
    self.showPrevButton = function($div){   
        $prevBtn = $('<button>Previous</button>');
        $prevBtn.addClass('btn btn-default btn-prev');
        $div.append($prevBtn);
        
        return $prevBtn;
    };
    
    self.showNextButton = function($div, stageNo){
        //next button
        if (stageNo < self.stages.length-1){
            $nextBtn = $('<button>Next</button>');
            $nextBtn.addClass('btn btn-default btn-next');
            $div.append($nextBtn);
            
            return $nextBtn;
        }else{
            //submit button
            if (stageNo === self.stages.length-1){
                $submitBtn = $('<button>Submit</button>');
                $submitBtn.addClass('btn btn-success btn-next');
                $div.append($submitBtn);
                
                return $submitBtn;
            }            
        }
    };
    
    self.showMultiControls = function($div, stage){
        $multiDiv = $("<div class='multi-stage'></div>");
        $buttonGroup = $("<div class='btn-group'</div>");
        $prevBtn = $("<button type='button' class='btn btn-default'>Prev.</button>");
        $nextBtn = $("<button type='button' class='btn btn-default'>Next</button>");       
        
        if(stage.record == 0){
            $prevBtn.addClass("disabled");
        }
        
        if (stage.value[stage.record] === ""){
            $nextBtn.addClass("disabled");
        }
        
        $buttonGroup.append($prevBtn).append($nextBtn);
        $multiDiv.append($buttonGroup);
        $div.append($multiDiv);
        
        return {prevRecordBtn: $prevBtn, nextRecordBtn: $nextBtn};
    };        
    
    //set stage value from a checkbox
    self.setChecked = function($checkbox){
        var record = self.stages[self.stage].record;
        if($checkbox.is(":checked")){          
            
            //uncheck prevosuly selected box if present
            $(".selector.selected").removeClass("selected").prop('checked', false);
            
            $checkbox.addClass("selected");
            self.stages[self.stage].value[record] = self.getData($checkbox.data("id"));            
        }else{
            $checkbox.removeClass("selected");
            self.stages[self.stage].value[record] = "";            
        }
        self.showStage(self.stage);
    };   
    
    self.setCustom = function(value){
        var record = self.stages[self.stage].record;
        self.stages[self.stage].value[record] = value;
        self.showStage(self.stage);
    };
    
    //set stage value from a table cell
    self.setCell = function(id){
        console.log("get the cell value...");
    };
    
    //gets the actual value associated with an id from the view of the document
    self.getData = function(id){
        if ($('#item-' + id).hasClass("para")){
            return $('#item-' + id + ' .value').text();        
        }                
        if ($('#item-' + id).hasClass("image")){
            return $('#item-' + id + ' img').attr('src');        
        }   
        if ($('#item-' + id).hasClass("heading")){            
            return $('#item-' + id + ' .value').text();        
        }
    };   
    
    //set stage value from a file
    self.setFile = function(path){
        var record = self.stages[self.stage].record;
        self.stages[self.stage].value[record] = path;
        self.showStage(self.stage);
    };
}          