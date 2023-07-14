<<<<<<< HEAD
window.addEventListener('DOMContentLoaded', function () {
   /** jQueryの処理 */
   $.ajaxSetup({
=======
window.addEventListener('DOMContentLoaded', function(){
    /** jQueryの処理 */ 
    $.ajaxSetup({
>>>>>>> 423b1b9139e14132222999eeed2d918752c4ac2f
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
<<<<<<< HEAD

   ajaxSearch();

   $('#search').on('click', function () {
      ajaxSearch();
   });

=======
   
   ajaxSearch();
   
   $('#search').on('click', function () {
      ajaxSearch();
   });
   
>>>>>>> 423b1b9139e14132222999eeed2d918752c4ac2f
   function ajaxSearch() {
      $("tbody").empty();
      let keyword = $('#keyword').val();
      let product_id = $('#product_id').val();
<<<<<<< HEAD
      let company_id = $('#company_id').val();
=======
>>>>>>> 423b1b9139e14132222999eeed2d918752c4ac2f
      let from_price = $('#from_price').val();
      let to_price = $('#to_price').val();
      let from_stock = $('#from_stock').val();
      let to_stock = $('#to_stock').val();
<<<<<<< HEAD

=======
   
>>>>>>> 423b1b9139e14132222999eeed2d918752c4ac2f
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
<<<<<<< HEAD
            company_id: company_id,
=======
>>>>>>> 423b1b9139e14132222999eeed2d918752c4ac2f
            from_price: from_price,
            to_price: to_price,
            from_stock: from_stock,
            to_stock: to_stock
         }
      }).done(function (data) {
         let html = '';
<<<<<<< HEAD
         $.each(data, function (index, value) {
=======
         $.each(data, function (index,value) {
>>>>>>> 423b1b9139e14132222999eeed2d918752c4ac2f
            let id = value.id;
            let product_name = value.product_name;
            let price = value.price;
            let stock = value.stock;
            let company_name = value.company_name;
            if (value.img_path !== "") {
               img_path = '/images/' + value.img_path;
            } else {
<<<<<<< HEAD
               img_path = 'http://localhost:8000/drinkmachine/public/images/no_image.png';
=======
               img_path = 'http://localhost:8888/vmachine/public/images/no_image.png';
>>>>>>> 423b1b9139e14132222999eeed2d918752c4ac2f
            }
            html = `
                          <tr class="product_list">
                              <td class="id">${id}</td>
                              <td class="product_name">${product_name}</td>
                              <td class="price">${price}</td>
                              <td class="stock">${stock}</td>
                              <td class="company_name">${company_name}</td>
                              <td class="img_path"><img src="${img_path}"></td>
<<<<<<< HEAD
                              <td class="show"><button class="btn btn-info" type="button" name="show" value="show">商品詳細</button></td>
                              <form method="post" class="delete" action="{{ route('product.delete', $product->id) }}">
                               @csrf
                               @method('delete')
                              <td class="delete"><button class="btn btn-danger" data-id='".$product_id."' id="delete" type="button" name="delete" value="delete">削除</button></td>
=======
                              <td class="show"><button class="show_button" type="button" name="show" value="show">商品詳細</button></td>
                              <form method="post" class="delete" action="{{ route('product.delete', $product->id) }}">
                               @csrf
                               @method('delete')
                              <td class="delete"><button class="delete_button" data-id='".$product_id."' id="delete" type="button" name="delete" value="delete">削除</button></td>
>>>>>>> 423b1b9139e14132222999eeed2d918752c4ac2f
                              </form>
                          </tr>
             `;
            $('#products_area').append(html); //できあがったテンプレートを id=products_area の中に追加
         })
<<<<<<< HEAD
=======
         $(function () {
            $('#sort_table').tablesorter();
         });
         $("#sort_table").trigger("update");
>>>>>>> 423b1b9139e14132222999eeed2d918752c4ac2f
      }).fail(function () {
         // 通信が失敗したときの処理
         console.log('エラーです');
         // alert('エラーです');
      })
   }
<<<<<<< HEAD

   //商品詳細ページへ
   $(function () {

      $(document).on('click', ".btn-info", function () {
         let product_id = $(this).closest('tr').children('td:first').text();
         window.location.href = "/product/" + product_id;
      });
   });

   // 商品削除
   $(function () {
      $(document).on("click", ".btn-danger", function (event) {
         let id = $(this).closest('tr').children('td:first').text();
         $.ajax({
            type: 'POST',
            url: '/product/delete',
=======
   
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
            url: '/vmachine/public/products/delete',
>>>>>>> 423b1b9139e14132222999eeed2d918752c4ac2f
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
<<<<<<< HEAD

   //ソート機能(id)
   $(function () {

      let clickCount = 0;
      let timer = null
      let timeout = 4000;
      let id = "";

      $('.sort-id').on('click', function () {

         $(this).data('click', ++clickCount);
         let click = $(this).data('click');

         if (click % 2 == 1) {
            id = 1;
         } else {
            id = 2;
         }
         if (clickCount == 1) {
            timer = setTimeout(function () {
               timer = null;
               clickCount = 0;
            }, timeout)

         }


         $("tbody").empty(); //もともとある要素を空にする     

         $.ajax({
            type: 'GET', //HTTP通信の種類
            url: '/list/sort/id', //通信したいURL
            data: {
               'id': id,
            },
            dataType: 'json',
         })

            //通信が成功したとき
            .done(function (data) {
               let html = '';
               $.each(data, function (index, value) { //dataの中身からvalueを取り出す
                  //ここの記述はリファクタ可能
                  let id = value.id;
                  let img_path = value.img_path;
                  let product_name = value.product_name;
                  let price = value.price;
                  let stock = value.stock;
                  let company_name = value.company.company_name;
                  // １ユーザー情報のビューテンプレートを作成
                  html = `
                           <tr class="product_list">
                               <td class="id">${id}</td>
                               <td class="product_name">${product_name}</td>
                               <td class="price">${price}</td>
                               <td class="stock">${stock}</td>
                               <td class="company_name">${company_name}</td>
                               <td class="img_path"><img src="${img_path}"></td>
                               <td class="show"><button class="btn btn-info" type="button" name="show" value="show">商品詳細</button></td>
                               <form method="post" class="delete" action="{{ route('product.delete', $product->id) }}">
                                @csrf
                                @method('delete')
                               <td class="delete"><button class="btn btn-danger" data-id='".$product_id."' id="delete" type="button" name="delete" value="delete">削除</button></td>
                               </form>
                           </tr>
              `;
                  $('#products_area').append(html); //できあがったテンプレートを id=products_area の中に追加
               });

               //削除機能

               $(function () {

                  $('.btn btn-danger').on('click', function () {
                     let deleteConfirm = confirm('削除してよろしいですか？');
                     if (deleteConfirm == true) {
                        let clickDelete = $(this);
                        let userID = clickDelete.attr('data-product_id');

                        $.ajaxSetup({
                           headers: {
                              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                           }
                        });

                        $.ajax({
                           type: 'POST',
                           url: '/product/delete/' + userID,
                           dataType: 'json',
                           data: {
                              'id': userID
                           },

                        })
                           //通信が成功したとき
                           .done(function () {
                              clickDelete.parents('tr').remove();
                           })
                           //通信が失敗したとき
                           .fail(function () {
                              alert("エラー");
                           });
                     } else {
                        (function (e) {
                           e.preventDefault();
                        });
                     };
                  });
               });

               //できあがったテンプレートをビューに追加
            })
            //通信が失敗したとき
            .fail(function () {
               alert("失敗しました");
            })
      });
   });


   //ソート機能(商品名)
   $(function () {

      let clickCount = 0;
      let timer = null
      let timeout = 4000;
      let product_name = "";
      $('.sort-product_name').on('click', function () {

         $(this).data('click', ++clickCount);
         let click = $(this).data('click');
         if (click % 2 == 1) {
            product_name = 5;
         } else {
            product_name = 6;
         }

         if (clickCount == 1) {
            timer = setTimeout(function () {
               timer = null;
               clickCount = 0;
            }, timeout)

         }

         $("tbody").empty(); //もともとある要素を空にする

         $.ajax({
            type: 'GET', //HTTP通信の種類
            url: '/list/sort/product_name', //通信したいURL
            data: {
               'product_name': product_name,
            },
            dataType: 'json',
         })

            //通信が成功したとき
            .done(function (data) {
               let html = '';
               $.each(data, function (index, value) { //dataの中身からvalueを取り出す
                  //ここの記述はリファクタ可能
                  let id = value.id;
                  let img_path = value.img_path;
                  let product_name = value.product_name;
                  let price = value.price;
                  let stock = value.stock;
                  let company_name = value.company.company_name;
                  // １ユーザー情報のビューテンプレートを作成
                  html = `
                           <tr class="product_list">
                               <td class="id">${id}</td>
                               <td class="product_name">${product_name}</td>
                               <td class="price">${price}</td>
                               <td class="stock">${stock}</td>
                               <td class="company_name">${company_name}</td>
                               <td class="img_path"><img src="${img_path}"></td>
                               <td class="show"><button class="btn btn-info" type="button" name="show" value="show">商品詳細</button></td>
                               <form method="post" class="delete" action="{{ route('product.delete', $product->id) }}">
                                @csrf
                                @method('delete')
                               <td class="delete"><button class="btn btn-danger" data-id='".$product_id."' id="delete" type="button" name="delete" value="delete">削除</button></td>
                               </form>
                           </tr>
              `;
                  $('#products_area').append(html); //できあがったテンプレートを id=products_area の中に追加
               });

               //削除機能

               $(function () {

                  $('.btn btn-danger').on('click', function () {
                     let deleteConfirm = confirm('削除してよろしいですか？');
                     if (deleteConfirm == true) {
                        let clickDelete = $(this);
                        let userID = clickDelete.attr('data-product_id');

                        $.ajaxSetup({
                           headers: {
                              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                           }
                        });

                        $.ajax({
                           type: 'POST',
                           url: '/list/delete/' + userID,
                           dataType: 'json',
                           data: {
                              'id': userID
                           },

                        })
                           //通信が成功したとき
                           .done(function () {
                              clickDelete.parents('tr').remove();
                           })
                           //通信が失敗したとき
                           .fail(function () {
                              alert("エラー");
                           });
                     } else {
                        (function (e) {
                           e.preventDefault();
                        });
                     };
                  });
               });

               //できあがったテンプレートをビューに追加
            })
            //通信が失敗したとき
            .fail(function () {
               alert("失敗しました");
            })
      });
   });


   //ソート機能(価格)
   $(function () {

      let clickCount = 0;
      let timer = null
      let timeout = 4000;
      let price = "";
      $('.sort-price').on('click', function () {

         $(this).data('click', ++clickCount);
         let click = $(this).data('click');
         if (click % 2 == 1) {
            price = 7;
         } else {
            price = 8;
         }

         if (clickCount == 1) {
            timer = setTimeout(function () {
               timer = null;
               clickCount = 0;
            }, timeout)

         }

         $("tbody").empty(); //もともとある要素を空にする

         $.ajax({
            type: 'GET', //HTTP通信の種類
            url: '/list/sort/price', //通信したいURL
            data: {
               'price': price,
            },
            dataType: 'json',
         })

            //通信が成功したとき
            .done(function (data) {
               let html = '';
               $.each(data, function (index, value) { //dataの中身からvalueを取り出す
                  //ここの記述はリファクタ可能
                  let id = value.id;
                  let img_path = value.img_path;
                  let product_name = value.product_name;
                  let price = value.price;
                  let stock = value.stock;
                  let company_name = value.company.company_name;
                  // １ユーザー情報のビューテンプレートを作
                  html = `
                   <tr class="product_list">
                       <td class="id">${id}</td>
                       <td class="product_name">${product_name}</td>
                       <td class="price">${price}</td>
                       <td class="stock">${stock}</td>
                       <td class="company_name">${company_name}</td>
                       <td class="img_path"><img src="${img_path}"></td>
                       <td class="show"><button class="btn btn-info " type="button" name="show" value="show">商品詳細</button></td>
                       <form method="post" class="delete" action="{{ route('product.delete', $product->id) }}">
                        @csrf
                        @method('delete')
                       <td class="delete"><button class="btn btn-danger" data-id='".$product_id."' id="delete" type="button" name="delete" value="delete">削除</button></td>
                       </form>
                   </tr>
      `;
                  $('#products_area').append(html); //できあがったテンプレートを id=products_area の中に追加

               });

               //削除機能

               $(function () {

                  $('.btn btn-danger').on('click', function () {
                     let deleteConfirm = confirm('削除してよろしいですか？');
                     if (deleteConfirm == true) {
                        let clickDelete = $(this);
                        let userID = clickDelete.attr('data-product_id');

                        $.ajaxSetup({
                           headers: {
                              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                           }
                        });

                        $.ajax({
                           type: 'POST',
                           url: '/list/delete/' + userID,
                           dataType: 'json',
                           data: {
                              'id': userID
                           },

                        })
                           //通信が成功したとき
                           .done(function () {
                              clickDelete.parents('tr').remove();
                           })
                           //通信が失敗したとき
                           .fail(function () {
                              alert("エラー");
                           });
                     } else {
                        (function (e) {
                           e.preventDefault();
                        });
                     };
                  });
               });

               //できあがったテンプレートをビューに追加
            })
            //通信が失敗したとき
            .fail(function () {
               alert("失敗しました");
            })
      });
   });


   //ソート機能(在庫)
   $(function () {

      let clickCount = 0;
      let timer = null
      let timeout = 4000;
      let stock = "";
      $('.sort-stock').on('click', function () {

         $(this).data('click', ++clickCount);
         let click = $(this).data('click');
         if (click % 2 == 1) {
            stock = 9;
         } else {
            stock = 10;
         }

         if (clickCount == 1) {
            timer = setTimeout(function () {
               timer = null;
               clickCount = 0;
            }, timeout)

         }

         $("tbody").empty(); //もともとある要素を空にする

         $.ajax({
            type: 'GET', //HTTP通信の種類
            url: '/list/sort/stock', //通信したいURL
            data: {
               'stock': stock,
            },
            dataType: 'json',
         })

            //通信が成功したとき
            .done(function (data) {
               let html = '';
               $.each(data, function (index, value) { //dataの中身からvalueを取り出す
                  //ここの記述はリファクタ可能
                  let id = value.id;
                  let img_path = value.img_path;
                  let product_name = value.product_name;
                  let price = value.price;
                  let stock = value.stock;
                  let company_name = value.company.company_name;
                  // １ユーザー情報のビューテンプレートを作成
                  html = `
                           <tr class="product_list">
                               <td class="id">${id}</td>
                               <td class="product_name">${product_name}</td>
                               <td class="price">${price}</td>
                               <td class="stock">${stock}</td>
                               <td class="company_name">${company_name}</td>
                               <td class="img_path"><img src="${img_path}"></td>
                               <td class="show"><button class="btn btn-info" type="button" name="show" value="show">商品詳細</button></td>
                               <form method="post" class="delete" action="{{ route('product.delete', $product->id) }}">
                                @csrf
                                @method('delete')
                               <td class="delete"><button class="btn btn-danger" data-id='".$product_id."' id="delete" type="button" name="delete" value="delete">削除</button></td>
                               </form>
                           </tr>
              `;
                  $('#products_area').append(html); //できあがったテンプレートを id=products_area の中に追加

               });

               //削除機能

               $(function () {

                  $('.btn btn-danger').on('click', function () {
                     let deleteConfirm = confirm('削除してよろしいですか？');
                     if (deleteConfirm == true) {
                        let clickDelete = $(this);
                        let userID = clickDelete.attr('data-product_id');

                        $.ajaxSetup({
                           headers: {
                              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                           }
                        });

                        $.ajax({
                           type: 'POST',
                           url: '/list/delete/' + userID,
                           dataType: 'json',
                           data: {
                              'id': userID
                           },

                        })
                           //通信が成功したとき
                           .done(function () {
                              clickDelete.parents('tr').remove();
                           })
                           //通信が失敗したとき
                           .fail(function () {
                              alert("エラー");
                           });
                     } else {
                        (function (e) {
                           e.preventDefault();
                        });
                     };
                  });
               });

               //できあがったテンプレートをビューに追加
            })
            //通信が失敗したとき
            .fail(function () {
               alert("失敗しました");
            })
      });
   });

   //ソート機能(メーカー名)
   $(function () {

      let clickCount = 0;
      let timer = null
      let timeout = 4000;
      let company_name = "";
      $('.sort-company_name').on('click', function () {

         $(this).data('click', ++clickCount);
         let click = $(this).data('click');
         if (click % 2 == 1) {
            company_name = 11;
         } else {
            company_name = 12;
         }

         if (clickCount == 1) {
            timer = setTimeout(function () {
               timer = null;
               clickCount = 0;
            }, timeout)

         }

         $("tbody").empty(); //もともとある要素を空にする

         $.ajax({
            type: 'GET', //HTTP通信の種類
            url: '/list/sort/company_name', //通信したいURL
            data: {
               'company_name': company_name
            },
            dataType: 'json',
         })

            //通信が成功したとき
            .done(function (data) {
               let html = '';
               $.each(data, function (index, value) { //dataの中身からvalueを取り出す
                  //ここの記述はリファクタ可能
                  let id = value.id;
                  let img_path = value.img_path;
                  let product_name = value.product_name;
                  let price = value.price;
                  let stock = value.stock;
                  let company_name = value.company_name;
                  // １ユーザー情報のビューテンプレートを作成
                  html = `
                           <tr class="product_list">
                               <td class="id">${id}</td>
                               <td class="product_name">${product_name}</td>
                               <td class="price">${price}</td>
                               <td class="stock">${stock}</td>
                               <td class="company_name">${company_name}</td>
                               <td class="img_path"><img src="${img_path}"></td>
                               <td class="show"><button class="btn btn-info" type="button" name="show" value="show">商品詳細</button></td>
                               <form method="post" class="delete" action="{{ route('product.delete', $product->id) }}">
                                @csrf
                                @method('delete')
                               <td class="delete"><button class="btn btn-danger" data-id='".$product_id."' id="delete" type="button" name="delete" value="delete">削除</button></td>
                               </form>
                           </tr>
              `;
                  $('#products_area').append(html); //できあがったテンプレートを id=products_area の中に追加
               });

               //削除機能

               $(function () {

                  $('.btn-delete').on('click', function () {
                     let deleteConfirm = confirm('削除してよろしいですか？');
                     if (deleteConfirm == true) {
                        let clickDelete = $(this);
                        let userID = clickDelete.attr('data-product_id');

                        $.ajaxSetup({
                           headers: {
                              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                           }
                        });

                        $.ajax({
                           type: 'POST',
                           url: '/step8/public/home/delete/' + userID,
                           dataType: 'json',
                           data: {
                              'id': userID
                           },

                        })
                           //通信が成功したとき
                           .done(function () {
                              clickDelete.parents('tr').remove();
                           })
                           //通信が失敗したとき
                           .fail(function () {
                              alert("エラー");
                           });
                     } else {
                        (function (e) {
                           e.preventDefault();
                        });
                     };
                  });
               });
               //できあがったテンプレートをビューに追加
            })
            //通信が失敗したとき
            .fail(function () {
               alert("失敗しました");
            })
      });
   });
});
=======
});   
>>>>>>> 423b1b9139e14132222999eeed2d918752c4ac2f
