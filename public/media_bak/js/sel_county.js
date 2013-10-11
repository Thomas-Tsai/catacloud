$(function () {

    // 判斷是否有預設值
    var defaultValue = false;
    if (0 < $.trim($('#fullIdPath').val()).length) {
        $fullIdPath = $('#fullIdPath').val().split(',');
        defaultValue = true;
    }
    
    // 設定預設選項
    if (defaultValue) {
        $('#location').selectOptions($fullIdPath[0]); 
    }
    
    // 開始產生關聯下拉式選單
    $('#location').change(function () {
        // 觸發第二階下拉式選單
        $('#sublocation').removeOption(/.?/).ajaxAddOption(
            '/location/json', 
            { 'location': $(this).val()}, 
            false, 
            function () {
                
                // 設定預設選項
                if (defaultValue) {
                    $(this).selectOptions($fullIdPath[1]).trigger('change');
                } else {
                    $(this).selectOptions().trigger('change');
                }
            }
        ).change(function () {
            // 觸發第三階下拉式選單
            $('#school').removeOption(/.?/).ajaxAddOption(
                '/school/json', 
                { 'location': $(this).val(), 'school': '1' }, 
                false, 
                function () {
                
                    // 設定預設選項
                    if (defaultValue) {
                        $(this).selectOptions($fullIdPath[2]);
                    }
                }
            );
        });
    }).trigger('change');

    // 全部選擇完畢後，顯示所選擇的選項
    $('#school').change(function () {
        //alert('主機：' + $('#location option:selected').text() + 
        //      '／類型：' + $('#sublocation option:selected').text() +
        //      '／遊戲：' + $('#school option:selected').text());
    });
});
