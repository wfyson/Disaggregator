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
    self.$input;
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
                                   
            $stageValue = $('<div class="value-overview"></div>');
            if (stage.value !== ""){
                $stageValue.append(stage["value"]);
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
        
        //first update the overview area to represent current status        
        self.updateOverviewArea(stageNo);
        
        var stage = self.stages[stageNo];
        
        //clear previous stage
        self.clearStagingArea();                    
        
        //show the input area
        switch (stage.type){
            case "text":                
                var $input = self.showTextStage(stage);
                break;
            case "file":
                //passed a callback for when the file has been uploaded
                var $input = self.showFileStage(stage, self.setFile); 
                break;
            case "image":
                //passed a callback should a file be uploaded instead of selected
                var $input = self.showImageStage(stage, self.setFile);
                break;
        }
        
        //show previous and next buttons and add their functionality
        buttons = self.showButtons(stageNo);  
        if (typeof buttons.$prevBtn !== "undefined"){
            buttons.$prevBtn.click(function(){
                self.stages[stageNo].value = $input.val();
                self.stage--;
                self.showStage(self.stage);
            });
        }
        
        if (typeof buttons.$nextBtn !== "undefined"){
            buttons.$nextBtn.click(function(){
                if (stageNo === (self.stages.length - 1)){
                    //we're on the final stage so submit the record                    
                    var data = {};
                    self.missingStages = [];
                    for(var i = 0; i < self.stages.length; i++){                        
                        var stage = self.stages[i];
                        data[stage.name] = stage.value;
                        if (stage.value === ""){
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
                    //move on to the next stage
                    self.stages[stageNo].value = $input.val();
                    self.stage++;
                    self.showStage(self.stage);
                }
            });
        }                
    };
            
    self.showButtons = function(stage){
                   
        var $prevBtn, $nextBtn;
        
        //buttons div 
        $btnDiv = $('<div class="buttons"></div>');
        
        //previous button
        if (stage > 0){
            $prevBtn = $('<button>Previous</button>');
            $prevBtn.addClass('btn btn-default btn-prev');
            $btnDiv.append($prevBtn);
        }
        
        //next button
        if (stage < self.stages.length-1){
            $nextBtn = $('<button>Next</button>');
            $nextBtn.addClass('btn btn-default btn-next');
            $btnDiv.append($nextBtn);
        }
        
        //submit button
        if (stage === self.stages.length-1){
            $nextBtn = $('<button>Submit</button>');
            $nextBtn.addClass('btn btn-success btn-next');
            $btnDiv.append($nextBtn);
        }
        
        self.$stagingArea.append($btnDiv);
        return {$prevBtn: $prevBtn, 
            $nextBtn: $nextBtn};
    };     
    
    //create a text stage
    self.showTextStage = function(stage){        
        $inputDiv = $("<div class='input'></div>");
        
        //name of thing we're asking about
        $name = $("<h3>" + stage.name + ": </h3>");
        
        //text area
        $textInput = $("<input type='text'>");
        self.$input = $textInput;
        
        self.$input.val(stage.value);
        
        //put it all together...
        $inputDiv.append($name).append($textInput);        
        self.$stagingArea.append($inputDiv);     
        
        return self.$input;
    };
    
    //create an image stage
    self.showImageStage = function(stage, callback){
        $inputDiv = $("<div class='input image-input'></div>");
        
        //name of the thing we're asking about
        $name = $("<h3>" + stage.name + ": </h3>");
        $inputDiv.append($name);
        
        if (stage.value == ""){
            //option 1 - select an image
            $selectImage = $("<div id='select-image'></div>");
            $selectHeading = $("<p>Select an image from the document...</p>");
            $selectImage.append($selectHeading);
            $inputDiv.append($selectImage);

            //option 2 - upload an image
            self.generateFileUploadHtml($inputDiv, callback); 
        }else{
            //perhaps show a tumbnail too!            
            //show the remove file option
            self.generateFileRemoveHtml($inputDiv, stage.value);
        }
        
        self.$stagingArea.append($inputDiv);
        return self.$input;        
    };
    
    //create a file stage
    self.showFileStage = function(stage, callback){        
        $inputDiv = $("<div class='input'></div>");
        
        //name of thing we're asking about
        $name = $("<h3>" + stage.name + ": </h3>");
        $inputDiv.append($name);        
        
        if(stage.value == ""){
            self.generateFileUploadHtml($inputDiv, callback);              
        }else{
            self.generateFileRemoveHtml($inputDiv, stage.value);
        }
        
        self.$stagingArea.append($inputDiv);
        return self.$input;
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
        self.$input = $input;

        $inputContent = $("<span class='glyphicon glyphicon-plus'></span>");

        $uploadLink.append($input).append($inputContent).append(" Upload File...");
        $uploadDiv.append($uploadLink);

        //also create a div (initially hidden) for showing upload progress
        $uploadProgress = $('<div id="upload-progress"></div>');
        $progressBar = $('<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">');

        $progressBar.append("Uploading...");
        $uploadProgress.append($progressBar);

        $inputDiv.append($uploadDiv).append($uploadProgress); 
    };
    
    self.generateFileRemoveHtml = function($inputDiv, value){
        //show the uploaded file
        $input = $("<input id='uploaded-file' type='text' readonly>");            
        self.$input = $input;
        self.$input.val(value);
        
        $removeBtn = $("<button class='btn btn-primary'></button>");
        $removeBtn.append("Remove File...");
            
        $inputDiv.append($input).append($removeBtn);
    };
    
    //set stage value from a checkbox
    self.setChecked = function(id){
        self.stages[self.stage].value = self.getData(id);
        self.showStage(self.stage);
    };
    
    //set stage value from a table cell
    self.setCell = function(id){
        console.log("get the cell value...");
    };
    
    //gets the actual value associated with an id from the view of the document
    self.getData = function(id){
        return $('#item-' + id + ' .value').text();        
    };   
    
    //set stage valur from a file
    self.setFile = function(path){
        self.stages[self.stage].value = path;
        self.showStage(self.stage);
    };
}          