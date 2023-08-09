<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Theater;
use App\Models\Ticket;
use App\Models\TicketSeat;
use App\Models\User;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    function __construct()
    {
    }

    public function home(Request $request)
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh')->endOfDay();
        $year = Carbon::now('Asia/Ho_Chi_Minh')->subDays(365)->startOfYear()->toDateString();
        $start_of_month = Carbon::now('Asia/Ho_Chi_Minh')->startOfMonth();
        $total_year = Ticket::whereBetween('created_at', [$year, $now])->where('hasPaid', 1)->orderBy('created_at', 'ASC')->get();
        $theaters = Theater::orderBy('id', 'ASC')->take(4)->get();
        $ticket = Ticket::whereDate('created_at', Carbon::today())->where('hasPaid', 1)->get();
        $ticket_seat = TicketSeat::get()->whereBetween('created_at', [$year, $now])->count();
        $user = User::role('user')->get();
        $movies = Movie::all();

        foreach ($theaters as $theater) {
            $total_seat = 0;
            $total_price = 0;
            foreach ($theater['rooms'] as $theater_room) {
                foreach ($theater_room['schedules'] as $theater_schedule) {
                    foreach ($theater_schedule['Ticket'] as $theater_ticket) {
                        $total_seat += $theater_ticket['ticketseats']->count();
                        $total_price += $theater_ticket['totalPrice'];
                    }
                }
            }
            $theater->setAttribute('totalPrice', $total_price);
            $theater->setAttribute('ticketseats', $total_seat);
        }

        foreach ($movies as $movie) {
            $total_seat = 0;
            $total_price = 0;
            foreach ($movie['schedules'] as $movie_schedule) {
                foreach ($movie_schedule['Ticket'] as $movie_ticket) {
                    $total_seat += $movie_ticket['ticketseats']->count();
                    $total_price += $movie_ticket['totalPrice'];
                }
            }
            $movie->setAttribute('totalPrice', $total_price);
            $movie->setAttribute('ticketseats', $total_seat);
        }

        $movies = $movies->sortByDesc('totalPrice');



        $sum = 0;
        $sum_today = 0;

        //total of month
        foreach ($total_year as $value) {
            $sum += $value['totalPrice'];
        }
        //total today
        foreach ($ticket as $today) {
            $sum_today += $today['totalPrice'];
        }

        return view('admin.home.list', [
            'user' => $user,
            'ticket' => $ticket,
            'sum' => $sum,
            'sum_today' => $sum_today,
            'now' => $now,
            'start_of_month' => $start_of_month,
            'ticket_seat' => $ticket_seat,
            'year' => $year,
            'theaters' => $theaters,
            'movies' => $movies
        ]);
    }
    public function filter_by_date(Request $request)
    {
        $start_time = Carbon::createFromFormat('Y-m-d', $request->from_date)->startOfDay();
        $end_time = Carbon::createFromFormat('Y-m-d', $request->to_date)->endOfDay(); // lấy ngày cuối cùng

        $get = Ticket::whereBetween('created_at', [$start_time, $end_time])->where('holdState', 0)->orderBy('created_at', 'ASC')->get();
        $value_first = $get->first();
        $value_last = $get->last();

        $date_current = date("d-m-Y", strtotime($value_first['created_at']));

        $total = 0;
        $seat_count = 0;
        $chart_data = [];

        foreach ($get as $value) {
            if ($date_current == date("d-m-Y", strtotime($value['created_at']))) {
                $total += $value['totalPrice'];
                $seat_count += $value['ticketSeats']->count();
            } else {
                $data = array(
                    'date' =>  $date_current,
                    'total' => $total,
                    'seat_count' => $seat_count
                );
                $date_current = date("d-m-Y", strtotime($value['created_at']));
                $total = $value['totalPrice'];
                $seat_count = $value['ticketSeats']->count();
                array_push($chart_data, $data);
            }
            if ($value_last->id == $value['id']) {
                $data = array(
                    'date' => date("d-m-Y", strtotime($value['created_at'])),
                    'total' => $total,
                    'seat_count' => $seat_count
                );
                array_push($chart_data, $data);
            }
        }
        return response()->json([
            'success' => 'Thành công',
            'chart_data' => $chart_data
        ]);
    }

    public function statistical_filter(Request $request)
    {

        $now = Carbon::now('Asia/Ho_Chi_Minh')->endOfDay();
        $week = Carbon::now('Asia/Ho_Chi_Minh')->subDays(7)->startOfDay()->toDateString();
        $this_month = Carbon::now('Asia/Ho_Chi_Minh')->startOfMonth()->toDateString();
        $start_last_month = Carbon::now('Asia/Ho_Chi_Minh')->subMonth()->startOfMonth()->toDateString();
        $end_last_month = Carbon::now('Asia/Ho_Chi_Minh')->subMonth()->endOfMonth()->toDateString();
        $year = Carbon::now('Asia/Ho_Chi_Minh')->subDays(365)->startOfYear()->toDateString();

        if ($request['statistical_value'] == 'week') {
            $get = Ticket::whereBetween('created_at', [$week, $now])->where('holdState', 0)->orderBy('created_at', 'ASC')->get();
            $value_first = $get->first();
            $value_last = $get->last();
            $date_current = date("d-m-Y", strtotime($value_first['created_at']));
        }
        if ($request['statistical_value'] == 'year') {
            $get = Ticket::whereBetween('created_at', [$year, $now])->where('holdState', 0)->orderBy('created_at', 'ASC')->get();
            $value_first = $get->first();
            $value_last = $get->last();
            $date_current = date("m-Y", strtotime($value_first['created_at']));
        }
        if ($request['statistical_value'] == 'this_month') {
            $get = Ticket::whereBetween('created_at', [$this_month, $now])->where('holdState', 0)->orderBy('created_at', 'ASC')->get();
            $value_first = $get->first();
            $value_last = $get->last();
            $date_current = date("d-m-Y", strtotime($value_first['created_at']));
        }
        if ($request['statistical_value'] == 'last_month') {
            $get = Ticket::whereBetween('created_at', [$start_last_month, $end_last_month])->where('holdState', 0)->orderBy('created_at', 'ASC')->get();
            $value_first = $get->first();
            $value_last = $get->last();
            $date_current = date("d-m-Y", strtotime($value_first['created_at']));
        }
        function date_statistical($option, $date)
        {
            if ($option == 'year') {
                return date("m-Y", strtotime($date));
            } else {
                return date("d-m-Y", strtotime($date));
            }
        }
        $total = 0;
        $seat_count = 0;
        $chart_data = [];

        foreach ($get as $value) {
            if ($date_current == date_statistical($request['statistical_value'], $value['created_at'])) {
                $total += $value['totalPrice'];
                $seat_count += $value['ticketSeats']->count();
            } else {
                $data = array(
                    'date' =>  $date_current,
                    'total' => $total,
                    'seat_count' => $seat_count
                );
                $date_current = date_statistical($request['statistical_value'], $value['created_at']);
                $total = $value['totalPrice'];
                $seat_count = $value['ticketSeats']->count();
                array_push($chart_data, $data);
            }
            if ($value_last->id == $value['id']) {
                $data = array(
                    'date' => date_statistical($request['statistical_value'], $value['created_at']),
                    'total' => $total,
                    'seat_count' => $seat_count
                );
                array_push($chart_data, $data);
            }
        }

        return response()->json([
            'success' => 'Thành công',
            'get' => $get,
            'chart_data' => $chart_data,
        ]);
    }

    public function statistical_sortby(Request $request)
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh')->endOfDay();
        $year = Carbon::now('Asia/Ho_Chi_Minh')->subDays(365)->startOfYear()->toDateString();

        $get = Ticket::whereBetween('created_at', [$year, $now])->where('holdState', 0)->orderBy('created_at', 'ASC')->get();
        $value_first = $get->first();
        $value_last = $get->last();
        $date_current = date("m-Y", strtotime($value_first['created_at']));

        $seat_count = 0;
        $theaters = Theater::all();
        foreach ($theaters as $theater) {
            $total[$theater->id] = 0;
        }
        $chart_data = [];
        if ($request['statistical_value'] == 'ticket') {
            foreach ($get as $value) {
                if ($date_current == date("m-Y", strtotime($value['created_at']))) {
                    $seat_count += $value['ticketSeats']->count();
                } else {
                    $data = array(
                        'date' =>  $date_current,
                        'seat_count' => $seat_count
                    );
                    $date_current = date("m-Y", strtotime($value['created_at']));
                    $seat_count = $value['ticketSeats']->count();
                    array_push($chart_data, $data);
                }
                if ($value_last->id == $value['id']) {
                    $data = array(
                        'date' => date("m-Y", strtotime($value['created_at'])),
                        'seat_count' => $seat_count
                    );
                    array_push($chart_data, $data);
                }
            }
        }
        if ($request['statistical_value'] == 'theater') {
            foreach ($get as $value) {
                if ($date_current == date("m-Y", strtotime($value['created_at']))) {
                    if ($value->schedule_id != null) {
                        $total[$value->schedule->room->theater_id] += $value['totalPrice'];
                    }
                } else {
                    $data = array(
                        'date' =>  $date_current,
                    );
                    foreach ($theaters as $theater) {

                        $data[$theater->id] = $total[$theater->id];
                        //                        dd($data);
                    }
                    $date_current = date("m-Y", strtotime($value['created_at']));
                    foreach ($theaters as $theater) {
                        if ($value->schedule_id != null && $value->schedule->room->theater_id == $theater->id) {
                            $total[$theater->id] = $value['totalPrice'];
                        } else {
                            $total[$theater->id] = 0;
                        }
                    }
                    array_push($chart_data, $data);
                }
                if ($value_last->id == $value['id']) {
                    $data = array(
                        'date' =>  $date_current,
                    );
                    foreach ($theaters as $theater) {
                        $data[$theater->id] = $total[$theater->id];
                        //                        dd($data);
                    }
                }
            }
        }
        //        if($request['statistical_value'] == 'genre'){
        //
        //        }
        return response()->json([
            'success' => 'Thành công',
            'chart_data' => $chart_data,
        ]);
    }
    //User
    public function user()
    {
        $users = User::orderBy('id', 'DESC')->with('roles', 'permissions')->Paginate(50);
        return view('admin.user_account.list', ['users' => $users]);
    }
    public function searchUser(Request $request)
    {
        $output = '';
        if ($request->search == null) {
            $users = User::orderBy('id', 'DESC')->Paginate(50);
        } else {
            $users = User::where('code', 'LIKE', '%' . $request->search . '%')->get();
        }
        if ($users) {
            foreach ($users as $value) {
                foreach ($value['roles'] as $role) {
                    if ($role['name'] == 'user') {
                        $output .= '<tr>
                        <td class="align-middle text-center">
                            <h6 class="mb-0 text-sm ">' . $value['code'] . '</h6>
                        </td>

                        <td class="align-middle text-center">
                            <h6 class="mb-0 text-sm ">' . $value['fullName'] . '</h6>
                        </td>

                        <td class="align-middle text-center">
                            <span class="text-secondary font-weight-bold">' . $value['email'] . '</span>
                        </td>

                        <td class="align-middle text-center">
                            <span class="text-secondary font-weight-bold">' . $value['phone'] . '</span>
                        </td>

                         <td class="align-middle text-center">
                            <button href="#barcode" class="btn btn-link text-danger "
                                    data-bs-toggle="modal"
                                    data-bs-target="#barcode' . $value['id'] . '"><i style="color:grey" class="fa-sharp fa-regular fa-eye"></i>
                            </button>
                        </td>
                        <td id="status' . $value['id'] . '" class="align-middle text-center text-sm ">';
                        if ($value['status'] == 1) {
                            $output .= '
                             <a href="javascript:void(0)" class="btn_active"  onclick="changestatus(' . $value['id'] . ',0)">
                                    <span class="badge badge-sm bg-gradient-success">Online</span>
                                </a>';
                        } else {
                            $output .= '<a href="javascript:void(0)" class="btn_active"  onclick="changestatus(' . $value['id'] . ',1)">
                                    <span class="badge badge-sm bg-gradient-secondary">Offline</span>
                                </a>';
                        }
                        $output .= '
                        </td>
                         <td class="align-middle text-center">
                            <span class="text-secondary font-weight-bold">' . number_format($value['point'], 0, ",", ".") . ' Point</span>
                        </td>
                        <td class="align-middle text-center">
                            <span
                                class="text-secondary font-weight-bold">' . date("d-m-Y H:m:s", strtotime($value['created_at'])) . '</span>
                        </td>
                        <td class="align-middle text-center">
                            <span
                                class="text-secondary font-weight-bold">' . date("d-m-Y H:m:s", strtotime($value['updated_at'])) . '</span>
                        </td>
                        </tr>';
                    }
                }
            }
        }

        return Response($output);
    }
    //Staff
    public function staff()
    {
        $staff = User::orderBy('id', 'DESC')->with('roles', 'permissions')->Paginate(20);
        $permission = Permission::orderBy('id', 'asc')->get();
        $theaters = Theater::all();

        return view('admin.staff_account.list', [
            'staff' => $staff,
            'permission' => $permission,
            'theaters' => $theaters
        ]);
    }

    public function postCreate(Request $request)
    {
        $request->validate([
            'fullName' => 'required|min:1',
            'email' => 'required|unique:users',
            'phone' => 'required|unique:users',
            'password' => 'required',
        ], [
            'fullName.required' => 'fullName is required',
            'email.required' => 'Email is required',
            'email.unique' => 'Email already exists',
            'password.required' => 'Password is required',
            'phone.required' => 'Phone is required',
            'phone.unique' => 'Phone already exists'
        ]);
        $request['password'] = bcrypt($request['password']);
        $staff = new User([
            'fullName' => $request['fullName'],
            'password' => $request['password'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'code' => rand(10000000000, 9999999999999999),
            'point' => 0,
            'email_verified' => true,
            'remember_token' => Str::random(20),
        ]);
        //        dd($staff);

        $staff->theater_id = $request->theater_id;

        $staff->save();
        $staff->syncRoles('staff');
        return redirect('/admin/staff')->with('success', 'Create Account Successfully!');
    }

    public function postPermission(Request $request, $id)
    {
        $data = $request->all();
        $user = User::find($id);
        if ($user->hasRole('admin')) {
            return redirect('admin/staff')->with('warning', 'Cannot change permission for admin!');
        } else {
            if (array_key_exists('permission', $data)) {
                $user->syncPermissions($data['permission']);
            } else {
                return redirect('admin/staff')->with('warning', 'Please check least 1 Permission!');
            }
        }


        return redirect('admin/staff')->with('success', 'Updated Permission Sucessfully !');
    }

    public function delete($id)
    {
        $user = User::find($id);
        if ($user->hasRole('admin')) {
            return response()->json(['error' => "Can't Delete Admin Account !"]);
        } else {
            User::destroy($id);
            return response()->json(['success' => 'Delete Successfully']);
        }
    }

    //Profile
    public function profile()
    {
        if (Auth::check()) {
            $user = Auth()->user();
        } else {
            return redirect('admin/sign_in');
        }
        return view('admin.profile', ['user' => $user])->with('roles', 'permissions');
    }
    public function Postprofile(Request $request)
    {
        $user = User::find(Auth::user()->id);
        if ($request['checkPassword'] == 'on') {
            $request->validate([
                'password' => 'required',
                'repassword' => 'required|same:password'
            ], [
                'password.required' => 'Vui lòng nhập mật khẩu mới',
                'repassword.required' => 'Vui lòng nhập lại mật khẩu',
                'repassword.same' => "Mật khẩu nhập lại không đúng"
            ]);
            $request['password'] = bcrypt($request['password']);
        }
        $user->update($request->all());
        return redirect('admin/sign_out')->with('success', 'Update Successfully');
    }
    //Sign_in
    public function sign_in()
    {
        return view('admin.sign_in');
    }

    public function Post_sign_in(Request $request)
    {
        $request->validate(
            [
                'email' => 'required',
                'password' => 'required'
            ],
            [
                'email.required' => 'Please enter your email!',
                'password.required' => 'Please enter your password!'
            ]
        );
        if (Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {
            return redirect('admin');
        } else {
            return redirect('admin/sign_in')->with('warning', "Sign in unsuccessfully!");
        }
    }

    public function sign_out()
    {
        Auth::logout();
        return redirect('admin/sign_in');
    }

    public function status(Request $request)
    {
        $user = User::find($request->user_id);
        if ($user->hasRole('admin')) {
            return response()->json(['error' => "Can't change Admin Status!"]);
        } else {
            $user['status'] = $request->active;
            $user->save();
        }
    }
    public function feedback()
    {
        $feed = Feedback::orderBy('id', 'DESC')->Paginate(15);
        return view('admin.feedback.list', ['feed' => $feed]);
    }
}
