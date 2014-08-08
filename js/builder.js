/*
 * JS related to building compounds and reactions and breaking down collecting
 * the parameters for them into stages.
 * 
 * A builder object handles both the view of the current stage and displays an 
 * ongoing overview - provided it is given two components of the page to display
 * these things in...
 */
function Builder(stages, $stagingArea, $overviewArea){
    
    var self = this;
    self.stages = stages;
    self.$stagingArea = $stagingArea;
    self.$overviewArea = $overviewArea;
    self.$input;
    
    /*
     * overview area
     */
    self.updateOverviewArea = function(){
      
        for(var i = 0; i < self.stages.length; i++){
            var stage = self.stages[i];
            $stageOverview = $('<div class="stage-overview"></div>');
            
            $stageName = $('<div class="name-overview"></div>');
            $stageName.append(stage.name);
                                   
            $stageValue = $('<div class="value-overview"></div>');
            if (stage.value !== ""){
                $stageValue.append(self.getData(stage.value));
            }
            
            $stageOverview.append($stageName).append($stageValue);            
        }
        
    };
    
    self.$overviewArea.append('<h3>New Compound</h3>');
    
    
    
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
        if (stage < limit){
            $nextBtn = $('<button>Next</button>');
            $nextBtn.addClass('btn btn-default btn-next');
            $btnDiv.append($nextBtn);
        }
        
        self.$stagingArea.append($btnDiv);
        return {$prevBtn: $prevBtn, 
            $nextBtn: $nextBtn};
    };
    
    self.showNextButton = function(){
        $nextDiv = $('<div class="next"></div>');
        $nextBtn = $('<button>Next</button>');
        $nextBtn.addClass('btn btn-default');
        
        $nextDiv.append($nextBtn);
        self.$stagingArea.append($nextDiv);
        return $nextBtn;
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
function CompoundBuilder($stagingArea, $overviewArea){
    
    var self = this;    
    self.stages = [
        {name: "Name", type: "text", value: ""},
        {name: "Description", type: "text", value: ""},
        {name: "MolFile", type: "file", value: ""}];    
    self.stage = 0;            
    self.builder = new Builder(self.stages, $stagingArea, $overviewArea);
    
    self.showStage = function(stageNo){        
        var stage = self.stages[stageNo];
        
        //clear previous stage
        self.builder.clearStagingArea();                    
        
        //show the input area
        switch (stage.type){
            case "text":                
                var $input = self.builder.showTextStage(stage);
                break;
            case "file":
                var $input = self.builder.showFileStage();
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
                self.stages[self.stage].value = $input.val();
                self.stage++;
                self.showStage(self.stage);
                console.log(self.stages);
            });
        }                
    };
    
    self.setChecked = function(id){
        self.builder.updateStage(id);
    }  
    
    //call a php script that saves this compound in the database
    self.submit = function(){
        //loop through stages, get the value for each and post it off to the server    
    };
}