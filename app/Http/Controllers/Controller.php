<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}

/* Single File Upload
$image = request('image');
if ($image) {
	$name = uniqid().time().".".$image->getClientOriginalExtension();
	$image->move(public_path('storage/image'), $name);
	$path = 'storage/image/'.$name;
}else {
	$path = null;
}

 Multiple File Upload
$images = request('images');
if ($images) {
	foreach ($images as $image) {
		$name = uniqid().time().".".$image->getClientOriginalExtension();
		$image->move(public_path('storage/image'), $name);
		$path = 'storage/image/'.$name;
		$image_array[] = $path;
	}
}*/