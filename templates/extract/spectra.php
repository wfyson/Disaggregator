<?php include "pageSetup.php" ?>
<?php include "completeModal.php" ?>

<script>
    window.onload = function(){        
                
        var spectraData = {};
        
        var types = [
            "2D NMR 1H-13C Direct correlation",
            "2D NMR 1H-13C Long-range correlation",
            "2D NMR 1H-1H COSY",
            "2D NMR 1H-1H Long-range correlation",
            "2D NMR 1H-1H NOESY/ROESY",
            "APCl+ Mass Spectrum",
            "APCl- Mass Spectrum",
            "APPl+ Mass Spectrum",
            "APPl- Mass Spectrum",
            "Chemical Ionization +ve",
            "Chemical Ionization -ve",
            "CNMR",
            "Electron Impact",
            "ESI+ Mass Spectrum",
            "ESI- Mass Spectrum",
            "FNMR",
            "HNMR",
            "Infrared",
            "MALDI+ Mass Spectrum",
            "MALDI- Mass Spectrum",
            "Near Infrared",
            "PNMR",
            "Raman",
            "UV-Vis"
        ];
        
        //each stage's value should be an array to make it easier for the cases where multi is true
        var spectraStages = [
            {name: "Type", type: "select", value: [""], multi: false, record: 0, options: types},
            {name: "Comment", type: "text", value: [""], multi: false, record: 0},
            {name: "JCAMPFile", type: "file", value: [""], multi: false, record: 0},
            {name: "Image", type: "image", value: [""], multi: false, record: 0}];   
        
        spectraData['type'] = "Spectra";
        spectraData['title'] = "Spectrum";
        spectraData['docid'] = <?php echo $_GET['docid'] ?>;
        spectraData['compoundid'] = <?php echo $_GET['compoundid'] ?>;
        spectraData['stages'] = spectraStages;
        
        var spectraBuilder = new Builder(spectraData, $('#extract-panel'), $('#progress'));              
        spectraBuilder.showStage(0);
        
        //hook up all the checkboxes and table cells to the compound builder here
        $('#extract-content .selector').change(function(){              
               spectraBuilder.setChecked($(this));
        });
        
        $('#extract-content table td').click(function(){
            $this = $(this);
            if($this.hasClass("selected")) {
                $this.removeClass("selected");  
            }else{
                $this.addClass("selected");
                spectraBuilder.setCell($(this).data("id"));
            }                        
        });
        
        
        
    };
</script>

<?php include "templates/include/footer.php" ?>

