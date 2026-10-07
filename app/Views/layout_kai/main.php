<?php
echo view('layout/partials/head');
?>

<body>
    <div class="wrapper">

        <?php
        echo view('layout/partials/sidebar');
        ?>
        <div class="main-panel">

            <?php
            echo  view('layout/partials/navbar');

            echo $this->renderSection('content');

            echo view('layout/partials/footer');
            ?>
        </div>
        <?php
        echo   view('layout/partials/custom_template');
        ?>



    </div>
    <?php
    echo view('layout/partials/script');
    ?>
</body>

</html>