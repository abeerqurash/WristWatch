<?php
namespace App\Http\Controllers;
use App\Models\FormSubmission;
use Illuminate\Http\Request;
class StorefrontController extends Controller
{
    public function subscribe(Request $request)
    {
        $data = $request->validate(['email' => ['required', 'email', 'max:255']]);
        FormSubmission::firstOrCreate(['email' => strtolower($data['email']), 'form_type' => 'Newsletter'], ['form_source' => 'storefront', 'full_name' => 'Newsletter subscriber', 'subject' => 'WristWatch newsletter', 'message' => 'Requested news and offers from the storefront.']);
        return back()->with('success', 'Thank you for subscribing to WristWatch.');
    }
}
