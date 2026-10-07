<?php
echo view('layout/partials/head');
?>

<body>
    <div class="wrapper">




        <?php
        echo $this->renderSection('content');
        ?>





    </div>
    <?php
    echo view('layout/partials/script');
    ?>
</body>

</html>