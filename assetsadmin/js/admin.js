// 1. Hàm xác nhận xóa
function confirmDelete(id) {
    if (confirm("Ní có chắc muốn xóa bản ghi này? Thao tác không thể hoàn tác!")) {
        window.location.href = "delete.php?id=" + id;
    }
}

// 2. Chức năng Đổi giao diện Dark Mode (Cộng điểm)
function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    const isDark = document.body.classList.contains('dark-mode');
    localStorage.setItem('admin_dark_mode', isDark ? 'enabled' : 'disabled');
}

// 3. Chức năng Xuất Excel trực tiếp từ bảng dữ liệu (Cộng điểm)
function exportToExcel() {
    var tab_text = "<table border='1'><tr bgcolor='#87AFC7'>";
    var tab = document.getElementById('product-table');
    if (!tab) return alert("Không tìm thấy bảng dữ liệu!");
    
    for (var j = 0 ; j < tab.rows.length ; j++) {     
        // Bỏ qua cột Thao tác cuối cùng khi xuất Excel
        var rowContent = tab.rows[j].innerHTML;
        tab_text = tab_text + rowContent + "</tr>";
    }
    tab_text = tab_text + "</table>";
    
    var blob = new Blob([tab_text], { type: "application/vnd.ms-excel;charset=utf-8;" });
    var downloadLink = document.createElement("a");
    downloadLink.href = URL.createObjectURL(blob);
    downloadLink.download = "danh-sach-san-pham.xls";
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

// 4. Validation phía Client (Bắt lỗi ngay lập tức)
document.addEventListener('DOMContentLoaded', function() {
    if (localStorage.getItem('admin_dark_mode') === 'enabled') {
        document.body.classList.add('dark-mode');
    }
    
    var productForm = document.getElementById('productForm');
    if (productForm) {
        productForm.addEventListener('submit', function(e) {
            var price = document.getElementById('price').value;
            var stock = document.getElementById('stock').value;
            if (parseFloat(price) <= 0 || parseInt(stock) < 0) {
                alert('Dữ liệu nhập vào không hợp lệ (Giá > 0, Kho >= 0)!');
                e.preventDefault();
            }
        });
    }
});