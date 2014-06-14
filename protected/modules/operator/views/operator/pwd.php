<div id="content"  style="width:500px;">
    <div class="tab-main" id="form-container">
        <?php
        if ($result['message'] != '') {
            echo '<div class="success">' . $result['message'] . '</div>';
        } else if ($result['error'] != '') {
            echo '<div class="error">' . $result['error'] . '</div>';
        }
       

        $this->renderPartial('_formpwd', array('model' => $model, '_mode_' => 'modify', 'result' => $result));
        ?>
    </div>
</div>