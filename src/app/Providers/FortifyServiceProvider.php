<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\RegisterResponse;


class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);

        /*会員登録画面の表示*/
        Fortify::registerView(function () {
            return view ('auth.register');
        });

        /*ログイン画面の表示*/
        Fortify::loginView(function () {
            return view('auth.login');
        });

         // ログイン後のリダイレクト
        $this->app->instance(LoginResponse::class, new class implements LoginResponse {
        public function toResponse($request)
        {
            return redirect('/');
        }
        });

        // 登録後のリダイレクト
        $this->app->instance(RegisterResponse::class, new class implements RegisterResponse {
        public function toResponse($request)
        {
            return redirect('/mypage/profile');
        }
        });

        //ログイン失敗時エラーメッセージ
        Fortify::authenticateUsing(function ($request) {
            $user = User::where('email',$request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }
            throw ValidationException::withMessages([
                'email' => ['ログイン情報が登録されていません'],
            ]);
        });

        //ログインのタイムリミット設定
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(10)->by($email . $request->ip());
        });

        // カスタム LoginRequest を Fortify にバインド
        $this->app->bind(FortifyLoginRequest::class, LoginRequest::class);

    }

}
