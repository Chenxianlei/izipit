<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class yedeng extends CI_Controller {

	private $title;
	private $keywords;
	private $description;
	private $search;
	private $head;

	function __construct()
	{
		parent::__construct();

		$this->title       = $this->system_model->get_webtitle();
		$this->keywords    = $this->system_model->get_keywords();
		$this->description = $this->system_model->get_description();
		$this->search      = "";

    	if( $this->session->userdata('online') ) {
			$this->user = $this->session->userdata('Username');
		} else {
			$this->user = 0;
		}

		$this->head['search']      = $this->search;
		$this->head['title']       = $this->title;
		$this->head['keywords']    = $this->keywords;
		$this->head['description'] = $this->description;
	}

	public function index() {
		$offset = $this->input->get('page')?:0;
		$this->head['title'] = "夜灯-" . $this->title;
      	$this->load->view('default/mt_header.php',$this->head);
		$this->db->select('*')->from('yedeng');
		$all = $this->db->count_all_results();
      	$this->load->view('mt_yedeng.php', array('offset'=>$offset, 'total'=>$all));
      	
      	$this->load->view('default/mt_footer.php');
	}

	public function curl_list() {
		$ch = curl_init("http://bk2.radio.cn/mms4/videoPlay/getMorePrograms.jspa?programName=财经夜读&start=0&limit=20&channelId=15&callback=jQuery183026698157979774884_1488041219154&_=1488041789251") ;  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回  
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
		echo $output = curl_exec($ch) ; 
	}

	public function curl_get() {
		//$ch = curl_init("http://bk2.radio.cn/mms4/videoPlay/getVodProgramPlayUrlJson.jspa?programId=614262&programVideoId=0&videoType=PC&terminalType=515104503&dflag=1") ;  
		//$ch = curl_init("http://bk2.radio.cn/mms4reportDataCollectMgr/downloadData.jspa?programId=614262&videoType=PC");//old
		$ch = curl_init("http://www.radio.c/api/mobileservices.php?action=listen_click&id=614262&type=1");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回  
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
		echo $output = curl_exec($ch) ; 
	}

	public function save_data () {
		if ($_POST['url'] != '') {
			$data = [
				'mid' => $_POST['id'],
				'title' => $_POST['title'],
				'time' => $_POST['time'],
				'url' => $_POST['url'],
				'lrc' => ''
			];
			$this->db->insert('yedeng', $data);
			$insert_mid = $this->db->insert_id();
		}
        redirect(base_url().'yedeng', 'refresh');
	}

	function get_that_one()
	{
		$data = $this->db->select('ID,pic_uuid,pic_name,pic_url,pic_text,pic_type,pic_tag,pic_user,pic_collect,pic_like,pic_share,pic_view,pic_status,pic_datetime')->from('picture')->where(array('id >'=>1634, 'id <='=>1636))->get()->result_array();
		return $data;
	}

	public function wall()
	{
		
	}

	public function m3d()
	{
		$pic = $this->random(48);
		$pic2 = $this->get_that_one(1634);
		$pic = array_merge($pic, $pic2);
		foreach ($pic as $key => $val) {
			if (strpos($val['pic_url'], '.jpg')) {
				$data[]['src'] = str_replace('.jpg', '_thumb.jpg', 'http://www.izipit.top/'.$val['pic_url']);
			}
			if (strpos($val['pic_url'], '.jepg')) {
				$data[]['src'] = str_replace('.jepg', '_thumb.jepg', 'http://www.izipit.top/'.$val['pic_url']);
			}
			if (strpos($val['pic_url'], '.png')) {
				$data[]['src'] = str_replace('.png', '_thumb.png', 'http://www.izipit.top/'.$val['pic_url']);
			}

		}
		echo json_encode($data);
	}

    function random($num = 0) {  //随机返回3条记录
      $sql   = "SELECT * FROM picture WHERE pic_status = 1 ORDER BY RAND() LIMIT {$num}"; 
      $query = $this->db->query($sql);
      return $query->result_array();
    }

	function _get_cbb_list($start = 0, $limit = 5)
	{
		$ch = curl_init("http://bk2.radio.cn/mms4/videoPlay/getMorePrograms.jspa?programName=财经夜读&start=".$start."&limit=".$limit."&channelId=15&callback=json&_=1488041789251") ;  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回  
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
		$output = curl_exec($ch) ; 
		//$output = json_decode($output, true);
		//json({"total":1404,"programs":[{"programName":"财经夜读_2017-02-26","creationTime":"2017-02-25","programId":636928},{"programName":"财经夜读_2017-02-25","creationTime":"2017-02-25","programId":636560}]})
		$output = substr($output, 5, -1);
		$output = json_decode($output, true);
		return $output;
	}

	private function _get_diff($offset = 0, $output = array(), $ids = array(), $start = 0, $limit = 5)
	{
		$arr_list = array();

		foreach ($output['programs'] as $key => $val) {
			if (!in_array($val['programId'], $ids)) {
				$arr_list[$key]['title'] = $val['programName'];
				$arr_list[$key]['author'] = '经济之声';
				$arr_list[$key]['id'] = $val['programId'];
				$arr_list[$key]['uptime'] = $val['creationTime'];
				$arr_list[$key]['url'] = "http://bk2.radio.cn/mms4/videoPlay/getVodProgramPlayUrlJson.jspa?programId=".$val['programId']."&programVideoId=0&videoType=PC&terminalType=515104503&dflag=1";
				$arr_list[$key]['pic'] = 'http://www.izipit.top/upload/user/18ca38041958081b8e966faac5c803be_3.jpg';
				$arr_list[$key]['lrc'] = 'http://www.izipit.top/dist/js/player/c.lrc';
			}
		}
		if (empty($arr_list)) {
			// if (($start+5)<15) {
				$this->listinfo($offset, $start+5, $limit);
			// } else {
				// return array();				
			// }
		} else {
			return $arr_list;
		}
	}

	public function listinfo($offset = 0, $start = 0, $limit = 5) {
		$output = $this->_get_cbb_list($start, $limit);
		
		// 获取ids
		$my_cbb_db_list = $this->db->select('*')
								 ->from('yedeng')
								 ->order_by('time desc')
//								 ->limit(5,$offset = 0)
								 ->get()->result_array();
		$ids = array();
		foreach ($my_cbb_db_list as $val) {
			$ids[] = $val['mid'];
		}

		$data_db_list = $this->db->select('*')
								 ->from('yedeng')
								 ->order_by('time desc')
								 ->limit(5,$offset)
								 ->get()->result_array();
		$album = array();
		foreach ($data_db_list as $key => $val) {
			$album[$key]['title'] = $val['title'];
			$album[$key]['author'] = '经济之声';
			$album[$key]['url'] = $val['url'];
			$album[$key]['pic'] = 'http://www.izipit.top/upload/user/18ca38041958081b8e966faac5c803be_3.jpg';
			$album[$key]['lrc'] = 'http://www.izipit.top/dist/js/player/c.lrc';
		}
		//var_dump($ids);die;
		$arr_list = $this->_get_diff($offset, $output, $ids, $start, $limit);
		//var_dump($arr_list);


		if (!empty($arr_list)) {
			$ArrAlbum = array();
			$ArrAlbum = [
//					[
//			            'title'=>'La fille aux cheveux de lin',
//			            'author'=>'Claude Debussy',
//			            'url'=>'http://m10.music.126.net/20170301203344/229f63e32de0df014dbf38ffd8a44d1a/ymusic/160f/d6b3/e922/db2ebdd0ba0604400e53e1039cab3a98.mp3',
//			            'pic'=>'http://p3.music.126.net/1pIjVU7tV2NU5AWqgxK49A==/1297423720814393.jpg',
//			            'lrc'=>'http://www.izipit.top/dist/js/player/a.lrc'
//					],
					[
			            'title'=>'secret base~君がくれたもの~',
			            'author'=>'茅野愛衣',
			            'url'=>'http://devtest.qiniudn.com/secret base~.mp3',
			            'pic'=>'https://ss1.baidu.com/6ONXsjip0QIZ8tyhnq/it/u=2534129488,2639379667&fm=58',
			            'lrc'=>'https://aplayer.js.org/secret%20base~%E5%90%9B%E3%81%8C%E3%81%8F%E3%82%8C%E3%81%9F%E3%82%82%E3%81%AE~.lrc'
			        ],
//			        [
//			            'title'=>'财经夜读_20170210',
//			            'author'=>'经济之声',
//			            'url'=>'http://182.201.212.91/dl.radio.cn/aod2014/Archive/jjzs/2017/02/10/cjyd_1469674708648jjzs_1486738804047.m4a?wsiphost=local',
//			            'pic'=>'http://www.izipit.top/upload/user/18ca38041958081b8e966faac5c803be_3.jpg',
//			            'lrc'=>'http://www.izipit.top/dist/js/player/c.lrc'
//			        ]
			];
			$merge = array_merge($ArrAlbum, $album);
			$ArrOut = ($offset==0)?$merge:$album;
			echo json_encode(array('album'=>$ArrOut, 'list'=>$arr_list));
		}
	}
}
/* End of file xixi.php */
/* Location: ./application/controllers/xixi.php */