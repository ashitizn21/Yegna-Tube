<?php   require_once "includes/header.php";     ?>
<?php   require_once "includes/classes/VideoDetailsFormProvider.php";     ?>

    <div class="column">
        <?php

            $videoDetailsFormProvider = new VidoeDetailsFormProvider($con);
            echo $videoDetailsFormProvider->createUploadForm();

           
        ?>
    </div>

<?php   require_once "includes/footer.php";  ?>