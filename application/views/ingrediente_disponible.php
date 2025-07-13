<table class="table table-bordered table-hover table-responsive">
    <thead>
        <tr>
            <th>Ingredientes</th>
        </tr>
    </thead>    
    <tbody>                                                        
        <?php 
        foreach ($pro as $p) {
        ?>
          <tr class="adding" id="<?php print $p->id; ?>">
            <td>
              <?php print $p->producto; ?>
            </td>
          </tr>
        <?php 
        }
        ?>
    </tbody>
</table>