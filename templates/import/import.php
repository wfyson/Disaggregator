<?php include "templates/include/header.php" ?>

<div class="import-overview">

<div class="jumbotron">
    <h2>Import Documents</h2>

    <p>
    Your ORCID can be used to import documents from a number of different sources,
    allowing the construction of a portfolio of research outputs generated from a 
    range of repositories.
    </p>
    
    <p>Your ORCID is: <?php echo $contributor->orcid ?></p>

</div>
    
    <table class="table">
        <tr>
            <td>
                <img src="/img/logos/eprints.png"/>
            </td>
            <td>
                <p class="lead">
                    Import documents from an EPrints repository. Simply insert the
                    URL of your repository (e.g. http://eprints.soton.ac.uk/)
                </p> 
            </td>
            <td>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="EPrints URL">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button">Import!</button>
                    </span>
                </div>
            </td>
        </tr>
    </table>

    
</div>
