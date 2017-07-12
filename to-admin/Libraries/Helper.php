<?php

function ImagePicker($Image = 'Image', $Picture = 'Picture') {
    echo "$('#$Image').click(function() {
    var elf = $('#FileManagerDetails').elfinder({url: '" . APP_PLUGINS . "elfinder/php/connector.php', height: 490, width: 600, docked: false, dialog: {width: 700, modal: true}, getFileCallback: function(file) {
            var a = file.lastIndexOf('Media');
            var b = file.length;
            var img = file.substring(a, b);
            document.getElementById('$Picture').value =
            document.getElementById('$Image').src = img;
            $('#FileManagerDetails').modal('hide');
    }});
    });";
}

function SerializeExplode($string, $delimiter = ',') {
    if ($string) {
        return serialize(explode($delimiter, $string));
    }
    return null;
}

function ImplodeUnSerialize($string, $delimiter = ',') {
    if ($string) {
        return implode(unserialize($string), $delimiter);
    }
    return null;
}

function imgFlag($Lng) {
    return Img(ADM_CURRENT_URL_TEMPLATE . 'img/' . $Lng['Image'], 'class="flagLabel" title="' . $Lng['LanguageName'] . '"');
}


function _autoComplete($ID) {
    $Registry = Registry::GetInstance();
    return '
        <script>
    $(function () {
        var cache = {};
        $("#' . $ID . '").autocomplete({
            minLength: 2,
            source: function (request, response) {
                var term = request.term;
                if (term in cache) {
                    response(cache[ term ]);
                    return;
                }

                $.getJSON("' . ADM_BASE . $Registry->Data['pID'] . '/AutoComplete", request, function (data, status, xhr) {
                    cache[ term ] = data;
                    response(data);
                });
            }
        });
    });
</script>
        ';
}
