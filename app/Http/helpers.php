<?php

use App\Models\Option;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Ipecompany\Smsirlaravel\Smsirlaravel;

if (!function_exists('time_elapsed_string')) {
    function time_elapsed_string($datetime, $lang = 'fa', $full = false)
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        $ago = ' ago';
        $just_now = 'just now';
        $suffix = 's';
        switch ($lang) {
            case 'fa': {
                    $string = array(
                        'y' => 'سال',
                        'm' => 'ماه',
                        'w' => 'هفته',
                        'd' => 'روز',
                        'h' => 'ساعت',
                        'i' => 'دقیقه',
                        's' => 'ثانیه',
                    );
                    $ago = ' قبل';
                    $suffix = '';
                    $just_now = 'همین الان';
                    break;
                }
        }

        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? $suffix : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . $ago : $just_now;
    }
}

if (!function_exists('render_content')) {
    function render_content($content)
    {
        $content = preg_replace_callback(
            '/\[component=\w+\]/',
            function ($result) {
                $component = $result[0];
                $component = str_replace('[component=', '', $component);
                $component = str_replace(']', '', $component);
                if (view()->exists("components.$component")) {
                    return view("components.$component");
                }
                return '';
            },
            $content
        );
        return $content;
    }
}

if (!function_exists('jd')) {
    function jd($date, $format = 'Y/m/d - H:i')
    {
        return jdate($format, strtotime($date));
    }
}

if (!function_exists('is_image')) {
    function is_image($address)
    {
        if (preg_match('/(\.jpg|\.jpeg|\.png)$/', $address)) {
            return true;
        }
        return false;
    }
}

if (!function_exists('getOption')) {
    function getOption($option_name, $fallback = null)
    {
        $option_names = explode('.', $option_name);
        $option = Cache::remember("options_$option_names[0]", 60 * 24, function () use ($option_names) {
            $option =  \App\Models\Option::where('name', $option_names[0])->first();
            return $option ? $option->getValue() : null;
        });
        if ($option) {
            $option = json_decode(json_encode($option));
            unset($option_names[0]);
            foreach ($option_names as $option_name) {
                $target = $option->$option_name ?? null;
                if ($target) {
                    $option = $target;
                } else {
                    return $fallback;
                }
            }
            return $option ?: $fallback;
        } else {
            return $fallback;
        }
    }
}

if (!function_exists('checkActive')) {

    function checkActive(array $route_names)
    {
        if (in_array(request()->route()->getName(), $route_names)) {
            return true;
        } else {
            return request()->is($route_names);
        }
    }
}

if (!function_exists('getImageSrc')) {
    function getImageSrc($image = '', $template = 'original')
    {
        if ($image) {
            return route('imagecache', ['template' => $template, 'filename' => $image]);
        }
        return null;
    }
}

if (!function_exists('getProductCategoriesList')) {
    function getProductCategoriesList(&$list, $category = null, &$i = 0)
    {
        if ($category == null) {
            $categories = \App\Models\Category::where('parent_id', null)->orderBy('order', 'asc')->get();
            foreach ($categories as $item) {
                $list[$i] = $item;
                $i++;
                if ($item->children()->count()) {
                    getProductCategoriesList($list, $item, $i);
                }
            }
        } else {
            $categories = $category->children()->orderBy('order', 'asc')->get();
            foreach ($categories as $item) {
                $list[$i] = $item;
                $i++;
                if ($item->children()->count()) {
                    getProductCategoriesList($list, $item, $i);
                }
            }
        }
    }
}
// if (!function_exists('getArticleCategoriesList')) {
//     function getArticleCategoriesList(&$list, $category = null, &$i = 0)
//     {
//         if ($category == null) {
//             $categories = \App\ArticleCategory::where('parent_id', null)->orderBy('order', 'asc')->get();
//             foreach ($categories as $item) {
//                 $list[$i] = $item;
//                 $i++;
//                 if ($item->children()->count()) {
//                     getArticleCategoriesList($list, $item, $i);
//                 }
//             }
//         } else {
//             $categories = $category->children()->orderBy('order', 'asc')->get();
//             foreach ($categories as $item) {
//                 $list[$i] = $item;
//                 $i++;
//                 if ($item->children()->count()) {
//                     getArticleCategoriesList($list, $item, $i);
//                 }
//             }
//         }
//     }
// }

if (!function_exists('sendSms')) {
    function sendSms($data, $code, $mobile)
    {
        $option = Option::first();
        $sms_count = $option->smsCount;
        if ($sms_count > 0) {
            $option->decrement('smsCount');
            Smsirlaravel::ultraFastSend($data, $code, $mobile);
        }
    }
}

if (!function_exists('getCategoryChildren')) {
    function getCategoryChildren($category = null)
    {
        $list = [];
        if ($category) {
            $list[] = $category->id;
            if ($category->children()->count()) {
                foreach ($category->children as $child) {
                    $list[] = getCategoryChildren($child);
                }
            }
        } else {
            $categories = \App\Models\Category::where('parent_id', null)->orderBy('order', 'asc')->get();
            foreach ($categories as $category) {
                $list[] = $category->id;
                $list[] = getCategoryChildren($category);
            }
        }

        return array_unique(\Illuminate\Support\Arr::flatten($list));
    }
}

if (!function_exists('remove_spec')) {
    function remove_spec($string, $extension = null, $code = null)
    {
        $string = str_replace($extension, '', $string);
        $string = str_replace('?', '', $string);
        $string = str_replace('؟', '', $string);
        $string = str_replace('؛', '', $string);
        $string = str_replace('&#8203;', '', $string);
        $string = str_replace('\xE2\x80\x8C', '', $string);
        $string = str_replace('\xE2\x80\x8B', '', $string);
        $string = str_replace('&zwnj;', '', $string);
        $string = preg_replace("/\xE2\x80\x8C/", " ", $string);
        $string = preg_replace("/\xE2\x80\x8B/", " ", $string);
        $string = preg_replace('~\s+~u', ' ', $string);
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^{اآبپتثجچحخدذرزژسشصضطظعغفقکگلمنوهیءأإؤئية}\dA-Za-z-]/', '', $string); // Removes special chars.
        $string = preg_replace('/-+/', '-', $string);
        if ($code) {
            $string = $string . '-' . $code;
        }

        if ($extension) {
            return $string . '.' . $extension; // Replaces multiple hyphens with single one.
        } else {
            return $string; // Replaces multiple hyphens with single one.
        }
    }
}

if (!function_exists('hasParent')) {
    function hasParent($item, $id)
    {
        if (!$item->parent) {
            return false;
        }
        if ($item->parent->id == $id) {
            return true;
        }
        if ($item->parent) {
            return hasParent($item->parent, $id);
        } else {
            return false;
        }
    }
}

if (!function_exists('convertNumbers')) {
    function convertNumbers($srting, $toPersian = true)
    {
        $en_num = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        $fa_num = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
        if ($toPersian) return str_replace($en_num, $fa_num, $srting);
        else return str_replace($fa_num, $en_num, $srting);
    }
}

if (!function_exists('render_widget_position')) {
    function render_widget_position($position_name)
    {
        $option_widgets = Cache::remember("options_widgets", 60 * 24, function () {
            $option = \App\Models\Option::where('name', 'widgets')->first();
            if ($option) {
                return $option->getValue();
            }
            return [];
        });
        $option_widgets = collect($option_widgets);
        $option_widgets = $option_widgets->where('position', $position_name);
        $option_widgets = $option_widgets->sortBy('order');
        foreach ($option_widgets as $widget) {
            $widget_class = new \App\Models\Option::$widgets[$widget['name']]((array) $widget);
            echo $widget_class->run();
        }
        return '';
    }
}


if (!function_exists('getAvailableShippings')) {
    function getAvailableShippings($order_price, $province = null, $city = null)
    {

        $option = \App\Models\Option::firstOrCreate(['name' => 'site_information']);
        $option = $option->getValue();
        $site_province = $option['province'];
        $site_city = $option['city'];
        $shipping_option = \App\Models\Option::where('name', 'shipping')->first();
        $shippings = $shipping_option->getValue();

        $result = [];
        $free_shipping = [];
        $post_shipping = [];
        $bike_shipping = [];
        $in_place_delivery = [];
        $show_other_shipping = true;

        if ($shippings['free_shipping']['is_active'] ?? false) {
            $free_shipping['title'] = $shippings['free_shipping']['title'] ?? '';
            $free_shipping['name'] = 'free_shipping';
            $free_shipping['price'] = 0;
            $free_shipping['icon'] = 'icon-free-delivery';
            if ($province == $site_province['id']) {
                if ($order_price >= ($shippings['free_shipping']['province_min_order_price'] ?? 0)) {
                    $result[] = $free_shipping;
                    $show_other_shipping = $shippings['free_shipping']['show_other_shipping'] ?? true;
                }
            } else {
                if ($shippings['free_shipping']['all_cities'] ?? false) {
                    if ($order_price >= ($shippings['free_shipping']['all_min_order_price'] ?? 0)) {
                        $result[] = $free_shipping;
                        $show_other_shipping = $shippings['free_shipping']['show_other_shipping'] ?? true;
                    }
                }
            }
        }
        if ($show_other_shipping) {
            if ($shippings['post_shipping']['is_active'] ?? false) {
                $post_shipping['title'] = $shippings['post_shipping']['title'] ?? '';
                $post_shipping['name'] = 'post_shipping';
                $post_shipping['icon'] = 'icon-post';
                if ($province == $site_province['id']) {
                    $post_shipping['price'] = $shippings['post_shipping']['province_price'] ?? 0;
                    $result[] = $post_shipping;
                } else {
                    $post_shipping['price'] = $shippings['post_shipping']['other_provinces_price'] ?? 0;
                    $result[] = $post_shipping;
                }
            }
            if ($shippings['bike_shipping']['is_active'] ?? false) {
                $bike_shipping['title'] = $shippings['bike_shipping']['title'] ?? '';
                $bike_shipping['name'] = 'bike_shipping';
                $bike_shipping['icon'] = 'icon-courier';
                if ($city == $site_city['id']) {
                    $bike_shipping['price'] = $shippings['bike_shipping']['price'] ?? 0;
                    $result[] = $bike_shipping;
                }
            }
            if ($shippings['in_place_delivery']['is_active'] ?? false) {
                $in_place_delivery['title'] = $shippings['in_place_delivery']['title'] ?? '';
                $in_place_delivery['name'] = 'in_place_delivery';
                $in_place_delivery['icon'] = 'icon-delivery-man';
                $in_place_delivery['price'] = 0;
                if ($shippings['in_place_delivery']['in_province'] ?? false) {
                    if ($province == $site_province['id']) {
                        $result[] = $in_place_delivery;
                    }
                } else {
                    if ($shippings['in_place_delivery']['in_city'] ?? false) {
                        if ($city == $site_city['id']) {
                            $result[] = $in_place_delivery;
                        }
                    }
                }
            }
        }
        return $result;
    }
}



if (!function_exists('getAllCategoryChildren')) {
    function getAllCategoryChildren($category, &$array)
    {
        if ($category == null) {
            return;
        } else {
            $array[] = $category->id;
            if ($category->children()->count()) {
                foreach ($category->children as $child) {
                    getAllCategoryChildren($child, $array);
                }
            }
        }
    }
}

if (!function_exists('paginate')) {
    function paginate($items, $perPage = 20, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}

function jdate($format, $timestamp = '', $none = '', $time_zone = 'Asia/Tehran', $tr_num = 'fa')
{

    $T_sec = 0;/* <= رفع خطاي زمان سرور ، با اعداد '+' و '-' بر حسب ثانيه */

    if ($time_zone != 'local') date_default_timezone_set(($time_zone === '') ? 'Asia/Tehran' : $time_zone);
    $ts = $T_sec + (($timestamp === '') ? time() : tr_num($timestamp));
    $date = explode('_', date('H_i_j_n_O_P_s_w_Y', $ts));
    list($j_y, $j_m, $j_d) = gregorian_to_jalali($date[8], $date[3], $date[2]);
    $doy = ($j_m < 7) ? (($j_m - 1) * 31) + $j_d - 1 : (($j_m - 7) * 30) + $j_d + 185;
    $kab = (((($j_y % 33) % 4) - 1) == ((int)(($j_y % 33) * 0.05))) ? 1 : 0;
    $sl = strlen($format);
    $out = '';
    for ($i = 0; $i < $sl; $i++) {
        $sub = substr($format, $i, 1);
        if ($sub == '\\') {
            $out .= substr($format, ++$i, 1);
            continue;
        }
        switch ($sub) {

            case 'E':
            case 'R':
            case 'x':
            case 'X':
                $out .= 'http://jdf.scr.ir';
                break;

            case 'B':
            case 'e':
            case 'g':
            case 'G':
            case 'h':
            case 'I':
            case 'T':
            case 'u':
            case 'Z':
                $out .= date($sub, $ts);
                break;

            case 'a':
                $out .= ($date[0] < 12) ? 'ق.ظ' : 'ب.ظ';
                break;

            case 'A':
                $out .= ($date[0] < 12) ? 'قبل از ظهر' : 'بعد از ظهر';
                break;

            case 'b':
                $out .= (int)($j_m / 3.1) + 1;
                break;

            case 'c':
                $out .= $j_y . '/' . $j_m . '/' . $j_d . ' ،' . $date[0] . ':' . $date[1] . ':' . $date[6] . ' ' . $date[5];
                break;

            case 'C':
                $out .= (int)(($j_y + 99) / 100);
                break;

            case 'd':
                $out .= ($j_d < 10) ? '0' . $j_d : $j_d;
                break;

            case 'D':
                $out .= jdate_words(array('kh' => $date[7]), ' ');
                break;

            case 'f':
                $out .= jdate_words(array('ff' => $j_m), ' ');
                break;

            case 'F':
                $out .= jdate_words(array('mm' => $j_m), ' ');
                break;

            case 'H':
                $out .= $date[0];
                break;

            case 'i':
                $out .= $date[1];
                break;

            case 'j':
                $out .= $j_d;
                break;

            case 'J':
                $out .= jdate_words(array('rr' => $j_d), ' ');
                break;

            case 'k';
                $out .= tr_num(100 - (int)($doy / ($kab + 365) * 1000) / 10, $tr_num);
                break;

            case 'K':
                $out .= tr_num((int)($doy / ($kab + 365) * 1000) / 10, $tr_num);
                break;

            case 'l':
                $out .= jdate_words(array('rh' => $date[7]), ' ');
                break;

            case 'L':
                $out .= $kab;
                break;

            case 'm':
                $out .= ($j_m > 9) ? $j_m : '0' . $j_m;
                break;

            case 'M':
                $out .= jdate_words(array('km' => $j_m), ' ');
                break;

            case 'n':
                $out .= $j_m;
                break;

            case 'N':
                $out .= $date[7] + 1;
                break;

            case 'o':
                $jdw = ($date[7] == 6) ? 0 : $date[7] + 1;
                $dny = 364 + $kab - $doy;
                $out .= ($jdw > ($doy + 3) and $doy < 3) ? $j_y - 1 : (((3 - $dny) > $jdw and $dny < 3) ? $j_y + 1 : $j_y);
                break;

            case 'O':
                $out .= $date[4];
                break;

            case 'p':
                $out .= jdate_words(array('mb' => $j_m), ' ');
                break;

            case 'P':
                $out .= $date[5];
                break;

            case 'q':
                $out .= jdate_words(array('sh' => $j_y), ' ');
                break;

            case 'Q':
                $out .= $kab + 364 - $doy;
                break;

            case 'r':
                $key = jdate_words(array('rh' => $date[7], 'mm' => $j_m));
                $out .= $date[0] . ':' . $date[1] . ':' . $date[6] . ' ' . $date[4] . ' ' . $key['rh'] . '، ' . $j_d . ' ' . $key['mm'] . ' ' . $j_y;
                break;

            case 's':
                $out .= $date[6];
                break;

            case 'S':
                $out .= 'ام';
                break;

            case 't':
                $out .= ($j_m != 12) ? (31 - (int)($j_m / 6.5)) : ($kab + 29);
                break;

            case 'U':
                $out .= $ts;
                break;

            case 'v':
                $out .= jdate_words(array('ss' => ($j_y % 100)), ' ');
                break;

            case 'V':
                $out .= jdate_words(array('ss' => $j_y), ' ');
                break;

            case 'w':
                $out .= ($date[7] == 6) ? 0 : $date[7] + 1;
                break;

            case 'W':
                $avs = (($date[7] == 6) ? 0 : $date[7] + 1) - ($doy % 7);
                if ($avs < 0) $avs += 7;
                $num = (int)(($doy + $avs) / 7);
                if ($avs < 4) {
                    $num++;
                } elseif ($num < 1) {
                    $num = ($avs == 4 or $avs == ((((($j_y % 33) % 4) - 2) == ((int)(($j_y % 33) * 0.05))) ? 5 : 4)) ? 53 : 52;
                }
                $aks = $avs + $kab;
                if ($aks == 7) $aks = 0;
                $out .= (($kab + 363 - $doy) < $aks and $aks < 3) ? '01' : (($num < 10) ? '0' . $num : $num);
                break;

            case 'y':
                $out .= substr($j_y, 2, 2);
                break;

            case 'Y':
                $out .= $j_y;
                break;

            case 'z':
                $out .= $doy;
                break;

            default:
                $out .= $sub;
        }
    }
    return ($tr_num != 'en') ? tr_num($out, 'fa', '.') : $out;
}

/*	F	*/
function jstrftime($format, $timestamp = '', $none = '', $time_zone = 'Asia/Tehran', $tr_num = 'fa')
{

    $T_sec = 0;/* <= رفع خطاي زمان سرور ، با اعداد '+' و '-' بر حسب ثانيه */

    if ($time_zone != 'local') date_default_timezone_set(($time_zone === '') ? 'Asia/Tehran' : $time_zone);
    $ts = $T_sec + (($timestamp === '') ? time() : tr_num($timestamp));
    $date = explode('_', date('h_H_i_j_n_s_w_Y', $ts));
    list($j_y, $j_m, $j_d) = gregorian_to_jalali($date[7], $date[4], $date[3]);
    $doy = ($j_m < 7) ? (($j_m - 1) * 31) + $j_d - 1 : (($j_m - 7) * 30) + $j_d + 185;
    $kab = (((($j_y % 33) % 4) - 1) == ((int)(($j_y % 33) * 0.05))) ? 1 : 0;
    $sl = strlen($format);
    $out = '';
    for ($i = 0; $i < $sl; $i++) {
        $sub = substr($format, $i, 1);
        if ($sub == '%') {
            $sub = substr($format, ++$i, 1);
        } else {
            $out .= $sub;
            continue;
        }
        switch ($sub) {

                /* Day */
            case 'a':
                $out .= jdate_words(array('kh' => $date[6]), ' ');
                break;

            case 'A':
                $out .= jdate_words(array('rh' => $date[6]), ' ');
                break;

            case 'd':
                $out .= ($j_d < 10) ? '0' . $j_d : $j_d;
                break;

            case 'e':
                $out .= ($j_d < 10) ? ' ' . $j_d : $j_d;
                break;

            case 'j':
                $out .= str_pad($doy + 1, 3, 0, STR_PAD_LEFT);
                break;

            case 'u':
                $out .= $date[6] + 1;
                break;

            case 'w':
                $out .= ($date[6] == 6) ? 0 : $date[6] + 1;
                break;

                /* Week */
            case 'U':
                $avs = (($date[6] < 5) ? $date[6] + 2 : $date[6] - 5) - ($doy % 7);
                if ($avs < 0) $avs += 7;
                $num = (int)(($doy + $avs) / 7) + 1;
                if ($avs > 3 or $avs == 1) $num--;
                $out .= ($num < 10) ? '0' . $num : $num;
                break;

            case 'V':
                $avs = (($date[6] == 6) ? 0 : $date[6] + 1) - ($doy % 7);
                if ($avs < 0) $avs += 7;
                $num = (int)(($doy + $avs) / 7);
                if ($avs < 4) {
                    $num++;
                } elseif ($num < 1) {
                    $num = ($avs == 4 or $avs == ((((($j_y % 33) % 4) - 2) == ((int)(($j_y % 33) * 0.05))) ? 5 : 4)) ? 53 : 52;
                }
                $aks = $avs + $kab;
                if ($aks == 7) $aks = 0;
                $out .= (($kab + 363 - $doy) < $aks and $aks < 3) ? '01' : (($num < 10) ? '0' . $num : $num);
                break;

            case 'W':
                $avs = (($date[6] == 6) ? 0 : $date[6] + 1) - ($doy % 7);
                if ($avs < 0) $avs += 7;
                $num = (int)(($doy + $avs) / 7) + 1;
                if ($avs > 3) $num--;
                $out .= ($num < 10) ? '0' . $num : $num;
                break;

                /* Month */
            case 'b':
            case 'h':
                $out .= jdate_words(array('km' => $j_m), ' ');
                break;

            case 'B':
                $out .= jdate_words(array('mm' => $j_m), ' ');
                break;

            case 'm':
                $out .= ($j_m > 9) ? $j_m : '0' . $j_m;
                break;

                /* Year */
            case 'C':
                $tmp = (int)($j_y / 100);
                $out .= ($tmp > 9) ? $tmp : '0' . $tmp;
                break;

            case 'g':
                $jdw = ($date[6] == 6) ? 0 : $date[6] + 1;
                $dny = 364 + $kab - $doy;
                $out .= substr(($jdw > ($doy + 3) and $doy < 3) ? $j_y - 1 : (((3 - $dny) > $jdw and $dny < 3) ? $j_y + 1 : $j_y), 2, 2);
                break;

            case 'G':
                $jdw = ($date[6] == 6) ? 0 : $date[6] + 1;
                $dny = 364 + $kab - $doy;
                $out .= ($jdw > ($doy + 3) and $doy < 3) ? $j_y - 1 : (((3 - $dny) > $jdw and $dny < 3) ? $j_y + 1 : $j_y);
                break;

            case 'y':
                $out .= substr($j_y, 2, 2);
                break;

            case 'Y':
                $out .= $j_y;
                break;

                /* Time */
            case 'H':
                $out .= $date[1];
                break;

            case 'I':
                $out .= $date[0];
                break;

            case 'l':
                $out .= ($date[0] > 9) ? $date[0] : ' ' . (int)$date[0];
                break;

            case 'M':
                $out .= $date[2];
                break;

            case 'p':
                $out .= ($date[1] < 12) ? 'قبل از ظهر' : 'بعد از ظهر';
                break;

            case 'P':
                $out .= ($date[1] < 12) ? 'ق.ظ' : 'ب.ظ';
                break;

            case 'r':
                $out .= $date[0] . ':' . $date[2] . ':' . $date[5] . ' ' . (($date[1] < 12) ? 'قبل از ظهر' : 'بعد از ظهر');
                break;

            case 'R':
                $out .= $date[1] . ':' . $date[2];
                break;

            case 'S':
                $out .= $date[5];
                break;

            case 'T':
                $out .= $date[1] . ':' . $date[2] . ':' . $date[5];
                break;

            case 'X':
                $out .= $date[0] . ':' . $date[2] . ':' . $date[5];
                break;

            case 'z':
                $out .= date('O', $ts);
                break;

            case 'Z':
                $out .= date('T', $ts);
                break;

                /* Time and Date Stamps */
            case 'c':
                $key = jdate_words(array('rh' => $date[6], 'mm' => $j_m));
                $out .= $date[1] . ':' . $date[2] . ':' . $date[5] . ' ' . date('P', $ts) . ' ' . $key['rh'] . '، ' . $j_d . ' ' . $key['mm'] . ' ' . $j_y;
                break;

            case 'D':
                $out .= substr($j_y, 2, 2) . '/' . (($j_m > 9) ? $j_m : '0' . $j_m) . '/' . (($j_d < 10) ? '0' . $j_d : $j_d);
                break;

            case 'F':
                $out .= $j_y . '-' . (($j_m > 9) ? $j_m : '0' . $j_m) . '-' . (($j_d < 10) ? '0' . $j_d : $j_d);
                break;

            case 's':
                $out .= $ts;
                break;

            case 'x':
                $out .= substr($j_y, 2, 2) . '/' . (($j_m > 9) ? $j_m : '0' . $j_m) . '/' . (($j_d < 10) ? '0' . $j_d : $j_d);
                break;

                /* Miscellaneous */
            case 'n':
                $out .= "\n";
                break;

            case 't':
                $out .= "\t";
                break;

            case '%':
                $out .= '%';
                break;

            default:
                $out .= $sub;
        }
    }
    return ($tr_num != 'en') ? tr_num($out, 'fa', '.') : $out;
}

/*	F	*/
function jmktime($h = '', $m = '', $s = '', $jm = '', $jd = '', $jy = '', $none = '', $timezone = 'Asia/Tehran')
{
    if ($timezone != 'local') date_default_timezone_set($timezone);
    if ($h === '') {
        return time();
    } else {
        list($h, $m, $s, $jm, $jd, $jy) = explode('_', tr_num($h . '_' . $m . '_' . $s . '_' . $jm . '_' . $jd . '_' . $jy));
        if ($m === '') {
            return mktime($h);
        } else {
            if ($s === '') {
                return mktime($h, $m);
            } else {
                if ($jm === '') {
                    return mktime($h, $m, $s);
                } else {
                    $jdate = explode('_', jdate('Y_j', '', '', $timezone, 'en'));
                    if ($jd === '') {
                        list($gy, $gm, $gd) = jalali_to_gregorian($jdate[0], $jm, $jdate[1]);
                        return mktime($h, $m, $s, $gm);
                    } else {
                        if ($jy === '') {
                            list($gy, $gm, $gd) = jalali_to_gregorian($jdate[0], $jm, $jd);
                            return mktime($h, $m, $s, $gm, $gd);
                        } else {
                            list($gy, $gm, $gd) = jalali_to_gregorian($jy, $jm, $jd);
                            return mktime($h, $m, $s, $gm, $gd, $gy);
                        }
                    }
                }
            }
        }
    }
}

/*	F	*/
function jgetdate($timestamp = '', $none = '', $timezone = 'Asia/Tehran', $tn = 'en')
{
    $ts = ($timestamp === '') ? time() : tr_num($timestamp);
    $jdate = explode('_', jdate('F_G_i_j_l_n_s_w_Y_z', $ts, '', $timezone, $tn));
    return array(
        'seconds' => tr_num((int)tr_num($jdate[6]), $tn),
        'minutes' => tr_num((int)tr_num($jdate[2]), $tn),
        'hours' => $jdate[1],
        'mday' => $jdate[3],
        'wday' => $jdate[7],
        'mon' => $jdate[5],
        'year' => $jdate[8],
        'yday' => $jdate[9],
        'weekday' => $jdate[4],
        'month' => $jdate[0],
        0 => tr_num($ts, $tn)
    );
}

/*	F	*/
function jcheckdate($jm, $jd, $jy)
{
    list($jm, $jd, $jy) = explode('_', tr_num($jm . '_' . $jd . '_' . $jy));
    $l_d = ($jm == 12) ? ((((($jy % 33) % 4) - 1) == ((int)(($jy % 33) * 0.05))) ? 30 : 29) : 31 - (int)($jm / 6.5);
    return ($jm > 12 or $jd > $l_d or $jm < 1 or $jd < 1 or $jy < 1) ? false : true;
}

/*	F	*/
function tr_num($str, $mod = 'en', $mf = '٫')
{
    $num_a = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.');
    $key_a = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', $mf);
    return ($mod == 'fa') ? str_replace($num_a, $key_a, $str) : str_replace($key_a, $num_a, $str);
}

/*	F	*/
function jdate_words($array, $mod = '')
{
    foreach ($array as $type => $num) {
        $num = (int)tr_num($num);
        switch ($type) {

            case 'ss':
                $sl = strlen($num);
                $xy3 = substr($num, 2 - $sl, 1);
                $h3 = $h34 = $h4 = '';
                if ($xy3 == 1) {
                    $p34 = '';
                    $k34 = array('ده', 'یازده', 'دوازده', 'سیزده', 'چهارده', 'پانزده', 'شانزده', 'هفده', 'هجده', 'نوزده');
                    $h34 = $k34[substr($num, 2 - $sl, 2) - 10];
                } else {
                    $xy4 = substr($num, 3 - $sl, 1);
                    $p34 = ($xy3 == 0 or $xy4 == 0) ? '' : ' و ';
                    $k3 = array('', '', 'بیست', 'سی', 'چهل', 'پنجاه', 'شصت', 'هفتاد', 'هشتاد', 'نود');
                    $h3 = $k3[$xy3];
                    $k4 = array('', 'یک', 'دو', 'سه', 'چهار', 'پنج', 'شش', 'هفت', 'هشت', 'نه');
                    $h4 = $k4[$xy4];
                }
                $array[$type] = (($num > 99) ? str_replace(
                    array('12', '13', '14', '19', '20'),
                    array('هزار و دویست', 'هزار و سیصد', 'هزار و چهارصد', 'هزار و نهصد', 'دوهزار'),
                    substr($num, 0, 2)
                ) . ((substr($num, 2, 2) == '00') ? '' : ' و ') : '') . $h3 . $p34 . $h34 . $h4;
                break;

            case 'mm':
                $key = array('فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند');
                $array[$type] = $key[$num - 1];
                break;

            case 'rr':
                $key = array(
                    'یک', 'دو', 'سه', 'چهار', 'پنج', 'شش', 'هفت', 'هشت', 'نه', 'ده', 'یازده', 'دوازده', 'سیزده', 'چهارده', 'پانزده', 'شانزده', 'هفده', 'هجده', 'نوزده', 'بیست', 'بیست و یک', 'بیست و دو', 'بیست و سه', 'بیست و چهار', 'بیست و پنج', 'بیست و شش', 'بیست و هفت', 'بیست و هشت', 'بیست و نه', 'سی', 'سی و یک'
                );
                $array[$type] = $key[$num - 1];
                break;

            case 'rh':
                $key = array('یکشنبه', 'دوشنبه', 'سه شنبه', 'چهارشنبه', 'پنجشنبه', 'جمعه', 'شنبه');
                $array[$type] = $key[$num];
                break;

            case 'sh':
                $key = array('مار', 'اسب', 'گوسفند', 'میمون', 'مرغ', 'سگ', 'خوک', 'موش', 'گاو', 'پلنگ', 'خرگوش', 'نهنگ');
                $array[$type] = $key[$num % 12];
                break;

            case 'mb':
                $key = array('حمل', 'ثور', 'جوزا', 'سرطان', 'اسد', 'سنبله', 'میزان', 'عقرب', 'قوس', 'جدی', 'دلو', 'حوت');
                $array[$type] = $key[$num - 1];
                break;

            case 'ff':
                $key = array('بهار', 'تابستان', 'پاییز', 'زمستان');
                $array[$type] = $key[(int)($num / 3.1)];
                break;

            case 'km':
                $key = array('فر', 'ار', 'خر', 'تی‍', 'مر', 'شه‍', 'مه‍', 'آب‍', 'آذ', 'دی', 'به‍', 'اس‍');
                $array[$type] = $key[$num - 1];
                break;

            case 'kh':
                $key = array('ی', 'د', 'س', 'چ', 'پ', 'ج', 'ش');
                $array[$type] = $key[$num];
                break;

            default:
                $array[$type] = $num;
        }
    }
    return ($mod === '') ? $array : implode($mod, $array);
}


if (!function_exists('df')) {
    function df($date, $format = 'Y/m/d - H:i')
    {
        $carbon = new Carbon($date);
        if ($format === 'x') {
            return $carbon->timestamp * 1000;
        }
        if ($format === 'X') {
            return $carbon->timestamp;
        }
        return $carbon->format($format);
    }
}


/** Gregorian & Jalali (Hijri_Shamsi,Solar) date converter Functions
Author: JDF.SCR.IR =>> Download Full Version : http://jdf.scr.ir/jdf
License: GNU/LGPL _ Open Source & Free _ Version: 2.70 : [2017=1395]
--------------------------------------------------------------------
1461 = 365*4 + 4/4   &  146097 = 365*400 + 400/4 - 400/100 + 400/400
12053 = 365*33 + 32/4    &    36524 = 365*100 + 100/4 - 100/100   */

/*	F	*/
function gregorian_to_jalali($gy, $gm, $gd, $mod = '')
{
    list($gy, $gm, $gd) = explode('_', tr_num($gy . '_' . $gm . '_' . $gd));/* <= Extra :اين سطر ، جزء تابع اصلي نيست */
    $g_d_m = array(0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334);
    if ($gy > 1600) {
        $jy = 979;
        $gy -= 1600;
    } else {
        $jy = 0;
        $gy -= 621;
    }
    $gy2 = ($gm > 2) ? ($gy + 1) : $gy;
    $days = (365 * $gy) + ((int)(($gy2 + 3) / 4)) - ((int)(($gy2 + 99) / 100)) + ((int)(($gy2 + 399) / 400)) - 80 + $gd + $g_d_m[$gm - 1];
    $jy += 33 * ((int)($days / 12053));
    $days %= 12053;
    $jy += 4 * ((int)($days / 1461));
    $days %= 1461;
    $jy += (int)(($days - 1) / 365);
    if ($days > 365) $days = ($days - 1) % 365;
    if ($days < 186) {
        $jm = 1 + (int)($days / 31);
        $jd = 1 + ($days % 31);
    } else {
        $jm = 7 + (int)(($days - 186) / 30);
        $jd = 1 + (($days - 186) % 30);
    }
    return ($mod === '') ? array($jy, $jm, $jd) : $jy . $mod . $jm . $mod . $jd;
}

/*	F	*/
function jalali_to_gregorian($jy, $jm, $jd, $mod = '')
{
    list($jy, $jm, $jd) = explode('_', tr_num($jy . '_' . $jm . '_' . $jd));/* <= Extra :اين سطر ، جزء تابع اصلي نيست */
    if ($jy > 979) {
        $gy = 1600;
        $jy -= 979;
    } else {
        $gy = 621;
    }
    $days = (365 * $jy) + (((int)($jy / 33)) * 8) + ((int)((($jy % 33) + 3) / 4)) + 78 + $jd + (($jm < 7) ? ($jm - 1) * 31 : (($jm - 7) * 30) + 186);
    $gy += 400 * ((int)($days / 146097));
    $days %= 146097;
    if ($days > 36524) {
        $gy += 100 * ((int)(--$days / 36524));
        $days %= 36524;
        if ($days >= 365) $days++;
    }
    $gy += 4 * ((int)(($days) / 1461));
    $days %= 1461;
    $gy += (int)(($days - 1) / 365);
    if ($days > 365) $days = ($days - 1) % 365;
    $gd = $days + 1;
    foreach (array(0, 31, ((($gy % 4 == 0) and ($gy % 100 != 0)) or ($gy % 400 == 0)) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31) as $gm => $v) {
        if ($gd <= $v) break;
        $gd -= $v;
    }
    return ($mod === '') ? array($gy, $gm, $gd) : $gy . $mod . $gm . $mod . $gd;
}
