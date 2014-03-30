<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title></title>
    <script>
        var wpurp = window.opener.wpurp;

        document.title = wpurp.print_template.title;

        for(var font in wpurp.print_template.fonts) {
            var link = wpurp.print_template.fonts[font];
            document.write('<link rel="stylesheet" type="text/css" href="'+link+'">');
        }

        document.write('<link rel="stylesheet" type="text/css" href="'+wpurp.pluginUrl+'/css/print.css">');
        document.write('' +
            '<style>' +
            '.print-header { font-family: '+ wpurp.print_template.header.font +'; font-style: '+ wpurp.print_template.header.style +'; font-weight: '+ wpurp.print_template.header.weight +'; font-size: '+ wpurp.print_template.header.size +'px; } ' +
            '.wpurp-container { font-family: '+ wpurp.print_template.recipe.font +'; font-style: '+ wpurp.print_template.recipe.style +'; font-weight: '+ wpurp.print_template.recipe.weight +'; font-size: '+ wpurp.print_template.recipe.size +'px; } ' +
            '.print-footer { font-family: '+ wpurp.print_template.recipe.font +'; font-size: '+ wpurp.print_template.recipe.size +'px; } ' +
            wpurp.custom_print_css +
            '</style>');
    </script>
</head>
<body onload="setTimeout(function(){window.print()}, 500);">
<script>
    if(wpurp.print_template.logo !== '') {
        document.write('<img class="print-logo" src="'+wpurp.print_template.logo+'" />');
    }
</script>
<script>
    if(wpurp.print_template.header.text !== '') {
        document.write('<div class="print-header">');
        document.write(wpurp.print_template.header.text);
        document.write('</div>');
    }
</script>

<div class="wpurp-container">
    <script>
        document.write(window.opener.parentRecipe);
    </script>
</div>

<script>
    if(wpurp.print_template.footer !== '') {
        document.write('<div class="print-footer">');
        document.write(wpurp.print_template.footer);
        document.write('</div>');
    }
</script>
</body>
</html>