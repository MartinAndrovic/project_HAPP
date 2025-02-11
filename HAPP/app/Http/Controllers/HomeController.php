<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Category;
use App\Models\District;
use App\Models\Filter;
use App\Models\Region;
use App\Models\User;
use App\Models\Users_activity;
use App\Models\Users_category;
use App\Models\Users_district;
use App\Models\Users_region;
use Illuminate\Http\Request;
use App\Services\FilterMatchService;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $type = 'show';
        return view('home')->with('type', $type);
    }

    public function settings(Request $request)
    {
        $type = $request->type;
        if ($type == 'first') {
            return view('home')->with('type', $type);
        } else {
            return view('home')->with('type', $type);
        }
    }


    public function savePersonal(Request $request)
    {
        User::find(auth()->user()->id)->update(['age' => $request->age,
            'gender' => $request->gender]);
    }

    public function getPersonal()
    {
        $data = User::select('age', 'gender')
            ->where('id', '=', auth()->user()->id)
            ->first();

        return $data;
    }

    public function getFilters()
    {
        $filters = auth()->user()->filters->select('id', 'name');
        return $filters;
    }

    public function getFilter(Request $request)
    {
        $id = $request->id;
        $filter = Filter::find($id);
        $filter->categoriesList = $filter->users_categories()->join('categories', 'categories.id', '=', 'users_categories.category_id')->pluck('name');
        $filter->regionsList = $filter->users_regions()->join('regions', 'regions.id', '=', 'users_regions.region_id')->pluck('name');
        $filter->districtsList = $filter->users_regions()
            ->join('users_districts', 'users_districts.users_region_id', '=', 'users_regions.id')
            ->join('districts', 'districts.id', '=', 'users_districts.district_id')
            ->join('regions', 'regions.id', '=', 'districts.region_id')
            ->select('districts.name as name', 'regions.name as region')
            ->get();

        $filter->activitiesList = $filter->users_categories()
            ->join('users_activities', 'users_activities.users_category_id', '=', 'users_categories.id')
            ->join('activities', 'activities.id', '=', 'users_activities.activity_id')
            ->join('categories', 'categories.id', '=', 'activities.category_id')
            ->select('activities.name as name', 'categories.name as category')
            ->get();

        return compact('filter');
    }


    public function saveFilter(Request $request)
    {
        $filter = new Filter();
        $filter->user_id = auth()->user()->id;
        $filter->name = $request->name;
        $filter->age_from = $request->age_from;
        $filter->age_to = $request->age_to;
        $filter->date_from = $request->date_from;
        $filter->date_to = $request->date_to;
        $filter->group = $request->group;
        $filter->gender = $request->gender;
        $filtersaved = $filter->save();
        $regionsList = $request->regionsList;
        $districtsList = array_column($request->districtsList,'name');
        $categoriesList = $request->categoriesList;
        $activitiesList = array_column($request->activitiesList,'name');

        if ($regionsList != null) {
            $i = 0;
            while ($i < sizeof($regionsList)) {
                $users_region = new Users_region();
                $region_id = Region::where('name', $regionsList[$i])->value('id');
                $users_region->region_id = $region_id;
                $filter->users_regions()->save($users_region);
                $i++;
            }
        }
        if ($districtsList != null) {
            $i = 0;
            while ($i < sizeof($districtsList)) {
                $users_district = new Users_district();
                $district_id = District::where('name', $districtsList[$i])->value('id');
                $users_district->district_id = $district_id;
                $users_region_id = Users_region::join('regions', 'regions.id', '=', 'users_regions.region_id')
                    ->join('districts', 'districts.region_id', '=', 'regions.id')
                    ->where('districts.id', $district_id)
                    ->where('filter_id', $filter->id)
                    ->value('users_regions.id');
                Users_region::find($users_region_id)->users_districts()->save($users_district);
                $i++;
            }
        }
        if ($categoriesList != null) {
            $i = 0;
            while ($i < sizeof($categoriesList)) {
                $users_category = new Users_category();
                $category_id = Category::where('name', $categoriesList[$i])->value('id');
                $users_category->category_id = $category_id;
                $filter->users_categories()->save($users_category);
                $i++;
            }
        }
        if ($activitiesList != null) {
            $i = 0;
            while ($i < sizeof($activitiesList)) {
                $users_activity = new Users_activity();
                $activity_id = Activity::where('name', $activitiesList[$i])->value('id');
                $users_activity->activity_id = $activity_id;
                $users_category_id = Users_category::join('categories', 'categories.id', '=', 'users_categories.category_id')
                    ->join('activities', 'activities.category_id', '=', 'categories.id')
                    ->where('activities.id', $activity_id)
                    ->where('filter_id', $filter->id)
                    ->value('users_categories.id');
                $users_category = Users_category::find($users_category_id);
                $users_category->users_activities()->save($users_activity);
                $i++;
            }
        }
        if ($filtersaved) {
            $filterMatchService = new FilterMatchService();
            $filterMatchService->findMatches($filter);
        }

    }

    public function updateFilter(Request $request)
    {

        $filterID = $request->id;
        $filter = Filter::find($filterID);
        $updated_count = 0;

        $filter->name = $request->name;
        $filter->age_from = $request->age_from;
        $filter->age_to = $request->age_to;
        $filter->date_from = $request->date_from;
        $filter->date_to = $request->date_to;
        $filter->group = $request->group;
        $filter->gender = $request->gender;
        $filter->save();
        if ($filter->wasChanged(['age_from','age_to','date_from','date_to','group','gender'])) {
            $updated_count++;
        }
        $users_categories_inDB = $filter->users_categories()
            ->join('categories', 'categories.id', '=', 'users_categories.category_id')
            ->select('categories.name', 'users_categories.id')
            ->get();
        $users_categories_new = $request->categoriesList;
        $i = 0;
        while ($i < sizeof($users_categories_new)) {
            if (!$users_categories_inDB->contains('name', $users_categories_new[$i])) {
                $users_category = new Users_category();
                $users_category->category_id = Category::where('name', $users_categories_new[$i])->value('id');
                $saved = $filter->users_categories()->save($users_category);
                if ($saved) {
                    $updated_count++;
                }
            }
            $i++;
        }
        $i = 0;
        while ($i < sizeof($users_categories_inDB)) {
            if (!in_array($users_categories_inDB[$i]->name, $users_categories_new)) {
                $deleted = Users_category::find($users_categories_inDB[$i]->id)->delete();
                if ($deleted) {
                    $updated_count++;
                }
            }
            $i++;
        }
        $users_activities_inDB = $filter->users_categories()
            ->join('users_activities', 'users_activities.users_category_id', '=', 'users_categories.id')
            ->join('activities', 'activities.id', '=', 'users_activities.activity_id')
            ->select('users_activities.id', 'activities.name',)
            ->get();
        $users_activities_new = array_column($request->activitiesList, 'name');
        $i = 0;

        while ($i < sizeof($users_activities_new)) {
            if (!$users_activities_inDB->contains('name', $users_activities_new[$i])) {
                $users_activity = new Users_activity();
                $activity_id = Activity::where('name', $users_activities_new[$i])->value('id');
                $users_activity->activity_id = $activity_id;
                $users_activity->users_category_id = $filter->users_categories()
                    ->join('categories', 'categories.id', '=', 'users_categories.category_id')
                    ->join('activities', 'activities.category_id', '=', 'categories.id')
                    ->value('users_categories.id');
                $saved = $users_activity->save();
                if ($saved) {
                    $updated_count++;
                }
            }
            $i++;
        }
        $i = 0;

        while ($i < sizeof($users_activities_inDB)) {
            if (!in_array($users_activities_inDB[$i]->name, $users_activities_new)) {
                $deleted = Users_activity::find($users_activities_inDB[$i]->id)->delete();
                if ($deleted) {
                    $updated_count++;
                }
            }
            $i++;
        }
        /*---------------------------REGIONS----------------------*/
        $users_regions_inDB = $filter->users_regions()
            ->join('regions', 'regions.id', '=', 'users_regions.region_id')
            ->select('regions.name', 'users_regions.id')
            ->get();
        $users_regions_new = $request->regionsList;
        $i = 0;
        while ($i < sizeof($users_regions_new)) {
            if (!$users_regions_inDB->contains('name', $users_regions_new[$i])) {
                $users_region = new Users_region();
                $users_region->region_id = Region::where('name', $users_regions_new[$i])->value('id');
                $saved = $filter->users_regions()->save($users_region);
                if ($saved) {
                    $updated_count++;
                }
            }
            $i++;
        }
        $i = 0;
        while ($i < sizeof($users_regions_inDB)) {
            if (!in_array($users_regions_inDB[$i]->name, $users_regions_new)) {
                $deleted = Users_region::find($users_regions_inDB[$i]->id)->delete();
                if ($deleted) {
                    $updated_count++;
                }
            }
            $i++;
        }
        $users_districts_inDB = $filter->users_regions()
            ->join('users_districts', 'users_districts.users_region_id', '=', 'users_regions.id')
            ->join('districts', 'districts.id', '=', 'users_districts.district_id')
            ->select('users_districts.id', 'districts.name',)
            ->get();
        $users_districts_new = array_column($request->districtsList, 'name');
        $i = 0;

        while ($i < sizeof($users_districts_new)) {
            if (!$users_districts_inDB->contains('name', $users_districts_new[$i])) {
                $users_district = new Users_district();
                $district_id = District::where('name', $users_districts_new[$i])->value('id');
                $users_district->district_id = $district_id;
                $users_district->users_region_id = $filter->users_regions()
                    ->join('regions', 'regions.id', '=', 'users_regions.region_id')
                    ->join('districts', 'districts.region_id', '=', 'regions.id')
                    ->value('users_regions.id');
                $saved = $users_district->save();
                if ($saved) {
                    $updated_count++;
                }
            }
            $i++;
        }
        $i = 0;

        while ($i < sizeof($users_districts_inDB)) {
            if (!in_array($users_districts_inDB[$i]->name, $users_districts_new)) {
                $deleted = Users_district::find($users_districts_inDB[$i]->id)->delete();
                if ($deleted) {
                    $updated_count++;
                }
            }
            $i++;
        }
        if ($updated_count != 0) {
            $filterMatchService = new FilterMatchService();
            $filterMatchService->findMatches($filter);
        }
    }

    public function deleteFilter(Request $req)
    {
        $id = $req->id;
        $deleting = Filter::find($id);
        if ($deleting != null) {
            $deleting->delete();
        }
    }

}
