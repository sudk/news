<div id="content">
    <div class="title-box">
        <h1>添加操作员</h1>
    </div>
    <div class="tab-main" id="form-container">
        <?php
        if ($result['message'] != '') {
            echo '<div class="success">' . $result['message'] . '</div>';
        } else if ($result['error'] != '') {
            echo '<div class="error">' . $result['error'] . '</div>';
        }
        $this->renderPartial('_form', array('model' => $model, '_mode_' => 'insert', 'result' => $result));
        ?>
    </div>
</div>