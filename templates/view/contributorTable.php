<?php if ((isset($contributors)) && (count($contributors) > 0)) {    
    ?>
    <table class="table contributor-table">
        <thead>
            <tr>
                <th>Contributor</th>
                <th>Role</th>
            </tr>                                
        </thead>
        <tbody>
            <?php
            foreach ($contributors as $compoundContributor) {
                $contributor = $compoundContributor->getContributor();
                ?>
                <tr>
                    <td><?php echo $contributor->getName();
                    if ($contributor->orcid){
                        ?><a href="<?php echo $contributor->getOrcidLink() ?>" class="btn btn-primary btn-xs" target="_blank">Orcid</a>
                    <?php } ?>                                                            
                    </td>                
                    <td><?php echo $compoundContributor->role; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php }else{
    ?>
    No contributors recorded.
    
<?php } ?>

