<?php

namespace App\Managers;

use App\Models\Employee;
use App\Models\Invite;
use App\Models\User;
use App\Services\InviteService;

class UserManager
{
    public static function createEmployee( $data = [])
    {
        if(isset($data['email']) && !empty(trim($data['email']))) {

            $data = [
                'type' => 'employee',
                'status_id' => 3,
                'status' => 'active',
                'confirmed' => 'approved',
                'accept_agreements' => true,
                'email' => $data['email'],
                'password' => \Hash::make(\Str::random(9))
            ];
            $user = User::create($data);
            $data = [
                'first_name' => $data['first_name'] ?? 'unknown name',
                'last_name' => $data['last_name'] ?? 'unknown name',
            ];

            $employee = Employee::create($data);
            $user->employee()->save($employee);
            return $user;
        }
        return false;
    }

    public  function processOktaUser(User  $localUser, $socialUser)
    {
        $type = $localUser->type;

        if ($type === 'employee') {
            if ($localUser->employee) {
                $employee = $localUser->employee;
                if (isset($socialUser->first_name)
                    && !empty(trim($socialUser->first_name))
                    && $socialUser->first_name != $employee->first_name) {
                    $employee->first_name = $socialUser->first_name;
                }

                if (isset($socialUser->last_name)
                    && !empty(trim($socialUser->last_name))
                    && $socialUser->last_name != $employee->last_name) {
                    $employee->last_name = $socialUser->last_name;
                }


                $employee->save();
            }
        } elseif ($type === 'company') {
            if ($localUser->company) {
                $company = $localUser->company;
                if (isset($socialUser->name)
                    && !empty(trim($socialUser->name))
                    && $socialUser->name != $company->name) {
                    $company->name = $socialUser->name;
                }

                if (isset($socialUser->name)
                    && !empty(trim($socialUser->address))
                    && $socialUser->address != $company->location) {
                    $company->location = $socialUser->address;
                }
                $company->save();
            }
        } elseif ($type === 'admin' || $type === 'super') {
            if ($localUser->admin) {
                $admin = $localUser->admin;
                if (isset($socialUser->name)
                    && !empty(trim($socialUser->name))
                    && $socialUser->name != $admin->name) {
                    $admin->name = $socialUser->name;
                }

                if (isset($socialUser->name)
                    && !empty(trim($socialUser->address))
                    && $socialUser->address != $admin->location) {
                    $admin->location = $socialUser->address;
                }
                $admin->save();
            }
        }

        if($localUser->confirmed === 'pending') {
            $localUser->update([
                'confirmed' => 'approved',
                'accept_agreements' => true,
                'password' => \Hash::make(\Str::random(9))
            ]);
        }
        $localUser->token = $socialUser->token;
        $localUser->save();
        return $localUser;
    }
}