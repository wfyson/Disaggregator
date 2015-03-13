    
    /*Insert JS via PHP - this code is called on the window.onload event*/

        //hook up all the checkboxes and table cells to the compound builder here
        $('#extract-content .selector').change(function(){              
               builder.setChecked($(this));
        });
        
        $('#extract-content table td .para').click(function(){            
            builder.setCell($(this));
        });
        
        $('#extract-content table td .image').click(function(){            
            builder.setCell($(this));
        });
                
        //contributor functionality
        $('#new-contributor').click(function(){                        
            newContributor(builder);
        });
        
        //image selecting
        $('#extract-content .view-image').click(function(){      
            if(!$(this).hasClass("disabled")){              
                builder.setImage($(this));
            }
        });

        //on select controls
        function getSelectionText() {
            var text = "";
            if (window.getSelection) {
                text = window.getSelection().toString();
            } else if (document.selection && document.selection.type != "Control") {
                text = document.selection.createRange().text;
            }
            return text;
        }
        
        $('#highlight-input').click(function(){
            builder.setSelection($(this));
        });
        
        $('.para .value').mouseup(function(e){
            
            var selection = getSelectionText();
        
            if(selection.length > 0){
                //text has been selected
                $('#highlight-text').empty();
                $('#highlight-text').append(selection);
            
                var x = e.pageX + 10;
                var y = e.pageY - 20;
                $('#highlight-select').css({'top': y, 'left': x}).show();
            }else{
                //no text has been selected
                $('#highlight-select').hide();
            }
        });
        
        //OSCAR functionality       
        var selected = "";
        var count = 0;
        var maxCount = 0;
        var script = "././scripts/oscar.php?docid=" + <?php echo $_GET['docid'] ?>;
        $.post(script, function(data){
            $suggestionContent = $("#suggestions .content");
            $suggestionContent.empty();
            data = JSON.parse(data);            
            var output = data.output;
            for(var s in output) 
            {  
                var $suggestionEntry = $('<p>');
                var $suggestion = $('<span class="label label-default">');                
                $suggestion.append(s);
                    
                var $countIndicator = $('<span class="indicator">');                
                
                $suggestion.click(function(e){
                    $this = $(this);
                                                            
                    //highlight the text
                    var myHilitor = new Hilitor("content");                    
                    maxCount = myHilitor.apply($this.text());     
                    
                    //if we have a new selection, make a note of it                   
                    if($this.text() != selected)
                    {
                        selected = $this.text();   
                        count = 0;                                                
                    }else
                    {
                        //same item again, so just update the current count
                        count++;
                        if(count == maxCount)
                            count = 0;
                    }
                    
                    //show the number of results
                    $('.indicator').empty();
                    $indicator = $this.next();                    
                    $indicator.append((count +1) + " of " + maxCount);
                                        
                    //scroll the content div                                   
                    var offset = ($(".highlight:eq(" + count + ")").offset().top + $('#extract-content').scrollTop())  - $('#extract-content').offset().top;                                        
                    $(".highlight:eq(" + count + ")").addClass("current");
                    $('#extract-content').animate({
                        scrollTop: offset
                    });                                        
                });
                
                $suggestionEntry.append($suggestion).append($countIndicator);
                $suggestionContent.append($suggestionEntry);                                
            }
        });
        
        function scrollToElement(selector, time, verticalOffset) {
	    time = typeof (time) != 'undefined' ? time : 500;
	    verticalOffset = typeof (verticalOffset) != 'undefined' ? verticalOffset : 0;
	    element = $(selector);
	    offset = element.offset();
	    offsetTop = offset.top + verticalOffset;
	    $('html, body').animate({
	        scrollTop: offsetTop
	    }, time);
	}

