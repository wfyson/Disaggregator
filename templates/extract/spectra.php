<?php include "pageSetup.php" ?>
<?php include "completeModal.php" ?>

<script>
    window.onload = function(){        
                
        var spectrumData = {};
        
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
        var spectrumStages = [
            {name: "Type", type: "select", value: [""], multi: false, record: 0, options: types},
            {name: "Comment", type: "text", value: [""], multi: false, record: 0},
            {name: "JCAMPFile", type: "file", value: [""], multi: false, record: 0},
            {name: "Image", type: "image", value: [""], multi: false, record: 0}];   
        
        spectrumData['type'] = "Spectra";
        spectrumData['title'] = "Spectrum";
        spectrumData['docid'] = <?php echo $_GET['docid'] ?>;
        spectrumData['compoundid'] = <?php echo $_GET['compoundid'] ?>;
        spectrumData['stages'] = spectrumStages;
        
        var spectrumBuilder = new Builder(spectrumData, $('#extract-panel'), $('#progress'));              
        spectrumBuilder.showStage(0);
        
        //hook up all the checkboxes and table cells to the compound builder here
        $('#extract-content .selector').change(function(){              
            spectrumBuilder.setChecked($(this));
        });
        
         $('#extract-content table td .para').click(function(){            
            spectrumBuilder.setCell($(this));
        });
        
        $('#extract-content table td .image').click(function(){            
            spectrumBuilder.setCell($(this));
        });
        
        
        
    };
</script>

<?php include "templates/include/footer.php" ?>

