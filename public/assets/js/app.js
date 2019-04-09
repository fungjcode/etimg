(function($) {
    'use strict';

    $(function() {
        var $fullText = $('.admin-fullText');
        $('#admin-fullscreen').on('click', function() {
            $.AMUI.fullscreen.toggle();
        });

        $(document).on($.AMUI.fullscreen.raw.fullscreenchange, function() {
            $fullText.text($.AMUI.fullscreen.isFullscreen ? '退出全屏' : '开启全屏');
        });
    });
})(jQuery);

$(function() {
    $('#doc-form-file').on('change', function() {
        var fileNames = '';
        var Names = '<button type="submit" class="am-btn am-btn-success am-radius" onclick="load();return true;">' + '确认上传' + '</button> ';
        $.each(this.files, function() {
            fileNames += '<span class="am-badge am-badge-secondary am-radius am-text-default">' + this.name + '</span> ';
        });
        $('#file-list').html(fileNames);
        $('#list').html(Names);
    });
});

function load() {
    document.getElementById("file-list").innerHTML = '<div class="spinner"></div>';
    //document.write('<div class="spinner"></div>');
}
/*
$('#load').ready(function() {
    var html = '<div class="spinner"></div>';
    $('#load').html(html);
});*/