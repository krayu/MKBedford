<?php
namespace App\Http\Controllers;

require_once('Facebook/FacebookSession.php');
require_once('Facebook/FacebookRedirectLoginHelper.php');
require_once('Facebook/FacebookRequest.php');
require_once('Facebook/FacebookResponse.php');
require_once('Facebook/FacebookSDKException.php');
require_once('Facebook/FacebookRequestException.php');
require_once('Facebook/FacebookPermissionException.php');
require_once('Facebook/FacebookAuthorizationException.php');
require_once('Facebook/GraphObject.php');
require_once('Facebook/HttpClients/FacebookCurl.php');
require_once('Facebook/HttpClients/FacebookHttpable.php');
require_once('Facebook/HttpClients/FacebookCurlHttpClient.php');
require_once('Facebook/Entities/AccessToken.php');
require_once('Facebook/GraphUser.php');
 
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookPermissionException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\HttpClients\FacebookCurl;
use Facebook\HttpClients\FacebookHttpable;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\Entities\AccessToken;
use Facebook\GraphUser;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Ad;

class AdController extends Controller
{
	private $app_id = '1408147092825362';
	private $app_secret = 'f4f5e59c67c3f083d8f5a247c30f77e2';

    public function getHeaders($ads)
    {
        foreach ($ads as $key => $value) {
            $value['message'] = str_replace('-', '', $value['message']);
            $value['message'] = str_replace('+', '', $value['message']);
            $value['message'] = str_replace('.', '', $value['message']);
            $value['message'] = str_replace(',', '', $value['message']);
            $value['header'] = implode(' ', array_slice(explode(' ', $value['message']), 0, 3));
        }
        return $ads;
    }

    //main page
	public function index()
	{
        session(['all' => 'no']);
        session(['image' => 'no']);
        session(['lang' => '%']);
        session(['city' => '%']);
		$ads = Ad::take(51)->orderBy('published', 'desc')->where('message','!=','')->get();

        $ads = $this->getHeaders($ads);
		return view('main', ['ads' => $ads]);
	}

    //show specific category
    public function show($specific,Request $request)
    {
        switch($specific)
        {
            case 'all':session(['all' => 'yes']);session(['image' => 'no']);session(['lang' => '%']); session(['city' => '%']); break;
            case 'images' : session(['image' => 'yes']); session(['all' => 'no']);  break;
            case 'both' : session(['lang' => '%']); session(['all' => 'no']);  break;
            case 'polish' : session(['lang' => 'pl']); session(['all' => 'no']);  break;
            case 'english' : session(['lang' => 'en']); session(['all' => 'no']); break;
            case 'all-cities' : session(['city' => '%']); session(['all' => 'no']); break;
            case 'bedford' : session(['city' => 'bed']); session(['all' => 'no']); break;
            case 'luton' : session(['city' => 'lut']); session(['all' => 'no']);  break;
            case 'milton-keynes' : session(['city' => 'mil']); session(['all' => 'no']); break;
            case 'northampton' : session(['city' => 'nor']); session(['all' => 'no']);  break;
        }

        $all = session('all');
        $image = session('image');
        $lang = session('lang');
        $city = session('city');

        if($all == 'yes')
            $ads = Ad::take(51)->orderBy('published', 'desc')->where('message','!=','')->get();  
        elseif($image == 'yes')
            $ads = Ad::take(51)->orderBy('published', 'desc')->where('message','!=','')->where('img','<>','')->where('lang', 'like', $lang)->where('city', 'like', $city)->get();   
        else
            $ads = Ad::take(51)->orderBy('published', 'desc')->where('message','!=','')->where('lang', 'like', $lang)->where('city', 'like', $city)->get();    

        $ads = $this->getHeaders($ads);                         
        return view('main', ['ads' => $ads]);
    }    

    //show only one add
    public function item($id)
    {
        $ads = Ad::where('id', $id)->get();    
        foreach ($ads as $key => $value) {
             switch($value->city)
             {
                case 'lut': $value->city = 'Luton'; break;
                case 'bed': $value->city = 'Bedford'; break;
                case 'mil': $value->city = 'Milton Keynes'; break;
                case 'nor': $value->city = 'Northampton'; break;
             }
         } 
        return view('item', ['ads' => $ads]);
    }    

    //show search results
    public function results($val)
    {
        $ads = Ad::take(99)->orderBy('published','desc')->where('message','LIKE','% '.$val.'%')->get();
        $ads = $this->getHeaders($ads);
        if(count($ads) == 0)
            return redirect('/');
        else
            return view('main', ['ads' => $ads]);        
    }

    //using ajax show more ads
    public function showMore(Request $request)
    {
        $image = session('image');
        $lang = session('lang');
        $city = session('city');
        $offset = $request->counter;

        if($image == 'yes')
            $ads = Ad::take(9)->skip($offset)->orderBy('published', 'desc')->where('message','!=','')->where('img','<>','')->where('lang', 'like', $lang)->where('city', 'like', $city)->get();
        else    
            $ads = Ad::take(9)->skip($offset)->orderBy('published', 'desc')->where('message','!=','')->where('lang', 'like', $lang)->where('city', 'like', $city)->get();
        $ads = $this->getHeaders($ads);    
        return view('ads', ['ads' => $ads]);
    }

    
    //adding adds to database
    public function addAds()
    {
    	//not good
    	//Polacy w Milton Keynes, Bedford, Luton, Northampton - 1437132783165287
    	//Polski Biznes w Bedfordshire - 860314064058033
    	//dom polski, bedford - 36117266577
    	//Oddam Sprzedam Wszystko Co Mam.... - 749450951757448
    	//PL Lokalnie Luton, Milton, Bedford, Northampton, Hemel, Hatfield, St.Albans - 613008172144589
    	//Bedford 2 let - 504074149656401
    	$groups = array(
		['id' => '678386795557493', 'city' => 'mil', 'lang' => 'pl'],
		['id' => '935383179846792', 'city' => 'nor', 'lang' => 'pl'],
		['id' => '571815026292482', 'city' => 'bed', 'lang' => 'pl'],    		
		['id' => '977085258976239', 'city' => 'lut', 'lang' => 'pl'],
		['id' => '713742105310615', 'city' => 'bed', 'lang' => 'pl'],
		['id' => '751550764930583', 'city' => 'nor', 'lang' => 'pl'],
		['id' => '421743477964128', 'city' => 'bed', 'lang' => 'pl'],    		
		['id' => '633722216646815', 'city' => 'lut', 'lang' => 'pl'],
		['id' => '1675342532604739', 'city' => 'bed', 'lang' => 'pl'],
		['id' => '1554976711430011', 'city' => 'nor', 'lang' => 'pl'],    	
		['id' => '149780521866535', 'city' => 'lut', 'lang' => 'pl'],
		['id' => '303172633212931', 'city' => 'bed', 'lang' => 'pl'],
		['id' => '919283364765192', 'city' => 'lut', 'lang' => 'pl'],
		['id' => '360162850751908', 'city' => 'bed', 'lang' => 'pl'],
		['id' => '819924731454922', 'city' => 'lut', 'lang' => 'pl'],
		['id' => '571815026292482', 'city' => 'bed', 'lang' => 'pl'],
		['id' => '229076690528017', 'city' => 'bed', 'lang' => 'en'],    		
		['id' => '253078851520353', 'city' => 'nor', 'lang' => 'en'],
		['id' => '104954639544017', 'city' => 'bed', 'lang' => 'en'],
		['id' => '296296030477258', 'city' => 'mil', 'lang' => 'en'],
		['id' => '266911546792518', 'city' => 'bed', 'lang' => 'en'],  
		['id' => '141226829554135', 'city' => 'mil', 'lang' => 'en'],
		['id' => '411085638913317', 'city' => 'bed', 'lang' => 'en'],
		['id' => '510567899060056', 'city' => 'mil', 'lang' => 'en'],
		['id' => '192928520780475', 'city' => 'bed', 'lang' => 'en'],
		['id' => '294608527236331', 'city' => 'mil', 'lang' => 'en'],
		['id' => '171758846306495', 'city' => 'bed', 'lang' => 'en'],
		['id' => '1654062144864828', 'city' => 'mil', 'lang' => 'en'],
		['id' => '150745508341326', 'city' => 'bed', 'lang' => 'en'],
		['id' => '362573973775756', 'city' => 'mil', 'lang' => 'en'],
		['id' => '166415256780738', 'city' => 'bed', 'lang' => 'en'],
		['id' => '212088455561908', 'city' => 'mil', 'lang' => 'en'],
		['id' => '251457158257733', 'city' => 'bed', 'lang' => 'en'],
		['id' => '237773722992084', 'city' => 'mil', 'lang' => 'en'],
		['id' => '236240313166152', 'city' => 'bed', 'lang' => 'en'],
		['id' => '396337757065144', 'city' => 'bed', 'lang' => 'en'],      		
		['id' => '722446937826693', 'city' => 'nor', 'lang' => 'en'],
		['id' => '288356461255786', 'city' => 'bed', 'lang' => 'en'],
		['id' => '737050036309029', 'city' => 'nor', 'lang' => 'en'],
		['id' => '609830472463567', 'city' => 'bed', 'lang' => 'en'], 
        ['id' => '189656124461904', 'city' => 'mk', 'lang' => 'en'],              		
		['id' => '373049036057767', 'city' => 'bed', 'lang' => 'en'],
		['id' => '158606054327334', 'city' => 'nor', 'lang' => 'en'],
		['id' => '531373370255464', 'city' => 'bed', 'lang' => 'en'],
		['id' => '602940609742269', 'city' => 'nor', 'lang' => 'en'],
		['id' => '241447989369116', 'city' => 'bed', 'lang' => 'en'],
		['id' => '415445005178696', 'city' => 'nor', 'lang' => 'en'], 
		['id' => '208863302610001', 'city' => 'bed', 'lang' => 'en'],
		['id' => '452660824771170', 'city' => 'bed', 'lang' => 'en'],
		['id' => '501658936517750', 'city' => 'lut', 'lang' => 'en'],    		
		['id' => '250810604981656', 'city' => 'bed', 'lang' => 'en'],
 		['id' => '1439160499661178', 'city' => 'lut', 'lang' => 'en'],   		
		['id' => '1504917209740046', 'city' => 'bed', 'lang' => 'en'],
		['id' => '193027670821785', 'city' => 'lut', 'lang' => 'en'],
		['id' => '220225601327133', 'city' => 'bed', 'lang' => 'en'],
		['id' => '598651586872063', 'city' => 'lut', 'lang' => 'en'],
		['id' => '225098567593165', 'city' => 'bed', 'lang' => 'en'],
		['id' => '387450677952637', 'city' => 'lut', 'lang' => 'en'],
		['id' => '254356688101801', 'city' => 'bed', 'lang' => 'en'],
		['id' => '360270267343174', 'city' => 'lut', 'lang' => 'en'],  
		['id' => '672235332804254', 'city' => 'bed', 'lang' => 'en'], 			 		
		['id' => '270965682939584', 'city' => 'bed', 'lang' => 'en'],
		['id' => '232774276889869', 'city' => 'lut', 'lang' => 'en'],
		['id' => '527234344044248', 'city' => 'bed', 'lang' => 'en'],
		['id' => '170281086362769', 'city' => 'lut', 'lang' => 'en'],
 		['id' => '170573146349302', 'city' => 'bed', 'lang' => 'en'],   		
		['id' => '494106140728703', 'city' => 'bed', 'lang' => 'en'],
		['id' => '116487408495753', 'city' => 'lut', 'lang' => 'en'],
		['id' => '316055008455361', 'city' => 'bed', 'lang' => 'en'],
		['id' => '406106122814322', 'city' => 'lut', 'lang' => 'en'],
		['id' => '130796043747273', 'city' => 'bed', 'lang' => 'en'],
        ['id' => '250009115029505', 'city' => 'mil', 'lang' => 'en'],
        ['id' => '676018179126305', 'city' => 'bed', 'lang' => 'en'],
        ['id' => '207809519360903', 'city' => 'bed', 'lang' => 'en'],
        ['id' => '229274477128044', 'city' => 'bed', 'lang' => 'en'],
        ['id' => '432932080111425', 'city' => 'bed', 'lang' => 'en'],
        ['id' => '1484064048474096', 'city' => 'mil', 'lang' => 'en'],
        ['id' => '320191214837660', 'city' => 'lut', 'lang' => 'en'],
        ['id' => '771251919555624', 'city' => 'nor', 'lang' => 'en'],
        ['id' => '216432928404293', 'city' => 'mil', 'lang' => 'en'],
        ['id' => '616214935056932', 'city' => 'mil', 'lang' => 'en'],
        ['id' => '872633782794182', 'city' => 'mil', 'lang' => 'en'],
        ['id' => '1480379722251764', 'city' => 'bed', 'lang' => 'en'],
        ['id' => '359370580808804', 'city' => 'lut', 'lang' => 'en'],            
        ['id' => '592745997482430', 'city' => 'nor', 'lang' => 'pl'],
        ['id' => '199769523410201', 'city' => 'lut', 'lang' => 'en'],
        ['id' => '446928338725593', 'city' => 'mil', 'lang' => 'en'], 
        ['id' => '451304734930313', 'city' => 'bed', 'lang' => 'en'],
        ['id' => '451684104924472', 'city' => 'lut', 'lang' => 'en'],
        ['id' => '248089675221310', 'city' => 'nor', 'lang' => 'en'], 
        ['id' => '338465006264534', 'city' => 'bed', 'lang' => 'en']
		);

		foreach ($groups as $key => $value) {
			$this->addToDatabase($value['id'],$value['city'],$value['lang']);
		}
    }

    public function getTitle($title)
    {
        $title = strtok($title, ' ').' '.strtok(' ').' '.strtok(' ').' '.strtok(' ').' '.strtok(' ');
        $title = str_replace(',','',$title);
		$title = str_replace('http','',$title);
		$title = str_replace(':','',$title);
		$title = str_replace('/','',$title);
        $title = str_replace('!','',$title);
        $title = str_replace('£','',$title);
        $title = str_replace('- ','',$title);   
        $title = str_replace(' -','',$title);               
        $title = str_replace(' ','-',$title);
        $title = str_replace('ś','s',$title);
        $title = str_replace('ć','c',$title);
        $title = str_replace('ż','z',$title);
        $title = str_replace('ż','z',$title);
        $title = str_replace('ó','o',$title);
        $title = str_replace('ą','a',$title);
        $title = str_replace('ę','e',$title);
        $title = str_replace('ł','l',$title);
        return $title;
    }

    //adding groups to database - engine
    public function addToDatabase($group_id,$city,$lang)
    {
        FacebookSession::setDefaultApplication($this->app_id, $this->app_secret); 
        $session = new FacebookSession('EAAUAs8tCZARIBADZA24ZA6r48QZB73zaZCvVh0mcJj2uPjl9Mvb5ldzQnBsmPgXs71opbqbhEZBwMh14B8TWGm89dF9z3QGCOa9UgdStQZCINLVJ2fzR4k0o2oaMotSK76TyosZAXgHpOdRqvKZBFSrTo4H1yH0nJHPAZD');

        if($session) 
        {
          try {
                $response = (new FacebookRequest(
                $session, 'GET', '/'.$group_id.'?fields=feed.limit(20){id,created_time,full_picture,permalink_url,message,from}'
                ))->execute()->getGraphObject();
                $tab = $response->getProperty('feed')->getPropertyAsArray('data');
				

                foreach ($tab as $value) 
                {
                    if($value->getProperty('full_picture')!='' || $value->getProperty('message')!='')
                    Ad::firstOrCreate([
                        'page' => $group_id,
                        'ads_id' => $value->getProperty('id'),
                        'city' => $city,
                        'lang' => $lang,
                        'img' => $value->getProperty('full_picture')?$value->getProperty('full_picture'):'',
                        'url' => $value->getProperty('permalink_url'),
                        'message' => $message = $value->getProperty('message')?$value->getProperty('message'):'',
                        'title' => $this->getTitle($message),
                        'price' => preg_replace('/\D/', '', substr($message,strpos($message,'£'),5))*100,
                        'profile' => $value->getProperty('from')->getProperty('id'),
                        'profile_name' => $value->getProperty('from')->getProperty('name'),
                        'user_id' => 0,
                        'published' => $value->getProperty('created_time')
                        ]);


                      $ad =   Ad::firstOrNew([
                        'message' => $message = $value->getProperty('message')?$value->getProperty('message'):'',
                        'title' => $this->getTitle($message),
                        'profile' => $value->getProperty('from')->getProperty('id')
                        ]);

                      if ($ad->exists) {
                        } else {
                            Ad::create([
                                'page' => $group_id,
                                'ads_id' => $value->getProperty('id'),
                                'city' => $city,
                                'lang' => $lang,
                                'img' => $value->getProperty('full_picture')?$value->getProperty('full_picture'):'',
                                'url' => $value->getProperty('permalink_url'),
                                'message' => $message = $value->getProperty('message')?$value->getProperty('message'):'',
                                'title' => $this->getTitle($message),
                                'price' => preg_replace('/\D/', '', substr($message,strpos($message,'£'),5))*100,
                                'profile' => $value->getProperty('from')->getProperty('id'),
                                'profile_name' => $value->getProperty('from')->getProperty('name'),
                                'user_id' => 0,
                                'published' => $value->getProperty('created_time')
                                ]);
                        }

                }
            } 
            catch(FacebookRequestException $e) {
                echo "Exception occured, code: " . $e->getCode();
                echo " with message: " . $e->getMessage();
            }   
        }
    }

}