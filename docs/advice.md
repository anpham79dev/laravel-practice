# Lời khuyên tự học — RentHub

Viết lại ngày 23/11/2026. Mục đích: khi không có trợ lý, bạn vẫn biết mình đang ở đâu, làm gì tiếp theo và làm thế nào để biết mình đã làm đúng.

Dùng cùng các tài liệu:

- [skill.md](skill.md): chuẩn làm đúng Laravel / SQL / DevOps và cách tự kiểm, không chứa lời giải.
- [review-fix-23-11.md](review-fix-23-11.md): lỗi đã biết của source ngày 23/11, sửa dần qua các bước B1–B5.
- [roadmap.md](roadmap.md): lộ trình dài hạn; mục 18 là bản đồ kiến thức Laravel, mục 20 là nhật ký, mục 21 là lộ trình tự đi sau 22/02/2027.
- `C:\dev\skill`: thư viện skill và handbook đã đúc kết (xem mục 9 bên dưới).

## 1. Hiện trạng đã kiểm chứng (23/11/2026)

Chạy trên bản copy sạch của commit `e8c1b29` (PHP 8.2, SQLite bộ nhớ), không phải trên MySQL:

| Kiểm tra | Kết quả |
| --- | --- |
| `php -l` mọi file PHP | Qua |
| `php artisan test` | 19 passed, 132 assertions |
| `vendor/bin/pint --test` | **Fail**: 8 file (controller, hai Request, ShopLogic, `routes/web.php`, ba file test) |
| `POST /api/shop` bằng curl, không có CSRF token | **419** (test không bắt được vì Laravel bỏ qua CSRF khi chạy test) |
| `GET /api/shop/{id}` | 200; có `deleted_at`, mỗi chi nhánh có `shop_id` |
| `GET /api/shop` | Không có `deleted_at`, nên shape khác `show` |

Đọc từ code, chưa chạy riêng:

- `store`/`update` không trả `branches`, vì `syncBranches()` gọi `$this->branches()->get()` và cách này không gán quan hệ vào model.
- `update` dùng chung `StoreShopRequest` nên bắt buộc đủ trường, trong khi roadmap mục 6 ghi "cho phép bỏ qua trường không sửa".
- `destroy` xóa mềm ngay cả khi shop còn chi nhánh.

Chưa kiểm chứng: migration và schema trên MySQL thật; máy này chưa có `vendor/` và `.env`.

Ngoài code: `docker-compose.yml` và `.env.example` đang chứa tài khoản MySQL thật đã commit; An quyết định bỏ qua (mục 1 của review-fix-23-11.md).

## 2. Nguyên tắc học

1. **Đoán trước khi chạy.** Trước mỗi lệnh, request hay test, viết ra một dòng kết quả mình nghĩ sẽ thấy. Lệch chỗ nào thì đó là chỗ hổng cần học. Đây là thói quen có giá trị nhất khi không có AI.
2. **Tự viết trước, tra sau.** Viết bản của mình trước, rồi mới đối chiếu với tài liệu hoặc code mẫu. Chỉ đọc lời giải khi đã bị kẹt quá 20 phút và đã ghi lại điều mình thử.
3. **Bằng chứng thay cho cảm giác.** "Chạy được" nghĩa là có response, có test đỏ rồi xanh, có câu SQL. Không có bằng chứng thì chưa xong.
4. **Một mục tiêu nhỏ mỗi buổi.** Một buổi chỉ làm một bước B hoặc D bên dưới. Không học Docker, Laravel, Vue và CI cùng lúc.
5. **Ghi một dòng sau mỗi buổi** vào nhật ký mục 20 của roadmap: đã làm gì, bằng chứng, còn vướng gì, bài tiếp theo. Chỉ nâng mức kỹ năng khi tự làm lại được bài tương tự mà không xem lời giải (thang ở mục 20 roadmap).

**Quy tắc khi kẹt:** 20 phút đầu tự điều tra theo mục 5; sau đó viết ra input, kết quả mong đợi, kết quả thực tế, lỗi, và những gì đã thử. Nhiều khi viết xong đoạn này là tự thấy lỗi.

## 3. Việc làm theo thứ tự

Mỗi bước có: mục tiêu → gợi ý → xong khi. Bước nào xong thì ghi nhật ký.

### B0. Dựng môi trường bằng Docker (tự gõ từng lệnh)

- Làm theo mục "Dựng source lần đầu" trong roadmap (mục 3). Tự gõ, không dán cả khối.
- Trước khi gõ mỗi lệnh, đoán nó làm gì.
- Không chạy `docker compose down -v` hay `migrate:fresh` trên DB có dữ liệu cần giữ. `.env` không được commit.
- **Xong khi:** `docker compose exec app php artisan test` cho 19 passed, và bạn giải thích được bằng lời:
  - từng dòng trong `.docker/Dockerfile` và `docker-compose.yml`;
  - vì sao Laravel dùng `DB_HOST=db` còn DBeaver trên máy dùng `127.0.0.1:3307`;
  - `depends_on` với `healthcheck` giải quyết vấn đề gì;
  - `./:/var/www/html` (bind mount) khác `db_data` (named volume) thế nào, và vì sao mất cái nào thì mất dữ liệu.

### B1. Test cho `show`

- `php artisan make:test ShopShowTest`, dùng `RefreshDatabase` (an toàn vì `phpunit.xml` dùng SQLite `:memory:`).
- Bốn ca: có chi nhánh → 200 và `data.branches` đủ phần tử; id không tồn tại → 404; shop đã xóa mềm → 404; shop không có chi nhánh → `data.branches` là `[]`.
- Gợi ý: `assertOk()`, `assertNotFound()`, `assertJsonCount()`, `assertJsonPath()`; xóa mềm bằng `$shop->delete()`.
- **Xong khi:** 4 test qua, và khi cố ý phá code (bỏ `load('branches')`, hoặc đổi `Shop $shop` thành `int $id` rồi `Shop::find`) thì có test đỏ. Nếu phá mà test vẫn xanh thì test chưa đủ chặt.

### B2. `ShopResource` ổn định, thêm `ShopBranchResource`

- Vấn đề: `parent::toArray()` trả mọi thứ đang có trong model.
- Gợi ý: liệt kê rõ từng field; `'branches' => ShopBranchResource::collection($this->whenLoaded('branches'))`; ở `store`/`update` load `branches` trước khi trả.
- Tự hỏi: vì sao `$this->branches()->get()` không làm `$shop->branches` có sẵn? (khác nhau giữa **gọi method quan hệ** và **thuộc tính quan hệ**).
- **Xong khi:** có test khẳng định cùng một shape cho index/show/store/update (index có thể không có `branches`, nhưng các field chung phải giống nhau); không còn `deleted_at` và `shop_id` thừa.

### B3. Dọn code và qua Pint

- Bỏ `@param` lặp type hint ở controller, thêm return type (`AnonymousResourceCollection`, `JsonResponse`, `ShopResource`, `Response`).
- Đổi `$data` thành `$shop`/`$shops`; `ShopRequest` thành `IndexShopRequest`; bỏ `use ValidationRule` không dùng.
- Sửa `@param` của `syncBranches()`: mảng ngoài có key kiểu gì, mỗi phần tử là gì? (gợi ý: `list<array<string, mixed>>`).
- Chạy `vendor/bin/pint --test` để xem lỗi, rồi mới `vendor/bin/pint`. Xem `git diff` trước khi commit. Tách commit `style:` riêng khỏi commit `feat:` để diff dễ review.
- **Xong khi:** `pint --test` sạch, test vẫn qua.

### B4. Chốt route và CSRF

- Tạo `routes/api.php`, đăng ký `api: __DIR__.'/../routes/api.php'` trong `bootstrap/app.php`, chuyển route shop sang đó, dùng `Route::apiResource('shops', ShopController::class)` (số nhiều, khớp bảng endpoint mục 6 roadmap).
- Không tắt CSRF toàn ứng dụng để né 419.
- Test không phát hiện lỗi CSRF, nên **phải thử thật** bằng curl hoặc Postman.
- Bài tập quan sát: gửi `POST` sai dữ liệu **không** kèm `Accept: application/json` và ghi lại điều gì xảy ra (redirect hay JSON?). Từ đó hiểu vì sao client API phải gửi header này.
- **Xong khi:** `php artisan route:list --path=api -v` hiện middleware `api`; POST bằng curl không bị 419; test đã đổi URL sang `/api/shops` và qua.

### B5. Ba quyết định còn lệch giữa code và roadmap

Mỗi quyết định ghi một đoạn ngắn vào roadmap mục 4 hoặc 6: **quyết định / lý do / hệ quả / test**. Rồi sửa code và test cho khớp.

1. `branches.*.id` không thuộc shop: hiện trả 404. Đề xuất 422, vì đây là dữ liệu gửi lên sai, không phải shop không tồn tại. Gợi ý: `Rule::exists('shop_branches', 'id')->where('shop_id', ...)`; nhớ rằng `store` chưa có shop. Cân nhắc cả trường hợp chạy đua (id hợp lệ lúc validate, mất lúc ghi).
2. `update`: PUT thay toàn bộ (giữ `StoreShopRequest`), hay `PATCH` với `UpdateShopRequest` dùng `sometimes`. Chọn một, và đổi method route cho đúng nghĩa.
3. `destroy`: roadmap ghi "shop còn chi nhánh/listing trả lỗi nghiệp vụ", code hiện xóa mềm luôn. Chọn một và cập nhật `test_destroy_soft_deletes_shop_and_returns_no_content`. Nhớ rằng `restrictOnDelete` ở khóa ngoại chỉ có tác dụng khi xóa cứng, không phải khi xóa mềm.

### B6. CRUD chi nhánh theo shop

- Endpoint ở bảng mục 6 roadmap: `/api/shops/{shop}/branches[/{branch}]`.
- Gợi ý: `Route::apiResource('shops.branches', ...)->scoped()` để URL shop A + chi nhánh shop B trả 404; tạo qua `$shop->branches()->create(...)`; không nhận `shop_id` từ payload.
- **Xong khi:** test có ca chi nhánh thuộc shop khác → 404, input sai → 422, shop đã xóa mềm → 404.

### B7. CRUD RentalItem theo shop

- Tự viết từ đầu theo mục 4, không copy controller shop rồi đổi tên.
- Quy tắc: `rental_price_per_day` > 0, `deposit_amount` ≥ 0, cả hai là số nguyên VND; `shop_id` lấy từ route.
- **Xong khi:** đủ "Checklist trước khi coi một tính năng là xong" ở mục 19 roadmap, và có test IDOR (shop A không đọc/sửa/xóa được đồ của shop B bằng cách đổi id).

### B8. Factory/Seeder và MySQL thật

- `ShopFactory`, `ShopBranchFactory`, `RentalItemFactory`; dùng trong test thay cho mảng viết tay.
- Trỏ `.env` sang service `db`, chạy `php artisan migrate` (không `migrate:fresh` trên DB cần giữ), seed, rồi gọi API thật.
- Đối chiếu schema thật: `SHOW CREATE TABLE shop_branches;` và kiểm tra khóa ngoại, kiểu cột có đúng migration không.
- Thêm `Model::preventLazyLoading(! app()->isProduction())` vào `AppServiceProvider::boot()` để lộ N+1 sớm.
- **Xong khi:** xem được dữ liệu trong client SQL, giải thích được từng cột/khóa ngoại, và `index` shop không bị N+1 (đếm query bằng `DB::listen` hoặc `DB::enableQueryLog()`).

Sau B8, tiếp tục theo chặng 3 trong mục 20 roadmap (Auth, Policy, Vue).

## 4. Cách tự bắt đầu một tính năng

1. **Viết input/output trước khi viết code.** Ví dụ cho `POST /api/shops/{shop}/rental-items`:
   - Request: `{"item_name": "Sony A6400", "rental_price_per_day": 250000, "deposit_amount": 2000000}`.
   - 201: `{"data": {"id": 1, "item_name": "...", "rental_price_per_day": 250000, ...}}`.
   - 422 khi `rental_price_per_day` bằng 0 hoặc là số thập phân; 404 khi shop không tồn tại hoặc đã xóa mềm.
2. **Liệt kê quy tắc và ca lỗi:** 404 (không tồn tại, không thuộc shop), 422 (input sai), lỗi nghiệp vụ, dữ liệu rỗng, giới hạn kích thước.
3. **Viết test trước cho một happy path và một ca lỗi.** Chúng đỏ vì chưa có code là đúng.
4. **Làm theo lớp:** route → FormRequest → controller → logic model → Resource.
5. **Chạy test, rồi thử bằng curl.** Đọc câu SQL nếu cần.
6. **Pint, commit theo tính năng, ghi nhật ký.** Tài liệu đổi cùng commit với code.

## 5. Khi bị lỗi (không có AI)

Theo thứ tự, dừng ở bước tìm ra bằng chứng:

1. **Response HTTP:** mã lỗi và body. 404 → route/binding/scope; 419 → CSRF; 422 → đọc `errors`; 500 → sang bước 3.
2. **Route:** `php artisan route:list --path=api -v` (đúng method, URL, middleware?).
3. **Log:** `storage/logs/laravel.log` (hoặc `docker compose logs -f app`). Đọc dòng exception đầu tiên và dòng đầu tiên thuộc `app/`.
4. **SQL:** `->toRawSql()` trên Builder, hoặc `DB::listen` / `DB::enableQueryLog()` trong local.
5. **Tái hiện bằng test:** `php artisan test --filter=ten_test`; `dump()`/`dd()` tạm, gỡ trước khi commit.
6. **Môi trường:** `docker compose ps`, `docker compose logs db`, cấu hình `.env` đã nạp chưa (`php artisan config:clear`).
7. Khi hỏi người khác hoặc AI: ghi input, kết quả mong đợi, kết quả thực tế, lỗi, và những gì đã thử.

Lỗi hay gặp trong dự án này:

| Triệu chứng | Nghi ngờ trước |
| --- | --- |
| POST trả 419 | Route nằm trong nhóm `web`; thiếu CSRF token |
| Test xanh nhưng curl lỗi | Test bỏ qua CSRF; SQLite khác MySQL; `.env` khác `phpunit.xml` |
| `SQLSTATE connection refused` | Sai `DB_HOST` (trong container là `db`, trên máy là `127.0.0.1:3307`) hoặc `db` chưa healthy |
| Quan hệ trả rỗng | `select([...])` thiếu khóa ngoại; chưa `load()` |
| Dữ liệu "biến mất" sau `down -v` | Đã xóa named volume `db_data` |

## 6. DevOps — học gắn với RentHub

Không học DevOps tách rời. Mỗi bậc dưới đây làm trên chính RentHub, và chỉ sang bậc sau khi bậc trước chạy được. Kubernetes chỉ học sau D6.

| Bậc | Việc làm | Xong khi |
| --- | --- | --- |
| **D1** | Docker cơ bản: dựng `app` + `db` (B0), đọc Dockerfile và Compose, xem log, vào shell container | Giải thích được từng dòng; tự tìm được lỗi kết nối DB qua log |
| **D2** | Env và secret: `.env` và `.env.example`; đưa mật khẩu khỏi `docker-compose.yml` (đọc từ `.env` bằng `${DB_PASSWORD}`); thêm `.dockerignore`; MySQL thật, migrate, seed, `mysqldump` rồi **khôi phục thử** vào DB khác | `git grep` không còn mật khẩu thật; restore thử thành công và đếm khớp số dòng |
| **D3** | Quan sát: route `/up` (đã bật trong `bootstrap/app.php`), `docker compose ps`, log ra stderr trong container, đọc `laravel.log` | Cố ý gây một lỗi 500 và tìm ra nguyên nhân chỉ từ log |
| **D4** | CI: GitHub Actions chạy `composer install`, `pint --test`, `php artisan test` mỗi lần push/PR; thêm bước test trên MySQL cho test cần khóa/đồng thời | Push một commit làm test đỏ thì CI đỏ; sửa xong CI xanh |
| **D5** | Linux và VPS: user không phải root, SSH key, tường lửa, nginx + php-fpm, HTTPS, quyền thư mục `storage/` và `bootstrap/cache/`, script deploy `set -euo pipefail` (`git pull`/image mới → `composer install --no-dev` → `migrate --force` → cache → `queue:restart`) | Người khác dựng được staging theo README; deploy lỗi giữa chừng thì dừng, không tự bật lại bản hỏng |
| **D6** | Image production (multi-stage), tách web / worker / scheduler, backup định kỳ và **diễn tập restore**, kế hoạch rollback (app và migration) | Deploy không mất đơn; khôi phục được từ backup; biết rollback bản cũ |
| Sau | Kubernetes: Pod, Deployment, Service, Ingress, ConfigMap, Secret; thực hành trên kind hoặc minikube | Chỉ khi D1–D6 đã vững và có nhu cầu thật |

Nguyên tắc DevOps cần giữ (chi tiết ở skill.md mục 9):

- Không đưa secret vào git, log, tài liệu. Đã lộ thì phải **đổi (rotate)**, xóa khỏi HEAD là chưa đủ.
- `artisan serve` chỉ dùng cho dev. Production dùng nginx + php-fpm.
- Backup chưa thử restore chưa chứng minh được gì.
- Mỗi thay đổi hạ tầng một commit nhỏ, có cách quay lại.

## 7. Tự đánh giá trung thực

Mỗi tháng làm một lần, khoảng 2 giờ, để biết mình thật sự đã nhớ gì:

1. **Làm lại từ trống:** chọn một endpoint đã làm (ví dụ CRUD chi nhánh), xóa nhánh thử và tự viết lại trong 90 phút, không xem bản cũ. So sánh với bản cũ rồi ghi điểm khác.
2. **Phá có chủ đích:** bỏ `where shop_id`, bỏ transaction, bỏ `load`. Test phải đỏ. Test vẫn xanh nghĩa là test yếu.
3. **Giải thích bằng văn:** viết 10 dòng giải thích một luồng (ví dụ `PATCH /api/shops/{id}`) cho người chưa biết dự án. Chỗ viết không trôi chảy là chỗ chưa hiểu.
4. **Đo:** đếm số truy vấn của một endpoint trước và sau khi tối ưu.
5. **Cập nhật thang mức ở mục 20 roadmap:** chỉ nâng khi qua được các bài trên.

## 8. Bẫy cần nhớ

- Test xanh trên SQLite không chứng minh được khóa, đồng thời hay hành vi MySQL. `Queue::fake` không chứng minh worker chạy.
- `$request->validated()` mới là dữ liệu đã kiểm; `all()` thì không.
- Không tin client cho quyền sở hữu (`shop_id`, `owner_email`); luôn lấy shop từ route hoặc người đăng nhập.
- Sort và filter chỉ theo whitelist; list luôn phân trang có giới hạn.
- Resource khai báo rõ field; không trả nguyên model.
- Ghi nhiều bảng thì dùng transaction và có test rollback; không gọi HTTP/mail/upload bên trong transaction.
- PHPDoc chỉ để thêm thông tin type hint không nói được, ví dụ kiểu phần tử của mảng.
- Không thêm Service/Repository khi chưa có vấn đề cụ thể.
- Không sửa migration đã chạy; muốn đổi schema thì tạo migration mới.

## 9. Thư viện skill ở `C:\dev\skill`

Đây là bộ kinh nghiệm đã đúc kết, viết độc lập với nơi gốc. Dùng để tra khi gặp quyết định, không đọc từ đầu đến cuối.

| Khi cần | Đọc |
| --- | --- |
| Quyết định đặt logic ở đâu, transaction, cache, lỗi, trùng lặp | `skills/engineering-judgement/SKILL.md` |
| Controller, model, scope, FormRequest, observer, job | `skills/laravel-backend/SKILL.md` |
| Query, N+1, khóa, chunk, index, cache query | `skills/database/SKILL.md` |
| Docker, Compose, Nginx, deploy, secret | `skills/devops/SKILL.md` |
| Lệnh batch/cron, import/export, chạy lại an toàn | `skills/batch-jobs/SKILL.md` |
| Phân quyền theo shop, giới hạn input, secret, chữ ký | `skills/api-security/SKILL.md` |
| Vue 3 + TypeScript (chặng 3) | `skills/vue-frontend/SKILL.md` |
| Hiểu "vì sao" sâu hơn | `handbook/Engineering-Bi-Kip-Vo-Cong.md` |

Cách đọc đúng:

- Bắt đầu từ `SKILL.md`; xem `checklist.md` khi chuẩn bị commit hoặc review; `examples.md` khi cần ví dụ tốt/xấu.
- Các skill gốc viết cho hệ thống lớn, nhiều tenant, DB legacy. **RentHub là greenfield nhỏ**, nên bỏ qua phần legacy (nhiều connection, DB dùng chung, rule cho codebase cũ) và lấy phần nguyên tắc.
- Mỗi luật có nhãn phạm vi (`Reusable best practice`, `Team convention`, `Trade-off decision`, `Legacy workaround`). Chỉ phần best practice mới mang nguyên sang; còn lại phải tự cân nhắc.
- Nếu tài liệu và code đang chạy lệch nhau, tin code và ghi lại điểm lệch.
- `research/` có tên thật của hệ thống nội bộ; không sao chép, không chia sẻ ra ngoài. Thư mục `C:\dev\docker` là môi trường dev nội bộ đời cũ; chỉ đọc để hiểu mô hình "all-in-one" và vì sao người ta bỏ nó, không chép cấu hình, host hay thông tin liên hệ vào repo này.

## 10. Giữ tài liệu sống

- Sau mỗi buổi có kết quả: một dòng nhật ký (mục 20 roadmap).
- Thay đổi quyết định: cập nhật roadmap mục 4 hoặc 6 trong cùng commit với code.
- Mỗi lần chạy lại test hoặc Pint, ghi ngày và môi trường (SQLite hay MySQL, PHP bản nào) để biết con số còn đáng tin hay không.
- Khi sửa file này, giữ nguyên các mục B0–B8; chỉ đánh dấu xong khi đã có bằng chứng ở cột "Xong khi".
