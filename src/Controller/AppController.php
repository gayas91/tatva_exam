<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Mailer\Email;
use Cake\I18n\I18n;
use Cake\Utility\Inflector;
use Cake\Mailer\TransportFactory;
use Cake\Datasource\ConnectionManager;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */

class AppController extends Controller {

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
	public function initialize() {
        parent::initialize();
		header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, HEAD');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Engaged-Auth-Token, AuthToken, language');

		$session = $this->request->session();
		
		$this->loadComponent('RequestHandler');
		$this->loadComponent('Flash');
		$this->loadComponent('Cookie');
		$this->viewBuilder()->helpers(['General']);
        
        $this->loadComponent('Auth');
        $this->loadComponent('Upload');
        if (isset($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin') {
            $this->viewBuilder()->layout('admin');
            $this->Auth->config('authenticate', [
				'Form' => [
					'fields' => [
						'username'	=>	'email',
						'password'	=>	'password'
					],
					'scope' => [
						'role_id IN'=>	[1,3],
						'status'	=>	1
					]
				]
			]);
            $this->Auth->config('authError', 'Did you really think you are allowed to see that?');
            $this->Auth->config('loginAction', [
				'prefix'	=>	'admin',
				'controller'=>	'users',
				'action'	=>	'login'
			]);
            $this->Auth->config('loginRedirect', [
				'prefix'	=>	'admin',
				'controller'=>	'users',
				'action'	=>	'dashboard'
			]);
            $this->Auth->config('logoutRedirect', [
				'prefix'	=>	'admin',
				'controller'=>	'users',
				'action'	=>	'login'
			]);
            $this->Auth->config('storage', [
				'className'	=>	'Session',
				'key'		=>	'Auth.Admin'
			]);
        } else {
			$this->Auth->config('storage', [
				'className'	=>	'Session',
				'key'		=>	'Auth.User'
			]);
            $this->Auth->config('authenticate', [
				'Form' => [
					'fields' => [
						'username'	=>	'email',
						'password'	=>	'password'
					],
					'scope'	=>	['role_id' => 2]
				]
			]);
            $this->Auth->config('loginRedirect', [
				'controller'=>	'Homes',
				'action'	=>	'dashboard'
			]);
            $this->Auth->config('logoutRedirect', [
				'controller'=>	'Homes',
				'action'	=>	'index'
			]);
			$this->Auth->config('loginAction', [
				'controller'=>	'Homes',
				'action'	=>	'index'
			]);
        }

        $auth = $this->Auth->user();
        $this->set('auth', $auth);
        if (isset($auth['role_id']) && !empty($auth['role_id']) && $auth['role_id'] == 2) {
            $this->loadModel('Users');
            $user = $this->Users->get($this->Auth->user('id'));
            $this->set('user', $user);
        } else {
            $this->set('user', $auth);
        }
        /*
         * Load helpers
         */
        $this->viewBuilder()->helpers(['Custom']);
        $meta_title = "";
        $meta_keyword = "";
        $meta_desc = "";
        $this->set(compact('meta_title', 'meta_keyword', 'meta_desc'));
		
		$settingData	=	$this->settingData();
		$this->set(compact('settingData'));
		
		/* down sit for perticular time */
		$controller	=	$this->request->params['controller'];
		$action		=	$this->request->params['action'];
		$prefix		=	isset($this->request->params['prefix']) ? $this->request->params['prefix'] : '';
		// pr($this->request->params);die;
		//print_r($_SERVER['REMOTE_ADDR']);
		//if($prefix !== 'admin' && ($controller !== 'Homes' && $action !== 'index')) {
		if(($_SERVER['REMOTE_ADDR'] !== '111.93.58.10') && ($prefix !== 'admin' && ($controller !== 'Homes' && $action !== 'index') && ($controller !== 'Pages'))) {
			$this->redirect(['controller'=>'homes','action'=>'index']);
		}

		// remove leading and trailing whitespace from posted data
		array_walk_recursive($this->request->data, array($this, 'trimItem'));
		
		## check permission
		$params	=	$this->request->params;
		$cont	=	$params['controller'];
		$actn	=	$params['action'];
		$ur = $this->request->here;
		$urs = explode('/',$ur);
		$cont = $cont1 = '';
		if(isset($urs[2]) && $urs[2] != ''){
			$cont = $urs[2];
		}
		if(isset($urs[3]) && $urs[3] != ''){
			$cont1 = $urs[3];
		}
		if($cont1 == 'points' || $cont == 'points'){
			$cont = 'point_system';
		}
		if($cont1 == 'withdraw-requests' || $cont == 'withdraw-requests'){
			$cont = 'withdraw_requests';
		}
		if($cont1 == 'series' || $cont == 'series'){
			$cont = 'series_contest';
		}
		if($cont1 == 'schedule' || $cont == 'schedule'){
			$cont = 'schedule_contest';
		}
		if($cont1 == 'payment-offers' || $cont == 'payment-offers'){
			$cont = 'payment_offer';
		}
		if($cont1 == 'schedule' || $cont == 'schedule'){
			$cont = 'change_password';
		}
		if($cont1 == 'change-password' || $cont == 'change-password'){
			$cont = 'change_password';
		}
		if($cont1 == 'tds-details' || $cont == 'tds-details'){
			$cont = 'tds';
		}
		if($cont1 == 'series-players' || $cont == 'series-players'){
			$cont = 'players';
		}
		if($cont1 == 'payment-offers' || $cont == 'payment-offers'){
			$cont = 'payment_offer';
		}
		if($cont1 == 'banners' || $cont == 'banners'){
			$cont = 'banner';
		}
		if($cont1 == 'mst-teams' || $cont == 'mst-teams'){
			$cont = 'teams';
		}
		if($cont1 == 'notifications' || $cont == 'notifications'){
			$cont = 'notification';
		}
		if($cont1 == 'settings' || $cont == 'settings'){
			$cont = 'setting';
		}
		if($cont1 == 'contents' || $cont == 'contents'){
			$cont = 'content';
		}
		if($cont1 == 'banners' || $cont == 'banners'){
			$cont = 'banner';
		}
		if($cont1 == 'email-templates' || $cont == 'email-templates'){
			$cont = 'email';
		}
		if($cont1 == 'change-password' || $cont == 'change-password'){
			$cont = 'change_password';
		}
		if($cont1 == 'blogs' || $cont == 'blogs'){
			$cont = 'blog';
		}
		if($cont1 == 'transactions' || $cont == 'transactions'){
			$cont = 'transactions';
		}
		if($cont1 == 'stock-series' || $cont == 'stock-series'){
			$cont = 'stock_series';
		}
		if($cont1 == 'stock-schedule' || $cont == 'stock-schedule'){
			$cont = 'stock_schedule';
		}
		if($cont1 == 'stock-series-players' || $cont == 'stock-series-players'){
			$cont = 'stock_players';
		}
		$role_id = '';
		$role_id = $this->request->session()->read('Auth.Admin.role_id');
		$module_access = $this->request->session()->read('Auth.Admin.module_access');
		/*echo $module_access;
		echo $ur;die;*/
		$ma = array();
		if($module_access != ''){
			$ma = explode(',',$module_access);
		}
		if($role_id != '' && $role_id != 2){
			if($role_id == 1 || ($role_id == 3 && !empty($ma) && (in_array($cont, $ma) || in_array($cont1, $ma))) || ($actn == 'dashboard' || $actn == 'login' || $actn == 'forgotPassword' || $actn == 'forgotPassword' || $actn == 'resetPassword')){

			}else{
				$this->Flash->success(__('You are not authenticated to use this module.',true));
				$this->redirect(['controller'=>'users','action'=>'dashboard']);
			}
		}
    }

    function ExportExcel($headers = array(), $data_arr = array(),$filename=null){
		
		$header_string = '';
		$delimiter = "\t";
		if(empty($filename)  && $filename ==''){
			$filename = 'DATA_SHEET_'.time().'_' . date("Y-m-d") . ".xls";
		}
			 
		$count = 0;
		header("Content-Disposition: attachment; filename=$filename");
		header("Pragma: no-cache");
		header("Expires: 0");
		if(!empty($headers)){
			foreach($headers as $key => $header_label){
				print $header_label."\t";
			} 
			print "\r\n"; 
		}
		$count = 1; 
		if (!empty($data_arr)) {
			foreach ($data_arr as $key=> $eval_data) {
				if(!empty($eval_data)){
					$row_data = '';
					foreach($eval_data as $key => $value){
						print $value."\t"; 
					}
					print "\r\n"; 
				}
			} 
		} 
		die;
	}
	
	public function getSeriesRankingAdmin($userId = null,$seriesId=null) {
		$this->loadModel('PlayerTeams');
		$this->loadModel('PlayerTeamContests');
		$this->loadModel('Users');
		$this->autoRander	=	false;
		$filter		=	[];
		$currentDate=	date('Y-m-d');
		if(!empty($seriesId)) {
			$filter[]	=	['PlayerTeamContests.series_id'=>$seriesId];
		}
		if(!empty($userId)) {
			$filter[]	=	['PlayerTeamContests.user_id'=>$userId];
		}
		
		$joinedContest	=	$this->PlayerTeamContests->find()
							->where([$filter,'SeriesSquad.match_status'=>MATCH_FINISH,'SeriesSquad.date <=' => $currentDate,'SeriesSquad.status'=>ACTIVE])
							->contain(['SeriesSquad'=>['fields'=>['match_status','date','status']],'PlayerTeams'=>['Series']])->order(['SeriesSquad.date'=>'DESC'])->toArray();
		//pr($joinedContest);die;
		$response	=	[];
		if(!empty($joinedContest)) {
			$i = 0;
			foreach($joinedContest as $key=> $joinedteams) {
				$response[$i]['series_name'] = $joinedteams->player_team->series->name;
				$response[$i]['user_id']		=	$userId;
				$response[$i]['points']		=	$joinedteams->player_team->points;
				$response[$i]['rank']		=	$joinedteams->rank;
				$response[$i]['series_id']	=	(int) $joinedteams->player_team->series_id;
				$response[$i]['previous_rank']=	0;
				$response[$i]['winning_amount']=	$joinedteams->winning_amount;
				$response[$i]['date']=	$joinedteams->series_squad->date;
				$i++;
			}
		} else {
			$this->loadModel('Series');
			$series	=	$this->Series->find()->where(['id_api'=>$seriesId])->first();
			$response[0]['user_id']		=	$userId;
			$response[0]['points']		=	0;
			$response[0]['rank']		=	0;
			$response[0]['series_id']	=	(int) $seriesId;
			$response[0]['series_name']	=	!empty($series) ? $series->name : '';
			$response[0]['previous_rank']=	0;
			$response[0]['winning_amount']=	0;
			$response[0]['date']=	0;
		}
		//pr($response);die;
		return $response;
	}
	
	function trimItem(&$item,$key){
		if (is_string($item)){
			$item = trim($item);    
		}
	}
	
	protected function sendMail($to, $subject, $message, $from) {
			$email = new Email();
			$email->transport('gmail');
			$email->template('default')
					->from([$from =>'TatvaTest'])
					->emailFormat('html')
					->to($to)
					->subject($subject)
					->send($message);
		// pr($email);die;
	}
	
	protected function sendInvoieMail($to, $subject, $message, $from) {
		$email = new Email();
		$email->transport('gmail');
		$email->template('invoice')
				->from([$from =>'TatvaTest'])
				->emailFormat('html')
				->to($to)
				->subject($subject)
				->send($message);
	}

    protected function calculateAge($dateOfBirth = NULL) {
        $today = date("Y-m-d");
        $diff = date_diff(date_create($dateOfBirth), date_create($today));
        return $diff->format('%y');
    }

    public function getLanguages() {

        $this->loadModel('Languages');
        $languages = $this->Languages->find('list', [
                    'keyField' => 'id',
                    'valueField' => 'name'
                ])->where(['Languages.status' => 1])->toArray();
        $this->set(compact('languages'));
    }

    protected function checkRecaptchaResponse($response) {
        // verifying the response is done through a request to this URL
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        // The API request has three parameters (last one is optional)
        $data = array('secret' => Configure::read('GoogleCaptchaSecretKey') ,
            'response' => $response,
            'remoteip' => $_SERVER['REMOTE_ADDR']);

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ),
        );

        // We could also use curl to send the API request
        $context = stream_context_create($options);
        $json_result = file_get_contents($url, false, $context);
        $result = json_decode($json_result);
        return $result->success;
        if (!empty($response)) {
            return true;
        } else {
            return false;
        }
    }

	function createSlug($string = NULL, $separtor = '-') {
		$string = substr($string, 0, 50);
		$slug = Inflector::slug(strtolower($string), $separtor);
		return $slug;
    }

	public function pushnotification($token=null,$message=null,$title=null){

 	  $url = 'https://fcm.googleapis.com/fcm/send';
		$apiKey = FCM;
		$registrationIds =$token;
		$msgSuccess = true;
		$msg = array
				(
					'body' => $message,
					'title' => $title,
					'color' => '#db2723',
					'icon' => 'http://gintonico.com/content/uploads/2015/03/fontenova.jpg', /* Default Icon */
					'sound' => 'mySound',/* Default sound */
					'click_action'=>'FCM_PLUGIN_ACTIVITY',
					'icon'=>'fcm_push_icon'
				);
		$fields = array
				(
					'registration_ids' => $registrationIds,
					'notification' => $msg,
					//'data'=>array('name'=>'anil','url'=>'app.product'),

				);
		$headers = array
				(
					'Authorization: key=' . $apiKey,
					'Content-Type: application/json'
				);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		$result = curl_exec($ch);
		$result = json_decode($result);
		curl_close($ch);
		return $msgSuccess;
	 }
	
	public function settingData() {
		$this->loadModel('Settings');
		$result	=	$this->Settings->find()->first();
		return $result;
	}
	
	public function seriesList() {
		$this->loadModel('Series');
		$result	=	$this->Series->find('list', ['keyField'=>'id_api','valueField'=>'name'])->order(['name'])->toArray();
		return $result;
	}
	
	public function upcomingSeriesList() {
		$this->loadModel('SeriesSquad');
		$currentDate	=	date('Y-m-d');
		$oneMonthDate	=	date('Y-m-d',strtotime('+4 Days'));
		$currentTime	=	date('H:i', strtotime('+10 min'));
		$upCommingMatch	=	$this->SeriesSquad->find()->where(['OR'=>[['date'=>$currentDate,'time >= '=>$currentTime],['date > '=>$currentDate]],'Series.status'=>ACTIVE,'SeriesSquad.status'=>ACTIVE])->contain(['Series'])->order(['Series.name'])->toArray();
		$seriesList		=	[];
		if(!empty($upCommingMatch)) {
			foreach($upCommingMatch as $series) {
				$seriesList[$series->series_id]	=	$series->series->name;
			}
		}
		return $seriesList;
	}
	
	public function upcomingSeriesListApp() {
		$this->loadModel('SeriesSquad');
		$currentDate	=	date('Y-m-d');
		$oneMonthDate	=	date('Y-m-d',strtotime('+4 Days'));
		$currentTime	=	date('H:i', strtotime('+10 min'));
		$upCommingMatch	=	$this->SeriesSquad->find()->where(['OR'=>[['date'=>$currentDate,'time >= '=>$currentTime],['date > '=>$currentDate ,'date <= '=>$oneMonthDate]],'Series.status'=>ACTIVE,'SeriesSquad.status'=>ACTIVE])->contain(['Series'])->order(['Series.name'])->toArray();
		$seriesList		=	[];
		if(!empty($upCommingMatch)) {
			foreach($upCommingMatch as $series) {
				$seriesList[$series->series_id]	=	$series->series->name;
			}
		}
		return $seriesList;
	}
	
	public function generateOPT($length) {
		$string		=	'0123456789';
		$strShuffled=	str_shuffle($string);
		$otp		=	substr($strShuffled, 1, $length);
		//$otp = '123456';
		return $otp;
	}
	
	public function createUserReferal($length) {
		$string		=	'0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ9630125478abcdefghijklmnopqrstuvwxyz9876543210';
		$strShuffled=	str_shuffle($string);
		$referCode	=	substr($strShuffled, 1, $length);
		return $referCode;
	}
	
	public function getInviteCode($matchId,$contestId) {
		$this->loadModel('MatchContest');
		$matchContest	=	$this->MatchContest->find()->where(['contest_id'=>$contestId,'SeriesSquad.match_id'=>$matchId])->contain(['SeriesSquad'])->select(['invite_code'])->first();
		return $matchContest;
	}

    public function getPlayerImage($id = NULL) {
    	$data = array();
        $this->loadModel('PlayerRecord');
        $list       =   $this->PlayerRecord->find('all',array('conditions'=>array('player_id'=>$id)))->first();
        $data['player_credit'] = isset($list['player_credit']) ? $list['player_credit'] : '';
        $data['player_name'] = isset($list['player_name']) ? $list['player_name'] : '';
        $data['player_image'] = isset($list['image']) ? SITE_URL.'uploads/player_image/'.$list['image'] : '';
        return $data;
    }

    public function getPlayerData($id,$series_id) {
    	$data = array();
        $this->loadModel('SeriesPlayers');
        $list       =   $this->SeriesPlayers->find('all',array('conditions'=>array('series_id'=>$series_id,'player_id'=>$id)))->first();
        $data['player_credit'] = isset($list['player_credit']) ? $list['player_credit'] : '';
        $data['player_name'] = isset($list['player_name']) ? $list['player_name'] : '';
        $data['player_image'] = isset($list['image']) ? SITE_URL.'uploads/player_image/'.$list['image'] : '';
        return $data;
    }
	
	public function saveTransaction($userId = null, $txnId=null,$status = null,$txnAmount = null,$series_id = '', $match_id='',$transactionType='',$withdraw_id = '') {
		$this->loadModel('Transactions');
		$entity	=	$this->Transactions->newEntity();
		$entity->user_id		=	$userId;
		if($series_id != ''){
			$entity->series_id		=	$series_id;
		}
		if($match_id != ''){
			$entity->match_id		=	$match_id;
		}
		if($withdraw_id != ''){
			$entity->withdraw_id		=	$withdraw_id;
		}
		$entity->txn_amount		=	$txnAmount;
		$entity->currency		=	"INR";
		$entity->txn_date		=	date('Y-m-d H:i:s');
		$entity->local_txn_id	=	$txnId;
		$entity->added_type		=	$status;
		$entity->transaction_type		=	!empty($transactionType) ? $transactionType : 1;
		$this->Transactions->save($entity);
		unset($this->Transactions->id);
	}
	
	public function getPlayerPoint($seried_id,$match_id,$player_id,$captain,$viceCaptain) {
		$point	=	0;
		$mType	=	'';
		$rePnt	=	array();
		$this->loadModel('LiveScore');
		$this->loadModel('PointSystem');
		$list 	=   $this->LiveScore->find('all',array('conditions'=>array('seriesId'=>$seried_id,'matchId'=>$match_id,'playerId'=>$player_id)))->select(['point','matchType'])->toArray();
		if(!empty($list)){
			foreach($list as $record) {
				$point	+=	$record['point'];
				$mType	=	$record['matchType'];
			}
			if(($mType=='Test') || ($mType=='First-class')){
				$rePnt	=	$this->PointSystem->find('all',array('conditions'=>array('matchType'=>'3')))->first();
			} elseif ($mType=='ODI') {
				$rePnt	=	$this->PointSystem->find('all',array('conditions'=>array('matchType'=>'2')))->first();
			} elseif ($mType=='T20') {
				$rePnt	=	$this->PointSystem->find('all',array('conditions'=>array('matchType'=>'1')))->first();
			} else {
				$rePnt	=	$this->PointSystem->find('all',array('conditions'=>array('matchType'=>'4')))->first();
			}
			if(!empty($rePnt)){
				$captainPoint		=	$rePnt->othersCaptain;
				$viceCaptainPoint	=	$rePnt->othersViceCaptain;
				if($captain == $player_id){
					$point	=	($point*$captainPoint);
				}
				if($viceCaptain == $player_id){
					$point	=	($point*$viceCaptainPoint);
				}
			}
		}
		return $point;
	}

	public function getWinningAmount($seried_id,$match_id,$rank,$contest_id)
	{
		$point=0;$rePnt=array();
		$this->loadModel('LiveScore');
		$this->loadModel('CustomBreakup');
		$list 	=   $this->LiveScore->find('all',array('conditions'=>array('seriesId'=>$seried_id,'matchId'=>$match_id)))->select(['matchStatus'])->first();
		if($list['matchStatus']=='Finished'){
			$rePnt = $this->CustomBreakup->find()
						->where(['contest_id'=>$contest_id,'start >='=>$rank,'end <='=>$rank])
						->select(['price'])->first();

			return $rePnt['price'];
		}
		return '';
	}

	public function getMatchStatus($series_id,$match_id){
		$this->loadModel('SeriesSquad');
		$list 	=   $this->SeriesSquad->find('all',array('conditions'=>array('series_id'=>$series_id,'match_id'=>$match_id)))->select(['match_status'])->first();
		if(!empty($list)){
			return $list['match_status'];
		}else{
			return '';
		}
	}

	public function getRankOfPoint1($series_id,$match_id,$point){
		$this->loadModel('PlayerTeams');
		$rankRow = $this->PlayerTeams->find('all',array('conditions'=>array('series_id'=>$series_id,'match_id'=>$match_id,'points'=>$point)))->order(['points' => 'DESC'])->toArray();
		foreach ($rankRow as $key => $row) {
			if($row->rank!=''){
				return $row->rank;
			}
		}
		return 0;
	}
	
	public function getSeriesRanking($userId = null,$seriesId=null) {
		$this->loadModel('PlayerTeams');
		$this->loadModel('PlayerTeamContests');
		$this->loadModel('Users');
		$this->autoRander	=	false;
		$filter		=	[];
		$currentDate=	date('Y-m-d');
		if(!empty($seriesId)) {
			$filter[]	=	['PlayerTeamContests.series_id'=>$seriesId];
		}
		if(!empty($userId)) {
			$filter[]	=	['PlayerTeamContests.user_id'=>$userId];
		}
		
		$joinedContest	=	$this->PlayerTeamContests->find()
							->where([$filter,'SeriesSquad.match_status'=>MATCH_FINISH,'SeriesSquad.date <=' => $currentDate,'SeriesSquad.status'=>ACTIVE])
							->contain(['SeriesSquad'=>['fields'=>['match_status','date','status']],'PlayerTeams'=>['Series']])
							->group(['PlayerTeamContests.series_id'])->toArray();
		
		$response	=	[];
		if(!empty($joinedContest)) {
			foreach($joinedContest as $key=> $joinedteams) {
				$teams	=	$joinedteams->player_team;
				$allSeriesTeam	=	$this->PlayerTeams->find()
									->where(['PlayerTeams.series_id'=>$teams->series_id])
									->order(['PlayerTeams.points'=>'DESC'])
									->group(['series_id','user_id'])
									->select(['user_id','series_id'])
									->toArray();
				
				$pointArr	=	[];
				if(!empty($allSeriesTeam)) {
					foreach($allSeriesTeam as $allKey => $allSeriesRecrds) {
						$query		=	$this->PlayerTeams->find('all');
						$userTeam	=	$query
										->where(['PlayerTeams.series_id'=>$allSeriesRecrds->series_id,'user_id'=>$allSeriesRecrds->user_id])
										->select(['user_id','player_points' => $query->func()->max('PlayerTeams.points')])
										->group(['user_id','match_id','series_id'])
										->order(['PlayerTeams.points'=>'DESC'])
										->toArray();
						
						$totalPoints=	0;
						if(!empty($userTeam)) {
							foreach($userTeam as $userRecords) {
								$totalPoints	+=	$userRecords->player_points;
							}
						}
						$pointArr[$allKey]['points']	=	$totalPoints;
						$pointArr[$allKey]['user_id']	=	$allSeriesRecrds->user_id;
						$pointArr[$allKey]['series_id']	=	$allSeriesRecrds->series_id;
					}
				}
				usort($pointArr, $this->make_comparer('points'));
				$ranks	=	0;
				$tmpRank=	0;
				$counter=	1;
				foreach($pointArr as $pointKey => $pointRate) {
					$seriesRank	=	$ranks;
					if($tmpRank == $pointRate['points']) {
						$counter++;
						$pointArr[$pointKey]['rank']	=	$ranks;
					} else {
						if($counter > 1) {
							$ranks	+=	$counter;
						} else {
							$ranks++;
						}
						$pointArr[$pointKey]['rank']	=	$ranks;
						$counter	=	1;
					}
					$tmpRank	=	$pointRate['points'];
					$pointArr[$pointKey]['rank']			=	$ranks;
					$pointArr[$pointKey]['previous_rank']	=	$ranks;
				}
				$myRank		=	0;
				$myPoints	=	0;
				if(!empty($userId)) {
					foreach($pointArr as $pointRank) {
						if($pointRank['user_id'] == $userId) {
							$myRank		=	$pointRank['rank'];
							$myPoints	=	$pointRank['points'];
						}
					}
					$response[$key]['points']		=	$myPoints;
					$response[$key]['rank']			=	$myRank;
					$response[$key]['series_name']	=	$teams->series->name;
					$response[$key]['series_id']	=	$teams->series_id;
					$response[$key]['previous_rank']=	$myRank;
					$response[$key]['winning_amount']=	!empty($joinedteams->winning_amount) ? $joinedteams->winning_amount : 0;
				} else {
					if(!empty($pointArr)) {
						foreach($pointArr as $key=>$teamPoints) {
							$user	=	$this->Users->find()
										->where(['id'=>$teamPoints['user_id']])
										->select(['image','team_name'])->first();
							$rootPatch	=	WWW_ROOT.'uploads'.DS.'users'.DS;
							$userImage	=	'';
							if(!empty($user->image) && file_exists($rootPatch.$user->image)) {
								$userImage	=	SITE_URL.'uploads/users/'.$user->image;
							}
							$pointArr[$key]['team_name']	=	!empty($user) ? $user->team_name : '';
							$pointArr[$key]['user_image']	=	$userImage;
						}
					}
					$response	=	$pointArr;
				}
					
			}
		} else {
			if($userId != 0){
				$this->loadModel('Series');
				$series	=	$this->Series->find()->where(['id_api'=>$seriesId])->first();
				$response[0]['user_id']		=	$userId;
				$response[0]['points']		=	0;
				$response[0]['rank']		=	0;
				$response[0]['series_id']	=	(int) $seriesId;
				$response[0]['series_name']	=	!empty($series) ? $series->name : '';
				$response[0]['previous_rank']=	0;
			}
		}
		return $response;
	}
	
	public function getTeamNumber($series_id,$match_id,$user_id){
		$this->loadModel('PlayerTeams');
		$data = array();
		$rankRow = $this->PlayerTeams->find('all',array('conditions'=>array('series_id'=>$series_id,'match_id'=>$match_id,'user_id'=>$user_id)))->toArray();
		if(!empty($rankRow)){
			foreach ($rankRow as $row) {
				$data[] = $row->team_count;					
			}
		}
		return $data;
	}
	
	function make_comparer() {
		// Normalize criteria up front so that the comparer finds everything tidy
		$criteria = func_get_args();
		foreach ($criteria as $index => $criterion) {
			$criteria[$index] = is_array($criterion)
				? array_pad($criterion, 3, null)
				: array($criterion, SORT_DESC, null);
		}
	 
		return function($first, $second) use ($criteria) {
			foreach ($criteria as $criterion) {
				// How will we compare this round?
				list($column, $sortOrder, $projection) = $criterion;
				$sortOrder = $sortOrder === SORT_DESC ? -1 : 1;
	 
				// If a projection was defined project the values now
				if ($projection) {
					$lhs = call_user_func($projection, $first[$column]);
					$rhs = call_user_func($projection, $second[$column]);
				}
				else {
					$lhs = $first[$column];
					$rhs = $second[$column];
				}
	 
				// Do the actual comparison; do not return if equal
				if ($lhs < $rhs) {
					return -1 * $sortOrder;
				}
				else if ($lhs > $rhs) {
					return 1 * $sortOrder;
				}
			}
	 
			return 0; // tiebreakers exhausted, so $first == $second
		};
	}
	
	public function saveDreamTeam($matchId=null,$seriesId=null) {
		$this->loadModel('LiveScore');
		$this->loadModel('DreamTeams');
		// $result	=	$this->LiveScore->find()
						// ->select(['LiveScore.playerId','LiveScore.point','PlayerRecord.player_name','PlayerRecord.playing_role','PlayerRecord.player_credit','PlayerRecord.image'])
						// ->contain(['PlayerRecord'])
						// ->where(['LiveScore.seriesId'=>$seriesId,'LiveScore.matchId'=>$matchId])->toArray();
		
		$query	=	$this->LiveScore->find();
		$result	=	$query
						->select(['LiveScore.playerId','player_point'=>$query->func()->sum('LiveScore.point'),'LiveScore.point','PlayerRecord.player_name','PlayerRecord.playing_role','PlayerRecord.player_credit','PlayerRecord.image'])
						->contain(['PlayerRecord'])
						->group(['LiveScore.playerId'])
						->where(['LiveScore.seriesId'=>$seriesId,'LiveScore.matchId'=>$matchId])->toArray();
		
		$array	=	[];
		if(!empty($result)) {
			foreach($result as $key => $record) {
				$dreamPlayer	=	$this->DreamTeams->find()->where(['series_id'=>$seriesId,'match_id'=>$matchId])->first();
				if(empty($dreamPlayer)) {
					$array[]	=	[
						'player_id'	=>	!empty($record->playerId) ? $record->playerId :0,
						'point'		=>	(double) $record->player_point,
						'name'		=>	$record->player_record['player_name'],
						'role'		=>	!empty($record->player_record['playing_role']) ? $record->player_record['playing_role'] :'Batsman',
						'credit'	=>	!empty($record->player_record['player_credit']) ? $record->player_record['player_credit'] : '0'
					];
					$point[]	=	$record->player_point;
				}
			}
			if(!empty($array)) {
				foreach($array as $key => $row) {
					$point[$key]  = $row['point'];
				}
				array_multisort($point, SORT_DESC, $array);
				
				$playerList	=	[];
				$playerList['Wicketkeeper']	=	[];
				$playerList['Batsman']		=	[];
				$playerList['Bowler']		=	[];
				$playerList['Allrounder']	=	[];
				foreach($array as $k => $v) {
					if(strpos($v['role'], 'Wicketkeeper') !== false && count($playerList['Wicketkeeper']) < 1) {
						$playerList['Wicketkeeper'][]	=	$array[$k];
						unset($array[$k]);
					} else
					if(stripos($v['role'], 'batsman') !== false && count($playerList['Batsman']) < 3) {
						$playerList['Batsman'][]	=	$array[$k];
						unset($array[$k]);
					} else
					if(stripos($v['role'], 'bowler') !== false && count($playerList['Bowler']) < 3) {
						$playerList['Bowler'][]		=	$array[$k];
						unset($array[$k]);
					} else
					if(stripos($v['role'], 'allrounder') !== false && count($playerList['Allrounder']) < 1) {
						$playerList['Allrounder'][]	=	$array[$k];
						unset($array[$k]);
					}
				}
				$flag	=	0;
				$playerRole	=	[];
				foreach ($array as $key => $value) {
					if($flag < 2)  {
						if(stripos($value['role'], 'Wicketkeeper') !== 0) {
							$playerList['top_player'][]	=	$array[$key];
							$playerRole[]	=	$value['role'];
							unset($array[$key]);
							$flag++;
						}
					}
				}
				$allrounder	=	$batsman	=	$bowler	=	[];
				foreach($playerRole as $roleKey => $roleValue) {
					if(stripos($roleValue,'Batsman') !== false) {
						$batsman[$roleKey]	=	$roleValue;
					}
					if(stripos($roleValue,'bowler') !== false) {
						$bowler[$roleKey]	=	$roleValue;
					}
					if(stripos($roleValue,'Allrounder') !== false) {
						$allrounder[$roleKey]	=	$roleValue;
					}
				}
				foreach($array as $roleKey1 => $roleValue1) {
					if(stripos($roleValue1['role'],'Batsman') !== false && count($batsman) == 2) {
						unset($array[$roleKey1]);
					}
					if(stripos($roleValue1['role'],'bowler') !== false && count($bowler) == 2) {
						unset($array[$roleKey1]);
					}
					if(stripos($roleValue1['role'],'allrounder') !== false && count($allrounder) == 2) {
						unset($array[$roleKey1]);
					}
				}
				$flag1	=	0;
				foreach ($array as $key1 => $value1) {
					if($flag1 < 1)  {
						if(stripos($value1['role'], 'Wicketkeeper') !== 0) {
							$playerList['top_player'][]	=	$array[$key1];
							unset($array[$key1]);
							$flag1++;
						}
					}
				}
				if(!empty($playerList['top_player'])) {
					foreach($playerList['top_player'] as $topPlayer) {
						if(stripos($topPlayer['role'],'Batsman') !== false) {
							$playerList['Batsman'][]	=	$topPlayer;
						}
						if(stripos($topPlayer['role'],'bowler') !== false) {
							$playerList['Bowler'][]	=	$topPlayer;
						}
						if(stripos($topPlayer['role'],'allrounder') !== false) {
							$playerList['Allrounder'][]	=	$topPlayer;
						}
					}
					unset($playerList['top_player']);
				}
				
				$playerDetails	=	array_merge($playerList['Wicketkeeper'],$playerList['Batsman'],$playerList['Bowler'],$playerList['Allrounder']);
				// pr($playerDetails);
				if(!empty($playerDetails)) {
					foreach($playerDetails as $dreamPlayer) {
						$dreamTeam	=	$this->DreamTeams->find()->where(['player_id'=>$dreamPlayer['player_id'],'series_id'=>$seriesId,'match_id'=>$matchId])->first();
						if(empty($dreamTeam)) {
							$dreamEntity	=	$this->DreamTeams->newEntity();
							$dreamEntity->match_id	=	$matchId;
							$dreamEntity->series_id	=	$seriesId;
							$dreamEntity->player_id	=	$dreamPlayer['player_id'];
							$dreamEntity->points	=	$dreamPlayer['point'];
							$this->DreamTeams->save($dreamEntity);
						}
					}
				}
			}
		}
	}
	
	public function sendNotificationAPNS($userId,$notiType,$ReceiverDeviceTokens,$title='', $message='',$data=array()) {
		if($ReceiverDeviceTokens=='') {
			return true;
		} else {
			error_reporting(0);
			if(empty($data)){
				$mtDate = '';
			}else{
				$mtDate = serialize($data);
			}
			if($notiType != '1' && $notiType != '15'){
				$this->loadModel('Notifications');
				$entity	=	$this->Notifications->newEntity();
				$entity->user_id				=	$userId;
				$entity->nitification_type		=	$notiType;
				$entity->title					=	$title;
				$entity->notification			=	$message;
				$entity->match_data				=	$mtDate;
				$entity->date					=	date('Y-m-d');
				$entity->status					=	'1';
				$entity->is_send				=	'1';
				$this->Notifications->save($entity);
				unset($this->Notifications->id);
			}

			$deviceToken=	$ReceiverDeviceTokens;
			//$deviceToken=	'518c3fc8987a5d74014f613eb7db48e20b4d6200577a38e9b8787cf599e76e52';
			$passphrase	=	123456;
			$message	=	$message;
			$ckpem 		= 	dirname(__FILE__) . '/pushcert.pem';
			$ctx	=	stream_context_create();
			stream_context_set_option($ctx, 'ssl', 'local_cert', $ckpem);
			stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);	//pem,file
			
			// Open a connection to the APNS server
			$fp	=	stream_socket_client(
					'ssl://gateway.push.apple.com:2195', $err,
					$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
			
			if(!$fp) {
				//exit("Failed to connect: $err $errstr" . PHP_EOL);
				exit();
			}
			
			// Create the payload body
			$alert['title']	=	$title;
			$alert['body']	=	$message;
			
			$body['aps']	=	array(
				'title'			=>	$title,
				'alert'			=>	$alert,
				'matchData'		=>	$data,
				'type'			=>	$notiType,
				'sound'			=>	'default'
			);
			
			// Encode the payload as JSON
			$payload	=	json_encode($body);
			
			// Build the binary notification
			$msg	=	chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
			
			// Send it to the server
			$result	=	fwrite($fp, $msg, strlen($msg));
			//pr($result);die;
			if(!$result) {
				return false; //echo 'Message not delivered' . PHP_EOL;
			
			} else {
				return true; //echo 'Message successfully delivered' . PHP_EOL;
			}
			
			// Close the connection to the server
			fclose($fp);

			return true;
		}	
	}

	public function sendNotificationFCM($userId,$notiType,$ReceiverDeviceTokens,$title='', $message='',$data=array()) {
		if($ReceiverDeviceTokens=='') {
			return true;
		} else {
			if(empty($data)){
				$mtDate = '';
			}else{
				$mtDate = serialize($data);
			}
			if($notiType != '1' && $notiType != '15'){
				$this->loadModel('Notifications');
				$entity	=	$this->Notifications->newEntity();
				$entity->user_id				=	$userId;
				$entity->nitification_type		=	$notiType;
				$entity->title					=	$title;
				$entity->notification			=	$message;
				$entity->match_data				=	$mtDate;
				$entity->date					=	date('Y-m-d');
				$entity->status					=	'1';
				$entity->is_send				=	'1';
				$this->Notifications->save($entity);
				unset($this->Notifications->id);
			}
			//$ReceiverDeviceTokens =	'eGrNbN4bj7k:APA91bFuJ0ZW974gE6OBLltZCdki7g4TVCdb5whfGVm-M_piqoQCOGMj6GKaQrFC5-TrVIHc2-CWsTGlqDzqRhiZTRKMTTYg78TcAyYteYMgt7bZ2FTnSzD-dy8XrJgon2HlgRbSiTRL';
			$key	=	FCM_KEY;
			$badge_count	=	1;
			$msg=	array(
				'body'			=>	trim(strip_tags($message)),
				'title'			=>	$title,
				'matchData'		=>	$data,
				'type'			=>	$notiType,
				'sound'			=>	'mySound',
				'badge_count'	=>	$badge_count
			);
			$fields	=	array(
				'to'	=>	$ReceiverDeviceTokens,
				'data'	=>	$msg
			);
			
			$headers=	array(
				'Authorization: key=' . $key,
				'Content-Type: application/json'
			);
			
			$ch	=	curl_init();
			curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
			curl_setopt( $ch,CURLOPT_POST, true );
			curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
			curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ));
			$result	=	curl_exec($ch ); //print_r($result);die();
			curl_close( $ch );
			#Echo Result Of FireBase Server
			// echo $result; die();
			//echo 'QWERTY';
			return true;
		}		
	}
	
	public function sendSms($otp = null, $mobileNo = null) {
		//$url	=	'http://sms.airsonocc.in/api/sendhttp.php?authkey=8898A9wn9TqZ5f6ae8d6P11&mobiles=91'.$mobileNo.'&message='.$otp.' is the OTP for your Contasy11 account. Never share your OTP with anyone.&sender=CONTSY&route=3';
		//file_get_contents($url);

		/*$msg = $otp.' is the OTP for your TatvaTest account. Never share your OTP with anyone.';
		$username = urlencode("r159");
        $msg_token = urlencode("izQnqw");
        $sender_id = urlencode("WBSTRK"); // optional (compulsory in transactional sms)
        $message = urlencode($msg);
        $mobile = urlencode($mobileNo);
        
        $api = "http://103.238.223.66/api/send_transactional_sms.php?username=".$username."&msg_token=".$msg_token."&sender_id=".$sender_id."&message=".$message."&mobile=".$mobile."";
	    file_get_contents($api);*/

	    $msg = $otp.' is the OTP for your TatvaTest account. Never share your OTP with anyone.';
        $mobile=$mobileNo;
        $username = urlencode("r159");
        $msg_token = urlencode("izQnqw");
        $sender_id = urlencode("WBSTRK");
      
        $message = urlencode($msg);
        $mobile = urlencode($mobile);
      
        $api = "http://manage.hivemsg.com/api/send_transactional_sms.php?username=".$username."&msg_token=".$msg_token."&sender_id=".$sender_id."&message=".$message."&mobile=".$mobile."";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        
        //echo 'messages script executed.';
	}
	
	public function sendAppLink($mobileNo = null) {
		$settingData	=	$this->settingData();
		$referral_code = $settingData->referral_code;
		
		//$url	=	'http://control.msg91.com/api/sendotp.php?authkey=273731A4VJktldtfTx5cc0094b&message=Play fantasy cricket and win big: Download the app from http://playstock11.com/ use referral code '.$referral_code.'&sender=PLAYST&mobile='.$mobileNo.'&otp='.$referral_code;
		
		//file_get_contents($url);
	}
	
	public function get_email_template($slug) {
		$this->loadModel('EmailTemplates');
		$template = $this->EmailTemplates->find()->where(['subject' => $slug])->first();
		// pr($template);die;
		// $template_text = $template->template;
		// $template_subject = $template->email_name;
		// $template_data = array($template_text, $template_subject);
		return $template;
    }
	
	public function finalDate($date = null) {
		$finalDate	=	date('Y-m-d',strtotime($date));
		return $finalDate.'T00:00:00';
	}
	
	public function getLocalTeam($seriesId=null,$matchId=null,$playerId=null) {
		$this->loadModel('SeriesSquad');
		$this->loadModel('SeriesPlayers');
		$seriesTeam	=	$this->SeriesSquad->find()->where(['match_id'=>$matchId,'series_id'=>$seriesId])->first();
		$islocalTeam	=	false;
		if(!empty($seriesTeam)) {
			$teamData	=	$this->SeriesPlayers->find()->where(['series_id'=>$seriesId,'player_id'=>$playerId,'team_id'=>$seriesTeam->localteam_id])->toArray();
			$islocalTeam	=	!empty($teamData) ? true : false;
		}
		return $islocalTeam;
	}

	public function createAutoContest($contest_id,$series_id,$match_id){
		$this->loadModel('Contest');
		$this->loadModel('CustomBreakup');
		$this->loadModel('SeriesSquad');
		$this->loadModel('MatchContest');
		$contestData = $this->Contest->find()->where(['id'=>$contest_id])->first();
		$entity	=	$this->Contest->newEntity();
		$entity->category_id		=	$contestData->category_id;
		$entity->admin_comission	=	$contestData->admin_comission;
		$entity->winning_amount		=	$contestData->winning_amount;
		$entity->contest_size		=	$contestData->contest_size;
		$entity->min_contest_size	=	$contestData->min_contest_size;
		$entity->contest_type		=	$contestData->contest_type;
		$entity->entry_fee			=	$contestData->entry_fee;
		$entity->confirmed_winning	=	$contestData->confirmed_winning;
		$entity->multiple_team		=	$contestData->multiple_team;
		$entity->auto_create		=	$contestData->auto_create;
		$entity->status				=	$contestData->status;
		$entity->price_breakup		=	$contestData->price_breakup;
		$entity->invite_code		=	$contestData->invite_code;
		$entity->used_bonus			=	$contestData->used_bonus;
		$entity->created			=	date('Y-m-d H:i:s');
		$entity->parent_id			=	($contestData->parent_id == 0) ? $contest_id : $contestData->parent_id;
		$entity->is_auto_create		=	2;
		$lastContestId 	= $this->Contest->save($entity);
		unset($this->Contest->id);
		$newContestId 	= $lastContestId->id;

		$breakupData = $this->CustomBreakup->find()->where(['contest_id'=>$contest_id])->toArray();
		foreach($breakupData as $row) {
			$entityN				=	$this->CustomBreakup->newEntity();
			$entityN->contest_id	=	$newContestId;
			$entityN->name			=	$row->name;
			$entityN->start			=	$row->start;
			$entityN->end			=	$row->end;
			$entityN->percentage	=	$row->percentage;
			$entityN->price			=	$row->price;
			$entityN->created		=	date('Y-m-d H:i:s');
			$this->CustomBreakup->save($entityN);
			unset($this->CustomBreakup->id);
		}

		$seriesTeam	=	$this->SeriesSquad->find()->where(['match_id'=>$match_id,'series_id'=>$series_id])->first();
		$mtchID 	= 	$seriesTeam->id;

		$string	=	'0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$strShuffled	=	str_shuffle($string);
		$contestStr		=	substr($strShuffled,0,5);

		$entityM	=	$this->MatchContest->newEntity();
		$entityM->match_id		=	$mtchID;
		$entityM->contest_id	=	$newContestId;
		$entityM->invite_code	=	'1Q'.$contestStr.substr(str_shuffle($strShuffled),0,5);
		$entityM->created		=	date('Y-m-d H:i:s');
		$lastContestId 	= $this->MatchContest->save($entityM);
		unset($this->MatchContest->id);

		return true;
	}
	
	public function saveJoinContestDetail($decoded=array(),$bonusAmount,$winAmount,$cashAmount,$playerTeamContestId) {
		$totalAmount = $bonusAmount+$winAmount+$cashAmount;
		$this->loadModel('Contest');
		$contestData = $this->Contest->find()->where(['id'=>$decoded['contest_id']])->first();
		$adminComission = $contestData->admin_comission;
		$entryfee = $contestData->entry_fee;
		$comission = (($adminComission/100) * $totalAmount);
		$comission = round($comission,2);
		
		
		$this->loadModel('JoinContestDetails');
		$saveEntity	=	$this->JoinContestDetails->newEntity();
		$saveEntity->user_id		=	$decoded['user_id'];
		$saveEntity->contest_id		=	$decoded['contest_id'];
		$saveEntity->series_id		=	$decoded['series_id'];
		$saveEntity->match_id		=	$decoded['match_id'];
		$saveEntity->bonus_amount	=	$bonusAmount;
		$saveEntity->winning_amount	=	$winAmount;
		$saveEntity->deposit_cash	=	$cashAmount;
		$saveEntity->total_amount   =	$totalAmount;
		$saveEntity->admin_comission=	$comission;
		$saveEntity->player_team_contest_id	=	$playerTeamContestId;
		$this->JoinContestDetails->save($saveEntity);
		return true;
	}
	
	public function adminWallet() {
		$this->loadModel('AdminWallet');
		$wallet	=	$this->AdminWallet->find()->first();
		return $wallet;
	}
	
	public function saveStockContestDetail($bonusAmount=null,$winAmount=null,$cashAmount=null,$playerTeamContestId=null) {
		$this->loadModel('StockPlayerTeamContests');
		$result	=	$this->StockPlayerTeamContests->find()->where(['id'=>$playerTeamContestId])->first();
		if(!empty($result)) {
			$totalAmount	=	$bonusAmount + $winAmount + $cashAmount;
			$contestData	=	$this->Contest->find()->where(['id'=>$result->contest_id])->first();
			$adminComission	=	$contestData->admin_comission;
			$entryfee		=	$contestData->entry_fee;
			$comission		=	(($adminComission/100) * $totalAmount);
			$comission		=	round($comission,2);
			
			$result->bonus_amount	=	$bonusAmount;
			$result->winning_wallet	=	$winAmount;
			$result->deposit_cash	=	$cashAmount;
			$result->total_amount	=	$totalAmount;
			$result->admin_comission=	$comission;
			$this->StockPlayerTeamContests->save($result);
			unset($this->StockPlayerTeamContests->id);
			return true;
		}
	}
	
	public function createAutoContestStock($contest_id = null, $series_id = null, $matchId = null) {
		$this->loadModel('Contest');
		$this->loadModel('CustomBreakup');
		$this->loadModel('StockMatchContest');
		$contestData = $this->Contest->find()->where(['id'=>$contest_id])->first();
		$entity	=	$this->Contest->newEntity();
		$entity->category_id		=	$contestData->category_id;
		$entity->admin_comission	=	$contestData->admin_comission;
		$entity->winning_amount		=	$contestData->winning_amount;
		$entity->contest_size		=	$contestData->contest_size;
		$entity->min_contest_size	=	$contestData->min_contest_size;
		$entity->contest_type		=	$contestData->contest_type;
		$entity->entry_fee			=	$contestData->entry_fee;
		$entity->confirmed_winning	=	$contestData->confirmed_winning;
		$entity->multiple_team		=	$contestData->multiple_team;
		$entity->auto_create		=	$contestData->auto_create;
		$entity->status				=	$contestData->status;
		$entity->price_breakup		=	$contestData->price_breakup;
		$entity->invite_code		=	$contestData->invite_code;
		$entity->used_bonus			=	$contestData->used_bonus;
		$entity->created			=	date('Y-m-d H:i:s');
		$entity->parent_id			=	$contest_id;
		$entity->infinite_contest	=	$contestData->infinite_contest;
		$entity->is_auto_create		=	2;

		$lastContestId 	= $this->Contest->save($entity);
		unset($this->Contest->id);
		$newContestId 	= $lastContestId->id;

		$breakupData = $this->CustomBreakup->find()->where(['contest_id'=>$contest_id])->toArray();
		foreach($breakupData as $row) {
			$entityN				=	$this->CustomBreakup->newEntity();
			$entityN->contest_id	=	$newContestId;
			$entityN->name			=	$row->name;
			$entityN->start			=	$row->start;
			$entityN->end			=	$row->end;
			$entityN->percentage	=	$row->percentage;
			$entityN->price			=	$row->price;
			$entityN->created		=	date('Y-m-d H:i:s');
			$this->CustomBreakup->save($entityN);
			unset($this->CustomBreakup->id);
		}

		$string	=	'0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$strShuffled	=	str_shuffle($string);
		$contestStr		=	substr($strShuffled,0,5);

		$entityM	=	$this->StockMatchContest->newEntity();
		$entityM->match_id		=	$matchId;
		$entityM->contest_id	=	$newContestId;
		$entityM->invite_code	=	'1Q'.$contestStr.substr(str_shuffle($strShuffled),0,5);
		$entityM->created		=	date('Y-m-d H:i:s');
		$lastContestId	=	$this->StockMatchContest->save($entityM);
		unset($this->StockMatchContest->id);
		return true;
	}
	
	public function getStockInviteCode($matchId,$contestId) {
		$this->loadModel('StockMatchContest');
		$matchContest	=	$this->StockMatchContest->find()->where(['contest_id'=>$contestId,'match_id'=>$matchId])->select(['invite_code'])->first();
		return $matchContest;
	}
	
	public function upcomingStockSeriesListApp() {
		$this->loadModel('StockSeriesSquad');
		$currentDate	=	date('Y-m-d H:i:s',strtotime(MATCH_DURATION));
		
		$upCommingMatch	=	$this->StockSeriesSquad->find()
							->where(['StockSeriesSquad.start_date > '=>$currentDate,'StockSeries.status'=>ACTIVE,'StockSeriesSquad.status'=>ACTIVE])
							->contain(['StockSeries'])->order(['StockSeries.name'])->toArray();
		$seriesList		=	[];
		if(!empty($upCommingMatch)) {
			foreach($upCommingMatch as $series) {
				$seriesList[$series->series_id]	=	$series->stock_series->name;
			}
		}
		return $seriesList;
	}
	
    public function getStockPlayerDetail($id = NULL) {
    	$data = array();
        $this->loadModel('StockSeriesPlayers');
        $list       =   $this->StockSeriesPlayers->find('all',array('conditions'=>array('id'=>$id)))->first();
        return $list;
    }
	
	#################### Portfolio ####################
	public function getPortfolioInviteCode($matchId,$contestId) {
		$this->loadModel('PortfolioMatchContest');
		$matchContest	=	$this->PortfolioMatchContest->find()->where(['contest_id'=>$contestId,'match_id'=>$matchId])->select(['invite_code'])->first();
		return $matchContest;
	}
	
	#################### Kabbadi Section ####################
	public function upcomingKabbadiSeriesList() {
		$this->loadModel('KabbadiMatches');
		$currentDate	=	date('Y-m-d H:i:s');
		$upCommingMatch	=	$this->KabbadiMatches->find()->where(['KabbadiMatches.start_date >= '=>$currentDate,'KabbadiSeries.status'=>ACTIVE,'KabbadiMatches.status'=>ACTIVE])->contain(['KabbadiSeries'])->order(['KabbadiSeries.compitition_name'])->toArray();
		$seriesList		=	[];
		if(!empty($upCommingMatch)) {
			foreach($upCommingMatch as $series) {
				$seriesList[$series->series_id]	=	$series->kabbadi_series->compitition_name;
			}
		}
		return $seriesList;
	}
	
	public function getKabbadiInviteCode($matchId,$contestId) {
		$this->loadModel('KabbadiMatchContest');
		$matchContest	=	$this->KabbadiMatchContest->find()->where(['contest_id'=>$contestId,'KabbadiMatches.match_id'=>$matchId])->contain(['KabbadiMatches'])->select(['invite_code'])->first();
		return $matchContest;
	}
	
	public function getKabbadiPlayerPoint($seriedId,$matchId,$playerId,$captain,$viceCaptain) {
		$point	=	0;
		$this->loadModel('KabbadiLiveScores');
		$list 	=   $this->KabbadiLiveScores->find()->where(['series_id'=>$seriedId,'match_id'=>$matchId,'player_id'=>$playerId])->select(['point'])->first();
		if(!empty($list)){
			$point	=	$list->point;
			
			$captainPoint		=	2;
			$viceCaptainPoint	=	1.5;
			if($captain == $playerId){
				$point	=	($point * $captainPoint);
			}
			if($viceCaptain == $playerId){
				$point	=	($point * $viceCaptainPoint);
			}
		}
		return $point;
	}
	
	public function getKabbadiLocalTeam($seriesId=null,$matchId=null,$playerId=null) {
		$this->loadModel('KabbadiMatches');
		$this->loadModel('KabbadiSeriesPlayers');
		$seriesTeam	=	$this->KabbadiMatches->find()->where(['match_id'=>$matchId,'series_id'=>$seriesId])->first();
		$islocalTeam	=	false;
		if(!empty($seriesTeam)) {
			$teamData	=	$this->KabbadiSeriesPlayers->find()->where(['series_id'=>$seriesId,'player_id'=>$playerId,'team_id'=>$seriesTeam->home_team_id])->toArray();
			$islocalTeam	=	!empty($teamData) ? true : false;
		}
		return $islocalTeam;
	}

	public function saveKabbadiContestDetail($decoded=array(),$bonusAmount=null,$winAmount=null,$cashAmount=null,$playerTeamContestId=null) {
		$totalAmount = $bonusAmount+$winAmount+$cashAmount;
		$this->loadModel('Contest');
		$contestData = $this->Contest->find()->where(['id'=>$decoded['contest_id']])->first();
		$adminComission = $contestData->admin_comission;
		$entryfee = $contestData->entry_fee;
		$comission = (($adminComission/100) * $totalAmount);
		$comission = round($comission,2);
		
		
		$this->loadModel('KabbadiJoinContestDetails');
		$saveEntity	=	$this->KabbadiJoinContestDetails->newEntity();
		$saveEntity->user_id		=	$decoded['user_id'];
		$saveEntity->contest_id		=	$decoded['contest_id'];
		$saveEntity->series_id		=	$decoded['series_id'];
		$saveEntity->match_id		=	$decoded['match_id'];
		$saveEntity->bonus_amount	=	$bonusAmount;
		$saveEntity->winning_amount	=	$winAmount;
		$saveEntity->deposit_cash	=	$cashAmount;
		$saveEntity->total_amount   =	$totalAmount;
		$saveEntity->admin_comission=	$comission;
		$saveEntity->player_team_contest_id	=	$playerTeamContestId;
		$this->KabbadiJoinContestDetails->save($saveEntity);
		return true;
		
		/* $this->loadModel('KabbadiPlayerTeamContests');
		$result	=	$this->KabbadiPlayerTeamContests->find()->where(['id'=>$playerTeamContestId])->first();
		if(!empty($result)) {
			$totalAmount	=	$bonusAmount + $winAmount + $cashAmount;
			$contestData	=	$this->Contest->find()->where(['id'=>$result->contest_id])->first();
			$adminComission	=	$contestData->admin_comission;
			$entryfee		=	$contestData->entry_fee;
			$comission		=	(($adminComission/100) * $totalAmount);
			$comission		=	round($comission,2);
			
			$result->bonus_amount	=	$bonusAmount;
			$result->winning_wallet	=	$winAmount;
			$result->deposit_cash	=	$cashAmount;
			$result->total_amount	=	$totalAmount;
			$result->admin_comission=	$comission;
			$this->KabbadiPlayerTeamContests->save($result);
			unset($this->KabbadiPlayerTeamContests->id);
			return true;
		} */
	}
	
    public function getKabbadiPlayerDetail($playerId = NULL,$seriesId = null) {
    	$data = array();
        $this->loadModel('KabbadiSeriesPlayers');
        $list       =   $this->KabbadiSeriesPlayers->find('all',array('conditions'=>array('player_id'=>$playerId,'series_id'=>$seriesId)))->first();
        $data['player_credit']	=	isset($list['player_credit']) ? $list['player_credit'] : '';
        $data['player_name']	=	isset($list['player_name']) ? $list['player_name'] : '';
		$rootFile		=	WWW_ROOT.'uploads'.DS.'kabbadi_player_image'.DS;
		$playerImage	=	'';
		if(!empty($list['player_image']) && file_exists($rootFile.$list['player_image'])) {
			$playerImage	=	SITE_URL.'uploads/kabbadi_player_image/'.$list['player_image'];
		}
        $data['player_image']	=	$playerImage;
        return $data;
    }
	
	public function getKabbadiSeriesRanking($userId = null,$seriesId=null) {
		$this->loadModel('KabbadiPlayerTeams');
		$this->loadModel('KabbadiPlayerTeamContests');
		$this->loadModel('Users');
		$this->autoRander	=	false;
		$filter		=	[];
		$currentDate=	date('Y-m-d H:i:s');
		if(!empty($seriesId)) {
			$filter[]	=	['KabbadiPlayerTeamContests.series_id'=>$seriesId];
		}
		if(!empty($userId)) {
			$filter[]	=	['KabbadiPlayerTeamContests.user_id'=>$userId];
		}
		
		$joinedContest	=	$this->KabbadiPlayerTeamContests->find()
							->where([$filter,'KabbadiMatches.match_status'=>K_RESULT,'KabbadiMatches.start_date <=' => $currentDate,'KabbadiMatches.status'=>ACTIVE])
							->contain(['KabbadiMatches'=>['fields'=>['match_status','start_date','status']],'KabbadiPlayerTeams'=>['KabbadiSeries']])
							->group(['KabbadiPlayerTeamContests.series_id'])->toArray();
		
		$response	=	[];
		if(!empty($joinedContest)) {
			foreach($joinedContest as $key=> $joinedteams) {
				$teams	=	$joinedteams->kabbadi_player_team;
				$allSeriesTeam	=	$this->KabbadiPlayerTeams->find()
									->where(['KabbadiPlayerTeams.series_id'=>$teams->series_id])
									->order(['KabbadiPlayerTeams.points'=>'DESC'])
									->group(['series_id','user_id'])
									->select(['user_id','series_id'])
									->toArray();
				
				$pointArr	=	[];
				if(!empty($allSeriesTeam)) {
					foreach($allSeriesTeam as $allKey => $allSeriesRecrds) {
						$query		=	$this->KabbadiPlayerTeams->find('all');
						$userTeam	=	$query
										->where(['KabbadiPlayerTeams.series_id'=>$allSeriesRecrds->series_id,'user_id'=>$allSeriesRecrds->user_id])
										->select(['user_id','player_points' => $query->func()->max('KabbadiPlayerTeams.points')])
										->group(['user_id','match_id','series_id'])
										->order(['KabbadiPlayerTeams.points'=>'DESC'])
										->toArray();
						
						$totalPoints=	0;
						if(!empty($userTeam)) {
							foreach($userTeam as $userRecords) {
								$totalPoints	+=	$userRecords->player_points;
							}
						}
						$pointArr[$allKey]['points']	=	$totalPoints;
						$pointArr[$allKey]['user_id']	=	$allSeriesRecrds->user_id;
						$pointArr[$allKey]['series_id']	=	$allSeriesRecrds->series_id;
					}
				}
				usort($pointArr, $this->make_comparer('points'));
				$ranks	=	0;
				$tmpRank=	0;
				$counter=	1;
				foreach($pointArr as $pointKey => $pointRate) {
					$seriesRank	=	$ranks;
					if($tmpRank == $pointRate['points']) {
						$counter++;
						$pointArr[$pointKey]['rank']	=	$ranks;
					} else {
						if($counter > 1) {
							$ranks	+=	$counter;
						} else {
							$ranks++;
						}
						$pointArr[$pointKey]['rank']	=	$ranks;
						$counter	=	1;
					}
					$tmpRank	=	$pointRate['points'];
					$pointArr[$pointKey]['rank']			=	$ranks;
					$pointArr[$pointKey]['previous_rank']	=	$ranks;
				}
				$myRank		=	0;
				$myPoints	=	0;
				if(!empty($userId)) {
					foreach($pointArr as $pointRank) {
						if($pointRank['user_id'] == $userId) {
							$myRank		=	$pointRank['rank'];
							$myPoints	=	$pointRank['points'];
						}
					}
					$response[$key]['points']		=	$myPoints;
					$response[$key]['rank']			=	$myRank;
					$response[$key]['series_name']	=	$teams->kabbadi_series->compitition_name;
					$response[$key]['series_id']	=	$teams->series_id;
					$response[$key]['previous_rank']=	$myRank;
					$response[$key]['winning_amount']=	!empty($joinedteams->winning_amount) ? $joinedteams->winning_amount : 0;
				} else {
					if(!empty($pointArr)) {
						foreach($pointArr as $key=>$teamPoints) {
							$user	=	$this->Users->find()
										->where(['id'=>$teamPoints['user_id']])
										->select(['image','team_name'])->first();
							$rootPatch	=	WWW_ROOT.'uploads'.DS.'users'.DS;
							$userImage	=	'';
							if(!empty($user->image) && file_exists($rootPatch.$user->image)) {
								$userImage	=	SITE_URL.'uploads/users/'.$user->image;
							}
							$pointArr[$key]['team_name']	=	!empty($user) ? $user->team_name : '';
							$pointArr[$key]['user_image']	=	$userImage;
						}
					}
					$response	=	$pointArr;
				}
					
			}
		} else {
			if($userId != 0){
				$this->loadModel('KabbadiSeries');
				$series	=	$this->KabbadiSeries->find()->where(['compitition_id'=>$seriesId])->first();
				$response[0]['user_id']		=	$userId;
				$response[0]['points']		=	0;
				$response[0]['rank']		=	0;
				$response[0]['series_id']	=	(int) $seriesId;
				$response[0]['series_name']	=	!empty($series) ? $series->compitition_name : '';
				$response[0]['previous_rank']=	0;
			}
		}
		return $response;
	}
	
	public function createAutoContestKabbadi($contest_id,$series_id,$match_id){
		$this->loadModel('Contest');
		$this->loadModel('CustomBreakup');
		$this->loadModel('KabbadiMatches');
		$this->loadModel('KabbadiMatchContest');
		$contestData = $this->Contest->find()->where(['id'=>$contest_id])->first();
		$entity	=	$this->Contest->newEntity();
		$entity->category_id		=	$contestData->category_id;
		$entity->admin_comission	=	$contestData->admin_comission;
		$entity->winning_amount		=	$contestData->winning_amount;
		$entity->contest_size		=	$contestData->contest_size;
		$entity->min_contest_size	=	$contestData->min_contest_size;
		$entity->contest_type		=	$contestData->contest_type;
		$entity->entry_fee			=	$contestData->entry_fee;
		$entity->confirmed_winning	=	$contestData->confirmed_winning;
		$entity->multiple_team		=	$contestData->multiple_team;
		$entity->auto_create		=	$contestData->auto_create;
		$entity->status				=	$contestData->status;
		$entity->price_breakup		=	$contestData->price_breakup;
		$entity->invite_code		=	$contestData->invite_code;
		$entity->used_bonus			=	$contestData->used_bonus;
		$entity->created			=	date('Y-m-d H:i:s');
		$entity->parent_id			=	($contestData->parent_id == 0) ? $contest_id : $contestData->parent_id;
		$entity->is_auto_create		=	2;
		$lastContestId 	= $this->Contest->save($entity);
		unset($this->Contest->id);
		$newContestId 	= $lastContestId->id;

		$breakupData = $this->CustomBreakup->find()->where(['contest_id'=>$contest_id])->toArray();
		foreach($breakupData as $row) {
			$entityN				=	$this->CustomBreakup->newEntity();
			$entityN->contest_id	=	$newContestId;
			$entityN->name			=	$row->name;
			$entityN->start			=	$row->start;
			$entityN->end			=	$row->end;
			$entityN->percentage	=	$row->percentage;
			$entityN->price			=	$row->price;
			$entityN->created		=	date('Y-m-d H:i:s');
			$this->CustomBreakup->save($entityN);
			unset($this->CustomBreakup->id);
		}

		$seriesTeam	=	$this->KabbadiMatches->find()->where(['match_id'=>$match_id,'series_id'=>$series_id])->first();
		$mtchID 	= 	$seriesTeam->id;

		$string	=	'0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$strShuffled	=	str_shuffle($string);
		$contestStr		=	substr($strShuffled,0,5);

		$entityM	=	$this->KabbadiMatchContest->newEntity();
		$entityM->match_id		=	$mtchID;
		$entityM->contest_id	=	$newContestId;
		$entityM->invite_code	=	'1Q'.$contestStr.substr(str_shuffle($strShuffled),0,5);
		$entityM->created		=	date('Y-m-d H:i:s');
		$lastContestId 	= $this->KabbadiMatchContest->save($entityM);
		unset($this->KabbadiMatchContest->id);

		return true;
	}
	
	public function saveKabbadiDreamTeam($matchId=null,$seriesId=null) {
		$this->loadModel('KabbadiLiveScores');
		$this->loadModel('KabbadiDreamTeams');
		$this->loadModel('KabbadiMatches');
		$result	=	$this->KabbadiLiveScores->find()
						->where(['KabbadiLiveScores.series_id'=>$seriesId,'KabbadiLiveScores.match_id'=>$matchId,'OR'=>[['starting7'=>0,'substitute_score !='=>'0'],['starting7'=>1]]])
						->select(['KabbadiLiveScores.player_id','KabbadiLiveScores.point','KabbadiSeriesPlayers.player_name','KabbadiSeriesPlayers.player_role','KabbadiLiveScores.team_id'])
						->contain(['KabbadiSeriesPlayers'])
						->order(['CAST(KabbadiLiveScores.point as signed)'=>'DESC'])->toArray();
						
		$match	=	$this->KabbadiMatches->find()->where(['match_id'=>$matchId])->select(['home_team_id','away_team_id'])->first();
		$array	=	[];
		if(!empty($result)) {
			$homeTeam	=	$match->home_team_id;
			$awayTeam	=	$match->away_team_id;;
			foreach($result as $key => $record) {
				$dreamPlayer	=	$this->KabbadiDreamTeams->find()->where(['series_id'=>$seriesId,'match_id'=>$matchId])->first();
				if(empty($dreamPlayer)) {
					$array[]	=	[
						'player_id'	=>	!empty($record->player_id) ? $record->player_id : 0,
						'point'		=>	(double) $record->point,
						'team_id'	=>	(double) $record->team_id,
						'name'		=>	$record->kabbadi_series_player->player_name,
						'role'		=>	!empty($record->kabbadi_series_player->player_role) ? $record->kabbadi_series_player->player_role :''
					];
					$point[]	=	$record->point;
				}
			}
			
			$homeTeamCount	=	0;
			$awayTeamCount	=	0;
			if(!empty($array)) {
				$playerList	=	[];
				$playerList['all_rounder']	=	[];
				$playerList['raider']		=	[];
				$playerList['defender']		=	[];
				foreach($array as $k => $v) {
					if($v['role'] == ALL_ROUNDER && count($playerList['all_rounder']) < 1) {
						$playerList['all_rounder'][]	=	$array[$k];
						($v['team_id'] == $homeTeam) ? $homeTeamCount++ : $awayTeamCount++;
						unset($array[$k]);
					} else
					if($v['role'] == RAIDER && count($playerList['raider']) < 1) {
						$playerList['raider'][]	=	$array[$k];
						($v['team_id'] == $homeTeam) ? $homeTeamCount++ : $awayTeamCount++;
						unset($array[$k]);
					} else
					if($v['role'] == DEFENDER && count($playerList['defender']) < 2) {
						$playerList['defender'][]	=	$array[$k];
						($v['team_id'] == $homeTeam) ? $homeTeamCount++ : $awayTeamCount++;
						unset($array[$k]);
					}
				}
				
				foreach($array as $playerKey =>$playerValue) {
					$totalTeam	=	$homeTeamCount + $awayTeamCount;
					if($playerValue['team_id'] == $homeTeam && $homeTeamCount < 5 && $totalTeam < 7) {
						if($playerValue['role'] == ALL_ROUNDER && count($playerList['all_rounder']) < 2) {
							$playerList['all_rounder'][]	=	$array[$playerKey];
							($playerValue['team_id'] == $homeTeam) ? $homeTeamCount++ : $awayTeamCount++;
							unset($array[$playerKey]);
						} else if($playerValue['role'] == RAIDER && count($playerList['raider']) < 3) {
							$playerList['raider'][]	=	$array[$playerKey];
							($playerValue['team_id'] == $homeTeam) ? $homeTeamCount++ : $awayTeamCount++;
							unset($array[$playerKey]);
							
						} else if($playerValue['role'] == DEFENDER && count($playerList['defender']) < 3) {
							$playerList['defender'][]	=	$array[$playerKey];
							($playerValue['team_id'] == $homeTeam) ? $homeTeamCount++ : $awayTeamCount++;
							unset($array[$playerKey]);
							
						}
					}
					if($playerValue['team_id'] == $awayTeam && $awayTeamCount < 5 && $totalTeam < 7) {
						if($playerValue['role'] == ALL_ROUNDER && count($playerList['all_rounder']) < 2) {
							$playerList['all_rounder'][]	=	$array[$playerKey];
							($playerValue['team_id'] != $awayTeam) ? $homeTeamCount++ : $awayTeamCount++;
							unset($array[$playerKey]);
						} else if($playerValue['role'] == RAIDER && count($playerList['raider']) < 3) {
							$playerList['raider'][]	=	$array[$playerKey];
							($playerValue['team_id'] != $awayTeam) ? $homeTeamCount++ : $awayTeamCount++;
							unset($array[$playerKey]);
							
						} else if($playerValue['role'] == DEFENDER && count($playerList['defender']) < 3) {
							$playerList['defender'][]	=	$array[$playerKey];
							($playerValue['team_id'] != $awayTeam) ? $homeTeamCount++ : $awayTeamCount++;
							unset($array[$playerKey]);
							
						}
					}
				}
				// pr($homeTeamCount.' -- '.$awayTeamCount);
				// pr($playerList);die;
				
				$playerDetails	=	array_merge($playerList['all_rounder'],$playerList['raider'],$playerList['defender']);
				
				if(!empty($playerDetails)) {
					foreach($playerDetails as $dreamPlayer) {
						$dreamTeam	=	$this->KabbadiDreamTeams->find()->where(['player_id'=>$dreamPlayer['player_id'],'series_id'=>$seriesId,'match_id'=>$matchId])->first();
						if(empty($dreamTeam)) {
							$dreamEntity	=	$this->KabbadiDreamTeams->newEntity();
							$dreamEntity->match_id	=	$matchId;
							$dreamEntity->series_id	=	$seriesId;
							$dreamEntity->player_id	=	$dreamPlayer['player_id'];
							$dreamEntity->points	=	$dreamPlayer['point'];
							$this->KabbadiDreamTeams->save($dreamEntity);
						}
					}
				}
			}
		}
	}
	
	public function getKabbadiSeriesRankingAdmin($userId = null,$seriesId=null) {
		$this->loadModel('KabbadiPlayerTeams');
		$this->loadModel('KabbadiPlayerTeamContests');
		$this->loadModel('Users');
		$this->autoRander	=	false;
		$filter		=	[];
		$currentDate=	date('Y-m-d H:i:s');
		if(!empty($seriesId)) {
			$filter[]	=	['KabbadiPlayerTeamContests.series_id'=>$seriesId];
		}
		if(!empty($userId)) {
			$filter[]	=	['KabbadiPlayerTeamContests.user_id'=>$userId];
		}
		
		$joinedContest	=	$this->KabbadiPlayerTeamContests->find()
							->where([$filter,'KabbadiMatches.match_status'=>K_RESULT,'KabbadiMatches.start_date <=' => $currentDate,'KabbadiMatches.status'=>ACTIVE])
							->contain(['KabbadiMatches'=>['fields'=>['match_status','start_date','status']],'KabbadiPlayerTeams'=>['KabbadiSeries']])->order(['KabbadiMatches.start_date'=>'DESC'])->toArray();
		
		$response	=	[];
		if(!empty($joinedContest)) {
			$i	=	0;
			foreach($joinedContest as $key => $joinedteams) {
				$response[$i]['series_name']=	$joinedteams->kabbadi_player_team->kabbadi_series->compitition_name;
				$response[$i]['user_id']	=	$userId;
				$response[$i]['points']		=	$joinedteams->kabbadi_player_team->points;
				$response[$i]['rank']		=	$joinedteams->rank;
				$response[$i]['series_id']	=	(int) $joinedteams->kabbadi_player_team->series_id;
				$response[$i]['winning_amount']=	$joinedteams->winning_amount;
				$response[$i]['date']		=	date('Y-m-d H:i:s',strtotime($joinedteams->kabbadi_match->start_date));
				$i++;
			}
		}
		return $response;
	}
	#################### Kabbadi Section ####################
	
	public function getTotalTeamContest($matchId=null) {
		$this->loadModel('MatchContest');
		$count	=	$this->MatchContest->find()->where(['match_id'=>$matchId,'isCanceled'=>0])->count();        
		return $count;
	}
	
	public function getTeamParticipantsContest($seriesId=null,$matchId=null,$contestId=null) {
		$this->loadModel('PlayerTeamContests');
		$filter	=	[];
		if(!empty($contestId)) {
			$filter['contest_id']	=	$contestId;
		}
		$count	=	$this->PlayerTeamContests->find('all', ['conditions' => ['series_id' => $seriesId, 'match_id' => $matchId,$filter]])->count();
		return $count;
	}
	
	public function getTotalEarningContest($seriesId=null,$matchId=null,$contestId=null) {
		$conn	=	ConnectionManager::get('default');
		$filter	=	'';
		if(!empty($contestId)) {
			$filter	=	'AND mc.contest_id ='.$contestId;
		}
		$stmt	=	$conn->execute("SELECT sum(total_amount) as total_amount, sum(bonus_amount) as bonus_amount FROM join_contest_details where series_id = $seriesId AND match_id = $matchId AND contest_id IN (SELECT mc.contest_id from series_squad as ss LEFT JOIN match_contest as mc ON ss.id = mc.match_id AND mc.isCanceled = 0 where ss.series_id = $seriesId AND ss.match_id = $matchId ".$filter.")");
		
		$results=	$stmt ->fetchAll('assoc');
		$totalEarning	=	$results[0];
		return $totalEarning;
	}
	
    public function getTotalWinningAmnt($series_id=null,$match_id=null,$contestId=null) {
        $conn	=	ConnectionManager::get('default');
        $filter	=	'';
		if(!empty($contestId)) {
			$filter	=	'AND contest_id ='.$contestId;
		}
        $stmt	=	$conn->execute("SELECT sum(winning_amount) as ttl FROM player_team_contests where series_id = $series_id AND match_id=$match_id ".$filter);
        $results=	$stmt ->fetchAll('assoc');
        $totalEarning	=	$results[0]['ttl'];

        return round($totalEarning,2);
    }
	
	public function numberToWords($amount=0) {
		$number = 	$amount;
		$no		=	floor($number);
		$point	=	round($number - $no, 2) * 100;
		$hundred=	null;
		$digits_1=	strlen($no);
		$i	=	0;
		$str=	array();
		$words	=	array('0' => '', '1' => 'One', '2' => 'Two',
			'3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
			'7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
			'10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
			'13' => 'Thirteen', '14' => 'Fourteen',
			'15' => 'Fifteen', '16' => 'sixteen', '17' => 'seventeen',
			'18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
			'30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
			'60' => 'Sixty', '70' => 'Seventy',
			'80' => 'Eighty', '90' => 'Ninety'
		);
		$digits	=	array('', 'hundred', 'thousand', 'lakh', 'crore');
		while($i < $digits_1) {
			$divider	=	($i == 2) ? 10 : 100;
			$number	=	floor($no % $divider);
			$no	=	floor($no / $divider);
			$i	+=	($divider == 10) ? 1 : 2;
			if($number) {
				$plural	=	(($counter = count($str)) && $number > 9) ? 's' : null;
				$hundred=	($counter == 1 && $str[0]) ? ' and ' : null;
				$str []	=	($number < 21) ? $words[$number] .
					" " . $digits[$counter] . $plural . " " . $hundred
					:
					$words[floor($number / 10) * 10]
					. " " . $words[$number % 10] . " "
					. $digits[$counter] . $plural . " " . $hundred;
			} else {
				 $str[] = null;
			}
		}
		$str = array_reverse($str);
		$result = implode('', $str);
		$points = ($point) ? "." . $words[$point / 10] . " " . $words[$point = $point % 10] : '';
		$paisaPoint	=	'';
		if($points) {
			$paisaPoint	=	$points . " Paise";
		}
		return $result . "Rupees  " . $paisaPoint;
	}
	
	public function getContestLeftMatch($contest_id,$match_id) {
        $this->loadModel('PlayerTeamContests');
        $totalTeamsJoined   =   $this->PlayerTeamContests->find()
	                            ->where(['PlayerTeamContests.match_id'=>$match_id,'PlayerTeamContests.contest_id'=>$contest_id])
	                            ->contain(['Users'=>['fields'=>['team_name','id','image']]])
	                            ->order(['PlayerTeamContests.user_id'])->count();
        return $totalTeamsJoined;
    }
	
}
