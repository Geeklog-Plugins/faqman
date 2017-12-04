<html>
<head>
<title>{TITLE}</title>
<style sheet="text/css">
<!--
    form { margin: 0 }
    select, option { font-family: Verdana, Geneva, Arial, Helvetica, Sans-Serif; font-size: 8pt }
-->
</style>
<script language="javascript" type="text/javascript">
<!--
function jumpMenu(name)
{
    eval("window.location='" + name.options[name.selectedIndex].value + "'");
}

function open_window(url)
{
    var NEW_WIN = null;

    if(window.screen)
    {
        var LEFT = (screen.width - 600) / 2;
        var TOP = (screen.height - 450) / 2;
    }

    NEW_WIN = window.open("", "printer", "toolbar=yes,width=\"100%\",height=450,directories=no,status=yes,scrollbars=yes,resize=yes,menubar=no,left=\" + LEFT\",top=\" + TOP\"");
    NEW_WIN.document.location.href = url;
}
-->
</script>
</head>
<div align="center">
{CONTENT}
</div>
</body>
</html>