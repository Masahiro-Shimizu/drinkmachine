window.addEventListener('DOMContentLoaded', function () {
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
         $.each(data, function (index, value) {
            let id = value.id;
            let product_name = value.product_name;
            let price = value.price;
            let stock = value.stock;
            let company_name = value.company_name;
            if (value.img_path !== "") {
               img_path = '/images/' + value.img_path;
            } else {
               img_path = 'http://localhost:8000/drinkmachine/public/images/no_image.png';
            }
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
         })
      }).fail(function () {
         // 通信が失敗したときの処理
         console.log('エラーです');
         // alert('エラーです');
      })
   }

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
});