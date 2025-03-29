/**
 * Main
 */

'use strict';

let menu, animate;

(function () {
  // Initialize menu
  //-----------------

  let layoutMenuEl = document.querySelectorAll('#layout-menu');
  layoutMenuEl.forEach(function (element) {
    menu = new Menu(element, {
      orientation: 'vertical',
      closeChildren: false
    });
    // Change parameter to true if you want scroll animation
    window.Helpers.scrollToActive((animate = false));
    window.Helpers.mainMenu = menu;
  });

  // Initialize menu togglers and bind click on each
  let menuToggler = document.querySelectorAll('.layout-menu-toggle');
  menuToggler.forEach(item => {
    item.addEventListener('click', event => {
      event.preventDefault();
      window.Helpers.toggleCollapsed();
    });
  });

  // Display menu toggle (layout-menu-toggle) on hover with delay
  let delay = function (elem, callback) {
    let timeout = null;
    elem.onmouseenter = function () {
      // Set timeout to be a timer which will invoke callback after 300ms (not for small screen)
      if (!Helpers.isSmallScreen()) {
        timeout = setTimeout(callback, 300);
      } else {
        timeout = setTimeout(callback, 0);
      }
    };

    elem.onmouseleave = function () {
      // Clear any timers set to timeout
      document.querySelector('.layout-menu-toggle').classList.remove('d-block');
      clearTimeout(timeout);
    };
  };
  if (document.getElementById('layout-menu')) {
    delay(document.getElementById('layout-menu'), function () {
      // not for small screen
      if (!Helpers.isSmallScreen()) {
        document.querySelector('.layout-menu-toggle').classList.add('d-block');
      }
    });
  }

  // Display in main menu when menu scrolls
  let menuInnerContainer = document.getElementsByClassName('menu-inner'),
    menuInnerShadow = document.getElementsByClassName('menu-inner-shadow')[0];
  if (menuInnerContainer.length > 0 && menuInnerShadow) {
    menuInnerContainer[0].addEventListener('ps-scroll-y', function () {
      if (this.querySelector('.ps__thumb-y').offsetTop) {
        menuInnerShadow.style.display = 'block';
      } else {
        menuInnerShadow.style.display = 'none';
      }
    });
  }

  // Init helpers & misc
  // --------------------

  // Init BS Tooltip
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Accordion active class
  const accordionActiveFunction = function (e) {
    if (e.type == 'show.bs.collapse' || e.type == 'show.bs.collapse') {
      e.target.closest('.accordion-item').classList.add('active');
    } else {
      e.target.closest('.accordion-item').classList.remove('active');
    }
  };

  const accordionTriggerList = [].slice.call(document.querySelectorAll('.accordion'));
  const accordionList = accordionTriggerList.map(function (accordionTriggerEl) {
    accordionTriggerEl.addEventListener('show.bs.collapse', accordionActiveFunction);
    accordionTriggerEl.addEventListener('hide.bs.collapse', accordionActiveFunction);
  });

  // Auto update layout based on screen size
  window.Helpers.setAutoUpdate(true);

  // Toggle Password Visibility
  window.Helpers.initPasswordToggle();

  // Speech To Text
  window.Helpers.initSpeechToText();

  // Manage menu expanded/collapsed with templateCustomizer & local storage
  //------------------------------------------------------------------

  // If current layout is horizontal OR current window screen is small (overlay menu) than return from here
  if (window.Helpers.isSmallScreen()) {
    return;
  }

  // If current layout is vertical and current window screen is > small

  // Auto update menu collapsed/expanded based on the themeConfig
  window.Helpers.setCollapsed(true, false);
})();


function togglePasswordVisibility(fieldId) {
  const passwordField = document.getElementById(fieldId);
  const icon = passwordField.nextElementSibling.querySelector('i');
  if (passwordField.type === 'password') {
  passwordField.type = 'text';
  icon.classList.remove('bx-low-vision');
  icon.classList.add('bxs-low-vision');
  } else {
  passwordField.type = 'password';
  icon.classList.remove('bxs-low-vision');
  icon.classList.add('bx-low-vision');
  }
}

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


const imageInput = document.getElementById('imageInput');
const preview = document.getElementById('preview');
let files = new DataTransfer();

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


document.getElementById('documentSelect').addEventListener('change', function() {
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
        text: ".txt"
    };
    return formats[docType] || "*";
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

document.querySelectorAll('.delete-image').forEach(button => {
  button.addEventListener('click', function() {
      if (confirm('Are you sure you want to delete this image?')) {
          let imageId = this.getAttribute('data-image-id');
          fetch(`delete_image.php?action=deleteimage&image_id=${imageId}`, {
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


function updateStatus() {
  const status = document.getElementById('statusAction').value;
  if (!status) return;
  
  if (confirm('Are you sure you want to update the status?')) {
      const form = document.createElement('form');
      form.method = 'POST';
      
      const ticketInput = document.createElement('input');
      ticketInput.type = 'hidden';
      ticketInput.name = 'ticket_id';
      ticketInput.value = document.getElementById('ticket_id').value;

      const actionInput = document.createElement('input');
      actionInput.type = 'hidden';
      actionInput.name = 'update-dispute';
      actionInput.value = 'dispute_update';
      
      const statusInput = document.createElement('input');
      statusInput.type = 'hidden';
      statusInput.name = 'status';
      statusInput.value = status;
      
      form.appendChild(ticketInput);
      form.appendChild(actionInput);
      form.appendChild(statusInput);
      document.body.appendChild(form);
      form.submit();
  }
}

function updateWallet() {
  const walletForm = document.getElementById('walletForm');
  if (confirm('Are you sure you want to modify the wallet?')) {
      walletForm.method = 'POST';
      walletForm.submit();
  }
}