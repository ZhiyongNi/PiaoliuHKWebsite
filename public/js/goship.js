// JavaScript Document
var CalculateSum = 0;
function selectPackage() {
    $("[name='RelatedPackageID[]']").attr("readonly", "readonly");
    var PackageArray = "";
    $("[name='RelatedPackageID[]']:checked").each(function () {
        PackageArray += $(this).val() + "|";
    })
    var JsonStr = new Object();
    JsonStr.PackageID = PackageArray;
    $.getJSON("/ajax/goshipfriststep", JSON.parse(JSON.stringify(JsonStr)), function (json) {
        $.each(json, function (k, i) {
            if (k == "Fare") {
                $('#Fare').html(i.join("+"));
            } else if (k == "FareSum") {
                CalculateSum = parseInt(i);
            }
        });
    });
}
function selectMethod() {
    $("[name='TransitBillMethod']").attr("readonly", "readonly");
    var Method = "";
    $("[name='TransitBillMethod']:checked").each(function () {
        Method = $(this).val();
    })
    var JsonStr = new Object();
    JsonStr.Method = Method;
    var Fee = new Array();
    $.getJSON("/ajax/goshipsecondstep", JSON.parse(JSON.stringify(JsonStr)), function (json) {
        $.each(json, function (k, i) {
            Fee = parseInt(i[Method]);
        });
        $('#Fee').html(Fee);
        $("[name='TransitBillPrice']").attr("value", CalculateSum + Fee);//填充内容
    });
}