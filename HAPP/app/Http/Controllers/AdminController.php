<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Category;
use App\Models\Region;
use App\Models\District;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin/admin_panel');
    }

    public function users()
    {
        $users = User::all();
        return $users;
    }

    public function regions(Request $request)
    {
        $regions = Region::all();
        return $regions;
    }

    public function districts(Request $request)
    {
        if ($request->where != null) {
            $id = Region::where('name', $request->where)->pluck('id');
            $districts = null;
            if (District::where('region_id', $id)->exists()) {
                $districts = District::where('region_id', $id)->get();
            }
        } else{
            $districts = District::all();
        }
        return $districts;
    }

    public function categories()
    {
        $categories = Category::all();
        return $categories;
    }

    public function activities(Request $request)
    {
        if ($request->where != null) {
            $id = Category::where('name', $request->where)->pluck('id');
            $activities = null;
            if (Activity::where('category_id', $id)->exists()) {
                $activities = Activity::where('category_id', $id)->get();
            }
        } else {
            $activities = Activity::all();
        }
        return $activities;
    }

    public function deleteUser(Request $request)
    {
        $id = $request->id;
        $user = User::find($id);
        if ($user != null) {
            $user->delete();
        }
    }

    public function deleteRegion(Request $req)
    {
        $id = $req->id;
        $deleting = Region::find($id);
        if ($deleting != null) {
            $deleting->delete();
        }
    }

    public function deleteDistrict(Request $request)
    {
        $id = $request->id;
        $district = District::find($id);
        if ($district != null) {
            $district->delete();
        }
    }

    public function deleteCategory(Request $req)
    {
        $id = $req->id;
        $deleting = Category::find($id);
        if ($deleting != null) {
            $deleting->delete();
        }
    }

    public function deleteActivity(Request $request)
    {
        $id = $request->id;
        $activity = Activity::find($id);
        if ($activity != null) {
            $activity->delete();
        }
    }

    public function addRegion(Request $req)
    {
        $region = new Region();
        $region->name = $req->name;
        $region->save();
    }

    public function addDistrict(Request $req)
    {
        $district = new District();
        $district->name = $req->name;
        $region = Region::where('name', $req->parentRegion)->first();
        $region->districts()->save($district);
    }

    public function addCategory(Request $req)
    {
        $category = new Category();
        $category->name = $req->name;
        $category->save();
    }

    public function addActivity(Request $req)
    {
        $activity = new Activity();
        $activity->name = $req->name;
        $category = Category::where('name', $req->parentCategory)->first();
        $category->activities()->save($activity);
    }

    public function updateUserName(Request $request)
    {
        $id = $request->id;
        return User::find($id)->update(['name' => $request->name]);
    }

    public function updateRegionName(Request $request)
    {
        $id = $request->id;
        return Region::find($id)->update(['name' => $request->name]);
    }

    public function updateDistrictName(Request $request)
    {
        $id = $request->id;
        return District::find($id)->update(['name' => $request->name]);
    }

    public function updateCategoryName(Request $request)
    {
        $id = $request->id;
        return Category::find($id)->update(['name' => $request->name]);
    }

    public function updateActivityName(Request $request)
    {
        $id = $request->id;
        return Activity::find($id)->update(['name' => $request->name]);
    }

    public function addDistrictToRegion(Request $request)
    {
        $district = new District();
        $district->name = $request->name;
        $region = Region::find($request->id);
        $region->districts()->save($district);
    }
}
