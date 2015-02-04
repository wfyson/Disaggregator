<?php include "pageSetup.php" ?>

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
            {name: "Type", type: "select", value: [""], multi: false, record: 0, options: types, optional: false},
            {name: "Comment", type: "text", value: [""], multi: false, record: 0, optional: false},
            {name: "JCAMPFile", type: "file", value: [""], multi: false, record: 0, optional: false},
            {name: "Image", type: "image", value: [""], multi: false, record: 0, optional: false}];   
        
        spectrumData['type'] = "Spectra";
        spectrumData['title'] = "Spectrum";
        spectrumData['docid'] = <?php echo $_GET['docid'] ?>;
        spectrumData['compoundid'] = <?php echo $_GET['compoundid'] ?>;
        spectrumData['stages'] = spectrumStages;
        
        var builder = new Builder(spectrumData, $('#extract-panel'), $('#progress'));              
        builder.showStage(0);
        
        <?php include "controls.php" ?>
    };
</script>

<?php include "templates/include/footer.php" ?>

