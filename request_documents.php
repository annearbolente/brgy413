<?php
session_start();
include("connect.php");
include_once 'sidebar.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'user') {
    header("Location: RegLog.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Documents</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="plugins/formstyles.css">
    <link rel="stylesheet" href="plugins/userHomepage.css" />
</head>
<body>
    <div class="home">
        <div class="homepage-wrapper">
            <p class="greeting">Request Documents</p>
            <div class="container mt-5">
                <div class="row g-4">
                    <div class="col-sm-6">
                        <button class="document-btn" data-open-form="#brgyId">Barangay ID <i class="fa-solid fa-circle-plus"></i></button>
                    </div>
                    <div class="col-sm-6">
                        <button class="document-btn" data-open-form="#brgyClearance">Barangay Clearance <i class="fa-solid fa-circle-plus"></i></button>
                    </div>
                    <div class="col-sm-6">
                        <button class="document-btn" data-open-form="#brgyIndigency">Barangay Indigency <i class="fa-solid fa-circle-plus"></i></button>
                    </div>
                    <div class="col-sm-6">
                        <button class="document-btn" data-open-form="#businessClearance">Business Clearance <i class="fa-solid fa-circle-plus"></i></button>
                    </div>

                    <div class="col-sm-6">
                        <button class="document-btn" data-open-form="#ComingSoon">Coming Soon <i class="fa-solid fa-circle-plus"></i></button>
                    </div>

                    <div class="col-sm-6">
                        <button class="document-btn" data-open-form="#ComingSoon">Coming Soon <i class="fa-solid fa-circle-plus"></i></button>
                    </div>

                    <div class="col-sm-6">
                        <button class="document-btn" data-open-form="#ComingSoon">Coming Soon <i class="fa-solid fa-circle-plus"></i></button>
                    </div>

                    <div class="col-sm-6">
                        <button class="document-btn" data-open-form="#ComingSoon">Coming Soon <i class="fa-solid fa-circle-plus"></i></button>
                    </div>

                    <div class="col-sm-6">
                        <button class="document-btn" data-open-form="#ComingSoon">Coming Soon <i class="fa-solid fa-circle-plus"></i></button>
                    </div>

                    <div class="col-sm-6">
                        <button class="document-btn" data-open-form="#ComingSoon">Coming Soon <i class="fa-solid fa-circle-plus"></i></button>
                    </div>

                    <div class="col-sm-6">
                        <button class="document-btn" data-open-form="#ComingSoon">Coming Soon <i class="fa-solid fa-circle-plus"></i></button>
                    </div>

                    <div class="col-sm-6">
                        <button class="document-btn" data-open-form="#ComingSoon">Coming Soon <i class="fa-solid fa-circle-plus"></i></button>
                    </div>
                </div>

                <!-- FIXED: Removed nested form-body wrappers -->
                <div id="brgyId" class="form-card">
                    <?php include("Brgyid.php"); ?>
                </div>

                <div id="brgyClearance" class="form-card">
                    <?php include("BrgyClearance.php"); ?>
                </div>

                <div id="brgyIndigency" class="form-card">
                    <?php include("brgyIndigency.php"); ?>
                </div>

                <div id="businessClearance" class="form-card">
                    <?php include("businessClearance.php"); ?>
                </div>
            </div>

            <div id="ComingSoon" class="form-card">
                <div class="form-header">
                    <h2>Coming Soon</h2>
                    <span class="close-btn">&times;</span>
                </div>
                <div style="padding: 40px 25px; text-align: center;">
                    <i class="fa-solid fa-clock" style="font-size: 64px; color: #ccc; margin-bottom: 20px;"></i>
                    <h3 style="color: #182052; margin-bottom: 15px;">Request Document — Coming Soon</h3>
                    <p style="color: #666;">This document request is not yet available. Check back later.</p>
                </div>
            </div>
        </div>
       
   <!-- Include Footer -->
  <?php include_once 'footer.php'; ?>
    </div>

    <div id="modalOverlay" class="modal-overlay"></div>

    <!-- jQuery (required) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- ALL JavaScript in one place -->
    <script>
    $(function() {
        
        // ========================================
        // TOAST NOTIFICATION FUNCTION
        // ========================================
        function showToast(message, type = 'success') {
            // Smoothly remove any existing toasts
            $('.toast-notification').each(function() {
                var $existing = $(this);
                $existing.removeClass('show');
                setTimeout(function() {
                    $existing.remove();
                }, 300);
            });
            
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
            
            var toast = $('<div class="toast-notification" style="background-color: ' + bgColor + ';">' +
                            '<div class="toast-content">' +
                                '<span class="toast-icon">' + icon + '</span>' +
                                '<span class="toast-message">' + message + '</span>' +
                            '</div>' +
                            '<span class="toast-close">&times;</span>' +
                          '</div>');
            
            $('body').append(toast);
            
            // Smooth slide-in animation
            setTimeout(function() {
                toast.addClass('show');
            }, 50);
            
            // Auto-hide with smooth transition
            var hideTimeout = setTimeout(function() {
                hideToast(toast);
            }, 5000);
            
            // Manual close with smooth transition
            toast.find('.toast-close').on('click', function() {
                clearTimeout(hideTimeout);
                hideToast(toast);
            });
        }
        
        function hideToast(toast) {
            toast.removeClass('show');
            setTimeout(function() {
                toast.remove();
            }, 400);
        }

        // ========================================
        // FORM SUBMISSION HANDLER
        // ========================================
        $('form').submit(function(event) {
            event.preventDefault(); 
            
            var $form = $(this);
            var $submitBtn = $form.find('.submit-btn');
            var originalBtnText = $submitBtn.html();
            
            $submitBtn.prop('disabled', true)
                      .html('<i class="fa-solid fa-spinner fa-spin"></i> Submitting...');
            
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
                    $submitBtn.prop('disabled', false).html(originalBtnText);
                    showToast(response.trim(), 'success');
                    $form[0].reset();
                    
                    setTimeout(function() {
                        var $formCard = $form.closest('.form-card');
                        if ($formCard.length) {
                            $formCard.removeClass('show');
                            $('#modalOverlay').removeClass('show');
                            $('body').css('overflow', 'auto');
                            setTimeout(function() {
                                $formCard.css('display', 'none');
                                $('#modalOverlay').css('display', 'none');
                            }, 300);
                        }
                    }, 2000);
                },
                
                error: function(xhr, status, error) {
                    $submitBtn.prop('disabled', false).html(originalBtnText);
                    
                    var errorMsg = 'An error occurred during submission. Please try again.';
                    if (xhr.responseText) {
                        errorMsg = xhr.responseText.trim();
                    }
                    
                    showToast(errorMsg, 'error');
                }
            });
        });

        // ========================================
        // MODAL OPENING/CLOSING LOGIC
        // ========================================
        function toggleForm(formId) {
            const formToShow = document.querySelector(formId);
            const overlay = document.getElementById('modalOverlay');

            if (!formToShow || !overlay) return;

            const isVisible = formToShow.classList.contains('show');

            document.querySelectorAll('.form-card').forEach(card => {
                card.classList.remove('show');
                card.style.display = 'none';
            });
            overlay.classList.remove('show');
            overlay.style.display = 'none';
            document.body.style.overflow = 'auto';

            if (!isVisible) {
                formToShow.style.display = 'block';
                overlay.style.display = 'block';
                setTimeout(() => {
                    formToShow.classList.add('show');
                    overlay.classList.add('show');
                    document.body.style.overflow = 'hidden';
                }, 10);
            }
        }

        document.querySelectorAll('[data-open-form]').forEach(button => {
            button.addEventListener('click', () => {
                const targetForm = button.getAttribute('data-open-form');
                toggleForm(targetForm);
            });
        });

        document.addEventListener('click', e => {
            if (e.target.classList.contains('close-btn')) {
                const formCard = e.target.closest('.form-card');
                const overlay = document.getElementById('modalOverlay');
                if (formCard && overlay) {
                    formCard.classList.remove('show');
                    overlay.classList.remove('show');
                    document.body.style.overflow = 'auto';
                    setTimeout(() => {
                        formCard.style.display = 'none';
                        overlay.style.display = 'none';
                    }, 300);
                }
            }
        });

        document.getElementById('modalOverlay').addEventListener('click', () => {
            document.querySelectorAll('.form-card').forEach(card => {
                card.classList.remove('show');
                card.style.display = 'none';
            });
            const overlay = document.getElementById('modalOverlay');
            overlay.classList.remove('show');
            overlay.style.display = 'none';
            document.body.style.overflow = 'auto';
        });
    });
    </script>
</body>
</html>