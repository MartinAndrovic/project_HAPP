<?php

namespace App\Services;

use App\Models\Filter;
use App\Models\Filter_Match;
use App\Models\Notification;
use App\Models\User;
use App\Models\Users_activity;
use App\Models\Users_district;
use App\Notifications\NewMatch;
use App\Notifications\NewMatchNotification;

class FilterMatchService
{

    public function findMatches($filter)
    {
        $categories = $filter->users_categories()->pluck('category_id')->toArray();
        $activities = Users_activity::whereIn('users_category_id',$categories)->pluck('activity_id')->toArray();
        $regions = $filter->users_regions()->pluck('region_id')->toArray();
        $districts = Users_district::whereIN('users_region_id',$regions)->pluck('district_id')->toArray();
        $user_age = User::find($filter->user_id)->age;
        if($user_age == null){
            $user_age = 0;
        }
        $user_gender = User::find($filter->user_id)->gender;
        $filter_matches_in_DB = $filter->filter_matches()->get();
        $first = $filter_matches_in_DB->where('filter_1_id',$filter->id)->pluck('filter_2_id')->toArray();
        $second = $filter_matches_in_DB->where('filter_2_id',$filter->id)->pluck('filter_1_id')->toArray();
        $matched_filters_ids = array_merge($first,$second);

        $matching_filters = Filter::where('user_id', '!=', $filter->user_id)

               ->when($filter->group != 'any',fn($q) => $q->where('group',$filter->group)
                                                          ->orwhere('group','any'))
               ->when($filter->group == 'any', fn($q) => $q->where('id'))
               ->when($filter->date_from != null, fn($q) => $q->where([['date_from','<=',$filter->date_to],
                                                                       ['date_to','>=',$filter->date_from]])
                                                              ->orwhere('date_from','=', null))
               ->when($filter->date_from == null, fn($q) => $q->whereNotNull('id'))

               ->when($filter->age_from != null, function ($q) use ($filter, $user_age) {
                   $q->where(function ($q) use ($filter, $user_age) {
                       $q->where(function ($query) {
                           $query->select('age')
                                 ->from('users')
                                 ->whereColumn('users.id', 'filters.user_id');
                       }, '>=', $filter->age_from)
                           ->where(function ($query) {
                               $query->select('age')
                                     ->from('users')
                                     ->whereColumn('users.id', 'filters.user_id');
                           }, '<=', $filter->age_to);
                       })
                       ->orwhere('age_from',null)
                       ->where('age_from', '>=', $user_age)
                       ->where('age_to', '<=', $user_age);
               })

               ->when($filter->age_from == null,function ($q) use ($filter,$user_age){
                   $q->where([['age_from','<=',$user_age],
                             ['age_to','>=',$user_age]])
                     ->orwhere('age_from',null);
               })

               ->when($filter->gender != 'any',function ($q) use ($filter,$user_gender){
                   $q->where(function ($q) use ($filter,$user_gender){
                       $q->where(function ($q){
                           $q->select('gender')
                               ->from('users')
                               ->whereColumn('users.id','filters.user_id');
                       },$filter->gender)
                           ->where('gender',$user_gender)
                           ->orwhere('gender','any');
                   });
               })
               ->when($filter->gender == 'any', function ($q) use ($user_gender){
                       $q->where('gender',$user_gender)
                         ->orwhere('gender','any');
                   })

               ->when(sizeof($categories) > 0 && sizeof($activities) == 0 , function ($q) use ($categories) {
                   $q->whereexists(function ($q) use ($categories){
                       $q->select('category_id')
                         ->from('users_categories')
                         ->wherecolumn('users_categories.filter_id', 'filters.id')
                         ->whereIn('users_categories.category_id',$categories);
                })
                     ->orwhereDoesntHave('users_categories');
                })

               ->when(sizeof($activities) > 0, function ($q) use ($categories, $activities) {
                   $q->whereExists(function ($q) use ($categories, $activities){
                        $q->select('category_id')
                            ->from('users_categories')
                            ->where(function ($q) use ($activities){
                                $q->whereRaw('(select count(*) from users_activities where users_activities.users_category_id != users_categories.id) = 0')
                                  ->orwhereExists(fn ($q) => $q->select('id')->from('users_activities')
                                    ->whereIn('activity_id',$activities)
                                    ->where('users_category_id','users_categories.id'));
                        })
                            ->whereColumn('filter_id', 'filters.id')
                            ->whereIn('category_id',$categories);
                })
                     ->orwhereDoesntHave('users_categories');
                    })

               ->when(sizeof($categories) == 0, fn($q) => $q->whereNotNull('id'))
               ->when(sizeof($regions) > 0 && sizeof($districts) == 0 , function ($q) use ($regions) {
                   $q->whereexists(function ($q) use ($regions){
                        $q->select('region_id')
                            ->from('users_regions')
                            ->wherecolumn('users_regions.filter_id', 'filters.id')
                            ->wherein('region_id', $regions);
                   })
                     ->orwhereDoesntHave('users_regions');
               })
               ->when(sizeof($districts) > 0, function ($q) use ($regions, $districts) {
                   $q->whereExists(function ($q) use ($regions, $districts){
                        $q->select('region_id')
                            ->from('users_regions')
                            ->where(function ($q) use ($districts){
                             $q->whereRaw('(select count(*) from users_districts where users_districts.users_region_id != users_regions.id) = 0')
                                ->orwhereExists(fn ($q) => $q->select('id')->from('users_districts')
                                    ->whereIn('district_id',$districts)
                                    ->where('users_region_id','users_regions.id'));
                        })
                        ->whereColumn('filter_id', 'filters.id')
                        ->whereIn('region_id',$regions);
                })
                    ->orwhereDoesntHave('users_regions');
               })
               ->when(sizeof($regions) == 0, fn($q) => $q->whereNotNull('id'))

               ->get();
        if(sizeof($matching_filters) > 0){
            foreach($matching_filters as $match){
                $new_filter_match = new Filter_Match();
                $new_filter_match->filter_1_id = $filter->id;
                $new_filter_match->filter_2_id = $match->id;
                if(!in_array($match->id,$matched_filters_ids)){
                    if($new_filter_match->save()){
                        User::find($filter->user_id)->notify(new NewMatchNotification($new_filter_match->id));
                        $user2_id = Filter::find($match->id)->user()->value('id');
                        User::find($user2_id)->notify(new NewMatchNotification($new_filter_match->id));
                    }
                }
            }
        }
        if(sizeof($filter_matches_in_DB) > 0){
            foreach ($filter_matches_in_DB as $f_match_db){
                 $count = ($matching_filters->where('id','!=',$f_match_db->filter_1_id)
                     ->where('id','!=',$f_match_db->filter_2_id)
                     ->count());
                 if($count == 0){
                    Notification::where('filter_match_id',$f_match_db->id)->delete();
                    $f_match_db->delete();
                }
            }
        }
    }
}
