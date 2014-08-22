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
    self.type = data.type;
    self.docid = data.docid;
    self.stages = data.stages;
    self.stage = 0;     
    
    
    self.$stagingArea = $stagingArea;
    self.$overviewArea = $overviewArea;
    self.missingStages = [];
    
    /*
     * overview area
     */
    self.updateOverviewArea = function(stageNo){  
        //first empty the overview area
        self.$overviewArea.empty();
                        
        self.$overviewArea.append('<h3>New ' + self.type + '</h3>');  
        //loop through all the stages
        for(var i = 0; i < self.stages.length; i++){
            var stage = self.stages[i];
            $stageOverview = $('<div class="stage-overview"></div>');
            if(i === stageNo){
                $stageOverview.addClass("current-stage");                
            }
            
            if($.inArray(i, self.missingStages) > -1){
                $stageOverview.addClass("missing-stage alert alert-danger");
            }
            
            $stageName = $('<div class="name-overview"></div>');
            $stageName.append("<b>" + stage.name + ": </b>");
            
            if(stage.multi){
                $stageValue = $('<div class="value-overview"></div>');     
                for(var j = 0; j < stage.value.length; j++){       
                    $stageRecord = $('<div class="record-overview"></div>');
                    $stageRecord.append(stage.value[j]);
                    $stageValue.append($stageRecord);
                }
            }else{            
                $stageValue = $('<div class="value-overview"></div>');
                if (stage.value[stage.record] !== ""){
                    $stageValue.append(stage.value[stage.record]);
                }
            }            
            $stageOverview.append($stageName).append($stageValue);    
            self.$overviewArea.append($stageOverview);
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
        $inputDiv = $("<div class='input'></div>");                
        //show the input area
        var value
        switch (stage.type){
            case "text":                
                value = self.showTextStage($inputDiv, stage);
                break;
            case "file":
                //passed a callback for when the file has been uploaded
                value = self.showFileStage($inputDiv, stage, self.setFile); 
                break;
            case "image":
                //passed a callback should a file be uploaded instead of selected
                value = self.showImageStage($inputDiv, stage, self.setFile);
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
               self.stages[stageNo].record = record - 1;
               self.showStage(stageNo);
            });
        }
        
        if (typeof $nextRecordBtn !== "undefined"){
            $nextRecordBtn.click(function(){
               var record = self.stages[stageNo].record;
               self.stages[stageNo].record = record + 1;
               self.showStage(stageNo);
            });
        }
        
        //add functionality to prev/next stage buttons
        if (typeof $prevStageBtn !== "undefined"){
            $prevStageBtn.click(function(){
                var record = self.stages[stageNo].record;
                self.stages[self.stage].value[record] = value;
                self.stage--;
                self.showStage(self.stage);
            });
        }
        
        if (typeof $nextStageBtn !== "undefined"){
            $nextStageBtn.click(function(){
                if (stageNo === (self.stages.length - 1)){
                    //we're on the final stage so submit the record                    
                    var data = {};
                    self.missingStages = [];
                    for(var i = 0; i < self.stages.length; i++){                        
                        var stage = self.stages[i];
                        data[stage.name] = stage.value[stage.record];
                        if (stage.value[stage.record] === ""){
                            //this stage needs an entry                            
                            self.missingStages.push(i);
                        }
                    }
                    //either submit the data or ask for more information                    
                    if (self.missingStages.length === 0){
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
                        }
                        $.post(script, data, function(){
                            console.log("new entry added to the database!");
                            //should probably redirect to homepage here
                        });
                    }else{
                        self.updateOverviewArea(stageNo);
                    }                    
                }else{
                    //set value and move on to the next stage
                    var record = self.stages[stageNo].record;
                    self.stages[stageNo].value[record] = value;
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
        
        $buttonGroup.append($prevBtn).append($nextBtn);
        $multiDiv.append($buttonGroup);
        $div.append($multiDiv);
        
        return {prevRecordBtn: $prevBtn, nextRecordBtn: $nextBtn};
    };
    
    //create a text stage
    self.showTextStage = function($inputDiv, stage){      
       
        $helpText = $("<h4>Select/Enter Text: </h4>");
       
        //text area
        $textInput = $("<input type='text'>");        
        $textInput.val(stage.value[stage.record]);
        
        $inputDiv.append($helpText).append($textInput);                
        
        return $textInput.val(); //this won't reflect changes to the text box
    };
    
    //create an image stage
    self.showImageStage = function($inputDiv, stage, callback){                
        
        if (stage.value[stage.record] == null){            
            $helpText =  $("<h4>Select an image from the document...</h4>");      
            $inputDiv.append($helpText);
            self.$stagingArea.append($inputDiv);
            return null;
        }else{
            $helpText = $("<h4>Image selected: </h4>");
            $linkText = $("<a></a>");
            $linkText.attr('target', '_blank');
            $linkText.attr('href', stage.value[stage.record]);
            $linkText.append(stage.value[stage.record]);
            $linkText.val(stage.value[stage.record]);
            $inputDiv.append($helpText).append($linkText);  
            self.$stagingArea.append($inputDiv);
            return stage.value[stage.record]; 
        }                              
    };
    
    //create a file stage
    self.showFileStage = function($inputDiv, stage, callback){         
        console.log(stage);
        if(stage.value[stage.record] == null){
            $helpText = $("<h4>Upload a file: </h4>");
            $inputDiv.append($helpText);
            $input = self.generateFileUploadHtml($inputDiv, callback);  
            return null;
        }else{
            $helpText = $("<h4>File uploaded: </h4>");
            $inputDiv.append($helpText);
            $input = self.generateFileRemoveHtml($inputDiv, stage.value[stage.record]);
            return $input.val();
        }
                
    };        
    
    self.generateFileUploadHtml = function($inputDiv, callback){
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
    };
    
    self.generateFileRemoveHtml = function($inputDiv, value){
        //show the uploaded file
        $input = $("<input id='uploaded-file' type='text' readonly>");            
        $input.val(value);
        
        $removeBtn = $("<button class='btn btn-primary'></button>");
        $removeBtn.append("Remove File...");
            
        $inputDiv.append($input).append($removeBtn);
        
        return $input;
    };
    
    //set stage value from a checkbox
    self.setChecked = function(id){
        var record = self.stages[self.stage].record;
        self.stages[self.stage].value[record] = self.getData(id);
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
    };   
    
    //set stage value from a file
    self.setFile = function(path){
        var record = self.stages[self.stage].record;
        self.stages[self.stage].value[record] = path;
        self.showStage(self.stage);
    };
}          