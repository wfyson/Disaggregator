       
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

