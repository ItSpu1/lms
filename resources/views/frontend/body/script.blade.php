{{-- /// Start Wish List Add Option // --}}

<script type="text/javascript">

    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        }

    })

    function addToWishList(course_id){
        $.ajax({
            type: "POST",
            dataType:'json',
            url: "/add-to-wishlist/"+course_id,

            success:function(data){
                                // Start Message

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 6000
            })
            if ($.isEmptyObject(data.error)) {

                    Toast.fire({
                    type: 'success',
                    icon: 'success',
                    title: data.success,
                    })

            }else{

           Toast.fire({
                    type: 'error',
                    icon: 'error',
                    title: data.error,
                    })
                }

              // End Message

            }
        })
    }

</script>
{{-- ///end wishlist add option ///  --}}
{{-- ///start load wishlist data ///  --}}
<script type="text/javascript">

    function wishlist(){
        $.ajax({
            type: "GET",
            datatype: 'json',
            url: "/get-wishlist-course/",

            success:function(response){

                $('#wishQty').text(response.wishQty)


                var rows = ""
                $.each(response.wishlist,function(key, value){
                    rows +=`
                    <div class="col-lg-4 responsive-column-half">
            <div class="card card-item">
                <div class="card-image">
                    <a href="course-details.html" class="d-block">
                        <img class="card-img-top" src="/${value.course.course_image}" alt="Card image cap">
                    </a>

                </div><!-- end card-image -->
                <div class="card-body">
                    <h6 class="ribbon ribbon-blue-bg fs-14 mb-3">${value.course.label}</h6>
                    <h5 class="card-title"><a href="/course/details/${value.course.id}/${value.course.course_name_slug}">${value.course.course_name}</a></h5>

                    <div class="d-flex justify-content-between align-items-center">
                    ${value.course.discount_price == null
                        ?`<p class="card-price text-black font-weight-bold">$${value.course.selling_price}</p>`
                        :`<p class="card-price text-black font-weight-bold">$${value.course.discount_price}<span class="before-price font-weight-medium">$${value.course.selling_price}</span></p>`
                        }

                        <div class="icon-element icon-element-sm shadow-sm cursor-pointer" data-toggle="tooltip" data-placement="top" title="Remove from Wishlist" id="${value.id}" onclick="wishlistRemove(this.id)"><i class="la la-heart"></i></div>
                    </div>
                </div>
            </div>
        </div>
            `
                });
            $('#wishlist').html(rows);
            }
        })
    }
    wishlist();

     ///wishlist remove start ///
    function wishlistRemove(id){
        $.ajax({
            type: "GET",
            datatype: "json",
            url: "/wishlist-remove/"+id,

            success:function(data){
                wishlist();
                 // Start Message

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 6000
            })
            if ($.isEmptyObject(data.error)) {

                    Toast.fire({
                    type: 'success',
                    icon: 'success',
                    title: data.success,
                    })

            }else{

           Toast.fire({
                    type: 'error',
                    icon: 'error',
                    title: data.error,
                    })
                }

              // End Message

            }
        })
    }

    ///end wishlist remove ///

</script>
{{-- ///end load wishlist data ///  --}}



{{-- /// End Wish List Add Option // --}}

{{-- /// section 21 added by enas// --}}

{{-- /// Start Add To Cart  // --}}
  <script type="text/javascript">

   function addToCart(courseId, courseName, instructorId, slug){
        // إرسال طلب AJAX لإضافة العنصر إلى سلة التسوق
        $.ajax({
            type: "POST",
            dataType: 'json',
            data: {
                _token: '{{ csrf_token() }}', // قيمة رمز CSRF الممررة كجزء من البيانات
                course_name: courseName, // اسم العنصر
                course_name_slug: slug, // معرف العنصر
                instructor: instructorId // معرف المدرب
            },
            url: "/cart/data/store/"+ courseId, // عنوان URL المستهدف لإضافة العنصر إلى سلة التسوق
            success: function(data) {
                // إعادة الاستجابة بعد نجاح عملية إضافة العنصر إلى سلة التسوق
     miniCart();
                // بداية الرسالة

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });

                if ($.isEmptyObject(data.error)) {
                    // إذا لم يكن هناك خطأ، عرض رسالة نجاح
                    Toast.fire({
                        type: 'success',
                        icon: 'success',
                        title: data.success,
                    });
                } else {
                    // إذا كان هناك خطأ، عرض رسالة خطأ
                    Toast.fire({
                        type: 'error',
                        icon: 'error',
                        title: data.error,
                    });
                }

                // نهاية الرسالة
            }
        });
   }
</script>
{{-- /// End Add to cart Option // --}}
{{-- ///start mini cart --}}
  <script type="text/javascript">

   // دالة لتحديث عربة التسوق المصغرة
function miniCart() {
  $.ajax({
    type: 'GET',
    url: '/course/mini/cart', // عنوان URL لاسترداد بيانات العربة
    dataType: 'json', // تنسيق البيانات المتوقع استلامها
    success: function(response) {
      // عند نجاح الاستجابة من الخادم

      // تحديث إجمالي العربة
      $('span[id="cartSubTotal"]').text(response.cartTotal);

      // تحديث عدد العناصر في العربة
      $('#cartQty').text(response.cartQty);

      var miniCart = "";

      // تكرار عناصر العربة وإنشاء عناصر HTML لعرضها في العربة المصغرة
      $.each(response.carts, function(key, value) {
        miniCart += `<li class="media media-card">
          <a href="shopping-cart.html" class="media-img">
            <img src="/${value.options.image}" alt="Cart image">
          </a>
          <div class="media-body">
            <h5><a href="/course/details/${value.id}/${value.options.slug}">${value.name}</a></h5>
            <span class="d-block fs-14">$${value.price}</span>
            <a type="submit" id="${value.rowId}" onclick="miniCartRemove(this.id)"><i class="la la-times"></i></a>
          </div>
        </li>`;
      });

      // عرض عناصر العربة المصغرة في الصفحة
      $('#miniCart').html(miniCart);
    }
  });
}

// تنفيذ دالة miniCart عند تحميل الصفحة
miniCart();

// دالة لإزالة عنصر من العربة المصغرة
function miniCartRemove(rowId) {
  $.ajax({
    type: 'GET',
    url: '/minicart/course/remove/' + rowId, // عنوان URL لإزالة العنصر المحدد
    dataType: 'json', // تنسيق البيانات المتوقع استلامها
    success: function(data) {
      // عند نجاح الاستجابة من الخادم

      // تحديث عربة التسوق المصغرة بعد إزالة العنصر
      miniCart();

      // عرض رسالة تنبيه باستخدام مكتبة Swal
      const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
      });

      if ($.isEmptyObject(data.error)) {
        // إذا لم يكن هناك أخطاء في الرد من الخادم

        Toast.fire({
          type: 'success',
          icon: 'success',
          title: data.success
        });
      } else {
        // إذا كان هناك خطأ في الرد من الخادم

        Toast.fire({
          type: 'error',
          icon: 'error',
          title: data.error
        });
      }
    }
  });
}
    // End Mini Cart Remove

 </script>
{{-- /// End Mini Cart // --}}

{{-- ///end mini cart --}}
{{-- ///start Mycart --}}
  <script type="text/javascript">
  function cart(){
    $.ajax({
        type:'GET',
        url:'/get-cart-course',
        dataType:'json',
        success:function(response){
            $('span[id="cartSubTotal"]').text(response.cartTotal);
            var rows=""
            $.each(response.carts,function(key,value){
                rows += `
                    <tr>
                    <th scope="row">
                        <div class="media media-card">
                            <a href="course-details.html" class="media-img mr-0">
                                <img src="/${value.options.image}" alt="Cart image">
                            </a>
                        </div>
                    </th>
                    <td>
                        <a href="/course/details/${value.id}/${value.options.slug}" class="text-black font-weight-semi-bold">${value.name}</a>

                    </td>
                    <td>
                        <ul class="generic-list-item font-weight-semi-bold">
                            <li class="text-black lh-18">$${value.price}</li>

                        </ul>
                    </td>

                    <td>
                        <button type="button" class="icon-element icon-element-xs shadow-sm border-0" data-toggle="tooltip" data-placement="top"  id="${value.rowId}" onclick="cartRemove(this.id)">
                            <i class="la la-times"></i>
                        </button>
                    </td>
                </tr>
                `
            });
            $('#cartPage').html(rows);
        }
    })
  }
  cart();

     // My Cart Remove Start
     function cartRemove(rowId){
        $.ajax({
            type: 'GET',
            url: '/cart-remove/'+rowId,
            dataType: 'json',

            success:function(data){
            miniCart();
            cart();
            couponCalculation();
// Start Message

const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 3000
            })
            if ($.isEmptyObject(data.error)) {

                    Toast.fire({
                    type: 'success',
                    icon: 'success',
                    title: data.success,
                    })

            }else{

           Toast.fire({
                    type: 'error',
                    icon: 'error',
                    title: data.error,
                    })
                }

              // End Message


            }
        })
    }

    // End My Cart Remove

</script>
{{-- /// End MyCart // --}}


{{-- /// End MYCart // --}}


