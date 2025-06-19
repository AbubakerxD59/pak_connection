<?php

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Order;
use App\Models\Package;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Exceptions\InvalidFormatException;

/**
 * Get company name
 *
 * @return response()
 */
if (!function_exists('getCompanyName')) {
    function getCompanyName()
    {
        return setting('company_name', config('app.name'));
    }
}
/**
 * Get company email from settings
 *
 * @return response()
 */
if (!function_exists('getCompanyEmail')) {
    function getCompanyEmail()
    {
        return setting('company_email');
    }
}
/**
 * Get company phone number from settings
 *
 * @return response()
 */
if (!function_exists('getCompanyPhoneNumber')) {
    function getCompanyPhoneNumber()
    {
        return setting('company_phone_number');
    }
}
/**
 * Get company logo url from settings
 *
 * @return response()
 */
if (!function_exists('getCompanyLogoUrl')) {
    function getCompanyLogoUrl()
    {
        return asset(setting('company_logo', 'assets/img/site_logo.jpg'));
    }
}
/**
 * Get number of items per page
 *
 * @return response()
 */
if (!function_exists('getNumberOfItemsPerPage')) {
    function getNumberOfItemsPerPage()
    {
        return setting('item_per_page', '50');
    }
}
/**
 * Get date format
 *
 * @return response()
 */
if (!function_exists('getDateFormat')) {
    function getDateFormat()
    {
        return setting('date_format', 'jS M, Y');
    }
}
/**
 * Get time format
 *
 * @return response()
 */
if (!function_exists('getTimeFormat')) {
    function getTimeFormat()
    {
        return setting('time_format', 'h:m a');
    }
}
/**
 * Get date time format
 *
 * @return response()
 */
if (!function_exists('getDateTimeFormat')) {
    function getDateTimeFormat()
    {
        return setting('date_time_format', 'jS M, Y  h:m a');
    }
}
/**
 * parse into date format
 *
 * @return response()
 */
if (!function_exists('parseDateFormat')) {
    function parseDateFormat($date)
    {
        if (!empty($date)) {
            try {
                return Carbon::parse($date)->format(getDateFormat());
            } catch (InvalidFormatException $e) {
                return '';
            }
        } else {
            return '';
        }
    }
}
/**
 * parse into time format
 *
 * @return response()
 */
if (!function_exists('parseTimeFormat')) {
    function parseTimeFormat($time)
    {
        if (!empty($time)) {
            try {
                return Carbon::parse($time)->format(getTimeFormat());
            } catch (InvalidFormatException $e) {
                return '';
            }
        } else {
            return '';
        }
    }
}
/**
 * parse into date time format
 *
 * @return response()
 */
if (!function_exists('parseDateTimeFormat')) {
    function parseDateTimeFormat($date_time)
    {
        if (!empty($date_time)) {
            try {
                return Carbon::parse($date_time)->format(getDateTimeFormat());
            } catch (InvalidFormatException $e) {
                return '';
            }
        } else {
            return '';
        }
    }
}

function saveImage($file)
{
    $fileName = strtotime(date('Y-m-d H:i:s')) . rand() . '.' . $file->extension();
    $path = public_path() . '/uploads//';
    $file->move($path, $fileName);

    return $fileName;
}

function saveVideo($folderName, $file)
{
    $fileName = strtotime(date('Y-m-d H:i:s')) . '.' . $file->extension();
    $path = public_path() . '/uploads//' . $folderName;
    $file->move($path, $fileName);

    return $fileName;
}

function getImage($folderName = null, $fileName, $parentFolder = 'uploads')
{
    return asset($parentFolder . '/' . $folderName . '/' . $fileName);
}

function getVideo($folderName, $fileName)
{
    return asset('uploads/' . $folderName . '/' . $fileName);
}
function check_permission($permission)
{
    $user = auth()->user();
    $permissionsViaRole = $user->getPermissionsViaRoles()->pluck('name')->toArray();
    $permission = strtolower($permission);
    if (in_array($permission, $permissionsViaRole)) {
        return true;
    } else {
        return false;
    }
}
function get_total_roles()
{
    $roles = Role::get();
    return count($roles);
}
function get_total_users($type = null)
{
    $users = new User;
    if (empty($type)) {
        $users = $users->get();
    }
    if ($type == 'active') {
        $users = $users->where('status', '1')->get();
    }
    if ($type == 'inactive') {
        $users = $users->where('status', '0')->get();
    }

    return count($users);
}

function get_total_orders($type = null)
{
    $orders = new Order;
    if ($type == '0') {
        $orders = $orders->unpaid()->get();
    } elseif ($type == '1') {
        $orders = $orders->paid()->get();
    } else {
        $orders = $orders->get();
    }
    return count($orders);
}

function get_status_view($type)
{
    if ($type == '1') {
        $div = '<span><i class="fa fa-check" style="color: green"></i></span>';
    } else if ($type == '0') {
        $div = '<span><i class="fa fa-times" style="color: red"></i></span>';
    }
    return $div;
}

function session_set($key, $value)
{
    if (request()->session()->exists($key)) {
        request()->session()->put($key, $value);
    }
    return true;
}

function calculate_discount_price($base_price, $discount_price, $discount_type, $type)
{
    $response = 0;
    $discounted_price = 0;
    if ($discount_type == '%') {
        $discounted_price = ($base_price * $discount_price) / 100;
        $response = $base_price - $discounted_price;
    } else {
        $discounted_price = $discount_price;
        $response = $base_price - $discount_price;
    }
    if ($type == 0) {
        return $discounted_price;
    } elseif ($type == 1) {
        return $response;
    }
}

function getPreviousWeekDates($mode = 0)
{
    $dates = [];
    $todayTimestamp = time();
    $startDateTimestamp = strtotime('-6 days', $todayTimestamp);
    for ($timestamp = $startDateTimestamp; $timestamp <= $todayTimestamp; $timestamp += 86400) {
        if ($mode == 1) {
            $dates[] = date('Y-m-d 00:00:00', $timestamp);
        } else {
            $dates[] = date('m-d', $timestamp);
        }
    }
    if ($mode == 1) {
        return $dates;
    }
    $dates = '"' . implode('", "', $dates) . '"';
    return $dates;
}

function getPreviousWeeksData($type = 'users')
{
    $dates = getPreviousWeekDates(1);
    $data = [];
    foreach ($dates as $date) {
        $start_date = $date;
        $end_date = date('Y-m-d 23:59:59', strtotime($start_date));
        if ($type == 'users') {
            $val = User::whereBetween('created_at', [$start_date, $end_date])->get()->count();
        }
        if ($type == "orders") {
            $val = Order::whereBetween("created_at", [$start_date, $end_date])->get()->count();
        }
        $data[] = $val;
    }
    $data = '"' . implode('", "', $data) . '"';
    return $data;
}

function getCurrentMonthDates($mode = 0)
{
    $dates = [];
    $currentYear = date('Y');
    $currentMonth = date('m');
    $daysInMonth = date('t');

    for ($day = 1; $day <= $daysInMonth; $day++) {
        $timestamp = strtotime("$currentYear-$currentMonth-$day");
        if ($mode == 1) {
            $dates[] = date('Y-m-d 00:00:00', $timestamp);
        } else {
            $dates[] = date('d-M', $timestamp);
        }
    }
    if ($mode == 1) {
        return $dates;
    }
    $dates = '"' . implode('", "', $dates) . '"';

    return $dates;
}

function getCurrentMonthData($type = 'users')
{
    $dates = getCurrentMonthDates(1);
    $data = [];
    foreach ($dates as $date) {
        $start_date = $date;
        $end_date = date('Y-m-d 23:59:59', strtotime($start_date));
        if ($type == 'users') {
            $val = User::whereBetween('created_at', [$start_date, $end_date])->get()->count();
        }
        if ($type == "orders") {
            $val = Order::whereBetween('created_at', [$start_date, $end_date])->get()->count();
        }
        $data[] = $val;
    }
    $data = '"' . implode('", "', $data) . '"';
    return $data;
}


function getPreviousMonths($mode = 0)
{
    $months = [];
    $currentMonth = new DateTime();
    for ($i = 7; $i >= 0; $i--) {
        $month = clone $currentMonth;
        $month->modify("-$i months");
        if ($mode == 1) {
            $months[] = $month->format("Y-m-1 00:00:00");
        } else {
            $months[] = $month->format("M-y");
        }
    }
    if ($mode == 1) {
        return $months;
    }
    $months = '"' . implode('", "', $months) . '"';
    return $months;
}

function getPreviousMonthsData($type = 'users')
{
    $dates = getPreviousMonths(1);
    $data = [];
    foreach ($dates as $date) {
        $start_date = $date;
        $end_date = date('Y-m-t 23:59:59', strtotime($start_date));
        if ($type == 'users') {
            $val = User::whereBetween('created_at', [$start_date, $end_date])->get()->count();
        }
        if ($type == 'orders') {
            $val = Order::whereBetween('created_at', [$start_date, $end_date])->get()->count();
        }
        $data[] = $val;
    }
    $data = '"' . implode('", "', $data) . '"';
    return $data;
}

function getEarnings()
{
    $order = new Order();
    $earning = $order->totalEarning();
    return '£' . $earning;
}

function check_features($features, $package_id)
{
    $packages = Package::where('id', '<>', $package_id)->get();
    $include = [];
    foreach ($packages as $package) {
        $package_features = $package->getFeatures();
        $diff = array_diff($package_features, $features);
        if (count($diff) <= 0) {
            array_push($include, $package->name);
            $features = array_diff($features, $package_features);
        }
    }
    if (count($include) > 0) {
        return [
            'include' => $include,
            'extra' => $features
        ];
    } else {
        return $features;
    }
}

function no_image()
{
    $image =  url(getImage('img', 'noimage.png', 'assets'));
    return $image;
}

function service_book_status($status)
{
    if ($status == 1) {
        $div = '<span class="badge badge-warning">Pending</span>';
    } elseif ($status == 2) {
        $div = '<span class="badge badge-info">Approved</span>';
    } elseif ($status == 3) {
        $div = '<span class="badge badge-primary">Started</span>';
    } elseif ($status == 4) {
        $div = '<span class="badge badge-success">Finished</span>';
    } elseif ($status == -1) {
        $div = '<span class="badge badge-danger">Rejected</span>';
    } else {
        $div = '';
    }
    return $div;
}
