@extends('layouts.page')
@section('title', @$title)
@section('pageContent')
    <div class="inner_family_feed_page  sectionbox sectionbottom30 ">
        <div class="title_btn_row">
            <div class="create_title">
                <h1 class="main_title">Family List</h1>
            </div>
            <div class="createnew">
                <a href="{{route('familytree.index')}}" class="pink_btn">Go to Tree View</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped"  style="min-width:600px">
                <thead>
                    <tr>
                    <th scope="col">Profile Photo</th>
                    <th scope="col" >Member Name</th>
                    <th scope="col" >Email / Mobile</th>
                    <th scope="col">Relationship</th>
                    <th scope="col">Added On</th>
                    </tr>
                </thead>
            <tbody>
            @if($results->count()==0)
                <tr class="data" style="background-color: rgba(0,0,0,.05);">
                    <td colspan="8" align="center">No Records Found</td>
                </tr>
            @else
                @foreach($results as $key => $row)
                    <tr>
                    <td scope="row" class="align-middle" style="text-align:left;width:20%">
                        <div class="familylist-img-div">
                            @php
                                if(@$row->gender == 'male'){
                                    $default_image = url('images/userdefault.jpg');
                                } else{
                                    $default_image = url('images/female-user.jpg');
                                }
                             @endphp
                            @if(empty(@$row->getFamilyTreeUserEmail))
                             <img src="{{ @$row->image ? Common_function::get_s3_file(config('custom_config.s3_family_tree'),@$row->image) : $default_image }}" alt="Family Feed" class="img-fluid familylist-img {{($default_image) ? 'default_image' : ''}}" />
                            @else
                            <img src="{{ @$row->getFamilyTreeUserEmail->profile_image ? Common_function::get_s3_file(config('custom_config.s3_user_thumb'),@$row->getFamilyTreeUserEmail->profile_image) : url('images/userdefault-1.svg') }}" alt="Family Feed" class="img-fluid familylist-img" />
                            @endif
                        </div>
                    </td>
                        
                    <td class="breakword align-middle" style="width:20%;">
                    @if(@$row->getFamilyTreeUserEmail != '')
                        {{@$row->getFamilyTreeUserEmail->first_name.' '.@$row->getFamilyTreeUserEmail->last_name}}<br>
                        ({{(@$row->name != '') ? (@$row->name) : ('-')}}) 
                    @else
                        {{(@$row->name != '') ? @$row->name : '-'}}
                    @endif

                        @if(@$row->getFamilyTreeUserEmail != '')
                        <a href="{{route('public-profile.index',@$row->getFamilyTreeUserEmail->id)}}"  target="_blank" style="color:black;"><i class="fas fa-external-link-alt"></i></a>
                        @endif
                    </td>
                    <td class="breakword align-middle" style="width:20%;">{{(@$row->email != '') ? @$row->email : '-'}}<br>
                        {{@$row->phone_number}}
                    </td>
                    <td class="align-middle" style="width:20%;"> {{config('custom_config.fmailylist_relation_type')[@$row->relationship]}}</td>
                    <td class="align-middle" style="width:20%;">{{date("jS F, Y", strtotime(@$row->created_at))}}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
            </table>
        </div>
    </div>
   
@stop