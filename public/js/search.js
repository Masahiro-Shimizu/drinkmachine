window.addEventListener('DOMContentLoaded', function(){
    /** jQueryの処理 */ 

$(document).on('click','#searchButton.btn.btn-secondary', function(){
    //alert("hello");
    searchProducts();
});

function searchProducts() {
    // 一旦、すべての行を非表示
    $('#resultTable tbody').empty();

    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: 'post',
        url: '/search',
        data: {
            'product_name': $("#product_name").val(),
            'companyName': $("#company_name").val(),
            'priceMin': $("#price").val(),
            'priceMax': $("#price").val(),
            'stockMin': $("#stock").val(),
            'stockMax': $("#stock").val(),
        },
        dataType: 'json'
    }).done(function (data) {
        //Ajax通信が成功したときの処理
        //alert("hi");
        let tr = '';
        $.each(data, function (_index, value) {
                //dataの中身からvalueを取り出す
                let id = value.id;
                let img_path = "<p>no image</p>";
                if (value.img_path !== '') {
                    img_path = '<img src="storage/' + value.img_path + '">';
                }
                let product_name = value.product_name;
                let price = value.price;
                let stock = value.stock;
                let comment = "";
                if (value.comment !== null) {
                    comment = value.comment;
                }
                let company_name = value.company_name;

                // 検索結果テーブルに表示する行
                tr = `
                <tr>
                    <td>${id}</td>
                    <td>
                        ${img_path}
                    </td>
                    <td id="resultProductName">${product_name}</td>
                    <td id="resultPrice">${price}</td>
                    <td id="resultStock">${stock}</td>
                    <td>${comment}</td>
                    <td id="resultCompanyName">${company_name}</td>
                    <td><a href="{{ route('product.detail', ['id'=>$product->id]) }}" class="btn btn-primary">詳細表示</a></td>
                    <td><input type="button" class="btn btn-danger" id="deleteButton" value="削除"></td>
                </tr>
                `;
                // 検索結果テーブルに行を追加
                $('#resultTable tbody').append(tr);
            });
        console.log('検索にてAjax通信に成功しました。');
    }).fail(function () {
        //Ajax通信が失敗したときの処理
        console.log('検索にてAjax通信に失敗しました。');
    })
}
});