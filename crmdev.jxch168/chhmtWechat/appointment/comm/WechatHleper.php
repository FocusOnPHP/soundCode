<?php
class WechartHleper {
    //cache���Ŀ¼
	private $cacheFileUrl ;
    //��������
	private $parms = array(
		"appId"=>"wxfd3462aa749cb1e1",
		"appSecret"=>"6a3544fa00dd17cff35613e344af0a1d"
	);
    //����ʱʱ��
    private $accessTokenTimeOut  = 7100;
    //���캯��
   function __construct() {
		//cache���Ŀ¼
	   $this->cacheFileUrl = dirname(dirname(dirname(dirname(__FILE__)))) . '/Uploads/wechat.cache';
   }
   
   //��ȡAccessToken
	public function getAccessToken(){
        //����ļ�����,��ȡtoken ����Ϊnull
		$token = file_exists($this->cacheFileUrl)?file_get_contents($this->cacheFileUrl):null;
        //���token������
       //���Ϊnull���� ����7100����
		if (is_null($token)||time() - filemtime($this->cacheFileUrl)>$this->accessTokenTimeOut)
		{
            //����accessToken�ĵ�ַ 
			$r = @file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->parms["appId"].'&secret='.$this->parms["appSecret"]);
			$token = json_decode($r,true)["access_token"];
			file_put_contents($this->cacheFileUrl, $token);
		}
		return $token;
	}

    //��ȡ�û���Ȩ
    public function getUserAccessCode($redirect_uri){
        //echo 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->parms["appId"].'&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_base&state=1#wechat_redirect';
        header('Location:https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->parms["appId"].'&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_base&state=1#wechat_redirect');
        exit;
    }
}

