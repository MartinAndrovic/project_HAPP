<?php

namespace App\Http\Controllers;

use App\Models\Filter_Match;
use Illuminate\Http\Request;

class FilterMatchController extends Controller
{
    public function getUsersMatches(Request $request){
        $user_id = auth()->user()->id;
        $users_filters_ids = auth()->user()->filters()->pluck('id')->toArray();

        $matches = Filter_Match::wherein('filter_1_id',$users_filters_ids)->orwherein('filter_2_id',$users_filters_ids)
            ->join('filters as users_filter', function($join) use ($user_id){
                $join->on('users_filter.id','=','filter_matches.filter_1_id')
                     ->where('users_filter.user_id','=',$user_id)
                     ->oron('users_filter.id','=','filter_matches.filter_2_id')
                     ->where('users_filter.user_id','=',$user_id);
        })
            ->join('filters as other_filter', function($join) use ($user_id) {
                $join->on('other_filter.id','=','filter_matches.filter_1_id')
                    ->where('other_filter.user_id','!=',$user_id)
                    ->oron('other_filter.id','=','filter_matches.filter_2_id')
                    ->where('other_filter.user_id','!=',$user_id)
                    ->join('users','users.id','=','other_filter.user_id');
            })
            ->select('users_filter.id as users_filter_id','users_filter.name as users_filter_name','users.id as other_users_id','users.name as other_users_name')
            ->get();
        return $matches;
    }
}
