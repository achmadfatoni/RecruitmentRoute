<?php

namespace Klsandbox\RecruitmentRoute\Http\Controllers;

use App;
use Klsandbox\RecruitmentRoute\Http\Requests\JoinPhonePostRequest;
use Klsandbox\RecruitmentRoute\Models\Recruitment;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use DB;
use Input;
use Klsandbox\NotificationService\Models\NotificationRequest;
use Klsandbox\RoleModel\Role;
use Klsandbox\SiteModel\Site;
use Request;
use Session;
use App\Http\Controllers\Controller;

class RecruitmentManagementController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function validator(array $data)
    {
        $user = auth()->user();
        $id = $user->id;

        $rules = [
            'recruitment_key' => 'required|min:5|max:300|alpha_dash|unique:users,recruitment_key,' . $id . ',id|recruitment_stockist',

        ];
        if ($user->access()->dropship) {
            $rules = [
                'recruitment_key' => 'required|min:5|max:300|alpha_dash||different:recruitment_dropship_key|unique:users,recruitment_key,' . $id . ',id|recruitment_stockist:'.$id,
                'recruitment_dropship_key' => 'required|min:5|max:300|alpha_dash||different:recruitment_key|unique:users,recruitment_dropship_key,' . $id . ',id|recruitment_dropship:'.$id
            ];
        }

        return \Validator::make($data, $rules);
    }

    public function getSettings()
    {
        User::stockistGuard();

        $user = Auth::user();

        return view('recruitment-route::settings')
            ->with('recruitment_key', $user->recruitment_key)
            ->with('recruitment_dropship_key', $user->recruitment_dropship_key)
            ->withRole($user->role);
    }

    public function getListRecruitments(User $user)
    {
        $start_date = new Carbon(date('Y-m-01'));
        $end_date = new Carbon(date('Y-m-01'));
        $end_date->endOfMonth();

        $data = Recruitment::where('user_id', $user->id)->orderBy('created_at', 'DESC')->get();
        $data_month = Recruitment::where('user_id', $user->id)
            ->where('recruitments.created_at', '>=', $start_date)
            ->where('recruitments.created_at', '<=', $end_date)
            ->count();

        return view('recruitment-route::list-recruitments')
            ->with('data', $data)
            ->with('data_month', $data_month);
    }

    // Only admin can ship the order
    public function postSettings()
    {
        User::stockistGuard();
        $user = Auth::user();

        $recruitment_key = Input::get('recruitment_key');

        $messages = $this->validator(Input::all());

        if ($messages->messages()->count()) {
            Request::flashOnly('recruitment_key');

            return view('recruitment-route::settings')
                ->withRole($user->role)
                ->with('recruitment_dropship_key', $user->recruitment_dropship_key)
                ->withErrors($messages);
        }
        $user->recruitment_key = $recruitment_key;

        if ($user->access()->dropship) {
            $user->recruitment_dropship_key = Input::get('recruitment_dropship_key');
        }

        $user->save();

        Session::flash('success_message', 'Recruitment key has been updated.');

        return view('recruitment-route::settings')
            ->with('recruitment_key', $user->recruitment_key)
            ->with('recruitment_dropship_key', $user->recruitment_dropship_key)
            ->withRole($user->role);
    }

    public function getTopRecruitmentUsers($filter)
    {
        if (!Auth::user()->admin()) {
            App::abort(500, 'Unauthorized');
        }

        switch ($filter) {
            case 'monthly':
                $start_date = new Carbon(date('Y-m-01'));
                $end_date = new Carbon(date('Y-m-01'));
                $end_date->endOfMonth();

                $data = User::select(['users.*', DB::raw('count(recruitments.id) as total')])
                    ->where('recruitments.created_at', '>=', $start_date)
                    ->where('recruitments.created_at', '<=', $end_date)
                    ->leftJoin('recruitments', 'users.id', '=', 'recruitments.user_id')
                    ->groupBy('users.id')
                    ->orderBy('total', 'DESC')->get();
                break;
            case 'all':
            default:
                $data = User::select(['users.*', DB::raw('count(recruitments.id) as total')])
                    ->leftJoin('recruitments', 'users.id', '=', 'recruitments.user_id')
                    ->groupBy('users.id')
                    ->orderBy('total', 'DESC')->get();
                break;
        }

        foreach ($data as $key => $val) {
            if ($val->total <= 0) {
                unset($data[$key]);
            }
        }

        return view('recruitment-route::leaderboard')
            ->with('data', $data);
    }

    public function getJoin($recruitment_key)
    {

        $findUser = User::where('recruitment_key', $recruitment_key)
            ->orWhere('recruitment_dropship_key', $recruitment_key)
            ->first();

        if (! $findUser) {
            App::abort(404, 'Recuitment key not found');
        }

        if ($findUser->recruitment_key == $recruitment_key) {
            $role = Role::whereSiteId($findUser->site_id)
                ->whereName('dropship')
                ->first();
        } else {
            $role = Role::whereSiteId($findUser->site_id)
                ->whereName('stockist')
                ->first();
        }

        if ($role->name == 'dropship') {
            return $this->joinDropship($findUser, $recruitment_key, $role);
        };

        return $this->joinStockist($findUser, $recruitment_key, $role);
    }

    public function postPhone(JoinPhonePostRequest $request)
    {
        $user_hash = Input::get('user_hash');
        $user = User::findWithHash($user_hash);
        Site::protect($user);

        $role = Role::findByName(Input::get('role'));

        if (! $role) {
            App::abort(404, 'Role not found');
        }

        $recruitment = Recruitment::create([
            'name' => $user_hash,
            'phone_number' => Input::get('phone'),
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);

        NotificationRequest::create([
            'target_id' => $recruitment->id,
            'route' => 'recruitment-added',
            'channel' => 'Sms',
            'to_user_id' => $user->id,
        ]);

        User::createUserEvent($user, [
            'controller' => 'timeline',
            'route' => '/new-recruitment',
            'target_id' => $recruitment->id,
        ]);

        return back();
    }

    /**
     * @param $recruitment_key
     * @param $role
     * @return mixed
     */
    public function joinStockist($user, $recruitment_key, $role)
    {
        return view('recruitment-route::join')
            ->withUser($user)
            ->withRole($role)
            ->with('recruitment_key', $recruitment_key);
    }

    private function joinDropship($user, $recruitment_key, $role)
    {
        return view('recruitment-route::join_dropship')
            ->withUser($user)
            ->withRole($role)
            ->with('recruitment_key', $recruitment_key);
    }
}
