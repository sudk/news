<div id="content">
    <div class="tab-main" id="form-container">
        <?php
        if ($msg['status'] == 1) {
            echo "\n", '<script>parent.', $this->gridId, '.refresh();</script>';
        }
        $this->renderPartial('_form', array('model' => $model, '_mode_' => 'modify', 'msg' => $msg,'attach'=>$attach));
        ?>
    </div>
</div>