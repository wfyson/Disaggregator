<?php include "../templates/include/header.php" ?>

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
                <form role="form" action="importDocs.php" method="post">
                    <div class="input-group">                    
                        <input type="text" class="form-control" name="source" placeholder="EPrints URL">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="submit" name="import" value="eprints">Import</button>
                        </span>                    
                    </div>
                </form>
            </td>
        </tr>
        <tr>
            <td>
                <img src="/img/logos/orcid.png"/>
            </td>
            <td>
                <p class="lead">
                    Import documents from your profile on the ORCID website.
                </p> 
            </td>
            <td>
                <button class="btn btn-default" type="button">Import!</button>                                
            </td>
        </tr>
    </table>

    
</div>
