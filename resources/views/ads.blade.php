        @foreach($ads as $i => $val)
            <div class="ads {{strlen($val->img)>0?'boximg':''}} {{$i%3==0?'first':''}} panel panel-default">
				<div class="panel-heading">
					<a title="{{$val->title}}" href="/item/{{$val->id}}/{{$val->title}}">
						{{$val->header}} 
					</a>	
                </div>
                <div class="panel-body">
                	<div class="for-image">
	                    <a title="{{$val->title}}" href="/item/{{$val->id}}/{{$val->title}}">
	                    	@if ($val->img != '')
	                    	<img src="{{$val->img}}" alt="{{$val->title}}" class="add-image" />
	                    	@endif
	                	</a>
	                </div>
	                <div class="for-text">	
	                    <a title="{{$val->title}}" href="/item/{{$val->id}}/{{$val->title}}">
                        	{{$val->message}}
                        </a>	
                	</div>
                </div>
            </div>
        @endforeach


