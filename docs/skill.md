# Sổ tay chuẩn RentHub — Laravel, SQL, DevOps

Viết lại ngày 23/11/2026. File này trả lời ba câu hỏi khi tự làm: **làm thế nào là đúng, vì sao, và kiểm tra bằng gì**. File **không chứa lời giải** cho bài tập trong [advice.md](advice.md). Hãy tự viết trước, rồi đối chiếu bài làm với quy tắc và phần "Tự kiểm" ở đây.

Đọc cùng:

- [roadmap.md](roadmap.md) mục 18: bản đồ toàn bộ kiến thức Laravel, mỗi nhóm trỏ về một mục của file này.
- [advice.md](advice.md): việc làm theo thứ tự (B0–B8, D1–D6), cách debug, tự đánh giá.
- [review-fix-23-11.md](review-fix-23-11.md): lỗi đã biết của source.

**Cách đọc một mục:** quy tắc → vì sao → **Tự kiểm** (câu hỏi hoặc bằng chứng cho thấy bạn làm đúng). Code chỉ là mảnh vài dòng minh họa một bẫy (sai → đúng), không phải file hoàn chỉnh để chép. Tên class chưa có trong repo (`Booking`, `RentalUnit`...) là minh họa cho chặng sau.

**Nhãn:** **[Chuẩn]** nên làm ở hầu hết dự án · **[Quy ước]** lựa chọn của repo này, đổi được nhưng phải thống nhất · **[Đánh đổi]** phải ghi rõ lý do khi chọn.

**Đánh số:** mục trong file này dùng số (1–12), ví dụ "skill 3.5". Bước học trong advice.md là B0–B8 và D1–D6.

Repo đang dùng `laravel/framework` 12.x, Carbon 3, PHP 8.4 trong Docker.

## 1. Trước khi viết một tính năng

Trả lời bằng văn bản, mỗi câu một dòng, trước khi mở editor:

1. Method + URL? Request mẫu, response mẫu và mã thành công (200/201/204)?
2. Tài nguyên thuộc **shop nào**, lấy shop từ đâu? (Từ route hoặc người đăng nhập, không từ payload.)
3. Quy tắc nghiệp vụ và ca lỗi: 401/403, 404, 409, 422, dữ liệu rỗng, giới hạn kích thước?
4. Ghi mấy bảng? (Từ hai bảng trở lên: transaction và test rollback.)
5. Có thể bị gọi đồng thời không? (Có: unique index hoặc khóa dòng.)
6. Có gọi hệ thống ngoài hoặc việc chậm không? (Có: làm sau commit, hoặc đưa vào queue.)
7. Test nào chứng minh nó đúng? (Ít nhất happy path, một ca 422, một ca 404, một ca sai shop.)

**Tự kiểm:** không còn câu nào trả lời "chưa biết"; mỗi ca lỗi ở câu 3 có một test ở câu 7. Quy trình làm tiếp ở advice.md mục 4.

## 2. Nền PHP và kiến trúc Laravel

### 2.1 PHP cần chắc [Chuẩn]

- Khai báo type cho tham số, giá trị trả về và property; `?Type` khi có thể null. PHPDoc chỉ thêm điều type hint không nói được, ví dụ kiểu phần tử mảng (`list<...>`, `array<string, mixed>`).
- Phân biệt `$this` (đối tượng), `self::`/`static::` (class), `parent::`. Trait là "chép code vào class", không phải kế thừa.
- `empty()` coi `"0"`, `0`, `[]`, `null` là rỗng. Khi `0` hoặc `"0"` là giá trị hợp lệ, dùng `array_key_exists`, `isset`, hoặc `filled()`/`blank()` của Laravel (`blank('0')` là `false`).
- `enum` cho tập giá trị cố định; `readonly` cho dữ liệu không đổi sau khi tạo; `match` thay `switch` khi cần trả về giá trị.
- Namespace khớp thư mục (PSR-4): `App\Models\Logic\ShopLogic` phải nằm ở `app/Models/Logic/ShopLogic.php`, tên trait trùng tên file. Sai chỗ này thì autoload không tìm thấy class.

**Tự kiểm:** giải thích từng dòng `ShopLogic` hiện có: dòng nào chạy trên Builder, dòng nào trên Model, dòng nào trên Collection.

### 2.2 Vòng đời request [Chuẩn]

`public/index.php` → `bootstrap/app.php` → middleware toàn cục → router chọn route → middleware của nhóm (`web` hoặc `api`) và của route → route model binding → FormRequest (authorize rồi validate) → controller → Resource → response.

Hệ quả cần nhớ:

- Binding chạy **trước** controller: shop không tồn tại hoặc đã xóa mềm thì trả 404 trước khi code của bạn chạy.
- FormRequest validate **trước** controller: dữ liệu sai thì 422, controller không chạy.
- Lỗi ở bất kỳ bước nào đều đi qua exception handler khai báo trong `bootstrap/app.php`.

**Tự kiểm:** viết 10 dòng mô tả đường đi của `POST /api/shop` từ lúc vào đến lúc trả 201, chỉ ra chỗ có thể sinh 404, 419, 422, 500.

### 2.3 Service container, provider, facade [Chuẩn]

- Để Laravel tự inject qua constructor hoặc tham số method; không `new` một service có phụ thuộc.
- Chỉ tạo interface + binding khi có ít nhất hai cài đặt thật, hoặc cần thay bản giả trong test (ví dụ cổng thanh toán ở chặng 6). Không tạo interface "cho đẹp".
- `register()` chỉ đăng ký binding. `boot()` cho việc cần service khác đã sẵn sàng (Gate, RateLimiter, `Model::preventLazyLoading`).
- Singleton không được giữ dữ liệu của một request (user, shop hiện tại), vì dữ liệu sẽ rò sang request sau khi chạy worker dài hoặc Octane.
- Facade là lối tắt tới object trong container; nhờ vậy test được bằng `Mail::fake()`, `Queue::fake()`, `Http::fake()`.

**Tự kiểm:** trả lời được "vì sao controller nhận được `StoreShopRequest` đã validate mà không ai `new` nó"; trong một test, thay một service bằng bản giả qua `$this->instance(...)` hoặc `::fake()`.

### 2.4 Cấu hình và môi trường [Chuẩn]

- `env()` chỉ dùng trong `config/*.php`; nơi khác dùng `config('...')`. Sau `php artisan config:cache`, `env()` gọi ngoài file config trả `null`.
- Đổi `.env` mà không thấy tác dụng: `php artisan config:clear`.
- Giá trị riêng của RentHub (thời gian giữ chỗ, giới hạn upload) đặt trong một file config riêng, tên có đơn vị (`hold_minutes`, `max_image_kb`).
- Test chạy theo `phpunit.xml` (hiện là SQLite `:memory:`, session và cache dạng array, queue `sync`), không theo `.env`.

**Tự kiểm:** `php artisan about` cho thấy đúng driver DB, cache, queue, session của môi trường đang chạy.

## 3. HTTP: route, middleware, controller, validation, response, lỗi

### 3.1 Route [Chuẩn]

- API JSON đặt trong `routes/api.php` (nhóm `api`: không session, không CSRF; Laravel tự thêm tiền tố `/api`). Không tắt CSRF toàn ứng dụng để né 419.
- **Ngoại lệ sẽ gặp ở chặng 4:** khi dùng Sanctum SPA cookie (`statefulApi()`), request từ domain frontend lại đi qua session và CSRF. Client phải gọi `/sanctum/csrf-cookie` trước; lúc này 419 là do cấu hình SPA, không phải route sai.
- CRUD dùng `apiResource`, URL số nhiều (`/api/shops`). Tên tham số sinh từ tên resource ở dạng số ít, gạch nối đổi thành gạch dưới: `shops.rental-items` → `{shop}/{rental_item}`.
- Tài nguyên lồng dùng `->scoped()`. Laravel tìm con qua quan hệ của cha, đoán tên quan hệ là dạng số nhiều camelCase của tham số (`rental_item` → `rentalItems()`). Nhờ vậy URL ghép shop A với đồ của shop B trả 404.
- Hành động ngoài CRUD (hủy đơn, xác nhận) là route tường minh, mỗi hành động một FormRequest, khai báo trước `apiResource` cùng tiền tố.
- Route model binding mặc định bỏ qua bản ghi đã xóa mềm (404). Muốn lấy cả bản đã xóa (để khôi phục) thì dùng `->withTrashed()` trên route đó.

**Tự kiểm:** `php artisan route:list --path=api -v` hiện đúng method, URI, tên tham số và middleware `api`; POST bằng curl (không token) không bị 419.

### 3.2 Middleware [Chuẩn]

- Middleware dành cho việc **mọi request của một nhóm** đều cần: xác thực, giới hạn tần suất, đặt ngôn ngữ, gắn request id vào log. Logic nghiệp vụ của một endpoint không đặt ở middleware.
- Laravel 11+ đăng ký middleware trong `bootstrap/app.php` (`->withMiddleware(...)`: `alias`, `append`, `api(append: ...)`); không còn file `Kernel.php`.
- Thứ tự quan trọng: middleware cần user (phân quyền) phải chạy sau middleware xác thực.

**Tự kiểm:** tự viết một middleware nhỏ (ví dụ gắn header `X-Request-Id` vào response và vào ngữ cảnh log), kèm một test kiểm tra header.

### 3.3 Controller [Chuẩn]

- Controller mỏng: nhận request đã validate, gọi logic, trả Resource. Có return type (`ShopResource`, `AnonymousResourceCollection`, `JsonResponse`, `Response`).
- Tên biến theo nghĩa (`$shop`, `$shops`), không đặt `$data` cho model.
- Hàm controller mà nơi khác (job, command) cần thì chuyển xuống model hoặc Action; không gọi controller từ controller.
- Controller chỉ có một hành động: class có `__invoke`.
- Laravel 11+ có `Controller` gốc trống, không có `$this->authorize()`. Dùng `authorize()` của FormRequest, middleware `can:` trên route, hoặc `Gate::authorize(...)`.

### 3.4 Đặt logic ở đâu [Quy ước]

| Loại logic | Đặt ở | Ví dụ trong repo |
| --- | --- | --- |
| Điều kiện truy vấn dùng lại | Scope trong trait `app/Models/Logic/XLogic.php` | `scopeSimple`, `scopeForList`, `scopeByParams` trong `ShopLogic` |
| Ghi hoặc đổi trạng thái một aggregate (model và bảng con của nó) | Method trên model, trong trait Logic | `saveFromRequest()` |
| Chạm nhiều aggregate, gọi hệ thống ngoài, hoặc được gọi từ job/command/API | Action nhận input đã validate, shop truyền tường minh | chưa cần ở RentHub |
| Nhận HTTP, trả response | Controller mỏng | `ShopController` |

- Không tách Service chỉ để controller ngắn hơn; lớp chỉ chuyển tiếp là chi phí, không phải kiến trúc. Không thêm repository/interface khi chưa có vấn đề cụ thể.
- Model hoặc trait vượt khoảng 1.000 dòng, hoặc trộn domain khác, thì tách phần đó ra Action.

**Tự kiểm:** với mỗi method mới, trả lời "nếu job hoặc command cần làm việc này, nó gọi vào đâu?". Nếu câu trả lời là "gọi controller" thì logic đang đặt sai chỗ.

### 3.5 Validation và FormRequest [Chuẩn]

- Endpoint ghi dữ liệu dùng FormRequest; chỉ truyền `$request->validated()` (hoặc `safe()->only([...])`) vào model, không dùng `all()`.
- **Field không có rule sẽ bị `validated()` bỏ đi.** Mọi field model được phép ghi phải có rule (ít nhất `sometimes`). Nếu thiếu, request vẫn trả 200 nhưng field đó không được lưu, và không có lỗi nào báo.
- Với mảng lồng, giới hạn key bằng `array:key1,key2` và giới hạn số phần tử (`min`, `max`).
- Tên FormRequest theo hành động: `IndexShopRequest`, `StoreShopRequest`, `UpdateShopRequest`. Store và update chỉ dùng chung khi quy tắc thật sự giống nhau. PATCH dùng `sometimes`; khi đó code ghi phải kiểm tra `array_key_exists` trước khi đọc key tùy chọn.
- `Rule::exists` và `Rule::unique` không tự biết shop: luôn thêm điều kiện `shop_id`. Bảng có xóa mềm thì cân nhắc loại các dòng đã xóa.
- Chuẩn hóa dữ liệu (bỏ khoảng trắng, đổi `"true"` thành `true`) đặt trong `prepareForValidation()`. Quy tắc liên quan nhiều field (ngày kết thúc sau ngày bắt đầu) đặt trong `after()`. Quy tắc dùng lại nhiều nơi viết thành Rule class (`php artisan make:rule`).
- Dữ liệu sai thì trả 422, không âm thầm bỏ qua hay tự sửa.

**Tự kiểm:**

- Test gửi đủ mọi field rồi kiểm tra DB có đủ (bắt lỗi thiếu rule).
- Test PATCH một field: các field khác giữ nguyên.
- Test gửi id thuộc shop khác: trả đúng mã đã chọn và DB không đổi.

### 3.6 Response và API Resource [Chuẩn]

- Resource **khai báo rõ từng field**, không dùng `parent::toArray()` (trả mọi cột, kể cả `deleted_at`, khóa ngoại, và cột thêm sau này).
- Quan hệ trong Resource dùng `whenLoaded(...)`; controller quyết định load gì. Gọi method quan hệ (`$shop->branches()->get()`) **không** gán quan hệ vào model. Chỉ `load()`/`with()` hoặc truy cập thuộc tính (`$shop->branches`) mới gán.
- Cùng một tài nguyên thì danh sách, chi tiết, tạo, sửa trả cùng tên field; danh sách có thể có ít quan hệ hơn.
- Mã trạng thái: 200 đọc/sửa, 201 tạo, 204 xóa, 401 chưa đăng nhập, 403 không có quyền, 404 không tồn tại hoặc không thuộc shop, 409 xung đột trạng thái, 422 input sai, 429 vượt tần suất.
- Danh sách luôn phân trang và có giới hạn. `paginate` có tổng số (tốn thêm một câu `count`); `simplePaginate` không có tổng; `cursorPaginate` theo khóa, ổn định cho danh sách lớn hoặc cuộn vô hạn.
- Export lớn dùng `streamDownload` kết hợp `lazyById`, không dựng cả file trong RAM.

**Tự kiểm:** test dùng `assertJsonStructure` cho index/show/store/update với cùng bộ field chung; `assertJsonMissingPath('data.deleted_at')`.

### 3.7 Lỗi và log [Chuẩn]

- Lỗi nghiệp vụ là **exception có kiểu riêng**, render một chỗ trong `bootstrap/app.php` (`->withExceptions(...)`). Không `try/catch` lặp lại ở từng controller; không dùng `InvalidArgumentException` chung (lỗi lập trình cũng sẽ thành 422).
- API phải trả JSON cả khi client quên header `Accept`. Sau bài quan sát ở bước B4, tìm hiểu `shouldRenderJsonWhen`.
- Không nuốt lỗi: `catch {}` rỗng, hoặc trả `success` khi thất bại, nguy hiểm hơn chính lỗi gốc.
- Log có ngữ cảnh (`shop_id`, `booking_id`, request id) qua tham số context, `Log::withContext` hoặc `Context`; không log token, mật khẩu, dữ liệu thanh toán.
- Trong container, log ra stderr (`LOG_CHANNEL=stderr`) để đọc bằng `docker compose logs`.

**Tự kiểm:** cố ý ném lỗi nghiệp vụ → response 422 đúng format; cố ý gây lỗi 500 → tìm ra nguyên nhân chỉ từ log.

## 4. Eloquent

### 4.1 Model [Chuẩn]

- `$fillable` chỉ gồm field người dùng được sửa. **Khóa phân quyền (`shop_id`) không nằm trong `$fillable`**; luôn gán qua quan hệ (`$shop->rentalItems()->create(...)`).
- Ép kiểu bằng casts (Laravel 11+ có thể khai báo bằng method `casts()`): `boolean`, `integer`, `datetime`, enum, `encrypted` cho dữ liệu nhạy cảm. Lưu ý `decimal:7` trả về **chuỗi** trong JSON.
- Accessor/mutator kiểu mới là method trả `Attribute::make(get: ..., set: ...)`. Kiểu `getXAttribute` vẫn chạy nhưng là cách cũ.
- Accessor dùng quan hệ chỉ an toàn khi quan hệ đã được load (`relationLoaded`); nếu không, mỗi dòng sinh thêm một truy vấn.
- Xóa mềm có `withTrashed()`, `onlyTrashed()`, `restore()`, `forceDelete()`. Xóa mềm cha **không** xóa con và không kích hoạt khóa ngoại; bạn phải tự quyết định xử lý con thế nào.
- `firstOrCreate`/`updateOrCreate` không an toàn khi có request đồng thời nếu thiếu unique index (hai request cùng "không thấy" rồi cùng tạo).

### 4.2 Quan hệ [Chuẩn]

- Dùng đúng loại quan hệ:
  - `hasMany`/`belongsTo`: shop với chi nhánh.
  - `belongsToMany` có pivot và cột thêm: tài khoản với shop kèm vai trò (chặng 4).
  - `hasManyThrough`: shop tới chiếc đồ qua listing (chặng 3).
  - Quan hệ đa hình `morphMany`: ảnh dùng chung cho shop và đồ (chặng 2).
- Quan hệ có `->select([...])` phải **gồm khóa ngoại**; nếu thiếu, quan hệ trả rỗng mà không báo lỗi.
- Lọc theo quan hệ dùng `whereHas`; đếm hoặc tính tổng dùng `withCount`, `withSum`, `withExists`. Không nạp cả quan hệ chỉ để đếm.

### 4.3 Scope và eager loading [Quy ước]

- `simple()` chỉ chọn cột cần cho danh sách. `forList($params)` gồm `simple` + lọc + sắp xếp + `with/withCount`, và là **nơi duy nhất** khai báo eager load cho màn danh sách. Màn chi tiết làm tương tự với `forDetail()`.
- `byParams($params)` chỉ áp dụng filter có giá trị. Sort chỉ theo whitelist đã validate. Luôn thêm `orderBy('id')` sau một cột sort không duy nhất để phân trang ổn định (`ShopLogic::scopeByParams` đã làm).
- Scope có `return` sớm thì điều kiện theo shop phải áp **trước** mọi nhánh return.
- Bật `Model::preventLazyLoading(! app()->isProduction())` trong `AppServiceProvider::boot()` để N+1 thành exception khi dev và test.
- Global scope theo shop **[Đánh đổi]**: chống quên lọc, nhưng làm điều kiện bị ẩn, và job/command không có người đăng nhập nên không biết shop. Nếu dùng, phải có test cho cả các đường chạy ngoài HTTP.

**Tự kiểm:** bật `preventLazyLoading` rồi chạy toàn bộ test; không có `LazyLoadingViolationException`.

### 4.4 Collection [Chuẩn]

- Builder (chưa chạy SQL) khác Collection (dữ liệu đã nằm trong RAM). Lọc trên Builder (`where`) thay vì nạp hết rồi `filter()`.
- Tra cứu nhiều lần trong vòng lặp: `keyBy('id')` một lần rồi lấy theo key, không gọi `firstWhere` ở mỗi vòng.
- Duyệt dữ liệu lớn: `lazyById()` hoặc `cursor()` thay vì `get()`.

**Tự kiểm:** với một đoạn code bất kỳ, chỉ ra từng biến là Builder, Model hay Collection, và câu SQL chạy ở dòng nào.

### 4.5 Factory và Seeder [Chuẩn]

- Model cần trait `HasFactory` để dùng `Model::factory()`. Factory sinh dữ liệu **hợp lệ và tối thiểu**; ca đặc biệt dùng state (ví dụ `->inactive()`); quan hệ dùng `has()`/`for()`.
- Test chỉ ghi rõ những giá trị nó đang kiểm tra; phần còn lại để factory lo.
- Seeder dùng cho dữ liệu dev/demo, chạy lại không tạo trùng; không chạy seeder demo ở production.

**Tự kiểm:** thay mảng viết tay trong một test bằng factory mà test vẫn đọc rõ nó đang kiểm tra điều gì.

### 4.6 Observer, event, enum [Chuẩn]

- Observer/listener dành cho hệ quả **bắt buộc** mà mọi luồng đều cần (audit, đồng bộ). Dữ liệu nghiệp vụ chỉ một luồng cần thì gọi tường minh trong luồng đó.
- `Model::query()->update()` hàng loạt **không** chạy observer.
- Observer, event hoặc listener có tác động ra ngoài hệ thống (mail, queue) phải chạy sau commit: observer dùng `ShouldHandleEventsAfterCommit`, event dùng `ShouldDispatchAfterCommit`, listener/job qua queue dùng `ShouldQueueAfterCommit`.
- Sự kiện xảy ra theo thời gian (giữ chỗ hết hạn) dùng scheduled command, không dùng observer.
- Trạng thái có luật chuyển dùng enum; luật chuyển nằm trong enum và có unit test không cần DB.

```php
// Bẫy: chạy sau commit thì thay đổi đã được đồng bộ, isDirty() luôn false
if ($booking->isDirty('status')) { /* không bao giờ chạy khi afterCommit */ }
if ($booking->wasChanged('status')) { /* đúng */ }
```

## 5. SQL, schema và đồng thời

### 5.1 Truy vấn [Chuẩn]

- SQL thô luôn dùng binding (`?`), không ghép chuỗi, kể cả "chỉ là id nội bộ".
- N+1: vòng lặp truy cập một quan hệ chưa eager load, nên mỗi dòng sinh thêm một truy vấn.

```php
foreach (Shop::all() as $shop) { $shop->branches->count(); }                     // 1 + N truy vấn
foreach (Shop::withCount('branches')->get() as $shop) { $shop->branches_count; } // 1 truy vấn
```

- `LIKE '%từ khóa%'` không dùng được index thường: chấp nhận khi dữ liệu nhỏ, đo bằng `EXPLAIN` khi dữ liệu lớn. Ký tự `%` và `_` trong từ khóa là ký tự đại diện; phải quyết định có escape hay không.
- Xem câu SQL thật: `->toRawSql()` trên Builder, `DB::listen(...)` hoặc `DB::enableQueryLog()` trong local/test; gỡ trước khi commit.

**Tự kiểm:** số truy vấn của endpoint danh sách **không đổi** khi tăng từ 1 lên 10 bản ghi (đếm bằng query log trong test).

### 5.2 Migration và schema [Chuẩn]

- Không sửa migration đã chạy ở môi trường khác; muốn đổi thì tạo migration mới, có `down()` đúng.
- Thêm cột NOT NULL vào bảng đã có dữ liệu thì làm nhiều bước: thêm cột nullable hoặc có default → điền dữ liệu → rồi mới siết NOT NULL.
- Tiền là số nguyên VND (`unsignedBigInteger`), không dùng float.
- Ràng buộc **cứng** (ví dụ mã chiếc đồ duy nhất trong shop) đặt bằng unique index ở DB, với `shop_id` nằm trong khóa.
- Xóa mềm và unique: dòng đã xóa mềm vẫn chiếm unique key. Phải chọn một trong hai: không cho dùng lại mã đã xóa (đơn giản), hoặc cho dùng lại (cần cột phụ, vì `NULL` không bằng nhau trong unique index của MySQL).
- `restrictOnDelete` chỉ chặn xóa **cứng**.
- Index theo truy vấn thật, thứ tự cột theo cách lọc; đo bằng `EXPLAIN` trước và sau khi thêm.
- Kiểm tra schema trên MySQL thật, không suy ra từ code hay từ SQLite.

**Tự kiểm:**

```sql
SHOW CREATE TABLE shop_branches;   -- khóa ngoại và kiểu cột có đúng như migration?
SHOW INDEX FROM rental_items;      -- index nào đang có?
EXPLAIN SELECT ...;                -- type = ALL là quét cả bảng; xem cột key và rows
```

`php artisan migrate --pretend` in ra SQL sẽ chạy mà không chạy thật.

### 5.3 Transaction [Chuẩn]

- Validate trước, mở transaction sau; trong transaction chỉ ghi DB.
- Không gọi HTTP, gửi mail hay upload file bên trong transaction. Các tác động ra ngoài chạy **sau commit** (`DB::afterCommit`, hoặc `->afterCommit()` cho job).
- `DB::transaction` trả về giá trị mà closure trả về; helper tự viết để bọc transaction cũng phải trả kết quả đó.
- Ghi nhiều bảng thì phải có test rollback: ép bước sau lỗi, rồi kiểm tra dữ liệu của bước trước không còn. Mẫu có trong repo: `test_failed_branch_creation_rolls_back_the_shop` trong `tests/Feature/ShopStoreTest.php`.
- Upload file nằm ngoài transaction có thể để lại file mồ côi khi rollback **[Đánh đổi]**: ghi chú lại và có job dọn.

### 5.4 Đồng thời [Chuẩn]

| Loại ràng buộc | Cách xử lý |
| --- | --- |
| Cứng: không được trùng, không được âm | Unique index, hoặc `lockForUpdate()` trong transaction |
| Mềm: hạn mức hiển thị | Dọn lại sau khi ghi, chấp nhận lệch trong thời gian ngắn |
| Số chứng từ theo shop | Bảng sequence + unique index; `MAX()+1` chỉ an toàn khi giữ khóa đến lúc commit |
| Khoảng ngày thuê chồng nhau | Khoảng `[start, end)`; kiểm tra lại **trong transaction đã khóa** các dòng `rental_units` |

- "Kiểm tra rồi mới ghi" không an toàn: hai request cùng qua bước kiểm tra. Hãy để unique index quyết định, rồi bắt `UniqueConstraintViolationException` và chuyển thành lỗi nghiệp vụ.
- Khóa nhiều dòng thì khóa theo thứ tự ổn định (id tăng dần) để tránh deadlock.
- Hai khoảng `[start, end)` chồng nhau khi `existing.start < new.end AND existing.end > new.start`. Chỉ tính các đặt chỗ còn chiếm lịch (bỏ đơn đã hủy và giữ chỗ đã hết hạn).

**Tự kiểm:** các ca biên phải có test. Ví dụ với đặt chỗ có sẵn từ 10/11 đến 12/11:

| Yêu cầu mới | Kết quả đúng |
| --- | --- |
| 12/11 → 14/11 | Không trùng (chạm biên cuối) |
| 08/11 → 10/11 | Không trùng (chạm biên đầu) |
| 11/11 → 13/11 | Trùng một phần |
| 09/11 → 15/11 | Trùng (bao trùm) |
| 10/11 → 12/11 | Trùng (y hệt) |

Khóa chỉ chứng minh được trên **MySQL thật với hai kết nối**. Terminal 1 chạy `START TRANSACTION; SELECT ... FOR UPDATE;` mà chưa commit; terminal 2 chạy câu tương tự và phải **bị chờ** đến khi terminal 1 commit. SQLite không chứng minh được điều này.

### 5.5 Xử lý nhiều dòng [Chuẩn]

- Nếu callback cập nhật chính cột dùng để lọc, dùng `chunkById`, không dùng `chunk()`: phân trang bằng OFFSET bỏ sót dòng khi tập kết quả co lại.
- Xóa hàng loạt theo lô (`limit` + lặp đến khi hết), ghi log số dòng đã xóa.
- Command xử lý dữ liệu có `--dry-run`, chạy lại an toàn, và in tổng kết.

### 5.6 Thời gian và tiền [Chuẩn]

- Timestamp sự kiện lưu theo UTC; ngày thuê tính theo `Asia/Ho_Chi_Minh` (roadmap mục 4). Timezone của app và của kết nối DB phải thống nhất.
- Carbon 3 (repo đang dùng): `diffInDays()` trả **float** và **có dấu** (gọi ngược thứ tự ra số âm). Tính số ngày thuê thì ép `(int)` và luôn gọi từ ngày bắt đầu tới ngày kết thúc.
- Tên biến có đơn vị: `$holdMinutes`, `$ttlSeconds`, `MAX_IMAGE_KB`.
- Test phụ thuộc thời gian dùng `$this->travelTo(...)`, không dùng `sleep`.

## 6. Bảo mật

### 6.1 Phạm vi shop và IDOR [Chuẩn]

IDOR là lỗi người dùng đổi id trên URL để đọc hoặc sửa dữ liệu không thuộc về mình.

- Shop lấy từ route (`{shop}`) hoặc từ người đăng nhập, **không** lấy từ payload. Mọi đường "tìm theo id" đều phải đi qua phạm vi shop.
- Giá trị tính được (tổng tiền, số ngày, tiền cọc) thì server tự tính lại, không nhận từ client.
- Giới hạn đầu vào: kích thước trang, độ dài mảng, độ dài chuỗi, kích thước file.

**Tự kiểm (test IDOR):**

- Tạo tài nguyên của shop B, gọi GET/PATCH/DELETE qua URL của shop A: tất cả trả 404, dữ liệu của shop B không đổi.
- Gửi `shop_id` của shop B trong payload khi tạo ở shop A: bản ghi vẫn thuộc shop A.

### 6.2 Xác thực với Sanctum [Chuẩn]

- Roadmap chọn Sanctum SPA cookie cho frontend first-party (mục 9). Cần cấu hình `statefulApi()`, `SANCTUM_STATEFUL_DOMAINS`, `SESSION_DOMAIN`, CORS `supports_credentials`, và gọi `/sanctum/csrf-cookie` trước khi đăng nhập.
- Token (`createToken`) dành cho client không phải trình duyệt, có `abilities` và hạn dùng. Không lưu token dài hạn trong `localStorage`.
- Sau khi đăng nhập gọi `session()->regenerate()` để chống chiếm phiên (session fixation). Khi đăng xuất gọi `invalidate()` và `regenerateToken()`.
- Mật khẩu dùng cast `hashed` hoặc `Hash::make`, kiểm tra bằng `Hash::check`. Không bao giờ mã hóa hai chiều hay ghi mật khẩu ra log.
- Endpoint đăng nhập và quên mật khẩu có giới hạn tần suất theo email + IP.

**Tự kiểm:** test 401 khi chưa đăng nhập; test đăng nhập sai quá số lần → 429; thử luồng SPA thật trên trình duyệt (test không kiểm CSRF).

### 6.3 Phân quyền với Gate và Policy [Chuẩn]

- Xác thực (bạn là ai) khác phân quyền (bạn được làm gì): 401 là chưa xác thực, 403 là không có quyền.
- Quyền trên một model dùng Policy (Laravel tự tìm `App\Policies\RentalItemPolicy` cho `App\Models\RentalItem`). Quyền không gắn với model dùng Gate.
- Gọi phân quyền ở một chỗ rõ ràng: `authorize()` của FormRequest, middleware `can:` trên route, hoặc `Gate::authorize()`.
- Danh tính lấy từ người đăng nhập, không từ field client gửi lên.
- **[Đánh đổi]** Tài nguyên của shop khác trả 404 (không lộ là nó tồn tại) hay 403 (rõ nghĩa hơn): chọn một, ghi vào roadmap mục 4, và test đúng theo lựa chọn đó.

**Tự kiểm:** mỗi endpoint có ma trận test: chủ shop (200), nhân viên (theo vai trò), shop khác (404 hoặc 403), chưa đăng nhập (401).

### 6.4 Secret, mã hóa, webhook, upload [Chuẩn]

- So sánh secret và chữ ký bằng `hash_equals`. Thiếu cấu hình secret thì **từ chối** (fail-closed), không cho qua.
- Webhook: xác thực chữ ký trên body gốc, đối chiếu số tiền và mã đơn, xử lý sự kiện trùng an toàn (lưu id sự kiện với unique index).
- `APP_KEY` mã hóa session, cookie và các cột `encrypted`. Đổi key mà không khai báo key cũ (`APP_PREVIOUS_KEYS`) thì dữ liệu cũ không giải mã được.
- Upload:
  - Whitelist `mimes`, giới hạn `max` (Laravel tính bằng KB), lưu file bằng tên do server sinh.
  - File riêng tư (giấy tờ, ảnh bàn giao) không đặt ở disk `public`; tải qua route có kiểm quyền, hoặc qua URL có chữ ký và hạn dùng.
  - Giới hạn kích thước phải khớp ở ba tầng: nginx `client_max_body_size`, PHP `upload_max_filesize`/`post_max_size`, và rule Laravel.

### 6.5 Giới hạn tần suất [Chuẩn]

- Định nghĩa limiter có tên trong `AppServiceProvider::boot()` (`RateLimiter::for(...)`), gắn vào route bằng middleware `throttle:<tên>`. Đếm theo user hoặc IP, không chỉ đếm chung cho toàn hệ thống.

**Tự kiểm:** test gọi vượt giới hạn nhận 429 kèm header `Retry-After`.

## 7. Xử lý nền và tích hợp

### 7.1 Queue và job [Chuẩn]

- Đặt connection tường minh. Nhớ rằng driver `sync` biến `ShouldQueue` thành chạy ngay trong request.
- Job có `$tries`, `$backoff`, `$timeout`, `failed()`. Lỗi vĩnh viễn (đối tác trả 4xx) thì gọi `$this->fail()`; lỗi tạm (timeout, 5xx, 429) thì ném exception để queue retry.
- `retry_after` của connection phải **lớn hơn** `timeout` lớn nhất của các job; nếu không, job có thể chạy song song với chính nó.
- Payload chỉ mang id. Job phải chạy lại nhiều lần vẫn an toàn (idempotent). Dispatch bên trong transaction thì thêm `->afterCommit()`.
- Không cho job chạy trùng: dùng `ShouldBeUnique` hoặc middleware `WithoutOverlapping`. Nhiều bước phụ thuộc nhau dùng chain; nhiều việc song song cần theo dõi tiến độ dùng batch.
- Worker giữ code cũ trong RAM, nên sau deploy phải `php artisan queue:restart`. Job lỗi nằm ở `failed_jobs`, chạy lại bằng `queue:retry`.

**Tự kiểm:** test dispatch bằng `Queue::fake()` + `assertPushed`, **và** chạy worker thật (`queue:work`) ít nhất một lần, vì fake không chứng minh worker chạy được.

### 7.2 Scheduler và Artisan command [Chuẩn]

- Laravel 11+ khai báo lịch trong `routes/console.php` (`Schedule::command(...)`). Server cần cron gọi `schedule:run` mỗi phút, hoặc một container chạy `schedule:work`.
- Việc định kỳ dùng `withoutOverlapping()`. Khi chạy nhiều server thì thêm `onOneServer()` (cần cache dùng chung giữa các server).
- Command có chữ ký với tham số và tùy chọn rõ ràng (`--dry-run`, `--shop=`), trả mã thoát `self::SUCCESS`/`self::FAILURE`, xử lý theo lô và in tổng kết.

**Tự kiểm:** test command bằng `$this->artisan('...')->assertExitCode(0)`; chạy hai lần liên tiếp không tạo dữ liệu trùng.

### 7.3 Mail và notification [Chuẩn]

- Mail và notification gửi qua queue (`ShouldQueue`), sau commit. Nội dung mail viết bằng Markdown mail (Blade).
- Dùng notification khi cùng một sự kiện cần nhiều kênh, ví dụ gửi mail và lưu vào DB để hiện trong app.
- Môi trường dev dùng `MAIL_MAILER=log` hoặc Mailpit; không gửi mail thật từ local.

**Tự kiểm:** `Mail::fake()` + `assertQueued(...)`; xem mail render đúng bằng cách trả mailable từ một route tạm trong local.

### 7.4 Cache [Chuẩn]

- Chỉ cache khi đã đo thấy chậm. Khóa cache gồm mọi tham số ảnh hưởng đến kết quả và **luôn có `shop_id`**.
- Có cách làm mất hiệu lực khi dữ liệu đổi: xóa khóa khi ghi, hoặc đặt TTL ngắn có chủ đích. Cache tag chỉ dùng được với Redis/Memcached.
- Việc không được chạy song song có thể dùng khóa phân tán `Cache::lock(...)`.

**Tự kiểm:** test sửa dữ liệu rồi đọc lại phải thấy giá trị mới (không dính cache cũ).

### 7.5 File storage [Chuẩn]

- Đọc và ghi file qua `Storage::disk(...)`, không dùng đường dẫn tuyệt đối. Disk `public` cần chạy `php artisan storage:link`.
- File riêng tư: xem 6.4.
- Test bằng `Storage::fake('...')` và `UploadedFile::fake()->image(...)`.

### 7.6 HTTP client và tích hợp ngoài [Chuẩn]

- Luôn đặt `timeout`. Chỉ retry lỗi tạm. Không retry thao tác không idempotent (như tạo thanh toán) nếu đối tác không hỗ trợ khóa chống trùng.
- Không gọi ra ngoài bên trong transaction (5.3).
- Trong test dùng `Http::fake()` và `Http::preventStrayRequests()` để test không vô tình gọi mạng thật.

### 7.7 Broadcasting (chặng 9)

- Sự kiện realtime dùng `ShouldBroadcast` (đi qua queue). Kênh riêng tư phải có hàm phân quyền trong `routes/channels.php`, kiểm tra user thuộc shop.
- Realtime chỉ là thông báo; nguồn sự thật vẫn là API, nên client tải lại dữ liệu khi nhận sự kiện.

### 7.8 Localization [Chuẩn]

- Chuỗi hiển thị cho người dùng (thông báo lỗi nghiệp vụ, mail) dùng `__('...')`. Thông báo validation tiếng Việt đặt trong `lang/vi/validation.php` (chạy `php artisan lang:publish` để có bản `en` làm gốc) và trong `attributes()` của FormRequest.
- Mã lỗi cho máy đọc (`code`) giữ ổn định, không đổi theo ngôn ngữ.

## 8. Test

### 8.1 Nguyên tắc [Chuẩn]

- Test hành vi quan sát được (HTTP response, DB, job/mail được đẩy đi), không test chi tiết cài đặt; không mock ORM.
- Feature test cho endpoint; unit test cho logic thuần (enum, tính giá) không cần DB.
- Tên test nói lên hành vi, ví dụ `test_shop_cannot_read_item_of_another_shop`.
- Fake những gì đi ra ngoài: `Mail`, `Queue`, `Event`, `Http`, `Storage`, `Notification`. Thời gian dùng `travelTo`.

### 8.2 Ma trận ca tối thiểu cho mỗi endpoint

| Ca | Mong đợi |
| --- | --- |
| Happy path | 200/201/204, response đúng shape, DB đúng |
| Input sai | 422, `errors` có đúng field, DB không đổi |
| Không tồn tại hoặc đã xóa mềm | 404 |
| Tài nguyên của shop khác | 404 (hoặc 403 theo lựa chọn ở 6.3) |
| Chưa đăng nhập / không có quyền (từ chặng 4) | 401 / 403 |
| Ghi nhiều bảng | Có test rollback |
| Danh sách rỗng | 200, `data` là `[]` |

### 8.3 Giới hạn của test

- SQLite không chứng minh được khóa, đồng thời hay hành vi riêng của MySQL.
- Laravel bỏ qua CSRF khi chạy test: test xanh không có nghĩa client thật gọi được.
- `Queue::fake` không chứng minh worker chạy được.
- `RefreshDatabase` phải chạy trên DB test cô lập (hiện `phpunit.xml` dùng SQLite `:memory:`). Trước khi đổi sang MySQL phải kiểm tra lại, đừng trỏ vào DB dev.

### 8.4 Test có thật sự bắt được lỗi? (phá có chủ đích)

| Phá code | Test nào phải đỏ |
| --- | --- |
| Bỏ điều kiện hoặc quan hệ theo shop | Test IDOR |
| Bỏ `DB::transaction` | Test rollback |
| Bỏ `load('branches')` | Test kiểm tra `data.branches` |
| Đổi `>` thành `>=` trong điều kiện chồng ngày | Test biên 12/11 → 14/11 |
| Thêm `deleted_at` vào Resource | Test `assertJsonMissingPath` |
| Bỏ một rule trong FormRequest | Test 422 của field đó |

Phá mà test vẫn xanh nghĩa là test chưa đủ chặt. Phá xong nhớ hoàn tác (`git diff` phải sạch).

## 9. DevOps

### 9.1 Docker và Compose [Chuẩn]

**Khái niệm phải giải thích được bằng lời:**

- **Image** là bản đóng gói chỉ đọc (hệ điều hành, PHP, extension). **Container** là một lần chạy image đó. Xóa container không xóa image; xóa image không xóa volume.
- Mỗi lệnh trong Dockerfile tạo một **layer** có cache. Đổi một lệnh thì lệnh đó và mọi lệnh sau phải build lại. Vì vậy lệnh ít đổi (cài extension) đặt trước, lệnh hay đổi (copy code) đặt sau.
- **Build context** là thư mục được gửi cho Docker khi build. `.dockerignore` loại file khỏi context (`.env`, `.git`, `vendor`...).
- Trong mạng Compose, các service gọi nhau bằng **tên service** (`db`). `localhost` bên trong container là chính container đó. `ports: "3307:3306"` chỉ mở cổng ra máy host; container khác vẫn gọi `db:3306`.
- **Bind mount** (`./:/var/www/html`) nối thư mục trên máy vào container, sửa code là thấy ngay. **Named volume** (`db_data`) do Docker quản lý và sống độc lập với container.
- Sửa code thì không cần build lại vì đã bind mount. Sửa Dockerfile thì phải `docker compose build` (hoặc `up --build`).
- `docker compose exec app ...` chạy lệnh trong container đang chạy. `docker compose run --rm app ...` tạo container tạm rồi xóa, dùng khi app chưa chạy (ví dụ `composer install` lần đầu).

**Quy tắc:**

- Dùng image chính thức và ghim phiên bản (repo dùng `php:8.4-cli`, `mysql:8.4`), không dùng `:latest`. Nối các lệnh `RUN` bằng `&&` để build dừng khi một lệnh lỗi.
- `artisan serve` chỉ dùng cho dev. Production dùng nginx + php-fpm, tách web / worker / scheduler.
- Compose đọc giá trị từ `.env` (`${DB_PASSWORD}`), không ghi cứng mật khẩu. Trong `healthcheck`, viết `$$VAR` để biến được mở rộng bên trong container thay vì bởi Compose.
- `MYSQL_USER` không được đặt là `root` (image MySQL sẽ báo lỗi); mật khẩu root chỉ đặt qua `MYSQL_ROOT_PASSWORD`.
- MySQL chỉ đọc các biến `MYSQL_*` ở **lần khởi tạo volume đầu tiên**. Đổi biến sau đó không đổi mật khẩu đã lưu trong volume; phải đổi bằng SQL (`ALTER USER`).
- Healthcheck MySQL nên ping qua TCP (`-h 127.0.0.1`). Lúc khởi tạo, MySQL chạy một server tạm chỉ nghe qua socket, nên ping `localhost` có thể báo healthy quá sớm.
- Dữ liệu DB nằm trong named volume; `docker compose down -v` xóa volume là mất dữ liệu.
- Bind mount `./:/var/www/html` dùng cho dev (sửa code thấy ngay). Image production `COPY` code vào và có `.dockerignore` loại `.env*`, `.git`, `vendor`, `node_modules`, log.
- Container chạy nhiều process dùng process manager (supervisord), không dùng `tail -f` để giữ container sống.
- Dọn dẹp: xem `docker system df` trước. `docker system prune` không xóa volume trừ khi thêm `--volumes`, nên không bao giờ thêm `--volumes` khi volume còn dữ liệu cần giữ.
- Trên Windows: script `.sh` chạy trong container phải dùng xuống dòng LF (repo đã có `.gitattributes` với `eol=lf`), nếu không sẽ lỗi `\r`. Bind mount từ ổ Windows chậm hơn từ ổ của WSL2; nếu test chạy chậm bất thường thì nghĩ tới nguyên nhân này.

**Tự kiểm:**

- `docker compose config` hiển thị cấu hình sau khi thay biến, không có ô trống ngoài ý muốn.
- `docker compose ps` thấy `db` ở trạng thái `healthy`.
- Giải thích được vì sao trong container dùng `DB_HOST=db` còn từ máy host dùng `127.0.0.1:3307`.
- Nói được mỗi lệnh sau làm mất gì: xóa container, `docker compose down`, `docker compose down -v`, xóa image.
- Cố ý đặt sai `DB_HOST` trong `.env`, rồi tìm ra nguyên nhân chỉ bằng `docker compose ps`, `docker compose logs` và lệnh `artisan` chạy qua `exec`.

### 9.2 Env và secret [Chuẩn]

- `.env` không commit. `.env.example` đủ key nhưng **giá trị rỗng hoặc giả**.
- Secret thật đi qua kênh ngoài git (biến CI, secret manager, file trên server).
- Secret đã từng được commit thì phải **đổi (rotate)**; xóa khỏi HEAD là chưa đủ vì lịch sử git vẫn còn.
- `APP_DEBUG=false` ở mọi môi trường ngoài local.
- Trong tài liệu, log, issue: ghi "file X chứa secret", không dán giá trị.

**Tự kiểm** (chỉ in tên file, không in giá trị):

```bash
git grep -l -i -E 'password|secret|api[_-]?key|token' -- . ':!*.lock'
git log --all --oneline -- .env
```

Kết quả sẽ có nhiều file config chỉ chứa *tên* key, điều đó bình thường. Mở từng file còn lại để xem có giá trị thật không.

### 9.3 Deploy [Chuẩn]

Một quy trình deploy đúng:

1. Script bắt đầu bằng `set -euo pipefail`. Nếu có `| tee` thì bắt buộc `pipefail`, nếu không mã thoát là của `tee`.
2. Kiểm tra điều kiện trước (có `.env`, đủ biến bắt buộc).
3. Staging/production kéo image đã build sẵn, không build trên server.
4. Sao lưu DB trước thay đổi khó đảo ngược. File backup ghi **ngoài thư mục repo**, hoặc thêm `*.sql` vào `.gitignore`.
5. Chạy migrate. Migration phải tương thích với code cũ đang chạy: thêm trước, xóa sau ở lần deploy kế tiếp.
6. Cache cấu hình (`php artisan optimize`), restart worker (`queue:restart`) và php-fpm.
7. Kiểm tra sau deploy (`/up`, một request thật, log). Lỗi thì **dừng và báo**, không tự bật lại bản hỏng; chọn rõ giữa giữ maintenance hay rollback.

Bẫy bash cần biết: `set -e` và `trap ... ERR` **không** kích hoạt khi lệnh lỗi nằm trước `&&`/`||` trong một chuỗi lệnh. Bước kiểm tra cuối phải tự thoát khi lỗi:

```bash
curl -fsS http://localhost/up > /dev/null && echo "OK"                                 # curl lỗi: không có thông báo
curl -fsS http://localhost/up > /dev/null || { echo "Health check lỗi" >&2; exit 1; }  # đúng
```

**Tự kiểm:** cố ý làm migrate lỗi trên staging → script dừng đúng bước, in dòng lỗi, app không bị bật lại với bản hỏng. Cố ý làm `/up` lỗi → mã thoát khác 0.

### 9.4 CI [Chuẩn]

- Mỗi push/PR chạy `composer install`, `vendor/bin/pint --test`, `php artisan test`. Sau đó thêm phân tích tĩnh (Larastan, tăng mức dần) và lint / type-check / test / build cho frontend.
- Phiên bản PHP trong CI trùng với image Docker (repo: 8.4).
- Test cần MySQL (khóa, đồng thời) chạy với service MySQL trong CI, không nối vào DB dev hay production.

**Tự kiểm:** push một commit cố ý làm test đỏ thì CI đỏ; sửa xong thì CI xanh.

### 9.5 Vận hành [Chuẩn]

- Có endpoint `/up` (đã bật trong `bootstrap/app.php`), log tập trung, cảnh báo khi job lỗi hoặc thanh toán lỗi.
- Backup DB và file định kỳ. **Backup chưa thử restore thì chưa chứng minh được gì**: khôi phục vào một DB khác rồi đối chiếu số dòng.
- Ghi mọi giá trị hạ tầng đặc biệt (cổng, uid, đường dẫn) vào README.
- Thay đổi hạ tầng theo lát nhỏ, mỗi lát một commit, có đường lui.
- Biết các lệnh vận hành của Laravel: `down`/`up` (maintenance), `optimize`/`optimize:clear`, `queue:restart`, `storage:link`.

### 9.6 Sườn Docker cho dự án Laravel [Chuẩn]

Mục này cho biết **một bộ Docker chuẩn gồm những gì và sắp xếp ra sao**. Đây là sườn: chỗ `<...>` và các dòng chú thích là phần bạn tự điền và tự tra lệnh, không phải file chạy được để chép. Repo hiện có `.docker/Dockerfile` và một file Compose; đây là **đích đến**, đi dần qua các bước D1–D6, không làm hết một lần.

**Bố cục file:**

```text
.docker/
  php/Dockerfile              # một Dockerfile, nhiều stage (dev, prod)          D1, D6
  php/php.ini                 # upload, memory, opcache cho production            D5–D6
  nginx/default.conf          # khi chuyển sang php-fpm                           D5–D6
docker-compose.yml            # base: phần dùng chung mọi môi trường              D1–D2
docker-compose.override.yml   # dev: Compose tự đọc khi chạy không có -f          D2
docker-compose.prod.yml       # prod: chạy bằng -f docker-compose.yml -f ...prod  D6
.dockerignore                 # loại .env*, .git, vendor, node_modules, log       D2
.env.example                  # đủ key, giá trị rỗng                              D2
```

**Sườn Dockerfile** (thứ tự stage và các bước bên trong là phần chuẩn):

```dockerfile
# ---- base: dùng chung cho dev và prod, ít thay đổi nhất nên đặt đầu ----
FROM php:<phiên bản cụ thể>-fpm AS base
# 1. Gói hệ thống cần để build extension: một lệnh RUN, nối bằng &&, dọn cache apt ở cuối cùng lệnh đó
# 2. Extension PHP mà dự án thật sự cần (đối chiếu composer.json và driver đang dùng)
# 3. Composer: copy file chạy từ image composer chính thức
# 4. WORKDIR

# ---- dev: code đi vào bằng bind mount, không COPY ----
FROM base AS dev
# 5. Công cụ chỉ dev cần (ví dụ xdebug), nếu có
# 6. CMD cho dev (image -fpm vẫn có PHP CLI, nên vẫn chạy được artisan serve)

# ---- vendor: cài dependency riêng một stage để tận dụng cache ----
FROM base AS vendor
# 7. COPY chỉ composer.json và composer.lock (code đổi thì stage này vẫn dùng cache)
# 8. composer install: không cài dev, không chạy script
#    (dựa trên base nên composer kiểm tra extension đúng với môi trường chạy thật)

# ---- prod: image chạy thật ----
FROM base AS prod
# 9.  Cấu hình PHP production (php.ini, opcache)
# 10. COPY code, rồi COPY vendor từ stage vendor
# 11. Tạo autoload tối ưu
# 12. Quyền ghi cho storage/ và bootstrap/cache; chuyển sang USER không phải root
# 13. CMD php-fpm
```

**Sườn Compose:**

```yaml
# docker-compose.yml (base): chỉ phần mọi môi trường đều giống nhau
services:
  app:
    build:
      context: .
      dockerfile: .docker/php/Dockerfile
    depends_on:
      db:
        condition: service_healthy
  db:
    image: mysql:<phiên bản cụ thể>
    environment:
      # mọi giá trị đọc dạng ${TÊN} từ .env, không ghi cứng
    volumes:
      - db_data:/var/lib/mysql
    healthcheck:
      # ping qua TCP (127.0.0.1), biến viết $$TÊN
volumes:
  db_data:
```

```yaml
# docker-compose.override.yml (dev)
services:
  app:
    build:
      target: dev
    volumes:
      # bind mount code vào WORKDIR
    ports:
      # cổng app ra máy
  db:
    ports:
      # cổng DB ra máy, khác 3306 để không đụng MySQL cài sẵn trên máy
```

```yaml
# docker-compose.prod.yml (prod)
services:
  app:
    image: <registry>/<tên>:<tag>   # kéo image đã build, không build trên server
    restart: unless-stopped
    # không bind mount code
  web:        # nginx đứng trước php-fpm
  worker:     # cùng image app, command chạy queue:work
  scheduler:  # cùng image app, command chạy schedule:work; chỉ MỘT bản
  db:
    # không publish cổng ra ngoài
```

**Quyết định phải tự chọn và ghi lý do** (vào roadmap mục 17 khi tới D5–D6):

1. Dev chạy `artisan serve` hay nginx + php-fpm giống prod? Giống prod thì ít bất ngờ hơn nhưng nặng hơn.
2. nginx cần đọc file tĩnh trong `public/`: build một image nginx có sẵn `public/`, hay chia sẻ volume với app?
3. Database ở prod chạy trong Compose hay dùng dịch vụ ngoài (có sẵn backup)?
4. Migrate chạy ở đâu: một lệnh riêng mỗi lần deploy (`run --rm`). **Không** đặt migrate trong entrypoint của image, vì app, worker, scheduler cùng khởi động sẽ migrate đồng thời.

**Bẫy thường gặp:**

- Worker bị dừng giữa job khi deploy: `stop_grace_period` của service worker phải lớn hơn `timeout` lâu nhất của job.
- Chạy hai bản scheduler thì việc định kỳ chạy hai lần.
- Ghi đè cả thư mục code bằng bind mount trong prod làm mất `vendor` đã cài trong image.
- Quên ghim phiên bản ở một chỗ (`FROM`, `image:`) khiến build hôm nay khác build tuần trước.

**Sườn đạt chuẩn khi:**

- [ ] `docker compose config` (dev) và `docker compose -f docker-compose.yml -f docker-compose.prod.yml config` (prod) đều ra cấu hình đúng, không có secret ghi cứng.
- [ ] Sửa một file PHP rồi build lại image prod: các layer cài extension và vendor hiện `CACHED`.
- [ ] Dev: sửa code thấy ngay, không cần build lại.
- [ ] Image prod không có `.env`, `.git`, dev dependency; chạy non-root; `docker run --rm <image> php -m` có đủ extension.
- [ ] Xóa container app rồi tạo lại không mất dữ liệu DB.
- [ ] Worker và scheduler restart độc lập được; sau deploy đã `queue:restart`.
- [ ] Mọi image đều ghim phiên bản.
- [ ] README ghi lệnh dựng môi trường và các giá trị đặc biệt (cổng, uid).

Có thể đọc Dockerfile của Laravel Sail để so sánh cách người khác làm, nhưng không chép nguyên: Sail viết cho dev, không cho production.

## 10. Bằng chứng: thế nào là "đã đúng"

| Muốn khẳng định | Bằng chứng đủ | Chưa đủ |
| --- | --- | --- |
| Endpoint chạy | Feature test xanh **và** gọi thật bằng curl/Postman | Chỉ có test (test bỏ qua CSRF) |
| Không rò dữ liệu giữa shop | Test IDOR: 404/403 và dữ liệu không đổi | "Đã có where shop_id" |
| Transaction đúng | Test ép bước sau lỗi, DB không còn dữ liệu của bước trước | Đọc thấy có `DB::transaction` |
| Không N+1 | Số truy vấn không đổi khi tăng dữ liệu; `preventLazyLoading` bật | Trang chạy nhanh trên máy |
| Chống trùng khi đồng thời | Hai kết nối MySQL: một bên bị chờ, hoặc một bên lỗi unique | Test SQLite xanh |
| Schema đúng | `SHOW CREATE TABLE` trên MySQL thật | Đọc migration |
| Test đủ chặt | Phá có chủ đích thì test đỏ (8.4) | Coverage cao |
| Job chạy được | Worker thật xử lý xong, job không nằm trong `failed_jobs` | `Queue::fake` xanh |
| Backup dùng được | Restore vào DB khác, số dòng khớp | Có file `.sql` |
| Deploy an toàn | Cố ý gây lỗi giữa chừng, script dừng đúng chỗ | Deploy thành công một lần |
| Code sạch | `pint --test` sạch | "Nhìn ổn" |

Ghi ngày và môi trường (PHP bản nào, SQLite hay MySQL) cạnh mọi kết quả để biết nó còn đáng tin hay không.

Lệnh lấy bằng chứng HTTP. Chạy trong Git Bash; trong PowerShell 5.1, `curl` là alias của `Invoke-WebRequest` nên phải gõ `curl.exe`:

```bash
curl -i -H "Accept: application/json" http://localhost:8000/api/shop/999
curl -i -X POST http://localhost:8000/api/shop \
  -H "Accept: application/json" -H "Content-Type: application/json" \
  -d '{"shop_name":"A","owner_name":"B","owner_phone":"0900000000","branches":[{"branch_name":"X","address":"Y"}]}'
```

Thứ tự debug khi gặp lỗi: xem advice.md mục 5.

## 11. Checklist

### 11.1 Trước khi commit

- [ ] Có đủ các ca ở 8.2 cho phần vừa làm.
- [ ] `php artisan test` qua, `vendor/bin/pint --test` sạch; ghi ngày và môi trường đã chạy.
- [ ] Không còn `dd()`, `dump()`, comment thừa, import không dùng.
- [ ] Không có secret trong diff; tài liệu (roadmap, advice) đổi cùng commit nếu quyết định thay đổi.
- [ ] Commit theo tính năng (`feat:`, `fix:`, `style:`, `chore:`, `docs:`); commit chỉ sửa style tách riêng.

```bash
# Chỉ xét dòng được thêm (+) và chỉ khớp lời gọi hàm đứng riêng (không khớp "add(")
git diff --staged | grep -nE '^\+.*\b(dd|dump|var_dump)\(' && echo "Còn debug code!"
```

### 11.2 Review code (theo thứ tự rủi ro)

1. Phạm vi shop: có đường nào tìm theo id mà không đi qua shop?
2. Mass assignment: đưa `all()` vào model; `shop_id` nằm trong `$fillable`; field thiếu rule.
3. Transaction: ghi nhiều bảng có bọc không; có IO bên trong không; có test rollback không?
4. Kiểm tra rồi mới ghi mà không có khóa hoặc unique index.
5. Lỗi bị nuốt thành rỗng hoặc `success`; có `catch {}` rỗng.
6. N+1, danh sách không phân trang, Resource trả thừa field.
7. Job: dùng connection mặc định, retry mọi loại lỗi, `retry_after` nhỏ hơn timeout, không idempotent.
8. Test: đủ ca chưa; test có thể xanh dù code sai không?

### 11.3 Trước khi deploy

- [ ] CI xanh; đã đọc migration và có kế hoạch rollback.
- [ ] Đã backup và đã từng thử restore.
- [ ] Biến môi trường mới đã có trên server; `.env.example` đã thêm key (không có giá trị thật).
- [ ] Biết cách kiểm tra sau deploy và cách quay lại bản trước.

## 12. Nhờ giúp và quy ước repo

### 12.1 Khi nhờ người khác hoặc AI

```text
Mục tiêu: <một câu, ví dụ "thêm GET /api/shops/{shop}/rental-items">
Đã làm: <file đã sửa, cách đã thử>
Input: <request mẫu>
Mong đợi: <response/mã mong đợi>
Thực tế: <response/mã thực tế, dòng log đầu tiên thuộc app/>
Ràng buộc: tôi muốn tự viết phần <X>; gợi ý tăng dần, đừng viết hộ cả đoạn.
```

Khi nhờ review: gửi file hiện tại và yêu cầu tách ba loại: lỗi làm sai hành vi, rủi ro, lựa chọn phong cách.

### 12.2 Quy ước của repo này [Quy ước]

- Model mỏng; quan hệ, scope và logic ghi nằm trong trait `app/Models/Logic/XLogic.php`.
- FormRequest đặt tên theo hành động; Resource khai báo rõ field; controller có return type.
- Tên cột và bảng theo mẫu hiện có: `shop_name`, `owner_phone`, `shop_branches`, `rental_items`; tiền là số nguyên VND.
- Test hành vi theo tính năng (`ShopIndexTest`, `ShopStoreTest`, `ShopSyncTest`), dùng `RefreshDatabase` trên DB test cô lập.
- Commit nhỏ theo tính năng; cập nhật nhật ký mục 20 của roadmap sau mỗi buổi có kết quả.
