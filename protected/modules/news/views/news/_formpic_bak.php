    <tr>
    	<td class="maxname" ></td>
    	<td class="mivalue" >
            <div id="queue"></div>
            <input id="file_upload" name="file_upload" type="file" multiple="true">
    	</td>
    </tr>
    <script src="./js/uploadify/jquery.uploadify.min.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="./js/uploadify/uploadify.css">
    <style type="text/css">
        body {
            font: 13px Arial, Helvetica, Sans-serif;
        }
    </style>
    <script type="text/javascript">
        <?php $timestamp = time();?>
        $(function() {
            $('#file_upload').uploadify({
                'formData'     : {
                    'timestamp' : '<?php echo $timestamp;?>',
                    'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
                },
                'buttonText':'上传文件',
                'swf'      : './js/uploadify/uploadify.swf',
                'uploader' : './?r=site/fileupload',
                'onUploadSuccess':function(file, data, response){
                    alert(data);
                }
            });
        });
    </script>