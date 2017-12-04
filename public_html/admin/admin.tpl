<html>
<head>
<title>{TITLE}</title>

<style sheet="text/css">
<!--
	a { text-decoration: none; color: #000000 }
	form { margin: 0 }
	select, option, input, textarea { font-family: Verdana, Geneva, Arial, Helvetica, Sans-Serif; font-size: 8pt }
-->
</style>
<script language="javascript" type="text/javascript">
<!--
function wrap()
{
    // Check that the user is using IE
    var browser = navigator.appName;
    if (browser.indexOf("Microsoft") < 0)
    {
        alert ("Sorry this feature requires Microsoft Internet Explorer");
        return false;
    }

    var form = window.document.templates;

    if (form.fdata.wrap == "off")
    {
        form.fdata.wrap = "soft";
        form.fdata.rows = 25;
        form.wrapping.value = "Disable Wrapping";
    }
    else
    {
        form.fdata.wrap = "off";
        form.fdata.rows = 24;
        form.wrapping.value = "Enable Wrapping";
    }

    return true;
}
-->
</script>
</head>
<body bgcolor="#FFFFFF">
<div align="center">
{CONTENT}
</div>
</body>
</html>