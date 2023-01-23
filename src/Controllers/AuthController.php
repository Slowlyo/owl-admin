<?php

namespace Slowlyo\SlowAdmin\Controllers;

use Illuminate\Http\Request;
use Slowlyo\SlowAdmin\SlowAdmin;
use Slowlyo\SlowAdmin\Libs\Captcha;
use Illuminate\Support\Facades\Hash;
use Slowlyo\SlowAdmin\Renderers\Page;
use Slowlyo\SlowAdmin\Models\AdminUser;
use Illuminate\Support\Facades\Validator;
use Slowlyo\SlowAdmin\Renderers\Form;
use Slowlyo\SlowAdmin\Renderers\TextControl;
use Slowlyo\SlowAdmin\Renderers\ImageControl;
use Symfony\Component\HttpFoundation\Response;
use Slowlyo\SlowAdmin\Services\AdminUserService;

class AuthController extends AdminController
{
    protected string $serviceName = AdminUserService::class;

    public function login(Request $request)
    {
        if (config('admin.auth.login_captcha')) {
            if (!$request->has('captcha')) {
                return $this->response()->fail(__('admin.required', ['attribute' => __('admin.captcha')]));
            }

            if (strtolower(admin_decode($request->sys_captcha)) != strtolower($request->captcha)) {
                return $this->response()->fail(__('admin.captcha_error'));
            }
        }

        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required',
                'password' => 'required',
            ], [
                'username' . '.required' => __('admin.required', ['attribute' => __('admin.username')]),
                'password.required'      => __('admin.required', ['attribute' => __('admin.password')]),
            ]);

            if ($validator->fails()) {
                abort(Response::HTTP_BAD_REQUEST, $validator->errors()->first());
            }
            $adminModel = config("admin.auth.model");
            $user = $adminModel::query()->where('username', $request->username)->first();
            if ($user && Hash::check($request->password, $user->password)) {
                $token = $user->createToken('admin')->plainTextToken;

                return $this->response()->success(compact('token'), __('admin.login_successful'));
            }

            abort(Response::HTTP_BAD_REQUEST, __('admin.login_failed'));
        } catch (\Exception $e) {
            return $this->response()->fail($e->getMessage());
        }
    }

    /**
     * 刷新验证码
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
     */
    public function reloadCaptcha()
    {
        $captcha = new Captcha();

        $captcha_img = $captcha->showImg();
        $sys_captcha = admin_encode($captcha->getCaptcha());

        return $this->response()->success(compact('captcha_img', 'sys_captcha'));
    }

    public function logout(): \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
    {
        $this->guard()->user()->currentAccessToken()->delete();

        return $this->response()->successMessage();
    }

    protected function guard(): \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
    {
        return SlowAdmin::guard();
    }

    public function currentUser()
    {
        return $this->response()->success(SlowAdmin::user()->only(['id', 'name', 'avatar']));
    }

    public function userSetting(): \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
    {
        $user = $this->user()->makeHidden([
            'username',
            'password',
            'remember_token',
            'created_at',
            'updated_at',
            'roles',
        ]);

        $form = Form::make()
            ->title('')
            ->panelClassName('px-48 m:px-0')
            ->mode('horizontal')
            ->data($user)
            ->api('put:' . admin_url('/user_setting/' . $user->id))
            ->body([
                ImageControl::make()
                    ->label(__('admin.admin_user.avatar'))
                    ->name('avatar')
                    ->receiver($this->uploadImagePath()),
                TextControl::make()->label(__('admin.admin_user.name'))->name('name')->required(true),
                TextControl::make()->type('input-password')->label(__('admin.old_password'))->name('old_password'),
                TextControl::make()->type('input-password')->label(__('admin.password'))->name('password'),
                TextControl::make()
                    ->type('input-password')
                    ->label(__('admin.confirm_password'))
                    ->name('confirm_password'),
            ]);

        $page = Page::make()->title(__('admin.user_setting'))->body($form);

        return $this->response()->success($page);
    }

    public function saveUserSetting($id): \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
    {
        $result = $this->service->updateUserSetting($id,
            request()->only([
                'avatar',
                'name',
                'old_password',
                'password',
                'confirm_password',
            ]));

        return $this->autoResponse($result);
    }
}
