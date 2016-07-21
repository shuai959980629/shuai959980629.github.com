<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @车服务
 * @author zhoushuai
 * @copyright(c) 2016-05-15
 * @version
 */
class CarService
{
    private $CI = NULL;
    private $token = '158c70473eaaa184148838042fabcea6';//用户提交合作申请过后，由车300来进行审核，审核通过的用户将会分配唯一的用户访问标识
    private $apiUrl = 'http://api.che300.com/service/';//接口地址
    private $param=array();

    public function __construct()
    {
        $this->param['token']=$this->token;
        $this->CI =& get_instance();
    }


    /**
     * @本地服务获取所有省份
     */
    private function _initAllCity(){
        $path = DATA_PATH . 'meta/city' . DIRECTORY_SEPARATOR.'city_prov.json';
        $dta = file_get_contents($path);
        $dta = json_decode($dta,true);
        return $dta;
    }

    /**
     * @本地服务获取品牌
     */
    private function _initCarBrandList(){
        $path = DATA_PATH . 'meta/brand' . DIRECTORY_SEPARATOR.'series_brand.json';
        $brand_list = file_get_contents($path);
        $brand_list = json_decode($brand_list,true);
        return $brand_list;
    }

    /**
     * @本地服务获取系列
     */
    private function _initCarSeriesList($brandId){
        $path = DATA_PATH . 'meta/brand' . DIRECTORY_SEPARATOR.'series_brand'.$brandId.'.json';
        $series_list = file_get_contents($path);
        return $series_list;
    }

    /**
     * @本地服务模型
     */
    private function _initCarModelList($seriesId){
        $path = DATA_PATH . 'meta/model' . DIRECTORY_SEPARATOR.'model_series'.$seriesId.'.json';
        $model_list = file_get_contents($path);
        return $model_list;
    }

    /**
     * @本地服务，根据省份id。获取城市列表
     */
    private function _initCityByProvId($prov_id){
        $path = DATA_PATH . 'meta/city' . DIRECTORY_SEPARATOR.'city_prov'.$prov_id.'.json';
        $city_list = file_get_contents($path);
        return $city_list;
    }

    /**
     * @城市列表
     * http://api.che300.com/service/getAllCity
     * @return array
     */
    public function getAllCity(){
        $paramStr = createLinkstringUrlencode($this->param);
        $url      = $this->apiUrl.'getAllCity?'.$paramStr;
        $result   = https_request($url);
        $result   = json_decode($result,true);
        return $result;
    }

    /**
     * @品牌列表
     */
    public function getBrandList(){
        $paramStr = createLinkstringUrlencode($this->param);
        $url      = $this->apiUrl.'getCarBrandList?'.$paramStr;
        $result   = https_request($url);
        $result   = json_decode($result,true);
        return $result;
    }

    /**
     * @百度api,根据ip获取城市
     */
    public function address_baidu($ip)
    {
        $url     = "http://api.map.baidu.com/location/ip?ak=Gvvf07LZCQ2H0awlNqNyOZzF&ip={$ip}&coor=bd09ll";
        $result  = https_request($url);
        $result  = json_decode($result, true);
        $address = explode('|',$result['address']);
        return $address;
    }

    /**
     * @param prov_id 省id
     * @param zone 当前所在城市
     * @example $data = array('prov_id'=>'','zone'=>'');
     * @return array
     */
    public function getProvince($data=array()){
        $allCity  = $this->getAllCitylist();
        $province = array();
        $current_city = array();
        foreach($allCity['city_list'] as $value){
            $province[$value['prov_id']]=$value['prov_name'];
            if(!empty($data['zone']) && $value['city_name']==$data['zone']){
                $allCity['city_id']=$value['city_id'];
                $allCity['prov_id']=$value['prov_id'];
            }else{
                $allCity['city_id'] = $allCity['prov_id']=22;//默认成都。
            }
            if($value['city_id']==$allCity['city_id'] && $value['prov_id']==$allCity['prov_id']){
                $current_city = $value;
            }
        }
        ksort($province);
        if(empty($data['prov_id'])){
            $dta['current_city'] = $current_city;
            $dta['province'] =$province;
            return $dta;
        }else{
            return array_key_exists($data['prov_id'],$province)?$province[$data['prov_id']]:false;
        }
    }




    public function getCityByCId($city_id){
        $allCity = $this->getAllCitylist();
        $city = array();
        foreach($allCity['city_list'] as $value){
            if($value['city_id']==$city_id){
                $city=$value;
                break;
            }
        }
        return $city;
    }


    /**
     * @城市列表
     * http://api.che300.com/service/getAllCity
     * @return array
     */
    public function getAllCitylist(){
        //从本地服务器取数据
        $allCity  = $this->_initAllCity();
        if(empty($allCity)){
            $allCity  = $this->getAllCity();
        }
        return $allCity;
    }


    /**
     * @ 品牌列表
     * @http://api.che300.com/service/getCarBrandList
     * @return array
     */
    public function getCarBrandList(){
        //从本地服务器取数据
        $brand_list=$this->_initCarBrandList();
        if(empty($brand_list)){
            $result = $this->getBrandList();
            foreach($result['brand_list'] as $value){
                $brand_list[$value['initial']][]=$value;
            }
        }
        return $brand_list;
    }

    /**
     * @城市列表
     */
    public function getCityByProvId($prov_id=''){
        //从本地服务器取数据
        $city = $this->_initCityByProvId($prov_id);
        if(empty($city)){
            $allCity = $this->getAllCitylist();
            $current_city = array();
            foreach($allCity['city_list'] as $value){
                $city[$value['prov_id']][]=$value;
                if($value['city_id']==$allCity['city_id'] && $value['prov_id']==$allCity['prov_id']){
                    $current_city = $value;
                }
            }
            ksort($city);
            if(empty($prov_id)){
                $data['current_city'] = $current_city;
                $data['city'] = $city;
                $city = $data;
            }else{
                $city= array_key_exists($prov_id,$city)?$city[$prov_id]:false;
            }
            $city = json_encode($city);
        }
        return $city;
    }

    /**
     * @车系列表
     * http://api.che300.com/service/getCarSeriesList
     * @param brandId unsigned int	 品牌标识，可以通过车300品牌数据接口拿回所有的品牌信息，从而提取品牌标识。
     * @return array
     */
    public function getCarSeriesList($brandId){
        //从本地服务器取数据
        $series_list=$this->_initCarSeriesList($brandId);
        if(empty($series_list)){
            $this->param['brandId']=$brandId;
            $paramStr = createLinkstringUrlencode($this->param);
            $url = $this->apiUrl.'getCarSeriesList?'.$paramStr;
            $result = https_request($url);
            $result=json_decode($result,true);
            $series_list = json_encode($result['series_list']);
        }
        return $series_list;
    }

    /**
     * @车型列表
     * http://api.che300.com/service/getCarModelList
     * @param seriesId unsigned int	 车系标识，可以通过车300车系数据接口拿回车系信息，从而提前车系标识。
     * @return array
     */
    public function getCarModelList($seriesId){
        //拉取服务器数据
        $model_list=$this->_initCarModelList($seriesId);
        if(empty($model_list)){
            $this->param['seriesId']=$seriesId;
            $paramStr = createLinkstringUrlencode($this->param);
            $url      = $this->apiUrl.'getCarModelList?'.$paramStr;
            $result   = https_request($url);
            $result   = json_decode($result,true);
            $model_list = json_encode($result['model_list']);
        }
        return $model_list;
    }

    /**
     * @指定车源的详细信息
     * http://api.che300.com/service/getCarDetail
     * @param key	 必须	 string	 车源的编号，如：8423
     * @return array
     */
    public function getCarDetail($key){
        $this->param['key']=$key;
        $paramStr = createLinkstringUrlencode($this->param);
        $url = $this->apiUrl.'getCarDetail?'.$paramStr;
        $result = https_request($url);
        $result=json_decode($result,true);
        return $result;
    }





    /**
     * @车型识别
     * http://api.che300.com/service/identifyModel
     * @param vin 17位VIN码（车辆识别代码/车架号）
     * @param brand    string	 品牌名称，是合作伙伴所使用的品牌名称，如：雪佛兰。
     * @param series   string	车系名称，是合作伙伴所使用的车系名称，如：赛欧三厢。
     * @param model    string	 车型名称，是合作伙伴所使用的车型名称，如：1.4L MT 幸福版。
     * @param modelYear unsigned int	 车型年款，是指定车型的年款，如：2011。
     * @param modelPrice double	 新车指导价，可选参数，最好提供可以增加识别的成功几率和准确性，如：7.23。
     * @return array
     * @example $data = array('vin'=>'LFV4A24FX93088505','brand'=>'','series'=>'','model'=>'','modelYear'=>'','modelPrice'=>'');
     */
    public function identifyModel($data){
        $this->param['vin'] =$data['vin'];
        $this->param['brand']=$data['brand'];
        $this->param['series']=$data['series'];
        $this->param['model']=$data['model'];
        $this->param['modelYear']=$data['modelYear'];
        $this->param['modelPrice']=$data['modelPrice'];
        $this->param=array_filter($this->param);
        $paramStr = createLinkstringUrlencode($this->param);
        $url = $this->apiUrl.'identifyModel?'.$paramStr;
        $result = https_request($url);
        if(empty($result)){
            $result = array('status'=>false,'msg'=>'无法获取che300数据');
        }else{
            $result=json_decode($result,true);
        }
        return $result;
    }

    /**
     * @精确估值
     * http://api.che300.com/service/getUsedCarPrice
     * @param modelId unsigned int	 车型标识，可以通过车300车型列表数据接口拿回车300所支持的所有车型相关信息，也可以申请合作成功之后提供网站自己的车型信息进行两者之间的映射。
     * @param regDate string	待估车辆的上牌时间（格式：yyyy-MM）。
     * @param mile double	 待估车辆的公里数，单位万公里。
     * @param zone unsigned int	 城市标识，可以通过车300城市列表数据接口拿回车300所支持的所有城市相关信息，也可以申请合作成功之后提供网站自己的城市信息进行两者之间的映射。
     * @return array
     * @example $data = array('modelId'=>'24712','regDate'=>'2015-07','mile'=>'3.5','zone'=>'22');
     */
    public function getUsedCarPrice($data){
        $this->param['modelId']=$data['modelId'];
        $this->param['regDate']=$data['regDate'];
        $this->param['mile']=$data['mile'];
        $this->param['zone']=$data['zone'];
        $this->param=array_filter($this->param);
        $paramStr = createLinkstringUrlencode($this->param);
        $url = $this->apiUrl.'getUsedCarPrice?'.$paramStr;
        $result = https_request($url);
        if(empty($result)){
            $result = array('status'=>false,'msg'=>'无法获取che300数据');
        }else{
            $result=json_decode($result,true);
        }
        return $result;
    }

    /**
     * @获取详细估值分析数据
     * http://api.che300.com/service/tuteng/getUsedCarPriceAnalysis
     * @param modelId	车型ID	必须
     * @param regDate	车辆上牌日期，如2012-01
     * @param mile	车辆行驶里程，单位是万公里
     * @param zone 城市ID
     * @param color 车辆颜色（中文），颜色列表：米色，棕色，金色，紫色，巧克力色，黑色，蓝色，灰色，绿色，红色，橙色，白色，香槟色，银色，黄色
     * @param interior 内饰状况（中文），可选列表：优、良、中、差
     * @param surface 漆面状况（中文），可选列表：优、良、中、差
     * @param work_state 工况状况（中文），可选列表：优、良、中、差
     * @example:$data=array('modelId'=>'','regDate'=>'','mile'=>'','zone'=>'','color'=>'','interior'=>'','surface'=>'','work_state'=>'');
     * @return array
     */
    public function getUsedCarPriceAnalysis($data){
        $this->param['modelId']=$data['modelId'];
        $this->param['regDate']=$data['regDate'];
        $this->param['mile']=$data['mile'];
        $this->param['zone']=$data['zone'];
        $this->param['color']=$data['color'];
        $this->param['interior']=$data['interior'];
        $this->param['surface']=$data['surface'];
        $this->param['work_state']=$data['work_state'];
        $this->param=array_filter($this->param);
        $paramStr = createLinkstringUrlencode($this->param);
        $url = $this->apiUrl.'tuteng/getUsedCarPriceAnalysis?'.$paramStr;
        $result = https_request($url);
        if(empty($result)){
            $result = array('status'=>false,'msg'=>'无法获取che300数据');
        }else{
            $result=json_decode($result,true);
        }
        return $result;
    }





    /**
     * @车源列表
     * http://api.che300.com/service/getCarList
     * @param zone unsigned int	 城市标识，可以通过车300城市数据接口拿回车300所支持的所有城市标识，也可以申请合作成功之后提供网站自己的城市信息进行两者之间的映射。
     * @param page unsigned int	 车源列表页码，也就是需要拿第几页车源，每页最多会有20条车源，如果不足20条会全部返回。如果不给的话，默认为1。
     * @param keyword string	 搜索关键字，根据改关键字进行搜索提供车源，如：宝马X1。
     * @param carBrand unsigned int	 品牌ID，可以通过车300的品牌数据接口拿回所有的品牌相关信息。
     * @param carSeries		 unsigned int	 车系ID，可以通过车300的车系数据接口拿回指定品牌下的所有车系相关信息。
     * @param carModel		 unsigned int	 车型ID，可以通过车300的车型数据接口拿回指定车系下的所有车型相关信息。
     * @param carYear		 string	 车龄，既可以指定一个区间（如：3-5），也可以指定一个具体的上牌年份（如：2009）。
     * @param carMile		 string	 车辆里程，既可以指定一个区间（如：3-5），也可以指定一个具体的公里数（如：10），该参数表达的意思是大于等于多少万公里。
     * @param carPrice		 string	 车辆价格，既可以指定一个区间（如：3-5），也可以指定一个具体的数目（如：10），该参数表达的意思是大于等于多少万元。
     * @param sellerType	 string	 卖家类型，1表示个人，2表示商家，不指定的话返回结果就是个人和商家混合车源。
     * @param vprSort		 string	 性价比排序，指定返回的车源按照性价比排序。可以取值asc和desc，其中asc表示升序，desc表示降序。该排序和其它排序方式一样，只会有一种生效，在调用接口的时候只需要指定一种排序即可。如果不指定的话，默认会是下面的发布时间排序。
     * @param priceSort		 string	 价格排序，指定返回的车源按照价格排序。可以取值asc和desc，其中asc表示升序，desc表示降序。该排序和其它排序方式一样，只会有一种生效，在调用接口的时候只需要指定一种排序即可。如果不指定的话，默认会是下面的发布时间排序。
     * @param registerDateSort		 string	 上牌时间排序，指定返回的车源按照上牌时间排序。可以取值asc和desc，其中asc表示升序，desc表示降序。该排序和其它排序方式一样，只会有一种生效，在调用接口的时候只需要指定一种排序即可。如果不指定的话，默认会是下面的发布时间排序。
     * @param mileAgeSort		 string	 车辆里程排序，指定返回的车源按照车辆里程排序。可以取值asc和desc，其中asc表示升序，desc表示降序。该排序和其它排序方式一样，只会有一种生效，在调用接口的时候只需要指定一种排序即可。如果不指定的话，默认会是下面的发布时间排序。
     * @param postDateSort		 string	 发布时间排序，指定返回的车源按照发布时间排序。可以取值asc和desc，其中asc表示升序，desc表示降序。该排序和其它排序方式一样，只会有一种生效，在调用接口的时候只需要指定一种排序即可。如果不指定的话，默认会是下面的发布时间排序。
     * @return array
     * @example:$data=array('zone'=>'','page'=>'','keyword'=>'','carBrand'=>'','carSeries'=>'','carModel'=>'', 'carYear'=>'','carMile'=>'', 'carPrice'=>'','sellerType'=>'', 'vprSort'=>'', 'priceSort'=>'', 'registerDateSort'=>'','mileAgeSort'=>'','postDateSort'=>'');
     */
    public function getCarList($data){
        $this->param['zone']=$data['zone'];
        $this->param['page']=$data['page'];
        $this->param['keyword']=$data['keyword'];
        $this->param['carBrand']=$data['carBrand'];
        $this->param['carSeries']=$data['carSeries'];
        $this->param['carModel']=$data['carModel'];
        $this->param['carYear']=$data['carYear'];
        $this->param['carMile']=$data['carMile'];
        $this->param['carPrice']=$data['carPrice'];
        $this->param['sellerType']=$data['sellerType'];
        $this->param['vprSort']=$data['vprSort'];
        $this->param['priceSort']=$data['priceSort'];
        $this->param['registerDateSort']=$data['registerDateSort'];
        $this->param['mileAgeSort']=$data['mileAgeSort'];
        $this->param['postDateSort']=$data['postDateSort'];
        $this->param=array_filter($this->param);
        $paramStr = createLinkstringUrlencode($this->param);
        $url = $this->apiUrl.'getCarList?'.$paramStr;
        $result = https_request($url);
        if(empty($result)){
            $result = array('status'=>false,'msg'=>'无法获取che300数据');
        }else{
            $result=json_decode($result,true);
        }
        return $result;
    }

    /**
     * @验证车牌的合法性：
     */
    public function validate_plate($plate_no){
        $this->CI->load->config('car_city');
        $plate_city_code = $this->CI->config->item('region');
        if(preg_match('/[\x80-\xff][A-Z][a-z0-9]{5}/i',$plate_no) && in_array(mb_substr($plate_no,0,1),$plate_city_code)){
            return true;
        }
        return false;
    }







}