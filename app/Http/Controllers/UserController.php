namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
public function index(){
return User::all();
}

public function store(Request $request){
$validated = $request->validate([
'name' => 'required|string|max:255',
'email' => 'required|string|email|unique:users',
'password' => 'required|string|min:6',
]);

$user = User::create([
'name' => $validated['name'],
'email' => $validated['email'],
'password' => Hash::make($validated['password']),
]);

return response()->json($user,201);
}
}