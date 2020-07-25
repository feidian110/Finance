$("#receipt-customer_id").change(function(){
    var opt=$("#receipt-customer_id").val();
    var url = "/merapi/finance/order/index";
    data = {id:opt}
    $.get(url,data,function (res) {
        $("#receipt-order_id").empty();
        var str = '';
        if(res.code ===200 && res.data !== null){
            data = res.data;
            for( var i=0; i < data.length; i++ ){
                str += '<option value="'+data[i].id+'">'+data[i].title+'</option>';
            }
            $("#receipt-order_id").append(str);
        }
    })
});

$("#supplier-inter_id").change(function(){
    var opt=$("#supplier-inter_id").val();
    alert(opt)
});

