/**
 * JavaScript xử lý tương tác phía Client - TZY Store
 * Kiến thức áp dụng: DOM Manipulation, Event Handling, Client Validation
 */

// 1. Hàm tăng giảm số lượng ở trang chi tiết sản phẩm (detail.php)
function changeQty(amount) {
    var display = document.getElementById('quantity_display');
    var hidden = document.getElementById('quantity_hidden');
    if (display && hidden) {
        var current = parseInt(display.value) || 1;
        var next = current + amount;
        if (next >= 1 && next <= 99) {
            display.value = next;
            hidden.value = next;
        }
    }
}

// Lắng nghe các sự kiện khi DOM đã sẵn sàng
document.addEventListener('DOMContentLoaded', function() {
    
    // 2. Xử lý form nhận tin bản tin (Newsletter) ở trang chủ
    var homeNewsletterForm = document.getElementById('homeNewsletterForm');
    if (homeNewsletterForm) {
        homeNewsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            this.reset();
            var successModal = new bootstrap.Modal(document.getElementById('homeSuccessModal'));
            successModal.show();
        });
    }

    // 3. Xử lý form nhận bản tin ở trang chi tiết (detail.php)
    var newsletterForm = document.getElementById('newsletterForm');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            this.reset();
            var successModal = new bootstrap.Modal(document.getElementById('newsletterSuccessModal'));
            successModal.show();
        });
    }

    // 4. Xử lý form hỗ trợ khách hàng ở trang chủ
    var homeSupportForm = document.getElementById('homeSupportForm');
    if (homeSupportForm) {
        homeSupportForm.addEventListener('submit', function(e) {
            e.preventDefault();
            this.reset();
            var supportModalEl = document.getElementById('homeSupportModal');
            var modalInstance = bootstrap.Modal.getInstance(supportModalEl);
            if (modalInstance) modalInstance.hide();
            alert('TZY Store đã tiếp nhận phiếu yêu cầu hỗ trợ của ní!');
        });
    }

    // 5. Xử lý form hỗ trợ khách hàng ở trang chi tiết
    var supportForm = document.getElementById('supportForm');
    if (supportForm) {
        supportForm.addEventListener('submit', function(e) {
            e.preventDefault();
            this.reset();
            var supportModalEl = document.getElementById('supportModal');
            var modalInstance = bootstrap.Modal.getInstance(supportModalEl);
            if (modalInstance) modalInstance.hide();
            alert('TZY Store đã tiếp nhận phiếu hỗ trợ của ní!');
        });
    }
});