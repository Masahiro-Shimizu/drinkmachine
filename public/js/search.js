window.addEventListener('DOMContentLoaded', function(){
    /** jQueryの処理 */ 
    $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
   
   ajaxSearch();
   
   $('#search').on('click', function () {
      ajaxSearch();
   });
   
   function ajaxSearch() {
      $("tbody").empty();
      let keyword = $('#keyword').val();
      let product_id = $('#product_id').val();
      let company_id = $('#company_id').val();
      let from_price = $('#from_price').val();
      let to_price = $('#to_price').val();
      let from_stock = $('#from_stock').val();
      let to_stock = $('#to_stock').val();
   
      $.ajax({
         type: 'GET', // HTTPリクエストメソッドの指定
         url: '/search', // 送信先URLの指定
         async: true, // 非同期通信フラグの指定
         dataType: 'json', // 受信するデータタイプの指定
         timeout: 10000, // タイムアウト時間の指定
         data: {
            // サーバーに送信したいデータを指定
            keyword: keyword,
            product_id: product_id,
            company_id: company_id,
            from_price: from_price,
            to_price: to_price,
            from_stock: from_stock,
            to_stock: to_stock
         }
      }).done(function (data) {
         let html = '';
         $.each(data, function (index,value) {
            let id = value.id;
            let product_name = value.product_name;
            let price = value.price;
            let stock = value.stock;
            let company_name = value.company_name;
            if (value.img_path !== "") {
               img_path = '/images/' + value.img_path;
            } else {
               img_path = 'http://localhost:8888/vmachine/public/images/no_image.png';
            }
            html = `
                          <tr class="product_list">
                              <td class="id">${id}</td>
                              <td class="product_name">${product_name}</td>
                              <td class="price">${price}</td>
                              <td class="stock">${stock}</td>
                              <td class="company_name">${company_name}</td>
                              <td class="img_path"><img src="${img_path}"></td>
                              <td class="show"><button class="show_button" type="button" name="show" value="show">商品詳細</button></td>
                              <form method="post" class="delete" action="{{ route('product.delete', $product->id) }}">
                               @csrf
                               @method('delete')
                              <td class="delete"><button class="delete_button" data-id='".$product_id."' id="delete" type="button" name="delete" value="delete">削除</button></td>
                              </form>
                          </tr>
             `;
            $('#products_area').append(html); //できあがったテンプレートを id=products_area の中に追加
         })
         $(function () {
            $('#sort_table').tablesorter();
         });
         $("#sort_table").trigger("update");
      }).fail(function () {
         // 通信が失敗したときの処理
         console.log('エラーです');
         // alert('エラーです');
      })
   }
   
   //商品詳細ページへ
   $(document).on("click", ".show_button", function () {
      let product_id = $(this).closest('tr').children('td:first').text();
      window.location.href = "/vmachine/public/products/" + product_id;
   });
   
   // 商品削除
   $(function () {
      $(document).on("click", ".delete_button", function (event) {
         let id = $(this).closest('tr').children('td:first').text();
         $.ajax({
            type: 'POST',
            url: '/product/delete',
            async: true, // 非同期通信フラグの指定
            dataType: 'json', // 受信するデータタイプの指定
            timeout: 10000, // タイムアウト時間の指定
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
               id: id
            }
         })
            .done(function (data) {
               ajaxSearch();
            })
            .fail(function () {
               // 通信が失敗したときの処理
               console.log('削除できませんでした。');
            })
      });
   });
});   