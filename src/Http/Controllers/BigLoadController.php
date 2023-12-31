<?php

namespace CronixWeb\BigCommerceAuth\Http\Controllers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use CronixWeb\BigCommerceAuth\Facades\BigCommerceAuth;
use CronixWeb\BigCommerceAuth\Models\Store;

class BigLoadController extends Controller
{
    public function load(Request $request)
    {
        $this->validatePerms($request);

        $redirect_path = Config::get('bigcommerce-auth.redirect_path', '/');

        if ($this->verifyAndLoginUserIfNot($request))
            return Response::redirectTo($redirect_path);

        App::abort(403);
    }

    /**
     * Validate Parameters
     * @param Request $request
     * @return void
     */
    protected function validatePerms(Request $request)
    {
        $request->validate([
            'signed_payload' => 'required|string'
        ]);
    }

    protected function verifyAndLoginUserIfNot(Request $request): bool
    {
        $signed_payload = BigCommerceAuth::verifySignedPayload($request->get('signed_payload'));
        if ($signed_payload) {
            $user = $this->getUserModelClass()::query()
                ->where('email', $signed_payload['user']['email'])
                ->first();
            if (!$user) {
                $user = $this->saveUserIfNotExist($signed_payload['user']['email']);
                if (!$user) {
                    return false;
                }
                $store = $this->getStoreModelClass()::query()
                    ->where('hash', $signed_payload['store_hash'])
                    ->first();
                if (!$store) {
                    return false;
                }
                if (!$this->assignUserToStore($user->id, $store->id)) {
                    return false;
                }
            }
            Auth::login($user);
            BigCommerceAuth::setStoreHash($signed_payload['store_hash']);
            $store = $this->getStoreModelClass()::query()
                ->where('hash', $signed_payload['store_hash'])
                ->first();
            BigCommerceAuth::callLoadCallback($user, $store);
            return true;
        }
        return false;
    }

    /**
     * @param $email
     * @return Model|Builder|Authenticatable
     */
    protected function saveUserIfNotExist($email)
    {
        return $this->getUserModelClass()::query()->firstOrCreate([
            'email' => $email
        ]);
    }

    protected function assignUserToStore($user_id, $store_id): bool
    {
        $store_has_users = Config::get('bigcommerce-auth.tables.store_has_users');
        if (DB::table($store_has_users)
            ->where('store_id', $store_id)
            ->where('user_id', $user_id)
            ->exists())
            return true;

        return DB::table($store_has_users)->insert([
            'store_id' => $store_id,
            'user_id' => $user_id,
        ]);
    }

    protected function getUserModelClass(): string
    {
        return Config::get('auth.providers.users.model');
    }

    protected function getStoreModelClass(): string
    {
        return Config::get('bigcommerce-auth.models.store_model');
    }
}