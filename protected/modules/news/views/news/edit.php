<div id="content"  style="width:700px;padding-top: 30px;">
    <div class="tab-main" id="form-container">
        <?php
        if ($result['message'] != '') {
            echo '<div class="success">' . $result['message'] . '</div>';
        } else if ($result['error'] != '') {
            echo '<div class="error">' . $result['error'] . '</div>';
        }
        if ($result['refresh'] == true) {
            echo "\n", '<script>parent.', $this->gridId, '.refresh();</script>', "\n";
            echo "\n<script>";
            echo 'parent.$("#windownbg").remove();';
            echo 'parent.$("#windown-box").remove()';
            echo "</script>\n";
        }
        $this->renderPartial('_form', array('model' => $model, '_mode_' => 'modify', 'msg' => $msg));
        ?>
    </div>
</div>