<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
class UserController extends Controller
{
    public function index(Request $request)
    {
        
        $query = User::query();
        $search_query = $request->input('search_query');
        if ($request->has('search_query') && !empty($search_query)) {
            $query->where(function ($query) use ($search_query) {
                $query->where('name', 'like', '%' . $search_query . '%')
                ->orWhere('email', 'like', '%' . $search_query . '%')
                ->orWhere('phone_no', 'like', '%' . $search_query . '%')
                ->orWhere('city', 'like', '%' . $search_query . '%');
            });
        }
        $data['users'] = $query->orderBy('id', 'DESC')->paginate(50);
        $data['searchParams'] = $request->all();
        return view('admin/users/manage_users', $data);
    }
    public function user_details($id)
    {
        $data['user'] = User::where('id', $id)->first();
        
        if (!empty($data['user'])) {
            return view('admin/users/users_details', $data);
        }
        
        return view('common/admin_404');
    }
    public function update_statuses(Request $request)
    {
        $data = $request->all();
        $status = User::where('id', $data['id'])->update([
            'is_blocked' => $data['status'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        if($status > 0) {
            if($data['status'] == '1'){
                $finalResult = response()->json(['msg' => 'success', 'response'=>'User Unblocked successfully.']);
            }else{
                $finalResult = response()->json(['msg' => 'success', 'response'=>'User Blocked successfully.']);
            }
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response'=>'Something went wrong!']);
            return $finalResult;
        }
    }
}