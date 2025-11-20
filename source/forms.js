$(function() {
    
    // --- Toast Notification Function ---
    function showToast(message, type = 'success') {
        // Remove any existing toasts
        $('.toast-notification').remove();
        
        // Determine icon based on type
        var icon = '';
        var bgColor = '';
        
        if (type === 'success') {
            icon = '<i class="fa-solid fa-circle-check"></i>';
            bgColor = '#28a745';
        } else if (type === 'error') {
            icon = '<i class="fa-solid fa-circle-xmark"></i>';
            bgColor = '#dc3545';
        } else if (type === 'warning') {
            icon = '<i class="fa-solid fa-triangle-exclamation"></i>';
            bgColor = '#ffc107';
        } else if (type === 'info') {
            icon = '<i class="fa-solid fa-circle-info"></i>';
            bgColor = '#17a2b8';
        }
        
        // Create toast element
        var toast = $('<div class="toast-notification" style="background-color: ' + bgColor + ';">' +
                        '<div class="toast-content">' +
                            '<span class="toast-icon">' + icon + '</span>' +
                            '<span class="toast-message">' + message + '</span>' +
                        '</div>' +
                        '<span class="toast-close">&times;</span>' +
                      '</div>');
        
        // Append to body
        $('body').append(toast);
        
        // Trigger animation
        setTimeout(function() {
            toast.addClass('show');
        }, 10);
        
        // Auto-hide after 5 seconds
        var hideTimeout = setTimeout(function() {
            hideToast(toast);
        }, 5000);
        
        // Manual close button
        toast.find('.toast-close').on('click', function() {
            clearTimeout(hideTimeout);
            hideToast(toast);
        });
    }
    
    function hideToast(toast) {
        toast.removeClass('show');
        setTimeout(function() {
            toast.remove();
        }, 300);
    }
    
    // --- File Select Handler ---
    $(document).on('change', ':file', function() {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });

    $(document).ready(function() {
        $(':file').on('fileselect', function(event, numFiles, label) {
            var input = $(this).parents('.input-group').find(':text'),
                log = numFiles > 1 ? numFiles + ' files selected' : label;

            if (input.length) {
                input.val(log);
            } else {
                if (log) console.log(log);
            }
        });
    });

    // --- Form Submission Handler with Toast Notifications ---
    $('form').submit(function(event) {
        event.preventDefault(); 
        
        var $form = $(this);
        var $submitBtn = $form.find('.submit-btn');
        var originalBtnText = $submitBtn.html();
        
        // Disable submit button during submission
        $submitBtn.prop('disabled', true)
                  .html('<i class="fa-solid fa-spinner fa-spin"></i> Submitting...');
        
        // Prepare form data (handle both regular and file upload forms)
        var formData;
        var processData = true;
        var contentType = 'application/x-www-form-urlencoded; charset=UTF-8';
        
        if ($form.attr('enctype') === 'multipart/form-data') {
            formData = new FormData($form[0]);
            processData = false;
            contentType = false;
        } else {
            formData = $form.serialize();
        }
        
        $.ajax({
            type: $form.attr('method'),
            url: $form.attr('action'),
            data: formData,
            processData: processData,
            contentType: contentType,
            
            success: function(response) {
                // Re-enable submit button
                $submitBtn.prop('disabled', false).html(originalBtnText);
                
                // Show success toast
                showToast(response.trim(), 'success');
                
                // Clear form fields on success
                $form[0].reset();
                
                // Close modal after 2 seconds
                setTimeout(function() {
                    var $formCard = $form.closest('.form-card');
                    if ($formCard.length) {
                        $formCard.removeClass('show');
                        $('.modal-overlay').removeClass('show');
                    }
                }, 2000);
            },
            
            error: function(xhr, status, error) {
                // Re-enable submit button
                $submitBtn.prop('disabled', false).html(originalBtnText);
                
                // Parse error message
                var errorMsg = 'An error occurred during submission. Please try again.';
                if (xhr.responseText) {
                    errorMsg = xhr.responseText.trim();
                }
                
                // Show error toast
                showToast(errorMsg, 'error');
            }
        });
    });

    // --- Autocomplete Logic ---
    if (typeof tags !== 'undefined' && $("#tags").length) {
        $("#tags").autocomplete({
            source: tags,
            change: function (event, ui) {
                if (ui.item == null || ui.item == undefined) {
                    $(this).val("");
                    $(this).attr("disabled", false);
                }
            }
        });
    }
});