<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Feature;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    private $feature;
    public function __construct(Feature $feature)
    {
        $this->feature = $feature;
    }
    public function home()
    {
        $user = Auth::user();
        $package = $user->getPackage();
        if ($package) {
            $features = $package->features()->get();
        } else {
            $features = [];
        }
        return view('frontend.member.home', compact('package', 'features'));
    }
}
