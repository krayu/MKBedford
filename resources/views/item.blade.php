@extends('layouts.app')

@section('content')
        @foreach($ads as $val)
        	<div id="go-back">
        		<a class="shadow" href="/">❮ Go Back</a>
        	</div>
			<div class="panel panel-default">
                <div class="panel-heading">
                	{{str_replace('-',' ',$val->title)}}
                </div>			
                <div class="panel-body">
                	<div class="item-image-placeholder col-md-8">
            			<img src="{{$val->img}}" class="item-image" />
                	</div>
                	<div class="item-info col-md-4">              	
                		<div class="list-group">
						  <a href="http://facebook.com/{{$val->profile}}" target="_blank" class="list-group-item green shadow">
						    <h4 class="list-group-item-heading">Contact {{$val->profile_name}} to buy</h4>
						  </a>
						</div>						
                		<div class="list-group">
						  <a href="{{$val->url}}" target="_blank" class="list-group-item active shadow">
						    <h4 class="list-group-item-heading">Price</h4>
						    <p class="list-group-item-text">£{{$val->price/100}}</p>
						  </a>
						</div>
                		<div class="list-group">
						  <a href="{{$val->url}}" target="_blank" class="list-group-item active shadow">
						    <h4 class="list-group-item-heading">Description</h4>
						    <p class="list-group-item-text">{{$val->message}}</p>
						  </a>
						</div>
                		<div class="list-group">
						  <a href="http://facebook.com/{{$val->profile}}" target="_blank" class="list-group-item active shadow">
						    <h4 class="list-group-item-heading">City</h4>
						    <p class="list-group-item-text">{{$val->city}}</p>
						  </a>
						</div>
                		<div class="list-group">
						  <a href="http://facebook.com/{{$val->profile}}" target="_blank" class="list-group-item active shadow">
						    <h4 class="list-group-item-heading">Seller</h4>
						    <p class="list-group-item-text">{{$val->profile_name}}</p>
						  </a>
						</div>
                		<div class="list-group">
						  <a href="{{$val->url}}" target="_blank" class="list-group-item active shadow">
						    <h4 class="list-group-item-heading">Source</h4>
						    <p class="list-group-item-text">Facebook Ads</p>
						  </a>
						 </div>	
                		<div class="list-group">
						  <a href="{{$val->url}}" target="_blank" class="list-group-item active shadow">
						    <h4 class="list-group-item-heading">Published</h4>
						    <p class="list-group-item-text">{{$val->published}}</p>
						  </a>
						</div>	
                		<div class="list-group">
						  <a href="http://facebook.com/{{$val->profile}}" target="_blank" class="list-group-item green shadow">
						    <h4 class="list-group-item-heading">Contact {{$val->profile_name}} to buy</h4>
						  </a>
						</div>													
                	</div>
                </div>
            </div>
        @endforeach
@endsection
