# Tên đề tài
Website bán quần áo (Nhóm bán hàng)

👕 TZY STORE  VÀ QUẢN LÝ BÁN QUẦN ÁO THỜI TRANG

# 1.Danh Sách Thành Viên
[Trần Nguyễn Duy] - MSSV: [24210501009] - Lớp: [242101TH001]

# 2.Phân Công Công Việc
[Trần Nguyễn Duy]: 
Khảo sát yêu cầu, thiết kế kiến trúc hệ thống và cơ sở dữ liệu (`tzy_store`).
Phát triển trọn gói các chức năng phía Khách hàng (Xem danh sách, tìm kiếm, lọc theo thẻ, giỏ hàng session, cổng xác thực đơn giản, lịch sử mua hàng).
Phát triển trọn gói các chức năng phía Quản trị viên (Bảng điều khiển thống kê, biểu đồ phân phối, quản lý danh mục sản phẩm CRUD, cập nhật trạng thái đơn hàng và bộ xuất dữ liệu Excel).
Tối ưu hóa kết nối bảo mật PDO, kiểm thử mã nguồn và viết tài liệu hướng dẫn.

# 3.Công Nghệ Sử Dụng
Backend: PHP thuần (Phiên bản khuyến nghị: PHP 7.4 - 8.x), xử lý Session để duy trì trạng thái đăng nhập và giỏ hàng.
Database: MySQL, kết nối thông qua thư viện an toàn PDO (PHP Data Objects) giúp phòng chống tấn công SQL Injection thông qua Prepared Statement.
Frontend: HTML5, CSS3, JavaScript, Bootstrap 5 (Giao diện chuẩn Responsive tối giản & sang trọng - Minimalist Luxury Aesthetics).
Thư viện tích hợp: Chart.js (Vẽ biểu đồ thống kê admin), Bootstrap Icons.
Công cụ phát triển: XAMPP Server, Visual Studio Code.

# 4.Mô Tả Chức Năng Hệ Thống
# 4.1.Phân Hệ Khách Hàng (Client-side):
Trang chủ & Bộ lọc thông minh: Hiển thị danh sách sản phẩm mới nhất, tích hợp thanh tìm kiếm theo tên/thẻ và bộ lọc nhanh theo Tag thời trang độc quyền.
Xem chi tiết sản phẩm (detail.php): Hiển thị chi tiết hình ảnh, giá bán, giá cũ, mô tả, phân loại kích thước (S, M, L, XL) và công cụ tăng giảm số lượng mua linh hoạt.
Giỏ hàng Session (add_to_cart.php & cart.php): Thêm sản phẩm vào giỏ hàng mà không cần đăng nhập tài khoản trước.
Tự động tính toán tổng số tiền, cập nhật số lượng và quản lý trạng thái giỏ.
Xác thực Email mua hàng nhanh: Đăng nhập nhanh bằng Email ngay tại giỏ hàng để bảo mật quyền lợi cá nhân.
Xử lý đặt hàng (Checkout): Thu thập thông tin giao hàng (Họ tên, SĐT, Địa chỉ) và khởi tạo đơn hàng theo hình thức COD.
Lịch sử mua hàng & Chi tiết hóa đơn: Khách hàng có thể theo dõi tiến độ xử lý đơn hàng của mình (Chờ xử lý, Thành công, Đã hủy) và xem chi tiết danh sách mặt hàng đã đặt bên trong hóa đơn.

# 4.2.Phân Hệ Quản Trị Viên (Admin-side):
Hệ thống bảo mật: Trang đăng nhập mã hóa, kiểm tra Session nghiêm ngặt để bảo vệ các tài nguyên hệ thống.
Bảng điều khiển (Dashboard) thống kê: Thống kê tổng số lượng sản phẩm và tổng số lượng hàng tồn kho.
Tích hợp biểu đồ Chart.js trực quan hóa tỷ lệ hàng hóa.
Quản lý Sản phẩm (Toàn quyền CRUD):
Thêm mới (Create): Nhập thông tin, gắn thẻ, phân mục (outerwear, T-shirts and polos, trousers) và upload file ảnh trực tiếp lên thư mục server.
Cập nhật (Update):Thay đổi thông tin sản phẩm, xử lý ghi đè hoặc giữ nguyên ảnh cũ thông minh.
Xóa dữ liệu (Delete): Hỗ trợ gỡ bỏ sản phẩm kèm cơ chế bẫy lỗi ngoại lệ (Try-Catch) nếu sản phẩm đó đang tồn tại trong đơn đặt hàng của khách.
Điều phối Đơn hàng: Xem toàn bộ danh sách đơn đặt hàng từ hệ thống và cập nhật trạng thái đơn hàng (Pending ➔ Completed / Canceled) ngay tại chỗ.
Trích xuất dữ liệu: Hỗ trợ tính năng Export to Excel kết xuất nhanh danh sách quản lý ra tệp bảng tính ngay trên trình duyệt.

# 5.Cấu Trúc Thư Mục Dự Án
```text
DoAnCuoiKy/
├── admin/                     # PHÂN HỆ QUẢN TRỊ (ADMIN)
│   ├── create.php             # Giao diện thêm sản phẩm mới
│   ├── delete.php             # Xử lý logic xóa sản phẩm (Bẫy lỗi ràng buộc)
│   ├── edit.php               # Giao diện chỉnh sửa thông tin sản phẩm
│   ├── index.php              # Dashboard chính (Thống kê, Biểu đồ, Đơn hàng, Export Excel)
│   ├── login.php              # Trang đăng nhập bảo mật Admin
│   ├── logout.php             # Đăng xuất và hủy Session hệ thống
│   ├── order_detail.php       # Xem chi tiết cấu trúc mặt hàng trong một hóa đơn
│   ├── orders.php             # Bảng quản lý danh sách toàn bộ hóa đơn khách đặt
│   ├── store.php              # Tiếp nhận POST, validate dữ liệu và xử lý upload ảnh mới
│   └── update.php             # Tiếp nhận POST, cập nhật dữ liệu và đồng bộ ảnh sản phẩm
├── config/
│   └── database.php           # Khởi tạo kết nối cơ sở dữ liệu tập trung qua lớp PDO
├── images/                    # Thư mục lưu trữ hình ảnh sản phẩm được tải lên từ Server
├── assets/                    # Chứa tài nguyên tĩnh dùng chung cho Admin (CSS, JS)
├── public/                    # Chứa hình ảnh, tài nguyên giao diện phía Khách hàng
├── add_to_cart.php            # Xử lý logic thêm sản phẩm và số lượng vào Session giỏ hàng
├── cart.php                   # Giao diện Giỏ hàng, Đăng nhập Email và Form đặt hàng COD
├── detail.php                 # Giao diện trang chi tiết sản phẩm (Chọn Size, chọn số lượng)
├── index.php                  # Trang chủ phía Khách hàng (Hiển thị sản phẩm, Tìm kiếm, Lọc Tag)
├── database.sql               # File sao lưu cấu trúc bảng dữ liệu và data mẫu của hệ thống
└── README.md                  # Tài liệu hướng dẫn này

## Hướng Dẫn Cài Đặt
Tải mã nguồn: Tải toàn bộ mã nguồn của dự án về máy tính và giải nén thư mục với tên là DoAnCuoiKy.
Cấu hình môi trường XAMPP:
Di chuyển/Sao chép thư mục DoAnCuoiKy vào thư mục lưu trữ cục bộ của XAMPP (Đường dẫn mặc định: C:\xampp\htdocs\DoAnCuoiKy).
Cấu hình Cơ sở dữ liệu:
Mở bảng điều khiển XAMPP Control Panel lên và nhấn Start hai dịch vụ Apache và MySQL.
Truy cập hệ quản trị cơ sở dữ liệu qua trình duyệt: http://localhost/phpmyadmin.
Tiến hành tạo mới một cơ sở dữ liệu lấy tên chính xác là: tzy_store (Chọn kiểu so khớp mã hóa: utf8mb4_general_ci).
Chọn database tzy_store vừa tạo, nhấp chọn tab Import (Nhập), nhấn Browse tìm đến file database.sql ở thư mục gốc dự án và nhấn Go (Thực hiện) để nạp cấu trúc dữ liệu.

## Hướng Dẫn Khởi Chạy Chương Trình
Kiểm tra thông số kết nối DB:
Hãy đảm bảo các thông số thiết lập trong file config/database.php khớp với tài khoản MySQL của bạn (Mặc định XAMPP: Host là localhost, User là root, và mật khẩu pass để trống '').
Trải nghiệm phía Khách hàng (Client Interface):
Mở trình duyệt web bất kỳ (Chrome, Edge, Firefox) và truy cập đường dẫn:
http://localhost/DoAnCuoiKy/index.php
Luồng test: Tìm kiếm sản phẩm ➔ Bấm xem chi tiết ➔ Chọn kích cỡ/số lượng ➔ Thêm vào giỏ hàng ➔ Nhập email xác thực ➔ Nhập thông tin và ấn đặt mua.
Trải nghiệm phía Quản trị (Admin Dashboard):
Điều hướng trình duyệt sang liên kết quản trị hệ thống:
http://localhost/DoAnCuoiKy/admin/login.php
Thông tin tài khoản kiểm thử mặc định:
Tên đăng nhập: Admin12345
Mật khẩu: tzy12345 

## Ảnh giao diện
## 8.Một Số Hình Ảnh Giao Diện
<img width="1840" height="862" alt="image" src="https://github.com/user-attachments/assets/15cfad53-6bb0-4820-866f-6b4daf17939d" />

