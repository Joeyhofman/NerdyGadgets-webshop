<?php

function printConfirmModal(
    string $modalTitle,
    string $text,
    string $modalId,
    string $confirmAction,
    bool $fade = false,
    string $confirmText = "Bevestigen",
    string $closeText = "Sluiten",
    string $submitButtonName = "",
    array $formData = []){
    ?>
    <div class="modal <?php if($fade) print("fade") ?> text-dark" id="<?php print($modalId); ?>" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?php print($modalTitle); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Sluiten">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p><?php print($text);  ?></p>
      </div>
      <div class="modal-footer">
        <form action="<?php print($confirmAction); ?>" method="POST">
            <?php foreach($formData as $key => $value){ ?>
                <input type="hidden" name="<?php print($key) ?>" value="<?php print($value); ?>">    
            <?php } ?>
            <button type="submit" name="<?php print($submitButtonName);?>" class="btn btn-danger"><?php print($confirmText); ?></button>
        </form>
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php print($closeText); ?></button>
      </div>
    </div>
  </div>
</div>
<?php
}

?>