//create an image stage
function showImageStage($inputDiv, stage) {
    if (stage.value[stage.record] == "") {
        $helpText = $("<h4>Select an image from the document...</h4>");
        $helpText.val(null);
        $inputDiv.append($helpText);

        self.$stagingArea.append($inputDiv);
        return $helpText;
    } else {
        $helpText = $("<h4>Image selected: </h4>");
        $linkText = $("<a></a>");
        $linkText.attr('target', '_blank');
        $linkText.attr('href', stage.value[stage.record]);
        $linkText.append(stage.value[stage.record]);
        $linkText.val(stage.value[stage.record]);
        $inputDiv.append($helpText).append($linkText);
        self.$stagingArea.append($inputDiv);
        return $linkText;
    }
}
;