# RentHub

RentHub là dự án xây dựng nền tảng cho thuê đồ dùng nhiều shop, kết nối người cần thuê với các cửa hàng cung cấp máy ảnh, thiết bị cắm trại, máy chơi game, dụng cụ và thiết bị sự kiện.

Dự án hướng đến quản lý toàn bộ vòng đời thuê: tìm đồ, chọn lịch, đặt thuê, thanh toán cọc, bàn giao, trả đồ và hoàn cọc.

## Chức năng hướng đến

- **Khám phá đồ cho thuê:** tìm kiếm, lọc theo danh mục, shop, giá và khoảng ngày còn trống.
- **Đặt thuê:** xem báo giá, chọn thời gian, giữ chỗ và theo dõi trạng thái đơn.
- **Thanh toán:** quản lý tiền thuê, tiền cọc, phí phát sinh và hoàn tiền.
- **Giao nhận:** ghi nhận tình trạng đồ, hình ảnh bàn giao và kết quả kiểm tra khi trả.
- **Quản lý shop:** quản lý chi nhánh, danh mục đồ, từng chiếc trong kho, lịch bảo trì và nhân viên.
- **Tương tác:** lưu yêu thích, đánh giá sau khi thuê, nhắn tin và nhận thông báo.
- **Quản trị nền tảng:** duyệt shop, kiểm duyệt nội dung, hỗ trợ tranh chấp và theo dõi báo cáo.

## Người sử dụng

| Vai trò       | Nhu cầu                                               |
| ------------- | ----------------------------------------------------- |
| Khách hàng    | Tìm đồ phù hợp, đặt thuê và quản lý các đơn của mình  |
| Chủ shop      | Đăng đồ cho thuê, quản lý kho, nhân viên và doanh thu |
| Nhân viên     | Xử lý đơn, bàn giao và nhận trả theo quyền được cấp   |
| Quản trị viên | Quản lý hoạt động chung của nền tảng                  |

## Công nghệ định hướng

- **Backend:** Laravel 12, API JSON và Laravel Sanctum.
- **Frontend:** Vue 3, TypeScript và Vite.
- **Database:** MySQL.
- **Tác vụ nền và realtime:** Laravel Queue, Scheduler, Notification và Reverb.
- **Cache và hàng đợi:** Redis khi cần mở rộng.
- **Môi trường phát triển:** Docker và Docker Compose.

## Mục tiêu dự án

RentHub là dự án thực hành Laravel thông qua các bài toán thực tế: quan hệ dữ liệu, xác thực và phân quyền, xử lý đặt trùng lịch, giao dịch thanh toán, công việc nền và vận hành ứng dụng.

Chi tiết nghiệp vụ và lộ trình phát triển nằm trong [tài liệu dự án](docs/roadmap.md).
