/*
 * JS related to building compounds and reactions and breaking down collecting
 * the parameters for them into stages.
 * 
 * A builder object handles both the view of the current stage and displays an 
 * ongoing overview - provided it is given two components of the page to display
 * these things in...
 */
function Builder($stagingArea, $overviewArea){
    
    var self = this;
    self.$stagingArea = $stagingArea;
    self.$overviewArea = $overviewArea;
    self.$input;
    
    /*
     * overview area
     */
    self.updateOverviewArea = function(stages, stageNo){  
        //first empty the overview area
        self.$overviewArea.empty();
                        
        self.$overviewArea.append('<h3>New Compound</h3>');  
        //loop through all the stages
        for(var i = 0; i < stages.length; i++){
            var stage = stages[i];
            $stageOverview = $('<div class="stage-overview"></div>');
            if(i === stageNo){
                $stageOverview.addClass("current-stage");
            }
            
            $stageName = $('<div class="name-overview"></div>');
            $stageName.append(stage.name + ": ");
                                   
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
    
    self.showButtons = function(stage, limit){
                   
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
        if (stage < limit-1){
            $nextBtn = $('<button>Next</button>');
            $nextBtn.addClass('btn btn-default btn-next');
            $btnDiv.append($nextBtn);
        }
        
        //submit button
        if (stage == limit-1){
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
    
    //create a file stage
    self.showFileStage = function(stage, callback){        
        $inputDiv = $("<div class='input'></div>");
        
        //name of thing we're asking about
        $name = $("<h3>" + stage.name + ": </h3>");
        $inputDiv.append($name);        
        
        if(stage.value == ""){
            //ask for file upload
            $uploadLink = $("<a class='btn btn-primary' href='javascript:;'></a>");
        
            $input = $("<input id='file-upload' type='file'>");
            $input.attr('name', 'temp_source');
            $input.attr('size', '40');   
            $input.on('change', function(event) {
                handleFileSelect(event, callback);
            });
            self.$input = $input;
        
            $inputContent = $("<span class='glyphicon glyphicon-plus'></span>");
            $inputContent.append(" UploadFile...");
        
            $uploadLink.append($inputContent);
            $inputDiv.append($input).append($uploadLink); 
        }else{
            //show the uploaded file
            $input = $("<input id='uploaded-file' type='text' readonly>");            
            self.$input = $input;
            self.$input.val(stage.value);
        
            $removeBtn = $("<button class='btn btn-primary'></button>");
            $removeBtn.append("Remove File...");
            
            $inputDiv.append($input).append($removeBtn);
        }
        
        self.$stagingArea.append($inputDiv);
        return self.$input;
    };        
    
    self.updateStage = function(id){
        self.$input.val(self.getData(id));
    };
    
    self.getData = function(id){
        return $('#item-' + id + ' .value').text();        
    };    
}

/*
 * The compoundBuilder works with the extract/compound.php template to allow
 * the user to gather all the parts of a compound together from a document
 * and then submit this to the server where it can be saved
 */
function CompoundBuilder($stagingArea, $overviewArea, docid){
    
    var self = this;    
    self.docid = docid;
    self.stages = [
        {name: "Name", type: "text", value: ""},
        {name: "Description", type: "text", value: ""},
        {name: "MolFile", type: "file", value: ""}];    
    self.stage = 0;            
    self.builder = new Builder($stagingArea, $overviewArea);
    
    self.showStage = function(stageNo){     
        
        //first update the overview area to represent current status        
        self.builder.updateOverviewArea(self.stages, stageNo);
        
        var stage = self.stages[stageNo];
        
        //clear previous stage
        self.builder.clearStagingArea();                    
        
        //show the input area
        switch (stage.type){
            case "text":                
                var $input = self.builder.showTextStage(stage);
                break;
            case "file":
                var $input = self.builder.showFileStage(stage, self.setFile);
                break;
        }
        
        //show previous and next buttons and add their functionality
        buttons = self.builder.showButtons(self.stage, self.stages.length);  
        if (typeof buttons.$prevBtn !== "undefined"){
            buttons.$prevBtn.click(function(){
                self.stages[self.stage].value = $input.val();
                self.stage--;
                self.showStage(self.stage);
            });
        }
        
        if (typeof buttons.$nextBtn !== "undefined"){
            buttons.$nextBtn.click(function(){
                if (self.stage == (self.stages.length - 1)){
                    //we're on the final stage so submit the compound                    
                    var data = {};
                    var missingStages = [];
                    for(var i = 0; i < self.stages.length; i++){                        
                        var stage = self.stages[i];
                        data[stage.name] = stage.value;
                        if (stage.value == ""){
                            //this stage needs an entry                            
                            missingStages.push(i);
                        }
                    }
                                        
                    if (missingStages.length === 0){
                        //we successfully have everything
                        data["docid"] = self.docid;
                        $.post('./scripts/addCompound.php', data, function(){
                            console.log("new compound added to the database!");
                            //should probably redirect to homepage here
                        });
                    }else{
                        console.log("some items are missing!!!!");
                    }
                    
                }else{
                    //move on to the next stage
                    self.stages[self.stage].value = $input.val();
                    self.stage++;
                    self.showStage(self.stage);
                }
            });
        }                
    };
    
    self.setChecked = function(id){
        self.builder.updateStage(id);
    };
    
    self.setFile = function(path){
        self.stages[self.stage].value = path;
        self.showStage(self.stage);
    };
    
    //call a php script that saves this compound in the database
    self.submit = function(){
        //loop through stages, get the value for each and post it off to the server    
    };
}