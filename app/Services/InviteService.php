<?php


namespace App\Services;


use App\Models\Company;
use App\Models\Invite;
use App\Models\User;
use Illuminate\Validation\Rules\In;

class InviteService
{
    /**
     * Create User Company By Token
     * @param string $token
     *
     * @return User | bool
    */
    public static function process(string $token)
    {
        $invite = Invite::when(!is_null($token) && !empty(trim($token)), function ($query) use ($token) {
            return $query->where('token', trim($token));
        })
            ->where('status', 'pending')
            ->first();
        if ($invite) {
            $company = CompanyService::create(trim($invite->email), trim($invite->name));
            $invite->update(['status' => 'used']);
            \Log::info('Company ' . $invite->email . ' created');
            return $company;
        }else{
            return false;
        }

        \Log::error('Can\' create this company something is wrong.');
        return false;

    }

    /**
     * Retrieve User By Token
     * @param string $token
     * @return  User |  bool
    */
    public static function getUserByToken(string $token)
    {
        $invite = Invite::when(!is_null($token) && !empty(trim($token)), function ($query) use ($token) {
            return $query->where('token', trim($token));
        })->where('status', 'used')
            ->first();

        if($invite) {
            return User::where('email', $invite->email)->first();
        }
        return false;
    }

    /**
     * Created Company invited and logged with okta
     * @param string $email
     * @return Company $company | bool
    */
    public function getCompanyInvitedByEmail(string $email)
    {
        $invite = Invite::where('email', $email)->first();
        if ($invite) {
            $company = CompanyService::create(trim($invite->email), trim($invite->name));
            $invite->update(['status' => 'used']);
            \Log::info('Company ' . $invite->email . ' created');
            return $company;
        }else{
            return false;
        }

        \Log::error('Can\' create this company something is wrong.');
        return false;
    }

}