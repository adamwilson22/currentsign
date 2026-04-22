<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\User;
use App\Models\FormUser;


use Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
class AdminFormController extends Controller {

    public function show_form(Request $request, User $user) {
        
            return view('admin.forms.show-form',['user'=>$user]);

    }
  
    public function post_form(Request $request, User $user) {

            $form = new FormUser();
             
            $data = implode(',', array_filter([
                $request->loan_details,
                $request->personal_details,
                $request->other_details_attachment,
                $request->address_details,
                $request->other_details,
                $request->bank_details

            ]));
            
            $formUser = new FormUser();
            $formUser->user_id = $user->id; 
            $formUser->form_modules = $data;
            $formUser->save();
           
            return redirect("admin/forms/".$user->id."/final-form?id=".$formUser->id)->with('success', 'Form Created Successfully.');

    }
    
 
    public function final_form(Request $request, User $user) {
        
            return view('admin.forms.final-form',['user'=>$user]);

    }

    public function show_form_application(Request $request) {
        
            return view('admin.forms.show-form-application');

    }


}
