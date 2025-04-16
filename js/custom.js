(function ($) {
  "use strict";



  $('.popup-youtube, .popup-vimeo').magnificPopup({
    // disableOn: 700,
    type: 'iframe',
    mainClass: 'mfp-fade',
    removalDelay: 160,
    preloader: false,
    fixedContentPos: false
  });

  var review = $('.client_review_slider');
  if (review.length) {
    review.owlCarousel({
      items: 1,
      loop: true,
      dots: true,
      autoplay: true,
      autoplayHoverPause: true,
      autoplayTimeout: 5000,
      nav: true,
      dots: false,
      navText: [" <i class='ti-angle-left'></i> ", "<i class='ti-angle-right'></i> "],
      responsive: {
        0: {
          nav: false
        },
        768: {
          nav: false
        },
        991: {
          nav: true
        }
      }
    });
  }


  var product_slide = $('.product_img_slide');
  if (product_slide.length) {
    product_slide.owlCarousel({
      items: 1,
      loop: true,
      dots: true,
      autoplay: true,
      autoplayHoverPause: true,
      autoplayTimeout: 5000,
      nav: true,
      dots: false,
      navText: [" <i class='ti-angle-left'></i> ", "<i class='ti-angle-right'></i> "],
      responsive: {
        0: {
          nav: false
        },
        768: {
          nav: false
        },
        991: {
          nav: true
        }
      }
    });
  }

  //product list slider
  var product_list_slider = $('.product_list_slider');
  if (product_list_slider.length) {
    product_list_slider.owlCarousel({
      items: 1,
      loop: true,
      dots: false,
      autoplay: true,
      autoplayHoverPause: true,
      autoplayTimeout: 5000,
      nav: true,
      navText: ["next", "previous"],
      smartSpeed: 1000,
      responsive: {
        0: {
          margin: 15,
          nav: false,
          items: 1
        },
        600: {
          margin: 15,
          items: 1,
          nav: false
        },
        768: {
          margin: 30,
          nav: true,
          items: 1
        }
      }
    });
  }

  if ($('.img-gal').length > 0) {
    $('.img-gal').magnificPopup({
      type: 'image',
      gallery: {
        enabled: true
      }
    });
  }

  // niceSelect js code
  $(document).ready(function () {
    $('.select').niceSelect();
  });

  // menu fixed js code
  $(window).scroll(function () {
    var window_top = $(window).scrollTop() + 1;
    if (window_top > 50) {
      $('.main_menu').addClass('menu_fixed animated fadeInDown');
    } else {
      $('.main_menu').removeClass('menu_fixed animated fadeInDown');
    }
  });

  $('.counter').counterUp({
    time: 2000
  });

  $('.slider').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    speed: 300,
    infinite: true,
    asNavFor: '.slider-nav-thumbnails',
    autoplay: true,
    pauseOnFocus: true,
    dots: true,
  });

  $('.slider-nav-thumbnails').slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    asNavFor: '.slider',
    focusOnSelect: true,
    infinite: true,
    prevArrow: false,
    nextArrow: false,
    centerMode: true,
    responsive: [{
      breakpoint: 480,
      settings: {
        centerMode: false,
      }
    }]
  });


  // Search Toggle
  $("#search_input_box").hide();
  $("#search_1").on("click", function () {
    $("#search_input_box").slideToggle();
    $("#search_input").focus();
  });
  $("#close_search").on("click", function () {
    $('#search_input_box').slideUp(500);
  });

  //------- Mailchimp js --------//  
  function mailChimp() {
    $('#mc_embed_signup').find('form').ajaxChimp();
  }
  mailChimp();

  //------- makeTimer js --------//  
  function makeTimer() {

    //		var endTime = new Date("29 April 2018 9:56:00 GMT+01:00");	
    var endTime = new Date("27 Sep 2019 12:56:00 GMT+01:00");
    endTime = (Date.parse(endTime) / 1000);

    var now = new Date();
    now = (Date.parse(now) / 1000);

    var timeLeft = endTime - now;

    var days = Math.floor(timeLeft / 86400);
    var hours = Math.floor((timeLeft - (days * 86400)) / 3600);
    var minutes = Math.floor((timeLeft - (days * 86400) - (hours * 3600)) / 60);
    var seconds = Math.floor((timeLeft - (days * 86400) - (hours * 3600) - (minutes * 60)));

    if (hours < "10") {
      hours = "0" + hours;
    }
    if (minutes < "10") {
      minutes = "0" + minutes;
    }
    if (seconds < "10") {
      seconds = "0" + seconds;
    }

    $("#days").html("<span>Days</span>" + days);
    $("#hours").html("<span>Hours</span>" + hours);
    $("#minutes").html("<span>Minutes</span>" + minutes);
    $("#seconds").html("<span>Seconds</span>" + seconds);

  }
// click counter js
(function() {
 
  window.inputNumber = function(el) {

    var min = el.attr('min') || false;
    var max = el.attr('max') || false;

    var els = {};

    els.dec = el.prev();
    els.inc = el.next();

    el.each(function() {
      init($(this));
    });

    function init(el) {

      els.dec.on('click', decrement);
      els.inc.on('click', increment);

      function decrement() {
        var value = el[0].value;
        value--;
        if(!min || value >= min) {
          el[0].value = value;
        }
      }

      function increment() {
        var value = el[0].value;
        value++;
        if(!max || value <= max) {
          el[0].value = value++;
        }
      }
    }
  }
})();

inputNumber($('.input-number'));



  setInterval(function () {
    makeTimer();
  }, 1000);
 

 $('.select_option_dropdown').hide();
 $(".select_option_list").click(function () {
   $(this).parent(".select_option").children(".select_option_dropdown").slideToggle('100');
   $(this).find(".right").toggleClass("fas fa-caret-down, fas fa-caret-up");
 });

 if ($('.new_arrival_iner').length > 0) {
  var containerEl = document.querySelector('.new_arrival_iner');
  var mixer = mixitup(containerEl);
 }
//  $('.controls').on('click', function(){
//   $('.controls').removeClass('add');
//   $('.controls').addClass('add');
//  });

 $('.controls').on('click', function(){
  $(this).addClass('active').siblings().removeClass('active');
 }); 

function toggleFavorite(userId,courseId) {
  var btn = document.getElementById('favorite-btn-' + courseId);
  var icon = btn.querySelector('i');

  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'toggle_favorite', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.onreadystatechange = function() {
      if (xhr.readyState === 4) {
          if (xhr.status === 200) {
              if (xhr.responseText === 'added') {
                  icon.classList.add('text-primary');
                  showToast('Course added to favorites');
              } else if (xhr.responseText === 'removed') {
                  icon.classList.remove('text-primary');
                  showToast('Course removed from favorites');
              }
          } else {
              console.error('Error: ' + xhr.status);
          }
      }
  };
  xhr.send('course_id=' + courseId + '&user_id=' + userId);
}



function showToast(message) {
  const toastContainer = document.createElement('div');
  toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
  toastContainer.style.zIndex = 11;

  const toast = document.createElement('div');
  toast.id = 'liveToast';
  toast.className = 'toast align-items-center text-white bg-primary border-0';
  toast.role = 'alert';
  toast.ariaLive = 'assertive';
  toast.ariaAtomic = 'true';

  const toastBody = document.createElement('div');
  toastBody.className = 'toast-body';
  toastBody.textContent = message;

  const toastButton = document.createElement('button');
  toastButton.type = 'button';
  toastButton.className = 'btn-close btn-close-white me-2 m-auto';
  toastButton.setAttribute('data-bs-dismiss', 'toast');
  toastButton.ariaLabel = 'Close';

  const toastFlex = document.createElement('div');
  toastFlex.className = 'd-flex';
  toastFlex.appendChild(toastBody);
  toastFlex.appendChild(toastButton);

  toast.appendChild(toastFlex);
  toastContainer.appendChild(toast);
  document.body.appendChild(toastContainer);

  const bootstrapToast = new bootstrap.Toast(toast, { delay: 5000 });
  bootstrapToast.show();
}

function previewProfilePicture(event) {
  var reader = new FileReader();
  reader.onload = function(){
      var output = document.getElementById('profilePicturePreview');
      output.src = reader.result;
  };
  reader.readAsDataURL(event.target.files[0]);
}

document.addEventListener('DOMContentLoaded', function() {
  const select = document.getElementById('subcategory-select');
  const products = document.querySelectorAll('.product');
  const reportCount = document.getElementById('report-count');

  if (!select || !products || !reportCount) {
      return;
  }
  select.addEventListener('change', function() {
      const subcategory = this.value;
      let visibleCount = 0;

      products.forEach(product => {
          if (subcategory === 'all' || subcategory === '' || product.classList.contains(subcategory)) {
              product.style.display = 'block';
              visibleCount++;
          } else {
              product.style.display = 'none';
          }
      });

      reportCount.textContent = `Found ${visibleCount} report(s)`;
  });
});


function updateCartCount(count) {
  const cartCountElement = document.querySelector('.cart-count');
  if (cartCountElement) {
    cartCountElement.textContent = count;
  }
}

$(document).ready(function(){
  $("#addCart").click(function(){
      var fileId = $('input[name="btnradio"]:checked').val();
      var report_id = $('#current_report_id').val();
      var user_id = $('#user_id').val();
      var order_id = $('#order_id').val();
      var affliate_id = $('#affliate_id').val();
      if(!fileId) {
          alert('Please select a file format');
          return;
      }   
      $.ajax({
          url: 'add_to_cart',
          type: 'POST',
          data: {
              reportId: report_id,
              userId: user_id,
              orderId: order_id,
              affliateId: affliate_id,
              file_id: fileId
          },
          success: function(response){
              let data = typeof response === 'string' ? JSON.parse(response) : response;
              if (data.error) {
                  showToast(data.error);
              } else {
                  showToast('Item added to cart successfully');
              }
              if (data.cartCount) {
                  updateCartCount(data.cartCount);
              }
          },
          error: function(){
              showToast('Error adding to cart');
          }
      });
  });
});

$('.delete-cart-item').click(function() {
  var itemId = $(this).data('item-id');
  if(confirm('Are you sure you want to remove this item?')) {
    $.ajax({
      url: 'delete_cart_item',
      type: 'POST',
      data: {item_id: itemId},
      success: function(response) {
        let data = JSON.parse(response);
        if(data.success) {
          $('#cart-item-'+itemId).remove();
          updateCartCount(data.cartCount);
          $('.cart-total').text(data.total);
            if(data.cartCount === 0 || data.cartCount === '0') {
            window.location.reload();
            }
          showToast('Item deleted from cart successfully');
        } else {
          showToast('Error removing item');
        }
      }
    });
  }
});


document.querySelectorAll('.delete-image').forEach(button => {
  button.addEventListener('click', function() {
      if (confirm('Are you sure you want to delete this image?')) {
          let imageId = this.getAttribute('data-image-id');
          fetch(`delete_image?action=deleteimage&image_id=${imageId}`, {
              method: 'GET'
          })
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  this.closest('.image-preview').remove();
                  showToast('Image deleted successfully.');
              } else {
                  alert('Failed to delete image.');
              }
          })
          .catch(error => {
              console.error('Error deleting image:', error);
          });
      }
  });
});



   // If using jQuery.noConflict()
   var $j = jQuery.noConflict();
   $j(document).ready(function() {
     $j('.select-multiple').select2();
   });
 

document.querySelectorAll('a.delete').forEach(link => {
    link.addEventListener('click', function(e) {
        if (!confirm('Are you sure you want to delete this item?')) {
            e.preventDefault();
        }
    });
});


document.querySelectorAll('a.read').forEach(link => {
  link.addEventListener('click', function(e) {
      if (!confirm('Are you sure you want to mark all notifications as read?')) {
          e.preventDefault();
      }
  });
});





const imageInput = document.getElementById('imageInput');
const preview = document.getElementById('preview');
let files = new DataTransfer();

if (imageInput) {
  imageInput.addEventListener('change', function() {
  const newFiles = Array.from(this.files);
  
  newFiles.forEach(file => {
    files.items.add(file);
    
    const reader = new FileReader();
    reader.onload = function(e) {
      const div = document.createElement('div');
      div.className = 'image-preview';
      
      const img = document.createElement('img');
      img.src = e.target.result;
      img.className = 'preview-image';
      
      const deleteBtn = document.createElement('button');
      deleteBtn.className = 'delete-btn';
      deleteBtn.innerHTML = 'X';
      deleteBtn.onclick = function(e) {
        e.preventDefault();
        const index = Array.from(preview.children).indexOf(div);
        const newFiles = new DataTransfer();
        
        Array.from(files.files).forEach((file, i) => {
          if (i !== index) newFiles.items.add(file);
        });
        
        files = newFiles;
        imageInput.files = files.files;
        div.remove();
      };
      
      div.appendChild(img);
      div.appendChild(deleteBtn);
      preview.appendChild(div);
    };
    reader.readAsDataURL(file);
  });
  
  this.files = files.files;
});
}



const documentSelect = document.getElementById('documentSelect');
if (documentSelect) {
    documentSelect.addEventListener('change', function() {
  console.log('Document select changed');
  const selectedOptions = Array.from(this.selectedOptions).map(option => option.value);
  console.log('Selected options:', selectedOptions);
  const pageInputsDiv = document.getElementById('pageInputs');

  // Loop through existing inputs and remove the ones for deselected options
  Array.from(pageInputsDiv.children).forEach(child => {
    const inputName = child.getAttribute('data-doc-type');
    console.log('Checking input:', inputName);
    if (!selectedOptions.includes(inputName)) {
      console.log('Removing input for:', inputName);
      child.remove();
    }
  });

  // Add inputs for newly selected options
  selectedOptions.forEach(docType => {
    console.log('Processing doc type:', docType);
    if (!document.querySelector(`[data-doc-type="${docType}"]`)) {
      console.log('Creating new input group for:', docType);
      const inputGroup = document.createElement('div');
      inputGroup.className = 'input-group mb-3';
      inputGroup.setAttribute('data-doc-type', docType);

      const fileLabel = document.createElement('span');
      fileLabel.className = 'input-group-text';
      fileLabel.textContent = `Upload ${docType}:`;

      const fileInput = document.createElement('input');
      fileInput.type = 'file';
      fileInput.className = 'form-control';
      fileInput.name = `file_${docType}`;
      fileInput.accept = getAcceptedFormats(docType);

      const pageLabel = document.createElement('span');
      pageLabel.className = 'input-group-text';
      pageLabel.textContent = `Pages for ${docType}:`;

      const pageInput = document.createElement('input');
      pageInput.type = 'number';
      pageInput.className = 'form-control';
      pageInput.min = '1';
      pageInput.name = `pages_${docType}`;
      pageInput.required = true;

      inputGroup.appendChild(fileLabel);
      inputGroup.appendChild(fileInput);
      inputGroup.appendChild(pageLabel);
      inputGroup.appendChild(pageInput);
      pageInputsDiv.appendChild(inputGroup);
    }
  });
});
}


function getOrderDetails(orderId) {
  $j.ajax({
    url: 'get_order_details',
    type: 'POST', 
    data: { order_id: orderId },
    success: function(response) {
      $j('#orderDetails').html(response);
    }
  });
}

// Run on page load if order_id exists
$j(document).ready(function() {
  const orderId = $j('#order_id').val();
    getOrderDetails(orderId);
});


//add to wishlist
$(document).ready(function() {
  $('.add-to-wishlist').click(function(e) {
      e.preventDefault();

      var button = $(this);
      var productId = button.data('product-id');
      var userId = $('#user_id').val(); // Get the user ID from the hidden input

      // Redirect if the user is not logged in
      if (!userId) {
          window.location.href = '/signin'; // Replace with your login page URL
          return; // Stop further execution
      }
      $.ajax({
          url: '../addwishlist', // Replace with your server URL
          type: 'POST',
          data: {
              productId: productId,
              user: userId, // Send the user ID with the request
          },
          success: function(response) {
              // Handle response from the server (added or removed)
              if (response.trim() === 'success') {
                  button.addClass('added'); // Change to "added" state (e.g., filled heart)
                  showToast('Item added to wishlist');
              } else if (response.trim() === 'removed') {
                  button.removeClass('added'); // Change to "removed" state (e.g., unfilled heart)
                  showToast('Item removed from wishlist');
              } else if (response.trim() === 'redirect') {
                  window.location.href = '/signin'; // Redirect to login if required
              } else {
                  //alert('Failed to update wishlist: ' + response);
                  showToast('Failed to update wishlist');
              }
          },
          error: function(xhr, status, error) {
              console.log(xhr.responseText);
              alert('An error occurred. Please try again.');
          }
      });
  });
});

$(document).ready(function () {
  $('.addtowishlist').click(function (e) {
      e.preventDefault();

      var button = $(this);
      var productId = button.data('product-id');
      var userId = $('#user_id').val(); // Get the user ID from hidden input

      // Redirect if the user is not logged in
      if (!userId) {
          window.location.href = '/signin'; // Redirect to login page
          return; // Stop further execution
      }

      $.ajax({
          url: '../addwishlist', // Replace with your server URL
          type: 'POST',
          data: {
              productId: productId,
              user: userId, // Send the user ID with the request
          },
          success: function (response) {
              // Handle response from server
              if (response.trim() === 'success') {
                  button.text('Remove from Wishlist') // Change button text
                        .removeClass('btn-outline-secondary') // Remove outline style
                        .addClass('btn-primary'); // Add primary style
                  showToast('Item added to wishlist');
              } else if (response.trim() === 'removed') {
                  button.text('Add to Wishlist') // Change button text back
                        .removeClass('btn-primary') // Remove primary style
                        .addClass('btn-outline-secondary'); // Add outline style
                  showToast('Item removed from wishlist');
              } else if (response.trim() === 'redirect') {
                  window.location.href = '/signin'; // Redirect if required
              } else {
                  showToast('Failed to update wishlist');
              }
          },
          error: function (xhr, status, error) {
              console.log(xhr.responseText);
              alert('An error occurred. Please try again.');
          }
      });
  });
});


//function for read more or see less
$(document).ready(function () {
  $(".description-container").each(function () {
      let fullDesc = $(this).find(".full-description").html(); // Get full content
      let previewLength = 100; // Adjust preview length
      let tempDiv = $("<div>").html(fullDesc); // Preserve HTML
      
      let previewText = tempDiv.text().substring(0, previewLength) + "..."; // Extract text only

      $(this).find(".preview-description").html(previewText); // Set preview
      
      $(this).find(".read-more-btn").click(function () {
          let preview = $(this).siblings(".preview-description");
          let fullContent = $(this).siblings(".full-description");

          if (preview.is(":visible")) {
              preview.hide();
              fullContent.show();
              $(this).text("See Less");
          } else {
              preview.show();
              fullContent.hide();
              $(this).text("Read More");
          }
      });
  });
});

}(jQuery));


function previewProfilePicture(event) {
  var reader = new FileReader();
  reader.onload = function(){
      var output = document.getElementById('profilePicturePreview');
      output.src = reader.result;
  };
  reader.readAsDataURL(event.target.files[0]);
}


function togglePrice() {
  const pricingType = document.getElementById('pricing-type');
  const priceField = document.getElementById('price-field');
  priceField.style.display = pricingType.value === 'paid' ? 'block' : 'none';
}

function togglePasswordVisibility(fieldId) {
  const passwordField = document.getElementById(fieldId);
  const icon = passwordField.nextElementSibling.querySelector('i');
  if (passwordField.type === 'password') {
  passwordField.type = 'text';
  icon.classList.remove('fa-eye');
  icon.classList.add('fa-eye-slash');
  } else {
  passwordField.type = 'password';
  icon.classList.remove('fa-eye-slash');
  icon.classList.add('fa-eye');
  }
}


function handleDocumentSelect(selectElement) {
  console.log('Document select changed');
  const selectedOptions = Array.from(selectElement.selectedOptions).map(option => option.value);
  console.log('Selected options:', selectedOptions);
  const pageInputsDiv = document.getElementById('pageInputs');

  // Loop through existing inputs and remove the ones for deselected options
  Array.from(pageInputsDiv.children).forEach(child => {
      const inputName = child.getAttribute('data-doc-type');
      if (!selectedOptions.includes(inputName)) {
          child.remove();
      }
  });

  // Add inputs for newly selected options
  selectedOptions.forEach(docType => {
      if (!document.querySelector(`[data-doc-type="${docType}"]`)) {
          // Create container div
          const inputContainer = document.createElement('div');
          inputContainer.className = 'mb-3';
          inputContainer.setAttribute('data-doc-type', docType);

          // Create label and file input
          const fileLabel = document.createElement('label');
          fileLabel.className = 'form-label';
          fileLabel.textContent = `Upload ${docType}:`;

          const fileInput = document.createElement('input');
          fileInput.type = 'file';
          fileInput.className = 'form-control';
          fileInput.name = `file_${docType}`;
          fileInput.accept = getAcceptedFormats(docType);

          // Create label and page input
          const pageLabel = document.createElement('label');
          pageLabel.className = 'form-label mt-2';
          pageLabel.textContent = `Number of Pages for ${docType}:`;

          const pageInput = document.createElement('input');
          pageInput.type = 'number';
          pageInput.className = 'form-control';
          pageInput.min = '1';
          pageInput.name = `pages_${docType}`;
          pageInput.required = true;

          // Append elements
          inputContainer.appendChild(fileLabel);
          inputContainer.appendChild(fileInput);
          inputContainer.appendChild(pageLabel);
          inputContainer.appendChild(pageInput);
          pageInputsDiv.appendChild(inputContainer);
      }
  });
}


// Function to return accepted file formats
function getAcceptedFormats(docType) {
    const formats = {
        word: ".doc,.docx",
        excel: ".xls,.xlsx",
        powerpoint: ".ppt,.pptx",
        pdf: ".pdf",
        text: ".txt",
        zip: ".zip,.rar,.tgz,.tar,.gz",
    };
    return formats[docType] || "*";
}

document.querySelectorAll('.deletefile').forEach(button => {
  button.addEventListener('click', function() {
      if (confirm('Are you sure you want to delete this file?')) {
          let imageId = this.getAttribute('data-file-id');
          fetch(`delete_image.php?action=deletefile&image_id=${imageId}`, {
              method: 'GET'
          })
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  this.closest('.file-preview').remove();
                  showToast('File deleted successfully.');
              } else {
                  alert('Failed to delete file.');
              }
          })
          .catch(error => {
              console.error('Error deleting file:', error);
          });
      }
  });
});

function togglePast() {
  const pricingType = document.getElementById('resourceType');
  const priceField = document.getElementById('past-field');

  // Convert selected options to array of values
  const selectedValues = Array.from(pricingType.selectedOptions).map(opt => opt.value);

  // Check if '19' is one of the selected values
  if (selectedValues.includes('19')) {
    priceField.style.display = 'block';
  } else {
    priceField.style.display = 'none';
  }
}
