<?php   require_once "includes/header.php";     ?>
<?php   require_once "includes/classes/VideoDetailsFormProvider.php";     ?>

    <div class="column">
        <?php

            $videoDetailsFormProvider = new VidoeDetailsFormProvider($con);
            echo $videoDetailsFormProvider->createUploadForm();

           
        ?>
    </div>

    <script>
        $("form").submit(function(){
            $("#loadingModal").modal("show");
        });
    </script>

    <!-- Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     
      <div class="modal-body">
        Pleace wait. This might take a while.!
        <img src="assets/images/icons/loading-spinner.gif" alt="loading gif">
      </div>
     
    </div>
  </div>
</div>

<?php   require_once "includes/footer.php";  ?>