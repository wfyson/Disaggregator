       
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

