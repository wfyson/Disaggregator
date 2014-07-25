/*
 * JS realted to building compounds and reactions and breaking down collecting
 * the parameters for them into stages.
 * 
 * A builder object handles both the view of the current stage and displays an 
 * ongoing overview - provided it is given two components of the page to display
 * these things in...
 */


/*
 * The compoundBuilder works with the extract/compound.php template to allow
 * the user to gather all the parts of a compound together from a document
 * and then submit this to the server where it can be saved
 */
function CompoundBuilder(compound){
    
    var self = this;    
    self.stages = [
        {name: "Name", type: "text"},
        {name: "Description", type: "text"},
        {name: "MolFile", type: "file"}];    
    self.stage = 0;            
    
    
    self.showStage = function(stage){
        console.log(stage);
    };
}