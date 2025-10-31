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
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Contracts\RegisterResponse;


class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse {

        public function toResponse($request)
        {
            return redirect('/login');
        }
    });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::registerView(function () {
            return view ('auth.register');
        });

        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });


        $this->app->instance(RegisterResponse::class, new class implements RegisterResponse {
        public function toResponse($request)
        {
            return redirect()->route('verification.notice');
        }
        });


        Fortify::authenticateUsing(function ($request) {
            $user = User::where('email',$request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                if (!$user->hasVerifiedEmail()) {
                    session(['redirect_to_verify' => true]);
                }
                return $user;
            }
            throw ValidationException::withMessages([
                'email' => ['ログイン情報が登録されていません'],
            ]);
        });


        $this->app->instance(LoginResponse::class, new class implements LoginResponse {
        public function toResponse($request)
        {
            if (session('redirect_to_verify')) {
                session()->forget('redirect_to_verify');
                return redirect()->route('verification.notice');
            }
            return redirect('/');
        }
        });


        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(10)->by($email . $request->ip());
        });


        $this->app->bind(FortifyLoginRequest::class, LoginRequest::class);
    }
}
