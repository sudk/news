<head>
</head>
<body>
    <?php
    if ($result['message'] != '') {
        echo '<div class="success">' . $result['message'] . '</div>';
    } else if ($result['error'] != '') {
        echo '<div class="error">' . $result['error'] . '</div>';
    }
    if ($result['refresh']) {
        echo "\n<script>";
        echo 'parent.$("#windownbg").remove();';
        echo 'parent.$("#windown-box").fadeOut("slow",function(){$(this).remove();});';
        echo "</script>\n";
        echo "\n", '<script>parent.', $this->gridId, '.refresh();</script>', "\n";
    }
    ?>
    <div id="form-container">
    <?php $this->renderPartial('_auth', array('model' => $model, '_mode_' => 'update')); ?>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function (){
            jQuery("body").css('background-image','url()');
        });
    </script>

</body>