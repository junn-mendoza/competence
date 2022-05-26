<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Task

Complete the following task - create a Laravel project that allows a user to login and create, update and delete calendar entries consisting of a title, body and due date. The entries should be orderable by name, created date and scheduled date. This can either be an API or have a front end - whichever the developer is more comfortable with is fine. Key points we're looking for are how the code is organised on the back end and how requests are handled.

1.) allows a user to login and create, update and delete calendar

```php
Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('/logout',[AuthController::class,'logout']);
    Route::apiResource('calendars', CalendarController::class);
});

Route::post('/register',[AuthController::class,'register']);

Route::post('/login',[AuthController::class,'login']);
```

2.) Entries consisting of a title, body and due date.

```php
public function up()
    {
        Schema::create('calendar', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('title');
            $table->string('body');
            $table->timestamp('due_date')->nullable();           
            $table->timestamps();
        });
    }
```

3.) The entries should be orderable by name, created date and scheduled date.

```php
return ResourcesCalendar::collection(
            Calendar::where('user_id',auth()->user()->id)
            ->get()
            ->sortBy('title')
            ->sortBy('created_at')
            ->sortBy('due_date')
            )->response();
```

4.) This can either be an API or have a front end

```diff
- I created an API for this project with sanctum authentication.
```

5.) Key points we're looking for are how the code is organised on the back end and how requests are handled.

- Created Resources and Form Request in this poroject.
- Form request is factor using multiple class.

```php
use App\Http\Requests\BaseRequest;

class UserRequest extends BaseRequest
{
```

```php
use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
```

- Use Json resource as basis for JSON API standard

```php
class Calendar extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        
        return [
            'id' => (string)$this->id,
            'type' => 'Calendars',
            'attributes' => [
                'title' => $this->title,
                'body'  => $this->body,
                'due_date' => $this->formatDate($this->due_date),
                'created_at' => $this->formatDate($this->created_at),
                'update_at' => $this->formatDate($this->updated_at),
            ]

        ];
    }
```

- Use a single validation rule for both insert and update

```php
class UserRequest extends BaseRequest
{
     /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if($this->path() == 'api/login'){
            return [
                'email' => 'required|email',
                'password' => 'required|string'
            ];
        }
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed'
        ];
    }
```

- Best practice to put the error, warning or information messages in the model which the messages belongs to.

```php
class Calendar extends Model
{
    use HasFactory;
    public const CALENDAR_SUCCESSFULLY_CREATED = 201;
    public const CALENDAR_SUCCESSFULLY_UPDATED = 200;
    public const CALENDAR_NOT_SAVED = 424;
    public const CALENDAR_SUCCESSFULLY_DELETED = 200;
    public const CALENDAR_DELETE_UNSUCCESSFULLY = 424;
    public const CALENDAR_NOT_FOUND = 404;
```

```php
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    public const USER_SUCCESSFULLY_CREATED = 201;
    public const USER_SUCCESSFULLY_LOGGED = 200;
    public const USER_NOT_FOUND = 404;
    public const USER_BAD_CREDENTIAL = 401;
    public const USER_LOG_OUT = 200;
```