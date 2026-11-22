# Lời khuyên tự học tiếp — RentHub

Viết ngày 22/11/2026 sau buổi review `ShopController` và `ShopLogic`. Dùng file này để tự làm tiếp khi không có trợ lý. Làm theo thứ tự, mỗi bước xong thì ghi một dòng vào nhật ký mục 20 trong [roadmap.md](roadmap.md).

## 1. Hiện trạng đã kiểm chứng

- `php -l` qua với controller và ShopLogic; `php artisan test`: 19 passed, 132 assertions (chạy ngày 22/11/2026).
- Route shop: index/store/show/update/destroy dưới `/api/shop`, nằm trong `routes/web.php` nên chịu middleware `web` (session + CSRF).
- `.env` local đang dùng `DB_CONNECTION=sqlite`; MySQL trong Docker chưa được xác minh.
- `show()` chưa có test.

## 2. Việc làm theo thứ tự

Mỗi bước có: mục tiêu → gợi ý → coi là xong khi.

### B1. Test cho `show`

- Tạo: `php artisan make:test ShopShowTest`, dùng `RefreshDatabase` (đã an toàn: phpunit dùng SQLite `:memory:`).
- Bốn ca: có chi nhánh → 200 và `data.branches` đủ phần tử; id không tồn tại → 404; shop đã xóa mềm → 404; shop không có chi nhánh → `data.branches` là `[]`.
- Gợi ý: `assertOk()`, `assertNotFound()`, `assertJsonCount(2, 'data.branches')`, `assertJsonPath(...)`. Xóa mềm bằng `$shop->delete()`.
- **Xong khi:** 4 test qua, và thử cố ý làm hỏng code (ví dụ bỏ `load`) thì có test đỏ.

### B2. Sửa `ShopResource` cho response ổn định

- Vấn đề: `parent::toArray()` trả mọi cột/quan hệ đang có → `store/update` thiếu `branches`, `show` có `deleted_at`, `index` thì không.
- Gợi ý: liệt kê rõ field cần trả; `'branches' => ...->whenLoaded('branches')` (tạo thêm `ShopBranchResource` cho chi nhánh). Ở `store`/`update` load `branches` trước khi trả.
- Câu tự hỏi: vì sao `$this->branches()->get()` không làm `$shop->branches` có sẵn trong Resource? (khác nhau giữa gọi method quan hệ và thuộc tính quan hệ).
- **Xong khi:** test kiểm tra cùng một shape JSON cho index/show/store/update; không còn `deleted_at` trong response.

### B3. Dọn controller và ShopLogic

- Bỏ PHPDoc `@param` chỉ lặp lại type hint; thêm return type: `AnonymousResourceCollection`, `JsonResponse`, `ShopResource`, `Response`.
- Đổi tên `$data` → `$shop` / `$shops`; `ShopRequest` → `IndexShopRequest`.
- Bài PHPDoc: dữ liệu `[{branch_name: 'A'}, {branch_name: 'B'}]` — mảng ngoài key kiểu gì, mỗi phần tử là gì? Tự sửa `@param` của `syncBranches`.
- Sửa/xóa comment `// relation ship`, `// scope` đặt sai chỗ.
- **Xong khi:** `vendor/bin/pint --test` sạch, test vẫn qua.

### B4. Chốt route và CSRF

- Đọc mục 3 "Quy ước route và CSRF" trong roadmap. Hướng khuyên dùng cho JSON API: tạo `routes/api.php`, đăng ký `api:` trong `bootstrap/app.php`, chuyển route shop sang đó, dùng `Route::apiResource('shops', ...)` (số nhiều, khớp bảng endpoint mục 6).
- Không tắt CSRF toàn ứng dụng để né 419.
- Nhớ: test không phát hiện lỗi CSRF vì Laravel bỏ qua CSRF khi chạy test → phải thử thật bằng Postman/curl.
- **Xong khi:** `php artisan route:list --path=api -v` hiện middleware `api`; POST bằng curl không bị 419; test đổi URL sang `/api/shops` và qua.

### B5. Ba quyết định nghiệp vụ còn lệch giữa code và roadmap

Tự quyết, ghi lý do vào roadmap mục 4 hoặc 6, rồi sửa code + test cho khớp:

1. Gửi `branches.*.id` không thuộc shop: hiện trả 404. Đề xuất 422 (lỗi dữ liệu gửi lên, không phải shop không tồn tại). Gợi ý: `Rule::exists('shop_branches', 'id')->where('shop_id', ...)` trong FormRequest (lấy shop bằng `$this->route('shop')`), nhớ trường hợp store chưa có shop.
2. Update: roadmap mục 6 nói "cho phép bỏ qua trường không sửa", nhưng hiện `update` dùng chung `StoreShopRequest` bắt buộc mọi trường. Chọn: PUT thay toàn bộ, hoặc tạo `UpdateShopRequest` với `sometimes`.
3. Xóa: roadmap ghi "shop còn chi nhánh/listing trả lỗi nghiệp vụ", code hiện xóa mềm luôn. Chọn một và cập nhật test `test_destroy_soft_deletes_shop_and_returns_no_content`.

### B6. CRUD chi nhánh theo shop

- Endpoint ở bảng mục 6: `/api/shops/{shop}/branches[/{branch}]`.
- Gợi ý: `Route::apiResource('shops.branches', ...)->scoped()` để URL shop A + chi nhánh shop B trả 404; tạo qua `$shop->branches()->create(...)`, không nhận `shop_id` từ payload.
- **Xong khi:** test có ca chi nhánh thuộc shop khác → 404, input sai → 422.

### B7. CRUD RentalItem theo shop

- Làm lại toàn bộ quy trình ở mục 3 bên dưới, tự viết từ đầu (không copy controller shop rồi sửa tên). Quy tắc: `rental_price_per_day` > 0, `deposit_amount` ≥ 0, giá là số nguyên VND.
- **Xong khi:** đủ checklist "trước khi coi một tính năng là xong" trong roadmap mục 19.

### B8. Factory/Seeder và MySQL thật

- `ShopFactory`, `ShopBranchFactory`, `RentalItemFactory`; dùng trong test thay cho mảng thủ công.
- Chạy Docker (`docker compose up -d`), trỏ `.env` sang service `db`, chạy `php artisan migrate` (không `migrate:fresh` trên DB có dữ liệu cần giữ), seed, rồi gọi API thật.
- **Xong khi:** xem được dữ liệu trong MySQL bằng client SQL và giải thích được cột/khóa ngoại.

## 3. Cách tự bắt đầu một tính năng

1. Viết ra input/output: method + URL, ví dụ request body, ví dụ response 200/201/204.
2. Liệt kê quy tắc và ca lỗi: 404 (không tồn tại / không thuộc shop), 422 (input sai), lỗi nghiệp vụ.
3. Viết test cho 1 happy path + 1 ca lỗi trước (hoặc ngay sau khi có route).
4. Làm theo lớp: route → FormRequest → controller → logic model → Resource.
5. Chạy test, thử bằng curl/Postman, đọc SQL nếu cần.
6. Pint, commit theo tính năng, ghi nhật ký.

## 4. Khi bị lỗi (không có AI)

Theo thứ tự, dừng ở bước tìm ra bằng chứng:

1. **Response HTTP:** mã lỗi và body. 404 → route/binding/scope; 419 → CSRF; 422 → đọc `errors`; 500 → sang bước 3.
2. **Route:** `php artisan route:list --path=api -v` (đúng method, URL, middleware?).
3. **Log:** `storage/logs/laravel.log` — đọc dòng exception đầu tiên và dòng đầu tiên thuộc `app/`.
4. **SQL:** `->toRawSql()` trên Builder, hoặc `DB::listen` / `DB::enableQueryLog()` trong local.
5. **Tái hiện bằng test:** `php artisan test --filter=ten_test`; `dump()`/`dd()` tạm, gỡ trước khi commit.
6. Khi hỏi ai đó: ghi input, kết quả mong đợi, kết quả thực tế, lỗi.

## 5. Lệnh hay dùng

```bash
php artisan test                      # toàn bộ test
php artisan test --filter=ShopShowTest
php artisan route:list --path=api -v
php artisan make:test TenTest
php artisan make:request TenRequest
php artisan make:resource TenResource
php artisan make:factory TenFactory --model=Ten
php -l duong/dan/file.php             # kiểm cú pháp
vendor/bin/pint --test                # kiểm phong cách, không sửa
vendor/bin/pint                       # sửa phong cách
```

## 6. Nguyên tắc nên giữ

- Không tin dữ liệu client cho quyền sở hữu (`shop_id`, `owner_email`); lấy shop từ route.
- Ghi nhiều bảng → transaction; có test rollback.
- Sort/filter chỉ theo whitelist; list luôn phân trang có giới hạn.
- Resource khai báo rõ field; không trả nguyên model.
- PHPDoc chỉ để thêm thông tin type hint không nói được (ví dụ kiểu phần tử mảng).
- Không thêm Service/Repository khi chưa có vấn đề cụ thể.
- Tự đánh giá trung thực theo thang ở mục 20: chỉ nâng mức khi tự làm lại được bài tương tự mà không xem lời giải.
