# Lộ trình tự học Laravel 12 + Vue 3: từ Beginner đến Advanced

> Học đến đâu, code đến đó. Mỗi bước có bài tập làm được ngay, tiêu chí hoàn thành và câu hỏi tự kiểm tra kèm đáp án.
> Tài liệu này được viết để **học một mình ở nhà, không cần AI**.

---

## Mục lục

0. [Cách dùng tài liệu này](#0-cách-dùng-tài-liệu-này)
1. [Dự án xuyên suốt: AniTrack](#1-dự-án-xuyên-suốt-anitrack)
2. [Bộ công cụ tự học không cần AI](#2-bộ-công-cụ-tự-học-không-cần-ai)
3. [Giai đoạn 1 – Tư duy lập trình & PHP hiện đại](#giai-đoạn-1--tư-duy-lập-trình--php-hiện-đại-beginner)
4. [Giai đoạn 2 – Laravel cơ bản: CRUD API đầu tiên](#giai-đoạn-2--laravel-cơ-bản-crud-api-đầu-tiên-beginner)
5. [Giai đoạn 3 – Vue 3 + TypeScript cơ bản](#giai-đoạn-3--vue-3--typescript-cơ-bản-beginner)
6. [Giai đoạn 4 – Database & quan hệ dữ liệu](#giai-đoạn-4--database--quan-hệ-dữ-liệu-intermediate)
7. [Giai đoạn 5 – OOP & SOLID trong nghiệp vụ thật](#giai-đoạn-5--oop--solid-trong-nghiệp-vụ-thật-intermediate)
8. [Giai đoạn 6 – Clean Code, Testing & Refactor](#giai-đoạn-6--clean-code-testing--refactor-intermediate)
9. [Giai đoạn 7 – Kiến trúc Vue](#giai-đoạn-7--kiến-trúc-vue-intermediate--advanced)
10. [Giai đoạn 8 – Design Patterns dùng đúng chỗ](#giai-đoạn-8--design-patterns-dùng-đúng-chỗ-advanced)
11. [Giai đoạn 9 – Kiến trúc Laravel nâng cao](#giai-đoạn-9--kiến-trúc-laravel-nâng-cao-advanced)
12. [Giai đoạn 10 – Tư duy thiết kế hệ thống](#giai-đoạn-10--tư-duy-thiết-kế-hệ-thống-advanced)
13. [Bài học từ codebase thực tế (ShopManager-Renew)](#bài-học-từ-codebase-thực-tế-shopmanager-renew)
14. [Checklist tự review code](#checklist-tự-review-code)
15. [Nhật ký học tập & lịch học](#nhật-ký-học-tập--lịch-học)

---

## 0. Cách dùng tài liệu này

### Mỗi bài học có cùng một cấu trúc

| Phần                | Bạn làm gì                                                     |
| ------------------- | -------------------------------------------------------------- |
| **Kiến thức**       | Đọc phần giải thích ngắn + link tài liệu chính thức.           |
| **Cách tư duy**     | Câu hỏi bạn phải tự trả lời _trước khi_ code.                  |
| **Nguyên tắc code** | Luật ngắn, áp dụng ngay.                                       |
| **Ví dụ**           | Code mẫu đọc hiểu được, **gõ lại bằng tay**, không copy-paste. |
| **Bài tập**         | Việc cụ thể trong dự án AniTrack, có tiêu chí hoàn thành.      |
| **Tự kiểm tra**     | Câu hỏi + đáp án ẩn. Trả lời ra giấy trước rồi mới mở đáp án.  |

### Quy tắc vàng

1. **Gõ tay, không copy.** Gõ lại làm não phải xử lý từng dòng. Copy thì không.
2. **Làm xong bài tập mới sang bài tiếp.** "Xong" nghĩa là đạt hết tiêu chí hoàn thành, không phải "chạy được là được".
3. **Mỗi giai đoạn chạy độc lập.** Giai đoạn N chỉ dùng kiến thức từ giai đoạn 1..N. Không cần biết giai đoạn sau.
4. **Bí 20 phút → làm theo [Thang gỡ bí](#22-thang-gỡ-bí-khi-không-có-ai).** Không bỏ cuộc, không đoán mò.
5. **Commit sau mỗi bài tập** với message nói rõ đã làm gì. Lịch sử git chính là bằng chứng bạn tiến bộ.

### Thời lượng tham khảo

| Giai đoạn | Cấp độ                  | Thời gian (45–60 phút/ngày, 5 ngày/tuần) |
| --------- | ----------------------- | ---------------------------------------- |
| 1–3       | Beginner                | 5–7 tuần                                 |
| 4–6       | Intermediate            | 6–8 tuần                                 |
| 7–8       | Intermediate → Advanced | 4–6 tuần                                 |
| 9–10      | Advanced                | 6–8 tuần                                 |

Chậm hơn không sao. Hiểu sâu quan trọng hơn chạy theo lịch.

---

## 1. Dự án xuyên suốt: AniTrack

### Vì sao chọn dự án này

**AniTrack** là web theo dõi anime cá nhân + cộng đồng: danh mục anime, thể loại, studio, tập phim, nhân vật; thư viện cá nhân (đang xem / đã xem / bỏ dở…), chấm điểm, review, bảng xếp hạng, thông báo khi có tập mới.

- **Bạn thích anime nên có động lực làm lâu dài.** Bạn cũng tự là người dùng của sản phẩm, tự biết tính năng nào hay, chỗ nào khó dùng.
- **Có dữ liệu thật miễn phí:** [Jikan API](https://docs.api.jikan.moe/) (dữ liệu MyAnimeList, không cần API key) để import anime thật, không phải tự gõ dữ liệu giả.
- **Bắt đầu cực đơn giản** (CRUD anime) và **mở rộng gần như vô hạn**: quan hệ nhiều-nhiều, trạng thái xem, chấm điểm có công thức, xếp hạng, quyền riêng tư, kiểm duyệt review, đồng bộ dữ liệu ngoài, thông báo, realtime.
- **Mỗi tính năng kéo theo đúng một kiến thức mới**, nên học đến đâu dùng đến đó.

### Bản đồ tính năng theo giai đoạn

| Giai đoạn | Tính năng AniTrack                              | Kiến thức chính                                     |
| --------- | ----------------------------------------------- | --------------------------------------------------- |
| 1         | Thư viện tính điểm & tiến độ xem (PHP thuần)    | Tư duy, PHP 8, class, enum, test                    |
| 2         | API danh mục anime (CRUD)                       | Route, Controller, Model, Validation, Resource      |
| 3         | Màn hình danh sách + form anime                 | Vue SFC, props/emits, TS, router, gọi API           |
| 4         | Thể loại, studio, tập phim, nhân vật            | Migration, 1-n, n-n, N+1, index, transaction        |
| 5         | Thư viện cá nhân + chấm điểm                    | OOP, SOLID, Enum trạng thái, Action class, lock     |
| 6         | Viết test, refactor toàn bộ                     | Clean code, Pest, Vitest, refactor an toàn          |
| 7         | Đăng nhập, layout, bộ lọc trên URL              | Pinia, composable, kiến trúc thư mục Vue            |
| 8         | Bảng xếp hạng, thông báo tập mới, import Jikan  | Strategy, Observer, Pipeline, DTO, Factory, Adapter |
| 9         | Review, kiểm duyệt, riêng tư, đồng bộ hằng ngày | Policy, Scope, Event, Job, Scheduler, Cache         |
| 10        | Lịch sử xếp hạng theo mùa, triển khai           | System design, Docker, CI, đo đạc hiệu năng         |

### Cấu trúc repo

```
anitrack/
├── backend/     # Laravel 12 (API thuần, JSON)
├── frontend/    # Vue 3 + Vite + TypeScript + Pinia + Vue Router
├── docs/        # Ghi chú thiết kế, sơ đồ ERD, quyết định kỹ thuật
└── LEARNING_LOG.md
```

**Tại sao tách backend/frontend thay vì dùng Inertia hay starter kit?** Vì bạn cần _thấy rõ_ ranh giới: frontend gửi request gì, backend trả gì, lỗi đi đường nào. Khi đã hiểu rõ, dùng Inertia sau chỉ mất một buổi.

---

## 2. Bộ công cụ tự học không cần AI

### 2.1 Tài liệu chính thức (bookmark hết)

| Chủ đề           | Link                                                    |
| ---------------- | ------------------------------------------------------- |
| Laravel 12       | https://laravel.com/docs/12.x                           |
| PHP              | https://www.php.net/manual/en/                          |
| Vue 3            | https://vuejs.org/guide/introduction.html               |
| Vue Router       | https://router.vuejs.org/                               |
| Pinia            | https://pinia.vuejs.org/                                |
| TypeScript       | https://www.typescriptlang.org/docs/handbook/intro.html |
| Pest (test PHP)  | https://pestphp.com/docs                                |
| Vitest (test JS) | https://vitest.dev/guide/                               |
| MySQL            | https://dev.mysql.com/doc/refman/8.0/en/                |
| Jikan API        | https://docs.api.jikan.moe/                             |

**Học offline:** vào https://devdocs.io, bật _Offline data_ cho PHP, Laravel, Vue, TypeScript, JavaScript, MySQL. Mất mạng vẫn tra được.

**Đọc source framework:** mọi thứ Laravel làm đều nằm trong `backend/vendor/laravel/framework/src`. Ctrl+Click vào một hàm trong IDE để nhảy tới code thật. Đây là "AI" đáng tin nhất, vì nó _chính là_ thứ đang chạy.

### 2.2 Thang gỡ bí khi không có AI

Làm **theo thứ tự**, mỗi bậc tối đa 10–15 phút:

1. **Đọc hết thông báo lỗi.** Dòng đầu tiên nói _cái gì_ sai. Stack trace nói _ở đâu_. Tìm dòng đầu tiên trong stack trace thuộc code của bạn (không phải `vendor/`).
2. **Phát biểu vấn đề thành 1 câu:** "Tôi mong đợi X, nhưng thực tế là Y." Viết ra giấy. Nhiều khi viết xong là thấy lỗi.
3. **Thu hẹp phạm vi:** request có tới controller không? Dữ liệu vào đúng không? Query đúng không? Response đúng không? Kiểm tra _từng chặng_, đừng đoán.
4. **In ra giá trị thật:** `dd()`, `dump()`, `logger()`, `console.log()`, Vue DevTools, tab Network.
5. **Thử trong môi trường nhỏ:** `php artisan tinker` cho PHP, file test riêng, hoặc component tối giản.
6. **Đọc tài liệu chính thức** đúng mục liên quan (không đọc blog trước).
7. **Đọc source framework** trong `vendor/` hoặc `node_modules/`.
8. **Ghi câu hỏi vào LEARNING_LOG**, chuyển sang việc khác, quay lại hôm sau. Não giải quyết rất nhiều vấn đề trong lúc nghỉ.

### 2.3 Lệnh và công cụ phải thuộc

| Mục đích              | Công cụ                                                                                      |
| --------------------- | -------------------------------------------------------------------------------------------- |
| Xem tất cả route      | `php artisan route:list --path=api`                                                          |
| Thử code PHP/Eloquent | `php artisan tinker`                                                                         |
| Xem SQL thật          | `Anime::where(...)->toRawSql()` hoặc `DB::listen(fn ($q) => logger($q->sql, $q->bindings));` |
| Xem log               | `storage/logs/laravel.log`                                                                   |
| Debug request         | Laravel Telescope (`composer require laravel/telescope --dev`)                               |
| Xem state Vue         | Vue DevTools (extension trình duyệt)                                                         |
| Xem request/response  | DevTools → tab **Network** → chọn request → _Payload_ / _Response_                           |
| Kiểm tra type TS      | `npx vue-tsc --noEmit`                                                                       |
| Chạy test             | `php artisan test`, `npx vitest`                                                             |

---

## Giai đoạn 1 – Tư duy lập trình & PHP hiện đại (Beginner)

**Mục tiêu:** viết được một module PHP thuần có class, type rõ ràng, có test, _chưa cần Laravel_.

### 1.1 Tư duy: chia bài toán trước khi code

**Kiến thức.** Code chỉ là bước cuối. Trước đó phải trả lời 4 câu:

1. **Input** là gì? (kiểu dữ liệu, giới hạn)
2. **Output** là gì?
3. **Quy tắc** biến input thành output là gì?
4. **Trường hợp đặc biệt** nào? (rỗng, âm, quá lớn, trùng, không tồn tại)

**Ví dụ áp dụng.** Bài toán: _tính điểm trung bình của một anime và điểm xếp hạng công bằng._

| Câu hỏi  | Trả lời                                                                                                                                                        |
| -------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Input    | Danh sách điểm người dùng chấm (số nguyên 1–10).                                                                                                               |
| Output   | Số lượt chấm, điểm trung bình (2 chữ số thập phân), điểm xếp hạng có trọng số.                                                                                 |
| Quy tắc  | Trung bình = tổng / số lượt. Điểm xếp hạng dùng công thức trọng số (bên dưới) để anime chỉ có 2 lượt chấm 10 điểm không vượt anime có 10.000 lượt chấm 9 điểm. |
| Đặc biệt | Chưa ai chấm → trung bình là `null` (không phải 0!). Điểm 0 hoặc 11 → lỗi.                                                                                     |

**Công thức điểm trọng số** (kiểu IMDb dùng):

```
WR = (v / (v + m)) × R + (m / (v + m)) × C

v = số lượt chấm của anime       R = điểm trung bình của anime
m = số lượt tối thiểu (ví dụ 50) C = điểm trung bình của toàn bộ anime
```

Ít lượt chấm → kết quả bị kéo về gần C. Nhiều lượt → kết quả gần R. Hãy thử tính tay vài ví dụ ra giấy trước khi code.

**Nguyên tắc code.**

- **"Chưa có dữ liệu" khác "bằng 0".** Dùng `null` để biểu diễn "chưa có".
- **Không so sánh số thực bằng `==`**: `0.1 + 0.2 == 0.3` là `false`. Làm tròn ở **một chỗ duy nhất**, so sánh trong test bằng sai số cho phép.
- Hàm nhận tham số có kiểu, trả về có kiểu. Một hàm làm một việc.

### 1.2 PHP 8 cần nắm

**Kiến thức** (đọc mục tương ứng trong php.net):

- `declare(strict_types=1);`: bắt PHP không tự ép kiểu.
- Kiểu dữ liệu: `int`, `float`, `string`, `bool`, `array`, `?int` (nullable), union `int|string`.
- **Constructor promotion**, `readonly` property.
- `enum` (PHP 8.1).
- `match` thay cho `switch`.
- Named arguments, nullsafe `?->`, spread `...`.
- Closure và arrow function `fn ($x) => $x * 2`.
- Hàm mảng: `array_map`, `array_filter`, `array_reduce`, `array_sum`.

**Ví dụ.**

```php
<?php

declare(strict_types=1);

namespace AniTrack\Rating;

final class Score
{
    public function __construct(public readonly int $value)
    {
        if ($value < 1 || $value > 10) {
            throw new \InvalidArgumentException('Điểm phải từ 1 đến 10.');
        }
    }
}
```

```php
final class RatingSummary
{
    public function __construct(
        public readonly int $votes,
        public readonly ?float $average, // null = chưa ai chấm
    ) {}
}

final class RatingCalculator
{
    /** @param list<Score> $scores */
    public function summarize(array $scores): RatingSummary
    {
        $votes = count($scores);
        if ($votes === 0) {
            return new RatingSummary(0, null);
        }

        $sum = array_sum(array_map(fn (Score $s) => $s->value, $scores));

        return new RatingSummary($votes, round($sum / $votes, 2));
    }

    public function weighted(RatingSummary $anime, float $globalAverage, int $minVotes = 50): ?float
    {
        if ($anime->average === null) {
            return null;
        }

        $v = $anime->votes;
        $m = $minVotes;

        return round(($v / ($v + $m)) * $anime->average + ($m / ($v + $m)) * $globalAverage, 2);
    }
}
```

**Cách tư duy khi đọc ví dụ.** Tự hỏi: _tại sao kiểm tra điểm ở constructor của `Score` mà không ở `summarize`?_ Vì một `Score` sai thì không nên tồn tại. Validate càng sớm càng tốt; mọi chỗ nhận `Score` đều yên tâm nó hợp lệ.

### 1.3 Test đầu tiên với Pest

Test là cách **tự kiểm chứng không cần ai**. Nó trả lời "code có đúng không?" thay bạn.

```bash
mkdir anitrack-core && cd anitrack-core
composer init        # trả lời các câu hỏi, autoload PSR-4: "AniTrack\\": "src/"
composer require pestphp/pest --dev
./vendor/bin/pest --init
```

```php
// tests/RatingCalculatorTest.php
use AniTrack\Rating\{RatingCalculator, RatingSummary, Score};

it('tính điểm trung bình', function () {
    $summary = (new RatingCalculator())->summarize([new Score(8), new Score(9), new Score(10)]);

    expect($summary->votes)->toBe(3)
        ->and($summary->average)->toBe(9.0);
});

it('trả null khi chưa ai chấm', function () {
    expect((new RatingCalculator())->summarize([])->average)->toBeNull();
});

it('kéo điểm về trung bình chung khi ít lượt chấm', function () {
    $weighted = (new RatingCalculator())->weighted(new RatingSummary(3, 9.0), globalAverage: 7.0);

    // (3/53)×9 + (50/53)×7 ≈ 7.11 → tính tay ra giấy để kiểm tra
    expect($weighted)->toEqualWithDelta(7.11, 0.001);
});

it('không cho điểm 11', function () {
    new Score(11);
})->throws(InvalidArgumentException::class);
```

### Bài tập giai đoạn 1

> **Bài 1.A – Thư viện lõi `anitrack-core`**
>
> 1. Tự viết `Score`, `RatingSummary`, `RatingCalculator`.
> 2. Viết `WatchProgress` (readonly): `watched` (số tập đã xem), `total` (`?int`, null = anime đang chiếu chưa biết tổng số tập). Có `percent(): ?int`, `isCompleted(): bool`. Không cho `watched` âm hoặc vượt `total`.
> 3. Viết `enum Season: string { case Winter = 'winter'; ... }` với hàm tĩnh `Season::fromDate(DateTimeInterface $d): self` (tháng 1–3 Winter, 4–6 Spring, 7–9 Summer, 10–12 Fall) và `dateRange(int $year): array` trả `[ngày đầu, ngày cuối]`.
> 4. Viết tối thiểu **12 test**, gồm: không ai chấm, 1 lượt, nhiều lượt, trọng số với v rất lớn (gần R), v = 0; tiến độ khi `total` null; xem đủ tập; vượt tập (lỗi); ngày 31/3 và 1/4 (biên của mùa).
>
> **Hoàn thành khi:** `./vendor/bin/pest` xanh hết; không test nào so sánh float bằng `toBe` trừ khi kết quả chắc chắn chính xác; giải thích được bằng lời mỗi dòng.

> **Bài 1.B – Kata tư duy** (mỗi bài ≤ 30 phút, viết test trước):
>
> - `formatWatchTime(int $episodes, int $minutesPerEpisode): string`: 12 tập × 24 phút → `"4 giờ 48 phút"`.
> - `slugify(string $title): string`: `"Shingeki no Kyojin: The Final Season"` → `"shingeki-no-kyojin-the-final-season"`.
> - `parseEpisodeRange(string $raw): array`: `"1-3, 5, 7-8"` → `[1, 2, 3, 5, 7, 8]`; chuỗi sai format → lỗi.

### Tự kiểm tra

<details><summary>1. Vì sao không so sánh số thực bằng `==` (hay `toBe` trong test)?</summary>

Số thực được lưu nhị phân gần đúng: `0.1 + 0.2` ra `0.30000000000000004`. Hai cách tính "cùng giá trị" có thể lệch ở chữ số rất nhỏ. So sánh bằng sai số cho phép (`toEqualWithDelta`) hoặc làm tròn trước ở một chỗ cố định.

</details>

<details><summary>2. Vì sao điểm trung bình khi chưa ai chấm là `null` chứ không phải `0`?</summary>

`0` là một giá trị thật ("rất dở"), còn "chưa ai chấm" là _không có dữ liệu_. Trả 0 sẽ làm anime mới bị xếp cuối bảng và hiển thị sai "0 điểm".

</details>

<details><summary>3. `strict_types=1` thay đổi gì khi gọi `new Score('8')`?</summary>

Không có strict types: PHP tự ép `'8'` thành `8` và chạy tiếp. Có strict types: ném `TypeError` ngay. Lỗi lộ ra sớm, đúng chỗ.

</details>

---

## Giai đoạn 2 – Laravel cơ bản: CRUD API đầu tiên (Beginner)

**Mục tiêu:** tự tạo API `anime` đầy đủ: danh sách (phân trang, tìm kiếm), xem, tạo, sửa, xóa, có validation và test.

### 2.1 Vòng đời một request (phải vẽ ra được)

```
HTTP request
  → public/index.php
  → bootstrap/app.php (đăng ký route, middleware, exception handling)
  → Middleware (ví dụ: auth, throttle)
  → Route (routes/api.php) khớp URL + method
  → FormRequest (authorize + validate)  ← sai thì trả 422 luôn, controller không chạy
  → Controller method
  → Model / Eloquent → SQL → Database
  → API Resource (định dạng JSON)
  → HTTP response
```

**Bài tập nhỏ:** in sơ đồ này ra, dán cạnh màn hình. Mỗi lần debug, hỏi: _"Request đang chết ở chặng nào?"_

### 2.2 Khởi tạo

```bash
composer create-project laravel/laravel backend
cd backend
php artisan install:api          # cài Sanctum + tạo routes/api.php
php artisan make:model Anime -mfc --api --requests
# -m migration, -f factory, -c controller, --api bỏ create/edit, --requests tạo FormRequest
```

Cấu hình `.env` dùng MySQL (hoặc SQLite khi mới học: `DB_CONNECTION=sqlite`).

> **Bẫy đặt tên:** tiếng Anh số nhiều của "anime" vẫn là "anime". Laravel tự đoán tên bảng từ tên model. Mở `php artisan tinker` gõ `Str::plural('anime')` để xem nó đoán gì. Để chắc chắn, khai báo rõ `protected $table = 'anime';` và đặt cùng tên trong migration.

### 2.3 Từng thành phần

**Enum: dùng lại kiến thức giai đoạn 1.**

```php
enum AnimeType: string
{
    case Tv = 'TV';
    case Movie = 'Movie';
    case Ova = 'OVA';
    case Ona = 'ONA';
    case Special = 'Special';
}

enum AiringStatus: string
{
    case Upcoming = 'upcoming';
    case Airing = 'airing';
    case Finished = 'finished';
}
```

**Migration: thiết kế bảng.**

```php
Schema::create('anime', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('title_japanese')->nullable();
    $table->string('slug')->unique();
    $table->text('synopsis')->nullable();
    $table->string('type', 20);
    $table->string('status', 20)->index();
    $table->unsignedSmallInteger('episodes_count')->nullable(); // null = chưa biết (đang chiếu)
    $table->date('aired_from')->nullable()->index();
    $table->string('image_url')->nullable();
    $table->timestamps();
});
```

**Model.**

```php
class Anime extends Model
{
    use HasFactory;

    protected $table = 'anime';

    protected $fillable = [
        'title', 'title_japanese', 'slug', 'synopsis', 'type',
        'status', 'episodes_count', 'aired_from', 'image_url',
    ];

    protected function casts(): array
    {
        return [
            'type'       => AnimeType::class,
            'status'     => AiringStatus::class,
            'aired_from' => 'date',
        ];
    }
}
```

**FormRequest: validation tách khỏi controller.**

```php
class StoreAnimeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // giai đoạn 9 sẽ thay bằng Policy
    }

    public function rules(): array
    {
        return [
            'title'          => ['required', 'string', 'max:255'],
            'title_japanese' => ['nullable', 'string', 'max:255'],
            'slug'           => ['required', 'string', 'max:255', 'alpha_dash', 'unique:anime,slug'],
            'synopsis'       => ['nullable', 'string', 'max:5000'],
            'type'           => ['required', Rule::enum(AnimeType::class)],
            'status'         => ['required', Rule::enum(AiringStatus::class)],
            'episodes_count' => ['nullable', 'integer', 'min:1'],
            'aired_from'     => ['nullable', 'date'],
            'image_url'      => ['nullable', 'url'],
        ];
    }
}
```

**API Resource: kiểm soát JSON trả ra.**

```php
class AnimeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'title_japanese' => $this->title_japanese,
            'slug'           => $this->slug,
            'synopsis'       => $this->synopsis,
            'type'           => $this->type,
            'status'         => $this->status,
            'episodes_count' => $this->episodes_count,
            'aired_from'     => $this->aired_from?->toDateString(),
            'image_url'      => $this->image_url,
            'created_at'     => $this->created_at?->toIso8601String(),
        ];
    }
}
```

**Controller: mỏng, chỉ điều phối.**

```php
class AnimeController extends Controller
{
    public function index(Request $request)
    {
        $anime = Anime::query()
            ->when($request->string('q')->trim()->value(), function ($query, string $q) {
                $query->where(fn ($w) => $w->where('title', 'like', "%{$q}%")
                    ->orWhere('title_japanese', 'like', "%{$q}%"));
            })
            ->latest()
            ->paginate($request->integer('per_page', 20));

        return AnimeResource::collection($anime);
    }

    public function store(StoreAnimeRequest $request)
    {
        $anime = Anime::create($request->validated());

        return AnimeResource::make($anime)->response()->setStatusCode(201);
    }

    public function show(Anime $anime)
    {
        return AnimeResource::make($anime);
    }

    public function update(UpdateAnimeRequest $request, Anime $anime)
    {
        $anime->update($request->validated());

        return AnimeResource::make($anime);
    }

    public function destroy(Anime $anime)
    {
        $anime->delete();

        return response()->noContent();
    }
}
```

```php
// routes/api.php
Route::apiResource('anime', AnimeController::class);
// Chạy `php artisan route:list --path=api` để xem tên tham số route Laravel sinh ra
// và chắc chắn nó khớp với tên biến `$anime` trong controller.
```

**Nguyên tắc code.**

- Luôn dùng `$request->validated()`, **không bao giờ** `$request->all()` để ghi DB.
- Controller không chứa logic nghiệp vụ. Nếu một method dài quá ~20 dòng, logic đang nằm sai chỗ.
- Một controller = một resource. Không dùng tham số kiểu `page_name` để "rẽ nhánh" trong 1 method.
- Trả đúng HTTP status: 200 đọc/sửa, 201 tạo, 204 xóa, 404 không thấy, 422 sai validate.

**Mẹo thực tế.**

- `Anime $anime` trong tham số gọi là **Route Model Binding**: Laravel tự tìm theo `id`, không thấy thì trả 404. Không cần tự `findOrFail`.
- Gọi thử API bằng file `.http` (extension REST Client của VS Code) hoặc Postman. Lưu file `.http` vào repo để làm "tài liệu sống".

```http
### Thêm anime
POST http://localhost:8000/api/anime
Accept: application/json
Content-Type: application/json

{
  "title": "Sousou no Frieren",
  "slug": "sousou-no-frieren",
  "type": "TV",
  "status": "finished",
  "episodes_count": 28
}
```

> **Luôn gửi header `Accept: application/json`.** Thiếu nó, lỗi validate sẽ redirect thay vì trả JSON 422. Đây là lỗi kinh điển người mới hay mất cả buổi.

### 2.4 Feature test

```php
// tests/Feature/AnimeApiTest.php
use App\Models\Anime;
use function Pest\Laravel\{getJson, postJson};

// Nhớ bật RefreshDatabase trong tests/Pest.php

it('thêm anime', function () {
    postJson('/api/anime', [
        'title' => 'Sousou no Frieren', 'slug' => 'sousou-no-frieren',
        'type' => 'TV', 'status' => 'finished', 'episodes_count' => 28,
    ])
        ->assertCreated()
        ->assertJsonPath('data.title', 'Sousou no Frieren');

    expect(Anime::count())->toBe(1);
});

it('không nhận type không hợp lệ', function () {
    postJson('/api/anime', ['title' => 'X', 'slug' => 'x', 'type' => 'Manga', 'status' => 'airing'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['type']);
});

it('tìm theo tên', function () {
    Anime::factory()->create(['title' => 'Sousou no Frieren']);
    Anime::factory()->create(['title' => 'Bocchi the Rock!']);

    getJson('/api/anime?q=Frieren')
        ->assertOk()
        ->assertJsonCount(1, 'data');
});
```

### Bài tập giai đoạn 2

> **Bài 2.A – Anime API hoàn chỉnh**
>
> 1. Làm đủ 5 endpoint như trên, _gõ tay_.
> 2. `UpdateAnimeRequest`: slug unique nhưng **bỏ qua chính anime đang sửa**. Gợi ý: tìm `Rule::unique(...)->ignore(...)` trong docs mục _Validation_.
> 3. Nếu không gửi `slug`, tự sinh từ `title` bằng hàm `slugify` của bài 1.B. Gợi ý: `prepareForValidation()` trong FormRequest.
> 4. Quy tắc chéo giữa các field: anime `finished` **bắt buộc** có `episodes_count`; anime `upcoming` thì `aired_from` (nếu có) phải ở tương lai. Gợi ý: `Rule::requiredIf`, hoặc hàm `after()` trong FormRequest.
> 5. `AnimeFactory` sinh dữ liệu giả; `DatabaseSeeder` tạo 50 anime.
> 6. Viết tối thiểu 8 feature test, bao gồm 404 và 422.
>
> **Hoàn thành khi:** test xanh; file `.http` gọi được cả 5 endpoint; vẽ lại được vòng đời request cho `POST /api/anime` mà không nhìn tài liệu.

### Tự kiểm tra

<details><summary>1. Gửi thêm field `id: 999` khi tạo anime thì sao?</summary>

`validated()` chỉ chứa field có trong `rules()` nên `id` bị bỏ qua. Thêm vào đó `$fillable` cũng không có `id`. Hai lớp bảo vệ chống mass assignment.

</details>

<details><summary>2. Validate sai thì code trong `store()` có chạy không?</summary>

Không. FormRequest được resolve _trước_ khi gọi method; validate fail sẽ ném `ValidationException`, Laravel chuyển thành response 422.

</details>

<details><summary>3. Vì sao cần API Resource thay vì `return $anime;`?</summary>

Để kiểm soát chính xác JSON: ẩn cột nội bộ, đổi format ngày, giữ API ổn định dù đổi tên cột DB. Frontend phụ thuộc vào Resource chứ không phụ thuộc vào cấu trúc bảng.

</details>

---

## Giai đoạn 3 – Vue 3 + TypeScript cơ bản (Beginner)

**Mục tiêu:** tự xây màn hình danh sách anime (dạng lưới thẻ có ảnh) + form thêm/sửa, gọi API ở giai đoạn 2.

### 3.1 Khởi tạo

```bash
npm create vue@latest frontend
# chọn: TypeScript ✔, Vue Router ✔, Pinia ✔, Vitest ✔, ESLint ✔, Prettier ✔
cd frontend && npm install && npm i axios
```

`.env`: `VITE_API_URL=http://localhost:8000/api`

Backend cần cho phép CORS: `php artisan config:publish cors`, kiểm tra `allowed_origins` có `http://localhost:5173`.

### 3.2 Kiến thức cốt lõi

| Khái niệm                  | Hiểu một câu                                                       |
| -------------------------- | ------------------------------------------------------------------ |
| `<script setup lang="ts">` | Cách viết component Vue 3 gọn nhất.                                |
| `ref()`                    | Giá trị phản ứng (reactive); đọc/ghi qua `.value` trong script.    |
| `computed()`               | Giá trị suy ra từ state khác, tự cập nhật, có cache.               |
| `watch()`                  | Chạy side effect khi state đổi (ví dụ: gọi lại API khi đổi trang). |
| `defineProps`              | Dữ liệu cha truyền xuống, **chỉ đọc**.                             |
| `defineEmits`              | Con báo sự kiện lên cha.                                           |
| `v-model`                  | Ràng buộc 2 chiều; trong component con dùng `defineModel()`.       |
| `onMounted`                | Chạy khi component đã gắn vào DOM.                                 |

**Nguyên tắc vàng của luồng dữ liệu:** _props đi xuống, events đi lên._ Component con **không bao giờ** sửa trực tiếp props.

### 3.3 Ví dụ hoàn chỉnh

**Kiểu dữ liệu: viết đầu tiên.**

```ts
// src/types/anime.ts
export type AnimeType = "TV" | "Movie" | "OVA" | "ONA" | "Special";
export type AiringStatus = "upcoming" | "airing" | "finished";

export interface Anime {
    id: number;
    title: string;
    title_japanese: string | null;
    slug: string;
    synopsis: string | null;
    type: AnimeType;
    status: AiringStatus;
    episodes_count: number | null;
    aired_from: string | null;
    image_url: string | null;
    created_at: string;
}

export type AnimeInput = Omit<Anime, "id" | "slug" | "created_at"> & {
    slug?: string;
};

export interface Paginated<T> {
    data: T[];
    meta: {
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
}
```

**Lớp gọi API: component không gọi axios trực tiếp.**

```ts
// src/api/http.ts
import axios from "axios";

export const http = axios.create({
    baseURL: import.meta.env.VITE_API_URL,
    headers: { Accept: "application/json" },
});
```

```ts
// src/api/anime.ts
import { http } from "./http";
import type { Anime, AnimeInput, Paginated } from "@/types/anime";

export const animeApi = {
    list: (params: { q?: string; page?: number }) =>
        http.get<Paginated<Anime>>("/anime", { params }).then((r) => r.data),

    create: (input: AnimeInput) =>
        http.post<{ data: Anime }>("/anime", input).then((r) => r.data.data),

    update: (id: number, input: AnimeInput) =>
        http
            .put<{ data: Anime }>(`/anime/${id}`, input)
            .then((r) => r.data.data),

    remove: (id: number) => http.delete(`/anime/${id}`),
};
```

**Component trình bày (presentational): chỉ nhận props, phát events.**

```vue
<!-- src/components/anime/AnimeGrid.vue -->
<script setup lang="ts">
import type { Anime } from "@/types/anime";

defineProps<{ items: Anime[] }>();
const emit = defineEmits<{
    edit: [anime: Anime];
    remove: [anime: Anime];
}>();
</script>

<template>
    <p v-if="items.length === 0">Chưa có anime nào.</p>
    <ul v-else class="grid">
        <li v-for="a in items" :key="a.id" class="card">
            <img
                v-if="a.image_url"
                :src="a.image_url"
                :alt="a.title"
                loading="lazy"
            />
            <h3>{{ a.title }}</h3>
            <small>{{ a.type }} · {{ a.episodes_count ?? "?" }} tập</small>
            <button @click="emit('edit', a)">Sửa</button>
            <button @click="emit('remove', a)">Xóa</button>
        </li>
    </ul>
</template>

<style scoped>
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 16px;
    list-style: none;
    padding: 0;
}
.card img {
    width: 100%;
    aspect-ratio: 2 / 3;
    object-fit: cover;
    border-radius: 8px;
}
</style>
```

**Component trang (page): giữ state, gọi API.**

```vue
<!-- src/pages/AnimeListPage.vue -->
<script setup lang="ts">
import { ref, watch, onMounted } from "vue";
import { animeApi } from "@/api/anime";
import AnimeGrid from "@/components/anime/AnimeGrid.vue";
import type { Anime } from "@/types/anime";

const items = ref<Anime[]>([]);
const keyword = ref("");
const page = ref(1);
const lastPage = ref(1);
const loading = ref(false);
const error = ref<string | null>(null);

async function load() {
    loading.value = true;
    error.value = null;
    try {
        const res = await animeApi.list({ q: keyword.value, page: page.value });
        items.value = res.data;
        lastPage.value = res.meta.last_page;
    } catch {
        error.value = "Không tải được danh sách anime.";
    } finally {
        loading.value = false;
    }
}

async function remove(a: Anime) {
    if (!confirm(`Xóa "${a.title}"?`)) return;
    await animeApi.remove(a.id);
    await load();
}

watch(page, load);
onMounted(load);
</script>

<template>
    <form
        @submit.prevent="
            page = 1;
            load();
        "
    >
        <input
            v-model="keyword"
            placeholder="Tìm tên anime (Romaji hoặc tiếng Nhật)"
        />
        <button>Tìm</button>
    </form>

    <p v-if="error">{{ error }}</p>
    <p v-else-if="loading">Đang tải…</p>
    <AnimeGrid v-else :items="items" @remove="remove" @edit="/* bài tập */" />

    <button :disabled="page <= 1" @click="page--">Trước</button>
    <span>{{ page }} / {{ lastPage }}</span>
    <button :disabled="page >= lastPage" @click="page++">Sau</button>
</template>
```

**Cách tư duy.** Trước khi viết một màn hình, vẽ ra giấy:

1. **State** nào cần giữ? (danh sách, từ khóa, trang, loading, lỗi)
2. **Hành động** nào làm đổi state? (tìm, đổi trang, xóa)
3. **3 trạng thái UI**: đang tải, lỗi, có dữ liệu (kèm trường hợp rỗng).
4. Phần nào tách thành component con _chỉ hiển thị_?

### Bài tập giai đoạn 3

> **Bài 3.A – Màn hình anime**
>
> 1. Tự gõ lại toàn bộ ví dụ trên, chạy được với backend thật.
> 2. Tạo `AnimeForm.vue` dùng chung cho **thêm và sửa**: props `initial?: Anime`, emit `submit: [input: AnimeInput]`, `cancel: []`. Ô `type`, `status` là `<select>`; có xem trước ảnh khi nhập `image_url`.
> 3. Hiển thị lỗi 422 của Laravel **dưới từng ô input**. Gợi ý: lỗi nằm ở `error.response.data.errors` dạng `{ slug: ['...'] }`. Dùng `axios.isAxiosError(e)` để thu hẹp kiểu.
> 4. Route: `/anime`, `/anime/new`, `/anime/:id`, `/anime/:id/edit`. Trang chi tiết hiển thị đầy đủ thông tin + synopsis.
> 5. Nút Submit bị khóa khi đang gửi (chống bấm 2 lần).
>
> **Hoàn thành khi:** `npx vue-tsc --noEmit` không lỗi; không có `any`; thêm/sửa/xóa/tìm/phân trang đều chạy; nhập slug trùng thấy lỗi ngay dưới ô.

### Tự kiểm tra

<details><summary>1. Vì sao `AnimeGrid` không tự gọi API xóa?</summary>

Để nó chỉ lo hiển thị nên dùng lại được ở nơi khác (trang tìm kiếm, trang xếp hạng…), dễ test. Quyết định "xóa xong thì làm gì" thuộc về trang cha.

</details>

<details><summary>2. Gõ từ khóa rất nhanh, 2 request trả về sai thứ tự thì sao?</summary>

Kết quả của request cũ có thể ghi đè kết quả mới (race condition). Cách xử lý (giai đoạn 7): đánh số request và bỏ qua kết quả cũ, hoặc hủy request cũ bằng `AbortController`.

</details>

<details><summary>3. `ref` và `reactive` khác gì, nên dùng cái nào?</summary>

`ref` bọc mọi kiểu (kể cả số, chuỗi), truy cập qua `.value`; gán lại cả object được. `reactive` chỉ cho object, mất reactivity nếu destructure hoặc gán lại. Mặc định dùng `ref` cho đơn giản và nhất quán.

</details>

---

## Giai đoạn 4 – Database & quan hệ dữ liệu (Intermediate)

**Mục tiêu:** thiết kế nhiều bảng có quan hệ 1-n và n-n, truy vấn hiệu quả, hiểu SQL thật Eloquent sinh ra.

### 4.1 Thiết kế trước khi migrate

Vẽ ERD ra giấy (hoặc https://dbdiagram.io) **trước** khi tạo migration:

```
studios 1──n anime n──n genres          (bảng trung gian anime_genre)
               │
               ├──1──n episodes
               └──n──n characters       (bảng trung gian anime_character, có cột role)
```

| Bảng              | Cột chính                                                                |
| ----------------- | ------------------------------------------------------------------------ |
| `studios`         | id, name (unique)                                                        |
| `anime`           | thêm `studio_id` (FK, nullable)                                          |
| `genres`          | id, name (unique), slug (unique)                                         |
| `anime_genre`     | anime_id, genre_id, **khóa chính kép** (anime_id, genre_id)              |
| `episodes`        | id, anime_id (FK), number, title, aired_at; **unique**(anime_id, number) |
| `characters`      | id, name, image_url                                                      |
| `anime_character` | anime_id, character_id, role (`main` / `supporting`)                     |

**Câu hỏi tư duy khi thiết kế bảng:**

- Quan hệ là 1-1, 1-n hay n-n? (Một anime có nhiều thể loại _và_ một thể loại có nhiều anime → n-n.)
- Xóa cha thì con thế nào? Xóa anime → xóa tập phim (`cascadeOnDelete`). Xóa studio → anime giữ lại, `studio_id` thành null (`nullOnDelete`).
- Cột nào hay dùng để **lọc / sắp xếp / join** → cần index.
- Cặp giá trị nào phải duy nhất → unique index (đừng chỉ dựa vào validation).

### 4.2 Quan hệ Eloquent

```php
class Anime extends Model
{
    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class)->orderBy('number');
    }

    public function characters(): BelongsToMany
    {
        return $this->belongsToMany(Character::class)->withPivot('role');
    }
}
```

```php
// Gán thể loại (n-n)
$anime->genres()->sync([1, 4, 7]);   // giữ đúng 3 thể loại này, bỏ các cái khác
$anime->genres()->attach(9);         // thêm 1
$anime->genres()->detach(4);         // bỏ 1
```

### 4.3 N+1: lỗi hiệu năng phổ biến nhất

```php
// ❌ 1 query lấy 50 tập + 50 query lấy anime + 50 query lấy studio = 101 query
$episodes = Episode::latest('aired_at')->take(50)->get();
foreach ($episodes as $ep) {
    echo $ep->anime->title . ' – ' . $ep->anime->studio?->name;
}

// ✅ 3 query
$episodes = Episode::with('anime.studio')->latest('aired_at')->take(50)->get();
```

**Mẹo:** bật chặn lazy loading khi dev, N+1 sẽ ném lỗi ngay:

```php
// app/Providers/AppServiceProvider.php → boot()
Model::preventLazyLoading(! app()->isProduction());
```

### 4.4 Query Scope: đặt tên cho điều kiện lọc

```php
class Anime extends Model
{
    public function scopeAiring(Builder $query): void
    {
        $query->where('status', AiringStatus::Airing);
    }

    public function scopeInSeason(Builder $query, int $year, Season $season): void
    {
        [$from, $to] = $season->dateRange($year); // enum Season từ bài 1.A
        $query->whereBetween('aired_from', [$from, $to]);
    }

    public function scopeWithGenre(Builder $query, string $genreSlug): void
    {
        $query->whereHas('genres', fn ($q) => $q->where('slug', $genreSlug));
    }
}

// Dùng:
Anime::inSeason(2024, Season::Fall)->withGenre('fantasy')->with('studio', 'genres')->paginate();
```

> **Lưu ý:** `whereYear('aired_from', 2024)` hay `whereMonth(...)` bọc cột trong hàm `YEAR()`/`MONTH()` nên MySQL **không dùng được index** trên `aired_from`. Dùng `whereBetween` với khoảng ngày như trên. Đây là kiểu chi tiết phân biệt junior với mid.

### 4.5 Transaction

Khi một thao tác ghi **nhiều bảng** thì hoặc tất cả thành công, hoặc không gì cả:

```php
DB::transaction(function () use ($data) {
    $anime = Anime::create($data['anime']);
    $anime->genres()->sync($data['genre_ids']);
    $anime->episodes()->createMany($data['episodes']);
});
```

### 4.6 SQL thật: phải đọc được

```php
Anime::inSeason(2024, Season::Fall)->withGenre('fantasy')->toRawSql();
```

Học thuộc và tự viết tay được: `SELECT … JOIN … WHERE … GROUP BY … HAVING … ORDER BY … LIMIT`. Thử viết tay: _"Đếm số anime theo từng thể loại, chỉ lấy thể loại có ≥ 5 anime"_. Dùng `EXPLAIN` để xem query có dùng index không.

### Bài tập giai đoạn 4

> **Bài 4.A – Thể loại, studio, tập phim, nhân vật**
>
> 1. Migration + Model + quan hệ cho tất cả bảng ở 4.1. Có foreign key, unique và index hợp lý.
> 2. Thêm/sửa anime nhận kèm `genre_ids[]` và `studio_id`, lưu trong **một transaction**. Validate `genre_ids.*` tồn tại.
> 3. API lồng: `GET/POST /api/anime/{anime}/episodes`. Không cho trùng số tập trong cùng anime (validation **và** unique index).
> 4. API danh sách anime lọc theo: thể loại, studio, mùa + năm, trạng thái; trả kèm studio + genres. **Số query cố định, không tăng theo số anime** (kiểm tra bằng Telescope hoặc `DB::listen`).
> 5. Endpoint `GET /api/schedule?week=2026-W45`: các tập phát sóng trong tuần, kèm anime.
> 6. Frontend: trang chi tiết anime (thể loại dạng chip, danh sách tập, nhân vật chính/phụ tách 2 nhóm); form lọc trên trang danh sách.
>
> **Hoàn thành khi:** bật `preventLazyLoading` không lỗi; viết tay được câu SQL tương đương cho API lọc theo thể loại; `EXPLAIN` cho thấy lọc theo mùa dùng index.

### Tự kiểm tra

<details><summary>1. `$anime->genres` và `$anime->genres()` khác gì?</summary>

Không ngoặc: chạy query, trả Collection (có cache trên model). Có ngoặc: trả Relation/Query Builder để nối thêm điều kiện hoặc gọi `sync/attach`, chưa chạy query.

</details>

<details><summary>2. `sync` và `attach` khác gì? Dùng cái nào cho form sửa anime?</summary>

`attach` chỉ thêm (gọi 2 lần sẽ trùng hoặc lỗi khóa chính). `sync` đưa danh sách về _đúng_ những ID truyền vào: thêm cái thiếu, xóa cái thừa. Form sửa dùng `sync`.

</details>

<details><summary>3. Validation đã chặn trùng số tập, sao vẫn cần unique index trong DB?</summary>

Hai request đồng thời có thể cùng qua validation (lúc kiểm tra chưa có bản ghi), rồi cùng insert. Chỉ ràng buộc ở DB mới chặn được tuyệt đối.

</details>

---

## Giai đoạn 5 – OOP & SOLID trong nghiệp vụ thật (Intermediate)

**Mục tiêu:** xây **Thư viện cá nhân (Library)**, tính năng có nghiệp vụ thật: trạng thái xem, tiến độ tập, chấm điểm, cập nhật điểm trung bình. Học OOP và SOLID bằng cách _cần_ đến chúng.

### 5.1 Nghiệp vụ

- Mỗi user thêm anime vào thư viện với trạng thái: `plan_to_watch`, `watching`, `on_hold`, `completed`, `dropped`. Mỗi anime chỉ có một mục trong thư viện của một user.
- Cập nhật số tập đã xem: đang "định xem" mà xem tập đầu → tự chuyển sang **Đang xem**; xem đủ số tập → tự chuyển **Đã xem xong** và ghi ngày xong. Không được vượt tổng số tập. Anime đang chiếu (chưa biết tổng) thì không bao giờ tự "xong".
- Chấm điểm 1–10 (dùng lại `Score` của giai đoạn 1). Anime lưu sẵn `score_avg`, `score_votes`, `members_count` để hiển thị nhanh; phải cập nhật **chính xác** kể cả khi nhiều người chấm cùng lúc.
- Chưa có đăng nhập (giai đoạn 7 mới làm). Tạm dùng route `/api/users/{user}/library`.

Bảng `library_entries`: user_id, anime_id, status, episodes_watched, score (nullable), started_at, finished_at; **unique**(user_id, anime_id). Thêm vào `anime`: `score_avg` (decimal 4,2 nullable), `score_votes`, `members_count`.

### 5.2 OOP: 4 trụ cột qua ví dụ thật

| Trụ cột                      | Trong AniTrack                                                                                         |
| ---------------------------- | ------------------------------------------------------------------------------------------------------ |
| **Encapsulation** (đóng gói) | Quy tắc chuyển trạng thái nằm _trong_ enum `WatchStatus`, không rải rác khắp controller.               |
| **Abstraction** (trừu tượng) | Controller gọi `UpdateWatchProgress->handle(...)`, không cần biết bên trong tự đổi trạng thái thế nào. |
| **Inheritance** (kế thừa)    | Exception nghiệp vụ kế thừa `DomainException`. Dùng ít, chỉ khi thật sự "là một".                      |
| **Polymorphism** (đa hình)   | Giai đoạn 8: nhiều cách xếp hạng cùng implement một interface.                                         |

**Enum có hành vi: encapsulation.**

```php
enum WatchStatus: string
{
    case PlanToWatch = 'plan_to_watch';
    case Watching = 'watching';
    case OnHold = 'on_hold';
    case Completed = 'completed';
    case Dropped = 'dropped';

    /** @return list<self> */
    public function allowedNext(): array
    {
        return match ($this) {
            self::PlanToWatch => [self::Watching, self::Completed, self::Dropped],
            self::Watching    => [self::OnHold, self::Completed, self::Dropped],
            self::OnHold      => [self::Watching, self::Completed, self::Dropped],
            self::Dropped     => [self::Watching],
            self::Completed   => [self::Watching], // xem lại (rewatch)
        };
    }

    public function canTransitionTo(self $next): bool
    {
        return in_array($next, $this->allowedNext(), true);
    }
}
```

```php
class LibraryEntry extends Model
{
    protected function casts(): array
    {
        return [
            'status'      => WatchStatus::class,
            'started_at'  => 'datetime',
            'finished_at' => 'datetime',
        ];
    }
}
```

### 5.3 SOLID: dùng thực dụng

| Nguyên tắc                | Câu hỏi tự kiểm tra                                 | Áp dụng trong AniTrack                                                                      |
| ------------------------- | --------------------------------------------------- | ------------------------------------------------------------------------------------------- |
| **S**ingle Responsibility | Class này có _mấy lý do_ để bị sửa?                 | Controller lo HTTP; Action lo nghiệp vụ; Resource lo JSON; `RatingCalculator` lo công thức. |
| **O**pen/Closed           | Thêm loại mới có phải sửa code cũ không?            | Thêm cách xếp hạng = thêm class, không sửa `if/else` (giai đoạn 8).                         |
| **L**iskov Substitution   | Thay class con vào chỗ class cha có còn đúng không? | Mọi `AnimeRanking` đều chỉ sắp xếp/lọc, không xóa hay sửa dữ liệu.                          |
| **I**nterface Segregation | Interface có bắt implement hàm không cần không?     | `AnimeDataSource::find()` nhỏ gọn, không gộp chung `find/search/sync/log`.                  |
| **D**ependency Inversion  | Class có tự `new` dependency không?                 | Inject `RatingCalculator` qua constructor; Laravel container tự giải quyết.                 |

> **SOLID không phải luật phải áp tuyệt đối.** Nó là công cụ để giảm chi phí _sửa đổi_. Code chưa từng cần đổi thì chưa cần trừu tượng hóa. Trừu tượng sớm quá còn tệ hơn trùng lặp.

### 5.4 Action class: nơi chứa nghiệp vụ

```php
final class UpdateWatchProgress
{
    public function handle(LibraryEntry $entry, int $episodesWatched): LibraryEntry
    {
        // WatchProgress từ giai đoạn 1: tự ném lỗi nếu âm hoặc vượt tổng số tập
        $progress = new WatchProgress($episodesWatched, $entry->anime->episodes_count);
        $next = $this->resolveStatus($entry->status, $progress);

        if ($next !== $entry->status && ! $entry->status->canTransitionTo($next)) {
            throw InvalidStatusTransition::from($entry->status, $next);
        }

        $entry->fill([
            'episodes_watched' => $progress->watched,
            'status'           => $next,
            'started_at'       => $entry->started_at ?? ($progress->watched > 0 ? now() : null),
            'finished_at'      => $next === WatchStatus::Completed ? now() : $entry->finished_at,
        ])->save();

        return $entry;
    }

    private function resolveStatus(WatchStatus $current, WatchProgress $progress): WatchStatus
    {
        return match (true) {
            $progress->isCompleted() => WatchStatus::Completed,
            $current === WatchStatus::PlanToWatch && $progress->watched > 0 => WatchStatus::Watching,
            default => $current,
        };
    }
}
```

```php
final class RateAnime
{
    public function handle(LibraryEntry $entry, ?int $rawScore): LibraryEntry
    {
        $score = $rawScore === null ? null : new Score($rawScore); // dùng lại validate của giai đoạn 1

        return DB::transaction(function () use ($entry, $score) {
            // Khóa dòng anime: hai người chấm cùng lúc sẽ phải xếp hàng
            $anime = Anime::whereKey($entry->anime_id)->lockForUpdate()->firstOrFail();

            $entry->update(['score' => $score?->value]);

            $stats = LibraryEntry::where('anime_id', $anime->id)
                ->whereNotNull('score')
                ->selectRaw('COUNT(*) as votes, AVG(score) as average')
                ->first();

            $anime->update([
                'score_votes' => $stats->votes,
                'score_avg'   => $stats->average === null ? null : round((float) $stats->average, 2),
            ]);

            return $entry;
        });
    }
}
```

```php
// Controller chỉ còn điều phối
public function progress(UpdateProgressRequest $request, LibraryEntry $entry, UpdateWatchProgress $action)
{
    return LibraryEntryResource::make($action->handle($entry, $request->integer('episodes_watched')));
}
```

**Exception nghiệp vụ trả lỗi đẹp:**

```php
class InvalidStatusTransition extends DomainException
{
    public static function from(WatchStatus $from, WatchStatus $to): self
    {
        return new self("Không thể chuyển từ {$from->value} sang {$to->value}.");
    }

    public function render(): JsonResponse
    {
        return response()->json(['message' => $this->getMessage()], 409);
    }
}
```

**Cách tư duy.** Mỗi khi viết logic, hỏi: _"Nếu mai có màn hình khác (hoặc command, hoặc job import) cũng cần làm việc này, tôi có phải copy code không?"_ Nếu có, logic đang nằm sai chỗ (thường là trong controller). Chuyển nó vào Action.

**Mẹo.** `lockForUpdate()` chỉ có tác dụng **bên trong transaction**. Ngoài transaction nó không khóa gì cả.

### Bài tập giai đoạn 5

> **Bài 5.A – Thư viện cá nhân**
>
> 1. Migration `library_entries` + các cột thống kê trên `anime`. Copy `anitrack-core` (giai đoạn 1) vào `app/Domain/Core` (hoặc require như package local).
> 2. Action: `AddToLibrary` (tăng `members_count`), `UpdateWatchProgress`, `ChangeWatchStatus`, `RateAnime`, `RemoveFromLibrary` (giảm `members_count`, tính lại điểm).
> 3. Route: `GET/POST /api/users/{user}/library`, `PATCH /api/library/{entry}/progress`, `PATCH /api/library/{entry}/status`, `PUT /api/library/{entry}/score`, `DELETE /api/library/{entry}`.
> 4. Lỗi nghiệp vụ trả **409** (chuyển trạng thái sai) hoặc **422** (vượt số tập), thông báo rõ ràng.
> 5. Test: tự chuyển Watching khi xem tập đầu; tự Completed khi đủ tập; anime đang chiếu không tự Completed; Dropped → OnHold bị chặn; chấm điểm cập nhật đúng `score_avg`; bỏ điểm (null) cập nhật lại; xóa mục cập nhật lại; thêm trùng anime bị chặn.
> 6. Frontend: trang "Thư viện của tôi" chia tab theo trạng thái; nút **+1 tập**; chọn điểm; nút hành động chỉ hiện khi hợp lệ (backend trả `allowed_statuses` trong Resource; frontend **không** tự lặp lại logic chuyển trạng thái).
>
> **Hoàn thành khi:** không controller method nào dài quá 10 dòng; giải thích được vì sao cần `lockForUpdate` ở `RateAnime`; chỉ ra được mỗi chữ cái SOLID đang thể hiện ở file nào.

### Tự kiểm tra

<details><summary>1. Bỏ `lockForUpdate` trong `RateAnime` thì hỏng ở đâu?</summary>

Hai người chấm cùng lúc: transaction A và B cùng tính `AVG` khi chưa thấy điểm của nhau (chưa commit), rồi lần lượt ghi đè `score_avg`. Kết quả cuối thiếu một trong hai điểm, lệch so với dữ liệu thật. Khóa dòng anime buộc B đợi A xong mới tính.

</details>

<details><summary>2. Vì sao lưu sẵn `score_avg` trên bảng anime thay vì mỗi lần hiển thị lại tính `AVG`?</summary>

Danh sách/xếp hạng đọc rất nhiều, chấm điểm thì ít. Tính sẵn giúp đọc nhanh và sắp xếp được bằng index. Cái giá là phải giữ đồng bộ (đây gọi là _denormalization_). Mọi chỗ thay đổi điểm đều phải đi qua Action để không quên cập nhật.

</details>

<details><summary>3. Vì sao frontend nên nhận `allowed_statuses` từ backend?</summary>

Quy tắc nghiệp vụ chỉ có một nguồn sự thật (backend). Nếu frontend tự tính, hai nơi sẽ lệch nhau khi quy tắc thay đổi.

</details>

---

## Giai đoạn 6 – Clean Code, Testing & Refactor (Intermediate)

**Mục tiêu:** viết code người khác (và chính bạn sau 6 tháng) đọc hiểu ngay; refactor không sợ vỡ.

### 6.1 Clean Code: 10 luật thực dụng

1. **Tên nói lên ý định.** `$n` → `$episodesWatched`. Hàm là động từ: `calculateAverage()`, biến boolean là câu hỏi: `isAiring`, `hasScore`.
2. **Hàm ngắn, làm một việc.** Một mức trừu tượng mỗi hàm.
3. **Return sớm** thay vì if lồng nhau.

    ```php
    // ❌
    if ($entry) { if ($entry->status !== WatchStatus::Dropped) { if ($episodes >= 0) { /* ... */ } } }

    // ✅
    if (! $entry) return;
    if ($entry->status === WatchStatus::Dropped) throw ...;
    if ($episodes < 0) throw ...;
    // logic chính ở đây, không thụt lề
    ```

4. **Không magic number/string.** `if ($status == 4)` → `WatchStatus::Completed`.
5. **Không dùng hằng số toàn cục.** Đặt hằng số trong class/enum mà nó thuộc về.
6. **Comment giải thích _tại sao_, không giải thích _cái gì_.** Code đã nói cái gì.
7. **Xóa code chết.** Code comment-out → xóa. Git đã lưu lịch sử.
8. **Nhất quán.** Một dự án một kiểu đặt tên: PHP method `camelCase`, cột DB `snake_case`, Vue component `PascalCase`.
9. **Không hàm helper toàn cục cho logic nghiệp vụ.** Helper chỉ cho tiện ích thuần (format, parse).
10. **File quá 300 dòng = tín hiệu cần tách.**

### 6.2 Kim tự tháp test

```
        ┌──────────┐   Ít: E2E (Playwright/Cypress), sau này
        ├──────────┤
        │ Feature  │   Nhiều: gọi API thật, DB test (Pest + RefreshDatabase)
        ├──────────┤
        │   Unit   │   Nhiều nhất: Enum, Calculator, composable, hàm thuần
        └──────────┘
```

**Test cái gì?** Ưu tiên theo rủi ro: điểm số, tiến độ, trạng thái, phân quyền, dữ liệu riêng tư. **Không** test getter/setter vô nghĩa.

**Cấu trúc một test: Arrange – Act – Assert.**

```php
it('không cho chuyển từ Dropped sang OnHold', function () {
    // Arrange
    $entry = LibraryEntry::factory()->create(['status' => WatchStatus::Dropped]);

    // Act
    $response = patchJson("/api/library/{$entry->id}/status", ['status' => 'on_hold']);

    // Assert
    $response->assertStatus(409);
    expect($entry->fresh()->status)->toBe(WatchStatus::Dropped);
});
```

**Test Vue với Vitest + Vue Test Utils:**

```ts
import { mount } from "@vue/test-utils";
import { describe, it, expect } from "vitest";
import AnimeGrid from "@/components/anime/AnimeGrid.vue";
import type { Anime } from "@/types/anime";

const frieren: Anime = {
    id: 1,
    title: "Sousou no Frieren",
    title_japanese: "葬送のフリーレン",
    slug: "sousou-no-frieren",
    synopsis: null,
    type: "TV",
    status: "finished",
    episodes_count: 28,
    aired_from: "2023-09-29",
    image_url: null,
    created_at: "",
};

describe("AnimeGrid", () => {
    it("phát sự kiện remove kèm anime", async () => {
        const wrapper = mount(AnimeGrid, { props: { items: [frieren] } });

        await wrapper.findAll("button")[1].trigger("click");

        expect(wrapper.emitted("remove")?.[0]).toEqual([frieren]);
    });

    it("hiện thông báo khi rỗng", () => {
        const wrapper = mount(AnimeGrid, { props: { items: [] } });
        expect(wrapper.text()).toContain("Chưa có anime nào");
    });
});
```

### 6.3 Quy trình refactor an toàn

1. Có test bao phủ hành vi hiện tại (chưa có → viết trước).
2. Đổi **một** thứ nhỏ.
3. Chạy test.
4. Commit.
5. Lặp lại.

Không bao giờ vừa refactor vừa thêm tính năng trong cùng một commit.

### 6.4 Công cụ tự động "review" thay AI

```bash
# Backend
composer require laravel/pint --dev        # format code theo chuẩn
composer require larastan/larastan --dev   # phân tích tĩnh, bắt lỗi type
./vendor/bin/pint
./vendor/bin/phpstan analyse --level=6 app

# Frontend
npm run lint
npx vue-tsc --noEmit
```

Larastan báo lỗi = có người review miễn phí. Nâng level dần từ 5 lên 8.

### Bài tập giai đoạn 6

> **Bài 6.A – Đợt dọn dẹp lớn**
>
> 1. Cài Pint, Larastan (level 6), ESLint; sửa hết lỗi.
> 2. Đọc lại _toàn bộ_ code giai đoạn 2–5, ghi danh sách vi phạm 10 luật ở 6.1, sửa từng cái, mỗi cái một commit.
> 3. Đạt: mọi Action có test; mọi component trình bày có ít nhất 1 test Vitest.
> 4. Chọn file dài nhất của bạn, tách nó ra.
>
> **Hoàn thành khi:** `php artisan test`, `npx vitest run`, `phpstan`, `vue-tsc` đều xanh; mở một file bất kỳ, đọc hiểu trong 1 phút.

### Tự kiểm tra

<details><summary>1. Test bị "giòn" (sửa code nhỏ là test vỡ dù hành vi không đổi) là do đâu?</summary>

Test đang kiểm tra _cách làm_ (chi tiết nội bộ) thay vì _kết quả_ (hành vi quan sát được). Hãy assert trên response, dữ liệu DB, event phát ra, đừng assert trên hàm private hay cấu trúc HTML quá chi tiết.

</details>

<details><summary>2. Khi nào được phép trùng lặp code?</summary>

Khi hai đoạn giống nhau _ngẫu nhiên_ nhưng có lý do thay đổi khác nhau. Quy tắc "Rule of Three": trùng lần thứ 3 mới tách chung.

</details>

---

## Giai đoạn 7 – Kiến trúc Vue (Intermediate → Advanced)

**Mục tiêu:** tổ chức frontend lớn dần mà không rối: đăng nhập, layout, composable, store, bộ lọc trên URL.

### 7.1 Cấu trúc thư mục theo tính năng

```
src/
├── api/              # http.ts + một file mỗi resource
├── types/            # interface dùng chung
├── stores/           # Pinia: CHỈ state toàn cục (auth, settings)
├── composables/      # logic tái sử dụng: useAsync, usePagination, useFormErrors
├── components/
│   ├── ui/           # nút, input, modal, rating stars: không biết gì về nghiệp vụ
│   └── <feature>/    # anime/, library/, reviews/: component nghiệp vụ
├── pages/            # mỗi route một page, ghép component + composable
├── layouts/
└── router/
```

**Quy tắc phụ thuộc** (chỉ được import theo chiều mũi tên):
`pages → components/<feature> → components/ui`; `pages → composables → api → types`.
`components/ui` **không** import `api` hay `stores`.

### 7.2 Khi nào dùng Pinia, khi nào không?

| Dữ liệu                                            | Để ở đâu                                  |
| -------------------------------------------------- | ----------------------------------------- |
| User đăng nhập, token, quyền                       | **Pinia** (nhiều trang dùng)              |
| Danh sách anime ở trang tìm kiếm                   | **State cục bộ trong page/composable**    |
| Form đang nhập                                     | **State cục bộ**                          |
| Bộ lọc (thể loại, mùa, năm) muốn giữ khi F5 / Back | **Query string trên URL** (`route.query`) |

Sai lầm phổ biến: đưa mọi thứ vào store. Store phình to, ai cũng sửa được, khó lần theo.

```ts
// src/stores/auth.ts
export const useAuthStore = defineStore("auth", () => {
    const user = ref<User | null>(null);
    const token = ref<string | null>(localStorage.getItem("token"));
    const isLoggedIn = computed(() => token.value !== null);

    async function login(email: string, password: string) {
        const res = await authApi.login(email, password);
        token.value = res.token;
        user.value = res.user;
        localStorage.setItem("token", res.token);
    }

    function logout() {
        token.value = null;
        user.value = null;
        localStorage.removeItem("token");
    }

    return { user, token, isLoggedIn, login, logout };
});
```

### 7.3 Composable: tái sử dụng logic có state

```ts
// src/composables/useAsync.ts
import { ref, shallowRef } from "vue";

export function useAsync<TArgs extends unknown[], TResult>(
    fn: (...args: TArgs) => Promise<TResult>,
) {
    const data = shallowRef<TResult | null>(null);
    const error = ref<unknown>(null);
    const loading = ref(false);
    let lastCallId = 0;

    async function run(...args: TArgs): Promise<TResult | undefined> {
        const callId = ++lastCallId;
        loading.value = true;
        error.value = null;
        try {
            const result = await fn(...args);
            if (callId === lastCallId) data.value = result; // bỏ kết quả của request cũ
            return result;
        } catch (e) {
            if (callId === lastCallId) error.value = e;
        } finally {
            if (callId === lastCallId) loading.value = false;
        }
    }

    return { data, error, loading, run };
}
```

```ts
// Dùng trong page
const { data, loading, error, run } = useAsync(animeApi.list);
watch([keyword, page], () => run({ q: keyword.value, page: page.value }), {
    immediate: true,
});
```

**Cách tư duy.** Khi thấy cùng bộ ba `loading / error / data` lặp ở 3 trang, đó là lúc tách composable. Không tách sớm hơn.

### 7.4 Xử lý lỗi tập trung

```ts
// src/api/http.ts
http.interceptors.request.use((config) => {
    const token = useAuthStore().token;
    if (token) config.headers.Authorization = `Bearer ${token}`;
    return config;
});

http.interceptors.response.use(
    (res) => res,
    (error) => {
        if (axios.isAxiosError(error) && error.response?.status === 401) {
            useAuthStore().logout();
            router.push({ name: "login" });
        }
        return Promise.reject(error);
    },
);
```

Phân loại lỗi rõ ràng: **401** → đăng nhập lại; **403** → thông báo không có quyền; **422** → hiện dưới ô input; **409** → thông báo nghiệp vụ; **5xx/mạng** → thông báo chung + nút thử lại.

### 7.5 Router guard

```ts
router.beforeEach((to) => {
    const auth = useAuthStore();
    if (to.meta.requiresAuth && !auth.isLoggedIn) {
        return { name: "login", query: { redirect: to.fullPath } };
    }
});
```

### Bài tập giai đoạn 7

> **Bài 7.A – Đăng nhập & kiến trúc**
>
> 1. Backend: Sanctum token: `POST /api/register`, `POST /api/login`, `POST /api/logout`, `GET /api/me`.
> 2. Đổi route thư viện từ `/api/users/{user}/library` sang `/api/me/library` (lấy user đang đăng nhập). Ghi vào LEARNING_LOG: vì sao route cũ là lỗ hổng bảo mật?
> 3. Frontend: `useAuthStore`, trang đăng ký/đăng nhập, router guard, interceptor 401. Nút "Thêm vào thư viện" trên trang anime: chưa đăng nhập thì chuyển sang login rồi quay lại đúng trang.
> 4. Sắp xếp lại thư mục theo 7.1. Viết `useAsync`, `usePagination` (đồng bộ `page` với `route.query.page`), `useFormErrors` (gom xử lý lỗi 422 từ bài 3.A).
> 5. Bộ lọc anime (thể loại, mùa, năm, trạng thái) lưu trên URL: F5 hoặc bấm Back vẫn giữ bộ lọc; copy link gửi người khác mở ra đúng kết quả.
> 6. Component `ui/RatingStars.vue` dùng `defineModel<number | null>()`. Test Vitest cho `useAsync`: chứng minh kết quả request cũ bị bỏ qua.
>
> **Hoàn thành khi:** không page nào tự gọi `axios`; `components/ui` không import `api`/`stores`; F5 ở bất kỳ trang nào vẫn đúng trạng thái.

### Tự kiểm tra

<details><summary>1. Vì sao `useAsync` dùng `shallowRef` cho `data`?</summary>

Dữ liệu API thường thay thế cả cục, không sửa sâu từng thuộc tính. `shallowRef` không biến đổi sâu cả object thành reactive nên nhẹ hơn với danh sách lớn.

</details>

<details><summary>2. Mixin (Vue 2) và composable khác nhau thế nào?</summary>

Mixin trộn thuộc tính vào `this` một cách ngầm: không biết `this.loading` đến từ đâu, dễ trùng tên. Composable là hàm trả về giá trị _rõ ràng_: `const { loading } = useAsync(...)`, nhìn là biết nguồn gốc, có type đầy đủ.

</details>

<details><summary>3. Vì sao `/api/users/{user}/library` là lỗ hổng sau khi có đăng nhập?</summary>

Ai cũng có thể đổi `{user}` thành ID người khác để xem hoặc sửa thư viện của họ (lỗi IDOR). Lấy user từ token đăng nhập thì không giả mạo được.

</details>

---

## Giai đoạn 8 – Design Patterns dùng đúng chỗ (Advanced)

**Mục tiêu:** nhận ra _vấn đề_ trước, rồi mới chọn pattern. Không học pattern để "khoe".

| Pattern              | Vấn đề nó giải quyết                                         | Tính năng AniTrack                               |
| -------------------- | ------------------------------------------------------------ | ------------------------------------------------ |
| **Strategy**         | Nhiều cách làm cùng một việc, chọn lúc chạy                  | Bảng xếp hạng: điểm cao, phổ biến, đang hot      |
| **Factory**          | Tạo object phù hợp theo điều kiện                            | Chọn Strategy theo `?sort=top`                   |
| **Observer / Event** | Một việc xảy ra, nhiều nơi cần phản ứng, không muốn gắn chặt | Có tập mới → thông báo người đang xem, ghi log   |
| **Pipeline**         | Chuỗi bước xử lý tuần tự, có thể thêm/bớt                    | Bộ lọc tìm kiếm anime nhiều điều kiện            |
| **DTO**              | Mảng `$data['x']` không có type, không biết có những key gì  | Dữ liệu anime import từ Jikan                    |
| **Adapter**          | Bọc dịch vụ ngoài để đổi được nguồn, test được               | `AnimeDataSource`: Jikan thật / bản giả cho test |

### 8.1 Strategy + Factory: bảng xếp hạng

```php
interface AnimeRanking
{
    public function apply(Builder $query): Builder;
}

final class MostPopular implements AnimeRanking
{
    public function apply(Builder $query): Builder
    {
        return $query->orderByDesc('members_count');
    }
}

final class TopRated implements AnimeRanking
{
    public function __construct(private int $minVotes = 50) {}

    public function apply(Builder $query): Builder
    {
        $globalAvg = (float) Anime::whereNotNull('score_avg')->avg('score_avg');
        $m = $this->minVotes;

        // Đúng công thức điểm trọng số ở giai đoạn 1, viết bằng SQL
        return $query->whereNotNull('score_avg')->orderByRaw(
            '(score_votes / (score_votes + ?)) * score_avg + (? / (score_votes + ?)) * ? DESC',
            [$m, $m, $m, $globalAvg],
        );
    }
}

final class AnimeRankingFactory
{
    public function make(string $sort): AnimeRanking
    {
        return match ($sort) {
            'top'     => new TopRated(),
            'popular' => new MostPopular(),
            default   => throw new InvalidArgumentException("Không hỗ trợ kiểu xếp hạng: {$sort}"),
        };
    }
}
```

Thêm kiểu xếp hạng mới = thêm class mới + một dòng trong Factory. Code cũ không đụng tới (Open/Closed).

> **Câu hỏi để tự nghĩ:** công thức trọng số giờ nằm ở _hai nơi_ (`RatingCalculator` PHP và câu SQL). Nếu đổi công thức, rất dễ quên sửa một chỗ. Có cách nào chỉ giữ một nơi? (Gợi ý: tính sẵn cột `weighted_score` trong `RateAnime` bằng `RatingCalculator`, rồi chỉ `orderByDesc('weighted_score')`. Đánh đổi là gì?)

### 8.2 Event: tách hệ quả khỏi hành động chính

```php
// Khi tạo tập mới (Action CreateEpisode):
EpisodeAired::dispatch($episode);

// Listener độc lập, mỗi cái một việc
class NotifyWatchers implements ShouldQueue
{
    public function handle(EpisodeAired $event): void
    {
        LibraryEntry::query()
            ->where('anime_id', $event->episode->anime_id)
            ->where('status', WatchStatus::Watching)
            ->with('user')
            ->chunkById(500, function ($entries) use ($event) {
                foreach ($entries as $entry) {
                    $entry->user->notify(new NewEpisodeNotification($event->episode));
                }
            });
    }
}
```

`CreateEpisode` không biết (và không cần biết) có bao nhiêu thứ phản ứng với nó. `chunkById` để không nạp 100.000 dòng vào RAM một lúc.

### 8.3 Pipeline: bộ lọc tìm kiếm

```php
$query = app(Pipeline::class)
    ->send(Anime::query())
    ->through([
        new FilterByKeyword($request->string('q')->value()),
        new FilterByGenre($request->string('genre')->value()),
        new FilterBySeason($request->integer('year') ?: null, $request->enum('season', Season::class)),
        new FilterByStatus($request->enum('status', AiringStatus::class)),
    ])
    ->thenReturn();
```

Mỗi filter là một class nhỏ có hàm `handle(Builder $query, Closure $next)`, test riêng được; giá trị rỗng thì bỏ qua.

### 8.4 DTO + Adapter: import dữ liệu thật từ Jikan

```php
final readonly class AnimeImportData
{
    /** @param list<string> $genres */
    public function __construct(
        public int $externalId,
        public string $title,
        public ?string $titleJapanese,
        public ?string $synopsis,
        public ?int $episodes,
        public string $status,
        public ?string $airedFrom,
        public ?string $imageUrl,
        public array $genres,
    ) {}
}

interface AnimeDataSource
{
    public function find(int $externalId): AnimeImportData;
}

final class JikanAnimeSource implements AnimeDataSource
{
    public function find(int $externalId): AnimeImportData
    {
        $json = Http::baseUrl('https://api.jikan.moe/v4')
            ->timeout(10)
            ->retry(3, 1000)
            ->get("/anime/{$externalId}")
            ->throw()
            ->json('data');

        return new AnimeImportData(
            externalId: $json['mal_id'],
            title: $json['title'],
            titleJapanese: $json['title_japanese'] ?? null,
            synopsis: $json['synopsis'] ?? null,
            episodes: $json['episodes'] ?? null,
            status: $json['status'],            // "Currently Airing"… → bài tập: ánh xạ sang AiringStatus
            airedFrom: $json['aired']['from'] ?? null,
            imageUrl: $json['images']['jpg']['image_url'] ?? null,
            genres: array_column($json['genres'] ?? [], 'name'),
        );
    }
}
```

Trong test: `Http::fake([...])` hoặc bind `FakeAnimeSource` thay cho `JikanAnimeSource`. Code import không cần biết dữ liệu đến từ đâu.

> **Đọc docs Jikan** để biết giới hạn số request mỗi giây/phút. Gọi quá nhanh sẽ bị chặn (HTTP 429). Đây là bài học thực tế khi làm việc với API bên ngoài.

**Cảnh báo over-engineering.** Đừng dùng Repository pattern bọc Eloquent "cho chuẩn". Eloquent đã là một lớp truy cập dữ liệu. Chỉ thêm abstraction khi có lý do cụ thể, như `AnimeDataSource` ở trên: nguồn dữ liệu bên ngoài _thật sự_ cần thay được khi test.

### Bài tập giai đoạn 8

> **Bài 8.A**
>
> 1. `GET /api/rankings?sort=top|popular`, dùng Strategy + Factory, validate `sort` bằng `Rule::in`.
> 2. Thêm kiểu `trending` (nhiều lượt thêm vào thư viện nhất trong 7 ngày) **mà không sửa class Strategy nào có sẵn**.
> 3. Event `EpisodeAired` + 2 listener: thông báo người đang xem (Laravel Notification, kênh `database`), ghi `activity_logs`. Test bằng `Event::fake()` và `Notification::fake()`.
> 4. `AnimeDataSource` + `JikanAnimeSource` + `FakeAnimeSource`. Command `php artisan anime:import {mal_id}` dùng Action `ImportAnime` (tạo hoặc cập nhật theo `external_id`, đồng bộ thể loại). Test bằng `Http::fake()`, không gọi mạng thật.
> 5. Refactor bộ lọc tìm kiếm sang Pipeline.
> 6. Frontend: trang Bảng xếp hạng có tab; chuông thông báo đọc từ `GET /api/me/notifications`.
>
> **Hoàn thành khi:** import được 20 anime thật bằng command; chạy import 2 lần không tạo bản trùng; giải thích được vì sao _không_ dùng Repository cho Eloquent.

### Tự kiểm tra

<details><summary>1. Dấu hiệu nào cho thấy nên dùng Strategy?</summary>

Một `switch`/`if-else` theo "loại" xuất hiện ở nhiều nơi, và mỗi lần thêm loại mới phải sửa tất cả các chỗ đó.

</details>

<details><summary>2. Nhược điểm của Event là gì?</summary>

Luồng chạy không còn đọc thẳng từ trên xuống: phải tìm xem ai lắng nghe event. Dùng cho _hệ quả phụ_ (thông báo, log), không dùng cho logic chính bắt buộc phải thành công cùng transaction.

</details>

<details><summary>3. Vì sao test import không nên gọi Jikan thật?</summary>

Test sẽ chậm, phụ thuộc mạng, có thể bị giới hạn request, và dữ liệu thật thay đổi theo thời gian khiến test lúc pass lúc fail. Test phải cho cùng kết quả mỗi lần chạy.

</details>

---

## Giai đoạn 9 – Kiến trúc Laravel nâng cao (Advanced)

**Mục tiêu:** cộng đồng có review, vai trò kiểm duyệt, quyền riêng tư, đồng bộ dữ liệu tự động, chạy nhanh và an toàn.

### 9.1 Quyền riêng tư: dữ liệu ai được xem

- Thư viện có chế độ `public` / `private` (cột `library_visibility` trên `users`).
- Review có thể bị moderator ẩn (`hidden_at`).

**Không** được dựa vào việc "nhớ thêm điều kiện" ở mọi query: thể nào cũng có chỗ quên. Gom điều kiện vào **một scope duy nhất** và dùng nó ở mọi nơi đọc dữ liệu:

```php
class Review extends Model
{
    public function scopeVisibleTo(Builder $query, ?User $viewer): void
    {
        if ($viewer?->role->canModerate()) {
            return; // moderator thấy cả review bị ẩn
        }

        $query->where(fn ($q) => $q->whereNull('hidden_at')
            ->when($viewer, fn ($q) => $q->orWhere('user_id', $viewer->id))); // tác giả vẫn thấy review của mình
    }
}
```

**Bài test bắt buộc:** user B gọi `GET /api/users/{A}/library` khi thư viện A là private → nhận **404** (không phải 403, để không lộ việc "có tồn tại").

### 9.2 Phân quyền: Policy

```php
class ReviewPolicy
{
    public function update(User $user, Review $review): bool
    {
        return $review->user_id === $user->id;
    }

    public function hide(User $user, Review $review): bool
    {
        return $user->role->canModerate();
    }
}

// Controller
Gate::authorize('hide', $review);
```

Vai trò: `user`, `moderator`, `admin`. Viết bảng quyền (ma trận vai trò × hành động: sửa anime, xóa anime, ẩn review, khóa tài khoản…) trong `docs/` **trước** khi code.

### 9.3 Queue & Job

Việc chậm (gọi Jikan, gửi thông báo hàng loạt, xuất dữ liệu) chạy nền:

```bash
php artisan queue:work --tries=3
```

Nguyên tắc Job: **idempotent** (chạy lại 2 lần không gây hậu quả gấp đôi), truyền ID hoặc model (được serialize thành ID), có `$tries`, `$backoff`, xử lý `failed()`.

### 9.4 Scheduler

```php
// routes/console.php
Schedule::command('anime:sync-airing')->dailyAt('03:00');
Schedule::command('digest:weekly')->weeklyOn(1, '08:00');
```

Đồng bộ anime đang chiếu mỗi đêm: có tập mới thì tạo `Episode` → bắn `EpisodeAired` → thông báo tự chạy (dùng lại toàn bộ giai đoạn 8).

### 9.5 Cache

```php
$ranking = Cache::remember("ranking:{$sort}:page:{$page}", now()->addHour(), fn () =>
    $factory->make($sort)->apply(Anime::query())->paginate(50)
);
```

Quy tắc: cache dữ liệu đọc nhiều, ít đổi; **luôn có kế hoạch cho dữ liệu cũ**. Xếp hạng lệch vài phút chấp nhận được, nên chỉ cần hết hạn sau 1 giờ (TTL). Danh sách thể loại thì đổi rất hiếm, cache lâu và xóa cache khi admin sửa thể loại. Ghi lý do chọn cách nào vào code.

### 9.6 Tổ chức thư mục khi dự án lớn

```
app/
├── Domain/
│   ├── Catalog/       (Anime, Genre, Studio, Episode, Character)
│   ├── Library/       (LibraryEntry, WatchStatus, Actions)
│   ├── Community/     (Review, Policies)
│   └── Import/        (AnimeDataSource, Jikan, ImportAnime)
├── Http/              (Controllers, Requests, Resources, Middleware)
└── Support/           (tiện ích dùng chung, không nghiệp vụ)
```

Chỉ chuyển sang cấu trúc theo domain khi `app/Models` vượt ~15 model. Trước đó cấu trúc mặc định của Laravel là đủ.

### Bài tập giai đoạn 9

> **Bài 9.A**
>
> 1. Review: bảng `reviews` (user_id, anime_id, body, has_spoiler, hidden_at; unique(user_id, anime_id)). CRUD review của chính mình; moderator ẩn/hiện review.
> 2. Policy theo ma trận quyền. Test cho từng ô của ma trận.
> 3. Quyền riêng tư thư viện. Test chứng minh không đọc được thư viện private hay review bị ẩn qua **bất kỳ** endpoint nào (danh sách, chi tiết, trang cá nhân, bảng xếp hạng…).
> 4. Command `anime:sync-airing` chạy theo lịch, dùng Job cho từng anime, tôn trọng giới hạn request của Jikan.
> 5. Thông báo tập mới không bị gửi trùng khi job chạy lại (gợi ý: unique constraint hoặc kiểm tra trước khi gửi).
> 6. Cache bảng xếp hạng + danh sách thể loại. Rate limit API login và API viết review (`throttle`).
> 7. Frontend: trang review có nút "hiện spoiler"; ẩn/hiện nút theo quyền (nhận `can` từ API, không tự đoán vai trò ở frontend).
>
> **Hoàn thành khi:** test riêng tư bao phủ mọi endpoint đọc dữ liệu; job đồng bộ chạy lại 2 lần không tạo tập trùng và không gửi thông báo trùng.

### Tự kiểm tra

<details><summary>1. Nếu dùng Global Scope (tự áp cho mọi query) thay cho scope `visibleTo`, cần cẩn thận gì?</summary>

Query bằng `DB::table()` và raw SQL **không** đi qua global scope. Command/Job chạy không có user đăng nhập nên scope có thể áp sai hoặc không áp. Validation `exists:` cũng bỏ qua scope. Global scope tiện nhưng "ngầm": người đọc code không thấy điều kiện ở đâu ra.

</details>

<details><summary>2. Vì sao trả 404 thay vì 403 khi xem thư viện private?</summary>

403 xác nhận rằng tài nguyên _có tồn tại_, chỉ là không được xem. 404 không tiết lộ gì. Với dữ liệu riêng tư, càng ít lộ thông tin càng tốt.

</details>

<details><summary>3. Vì sao truyền model vào Job, Laravel lại lấy lại từ DB khi chạy?</summary>

`SerializesModels` chỉ lưu ID. Khi job chạy, model được query lại để có dữ liệu mới nhất. Nếu model đã bị xóa thì job lỗi; xử lý bằng `$deleteWhenMissingModels = true`.

</details>

---

## Giai đoạn 10 – Tư duy thiết kế hệ thống (Advanced)

**Mục tiêu:** quyết định kỹ thuật dựa trên yêu cầu và đánh đổi, không dựa trên "nghe nói tốt".

### 10.1 Quy trình thiết kế một tính năng lớn

Áp dụng cho mọi tính năng từ đây về sau. Viết ra `docs/design/<tên>.md` **trước khi code**:

1. **Vấn đề:** ai cần gì, vì sao.
2. **Yêu cầu chức năng:** liệt kê hành vi.
3. **Yêu cầu phi chức năng:** bao nhiêu dữ liệu? bao nhiêu người dùng? cần nhanh cỡ nào? sai thì hậu quả gì?
4. **Mô hình dữ liệu:** bảng, quan hệ, index.
5. **API:** endpoint, request, response, mã lỗi.
6. **Các phương án** (ít nhất 2) + **đánh đổi** của từng cái.
7. **Quyết định** + lý do.
8. **Rủi ro & cách kiểm chứng.**

### 10.2 Ví dụ: Lịch sử xếp hạng theo mùa

_Yêu cầu:_ xem anime X đứng hạng mấy mỗi tuần trong mùa, vẽ biểu đồ lên/xuống hạng.

| Phương án                                                                                  | Ưu                             | Nhược                                          |
| ------------------------------------------------------------------------------------------ | ------------------------------ | ---------------------------------------------- |
| A. Tính lại từ `library_entries` mỗi lần xem                                               | Không cần bảng mới             | Không làm được: dữ liệu quá khứ đã bị ghi đè   |
| B. Snapshot hằng tuần vào `ranking_snapshots` (anime_id, week, rank, score) bằng scheduler | Đọc nhanh, có lịch sử thật     | Thêm bảng, thêm job; dữ liệu trễ tối đa 1 tuần |
| C. Ghi log mọi lượt chấm điểm, tính lại khi cần                                            | Chính xác tuyệt đối, linh hoạt | Dữ liệu rất lớn, tính toán nặng                |

Chọn B: đủ cho yêu cầu, đơn giản nhất _làm được_. Ghi rõ vì sao loại A và C. _Không tối ưu những gì chưa đo._

### 10.3 Những chủ đề cần nắm

- **Idempotency:** bấm "Thêm vào thư viện" 2 lần do mạng chậm → unique constraint + trả về mục đã có (200) thay vì lỗi.
- **Concurrency:** 2 moderator sửa cùng một anime (optimistic lock: cột `version` / so `updated_at`).
- **Soft delete và audit log:** xóa nhầm khôi phục được; biết ai sửa thông tin anime gì, lúc nào.
- **Phiên bản API:** `/api/v1` khi có app mobile dùng chung.
- **Quan sát hệ thống:** log có cấu trúc (kèm `user_id`, request id), theo dõi lỗi, slow query log.
- **Triển khai:** Docker Compose (nginx, php-fpm, mysql, redis, queue worker, scheduler), biến môi trường, migrate khi deploy, rollback.
- **CI:** GitHub Actions chạy Pint, Larastan, Pest, ESLint, vue-tsc, Vitest mỗi lần push.

### Bài tập giai đoạn 10

> **Bài 10.A**
>
> 1. Viết design doc cho Lịch sử xếp hạng theo mùa theo mẫu 10.1, rồi triển khai (frontend vẽ biểu đồ đường).
> 2. Seed 5.000 anime + 500.000 `library_entries`. Đo thời gian API bảng xếp hạng và API tìm kiếm, thêm index, đo lại, ghi kết quả vào design doc.
> 3. Optimistic lock khi sửa anime: người thứ 2 lưu bản cũ phải nhận 409, frontend hiện "Dữ liệu đã bị người khác thay đổi, tải lại?".
> 4. Thống kê cá nhân: tổng thời gian đã xem (dùng lại `formatWatchTime` của bài 1.B), phân bố điểm, thể loại xem nhiều nhất. Viết bằng SQL aggregate, không lặp trong PHP.
> 5. Docker Compose chạy toàn bộ hệ thống bằng 1 lệnh. CI xanh.
>
> **Hoàn thành khi:** người khác clone repo, đọc README, chạy `docker compose up` là dùng được; design doc giải thích được mọi quyết định lớn.

### Hướng mở rộng tiếp theo (tự chọn)

Thảo luận theo từng tập realtime (Laravel Reverb), gợi ý anime "người xem A cũng xem B", tìm kiếm full-text tiếng Nhật/Romaji/tiếng Anh (Laravel Scout + Meilisearch), danh sách tùy chỉnh ("Top 10 isekai của tôi"), theo dõi bạn bè và bảng tin hoạt động, lịch phát sóng theo múi giờ, app mobile dùng chung API.

---

## Bài học từ codebase thực tế (ShopManager-Renew)

ShopManager-Renew là codebase production lâu năm (Laravel 5.7, Vue 2, Vuex) do nhiều người cùng phát triển từ 2018. Phân tích nó giúp bạn thấy **cái gì đã làm tốt nên giữ**, và **cái gì khiến code khó bảo trì theo thời gian** nên tránh trong AniTrack. Những điểm yếu dưới đây là hệ quả tự nhiên của một dự án lớn, nhiều năm, nhiều deadline. Mục đích là học, không phải chê.

### Điều nên học theo

| Thực hành                                                                                                                                              | Ở đâu                         | Áp dụng vào AniTrack                                                     |
| ------------------------------------------------------------------------------------------------------------------------------------------------------ | ----------------------------- | ------------------------------------------------------------------------ |
| Đặt tên cho điều kiện truy vấn bằng **query scope**, xâu chuỗi đọc như câu văn: `TblUserBooking::dateFrom($from)->dateTo($to)->byShop(...)->approve()` | `app/Models/Webikept/Logic/*` | Giai đoạn 4.4                                                            |
| Scope chọn sẵn cột (`scopeSimple`, `forList`, `forMinimal`) chỉ lấy cột cần cho từng màn hình                                                          | `TblShopNotifyLogic.php`      | Giảm dữ liệu thừa                                                        |
| Định dạng output qua **API Resource** (`CommonCollection`, `BookingListResouce`)                                                                       | `app/Http/Resources`          | Giai đoạn 2.3                                                            |
| Có **Exception nghiệp vụ riêng** (`CannotSaveBookingException`)                                                                                        | `app/Exceptions`              | Giai đoạn 5.4                                                            |
| Dùng **Job / Event / Observer** cho việc chạy nền và hệ quả phụ                                                                                        | `app/Jobs`, `app/Observers`   | Giai đoạn 8–9                                                            |
| Frontend có **lớp API riêng** (`resources/js/api/*_api.js`), component không gọi URL trực tiếp                                                         | `resources/js/api`            | Giai đoạn 3.3                                                            |
| Tách Vuex theo module tính năng                                                                                                                        | `resources/js/store/*`        | Giai đoạn 7.2                                                            |
| Chia Model thành **Entity** (khai báo) + **Logic** (trait chứa quan hệ, scope)                                                                         | `app/Models/Webikept`         | Ý tưởng tách trách nhiệm; Laravel hiện đại làm bằng Action/Domain folder |

### Điểm yếu và cách làm tốt hơn

| #   | Vấn đề quan sát được                                           | Ví dụ                                                                                                | Hệ quả                                                                 | Cách tốt hơn (giai đoạn)                            |
| --- | -------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------- | --------------------------------------------------- |
| 1   | **Controller quá lớn**                                         | `BikeListApiController.php` ~1.200 dòng, `DealerApiController.php` ~1.000 dòng                       | Khó đọc, khó test, sửa chỗ này vỡ chỗ kia                              | Controller mỏng + Action class (5)                  |
| 2   | **Một method `index` rẽ nhánh theo `page_name`** bằng `switch` | `BookingApiController::index`                                                                        | Một URL làm nhiều việc khác nhau, route không tự mô tả                 | Mỗi việc một route/method rõ ràng (2)               |
| 3   | **`$request->all()` + `array_get`** thay vì validation         | `booking_detail()`                                                                                   | Input không kiểm soát; ngày sai format bị bỏ qua lặng lẽ               | FormRequest + `validated()` (2)                     |
| 4   | **Code comment-out để lại** và file `_delete_*`                | `BookingApiController`, `store/_delete_toiawase_point_back.js`, `pages/_delete_toiawase-point-back/` | Nhiễu khi đọc, không biết còn dùng hay không                           | Xóa; git đã lưu lịch sử (6)                         |
| 5   | **Hằng số toàn cục rất nhiều** (~675 `define`)                 | `app/define.php` (`COMMON_ON`, `ARG_PAGE_NUMBER`…)                                                   | Không biết hằng thuộc nghiệp vụ nào, không có type                     | `enum` + hằng số trong class (1, 5)                 |
| 6   | **Helper toàn cục chứa phụ thuộc ẩn** (~120 hàm)               | `app/helpers.php`, `dealer_code()` gọi ở mọi nơi                                                     | Hàm phụ thuộc trạng thái đăng nhập mà nhìn chữ ký không thấy; khó test | Inject rõ ràng; lấy user từ request (7, 9)          |
| 7   | **Trait Logic rất dài**                                        | `TblMotorcycleLogic.php` ~3.500 dòng, `MstDealerLogic.php` ~2.000 dòng                               | "God object", mọi thứ dồn về một chỗ                                   | Tách theo use case: Action, Query object (5, 8)     |
| 8   | **Component Vue khổng lồ**                                     | `BikeEditBasicForm.vue` ~5.000 dòng; nhiều file > 1.000 dòng                                         | Không ai đọc hết được; sửa một ô ảnh hưởng cả form                     | Tách component con + composable (3, 7)              |
| 9   | **API dạng callback** `(data, cb, errCb)`                      | `resources/js/api/booking_api.js`                                                                    | Callback lồng nhau, khó xử lý lỗi thống nhất                           | `async/await` trả Promise có type (3.3)             |
| 10  | **Mixin toàn cục lớn** (~600 dòng)                             | `resources/js/bootstrap/mixins.js`                                                                   | `this.xxx` không biết từ đâu ra; trùng tên ngầm                        | Composable trả giá trị rõ ràng (7.3)                |
| 11  | **Gần như không có test** (4 file trong `tests/`)              | `tests/`                                                                                             | Mỗi lần sửa phải test tay, sợ refactor                                 | Test từ giai đoạn 1, nhất là điểm số/trạng thái (6) |
| 12  | **Đặt tên không nhất quán**                                    | `booking_detail()` (snake) cạnh `bookingCount()` (camel); `BookingListResouce` (sai chính tả)        | Khó đoán tên khi tìm kiếm                                              | Pint + quy ước đặt tên (6.1)                        |

### Bài tập: đọc code thật (không sửa source công ty)

> **Bài R.1 – Lần theo luồng.** Chọn màn hình danh sách đặt lịch: bắt đầu từ component Vue trong `resources/js/components/pages/booking/`, lần qua store Vuex → `api/booking_api.js` → route → `BookingApiController` → scope trong `TblUserBookingLogic`. Vẽ sơ đồ ra giấy. **Không dùng AI**, chỉ dùng Ctrl+Click và tìm kiếm.
>
> **Bài R.2 – Viết lại trong AniTrack.** `BookingApiController::booking_detail` lấy danh sách theo khoảng ngày `from`/`to`. Viết chức năng tương đương trong AniTrack: `GET /api/episodes?from=…&to=…` (các tập phát sóng trong khoảng), theo chuẩn: FormRequest validate `from`/`to` là ngày hợp lệ, `to` ≥ `from`, khoảng tối đa 31 ngày; scope; Resource; test. So sánh hai phiên bản, ghi vào LEARNING_LOG: _phiên bản mới tốt hơn ở đâu, và tệ hơn ở đâu (nếu có)?_

---

## Checklist tự review code

In ra, dùng trước mỗi commit. Đây là "AI reviewer" của bạn.

**Đúng**

- [ ] Đã chạy thử happy path, trường hợp rỗng, trường hợp lỗi?
- [ ] Input đã validate? Có dùng `validated()` thay vì `all()`?
- [ ] Ghi nhiều bảng có transaction không? Số liệu tính sẵn (điểm, số thành viên) có được cập nhật không?
- [ ] Có test cho logic quan trọng không? Test có thực sự fail khi code sai không? (thử làm sai code xem)

**An toàn**

- [ ] Có kiểm tra quyền (Policy) không?
- [ ] Thư viện private / review bị ẩn có bị lộ qua endpoint này không?
- [ ] Có lấy user từ token thay vì từ tham số URL không?
- [ ] Có hiển thị dữ liệu người dùng bằng `v-html` không? (nguy cơ XSS: review, synopsis)

**Hiệu năng**

- [ ] Có N+1 không? (bật `preventLazyLoading`)
- [ ] Cột dùng để lọc/sắp xếp có index chưa?
- [ ] Có lấy cột/bản ghi thừa không? Có `chunkById` khi xử lý nhiều dòng chưa?

**Dễ đọc**

- [ ] Tên biến/hàm có nói lên ý định không?
- [ ] Có hàm/file nào quá dài không?
- [ ] Có magic number, code chết, comment thừa không?
- [ ] 6 tháng sau đọc lại có hiểu không?

**Công cụ**

- [ ] `pint`, `phpstan`, `php artisan test` xanh?
- [ ] `npm run lint`, `vue-tsc --noEmit`, `vitest run` xanh?

---

## Nhật ký học tập & lịch học

### Mẫu LEARNING_LOG.md

```markdown
## YYYY-MM-DD – Giai đoạn X, Bài Y: <chủ đề>

- Mục tiêu hôm nay:
- Tôi đã tự làm gì:
- Tôi hiểu thêm điều gì (viết như đang giải thích cho người khác):
- Vấn đề gặp phải:
- Tôi đã debug / kiểm chứng thế nào (đến bậc mấy của thang gỡ bí):
- Phần nào tôi vẫn chưa hiểu (câu hỏi cụ thể):
- Nếu làm lại, tôi sẽ làm khác điều gì:
- Tự viết lại được mà không nhìn tài liệu? (Có / Một phần / Chưa)
```

### Một buổi học (45–60 phút)

| Thời gian  | Việc                                               |
| ---------- | -------------------------------------------------- |
| 5 phút     | Đọc lại log buổi trước, chọn **một** mục tiêu nhỏ. |
| 30–40 phút | Tự đọc tài liệu + code.                            |
| 10 phút    | Kiểm chứng: chạy test, thử tay, chạy công cụ.      |
| 5 phút     | Ghi log, commit.                                   |

### Nhịp tuần

- **4 buổi** làm bài tập.
- **1 buổi ôn:** chọn code tuần trước, đóng file lại, **viết lại từ đầu** không nhìn. Phần nào không viết được là phần chưa thật sự hiểu.

### Mỗi khi xong một giai đoạn: bài kiểm tra độc lập

Tự đặt một yêu cầu nhỏ **chưa có trong tài liệu** (ví dụ: "quản lý seiyuu (diễn viên lồng tiếng) và vai diễn của họ", "đánh dấu anime yêu thích"), tự phân tích → thiết kế → code → test, giới hạn trong 3 buổi. Làm được nghĩa là kiến thức đã thành của bạn.

### Tiêu chí tiến bộ

| Năng lực | Beginner                                    | Intermediate                                 | Advanced                                                 |
| -------- | ------------------------------------------- | -------------------------------------------- | -------------------------------------------------------- |
| Đọc code | Lần theo một request qua controller → model | Lần theo luồng qua nhiều lớp, event, job     | Đánh giá được thiết kế, chỉ ra rủi ro                    |
| Laravel  | CRUD + validation + resource                | Quan hệ n-n, transaction, Action, lock, test | Policy, quyền riêng tư, queue, cache, tích hợp API ngoài |
| Vue      | Component, props/emits, gọi API             | Composable, Pinia, router guard, xử lý lỗi   | Kiến trúc thư mục, quy tắc phụ thuộc, test component     |
| Database | Thiết kế bảng đơn, khóa ngoại               | JOIN, index, N+1, EXPLAIN, denormalization   | Tối ưu dữ liệu lớn, khóa, concurrency, snapshot          |
| Tư duy   | Chia input/output/quy tắc/biên              | Chọn đúng chỗ đặt logic                      | Viết design doc, so sánh phương án, đo đạc               |

---

## Cam kết

> Tôi sẽ tự suy nghĩ trước, tự kiểm chứng, và chỉ tìm trợ giúp khi đã viết ra chính xác điều mình chưa hiểu.
> Tôi ưu tiên hiểu sâu một phần nhỏ hơn là hoàn thành thật nhiều mà không làm chủ được code.
> Năng lực của tôi được đo bằng số vấn đề tôi có thể tự phân tích, thiết kế và sửa chữa.
