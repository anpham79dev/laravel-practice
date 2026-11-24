# Review lỗi cần sửa — 23/11/2026

Ghi ngày 23/11/2026 khi review source ở commit `e8c1b29`. Đây là danh sách để **An tự sửa**. Mỗi mục có: vị trí, triệu chứng, nguyên nhân, hướng sửa, và khi nào coi là xong. File không có code lời giải; quy tắc liên quan tra ở [skill.md](skill.md), thứ tự làm theo [advice.md](advice.md).

**Nguồn bằng chứng:** đọc source trong buổi review này. Các mục ghi "đã chạy" lấy từ lần chạy ngày 23/11 ở advice.md mục 1 (PHP 8.2, SQLite bộ nhớ). Buổi review này chưa chạy lại test hay Pint.

**Phân loại:**

- **Lỗi hành vi:** chạy sai so với đặc tả.
- **Lệch đặc tả:** code và roadmap nói khác nhau; phải quyết định bên nào đúng rồi sửa bên kia.
- **Rủi ro:** chưa sai nhưng dễ gây sự cố.
- **Phong cách:** không đổi hành vi.

Sửa xong mục nào: đánh dấu `[x]`, ghi ngày và bằng chứng (test, lệnh, commit), rồi thêm một dòng nhật ký ở roadmap mục 20. Khi đổi quyết định (mục 5–8), sửa cả test đang khóa hành vi cũ và ghi lý do trong commit.

## Tổng quan

| # | Loại | Vấn đề | Bước (advice.md) |
| --- | --- | --- | --- |
| 1 | Rủi ro bảo mật | Tài khoản DB thật nằm trong file đã commit — **bỏ qua** (An quyết định 23/11/2026) | – |
| 2 | Lỗi hành vi | Route ghi dữ liệu bị CSRF chặn với client thật (419) | B4 |
| 3 | Lỗi hành vi | `store`/`update` không trả `branches` | B2 |
| 4 | Lỗi hành vi | Resource lộ field nội bộ, shape không thống nhất | B2 |
| 5 | Lệch đặc tả | `update` bắt buộc gửi đủ field | B5 |
| 6 | Lệch đặc tả | Xóa được shop còn chi nhánh | B5 |
| 7 | Lệch đặc tả | id chi nhánh không thuộc shop trả 404 | B5 |
| 8 | Cần quyết định | `?is_active=true` bị 422 | B4 hoặc B5 |
| 9 | Nhỏ | Ký tự `%`, `_` trong từ khóa tìm kiếm | sau B8 |
| 10 | Thiếu test | `show` chưa có test | B1 |
| 11 | Phong cách | Pint, import thừa, return type, tên biến, tên request | B3 |
| 12 | Phong cách | PHPDoc `syncBranches()` sai kiểu | B3 |
| 13 | Rủi ro sắp tới | Xóa mềm shop không ảnh hưởng chi nhánh và đồ | B6, B7 |

## Chi tiết

### 1. [-] Tài khoản DB thật nằm trong file đã commit — Rủi ro bảo mật (bỏ qua)

> **Bỏ qua:** An quyết định ngày 23/11/2026 không xử lý mục này. Nội dung bên dưới giữ lại để tham khảo nếu mở lại.

- **Vị trí:** [.env.example:27-28](../.env.example#L27-L28), [docker-compose.yml:17-20](../docker-compose.yml#L17-L20) và mật khẩu root trong healthcheck ở [docker-compose.yml:26](../docker-compose.yml#L26). Đã nằm trong lịch sử git từ các commit dựng Docker.
- **Triệu chứng:** ai đọc repo cũng thấy user và mật khẩu DB.
- **Nguyên nhân:** giá trị thật được ghi cứng, thay vì đọc từ `.env` (file không commit).
- **Hướng sửa:** skill 9.1–9.2. Compose đọc `${...}` từ `.env`; `.env.example` để trống giá trị; coi mật khẩu cũ là đã lộ và đổi nó. Nếu mật khẩu này còn dùng ở nơi khác thì đổi cả ở đó.
- **Bẫy khi sửa:** MySQL chỉ đọc biến `MYSQL_*` ở lần khởi tạo volume đầu tiên. Đổi biến trong compose **không** đổi mật khẩu đã lưu trong volume `db_data`. Hãy đổi bằng SQL (`ALTER USER`), đừng xóa volume nếu cần giữ dữ liệu.
- **Xong khi:** `git grep` ở skill 9.2 không còn thấy giá trị thật; `docker compose config` cho thấy giá trị lấy từ `.env`; `db` ở trạng thái healthy và `php artisan migrate:status` chạy được.

### 2. [x] Route ghi dữ liệu nằm trong nhóm `web` — Lỗi hành vi (đã chạy: curl POST trả 419)

> **Đã sửa 23/11/2026 (trợ lý sửa theo yêu cầu An):** thêm `routes/api.php` với `Route::apiResource('shops', ...)`, đăng ký `api:` trong `bootstrap/app.php`, bỏ nhóm `api` khỏi `web.php`, test đổi sang `/api/shops`. Bằng chứng: `route:list --path=api -v` hiện middleware `api`; curl `POST /api/shops` không token trả 422 (không còn 419); 22 test qua (PHP 8.4, SQLite bộ nhớ).

- **Vị trí:** [routes/web.php:11-13](../routes/web.php#L11-L13).
- **Triệu chứng:** POST/PUT/PATCH/DELETE từ curl, Postman hoặc SPA không kèm CSRF token đều nhận 419. Test vẫn xanh vì Laravel bỏ qua CSRF khi chạy test.
- **Nguyên nhân:** route trong `routes/web.php` nhận middleware `web` (session + CSRF).
- **Hướng sửa:** advice.md B4; skill 3.1. URL nên đổi sang `/api/shops` theo roadmap mục 6, và các test phải đổi URL theo.
- **Xong khi:** `route:list --path=api -v` hiện middleware `api`; curl POST không còn 419; test qua với URL mới.

### 3. [x] `store`/`update` không trả `branches` — Lỗi hành vi (đọc từ code)

> **Đã sửa 23/11/2026 (trợ lý):** controller gọi `$shop->load('branches')` sau `saveFromRequest()`. Test trong `ShopResponseTest` kiểm tra `data.branches`; bỏ `load` ở `store` thì test đỏ (đã thử).

- **Vị trí:** [ShopController.php:40-56](../app/Http/Controllers/ShopController.php#L40-L56), [ShopLogic.php:95](../app/Models/Logic/ShopLogic.php#L95).
- **Triệu chứng:** response 201/200 không có `branches` dù vừa tạo hoặc sửa chi nhánh; client phải gọi thêm `show` mới thấy.
- **Nguyên nhân:** `syncBranches()` gọi method quan hệ `branches()->get()`. Cách này trả về một collection mới mà không gán quan hệ vào model, và controller cũng không load trước khi trả.
- **Hướng sửa:** advice.md B2; skill 3.6.
- **Xong khi:** test `store` và `update` kiểm tra `data.branches` có đúng số phần tử và đúng giá trị vừa gửi.

### 4. [x] Resource dùng `parent::toArray()` — Lỗi hành vi (đã chạy GET show)

> **Đã sửa 23/11/2026 (trợ lý):** `ShopResource` khai báo rõ field, `branches` qua `whenLoaded`; thêm `ShopBranchResource`. `ShopResponseTest` kiểm tra cùng bộ field cho index/show/store/update và không có `deleted_at`, `shop_id`.

- **Vị trí:** [ShopResource.php:17](../app/Http/Resources/ShopResource.php#L17).
- **Triệu chứng:**
  - `GET /api/shop/{id}` trả `deleted_at`, và mỗi chi nhánh có `shop_id`.
  - `GET /api/shop` không có `deleted_at` (vì `simple()` chỉ chọn một số cột), nên shape của danh sách và chi tiết lệch nhau.
  - Cột thêm vào bảng sau này sẽ tự lộ ra API.
- **Hướng sửa:** advice.md B2; skill 3.6 (khai báo field, `whenLoaded`, thêm Resource cho chi nhánh).
- **Xong khi:** test kiểm tra cùng bộ field chung cho index/show/store/update; `assertJsonMissingPath` cho `deleted_at` và `shop_id` của chi nhánh.

### 5. [ ] `update` dùng `StoreShopRequest` — Lệch đặc tả roadmap mục 6

- **Vị trí:** [ShopController.php:51](../app/Http/Controllers/ShopController.php#L51).
- **Triệu chứng:** `PATCH /api/shop/{id}` chỉ gửi `shop_name` nhận 422 vì thiếu `owner_name`, `owner_phone`, `branches`. Roadmap mục 6 lại ghi PATCH là cập nhật cho phép bỏ qua trường không sửa. Ngoài ra `Route::resource` đăng ký cả PUT lẫn PATCH cho `update`.
- **Hướng sửa:** advice.md B5 điểm 2. Chọn PUT (thay toàn bộ) hay PATCH (sửa một phần). Nếu chọn PATCH, cần nhớ hai điều:
  - Request mới phải có rule cho **mọi** field được sửa, vì field không có rule bị `validated()` bỏ đi (skill 3.5).
  - `saveFromRequest()` đang đọc thẳng `$data['branches']`, nên sẽ lỗi `Undefined array key` khi PATCH không gửi `branches`.
- **Xong khi:** quyết định được ghi vào roadmap mục 6; test PATCH một field trả 200 và các field khác giữ nguyên; test gửi đủ field thì tất cả được lưu.

### 6. [ ] Xóa được shop còn chi nhánh — Lệch đặc tả roadmap mục 6

- **Vị trí:** [ShopController.php:61-66](../app/Http/Controllers/ShopController.php#L61-L66); roadmap mục 6, dòng `DELETE /api/shops/{shop}`.
- **Triệu chứng:** shop còn chi nhánh hoặc listing vẫn bị xóa mềm (204). Roadmap ghi trường hợp này phải trả lỗi nghiệp vụ.
- **Nguyên nhân:** code không kiểm tra; còn `restrictOnDelete` chỉ chặn xóa cứng, không chặn xóa mềm.
- **Hướng sửa:** advice.md B5 điểm 3. Hoặc giữ hành vi hiện tại và sửa roadmap, hoặc chặn bằng lỗi nghiệp vụ (chọn 409 hay 422, ghi lý do). Test `test_destroy_soft_deletes_shop_and_returns_no_content` trong `ShopSyncTest` phải cập nhật theo.
- **Xong khi:** roadmap và code khớp nhau; có test cho cả shop rỗng lẫn shop còn chi nhánh.

### 7. [ ] id chi nhánh không thuộc shop trả 404 — Lệch đặc tả

- **Vị trí:** [ShopLogic.php:99](../app/Models/Logic/ShopLogic.php#L99) (`findOrFail` trên collection), [StoreShopRequest.php:26](../app/Http/Requests/StoreShopRequest.php#L26) (chưa có rule kiểm tra id thuộc shop).
- **Triệu chứng:** PATCH shop A kèm `branches[0].id` của shop B trả 404, giống hệt "shop không tồn tại". Với POST tạo mới, gửi bất kỳ `id` nào cũng 404. Test `test_branch_from_another_shop_causes_a_full_rollback` trong `ShopSyncTest` đang khóa hành vi 404 này.
- **Hướng sửa:** advice.md B5 điểm 1 (đề xuất 422, vì đây là dữ liệu gửi lên sai). Cần cân nhắc thêm:
  - Khi tạo mới, có nên cho phép gửi `id` không?
  - Vẫn giữ kiểm tra lúc ghi cho trường hợp chạy đua: id hợp lệ lúc validate nhưng bị xóa trước lúc ghi.
- **Xong khi:** quyết định được ghi vào roadmap; có test cho cả POST và PATCH; vẫn rollback toàn bộ khi lỗi.

### 8. [ ] Lọc `?is_active=true` trả 422 — Cần quyết định (đọc từ code)

- **Vị trí:** [ShopRequest.php:20](../app/Http/Requests/ShopRequest.php#L20).
- **Triệu chứng:** rule `boolean` chỉ nhận `true`, `false`, `1`, `0`, `"1"`, `"0"`. Giá trị trong query string luôn là chuỗi, nên `?is_active=true` bị 422. Nhiều client gửi boolean đúng theo dạng này, kể cả `URLSearchParams` và axios khi frontend Vue gọi API. Test hiện chỉ dùng `is_active=0`.
- **Hướng sửa:** hoặc ghi rõ trong tài liệu API rằng chỉ nhận `1`/`0`, hoặc chuẩn hóa `"true"`/`"false"` trong `prepareForValidation()` (skill 3.5). Chọn một và ghi lại.
- **Xong khi:** có test cho đúng lựa chọn đã chọn.

### 9. [ ] Ký tự đại diện trong từ khóa tìm kiếm — Nhỏ (đọc từ code)

- **Vị trí:** [ShopLogic.php:63](../app/Models/Logic/ShopLogic.php#L63).
- **Triệu chứng:** tìm `%` khớp mọi shop; `_` khớp một ký tự bất kỳ (tìm `a_b` sẽ khớp `acb`). Đây không phải lỗ hổng SQL injection (đã dùng binding), chỉ là kết quả tìm kiếm bị lệch.
- **Hướng sửa:** quyết định có escape `%`, `_`, `\` hay không. Nếu escape, lưu ý SQLite và MySQL xử lý escape khác nhau (SQLite cần mệnh đề `ESCAPE`), nên phải thử trên cả hai.
- **Xong khi:** nếu chọn escape, test tìm `%` chỉ trả về shop có `%` trong tên.

### 10. [ ] `show` chưa có test — Thiếu test

- **Vị trí:** thư mục `tests/Feature`: chưa test nào gọi `GET /api/shop/{id}`.
- **Hướng sửa:** advice.md B1 (bốn ca).
- **Xong khi:** bốn test qua, và khi phá có chủ đích (skill 8.4) thì test đỏ.

### 11. [ ] Dọn phong cách — Phong cách (đã chạy: `pint --test` fail 8 file)

- Pint báo 8 file: controller, hai Request, `ShopLogic`, `routes/web.php`, ba file test.
- Import không dùng: `Illuminate\Contracts\Validation\ValidationRule` ở [ShopRequest.php:5](../app/Http/Requests/ShopRequest.php#L5) và [StoreShopRequest.php:5](../app/Http/Requests/StoreShopRequest.php#L5).
- `ShopController`: `index`, `store`, `update`, `destroy` thiếu return type; PHPDoc `@param` chỉ lặp lại type hint; biến `$data` đang chứa model hoặc paginator.
- `ShopRequest` nên đặt tên theo hành động.
- **Hướng sửa:** advice.md B3; tách riêng commit `style:`.
- **Xong khi:** `pint --test` sạch và test vẫn qua.

### 12. [ ] PHPDoc `syncBranches()` mô tả sai kiểu — Phong cách

- **Vị trí:** [ShopLogic.php:90-92](../app/Models/Logic/ShopLogic.php#L90-L92).
- **Triệu chứng:** kiểu khai báo trong `@param` không khớp với dữ liệu thật mà `StoreShopRequest` cho phép đi vào. IDE và công cụ phân tích tĩnh sẽ hiểu sai.
- **Hướng sửa:** tự xác định mảng ngoài có key kiểu gì và mỗi phần tử là gì (xem "Điểm review kế tiếp" ở roadmap mục 20).
- **Xong khi:** giải thích được bằng lời vì sao kiểu mới đúng.

### 13. [ ] Xóa mềm shop không ảnh hưởng chi nhánh và đồ — Rủi ro sắp tới

- **Vị trí:** `Shop` có `SoftDeletes`; `ShopBranch` và `RentalItem` thì không.
- **Hiện tại:** chưa gây lỗi, vì mọi endpoint đều đi qua binding `{shop}` (shop đã xóa mềm thì 404).
- **Khi nào thành lỗi:** ở B6, B7 và chặng 2 (danh mục chung cho khách), nếu truy vấn thẳng `ShopBranch`/`RentalItem` mà không đi qua shop thì sẽ hiện đồ của shop đã xóa.
- **Hướng sửa:** mọi truy vấn không đi qua shop phải loại shop đã xóa mềm. Nếu quyết định ở mục 6 là chặn xóa shop còn dữ liệu, rủi ro này nhỏ đi.
- **Xong khi:** có test chứng minh đồ của shop đã xóa mềm không xuất hiện trong danh sách công khai.
